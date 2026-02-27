<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/../doctor/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientIdRaw = $data['patient_id'] ?? null;
    if (!is_int($patientIdRaw) && !(is_string($patientIdRaw) && ctype_digit($patientIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientIdRaw;

    $doctorName = trim((string)($data['doctor_name'] ?? ''));
    if ($doctorName === '') {
        json_response(['ok' => false, 'error' => 'Missing doctor_name'], 400);
    }

    $notes = trim((string)($data['notes'] ?? ''));
    $notes = ($notes !== '') ? $notes : null;

    $nursingAssessmentId = null;
    $nursingAssessmentIdRaw = $data['nursing_assessment_id'] ?? null;
    if ($nursingAssessmentIdRaw !== null && $nursingAssessmentIdRaw !== '') {
        if (!is_int($nursingAssessmentIdRaw) && !(is_string($nursingAssessmentIdRaw) && ctype_digit((string)$nursingAssessmentIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid nursing_assessment_id'], 400);
        }
        $nursingAssessmentId = (int)$nursingAssessmentIdRaw;
        if ($nursingAssessmentId <= 0) {
            $nursingAssessmentId = null;
        }
    }

    $status = strtolower(trim((string)($data['status'] ?? 'requested')));
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

    if ($status === 'requested') {
        $appointmentAt = null;
    }

    $pdo = db();
    ensure_opd_tables($pdo);
    ensure_users_tables($pdo);
    ensure_doctor_tables($pdo);

    $stmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    $docStmt = $pdo->prepare(
        "SELECT u.id, da.status AS availability_status
         FROM users u
         JOIN user_roles ur ON ur.user_id = u.id
         LEFT JOIN doctor_availability da ON da.user_id = u.id
         WHERE u.full_name = :full_name AND u.status = 'active' AND ur.module = 'DOCTOR'
         LIMIT 1"
    );
    $docStmt->execute(['full_name' => $doctorName]);
    $doc = $docStmt->fetch();
    if ($doc) {
        $docId = (int)($doc['id'] ?? 0);
        if ($docId > 0) {
            $pdo->prepare('INSERT INTO doctor_availability (user_id) VALUES (:user_id) ON DUPLICATE KEY UPDATE user_id = user_id')
                ->execute(['user_id' => $docId]);

            $docStatus = strtolower((string)($doc['availability_status'] ?? 'available'));
            if (in_array($docStatus, ['busy', 'on_leave'], true)) {
                json_response([
                    'ok' => false,
                    'error' => 'Doctor is currently ' . $docStatus . '. Please select another doctor.',
                ], 409);
            }
        }
    }

    if ($appointmentAt !== null && $status === 'scheduled') {
        $dt = DateTime::createFromFormat('Y-m-d H:i:s', $appointmentAt);
        if ($dt instanceof DateTime) {
            $start = (clone $dt)->modify('-29 minutes')->format('Y-m-d H:i:s');
            $end = (clone $dt)->modify('+29 minutes')->format('Y-m-d H:i:s');

            $confStmt = $pdo->prepare(
                "SELECT COUNT(*) AS c
                 FROM opd_appointments
                 WHERE doctor_name = :doctor_name
                   AND appointment_at IS NOT NULL
                   AND appointment_at BETWEEN :start AND :end
                   AND status IN ('scheduled','waiting','checked_in','in_consultation')"
            );
            $confStmt->execute([
                'doctor_name' => $doctorName,
                'start' => $start,
                'end' => $end,
            ]);
            $conf = (int)($confStmt->fetchColumn() ?: 0);
            if ($conf > 0) {
                json_response([
                    'ok' => false,
                    'error' => 'Doctor already has an appointment around this time. Please choose another slot.',
                ], 409);
            }
        }
    }

    $ins = $pdo->prepare(
        'INSERT INTO opd_appointments (patient_id, doctor_name, appointment_at, status, notes, nursing_assessment_id) VALUES (:patient_id, :doctor_name, :appointment_at, :status, :notes, :nursing_assessment_id)'
    );
    $ins->execute([
        'patient_id' => $patientId,
        'doctor_name' => $doctorName,
        'appointment_at' => $appointmentAt,
        'status' => $status,
        'notes' => $notes,
        'nursing_assessment_id' => $nursingAssessmentId,
    ]);

    $id = (int)$pdo->lastInsertId();

    $get = $pdo->prepare(
        'SELECT a.*, p.patient_code, p.full_name
         FROM opd_appointments a
         JOIN patients p ON p.id = a.patient_id
         WHERE a.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    json_response([
        'ok' => true,
        'appointment' => $row,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
