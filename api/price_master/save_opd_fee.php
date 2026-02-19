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

    $feeCode = strtolower(trim((string)($data['fee_code'] ?? '')));
    if ($feeCode === '') {
        json_response(['ok' => false, 'error' => 'Missing fee_code'], 400);
    }

    $feeName = trim((string)($data['fee_name'] ?? ''));
    if ($feeName === '') {
        json_response(['ok' => false, 'error' => 'Missing fee_name'], 400);
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
        'INSERT INTO opd_fees (fee_code, fee_name, price) VALUES (:fee_code, :fee_name, :price)
         ON DUPLICATE KEY UPDATE fee_name = VALUES(fee_name), price = VALUES(price)'
    );
    $stmt->execute([
        'fee_code' => $feeCode,
        'fee_name' => $feeName,
        'price' => number_format($price, 2, '.', ''),
    ]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
