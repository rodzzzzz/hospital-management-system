<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

/**
 * Local endpoint called by frontend JS after login.
 * Stores the auth token and user data in the local PHP session.
 * 
 * POST { token: string, user: object }
 */

header('Content-Type: application/json; charset=utf-8');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: 'null', true);

if (!is_array($data)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Invalid JSON']);
    exit;
}

$token = (string)($data['token'] ?? '');
$user = $data['user'] ?? null;

if ($token === '' || !is_array($user) || empty($user['id'])) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'error' => 'Missing token or user']);
    exit;
}

auth_store_login($token, $user);

echo json_encode(['ok' => true]);
