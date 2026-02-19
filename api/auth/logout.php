<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_session.php';

cors_headers();
require_method('POST');

// Invalidate Bearer token if present (e.g. extension logout)
$bearer = auth_bearer_token();
if ($bearer !== null) {
    $pdo = db();
    auth_token_invalidate($pdo, $bearer);
}

auth_session_start();

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', (bool)($params['secure'] ?? false), (bool)($params['httponly'] ?? true));
}

session_destroy();

json_response(['ok' => true]);
