<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $userIdRaw = $data['user_id'] ?? null;
    if (!is_int($userIdRaw) && !(is_string($userIdRaw) && ctype_digit($userIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid user_id'], 400);
    }
    $userId = (int)$userIdRaw;

    $username = isset($data['username']) ? trim((string)$data['username']) : null;
    $fullName = isset($data['full_name']) ? trim((string)$data['full_name']) : null;
    $status = isset($data['status']) ? strtolower(trim((string)$data['status'])) : null;
    $password = isset($data['password']) ? (string)$data['password'] : null;

    if ($status !== null && !in_array($status, ['active', 'inactive'], true)) {
        $status = null;
    }

    $roles = $data['roles'] ?? null;
    if ($roles !== null && !is_array($roles)) {
        json_response(['ok' => false, 'error' => 'Invalid roles'], 400);
    }

    $pdo = db();
    ensure_users_tables($pdo);

    auth_session_start();
    $actor = auth_current_user_optional_token($pdo);
    if (!$actor) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $actorIsAdmin = auth_user_has_module($actor, 'ADMIN');
    $actorIsHr = auth_user_has_module($actor, 'HR');
    if (!$actorIsAdmin && !$actorIsHr) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $stmt = $pdo->prepare('SELECT id FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $userId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'User not found'], 404);
    }

    $stmt = $pdo->prepare("SELECT 1 FROM user_roles WHERE user_id = :id AND module = 'ADMIN' LIMIT 1");
    $stmt->execute(['id' => $userId]);
    $targetIsAdmin = (bool)$stmt->fetch();
    if ($targetIsAdmin) {
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

    $fields = [];
    $params = ['id' => $userId];

    if ($username !== null && $username !== '') {
        $fields[] = 'username = :username';
        $params['username'] = $username;
    }
    if ($fullName !== null && $fullName !== '') {
        $fields[] = 'full_name = :full_name';
        $params['full_name'] = $fullName;
    }
    if ($status !== null) {
        $fields[] = 'status = :status';
        $params['status'] = $status;
    }

    if ($password !== null && $password !== '') {
        $fields[] = 'password_hash = :password_hash';
        $params['password_hash'] = password_hash($password, PASSWORD_DEFAULT);
    }

    $pdo->beginTransaction();

    if (count($fields) > 0) {
        $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
        try {
            $pdo->prepare($sql)->execute($params);
        } catch (Throwable $e) {
            $pdo->rollBack();
            json_response(['ok' => false, 'error' => 'Unable to update user'], 409);
        }
    }

    if ($roles !== null) {
        $pdo->prepare('DELETE FROM user_roles WHERE user_id = :id')->execute(['id' => $userId]);
        if (count($roles) > 0) {
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
    }

    $pdo->commit();

    json_response(['ok' => true]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
