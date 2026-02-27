<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $pdo = db();
    ensure_admissions_tables($pdo);

    $body = json_decode(file_get_contents('php://input'), true) ?? [];

    $admissionId   = (int)($body['admission_id'] ?? 0);
    $patientId     = (int)($body['patient_id'] ?? 0);
    $assessedBy    = trim($body['assessed_by'] ?? '');
    $assessedAt    = $body['assessed_at'] ?? date('Y-m-d H:i:s');

    if ($admissionId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'admission_id and patient_id are required'], 422);
    }

    // Verify admission exists and belongs to patient
    $chk = $pdo->prepare('SELECT id FROM admissions WHERE id = :id AND patient_id = :pid LIMIT 1');
    $chk->execute(['id' => $admissionId, 'pid' => $patientId]);
    if (!$chk->fetch()) {
        json_response(['ok' => false, 'error' => 'Admission not found'], 404);
    }

    $fields = [
        'admission_id'            => $admissionId,
        'patient_id'              => $patientId,
        'height_cm'               => !empty($body['height_cm']) ? (float)$body['height_cm'] : null,
        'weight_kg'               => !empty($body['weight_kg']) ? (float)$body['weight_kg'] : null,
        'temperature'             => !empty($body['temperature']) ? (float)$body['temperature'] : null,
        'blood_pressure'          => trim($body['blood_pressure'] ?? ''),
        'pulse_rate'              => !empty($body['pulse_rate']) ? (int)$body['pulse_rate'] : null,
        'respiratory_rate'        => !empty($body['respiratory_rate']) ? (int)$body['respiratory_rate'] : null,
        'oxygen_saturation'       => !empty($body['oxygen_saturation']) ? (int)$body['oxygen_saturation'] : null,
        'pain_scale'              => isset($body['pain_scale']) ? (int)$body['pain_scale'] : null,
        'chief_complaint'         => trim($body['chief_complaint'] ?? ''),
        'history_present_illness' => trim($body['history_present_illness'] ?? ''),
        'past_medical_history'    => trim($body['past_medical_history'] ?? ''),
        'family_history'          => trim($body['family_history'] ?? ''),
        'social_history'          => trim($body['social_history'] ?? ''),
        'current_medications'     => trim($body['current_medications'] ?? ''),
        'allergies'               => trim($body['allergies'] ?? ''),
        'immunization_history'    => trim($body['immunization_history'] ?? ''),
        'diet_restrictions'       => trim($body['diet_restrictions'] ?? ''),
        'mobility_status'         => trim($body['mobility_status'] ?? ''),
        'fall_risk'               => $body['fall_risk'] ?? 'low',
        'code_status'             => $body['code_status'] ?? 'full_code',
        'assessed_by'             => $assessedBy,
        'assessed_at'             => $assessedAt,
    ];

    // Upsert
    $existing = $pdo->prepare('SELECT id FROM admission_assessments WHERE admission_id = :id LIMIT 1');
    $existing->execute(['id' => $admissionId]);
    $row = $existing->fetch();

    if ($row) {
        $sets = implode(', ', array_map(fn($k) => "$k = :$k", array_keys($fields)));
        $pdo->prepare("UPDATE admission_assessments SET $sets WHERE admission_id = :admission_id")
            ->execute($fields);
        $assessmentId = (int)$row['id'];
    } else {
        $cols = implode(', ', array_keys($fields));
        $vals = implode(', ', array_map(fn($k) => ":$k", array_keys($fields)));
        $pdo->prepare("INSERT INTO admission_assessments ($cols) VALUES ($vals)")->execute($fields);
        $assessmentId = (int)$pdo->lastInsertId();
    }

    json_response(['ok' => true, 'assessment_id' => $assessmentId]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
