<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/_session.php';

cors_headers();
require_method('POST');

auth_session_start();

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $username = trim((string)($data['username'] ?? ''));
    $password = (string)($data['password'] ?? '');

    if ($username === '' || $password === '') {
        json_response(['ok' => false, 'error' => 'Missing username or password'], 400);
    }

    $pdo = db();
    ensure_users_tables($pdo);

    $stmt = $pdo->prepare('SELECT id, username, full_name, password_hash, status FROM users WHERE username = :u LIMIT 1');
    $stmt->execute(['u' => $username]);
    $u = $stmt->fetch();

    if (!$u) {
        json_response(['ok' => false, 'error' => 'Invalid username or password'], 401);
    }

    if (strtolower((string)($u['status'] ?? '')) !== 'active') {
        json_response(['ok' => false, 'error' => 'Account is inactive'], 403);
    }

    $hash = (string)($u['password_hash'] ?? '');
    if ($hash === '' || !password_verify($password, $hash)) {
        json_response(['ok' => false, 'error' => 'Invalid username or password'], 401);
    }

    $rolesStmt = $pdo->prepare('SELECT module, role FROM user_roles WHERE user_id = :id');
    $rolesStmt->execute(['id' => (int)$u['id']]);
    $roles = $rolesStmt->fetchAll();

    $_SESSION['auth_user_id'] = (int)$u['id'];

    $userId = (int)$u['id'];
    $plainToken = auth_token_create($pdo, $userId);

    json_response([
        'ok' => true,
        'token' => $plainToken,
        'user' => [
            'id' => (int)($u['id'] ?? 0),
            'username' => (string)($u['username'] ?? ''),
            'full_name' => (string)($u['full_name'] ?? ''),
            'roles' => array_map(static function ($r) {
                return [
                    'module' => (string)($r['module'] ?? ''),
                    'role' => (string)($r['role'] ?? ''),
                ];
            }, is_array($roles) ? $roles : []),
        ],
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
