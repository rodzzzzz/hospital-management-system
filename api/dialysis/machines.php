<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_schema.php';

cors_headers();

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);
    $machines = $pdo->query('SELECT id, machine_code, status FROM dialysis_machines ORDER BY machine_code')->fetchAll();

    json_response([
        'ok' => true,
        'machines' => $machines,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
