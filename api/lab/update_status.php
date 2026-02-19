<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $requestId = $data['request_id'] ?? null;
    if (!is_int($requestId) && !(is_string($requestId) && ctype_digit($requestId))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid request_id'], 400);
    }
    $requestId = (int)$requestId;

    $status = strtolower(trim((string)($data['status'] ?? '')));
    if (!in_array($status, ['collected', 'in_progress', 'completed', 'cancelled'], true)) {
        json_response(['ok' => false, 'error' => 'Invalid status'], 400);
    }

    $pdo = db();
    ensure_lab_tables($pdo);

    $stmt = $pdo->prepare('SELECT status FROM lab_requests WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $requestId]);
    $row = $stmt->fetch();
    if (!$row) {
        json_response(['ok' => false, 'error' => 'Request not found'], 404);
    }

    $pdo->beginTransaction();

    $pdo->prepare('UPDATE lab_requests SET status = :status WHERE id = :id')->execute([
        'status' => $status,
        'id' => $requestId,
    ]);

    if ($status === 'in_progress') {
        $pdo->prepare("UPDATE lab_request_items SET status = 'in_progress' WHERE request_id = :id")->execute([
            'id' => $requestId,
        ]);
    } elseif ($status === 'completed') {
        $pdo->prepare("UPDATE lab_request_items SET status = 'completed' WHERE request_id = :id")->execute([
            'id' => $requestId,
        ]);
    } elseif ($status === 'cancelled') {
        $pdo->prepare("UPDATE lab_request_items SET status = 'cancelled' WHERE request_id = :id")->execute([
            'id' => $requestId,
        ]);
    }

    $pdo->commit();

    json_response(['ok' => true]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
