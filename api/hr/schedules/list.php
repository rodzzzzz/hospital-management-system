<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_hr_tables($pdo);

    $employeeId = isset($_GET['employee_id']) && ctype_digit((string)$_GET['employee_id']) ? (int)$_GET['employee_id'] : null;
    $from = trim((string)($_GET['date_from'] ?? ''));
    $to = trim((string)($_GET['date_to'] ?? ''));

    $sql =
        "SELECT s.id, s.employee_id, s.shift_date, s.start_time, s.end_time, s.notes, s.created_at,
                e.full_name, e.employee_code,
                d.name AS department_name,
                p.name AS position_name
         FROM hr_schedules s
         JOIN hr_employees e ON e.id = s.employee_id
         LEFT JOIN hr_departments d ON d.id = e.department_id
         LEFT JOIN hr_positions p ON p.id = e.position_id
         WHERE 1=1";

    $params = [];

    if ($employeeId !== null) {
        $sql .= ' AND s.employee_id = :employee_id';
        $params['employee_id'] = $employeeId;
    }

    if ($from !== '') {
        $sql .= ' AND s.shift_date >= :date_from';
        $params['date_from'] = $from;
    }

    if ($to !== '') {
        $sql .= ' AND s.shift_date <= :date_to';
        $params['date_to'] = $to;
    }

    $sql .= ' ORDER BY s.shift_date DESC, s.start_time DESC LIMIT 200';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    json_response(['ok' => true, 'schedules' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
