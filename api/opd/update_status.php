<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../encounters/_tables.php';
require_once __DIR__ . '/../cashier/_tables.php';
require_once __DIR__ . '/../price_master/_tables.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../doctor/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $idRaw = $data['id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit($idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$idRaw;

    $status = strtolower(trim((string)($data['status'] ?? '')));
    if (!in_array($status, ['requested', 'scheduled', 'waiting', 'checked_in', 'in_consultation', 'completed', 'cancelled', 'no_show', 'rejected'], true)) {
        json_response(['ok' => false, 'error' => 'Invalid status'], 400);
    }

    $appointmentAtRaw = trim((string)($data['appointment_at'] ?? ''));
    if ($appointmentAtRaw === '') {
        $date = trim((string)($data['date'] ?? ''));
        $time = trim((string)($data['time'] ?? ''));
        if ($date !== '' && $time !== '') {
            $appointmentAtRaw = $date . ' ' . $time;
        }
    }

    $appointmentAt = null;
    if ($appointmentAtRaw !== '') {
        $dt = null;
        $formats = ['Y-m-d H:i:s', 'Y-m-d H:i', DATE_ATOM];
        foreach ($formats as $fmt) {
            $x = DateTime::createFromFormat($fmt, $appointmentAtRaw);
            if ($x instanceof DateTime) {
                $dt = $x;
                break;
            }
        }
        if (!$dt) {
            $x = strtotime($appointmentAtRaw);
            if ($x !== false) {
                $dt = (new DateTime())->setTimestamp($x);
            }
        }
        if (!$dt) {
            json_response(['ok' => false, 'error' => 'Invalid appointment_at'], 400);
        }
        $appointmentAt = $dt->format('Y-m-d H:i:s');
    }

    if ($status === 'scheduled' && $appointmentAt === null) {
        json_response(['ok' => false, 'error' => 'Missing appointment_at'], 400);
    }

    $labTestsJson = null;
    $labTestsRaw = $data['lab_tests'] ?? null;
    if ($labTestsRaw !== null) {
        if (!is_array($labTestsRaw)) {
            json_response(['ok' => false, 'error' => 'Invalid lab_tests'], 400);
        }
        $clean = [];
        foreach ($labTestsRaw as $x) {
            if (!is_string($x) && !is_int($x)) continue;
            $s = strtolower(trim((string)$x));
            if ($s === '') continue;
            if (!preg_match('/^[a-z0-9_\-]+$/', $s)) continue;
            $clean[] = $s;
        }
        $clean = array_values(array_unique($clean));
        if (count($clean) > 50) {
            $clean = array_slice($clean, 0, 50);
        }
        $labTestsJson = json_encode($clean, JSON_UNESCAPED_SLASHES);
        if ($labTestsJson === false) {
            $labTestsJson = null;
        }
    }

    $labNote = null;
    $labNoteRaw = $data['lab_note'] ?? null;
    if ($labNoteRaw !== null) {
        if (!is_string($labNoteRaw) && !is_int($labNoteRaw) && !is_float($labNoteRaw)) {
            json_response(['ok' => false, 'error' => 'Invalid lab_note'], 400);
        }
        $labNote = trim((string)$labNoteRaw);
        if ($labNote === '') $labNote = null;
    }

    $pdo = db();
    ensure_opd_tables($pdo);

    ensure_cashier_tables($pdo);
    ensure_price_master_tables($pdo);
    ensure_doctor_tables($pdo);
    ensure_users_tables($pdo);

    $safeRollback = static function () use (&$pdo): void {
        if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
    };

    $safeCommit = static function () use (&$pdo): void {
        if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
            $pdo->commit();
        }
    };

    if (!$pdo->beginTransaction()) {
        json_response(['ok' => false, 'error' => 'Failed to start transaction'], 500);
    }

    $apptStmt = $pdo->prepare('SELECT id, patient_id, doctor_name, appointment_at, status FROM opd_appointments WHERE id = :id LIMIT 1');
    $apptStmt->execute(['id' => $id]);
    $existingAppt = $apptStmt->fetch();
    if (!$existingAppt) {
        $safeRollback();
        json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
    }

    if ($status === 'scheduled') {
        $user = auth_current_user($pdo);
        if (!$user) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
        }
        if (!auth_user_has_module($user, 'DOCTOR')) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }

        $userFullName = trim((string)($user['full_name'] ?? ''));
        $apptDoctorName = trim((string)($existingAppt['doctor_name'] ?? ''));
        if ($userFullName === '' || $apptDoctorName === '' || $userFullName !== $apptDoctorName) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }

        $userId = (int)($user['id'] ?? 0);
        if ($userId <= 0) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Invalid user'], 400);
        }

        $pdo->prepare('INSERT INTO doctor_availability (user_id) VALUES (:user_id) ON DUPLICATE KEY UPDATE user_id = user_id')
            ->execute(['user_id' => $userId]);

        $avStmt = $pdo->prepare('SELECT status FROM doctor_availability WHERE user_id = :user_id LIMIT 1');
        $avStmt->execute(['user_id' => $userId]);
        $avRow = $avStmt->fetch();
        $docStatus = strtolower((string)($avRow['status'] ?? 'available'));
        if (in_array($docStatus, ['busy', 'on_leave'], true)) {
            $safeRollback();
            json_response([
                'ok' => false,
                'error' => 'Doctor is currently ' . $docStatus . '. Please choose another time.',
            ], 409);
        }

        if ($appointmentAt !== null) {
            $dt = DateTime::createFromFormat('Y-m-d H:i:s', $appointmentAt);
            if ($dt instanceof DateTime) {
                $start = (clone $dt)->modify('-29 minutes')->format('Y-m-d H:i:s');
                $end = (clone $dt)->modify('+29 minutes')->format('Y-m-d H:i:s');

                $confStmt = $pdo->prepare(
                    "SELECT COUNT(*) AS c
                     FROM opd_appointments
                     WHERE doctor_name = :doctor_name
                       AND id <> :id
                       AND appointment_at IS NOT NULL
                       AND appointment_at BETWEEN :start AND :end
                       AND status IN ('scheduled','waiting','checked_in','in_consultation')"
                );
                $confStmt->execute([
                    'doctor_name' => $apptDoctorName,
                    'id' => $id,
                    'start' => $start,
                    'end' => $end,
                ]);
                $conf = (int)($confStmt->fetchColumn() ?: 0);
                if ($conf > 0) {
                    $safeRollback();
                    json_response([
                        'ok' => false,
                        'error' => 'You already have an appointment around this time. Please choose another slot.',
                    ], 409);
                }
            }
        }
    }

    $set = ['status = :status'];
    $params = ['status' => $status, 'id' => $id];

    if ($status === 'scheduled') {
        $params['approved_by_user_id'] = $userId;
        $set[] = 'approved_by_user_id = :approved_by_user_id';
    }

    if ($appointmentAt !== null) {
        $set[] = 'appointment_at = :appointment_at';
        $params['appointment_at'] = $appointmentAt;
    }

    if ($status === 'scheduled') {
        if ($labTestsJson !== null) {
            $set[] = 'lab_tests_json = :lab_tests_json';
            $params['lab_tests_json'] = $labTestsJson;
        }
        if ($labNote !== null) {
            $set[] = 'lab_note = :lab_note';
            $params['lab_note'] = $labNote;
        }
    }

    if (in_array($status, ['scheduled', 'rejected'], true)) {
        $set[] = 'responded_at = CURRENT_TIMESTAMP';
    }

    $sql = 'UPDATE opd_appointments SET ' . implode(', ', $set) . ' WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    if ($stmt->rowCount() === 0) {
        $chk = $pdo->prepare('SELECT id FROM opd_appointments WHERE id = :id LIMIT 1');
        $chk->execute(['id' => $id]);
        if (!$chk->fetch()) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
        }
    }

    $consultationInvoiceId = null;
    if ($status === 'completed') {
        $feeStmt = $pdo->prepare('SELECT fee_name, price FROM opd_fees WHERE fee_code = :code LIMIT 1');
        $feeStmt->execute(['code' => 'consultation']);
        $fee = $feeStmt->fetch();
        if (!$fee) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Missing OPD fee: consultation. Please set it in Price Master → OPD Fees.'], 400);
        }

        $feeName = trim((string)($fee['fee_name'] ?? 'OPD Consultation'));
        if ($feeName === '') $feeName = 'OPD Consultation';

        $price = (float)($fee['price'] ?? 0);
        if ($price < 0) $price = 0;

        $patientId = (int)($existingAppt['patient_id'] ?? 0);
        if ($patientId <= 0) {
            $safeRollback();
            json_response(['ok' => false, 'error' => 'Invalid patient'], 400);
        }

        $encounterId = resolve_encounter_id($pdo, $patientId, null, 'OPD');

        $chargeStmt = $pdo->prepare("SELECT id, encounter_id FROM cashier_charges WHERE source_module = 'opd_consultation' AND source_id = :sid LIMIT 1");
        $chargeStmt->execute(['sid' => $id]);
        $chargeRow = $chargeStmt->fetch();

        $chargeId = null;
        if ($chargeRow) {
            $chargeId = (int)($chargeRow['id'] ?? 0);
            if ($chargeId <= 0) $chargeId = null;
        }

        if ($chargeId === null) {
            $insCharge = $pdo->prepare(
                "INSERT INTO cashier_charges (source_module, source_id, patient_id, encounter_id, status)
                 VALUES ('opd_consultation', :source_id, :patient_id, :encounter_id, 'pending_invoice')"
            );
            $insCharge->execute([
                'source_id' => $id,
                'patient_id' => $patientId,
                'encounter_id' => $encounterId,
            ]);
            $chargeId = (int)$pdo->lastInsertId();

            $pdo->prepare(
                'INSERT INTO cashier_charge_items (charge_id, medicine_id, medicine_name, qty, instructions) VALUES (:charge_id, :medicine_id, :medicine_name, :qty, :instructions)'
            )->execute([
                'charge_id' => $chargeId,
                'medicine_id' => null,
                'medicine_name' => $feeName,
                'qty' => 1,
                'instructions' => null,
            ]);
        } else {
            $curEncounterId = $chargeRow['encounter_id'] ?? null;
            if ($curEncounterId === null || $curEncounterId === '' || !ctype_digit((string)$curEncounterId)) {
                $pdo->prepare('UPDATE cashier_charges SET encounter_id = :encounter_id WHERE id = :id')
                    ->execute(['encounter_id' => $encounterId, 'id' => $chargeId]);
            }
        }

        $invStmt = $pdo->prepare('SELECT id, status FROM cashier_invoices WHERE charge_id = :cid ORDER BY id DESC LIMIT 1');
        $invStmt->execute(['cid' => $chargeId]);
        $invRow = $invStmt->fetch();

        $invoiceId = null;
        $invoiceStatus = '';
        if ($invRow) {
            $invoiceId = (int)($invRow['id'] ?? 0);
            if ($invoiceId <= 0) $invoiceId = null;
            $invoiceStatus = strtolower(trim((string)($invRow['status'] ?? '')));
        }

        if ($invoiceId !== null && $invoiceStatus === 'paid') {
            $consultationInvoiceId = $invoiceId;
        } else {
            if ($invoiceId === null) {
                $insInvoice = $pdo->prepare(
                    'INSERT INTO cashier_invoices (charge_id, patient_id, encounter_id, status, total) VALUES (:charge_id, :patient_id, :encounter_id, :status, :total)'
                );
                $insInvoice->execute([
                    'charge_id' => $chargeId,
                    'patient_id' => $patientId,
                    'encounter_id' => $encounterId,
                    'status' => 'unpaid',
                    'total' => number_format($price, 2, '.', ''),
                ]);
                $invoiceId = (int)$pdo->lastInsertId();

                $pdo->prepare(
                    'INSERT INTO cashier_invoice_items (invoice_id, medicine_id, description, qty, unit_price, subtotal) VALUES (:invoice_id, :medicine_id, :description, :qty, :unit_price, :subtotal)'
                )->execute([
                    'invoice_id' => $invoiceId,
                    'medicine_id' => null,
                    'description' => $feeName,
                    'qty' => 1,
                    'unit_price' => number_format($price, 2, '.', ''),
                    'subtotal' => number_format($price, 2, '.', ''),
                ]);
            } else {
                $pdo->prepare('UPDATE cashier_invoices SET total = :total WHERE id = :id')
                    ->execute(['total' => number_format($price, 2, '.', ''), 'id' => $invoiceId]);
                $pdo->prepare('DELETE FROM cashier_invoice_items WHERE invoice_id = :id')
                    ->execute(['id' => $invoiceId]);
                $pdo->prepare(
                    'INSERT INTO cashier_invoice_items (invoice_id, medicine_id, description, qty, unit_price, subtotal) VALUES (:invoice_id, :medicine_id, :description, :qty, :unit_price, :subtotal)'
                )->execute([
                    'invoice_id' => $invoiceId,
                    'medicine_id' => null,
                    'description' => $feeName,
                    'qty' => 1,
                    'unit_price' => number_format($price, 2, '.', ''),
                    'subtotal' => number_format($price, 2, '.', ''),
                ]);
            }

            $consultationInvoiceId = $invoiceId;
        }
    }

    $get = $pdo->prepare(
        'SELECT a.*, p.patient_code, p.full_name, u.full_name AS approved_by_name
         FROM opd_appointments a
         JOIN patients p ON p.id = a.patient_id
         LEFT JOIN users u ON u.id = a.approved_by_user_id
         WHERE a.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    $safeCommit();

    json_response([
        'ok' => true,
        'appointment' => $row,
        'consultation_invoice_id' => $consultationInvoiceId,
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
