<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    ensure_patient_queue_table($pdo);

    $count = (int)$pdo->query("SELECT COUNT(*) AS c FROM patient_queue")->fetchColumn();

    json_response([
        'ok' => true,
        'count' => $count,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
