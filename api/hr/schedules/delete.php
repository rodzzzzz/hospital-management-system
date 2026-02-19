<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $idRaw = $data['schedule_id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit($idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid schedule_id'], 400);
    }
    $id = (int)$idRaw;

    $pdo = db();
    ensure_hr_tables($pdo);

    $stmt = $pdo->prepare('DELETE FROM hr_schedules WHERE id = :id');
    $stmt->execute(['id' => $id]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
