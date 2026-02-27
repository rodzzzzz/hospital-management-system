<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../../auth/_session.php';
require_once __DIR__ . '/../_tables.php';

require_method('POST');

try {
    $pdo = db();
    $user = auth_current_user_optional_token($pdo);
    if (!$user) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    ensure_chat_tables($pdo);

    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $threadId = (int)($data['thread_id'] ?? 0);
    $lastMessageId = (int)($data['last_message_id'] ?? 0);

    if ($threadId <= 0 || $lastMessageId <= 0) {
        json_response(['ok' => false, 'error' => 'Missing thread_id or last_message_id'], 400);
    }

    $thread = chat_get_thread($pdo, $threadId);
    if (!$thread) {
        json_response(['ok' => false, 'error' => 'Thread not found'], 404);
    }

    if (!chat_thread_accessible($user, $thread)) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    chat_mark_read($pdo, $threadId, (int)($user['id'] ?? 0), $lastMessageId);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
