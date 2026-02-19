<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../pharmacy/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $chargeId = $data['charge_id'] ?? null;
    if (!is_int($chargeId) && !(is_string($chargeId) && ctype_digit($chargeId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid charge_id'], 400);
    }
    $chargeId = (int)$chargeId;

    $pdo = db();
    ensure_cashier_tables($pdo);
    ensure_pharmacy_tables($pdo);
    ensure_price_master_tables($pdo);

    $pdo->beginTransaction();

    $cStmt = $pdo->prepare('SELECT id, patient_id, encounter_id, status FROM cashier_charges WHERE id = :id LIMIT 1');
    $cStmt->execute(['id' => $chargeId]);
    $charge = $cStmt->fetch();
    if (!$charge) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Charge not found'], 404);
    }

    if ((string)($charge['status'] ?? '') !== 'pending_invoice') {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Charge is not pending invoice'], 400);
    }

    $items = [];
    $total = 0.0;
    $invoiceItems = [];

    $isConsult = false;
    if (isset($charge['id'])) {
        try {
            $srcStmt = $pdo->prepare('SELECT source_module FROM cashier_charges WHERE id = :id LIMIT 1');
            $srcStmt->execute(['id' => $chargeId]);
            $srcRow = $srcStmt->fetch();
            $src = strtolower(trim((string)($srcRow['source_module'] ?? '')));
            $isConsult = in_array($src, ['opd_consultation', 'er_consultation'], true);
        } catch (Throwable $e) {
            $isConsult = false;
        }
    }

    if ($isConsult) {
        $feeStmt = $pdo->prepare('SELECT fee_name, price FROM opd_fees WHERE fee_code = :code LIMIT 1');
        $feeStmt->execute(['code' => 'consultation']);
        $fee = $feeStmt->fetch();
        if (!$fee) {
            $pdo->rollBack();
            json_response(['ok' => false, 'error' => 'Missing OPD fee: consultation. Please set it in Price Master → OPD Fees.'], 400);
        }

        $feeName = trim((string)($fee['fee_name'] ?? 'OPD Consultation'));
        if ($feeName === '') $feeName = 'OPD Consultation';
        $price = (float)($fee['price'] ?? 0);
        if ($price < 0) $price = 0;

        $total = $price;
        $invoiceItems[] = [
            'medicine_id' => null,
            'description' => $feeName,
            'qty' => 1,
            'unit_price' => $price,
            'subtotal' => $price,
        ];
    } else {
        $itemsStmt = $pdo->prepare(
            'SELECT i.medicine_id, i.medicine_name, i.qty, m.price FROM cashier_charge_items i LEFT JOIN pharmacy_medicines m ON m.id = i.medicine_id WHERE i.charge_id = :id ORDER BY i.id ASC'
        );
        $itemsStmt->execute(['id' => $chargeId]);
        $items = $itemsStmt->fetchAll();
        if (!is_array($items) || count($items) === 0) {
            $pdo->rollBack();
            json_response(['ok' => false, 'error' => 'Charge has no items'], 400);
        }

        $resolveByNameStmt = $pdo->prepare(
            'SELECT id, price FROM pharmacy_medicines WHERE LOWER(name) = LOWER(:name) ORDER BY id DESC LIMIT 1'
        );

        foreach ($items as $it) {
            $mid = $it['medicine_id'] ?? null;
            $priceRaw = $it['price'] ?? null;
            if (($mid === null || $mid === '' || !ctype_digit((string)$mid)) || $priceRaw === null || $priceRaw === '') {
                $name = trim((string)($it['medicine_name'] ?? ''));
                if ($name !== '') {
                    $resolveByNameStmt->execute(['name' => $name]);
                    $m = $resolveByNameStmt->fetch();
                    if ($m) {
                        if ($mid === null || $mid === '' || !ctype_digit((string)$mid)) {
                            $mid = $m['id'] ?? null;
                        }
                        if ($priceRaw === null || $priceRaw === '') {
                            $priceRaw = $m['price'] ?? null;
                        }
                    }
                }
            }

            if ($mid === null || $mid === '' || !ctype_digit((string)$mid)) {
                $pdo->rollBack();
                json_response(['ok' => false, 'error' => 'Charge item is missing medicine_id. Please re-create the resit using autocomplete selection.'], 400);
            }

            if ($priceRaw === null || $priceRaw === '') {
                $pdo->rollBack();
                json_response(['ok' => false, 'error' => 'Medicine price missing. Please set medicine price in Pharmacy → Medicines.'], 400);
            }

            $qty = (int)($it['qty'] ?? 0);
            if ($qty < 1) $qty = 1;

            $price = (float)$priceRaw;
            $subtotal = $qty * $price;
            $total += $subtotal;

            $invoiceItems[] = [
                'medicine_id' => (int)$mid,
                'description' => (string)($it['medicine_name'] ?? ''),
                'qty' => $qty,
                'unit_price' => $price,
                'subtotal' => $subtotal,
            ];
        }
    }

    $billingEncounterId = null;
    if (isset($charge['encounter_id']) && $charge['encounter_id'] !== '' && $charge['encounter_id'] !== null) {
        $billingEncounterId = (int)$charge['encounter_id'];
        if ($billingEncounterId <= 0) $billingEncounterId = null;
    }
    if ($billingEncounterId === null) {
        $billingEncounterId = create_encounter($pdo, (int)$charge['patient_id'], 'OPD');
        $pdo->prepare('UPDATE cashier_charges SET encounter_id = :encounter_id WHERE id = :id')
            ->execute(['encounter_id' => $billingEncounterId, 'id' => $chargeId]);
    }

    $insInvoice = $pdo->prepare(
        'INSERT INTO cashier_invoices (charge_id, patient_id, encounter_id, status, total) VALUES (:charge_id, :patient_id, :encounter_id, :status, :total)'
    );
    $insInvoice->execute([
        'charge_id' => $chargeId,
        'patient_id' => (int)$charge['patient_id'],
        'encounter_id' => $billingEncounterId,
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
            'medicine_id' => $it['medicine_id'],
            'description' => $it['description'],
            'qty' => $it['qty'],
            'unit_price' => number_format((float)$it['unit_price'], 2, '.', ''),
            'subtotal' => number_format((float)$it['subtotal'], 2, '.', ''),
        ]);
    }

    $pdo->prepare("UPDATE cashier_charges SET status = 'invoiced' WHERE id = :id")
        ->execute(['id' => $chargeId]);

    $pdo->commit();

    json_response(['ok' => true, 'invoice_id' => $invoiceId]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
