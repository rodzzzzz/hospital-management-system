<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

require_method('GET');

try {
    $pdo = db();
    ensure_er_assessment_tables($pdo);

    $authUser = auth_current_user($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $doctorId = (int)($authUser['id'] ?? 0);
    $doctorName = trim((string)($authUser['full_name'] ?? ''));

    $q = trim((string)($_GET['q'] ?? ''));

    $where = [];
    $params = [];

    if (!$isAdmin) {
        $where[] = '((s.doctor_id IS NOT NULL AND s.doctor_id = :doctor_id) OR (s.doctor_id IS NULL AND s.doctor_name = :doctor_name))';
        $params['doctor_id'] = $doctorId;
        $params['doctor_name'] = $doctorName;
    }

    $where[] = "s.status = 'submitted'";

    if ($q !== '') {
        $where[] = '(p.full_name LIKE :q OR p.patient_code LIKE :q OR e.encounter_no LIKE :q)';
        $params['q'] = '%' . $q . '%';
    }

    $sqlWhere = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $stmt = $pdo->prepare(
        'SELECT s.*, p.patient_code, p.full_name, e.encounter_no
         FROM er_assessment_submissions s
         JOIN patients p ON p.id = s.patient_id
         JOIN encounters e ON e.id = s.encounter_id
         ' . $sqlWhere . '
         ORDER BY s.submitted_at DESC, s.id DESC
         LIMIT 500'
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response(['ok' => true, 'submissions' => $rows]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
