<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../../_cors.php';
require_once __DIR__ . '/../_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $deptIdRaw = $data['department_id'] ?? null;
    $deptId = null;
    if ($deptIdRaw !== null && $deptIdRaw !== '' && ((is_int($deptIdRaw)) || (is_string($deptIdRaw) && ctype_digit($deptIdRaw)))) {
        $deptId = (int)$deptIdRaw;
        if ($deptId <= 0) $deptId = null;
    }

    $name = trim((string)($data['name'] ?? ''));
    $status = strtolower(trim((string)($data['status'] ?? 'active')));

    if ($name === '') {
        json_response(['ok' => false, 'error' => 'Missing name'], 400);
    }
    if (!in_array($status, ['active', 'inactive'], true)) {
        $status = 'active';
    }

    $pdo = db();
    ensure_hr_tables($pdo);

    $stmt = $pdo->prepare('INSERT INTO hr_positions (department_id, name, status) VALUES (:department_id, :name, :status)');
    try {
        $stmt->execute([
            'department_id' => $deptId,
            'name' => $name,
            'status' => $status,
        ]);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Position already exists'], 409);
    }

    json_response(['ok' => true, 'position_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
