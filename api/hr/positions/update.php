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

    $idRaw = $data['position_id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit($idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid position_id'], 400);
    }
    $id = (int)$idRaw;

    $deptIdRaw = $data['department_id'] ?? null;
    $deptId = null;
    $deptProvided = false;
    if (array_key_exists('department_id', $data)) {
        $deptProvided = true;
        if ($deptIdRaw !== null && $deptIdRaw !== '' && ((is_int($deptIdRaw)) || (is_string($deptIdRaw) && ctype_digit($deptIdRaw)))) {
            $deptId = (int)$deptIdRaw;
            if ($deptId <= 0) $deptId = null;
        }
    }

    $name = isset($data['name']) ? trim((string)$data['name']) : null;
    $status = isset($data['status']) ? strtolower(trim((string)$data['status'])) : null;

    if ($status !== null && !in_array($status, ['active', 'inactive'], true)) {
        $status = null;
    }

    $fields = [];
    $params = ['id' => $id];

    if ($deptProvided) {
        $fields[] = 'department_id = :department_id';
        $params['department_id'] = $deptId;
    }

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

    $stmt = $pdo->prepare('SELECT id FROM hr_positions WHERE id = :id');
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Position not found'], 404);
    }

    $sql = 'UPDATE hr_positions SET ' . implode(', ', $fields) . ' WHERE id = :id';

    try {
        $pdo->prepare($sql)->execute($params);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Unable to update position'], 409);
    }

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
