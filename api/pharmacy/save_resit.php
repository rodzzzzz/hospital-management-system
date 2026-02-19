<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';
require_once __DIR__ . '/../encounters/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientId = $data['patient_id'] ?? null;
    if (!is_int($patientId) && !(is_string($patientId) && ctype_digit($patientId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientId;

    $encounterIdRaw = $data['encounter_id'] ?? null;
    $preferredEncounterId = null;
    if (is_int($encounterIdRaw)) {
        $preferredEncounterId = $encounterIdRaw;
    } elseif (is_string($encounterIdRaw) && ctype_digit($encounterIdRaw)) {
        $preferredEncounterId = (int)$encounterIdRaw;
    }

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

    $pStmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $pStmt->execute(['id' => $patientId]);
    $pRow = $pStmt->fetch();
    if (!$pRow) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

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

    $resolvedEncounterId = create_encounter($pdo, $patientId, 'PHARMACY');

    $insResit = $pdo->prepare(
        'INSERT INTO pharmacy_resits (patient_id, encounter_id, prescribed_by, notes) VALUES (:patient_id, :encounter_id, :prescribed_by, :notes)'
    );
    $insResit->execute([
        'patient_id' => $patientId,
        'encounter_id' => $resolvedEncounterId,
        'prescribed_by' => ($prescribedBy !== '' ? $prescribedBy : null),
        'notes' => ($notes !== '' ? $notes : null),
    ]);

    $resitId = (int)$pdo->lastInsertId();

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

    $insCharge = $pdo->prepare(
        "INSERT INTO cashier_charges (source_module, source_id, patient_id, encounter_id, status) VALUES ('pharmacy_resit', :source_id, :patient_id, :encounter_id, 'pending_invoice')"
    );
    $insCharge->execute([
        'source_id' => $resitId,
        'patient_id' => $patientId,
        'encounter_id' => $resolvedEncounterId,
    ]);
    $chargeId = (int)$pdo->lastInsertId();

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

    $pdo->commit();

    json_response([
        'ok' => true,
        'resit_id' => $resitId,
        'charge_id' => $chargeId,
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
