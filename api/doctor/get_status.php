<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_doctor_tables($pdo);

    $user = auth_current_user($pdo);
    if (!$user) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }
    if (!auth_user_has_module($user, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $userId = (int)($user['id'] ?? 0);
    if ($userId <= 0) {
        json_response(['ok' => false, 'error' => 'Invalid user'], 400);
    }

    $pdo->prepare('INSERT INTO doctor_availability (user_id) VALUES (:user_id) ON DUPLICATE KEY UPDATE user_id = user_id')
        ->execute(['user_id' => $userId]);

    $stmt = $pdo->prepare('SELECT status, updated_at FROM doctor_availability WHERE user_id = :user_id LIMIT 1');
    $stmt->execute(['user_id' => $userId]);
    $row = $stmt->fetch();

    $status = (string)($row['status'] ?? 'available');

    json_response([
        'ok' => true,
        'status' => $status,
        'updated_at' => $row['updated_at'] ?? null,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
