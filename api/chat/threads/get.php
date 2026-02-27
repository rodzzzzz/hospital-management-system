<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../../_cors.php';
require_once __DIR__ . '/../../auth/_session.php';
require_once __DIR__ . '/../_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    $user = auth_current_user_optional_token($pdo);
    if (!$user) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    ensure_chat_tables($pdo);

    $type = strtolower(trim((string)($_GET['type'] ?? 'dept_pair')));

    if ($type === 'announcements') {
        $threadId = chat_ensure_announcements_thread($pdo);
        $thread = chat_get_thread($pdo, $threadId);
        if (!$thread) {
            json_response(['ok' => false, 'error' => 'Thread not found'], 404);
        }

        json_response([
            'ok' => true,
            'thread' => $thread,
        ]);
    }

    $target = chat_normalize_module((string)($_GET['target_module'] ?? ''));
    $senderModule = chat_normalize_module((string)($_GET['sender_module'] ?? ''));
    if ($senderModule === '') {
        $senderModule = chat_default_sender_module($user);
    }

    if ($target === '' || $senderModule === '') {
        json_response(['ok' => false, 'error' => 'Missing target_module'], 400);
    }

    if ($target === $senderModule) {
        json_response(['ok' => false, 'error' => 'Cannot chat with the same department'], 400);
    }

    if (!chat_user_has_module($user, $senderModule)) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $threadId = chat_ensure_dept_pair_thread($pdo, $senderModule, $target);
    $thread = chat_get_thread($pdo, $threadId);
    if (!$thread) {
        json_response(['ok' => false, 'error' => 'Thread not found'], 404);
    }

    if (!chat_thread_accessible($user, $thread)) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    json_response([
        'ok' => true,
        'thread' => $thread,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
