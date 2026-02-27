<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../../_cors.php';
require_once __DIR__ . '/../_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_hr_tables($pdo);

    $deptId = isset($_GET['department_id']) && ctype_digit((string)$_GET['department_id']) ? (int)$_GET['department_id'] : null;
    $q = trim((string)($_GET['q'] ?? ''));
    $status = strtolower(trim((string)($_GET['status'] ?? '')));

    $sql =
        "SELECT p.id, p.department_id, p.name, p.status, p.created_at, p.updated_at,
                d.name AS department_name
         FROM hr_positions p
         LEFT JOIN hr_departments d ON d.id = p.department_id
         WHERE 1=1";

    $params = [];

    if ($deptId !== null) {
        $sql .= ' AND p.department_id = :dept_id';
        $params['dept_id'] = $deptId;
    }

    if ($q !== '') {
        $sql .= ' AND p.name LIKE :q';
        $params['q'] = '%' . $q . '%';
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'], true)) {
        $sql .= ' AND p.status = :status';
        $params['status'] = $status;
    }

    $sql .= ' ORDER BY d.name ASC, p.name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    json_response(['ok' => true, 'positions' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
