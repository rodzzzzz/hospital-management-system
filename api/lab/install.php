<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

try {
    $pdo = db();
    ensure_lab_tables($pdo);

    json_response([
        'ok' => true,
        'message' => 'Lab tables are ready.',
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
