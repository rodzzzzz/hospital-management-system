<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $apptIdRaw = $_GET['appointment_id'] ?? null;
    if ($apptIdRaw === null || $apptIdRaw === '' || !ctype_digit((string)$apptIdRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
    }
    $appointmentId = (int)$apptIdRaw;

    $pdo = db();
    ensure_opd_assessment_tables($pdo);

    $stmt = $pdo->prepare(
        'SELECT a.*, p.patient_code, p.full_name
         FROM opd_nursing_assessments a
         JOIN patients p ON p.id = a.patient_id
         WHERE a.appointment_id = :appointment_id
         ORDER BY a.created_at DESC, a.id DESC
         LIMIT 1'
    );
    $stmt->execute(['appointment_id' => $appointmentId]);
    $row = $stmt->fetch();

    json_response(['ok' => true, 'assessment' => $row ?: null]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
