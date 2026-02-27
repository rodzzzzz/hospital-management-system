<?php
declare(strict_types=1);

require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';

cors_headers();
session_start();

$method = strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');

if ($method === 'GET') {
    json_response([
        'ok' => true,
        'active' => ($_SESSION['philhealth_claim_active'] ?? false) === true,
    ]);
}

require_method('POST');

$raw = file_get_contents('php://input');
$data = json_decode($raw ?: 'null', true);
if (!is_array($data)) {
    json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
}

$action = strtolower(trim((string)($data['action'] ?? '')));

if ($action === 'start') {
    $_SESSION['philhealth_claim_active'] = true;
    if (!isset($_SESSION['philhealth_claim_started_at'])) {
        $_SESSION['philhealth_claim_started_at'] = time();
    }
    json_response(['ok' => true, 'active' => true]);
}

if ($action === 'cancel') {
    unset($_SESSION['philhealth_claim_active'], $_SESSION['philhealth_claim_started_at']);
    json_response(['ok' => true, 'active' => false]);
}

json_response(['ok' => false, 'error' => 'Invalid action'], 400);
