<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $id = $_GET['id'] ?? null;
    if ($id === null || $id === '' || !ctype_digit((string)$id)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$id;

    $stmt = $pdo->prepare(
        "SELECT
            r.id,
            r.patient_id,
            r.prescribed_by,
            r.notes,
            r.created_at AS submitted_at,
            p.patient_code,
            p.full_name
         FROM pharmacy_resits r
         LEFT JOIN patients p ON r.patient_id = p.id
         WHERE r.id = :id
         LIMIT 1"
    );
    $stmt->execute(['id' => $id]);
    $resit = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$resit) {
        json_response(['ok' => false, 'error' => 'Resit not found'], 404);
    }

    $itemsStmt = $pdo->prepare(
        'SELECT medicine_id, medicine_name, qty, instructions FROM pharmacy_resit_items WHERE resit_id = :resit_id ORDER BY id ASC'
    );
    $itemsStmt->execute(['resit_id' => $id]);
    $items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);
    $resit['items'] = array_map(function ($it) {
        return [
            'medicine_id' => ($it['medicine_id'] === null ? null : (int)$it['medicine_id']),
            'name' => (string)($it['medicine_name'] ?? ''),
            'qty' => (string)($it['qty'] ?? ''),
            'sig' => (string)($it['instructions'] ?? ''),
        ];
    }, $items);

    json_response(['ok' => true, 'resit' => $resit]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
