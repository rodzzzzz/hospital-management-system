<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

require_method('GET');

try {
    $idRaw = $_GET['id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit((string)$idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$idRaw;

    $pdo = db();
    ensure_er_assessment_tables($pdo);

    $authUser = auth_current_user($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    $isDoctor = auth_user_has_module($authUser, 'DOCTOR');
    $isEr = auth_user_has_module($authUser, 'ER');
    if (!$isAdmin && !$isDoctor && !$isEr) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $stmt = $pdo->prepare(
        'SELECT s.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_assessment_submissions s
         JOIN patients p ON p.id = s.patient_id
         JOIN encounters e ON e.id = s.encounter_id
         WHERE s.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $sub = $stmt->fetch();
    if (!$sub) {
        json_response(['ok' => false, 'error' => 'Not found'], 404);
    }

    if (!$isAdmin && $isDoctor) {
        $doctorId = (int)($authUser['id'] ?? 0);
        $doctorName = trim((string)($authUser['full_name'] ?? ''));
        $subDocId = $sub['doctor_id'] ?? null;
        $subDocName = trim((string)($sub['doctor_name'] ?? ''));
        $ok = false;
        if ($subDocId !== null && $subDocId !== '' && ctype_digit((string)$subDocId)) {
            $ok = ((int)$subDocId === $doctorId);
        } else {
            $ok = ($doctorName !== '' && $subDocName !== '' && $doctorName === $subDocName);
        }
        if (!$ok) {
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }
    }

    $assessId = (int)($sub['er_assessment_id'] ?? 0);
    $assessment = null;
    if ($assessId > 0) {
        $aStmt = $pdo->prepare(
            'SELECT a.*, p.patient_code, p.full_name, e.encounter_no
             FROM er_nursing_assessments a
             JOIN patients p ON p.id = a.patient_id
             JOIN encounters e ON e.id = a.encounter_id
             WHERE a.id = :id
             LIMIT 1'
        );
        $aStmt->execute(['id' => $assessId]);
        $assessment = $aStmt->fetch() ?: null;
    }

    json_response(['ok' => true, 'submission' => $sub, 'assessment' => $assessment]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
