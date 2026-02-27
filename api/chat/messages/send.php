<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../../auth/_session.php';
require_once __DIR__ . '/../_tables.php';
require_once __DIR__ . '/../../websocket/_broadcast.php';

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
    $body = trim((string)($data['body'] ?? ''));
    $senderModule = chat_normalize_module((string)($data['sender_module'] ?? ''));
    if ($senderModule === '') {
        $senderModule = chat_default_sender_module($user);
    }

    if ($threadId <= 0 || $body === '' || $senderModule === '') {
        json_response(['ok' => false, 'error' => 'Missing thread_id or body'], 400);
    }

    if (!chat_user_has_module($user, $senderModule)) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $thread = chat_get_thread($pdo, $threadId);
    if (!$thread) {
        json_response(['ok' => false, 'error' => 'Thread not found'], 404);
    }

    if (!chat_thread_accessible($user, $thread)) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $type = (string)($thread['type'] ?? '');

    if ($type === 'announcements') {
        $canPost = auth_user_has_module($user, 'ADMIN') || auth_user_has_module($user, 'HR');
        if (!$canPost) {
            json_response(['ok' => false, 'error' => 'Forbidden'], 403);
        }
    }

    if ($type === 'dept_pair') {
        $a = chat_normalize_module((string)($thread['module_a'] ?? ''));
        $b = chat_normalize_module((string)($thread['module_b'] ?? ''));
        if ($senderModule !== $a && $senderModule !== $b) {
            json_response(['ok' => false, 'error' => 'Sender module not part of this thread'], 400);
        }
    }

    $ins = $pdo->prepare('INSERT INTO chat_messages (thread_id, sender_user_id, sender_module, body) VALUES (:tid, :uid, :sm, :body)');
    $ins->execute([
        'tid' => $threadId,
        'uid' => (int)($user['id'] ?? 0),
        'sm' => $senderModule,
        'body' => $body,
    ]);

    $msgId = (int)$pdo->lastInsertId();

    $msgStmt = $pdo->prepare(
        'SELECT m.id, m.thread_id, m.sender_user_id, m.sender_module, m.body, m.created_at, u.full_name
         FROM chat_messages m
         JOIN users u ON u.id = m.sender_user_id
         WHERE m.id = :id LIMIT 1'
    );
    $msgStmt->execute(['id' => $msgId]);
    $r = $msgStmt->fetch();

    if (!$r) {
        json_response(['ok' => false, 'error' => 'Message not found'], 500);
    }

    $message = [
        'id' => (int)($r['id'] ?? 0),
        'thread_id' => (int)($r['thread_id'] ?? 0),
        'sender_user_id' => (int)($r['sender_user_id'] ?? 0),
        'sender_module' => (string)($r['sender_module'] ?? ''),
        'sender_name' => (string)($r['full_name'] ?? ''),
        'body' => (string)($r['body'] ?? ''),
        'created_at' => (string)($r['created_at'] ?? ''),
        'is_me' => true,
    ];

    // Broadcast chat message via WebSocket
    $broadcastMessage = $message;
    $broadcastMessage['is_me'] = false; // Recipients will see it as not their own
    broadcastChatMessage($threadId, $broadcastMessage);

    json_response([
        'ok' => true,
        'message' => $message,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
