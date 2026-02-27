<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $apptIdRaw = $data['appointment_id'] ?? null;
    if (!is_int($apptIdRaw) && !(is_string($apptIdRaw) && ctype_digit((string)$apptIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid appointment_id'], 400);
    }
    $appointmentId = (int)$apptIdRaw;

    $nurseName = trim((string)($data['nurse_name'] ?? ''));
    $nurseName = ($nurseName !== '') ? $nurseName : null;

    $triageLevel = null;
    $triageLevelRaw = $data['triage_level'] ?? null;
    if ($triageLevelRaw !== null && $triageLevelRaw !== '') {
        if (!is_int($triageLevelRaw) && !(is_string($triageLevelRaw) && ctype_digit((string)$triageLevelRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid triage_level'], 400);
        }
        $triageLevel = (int)$triageLevelRaw;
        if ($triageLevel < 1 || $triageLevel > 5) {
            json_response(['ok' => false, 'error' => 'triage_level must be 1-5'], 400);
        }
    }

    $vitals = $data['vitals'] ?? null;
    if ($vitals !== null && !is_array($vitals)) {
        json_response(['ok' => false, 'error' => 'Invalid vitals'], 400);
    }

    $assessment = $data['assessment'] ?? null;
    if ($assessment !== null && !is_array($assessment)) {
        json_response(['ok' => false, 'error' => 'Invalid assessment'], 400);
    }

    $notes = trim((string)($data['notes'] ?? ''));
    $notes = ($notes !== '') ? $notes : null;

    $pdo = db();
    ensure_opd_assessment_tables($pdo);

    $apptStmt = $pdo->prepare('SELECT id, patient_id FROM opd_appointments WHERE id = :id LIMIT 1');
    $apptStmt->execute(['id' => $appointmentId]);
    $appt = $apptStmt->fetch();
    if (!$appt) {
        json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
    }

    $patientId = (int)($appt['patient_id'] ?? 0);
    if ($patientId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid patient'], 400);
    }

    $vitalsJson = ($vitals !== null) ? json_encode($vitals) : null;
    $assessmentJson = ($assessment !== null) ? json_encode($assessment) : null;

    $ins = $pdo->prepare(
        'INSERT INTO opd_nursing_assessments (appointment_id, patient_id, nurse_name, triage_level, vitals_json, assessment_json, notes) VALUES (:appointment_id, :patient_id, :nurse_name, :triage_level, :vitals_json, :assessment_json, :notes)'
    );
    $ins->execute([
        'appointment_id' => $appointmentId,
        'patient_id' => $patientId,
        'nurse_name' => $nurseName,
        'triage_level' => $triageLevel,
        'vitals_json' => $vitalsJson,
        'assessment_json' => $assessmentJson,
        'notes' => $notes,
    ]);

    $id = (int)$pdo->lastInsertId();

    $pdo->prepare('UPDATE opd_appointments SET nursing_assessment_id = :naid WHERE id = :id')
        ->execute(['naid' => $id, 'id' => $appointmentId]);

    $get = $pdo->prepare(
        'SELECT a.*, p.patient_code, p.full_name
         FROM opd_nursing_assessments a
         JOIN patients p ON p.id = a.patient_id
         WHERE a.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    json_response(['ok' => true, 'assessment' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
