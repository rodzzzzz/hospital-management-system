<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    xray_ensure_schema($pdo);

    $status = strtolower(trim((string)($_GET['status'] ?? '')));
    $allowed = ['requested', 'scheduled', 'in_progress', 'completed', 'reported', 'cancelled'];
    if ($status !== '' && !in_array($status, $allowed, true)) {
        json_response(['ok' => false, 'error' => 'Invalid status'], 400);
    }

    $q = trim((string)($_GET['q'] ?? ''));
    $limit = 50;

    $where = [];
    $params = [];

    if ($status !== '') {
        $where[] = 'status = :status';
        $params['status'] = $status;
    }

    if ($q !== '') {
        $where[] = '(patient_name LIKE :q OR exam_type LIKE :q OR technologist_name LIKE :q)';
        $params['q'] = '%' . $q . '%';
    }

    $sqlWhere = count($where) ? ('WHERE ' . implode(' AND ', $where)) : '';

    $stmt = $pdo->prepare(
        'SELECT id, patient_name, exam_type, priority, status, ordered_at, scheduled_at, completed_at, technologist_name
         FROM xray_orders
         ' . $sqlWhere .
        ' ORDER BY ordered_at DESC
          LIMIT ' . $limit
    );

    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    json_response([
        'ok' => true,
        'orders' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
