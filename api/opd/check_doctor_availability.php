<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/../doctor/_tables.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $doctorIdRaw = $_GET['doctor_id'] ?? null;
    if (!is_int($doctorIdRaw) && !(is_string($doctorIdRaw) && ctype_digit((string)$doctorIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid doctor_id'], 400);
    }
    $doctorId = (int)$doctorIdRaw;
    if ($doctorId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid doctor_id'], 400);
    }

    $appointmentAtRaw = trim((string)($_GET['appointment_at'] ?? ''));
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
        $appointmentAt = $dt;
    }

    $pdo = db();
    ensure_users_tables($pdo);
    ensure_doctor_tables($pdo);
    ensure_opd_tables($pdo);

    $docStmt = $pdo->prepare(
        "SELECT u.id, u.username, u.full_name
         FROM users u
         JOIN user_roles ur ON ur.user_id = u.id
         WHERE u.id = :id AND u.status = 'active' AND ur.module = 'DOCTOR'
         LIMIT 1"
    );
    $docStmt->execute(['id' => $doctorId]);
    $doc = $docStmt->fetch();
    if (!$doc) {
        json_response(['ok' => false, 'error' => 'Doctor not found'], 404);
    }

    $pdo->prepare('INSERT INTO doctor_availability (user_id) VALUES (:user_id) ON DUPLICATE KEY UPDATE user_id = user_id')
        ->execute(['user_id' => $doctorId]);

    $avStmt = $pdo->prepare('SELECT status, updated_at FROM doctor_availability WHERE user_id = :user_id LIMIT 1');
    $avStmt->execute(['user_id' => $doctorId]);
    $av = $avStmt->fetch();

    $availabilityStatus = strtolower((string)($av['status'] ?? 'available'));
    $availabilityUpdatedAt = $av['updated_at'] ?? null;

    $conflictCount = 0;
    $hasConflict = false;

    if ($appointmentAt instanceof DateTime) {
        $start = (clone $appointmentAt)->modify('-29 minutes')->format('Y-m-d H:i:s');
        $end = (clone $appointmentAt)->modify('+29 minutes')->format('Y-m-d H:i:s');

        $confStmt = $pdo->prepare(
            "SELECT COUNT(*) AS c
             FROM opd_appointments
             WHERE doctor_name = :doctor_name
               AND appointment_at IS NOT NULL
               AND appointment_at BETWEEN :start AND :end
               AND status IN ('scheduled','waiting','checked_in','in_consultation')"
        );
        $confStmt->execute([
            'doctor_name' => (string)($doc['full_name'] ?? ''),
            'start' => $start,
            'end' => $end,
        ]);
        $conflictCount = (int)($confStmt->fetchColumn() ?: 0);
        $hasConflict = $conflictCount > 0;
    }

    $effectiveAvailable = true;
    $reason = null;

    if ($availabilityStatus === 'on_leave') {
        $effectiveAvailable = false;
        $reason = 'Doctor on leave';
    } elseif ($availabilityStatus === 'busy') {
        $effectiveAvailable = false;
        $reason = 'Doctor marked busy';
    } elseif ($hasConflict) {
        $effectiveAvailable = false;
        $reason = 'Doctor already booked for this time';
    }

    json_response([
        'ok' => true,
        'doctor' => [
            'id' => (int)($doc['id'] ?? 0),
            'username' => (string)($doc['username'] ?? ''),
            'full_name' => (string)($doc['full_name'] ?? ''),
        ],
        'availability' => [
            'status' => $availabilityStatus,
            'updated_at' => $availabilityUpdatedAt,
        ],
        'slot' => [
            'has_conflict' => $hasConflict,
            'conflict_count' => $conflictCount,
            'appointment_at' => $appointmentAt ? $appointmentAt->format('Y-m-d H:i:s') : null,
        ],
        'effective_available' => $effectiveAvailable,
        'reason' => $reason,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
