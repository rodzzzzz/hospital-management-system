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

    $assessmentIdRaw = $data['er_assessment_id'] ?? null;
    if (!is_int($assessmentIdRaw) && !(is_string($assessmentIdRaw) && ctype_digit((string)$assessmentIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid er_assessment_id'], 400);
    }
    $assessmentId = (int)$assessmentIdRaw;

    $doctorIdRaw = $data['doctor_id'] ?? null;
    $doctorId = null;
    if ($doctorIdRaw !== null && $doctorIdRaw !== '') {
        if (!is_int($doctorIdRaw) && !(is_string($doctorIdRaw) && ctype_digit((string)$doctorIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid doctor_id'], 400);
        }
        $doctorId = (int)$doctorIdRaw;
        if ($doctorId <= 0) $doctorId = null;
    }

    $doctorName = trim((string)($data['doctor_name'] ?? ''));
    $doctorName = ($doctorName !== '') ? $doctorName : null;

    $pdo = db();
    ensure_er_assessment_tables($pdo);
    ensure_encounter_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    if (!$isAdmin && auth_user_has_role($authUser, 'ER', 'NP/PA')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $submittedBy = trim((string)($authUser['full_name'] ?? ''));
    $submittedBy = ($submittedBy !== '') ? $submittedBy : null;

    $stmt = $pdo->prepare('SELECT id, encounter_id, patient_id FROM er_nursing_assessments WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $assessmentId]);
    $assess = $stmt->fetch();
    if (!$assess) {
        json_response(['ok' => false, 'error' => 'Assessment not found'], 404);
    }

    $encounterId = (int)($assess['encounter_id'] ?? 0);
    $patientId = (int)($assess['patient_id'] ?? 0);
    if ($encounterId <= 0 || $patientId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid assessment'], 400);
    }

    if ($doctorId !== null) {
        $dStmt = $pdo->prepare('SELECT id, full_name FROM users WHERE id = :id LIMIT 1');
        $dStmt->execute(['id' => $doctorId]);
        $doc = $dStmt->fetch();
        if (!$doc) {
            json_response(['ok' => false, 'error' => 'Doctor not found'], 404);
        }
        if ($doctorName === null) {
            $nm = trim((string)($doc['full_name'] ?? ''));
            if ($nm !== '') $doctorName = $nm;
        }
    }

    if ($doctorId === null && $doctorName === null) {
        json_response(['ok' => false, 'error' => 'Missing doctor_id or doctor_name'], 400);
    }

    $ins = $pdo->prepare(
        'INSERT INTO er_assessment_submissions (encounter_id, patient_id, er_assessment_id, submitted_by, doctor_id, doctor_name, status)
         VALUES (:encounter_id, :patient_id, :er_assessment_id, :submitted_by, :doctor_id, :doctor_name, :status)'
    );
    $ins->execute([
        'encounter_id' => $encounterId,
        'patient_id' => $patientId,
        'er_assessment_id' => $assessmentId,
        'submitted_by' => $submittedBy,
        'doctor_id' => $doctorId,
        'doctor_name' => $doctorName,
        'status' => 'submitted',
    ]);

    $id = (int)$pdo->lastInsertId();

    $fIns = $pdo->prepare(
        'INSERT INTO er_doctor_feedback (encounter_id, patient_id, er_assessment_id, doctor_name, lab_tests_json, lab_note, status)
         VALUES (:encounter_id, :patient_id, :er_assessment_id, :doctor_name, :lab_tests_json, :lab_note, :status)'
    );
    $fIns->execute([
        'encounter_id' => $encounterId,
        'patient_id' => $patientId,
        'er_assessment_id' => $assessmentId,
        'doctor_name' => $doctorName,
        'lab_tests_json' => null,
        'lab_note' => null,
        'status' => 'pending',
    ]);

    $get = $pdo->prepare(
        'SELECT s.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_assessment_submissions s
         JOIN patients p ON p.id = s.patient_id
         JOIN encounters e ON e.id = s.encounter_id
         WHERE s.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    json_response(['ok' => true, 'submission' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
