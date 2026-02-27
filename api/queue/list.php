<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_patient_queue_table($pdo);

    $status = trim((string)($_GET['status'] ?? 'queued'));
    if ($status === '') $status = 'queued';

    $limit = 200;

    $stmt = $pdo->prepare('SELECT id, payload_json, status, confirmed_patient_id, confirmed_by, confirmed_at, created_at, updated_at FROM patient_queue WHERE status = :status ORDER BY created_at DESC LIMIT ' . $limit);
    $stmt->execute(['status' => $status]);
    $rows = $stmt->fetchAll();

    $items = [];
    foreach ($rows as $r) {
        $payload = json_decode((string)($r['payload_json'] ?? ''), true);
        if (!is_array($payload)) $payload = [];
        $items[] = [
            'id' => (int)$r['id'],
            'status' => (string)$r['status'],
            'payload' => $payload,
            'confirmed_patient_id' => $r['confirmed_patient_id'] !== null ? (int)$r['confirmed_patient_id'] : null,
            'confirmed_by' => $r['confirmed_by'],
            'confirmed_at' => $r['confirmed_at'],
            'created_at' => $r['created_at'],
            'updated_at' => $r['updated_at'],
        ];
    }

    json_response([
        'ok' => true,
        'queue' => $items,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
