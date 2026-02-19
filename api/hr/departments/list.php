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

    $sql = 'SELECT id, name, status, created_at, updated_at FROM hr_departments WHERE 1=1';
    $params = [];

    if ($q !== '') {
        $sql .= ' AND name LIKE :q';
        $params['q'] = '%' . $q . '%';
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'], true)) {
        $sql .= ' AND status = :status';
        $params['status'] = $status;
    }

    $sql .= ' ORDER BY name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    json_response(['ok' => true, 'departments' => $stmt->fetchAll()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
