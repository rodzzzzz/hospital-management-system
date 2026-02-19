<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $username = trim((string)($data['username'] ?? ''));
    $fullName = trim((string)($data['full_name'] ?? ''));
    $status = strtolower(trim((string)($data['status'] ?? 'active')));
    $password = isset($data['password']) ? (string)$data['password'] : '';

    if ($username === '' || $fullName === '') {
        json_response(['ok' => false, 'error' => 'Missing username or full_name'], 400);
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $status = 'active';
    }

    $roles = $data['roles'] ?? [];
    if (!is_array($roles)) {
        $roles = [];
    }

    $pdo = db();
    ensure_users_tables($pdo);

    auth_session_start();
    $actor = auth_current_user($pdo);
    if (!$actor) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $actorIsAdmin = auth_user_has_module($actor, 'ADMIN');
    $actorIsHr = auth_user_has_module($actor, 'HR');
    if (!$actorIsAdmin && !$actorIsHr) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    if (!$actorIsAdmin && is_array($roles)) {
        foreach ($roles as $r) {
            if (!is_array($r)) {
                continue;
            }
            $m = strtoupper(trim((string)($r['module'] ?? '')));
            if ($m === 'ADMIN') {
                json_response(['ok' => false, 'error' => 'Forbidden'], 403);
            }
        }
    }

    $pdo->beginTransaction();

    $passwordHash = null;
    if ($password !== '') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    }

    $cols = ['username', 'full_name', 'status'];
    $vals = [':username', ':full_name', ':status'];
    $paramsIns = [
        'username' => $username,
        'full_name' => $fullName,
        'status' => $status,
    ];

    if ($passwordHash !== null) {
        $cols[] = 'password_hash';
        $vals[] = ':password_hash';
        $paramsIns['password_hash'] = $passwordHash;
    }

    $ins = $pdo->prepare('INSERT INTO users (' . implode(', ', $cols) . ') VALUES (' . implode(', ', $vals) . ')');
    try {
        $ins->execute($paramsIns);
    } catch (Throwable $e) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Username already exists'], 409);
    }

    $userId = (int)$pdo->lastInsertId();

    if ($userId > 0 && count($roles) > 0) {
        $roleIns = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, module, role) VALUES (:user_id, :module, :role)');
        foreach ($roles as $r) {
            if (!is_array($r)) continue;
            $m = strtoupper(trim((string)($r['module'] ?? '')));
            $role = trim((string)($r['role'] ?? ''));
            if ($m === '' || $role === '') continue;
            $roleIns->execute([
                'user_id' => $userId,
                'module' => $m,
                'role' => $role,
            ]);
        }
    }

    $pdo->commit();

    json_response([
        'ok' => true,
        'user_id' => $userId,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
