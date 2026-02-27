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

    $payload = $data['payload'] ?? null;
    if (!is_array($payload)) {
        json_response(['ok' => false, 'error' => 'Missing payload'], 400);
    }

    $fullName = trim((string)($payload['full_name'] ?? ''));
    if ($fullName === '') {
        json_response(['ok' => false, 'error' => 'Missing full_name'], 400);
    }

    $pdo = db();
    ensure_patient_queue_table($pdo);

    $payloadJson = json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (!is_string($payloadJson)) {
        json_response(['ok' => false, 'error' => 'Failed to encode payload'], 500);
    }

    $stmt = $pdo->prepare('UPDATE patient_queue SET payload_json = :payload_json WHERE id = :id AND status = \'queued\'');
    $stmt->execute([
        'payload_json' => $payloadJson,
        'id' => $queueId,
    ]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
