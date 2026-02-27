<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';

cors_headers();
require_method('GET');

try {
    $idRaw = $_GET['id'] ?? null;
    if ($idRaw === null || $idRaw === '' || !ctype_digit((string)$idRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }

    $id = (int)$idRaw;

    $pdo = db();
    $stmt = $pdo->prepare('SELECT * FROM patients WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $patient = $stmt->fetch();

    if (!$patient) {
        json_response(['ok' => false, 'error' => 'Patient not found'], 404);
    }

    json_response([
        'ok' => true,
        'patient' => $patient,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
