<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();

try {
    $pdo = philhealth_db();
    $pin = require_string('pin');

    $stmt = $pdo->prepare('SELECT 1 FROM patients WHERE philhealth_pin = :pin LIMIT 1');
    $stmt->execute(['pin' => $pin]);
    $exists = $stmt->fetch() !== false;

    json_response([
        'ok' => true,
        'exists' => $exists,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
