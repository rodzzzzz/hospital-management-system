<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $requestId = $data['request_id'] ?? null;
    if (!is_int($requestId) && !(is_string($requestId) && ctype_digit($requestId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid request_id'], 400);
    }
    $requestId = (int)$requestId;

    $releasedBy = trim((string)($data['released_by'] ?? ''));
    if ($releasedBy === '') {
        json_response(['ok' => false, 'error' => 'Missing released_by'], 400);
    }

    $results = $data['results'] ?? null;
    if (!is_array($results) || count($results) === 0) {
        json_response(['ok' => false, 'error' => 'Missing results'], 400);
    }

    $normalized = [];
    foreach ($results as $r) {
        if (!is_array($r)) {
            continue;
        }
        $itemId = $r['request_item_id'] ?? null;
        if (!is_int($itemId) && !(is_string($itemId) && ctype_digit($itemId))) {
            continue;
        }
        $itemId = (int)$itemId;
        $resultText = (string)($r['result_text'] ?? '');

        $normalized[$itemId] = $resultText;
    }

    if (count($normalized) === 0) {
        json_response(['ok' => false, 'error' => 'Invalid results'], 400);
    }

    $pdo = db();
    ensure_lab_tables($pdo);
    ensure_cashier_tables($pdo);
    ensure_price_master_tables($pdo);

    $pdo->beginTransaction();

    $reqStmt = $pdo->prepare('SELECT id, patient_id, encounter_id, status FROM lab_requests WHERE id = :id LIMIT 1 FOR UPDATE');
    $reqStmt->execute(['id' => $requestId]);
    $req = $reqStmt->fetch();
    if (!$req) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Request not found'], 404);
    }

    if ((string)($req['status'] ?? '') !== 'in_progress') {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Request is not in progress'], 400);
    }

    $itemStmt = $pdo->prepare('SELECT id, test_code, test_name FROM lab_request_items WHERE request_id = :rid');
    $itemStmt->execute(['rid' => $requestId]);
    $items = $itemStmt->fetchAll();
    $itemIds = array_map(static fn($x) => (int)$x['id'], $items);

    if (count($itemIds) === 0) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'No request items'], 400);
    }

    foreach (array_keys($normalized) as $itemId) {
        if (!in_array($itemId, $itemIds, true)) {
            $pdo->rollBack();
            json_response(['ok' => false, 'error' => 'Result item does not belong to request'], 400);
        }
    }

    $now = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');

    $upsert = $pdo->prepare(
        "INSERT INTO lab_results (request_item_id, result_text, released_by, released_at)
         VALUES (:item_id, :result_text, :released_by, :released_at)
         ON DUPLICATE KEY UPDATE
            result_text = VALUES(result_text),
            released_by = VALUES(released_by),
            released_at = VALUES(released_at)"
    );

    foreach ($normalized as $itemId => $resultText) {
        $upsert->execute([
            'item_id' => $itemId,
            'result_text' => $resultText,
            'released_by' => $releasedBy,
            'released_at' => $now,
        ]);
    }

    $pdo->prepare('UPDATE lab_request_items SET status = \'completed\' WHERE request_id = :id')->execute([
        'id' => $requestId,
    ]);

    $encounterId = null;
    if (isset($req['encounter_id']) && $req['encounter_id'] !== '' && $req['encounter_id'] !== null) {
        $encounterId = (int)$req['encounter_id'];
        if ($encounterId <= 0) $encounterId = null;
    }
    if ($encounterId === null) {
        $encounterId = create_encounter($pdo, (int)($req['patient_id'] ?? 0), 'OPD');
        $pdo->prepare('UPDATE lab_requests SET encounter_id = :encounter_id WHERE id = :id')
            ->execute(['encounter_id' => $encounterId, 'id' => $requestId]);
        $req['encounter_id'] = $encounterId;
    }

    $feeCodes = [];
    foreach ($items as $it) {
        $code = strtolower(trim((string)($it['test_code'] ?? '')));
        if ($code === '') continue;
        $feeCodes[$code] = true;
    }
    $feeCodes = array_keys($feeCodes);
    if (count($feeCodes) === 0) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Missing test codes for billing'], 400);
    }

    $placeholders = implode(',', array_fill(0, count($feeCodes), '?'));
    $feeStmt = $pdo->prepare("SELECT test_code, price FROM lab_test_fees WHERE test_code IN ({$placeholders})");
    $feeStmt->execute($feeCodes);
    $feeRows = $feeStmt->fetchAll();
    $feeByCode = [];
    foreach ($feeRows as $r) {
        $c = strtolower(trim((string)($r['test_code'] ?? '')));
        if ($c === '') continue;
        $feeByCode[$c] = (float)($r['price'] ?? 0);
    }

    foreach ($feeCodes as $c) {
        if (!array_key_exists($c, $feeByCode)) {
            $pdo->rollBack();
            json_response(['ok' => false, 'error' => "Missing lab price for test code: {$c}. Please set it in Price Master → Laboratory Fees."], 400);
        }
    }

    $chargeId = null;
    $chargeStmt = $pdo->prepare("SELECT id FROM cashier_charges WHERE source_module = 'lab_request' AND source_id = :sid LIMIT 1");
    $chargeStmt->execute(['sid' => $requestId]);
    $existingCharge = $chargeStmt->fetch();
    if ($existingCharge) {
        $chargeId = (int)($existingCharge['id'] ?? 0);
        if ($chargeId <= 0) $chargeId = null;
    }

    $invoiceId = null;
    if ($chargeId !== null) {
        $invStmt = $pdo->prepare('SELECT id FROM cashier_invoices WHERE charge_id = :cid ORDER BY id DESC LIMIT 1');
        $invStmt->execute(['cid' => $chargeId]);
        $invRow = $invStmt->fetch();
        if ($invRow) {
            $invoiceId = (int)($invRow['id'] ?? 0);
            if ($invoiceId <= 0) $invoiceId = null;
        }
    }

    if ($chargeId === null) {
        $insCharge = $pdo->prepare(
            "INSERT INTO cashier_charges (source_module, source_id, patient_id, encounter_id, status)
             VALUES ('lab_request', :source_id, :patient_id, :encounter_id, 'pending_invoice')"
        );
        $insCharge->execute([
            'source_id' => $requestId,
            'patient_id' => (int)($req['patient_id'] ?? 0),
            'encounter_id' => $encounterId,
        ]);
        $chargeId = (int)$pdo->lastInsertId();

        $insChargeItem = $pdo->prepare(
            'INSERT INTO cashier_charge_items (charge_id, medicine_id, medicine_name, qty, instructions) VALUES (:charge_id, :medicine_id, :medicine_name, :qty, :instructions)'
        );
        foreach ($items as $it) {
            $insChargeItem->execute([
                'charge_id' => $chargeId,
                'medicine_id' => null,
                'medicine_name' => (string)($it['test_name'] ?? ''),
                'qty' => 1,
                'instructions' => null,
            ]);
        }
    }

    if ($invoiceId === null) {
        $invoiceItems = [];
        $total = 0.0;
        foreach ($items as $it) {
            $code = strtolower(trim((string)($it['test_code'] ?? '')));
            $price = (float)($feeByCode[$code] ?? 0);
            $qty = 1;
            $subtotal = $qty * $price;
            $total += $subtotal;
            $invoiceItems[] = [
                'description' => (string)($it['test_name'] ?? ''),
                'qty' => $qty,
                'unit_price' => $price,
                'subtotal' => $subtotal,
            ];
        }

        $insInvoice = $pdo->prepare(
            'INSERT INTO cashier_invoices (charge_id, patient_id, encounter_id, status, total) VALUES (:charge_id, :patient_id, :encounter_id, :status, :total)'
        );
        $insInvoice->execute([
            'charge_id' => $chargeId,
            'patient_id' => (int)($req['patient_id'] ?? 0),
            'encounter_id' => $encounterId,
            'status' => 'unpaid',
            'total' => number_format($total, 2, '.', ''),
        ]);
        $invoiceId = (int)$pdo->lastInsertId();

        $insItem = $pdo->prepare(
            'INSERT INTO cashier_invoice_items (invoice_id, medicine_id, description, qty, unit_price, subtotal) VALUES (:invoice_id, :medicine_id, :description, :qty, :unit_price, :subtotal)'
        );
        foreach ($invoiceItems as $it) {
            $insItem->execute([
                'invoice_id' => $invoiceId,
                'medicine_id' => null,
                'description' => $it['description'],
                'qty' => $it['qty'],
                'unit_price' => number_format((float)$it['unit_price'], 2, '.', ''),
                'subtotal' => number_format((float)$it['subtotal'], 2, '.', ''),
            ]);
        }
    }

    $pdo->prepare(
        "UPDATE lab_requests
         SET status = 'completed',
             cashier_status = 'submitted_to_cashier'
         WHERE id = :id"
    )->execute(['id' => $requestId]);

    $pdo->commit();

    json_response([
        'ok' => true,
        'released_at' => $now,
        'invoice_id' => $invoiceId,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
