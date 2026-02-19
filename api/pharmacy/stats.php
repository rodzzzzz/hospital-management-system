<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $totalMedicines = (int)($pdo->query('SELECT COUNT(*) AS c FROM pharmacy_medicines')->fetch()['c'] ?? 0);
    $lowStock = (int)($pdo->query('SELECT COUNT(*) AS c FROM pharmacy_medicines WHERE quantity > 0 AND quantity <= min_quantity')->fetch()['c'] ?? 0);
    $outOfStock = (int)($pdo->query('SELECT COUNT(*) AS c FROM pharmacy_medicines WHERE quantity <= 0')->fetch()['c'] ?? 0);
    $totalResits = (int)($pdo->query('SELECT COUNT(*) AS c FROM pharmacy_resits')->fetch()['c'] ?? 0);

    json_response([
        'ok' => true,
        'stats' => [
            'total_medicines' => $totalMedicines,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'total_resits' => $totalResits,
        ],
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
