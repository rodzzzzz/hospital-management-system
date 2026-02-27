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

    $fullName = trim((string)($data['full_name'] ?? ''));
    $username = trim((string)($data['username'] ?? ''));

    if ($fullName === '' || $username === '') {
        json_response(['ok' => false, 'error' => 'Missing full_name or username'], 400);
    }

    $pdo = db();
    ensure_users_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $userId = (int)($authUser['id'] ?? 0);
    if ($userId <= 0) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    try {
        $stmt = $pdo->prepare('UPDATE users SET username = :username, full_name = :full_name WHERE id = :id');
        $stmt->execute([
            'username' => $username,
            'full_name' => $fullName,
            'id' => $userId,
        ]);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Username already exists'], 409);
    }

    $stmt = $pdo->prepare('SELECT id, username, full_name, status FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $userId]);
    $u = $stmt->fetch();

    json_response([
        'ok' => true,
        'user' => [
            'id' => (int)($u['id'] ?? 0),
            'username' => (string)($u['username'] ?? ''),
            'full_name' => (string)($u['full_name'] ?? ''),
            'status' => (string)($u['status'] ?? ''),
        ],
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
