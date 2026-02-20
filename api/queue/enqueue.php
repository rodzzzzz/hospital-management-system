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

    $fullName = trim((string)($data['full_name'] ?? ''));
    if ($fullName === '') {
        json_response(['ok' => false, 'error' => 'Missing full_name'], 400);
    }

    $pdo = db();
    ensure_patient_queue_table($pdo);

    $payloadJson = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    if (!is_string($payloadJson)) {
        json_response(['ok' => false, 'error' => 'Failed to encode payload'], 500);
    }

    $stmt = $pdo->prepare("INSERT INTO patient_queue (payload_json, status) VALUES (:payload_json, 'queued')");
    $stmt->execute(['payload_json' => $payloadJson]);

    $queueId = (int)$pdo->lastInsertId();

    json_response([
        'ok' => true,
        'queue_id' => $queueId,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
