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

    $pdo->beginTransaction();
    $pdo->prepare('DELETE FROM users WHERE id = :id')->execute(['id' => $userId]);
    $pdo->commit();

    json_response(['ok' => true]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
