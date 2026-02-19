<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

require_method('GET');

try {
    $id = require_int('id');

    $pdo = db();
    ensure_pharmacy_tables($pdo);

    $stmt = $pdo->prepare(
        'SELECT n.*, p.patient_code, p.full_name
         FROM pharmacy_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         WHERE n.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();
    if (!$row) {
        json_response(['ok' => false, 'error' => 'Note not found'], 404);
    }

    json_response([
        'ok' => true,
        'note' => $row,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
