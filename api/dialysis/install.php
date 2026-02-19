<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

try {
    $pdo = db();

    dialysis_ensure_schema($pdo);

    json_response([
        'ok' => true,
        'message' => 'Dialysis tables are ready.',
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
