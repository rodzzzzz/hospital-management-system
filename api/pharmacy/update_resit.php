<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';
require_once __DIR__ . '/../encounters/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $resitId = $data['resit_id'] ?? null;
    if (!is_int($resitId) && !(is_string($resitId) && ctype_digit($resitId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid resit_id'], 400);
    }
    $resitId = (int)$resitId;

    $prescribedBy = trim((string)($data['prescribed_by'] ?? ''));
    $notes = trim((string)($data['notes'] ?? ''));

    $items = $data['items'] ?? null;
    if (!is_array($items) || count($items) === 0) {
        json_response(['ok' => false, 'error' => 'Missing items'], 400);
    }

    $normalized = [];
    foreach ($items as $it) {
        if (!is_array($it)) continue;
        $name = trim((string)($it['name'] ?? ''));
        $medicineIdRaw = $it['medicine_id'] ?? null;
        $medicineId = null;
        if (is_int($medicineIdRaw)) {
            $medicineId = $medicineIdRaw;
        } elseif (is_string($medicineIdRaw) && ctype_digit($medicineIdRaw)) {
            $medicineId = (int)$medicineIdRaw;
        }
        $qty = trim((string)($it['qty'] ?? ''));
        $sig = trim((string)($it['sig'] ?? ''));
        if ($name === '') continue;
        $normalized[] = [
            'name' => $name,
            'medicine_id' => $medicineId,
            'qty' => $qty,
            'sig' => $sig,
        ];
    }

    if (count($normalized) === 0) {
        json_response(['ok' => false, 'error' => 'Invalid items'], 400);
    }

    $pdo = db();
    ensure_pharmacy_tables($pdo);
    ensure_cashier_tables($pdo);

    $pdo->beginTransaction();

    $resolveMedStmt = $pdo->prepare(
        'SELECT id FROM pharmacy_medicines WHERE LOWER(name) = LOWER(:name) ORDER BY id DESC LIMIT 1'
    );

    foreach ($normalized as &$it) {
        if ($it['medicine_id'] === null) {
            $resolveMedStmt->execute(['name' => $it['name']]);
            $m = $resolveMedStmt->fetch();
            if ($m && isset($m['id'])) {
                $it['medicine_id'] = (int)$m['id'];
            }
        }
    }
    unset($it);

    // Update resit header
    $updResit = $pdo->prepare(
        'UPDATE pharmacy_resits SET prescribed_by = :prescribed_by, notes = :notes WHERE id = :id'
    );
    $updResit->execute([
        'prescribed_by' => ($prescribedBy !== '' ? $prescribedBy : null),
        'notes' => ($notes !== '' ? $notes : null),
        'id' => $resitId,
    ]);

    // Delete old items and insert new
    $delItems = $pdo->prepare('DELETE FROM pharmacy_resit_items WHERE resit_id = :resit_id');
    $delItems->execute(['resit_id' => $resitId]);

    $insItem = $pdo->prepare(
        'INSERT INTO pharmacy_resit_items (resit_id, medicine_id, medicine_name, qty, instructions) VALUES (:resit_id, :medicine_id, :medicine_name, :qty, :instructions)'
    );
    foreach ($normalized as $it) {
        $insItem->execute([
            'resit_id' => $resitId,
            'medicine_id' => $it['medicine_id'],
            'medicine_name' => $it['name'],
            'qty' => ($it['qty'] !== '' ? $it['qty'] : null),
            'instructions' => ($it['sig'] !== '' ? $it['sig'] : null),
        ]);
    }

    // Update linked cashier charge and items if exists
    $chargeStmt = $pdo->prepare(
        'SELECT id FROM cashier_charges WHERE source_module = \'pharmacy_resit\' AND source_id = :source_id LIMIT 1'
    );
    $chargeStmt->execute(['source_id' => $resitId]);
    $chargeRow = $chargeStmt->fetch(PDO::FETCH_ASSOC);
    if ($chargeRow) {
        $chargeId = (int)$chargeRow['id'];

        // Delete old charge items
        $delChargeItems = $pdo->prepare('DELETE FROM cashier_charge_items WHERE charge_id = :charge_id');
        $delChargeItems->execute(['charge_id' => $chargeId]);

        // Insert new charge items
        $insChargeItem = $pdo->prepare(
            'INSERT INTO cashier_charge_items (charge_id, medicine_id, medicine_name, qty, instructions) VALUES (:charge_id, :medicine_id, :medicine_name, :qty, :instructions)'
        );
        foreach ($normalized as $it) {
            $qtyInt = 1;
            $qtyRaw = trim((string)($it['qty'] ?? ''));
            if ($qtyRaw !== '' && ctype_digit($qtyRaw)) {
                $qtyInt = (int)$qtyRaw;
                if ($qtyInt < 1) $qtyInt = 1;
            }

            $insChargeItem->execute([
                'charge_id' => $chargeId,
                'medicine_id' => $it['medicine_id'],
                'medicine_name' => $it['name'],
                'qty' => $qtyInt,
                'instructions' => (($it['sig'] ?? '') !== '' ? $it['sig'] : null),
            ]);
        }

        // If invoice already exists, optionally update invoice items (here we keep invoice items as-is; cashier can re-generate)
    }

    $pdo->commit();

    json_response(['ok' => true, 'resit_id' => $resitId]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
