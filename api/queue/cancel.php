<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $queueIdRaw = $data['queue_id'] ?? null;
    if (!is_numeric($queueIdRaw) || (int)$queueIdRaw <= 0) {
        json_response(['ok' => false, 'error' => 'Missing queue_id'], 400);
    }
    $queueId = (int)$queueIdRaw;

    $pdo = db();
    ensure_patient_queue_table($pdo);

    $stmt = $pdo->prepare('UPDATE patient_queue SET status = \"cancelled\" WHERE id = :id AND status = \"queued\"');
    $stmt->execute(['id' => $queueId]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
