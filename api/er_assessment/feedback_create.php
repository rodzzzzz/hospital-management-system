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

    $submissionIdRaw = $data['submission_id'] ?? null;
    $submissionId = null;
    if ($submissionIdRaw !== null && $submissionIdRaw !== '') {
        if (!is_int($submissionIdRaw) && !(is_string($submissionIdRaw) && ctype_digit((string)$submissionIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid submission_id'], 400);
        }
        $submissionId = (int)$submissionIdRaw;
        if ($submissionId <= 0) $submissionId = null;
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

    $assessmentIdRaw = $data['er_assessment_id'] ?? null;
    $assessmentId = null;
    if ($assessmentIdRaw !== null && $assessmentIdRaw !== '') {
        if (!is_int($assessmentIdRaw) && !(is_string($assessmentIdRaw) && ctype_digit((string)$assessmentIdRaw))) {
            json_response(['ok' => false, 'error' => 'Invalid er_assessment_id'], 400);
        }
        $assessmentId = (int)$assessmentIdRaw;
        if ($assessmentId <= 0) $assessmentId = null;
    }

    $doctorName = trim((string)($data['doctor_name'] ?? ''));
    $doctorName = ($doctorName !== '') ? $doctorName : null;

    $labTestsRaw = $data['lab_tests'] ?? null;
    if ($labTestsRaw !== null && !is_array($labTestsRaw)) {
        json_response(['ok' => false, 'error' => 'Invalid lab_tests'], 400);
    }
    $labTests = [];
    if (is_array($labTestsRaw)) {
        foreach ($labTestsRaw as $x) {
            if (!is_string($x) && !is_int($x)) continue;
            $s = strtolower(trim((string)$x));
            if ($s === '') continue;
            if (!preg_match('/^[a-z0-9_\-]+$/', $s)) continue;
            $labTests[] = $s;
        }
        $labTests = array_values(array_unique($labTests));
        if (count($labTests) > 50) {
            $labTests = array_slice($labTests, 0, 50);
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
    ensure_er_assessment_tables($pdo);
    ensure_encounter_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    if ($doctorName === null) {
        $me = trim((string)($authUser['full_name'] ?? ''));
        if ($me !== '') {
            $doctorName = $me;
        }
    }

    if ($encounterId === null) {
        $open = find_open_encounter_by_type($pdo, $patientId, 'ER');
        $encounterId = $open && isset($open['id']) ? (int)$open['id'] : null;
        if ($encounterId === null) {
            json_response(['ok' => false, 'error' => 'No active ER encounter for patient'], 400);
        }
    }

    $encStmt = $pdo->prepare('SELECT id, patient_id, type FROM encounters WHERE id = :id LIMIT 1');
    $encStmt->execute(['id' => $encounterId]);
    $enc = $encStmt->fetch();
    if (!$enc) {
        json_response(['ok' => false, 'error' => 'Encounter not found'], 404);
    }

    if (strtoupper((string)($enc['type'] ?? '')) !== 'ER') {
        json_response(['ok' => false, 'error' => 'Encounter is not ER'], 400);
    }

    if ((int)($enc['patient_id'] ?? 0) !== $patientId) {
        json_response(['ok' => false, 'error' => 'Encounter mismatch'], 400);
    }

    if ($assessmentId !== null) {
        $aStmt = $pdo->prepare('SELECT id, encounter_id, patient_id FROM er_nursing_assessments WHERE id = :id LIMIT 1');
        $aStmt->execute(['id' => $assessmentId]);
        $a = $aStmt->fetch();
        if (!$a) {
            json_response(['ok' => false, 'error' => 'Assessment not found'], 404);
        }
        if ((int)($a['encounter_id'] ?? 0) !== $encounterId || (int)($a['patient_id'] ?? 0) !== $patientId) {
            json_response(['ok' => false, 'error' => 'Assessment mismatch'], 400);
        }
    }

    $labTestsJson = $labTests ? json_encode($labTests, JSON_UNESCAPED_SLASHES) : null;

    $id = null;
    $findExisting = $pdo->prepare(
        'SELECT id
         FROM er_doctor_feedback
         WHERE encounter_id = :encounter_id
           AND patient_id = :patient_id
           AND ((:er_assessment_id_null IS NULL AND er_assessment_id IS NULL) OR er_assessment_id = :er_assessment_id_eq)
         ORDER BY id DESC
         LIMIT 1'
    );
    $findExisting->execute([
        'encounter_id' => $encounterId,
        'patient_id' => $patientId,
        'er_assessment_id_null' => $assessmentId,
        'er_assessment_id_eq' => $assessmentId,
    ]);
    $existing = $findExisting->fetch();
    if ($existing && isset($existing['id'])) {
        $id = (int)$existing['id'];
        $upFeedback = $pdo->prepare(
            'UPDATE er_doctor_feedback
             SET doctor_name = :doctor_name,
                 lab_tests_json = :lab_tests_json,
                 lab_note = :lab_note,
                 status = :status,
                 feedback_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );
        $upFeedback->execute([
            'doctor_name' => $doctorName,
            'lab_tests_json' => $labTestsJson,
            'lab_note' => $labNote,
            'status' => 'sent',
            'id' => $id,
        ]);
    } else {
        $ins = $pdo->prepare(
            'INSERT INTO er_doctor_feedback (encounter_id, patient_id, er_assessment_id, doctor_name, lab_tests_json, lab_note, status)
             VALUES (:encounter_id, :patient_id, :er_assessment_id, :doctor_name, :lab_tests_json, :lab_note, :status)'
        );
        $ins->execute([
            'encounter_id' => $encounterId,
            'patient_id' => $patientId,
            'er_assessment_id' => $assessmentId,
            'doctor_name' => $doctorName,
            'lab_tests_json' => $labTestsJson,
            'lab_note' => $labNote,
            'status' => 'sent',
        ]);
        $id = (int)$pdo->lastInsertId();
    }

    if ($submissionId !== null) {
        $up = $pdo->prepare(
            "UPDATE er_assessment_submissions
             SET status = 'responded', responded_at = CURRENT_TIMESTAMP
             WHERE id = :id"
        );
        $up->execute(['id' => $submissionId]);
    }

    $get = $pdo->prepare(
        'SELECT f.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_doctor_feedback f
         JOIN patients p ON p.id = f.patient_id
         JOIN encounters e ON e.id = f.encounter_id
         WHERE f.id = :id
         LIMIT 1'
    );
    $get->execute(['id' => $id]);
    $row = $get->fetch();

    json_response(['ok' => true, 'feedback' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
