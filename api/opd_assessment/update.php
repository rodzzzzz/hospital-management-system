<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $idRaw = $data['id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit((string)$idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$idRaw;

    $appointmentIdRaw = $data['appointment_id'] ?? null;
    if ($appointmentIdRaw !== null && $appointmentIdRaw !== '') {
        if (!is_int($appointmentIdRaw) && !(is_string($appointmentIdRaw) && ctype_digit((string)$appointmentIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid appointment_id'], 400);
        }
        $appointmentIdRaw = (int)$appointmentIdRaw;
    } else {
        $appointmentIdRaw = null;
    }

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

    $stmt = $pdo->prepare('SELECT id, appointment_id, patient_id FROM opd_nursing_assessments WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $existing = $stmt->fetch();
    if (!$existing) {
        json_response(['ok' => false, 'error' => 'Assessment not found'], 404);
    }

    $existingAppointmentId = (int)($existing['appointment_id'] ?? 0);
    $existingPatientId = (int)($existing['patient_id'] ?? 0);
    if ($existingAppointmentId <= 0 || $existingPatientId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid assessment'], 400);
    }

    if ($appointmentIdRaw !== null && (int)$appointmentIdRaw !== $existingAppointmentId) {
        json_response(['ok' => false, 'error' => 'Appointment mismatch'], 400);
    }

    $vitalsJson = ($vitals !== null) ? json_encode($vitals) : null;
    $assessmentJson = ($assessment !== null) ? json_encode($assessment) : null;

    $upd = $pdo->prepare(
        'UPDATE opd_nursing_assessments
         SET nurse_name = :nurse_name,
             triage_level = :triage_level,
             vitals_json = :vitals_json,
             assessment_json = :assessment_json,
             notes = :notes
         WHERE id = :id'
    );
    $upd->execute([
        'nurse_name' => $nurseName,
        'triage_level' => $triageLevel,
        'vitals_json' => $vitalsJson,
        'assessment_json' => $assessmentJson,
        'notes' => $notes,
        'id' => $id,
    ]);

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
