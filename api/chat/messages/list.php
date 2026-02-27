<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_cors.php';
require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
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

    $threadId = require_int('thread_id');
    $afterId = 0;
    $afterRaw = $_GET['after_id'] ?? null;
    if (is_string($afterRaw) && ctype_digit($afterRaw)) {
        $afterId = (int)$afterRaw;
    }

    $thread = chat_get_thread($pdo, $threadId);
    if (!$thread) {
        json_response(['ok' => false, 'error' => 'Thread not found'], 404);
    }

    if (!chat_thread_accessible($user, $thread)) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $limit = 100;
    $params = ['tid' => $threadId, 'after' => $afterId];

    $stmt = $pdo->prepare(
        'SELECT m.id, m.thread_id, m.sender_user_id, m.sender_module, m.body, m.created_at, u.full_name
         FROM chat_messages m
         JOIN users u ON u.id = m.sender_user_id
         WHERE m.thread_id = :tid AND m.id > :after
         ORDER BY m.id ASC
         LIMIT ' . (int)$limit
    );
    $stmt->execute($params);
    $rows = $stmt->fetchAll();

    $messages = [];
    $lastId = $afterId;

    if ($afterId === 0 && (!is_array($rows) || count($rows) === 0)) {
        $threadType = (string)($thread['type'] ?? '');
        $myModule = chat_default_sender_module($user);

        $myRole = '';
        if ($myModule !== '') {
            $needle = strtoupper(trim($myModule));
            $roles = $user['roles'] ?? [];
            if (is_array($roles)) {
                foreach ($roles as $r) {
                    if (!is_array($r)) {
                        continue;
                    }
                    if (strtoupper(trim((string)($r['module'] ?? ''))) === $needle) {
                        $rr = trim((string)($r['role'] ?? ''));
                        if ($rr !== '') {
                            $myRole = $rr;
                            break;
                        }
                    }
                }
            }
        }

        $otherModule = '';
        if ($threadType === 'dept_pair') {
            $a = chat_normalize_module((string)($thread['module_a'] ?? ''));
            $b = chat_normalize_module((string)($thread['module_b'] ?? ''));
            if ($myModule !== '' && $myModule === $a) {
                $otherModule = $b;
            } elseif ($myModule !== '' && $myModule === $b) {
                $otherModule = $a;
            } else {
                $otherModule = $a !== '' ? $a : $b;
            }
        } elseif ($threadType === 'announcements') {
            $otherModule = 'ANNOUNCEMENTS';
        }

        $label = $otherModule !== '' ? $otherModule : 'DEPARTMENT';

        $persona = [
            'ER' => [
                ['name' => 'Mitsury', 'role' => 'ER Nurse'],
                ['name' => 'Jasper', 'role' => 'ER Supervisor'],
            ],
            'OPD' => [
                ['name' => 'Mary Rose', 'role' => 'OPD Supervisor'],
                ['name' => 'Kane', 'role' => 'OPD Medtech'],
            ],
            'LAB' => [
                ['name' => 'Aira', 'role' => 'Lab Supervisor'],
                ['name' => 'Noah', 'role' => 'Lab Medtech'],
            ],
            'PHARMACY' => [
                ['name' => 'Camille', 'role' => 'Pharmacist'],
                ['name' => 'Jude', 'role' => 'Pharmacy Assistant'],
            ],
            'CASHIER' => [
                ['name' => 'Bianca', 'role' => 'Cashier Supervisor'],
                ['name' => 'Liam', 'role' => 'Cashier Clerk'],
            ],
            'HR' => [
                ['name' => 'Sofia', 'role' => 'HR Officer'],
                ['name' => 'Ethan', 'role' => 'HR Assistant'],
            ],
        ];

        $p = $persona[$label] ?? null;
        $p1 = is_array($p) && isset($p[0]) ? $p[0] : ['name' => $label . ' User 1', 'role' => 'Staff'];
        $p2 = is_array($p) && isset($p[1]) ? $p[1] : ['name' => $label . ' User 2', 'role' => 'Staff'];

        $messages = [
            [
                'id' => -1,
                'thread_id' => $threadId,
                'sender_user_id' => 0,
                'sender_module' => $label,
                'sender_name' => (string)($p1['name'] ?? $label),
                'sender_role' => (string)($p1['role'] ?? ''),
                'body' => 'Hi, do you have an update?',
                'created_at' => '',
                'is_me' => false,
            ],
            [
                'id' => -2,
                'thread_id' => $threadId,
                'sender_user_id' => (int)($user['id'] ?? 0),
                'sender_module' => ($myModule !== '' ? $myModule : ''),
                'sender_name' => (string)($user['full_name'] ?? 'You'),
                'sender_role' => $myRole,
                'body' => 'Acknowledged. We are checking now.',
                'created_at' => '',
                'is_me' => true,
            ],
            [
                'id' => -3,
                'thread_id' => $threadId,
                'sender_user_id' => 0,
                'sender_module' => $label,
                'sender_name' => (string)($p2['name'] ?? $label),
                'sender_role' => (string)($p2['role'] ?? ''),
                'body' => 'Thanks. Please notify once ready.',
                'created_at' => '',
                'is_me' => false,
            ],
        ];

        json_response([
            'ok' => true,
            'thread' => $thread,
            'messages' => $messages,
            'last_id' => 0,
        ]);
    }

    foreach ($rows as $r) {
        $id = (int)($r['id'] ?? 0);
        if ($id > $lastId) {
            $lastId = $id;
        }
        $messages[] = [
            'id' => $id,
            'thread_id' => (int)($r['thread_id'] ?? 0),
            'sender_user_id' => (int)($r['sender_user_id'] ?? 0),
            'sender_module' => (string)($r['sender_module'] ?? ''),
            'sender_name' => (string)($r['full_name'] ?? ''),
            'body' => (string)($r['body'] ?? ''),
            'created_at' => (string)($r['created_at'] ?? ''),
            'is_me' => ((int)($r['sender_user_id'] ?? 0) === (int)($user['id'] ?? 0)),
        ];
    }

    json_response([
        'ok' => true,
        'thread' => $thread,
        'messages' => $messages,
        'last_id' => $lastId,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
