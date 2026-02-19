<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);
    $q = trim((string)($_GET['q'] ?? ''));

    if ($q === '') {
        $stmt = $pdo->query('SELECT id, patient_code, full_name FROM dialysis_patients ORDER BY full_name LIMIT 100');
        json_response(['ok' => true, 'patients' => $stmt->fetchAll()]);
    }

    $stmt = $pdo->prepare('SELECT id, patient_code, full_name FROM dialysis_patients WHERE patient_code LIKE :q OR full_name LIKE :q ORDER BY full_name LIMIT 50');
    $stmt->execute(['q' => '%' . $q . '%']);

    json_response([
        'ok' => true,
        'patients' => $stmt->fetchAll(),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
