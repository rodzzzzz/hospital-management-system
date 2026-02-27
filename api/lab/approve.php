<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $requestId = $data['request_id'] ?? null;
    if (!is_int($requestId) && !(is_string($requestId) && ctype_digit((string)$requestId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid request_id'], 400);
    }
    $requestId = (int)$requestId;

    $action = strtolower(trim((string)($data['action'] ?? '')));
    if (!in_array($action, ['approve', 'reject'], true)) {
        json_response(['ok' => false, 'error' => 'Invalid action'], 400);
    }

    $reason = trim((string)($data['reason'] ?? ''));
    if ($action === 'reject' && $reason === '') {
        json_response(['ok' => false, 'error' => 'Missing reason'], 400);
    }

    $pdo = db();
    ensure_lab_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $approvedBy = trim((string)($authUser['full_name'] ?? ''));
    if ($approvedBy === '') {
        $approvedBy = trim((string)($authUser['username'] ?? ''));
    }
    if ($approvedBy === '') {
        $approvedBy = 'Doctor';
    }

    $stmt = $pdo->prepare('SELECT id, status FROM lab_requests WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $requestId]);
    $req = $stmt->fetch();
    if (!$req) {
        json_response(['ok' => false, 'error' => 'Request not found'], 404);
    }

    $newStatus = ($action === 'approve') ? 'approved' : 'rejected';

    $update = $pdo->prepare(
        "UPDATE lab_requests
         SET status = :status,
             approved_by = :approved_by,
             approved_at = :approved_at,
             rejection_reason = :rejection_reason
         WHERE id = :id"
    );

    $update->execute([
        'status' => $newStatus,
        'approved_by' => $approvedBy,
        'approved_at' => (new DateTimeImmutable('now'))->format('Y-m-d H:i:s'),
        'rejection_reason' => ($action === 'reject') ? $reason : null,
        'id' => $requestId,
    ]);

    json_response([
        'ok' => true,
        'status' => $newStatus,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
