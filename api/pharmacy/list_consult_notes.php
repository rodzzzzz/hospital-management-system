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
    ensure_pharmacy_tables($pdo);

    $limit = 200;

    $stmt = $pdo->query(
        'SELECT n.id, n.source_module, n.source_note_id, n.patient_id, p.patient_code, p.full_name,
                n.provider_name, n.note_created_at, n.submitted_by_name, n.submitted_at
         FROM pharmacy_consultation_notes n
         JOIN patients p ON p.id = n.patient_id
         ORDER BY n.submitted_at DESC, n.id DESC
         LIMIT ' . (int)$limit
    );

    $rows = $stmt->fetchAll();

    json_response([
        'ok' => true,
        'notes' => $rows,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
