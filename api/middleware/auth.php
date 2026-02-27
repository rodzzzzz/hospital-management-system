<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/../auth/_tokens.php';

/**
 * Require a valid Bearer token. Returns the authenticated user array.
 * Sends a 401 JSON response and exits if not authenticated.
 */
function require_auth(): array
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if ($header === '' && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }

    $token = null;
    if (preg_match('/^\s*Bearer\s+(\S+)\s*$/i', $header, $m)) {
        $token = $m[1];
    }

    if ($token === null || $token === '') {
        json_response(['ok' => false, 'error' => 'Authentication required'], 401);
    }

    $pdo = db();
    $user = auth_current_user_from_token($pdo, $token);

    if ($user === null) {
        json_response(['ok' => false, 'error' => 'Invalid or expired token'], 401);
    }

    return $user;
}
