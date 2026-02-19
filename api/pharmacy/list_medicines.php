<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $q = trim((string)($_GET['q'] ?? ''));
    $limit = 200;

    if ($q !== '') {
        $qLower = strtolower($q);
        $stmt = $pdo->prepare(
            "SELECT id, medicine_code, name, category, quantity, min_quantity, price, expiry_date, manufacturer, updated_at
             FROM pharmacy_medicines
             WHERE LOWER(name) LIKE :q_like OR LOWER(medicine_code) LIKE :q_like_code
             ORDER BY updated_at DESC
             LIMIT {$limit}"
        );
        $stmt->execute([
            'q_like' => '%' . $qLower . '%',
            'q_like_code' => '%' . $qLower . '%',
        ]);
        $rows = $stmt->fetchAll();
    } else {
        $stmt = $pdo->query(
            "SELECT id, medicine_code, name, category, quantity, min_quantity, price, expiry_date, manufacturer, updated_at
             FROM pharmacy_medicines
             ORDER BY updated_at DESC
             LIMIT {$limit}"
        );
        $rows = $stmt->fetchAll();
    }

    json_response([
        'ok' => true,
        'medicines' => $rows,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
