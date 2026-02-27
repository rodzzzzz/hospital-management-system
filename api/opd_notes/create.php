<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../cashier/create_consultation_charge.php';

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

    $noteText = trim((string)($data['note_text'] ?? ''));
    if ($noteText === '') {
        json_response(['ok' => false, 'error' => 'Missing note_text'], 400);
    }

    $pdo = db();
    ensure_opd_notes_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    $isDoctor = auth_user_has_module($authUser, 'DOCTOR');
    $isOpd = auth_user_has_module($authUser, 'OPD');
    if (!$isAdmin && !$isDoctor && !$isOpd) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $apptStmt = $pdo->prepare('SELECT id, patient_id, doctor_name FROM opd_appointments WHERE id = :id LIMIT 1');
    $apptStmt->execute(['id' => $appointmentId]);
    $appt = $apptStmt->fetch();
    if (!$appt) {
        json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
    }

    $userFullName = trim((string)($authUser['full_name'] ?? ''));
    $apptDoctorName = trim((string)($appt['doctor_name'] ?? ''));
    if (!$isAdmin && $isDoctor && $apptDoctorName !== '') {
        $authUserId = (int)($authUser['id'] ?? 0);
        $apptDoctorUserId = null;

        try {
            $docStmt = $pdo->prepare(
                "SELECT u.id
                 FROM users u
                 JOIN user_roles ur ON ur.user_id = u.id
                 WHERE u.full_name = :full_name AND ur.module = 'DOCTOR'
                 LIMIT 1"
            );
            $docStmt->execute(['full_name' => $apptDoctorName]);
            $docRow = $docStmt->fetch();
            if ($docRow && isset($docRow['id'])) {
                $x = (int)$docRow['id'];
                if ($x > 0) {
                    $apptDoctorUserId = $x;
                }
            }
        } catch (Throwable $e) {
        }

        $ownsAppointment = false;
        if ($apptDoctorUserId === null) {
            $ownsAppointment = true;
        } else if ($authUserId > 0) {
            $ownsAppointment = ($authUserId === $apptDoctorUserId);
        } else if ($userFullName !== '') {
            $normUser = strtolower(preg_replace('/\\s+/', ' ', $userFullName));
            $normAppt = strtolower(preg_replace('/\\s+/', ' ', $apptDoctorName));
            $ownsAppointment = ($normUser === $normAppt);
        }

        if (!$ownsAppointment) {
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }
    }

    $patientId = (int)($appt['patient_id'] ?? 0);
    if ($patientId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid patient'], 400);
    }

    $doctorUserId = (int)($authUser['id'] ?? 0);
    if ($doctorUserId <= 0) {
        $doctorUserId = null;
    }

    $doctorName = $userFullName !== '' ? $userFullName : null;

    $ins = $pdo->prepare(
        'INSERT INTO opd_consultation_notes (appointment_id, patient_id, doctor_user_id, doctor_name, note_text) VALUES (:appointment_id, :patient_id, :doctor_user_id, :doctor_name, :note_text)'
    );
    $ins->execute([
        'appointment_id' => $appointmentId,
        'patient_id' => $patientId,
        'doctor_user_id' => $doctorUserId,
        'doctor_name' => $doctorName,
        'note_text' => $noteText,
    ]);

    $id = (int)$pdo->lastInsertId();

    $get = $pdo->prepare(
        'SELECT n.*, p.patient_code, p.full_name
         FROM opd_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         WHERE n.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    // Create automatic consultation charge
    try {
        $chargeId = create_consultation_charge($pdo, $patientId, 'opd_consultation', $id);
        if ($chargeId) {
            $row['charge_id'] = $chargeId;
        }
    } catch (Throwable $e) {
        // Log error but don't fail the note creation
        error_log('Failed to create consultation charge: ' . $e->getMessage());
    }

    json_response(['ok' => true, 'note' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
