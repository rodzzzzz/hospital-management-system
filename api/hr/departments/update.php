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

    $idRaw = $data['department_id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit($idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid department_id'], 400);
    }
    $id = (int)$idRaw;

    $name = isset($data['name']) ? trim((string)$data['name']) : null;
    $status = isset($data['status']) ? strtolower(trim((string)$data['status'])) : null;

    if ($status !== null && !in_array($status, ['active', 'inactive'], true)) {
        $status = null;
    }

    $fields = [];
    $params = ['id' => $id];

    if ($name !== null && $name !== '') {
        $fields[] = 'name = :name';
        $params['name'] = $name;
    }
    if ($status !== null) {
        $fields[] = 'status = :status';
        $params['status'] = $status;
    }

    if (count($fields) === 0) {
        json_response(['ok' => true]);
    }

    $pdo = db();
    ensure_hr_tables($pdo);

    $stmt = $pdo->prepare('SELECT id FROM hr_departments WHERE id = :id');
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Department not found'], 404);
    }

    $sql = 'UPDATE hr_departments SET ' . implode(', ', $fields) . ' WHERE id = :id';

    try {
        $pdo->prepare($sql)->execute($params);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Unable to update department'], 409);
    }

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
