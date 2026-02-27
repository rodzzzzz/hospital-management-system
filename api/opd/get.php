<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../users/_tables.php';

cors_headers();
require_method('GET');

try {
    $idRaw = $_GET['id'] ?? null;
    if ($idRaw === null || $idRaw === '' || !ctype_digit((string)$idRaw)) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$idRaw;

    $pdo = db();
    ensure_opd_tables($pdo);
    ensure_users_tables($pdo);

    $stmt = $pdo->prepare(
        'SELECT a.*, p.patient_code, p.full_name, u.full_name AS approved_by_name
         FROM opd_appointments a
         JOIN patients p ON p.id = a.patient_id
         LEFT JOIN users u ON u.id = a.approved_by_user_id
         WHERE a.id = :id
         LIMIT 1'
    );
    $stmt->execute(['id' => $id]);
    $row = $stmt->fetch();

    if (!$row) {
        json_response(['ok' => false, 'error' => 'Appointment not found'], 404);
    }

    json_response([
        'ok' => true,
        'appointment' => $row,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
