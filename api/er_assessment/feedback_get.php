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
    if (!$isAdmin && !auth_user_has_module($authUser, 'ER') && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $stmt = $pdo->prepare(
        'SELECT f.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_doctor_feedback f
         JOIN patients p ON p.id = f.patient_id
         JOIN encounters e ON e.id = f.encounter_id
         WHERE f.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if (!$row) {
        json_response(['ok' => false, 'error' => 'Not found'], 404);
    }

    json_response(['ok' => true, 'feedback' => $row]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
