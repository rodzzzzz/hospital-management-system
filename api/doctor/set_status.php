<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $statusRaw = strtolower(trim((string)($data['status'] ?? '')));
    if ($statusRaw === '') {
        json_response(['ok' => false, 'error' => 'Missing status'], 400);
    }

    $allowed = ['available', 'busy', 'on_leave'];
    if (!in_array($statusRaw, $allowed, true)) {
        json_response(['ok' => false, 'error' => 'Invalid status'], 400);
    }

    $pdo = db();
    ensure_doctor_tables($pdo);

    $user = auth_current_user_optional_token($pdo);
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

    $pdo->prepare('INSERT INTO doctor_availability (user_id, status) VALUES (:user_id, :status) ON DUPLICATE KEY UPDATE status = VALUES(status)')
        ->execute([
            'user_id' => $userId,
            'status' => $statusRaw,
        ]);

    json_response(['ok' => true, 'status' => $statusRaw]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
