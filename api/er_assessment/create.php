<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../encounters/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $patientIdRaw = $data['patient_id'] ?? null;
    if (!is_int($patientIdRaw) && !(is_string($patientIdRaw) && ctype_digit((string)$patientIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid patient_id'], 400);
    }
    $patientId = (int)$patientIdRaw;

    $encounterIdRaw = $data['encounter_id'] ?? null;
    $encounterId = null;
    if ($encounterIdRaw !== null && $encounterIdRaw !== '') {
        if (!is_int($encounterIdRaw) && !(is_string($encounterIdRaw) && ctype_digit((string)$encounterIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid encounter_id'], 400);
        }
        $encounterId = (int)$encounterIdRaw;
        if ($encounterId <= 0) $encounterId = null;
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
    ensure_er_assessment_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER') && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $stmt = $pdo->prepare('SELECT id FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $patientId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    $resolvedEncounterId = resolve_er_encounter_id($pdo, $patientId, $encounterId);

    $chk = $pdo->prepare("SELECT id, type, status FROM encounters WHERE id = :id AND patient_id = :patient_id LIMIT 1");
    $chk->execute(['id' => $resolvedEncounterId, 'patient_id' => $patientId]);
    $enc = $chk->fetch();
    if (!$enc) {
        json_response(['ok' => false, 'error' => 'Encounter not found'], 404);
    }
    $encType = strtoupper((string)($enc['type'] ?? ''));
    if ($encType !== 'ER') {
        json_response(['ok' => false, 'error' => 'Encounter is not ER'], 400);
    }

    $vitalsJson = ($vitals !== null) ? json_encode($vitals) : null;
    $assessmentJson = ($assessment !== null) ? json_encode($assessment) : null;

    $ins = $pdo->prepare(
        'INSERT INTO er_nursing_assessments (encounter_id, patient_id, nurse_name, triage_level, vitals_json, assessment_json, notes) VALUES (:encounter_id, :patient_id, :nurse_name, :triage_level, :vitals_json, :assessment_json, :notes)'
    );
    $ins->execute([
        'encounter_id' => $resolvedEncounterId,
        'patient_id' => $patientId,
        'nurse_name' => $nurseName,
        'triage_level' => $triageLevel,
        'vitals_json' => $vitalsJson,
        'assessment_json' => $assessmentJson,
        'notes' => $notes,
    ]);

    $id = (int)$pdo->lastInsertId();

    $get = $pdo->prepare(
        'SELECT a.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_nursing_assessments a
         JOIN patients p ON p.id = a.patient_id
         JOIN encounters e ON e.id = a.encounter_id
         WHERE a.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    json_response(['ok' => true, 'assessment' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
