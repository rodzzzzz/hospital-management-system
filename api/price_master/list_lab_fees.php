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
    ensure_price_master_tables($pdo);

    $stmt = $pdo->query('SELECT test_code, test_name, price, updated_at FROM lab_test_fees ORDER BY test_name ASC, test_code ASC');
    $rows = $stmt ? $stmt->fetchAll() : [];

    $fees = array_map(function ($r) {
        return [
            'test_code' => (string)($r['test_code'] ?? ''),
            'test_name' => (string)($r['test_name'] ?? ''),
            'price' => number_format((float)($r['price'] ?? 0), 2, '.', ''),
            'updated_at' => (string)($r['updated_at'] ?? ''),
        ];
    }, is_array($rows) ? $rows : []);

    json_response(['ok' => true, 'fees' => $fees]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
