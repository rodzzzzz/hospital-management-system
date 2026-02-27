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

    $testCode = strtolower(trim((string)($data['test_code'] ?? '')));
    if ($testCode === '') {
        json_response(['ok' => false, 'error' => 'Missing test_code'], 400);
    }

    $testName = trim((string)($data['test_name'] ?? ''));
    if ($testName === '') {
        json_response(['ok' => false, 'error' => 'Missing test_name'], 400);
    }

    $priceRaw = $data['price'] ?? null;
    if ($priceRaw === null || $priceRaw === '' || !is_numeric((string)$priceRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid price'], 400);
    }

    $price = (float)$priceRaw;
    if ($price < 0) {
        json_response(['ok' => false, 'error' => 'Price cannot be negative'], 400);
    }

    $pdo = db();
    ensure_price_master_tables($pdo);

    $stmt = $pdo->prepare(
        'INSERT INTO lab_test_fees (test_code, test_name, price) VALUES (:test_code, :test_name, :price)
         ON DUPLICATE KEY UPDATE test_name = VALUES(test_name), price = VALUES(price)'
    );
    $stmt->execute([
        'test_code' => $testCode,
        'test_name' => $testName,
        'price' => number_format($price, 2, '.', ''),
    ]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
