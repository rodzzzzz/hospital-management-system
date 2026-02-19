<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_hr_tables($pdo);

    $q = trim((string)($_GET['q'] ?? ''));
    $status = strtolower(trim((string)($_GET['status'] ?? '')));

    $deptId = isset($_GET['department_id']) && ctype_digit((string)$_GET['department_id']) ? (int)$_GET['department_id'] : null;
    $posId = isset($_GET['position_id']) && ctype_digit((string)$_GET['position_id']) ? (int)$_GET['position_id'] : null;

    $sql =
        "SELECT e.id, e.employee_code, e.full_name, e.phone, e.email, e.department_id, e.position_id, e.status, e.created_at, e.updated_at,
                d.name AS department_name,
                p.name AS position_name
         FROM hr_employees e
         LEFT JOIN hr_departments d ON d.id = e.department_id
         LEFT JOIN hr_positions p ON p.id = e.position_id
         WHERE 1=1";

    $params = [];

    if ($q !== '') {
        $sql .= ' AND (e.full_name LIKE :q OR e.employee_code LIKE :q)';
        $params['q'] = '%' . $q . '%';
    }

    if ($deptId !== null) {
        $sql .= ' AND e.department_id = :dept_id';
        $params['dept_id'] = $deptId;
    }

    if ($posId !== null) {
        $sql .= ' AND e.position_id = :pos_id';
        $params['pos_id'] = $posId;
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'], true)) {
        $sql .= ' AND e.status = :status';
        $params['status'] = $status;
    }

    $sql .= ' ORDER BY e.full_name ASC, e.id ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    json_response(['ok' => true, 'employees' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
