<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/_session.php';

require_method('GET');

try {
    $pdo = db();
    $user = auth_current_user($pdo);
    if (!$user) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    json_response(['ok' => true, 'user' => $user]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
