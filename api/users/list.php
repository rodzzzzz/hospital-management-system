<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_users_tables($pdo);

    $q = trim((string)($_GET['q'] ?? ''));
    $module = strtoupper(trim((string)($_GET['module'] ?? '')));
    $status = strtolower(trim((string)($_GET['status'] ?? '')));
    $unassigned = trim((string)($_GET['unassigned'] ?? ''));
    $unassignedOnly = ($unassigned === '1' || strtolower($unassigned) === 'true');

    $params = [];

    if ($unassignedOnly) {
        $sql =
            "SELECT u.id, u.username, u.full_name, u.status, u.created_at, u.updated_at, ur.module, ur.role
             FROM users u
             LEFT JOIN user_roles ur ON ur.user_id = u.id
             WHERE ur.id IS NULL";
    } elseif ($module !== '') {
        $sql =
            "SELECT u.id, u.username, u.full_name, u.status, u.created_at, u.updated_at, ur.module, ur.role
             FROM users u
             JOIN user_roles ur ON ur.user_id = u.id
             WHERE ur.module = :module";
        $params['module'] = $module;
    } else {
        $sql =
            "SELECT u.id, u.username, u.full_name, u.status, u.created_at, u.updated_at, ur.module, ur.role
             FROM users u
             LEFT JOIN user_roles ur ON ur.user_id = u.id
             WHERE 1=1";
    }

    if ($q !== '') {
        $sql .= " AND (u.full_name LIKE :q OR u.username LIKE :q)";
        $params['q'] = '%' . $q . '%';
    }

    if ($status !== '' && in_array($status, ['active', 'inactive'], true)) {
        $sql .= " AND u.status = :status";
        $params['status'] = $status;
    }

    $sql .= " ORDER BY u.full_name ASC, u.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $byId = [];
    foreach ($rows as $r) {
        $id = (int)($r['id'] ?? 0);
        if ($id <= 0) {
            continue;
        }

        if (!isset($byId[$id])) {
            $byId[$id] = [
                'id' => $id,
                'username' => (string)($r['username'] ?? ''),
                'full_name' => (string)($r['full_name'] ?? ''),
                'status' => (string)($r['status'] ?? ''),
                'created_at' => (string)($r['created_at'] ?? ''),
                'updated_at' => (string)($r['updated_at'] ?? ''),
                'roles' => [],
            ];
        }

        $m = (string)($r['module'] ?? '');
        $role = (string)($r['role'] ?? '');
        if ($m !== '' && $role !== '') {
            $byId[$id]['roles'][] = [
                'module' => $m,
                'role' => $role,
            ];
        }
    }

    json_response([
        'ok' => true,
        'users' => array_values($byId),
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
