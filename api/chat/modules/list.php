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

    $userId = (int)($user['id'] ?? 0);
    $userModules = chat_get_user_modules($user);

    $sendAsModules = array_values(array_filter($userModules, static function ($m) {
        return $m !== 'ADMIN';
    }));

    $canPostAnnouncements = auth_user_has_module($user, 'ADMIN') || auth_user_has_module($user, 'HR');
    if ($canPostAnnouncements && count($sendAsModules) === 0) {
        if (auth_user_has_module($user, 'HR')) {
            $sendAsModules = ['HR'];
        } elseif (auth_user_has_module($user, 'ADMIN')) {
            $sendAsModules = ['ADMIN'];
        }
    }

    $mods = $pdo->query('SELECT DISTINCT module FROM user_roles ORDER BY module ASC')->fetchAll();
    $deptModules = [];
    foreach ($mods as $r) {
        $m = chat_normalize_module((string)($r['module'] ?? ''));
        if ($m === '' || $m === 'ADMIN') {
            continue;
        }
        $deptModules[] = $m;
    }

    $deptModules = array_values(array_unique($deptModules));

    $annThreadId = chat_ensure_announcements_thread($pdo);

    $items = [];

    $items[] = [
        'key' => 'announcements',
        'type' => 'announcements',
        'label' => 'Announcements',
        'module' => null,
        'unread' => chat_unread_count($pdo, $annThreadId, $userId),
    ];

    foreach ($deptModules as $m) {
        $unread = 0;

        if (count($sendAsModules) > 0) {
            foreach ($sendAsModules as $my) {
                if ($my === $m) {
                    continue;
                }
                try {
                    $tid = chat_ensure_dept_pair_thread($pdo, $my, $m);
                    $unread += chat_unread_count($pdo, $tid, $userId);
                } catch (Throwable $e) {
                }
            }
        }

        $items[] = [
            'key' => $m,
            'type' => 'department',
            'label' => $m,
            'module' => $m,
            'unread' => $unread,
        ];
    }

    json_response([
        'ok' => true,
        'me' => [
            'id' => $userId,
            'full_name' => (string)($user['full_name'] ?? ''),
            'modules' => $userModules,
            'send_as_modules' => $sendAsModules,
            'can_post_announcements' => $canPostAnnouncements,
        ],
        'items' => $items,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
