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

    $code = trim((string)($data['medicine_code'] ?? ''));
    $name = trim((string)($data['name'] ?? ''));
    $category = trim((string)($data['category'] ?? ''));
    $manufacturer = trim((string)($data['manufacturer'] ?? ''));
    $description = trim((string)($data['description'] ?? ''));

    $quantityRaw = $data['quantity'] ?? 0;
    $minQtyRaw = $data['min_quantity'] ?? 0;

    if ($name === '') {
        json_response(['ok' => false, 'error' => 'Medicine name is required'], 400);
    }

    if (!is_int($quantityRaw) && !(is_string($quantityRaw) && preg_match('/^-?\d+$/', $quantityRaw))) {
        json_response(['ok' => false, 'error' => 'Invalid quantity'], 400);
    }
    if (!is_int($minQtyRaw) && !(is_string($minQtyRaw) && preg_match('/^-?\d+$/', $minQtyRaw))) {
        json_response(['ok' => false, 'error' => 'Invalid min_quantity'], 400);
    }

    $quantity = (int)$quantityRaw;
    $minQty = (int)$minQtyRaw;
    if ($quantity < 0) $quantity = 0;
    if ($minQty < 0) $minQty = 0;

    $priceRaw = $data['price'] ?? null;
    $price = null;
    if ($priceRaw !== null && $priceRaw !== '') {
        if (!is_numeric($priceRaw)) {
            json_response(['ok' => false, 'error' => 'Invalid price'], 400);
        }
        $price = (string)$priceRaw;
    }

    $expiryRaw = trim((string)($data['expiry_date'] ?? ''));
    $expiry = null;
    if ($expiryRaw !== '') {
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $expiryRaw)) {
            json_response(['ok' => false, 'error' => 'Invalid expiry_date (use YYYY-MM-DD)'], 400);
        }
        $expiry = $expiryRaw;
    }

    $pdo = db();
    ensure_pharmacy_tables($pdo);

    if ($code !== '') {
        $stmt = $pdo->prepare(
            "INSERT INTO pharmacy_medicines (medicine_code, name, category, quantity, min_quantity, price, expiry_date, manufacturer, description)
             VALUES (:code, :name, :category, :quantity, :min_quantity, :price, :expiry_date, :manufacturer, :description)
             ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                category = VALUES(category),
                quantity = VALUES(quantity),
                min_quantity = VALUES(min_quantity),
                price = VALUES(price),
                expiry_date = VALUES(expiry_date),
                manufacturer = VALUES(manufacturer),
                description = VALUES(description)"
        );
        $stmt->execute([
            'code' => $code,
            'name' => $name,
            'category' => ($category !== '' ? $category : null),
            'quantity' => $quantity,
            'min_quantity' => $minQty,
            'price' => $price,
            'expiry_date' => $expiry,
            'manufacturer' => ($manufacturer !== '' ? $manufacturer : null),
            'description' => ($description !== '' ? $description : null),
        ]);

        $idStmt = $pdo->prepare('SELECT id FROM pharmacy_medicines WHERE medicine_code = :code LIMIT 1');
        $idStmt->execute(['code' => $code]);
        $row = $idStmt->fetch();
        $medicineId = $row ? (int)$row['id'] : 0;

        json_response(['ok' => true, 'medicine_id' => $medicineId]);
    }

    $stmt = $pdo->prepare(
        'INSERT INTO pharmacy_medicines (medicine_code, name, category, quantity, min_quantity, price, expiry_date, manufacturer, description)
         VALUES (NULL, :name, :category, :quantity, :min_quantity, :price, :expiry_date, :manufacturer, :description)'
    );
    $stmt->execute([
        'name' => $name,
        'category' => ($category !== '' ? $category : null),
        'quantity' => $quantity,
        'min_quantity' => $minQty,
        'price' => $price,
        'expiry_date' => $expiry,
        'manufacturer' => ($manufacturer !== '' ? $manufacturer : null),
        'description' => ($description !== '' ? $description : null),
    ]);

    json_response(['ok' => true, 'medicine_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
