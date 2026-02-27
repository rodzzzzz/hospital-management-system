<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

/**
 * Local endpoint called by frontend JS on logout.
 * Clears the auth token and user data from the local PHP session.
 * Also calls the backend logout endpoint to invalidate the token server-side.
 */

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

// Invalidate the token on the backend
$token = auth_get_token();
if ($token !== null) {
    try {
        api_fetch('auth/logout.php', 'POST');
    } catch (Throwable $e) {
        // Best-effort; proceed with local cleanup
    }
}

// Clear local session
auth_clear();

echo json_encode(['ok' => true]);
