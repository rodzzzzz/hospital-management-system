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

    $currentPassword = (string)($data['current_password'] ?? '');
    $newPassword = (string)($data['new_password'] ?? '');

    if ($currentPassword === '' || $newPassword === '') {
        json_response(['ok' => false, 'error' => 'Missing current_password or new_password'], 400);
    }

    if (strlen($newPassword) < 6) {
        json_response(['ok' => false, 'error' => 'Password must be at least 6 characters'], 400);
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

    $stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $userId]);
    $row = $stmt->fetch();
    $hash = (string)($row['password_hash'] ?? '');

    if ($hash === '' || !password_verify($currentPassword, $hash)) {
        json_response(['ok' => false, 'error' => 'Current password is incorrect'], 401);
    }

    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $upd = $pdo->prepare('UPDATE users SET password_hash = :h WHERE id = :id');
    $upd->execute(['h' => $newHash, 'id' => $userId]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
