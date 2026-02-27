<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $appointmentIdRaw = $data['appointment_id'] ?? null;
    if (!is_int($appointmentIdRaw) && !(is_string($appointmentIdRaw) && ctype_digit((string)$appointmentIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
    }
    $appointmentId = (int)$appointmentIdRaw;

    $pdo = db();
    ensure_opd_billing_tables($pdo);
    ensure_cashier_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'OPD') && !auth_user_has_module($authUser, 'CASHIER')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $pdo->beginTransaction();

    $apptStmt = $pdo->prepare('SELECT id, patient_id, status FROM opd_appointments WHERE id = :id LIMIT 1 FOR UPDATE');
    $apptStmt->execute(['id' => $appointmentId]);
    $appt = $apptStmt->fetch();
    if (!$appt) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
    }

    $status = strtolower(trim((string)($appt['status'] ?? '')));
    if ($status !== 'completed') {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Appointment is not completed'], 400);
    }

    $patientId = (int)($appt['patient_id'] ?? 0);
    if ($patientId <= 0) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Invalid patient'], 400);
    }

    $itemsStmt = $pdo->prepare(
        'SELECT id, item_type, description, qty, unit_price, (qty * unit_price) AS subtotal
         FROM opd_billing_items
         WHERE appointment_id = :appointment_id
         ORDER BY created_at ASC, id ASC'
    );
    $itemsStmt->execute(['appointment_id' => $appointmentId]);
    $items = $itemsStmt->fetchAll();

    if (!is_array($items) || count($items) === 0) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'No OPD billing items to submit'], 400);
    }

    $chargeStmt = $pdo->prepare("SELECT id FROM cashier_charges WHERE source_module = 'opd_billing_summary' AND source_id = :sid LIMIT 1");
    $chargeStmt->execute(['sid' => $appointmentId]);
    $existingCharge = $chargeStmt->fetch();

    $chargeId = null;
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
        $encounterId = create_encounter($pdo, $patientId, 'OPD');

        $insCharge = $pdo->prepare(
            "INSERT INTO cashier_charges (source_module, source_id, patient_id, encounter_id, status)
             VALUES ('opd_billing_summary', :source_id, :patient_id, :encounter_id, 'pending_invoice')"
        );
        $insCharge->execute([
            'source_id' => $appointmentId,
            'patient_id' => $patientId,
            'encounter_id' => $encounterId,
        ]);
        $chargeId = (int)$pdo->lastInsertId();

        $insChargeItem = $pdo->prepare(
            'INSERT INTO cashier_charge_items (charge_id, medicine_id, medicine_name, qty, instructions) VALUES (:charge_id, :medicine_id, :medicine_name, :qty, :instructions)'
        );
        foreach ($items as $it) {
            $desc = trim((string)($it['description'] ?? ''));
            if ($desc === '') $desc = 'OPD Item';
            $qty = (int)($it['qty'] ?? 1);
            if ($qty < 1) $qty = 1;

            $insChargeItem->execute([
                'charge_id' => $chargeId,
                'medicine_id' => null,
                'medicine_name' => $desc,
                'qty' => $qty,
                'instructions' => null,
            ]);
        }
    }

    if ($invoiceId === null) {
        $encounterId = null;
        $encStmt = $pdo->prepare('SELECT encounter_id FROM cashier_charges WHERE id = :id LIMIT 1');
        $encStmt->execute(['id' => $chargeId]);
        $encRow = $encStmt->fetch();
        if ($encRow && isset($encRow['encounter_id']) && $encRow['encounter_id'] !== null && $encRow['encounter_id'] !== '') {
            $encounterId = (int)$encRow['encounter_id'];
            if ($encounterId <= 0) $encounterId = null;
        }
        if ($encounterId === null) {
            $encounterId = create_encounter($pdo, $patientId, 'OPD');
            $pdo->prepare('UPDATE cashier_charges SET encounter_id = :encounter_id WHERE id = :id')
                ->execute(['encounter_id' => $encounterId, 'id' => $chargeId]);
        }

        $total = 0.0;
        foreach ($items as $it) {
            $total += (float)($it['subtotal'] ?? 0);
        }
        if ($total < 0) $total = 0;

        $insInvoice = $pdo->prepare(
            'INSERT INTO cashier_invoices (charge_id, patient_id, encounter_id, status, total) VALUES (:charge_id, :patient_id, :encounter_id, :status, :total)'
        );
        $insInvoice->execute([
            'charge_id' => $chargeId,
            'patient_id' => $patientId,
            'encounter_id' => $encounterId,
            'status' => 'unpaid',
            'total' => number_format($total, 2, '.', ''),
        ]);
        $invoiceId = (int)$pdo->lastInsertId();

        $insItem = $pdo->prepare(
            'INSERT INTO cashier_invoice_items (invoice_id, medicine_id, description, qty, unit_price, subtotal) VALUES (:invoice_id, :medicine_id, :description, :qty, :unit_price, :subtotal)'
        );
        foreach ($items as $it) {
            $desc = trim((string)($it['description'] ?? ''));
            if ($desc === '') $desc = 'OPD Item';
            $qty = (int)($it['qty'] ?? 1);
            if ($qty < 1) $qty = 1;
            $unit = (float)($it['unit_price'] ?? 0);
            if ($unit < 0) $unit = 0;
            $subtotal = $qty * $unit;

            $insItem->execute([
                'invoice_id' => $invoiceId,
                'medicine_id' => null,
                'description' => $desc,
                'qty' => $qty,
                'unit_price' => number_format($unit, 2, '.', ''),
                'subtotal' => number_format($subtotal, 2, '.', ''),
            ]);
        }
    }

    $pdo->commit();

    json_response(['ok' => true, 'charge_id' => $chargeId, 'invoice_id' => $invoiceId]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
