<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
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

    $stmt = $pdo->prepare('INSERT INTO hr_departments (name, status) VALUES (:name, :status)');
    try {
        $stmt->execute(['name' => $name, 'status' => $status]);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Department already exists'], 409);
    }

    json_response(['ok' => true, 'department_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
