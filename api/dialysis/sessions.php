<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_schema.php';

try {
    $pdo = db();
    dialysis_ensure_schema($pdo);
    $date = trim((string)($_GET['date'] ?? ''));
    if ($date === '') {
        $date = (new DateTimeImmutable('today'))->format('Y-m-d');
    }

    $stmt = $pdo->prepare(
        "SELECT s.id,
                p.patient_code,
                p.full_name,
                m.machine_code,
                s.start_time,
                s.end_time,
                s.status
         FROM dialysis_sessions s
         JOIN dialysis_patients p ON p.id = s.patient_id
         JOIN dialysis_machines m ON m.id = s.machine_id
         WHERE DATE(s.start_time) = :d
         ORDER BY s.start_time ASC"
    );
    $stmt->execute(['d' => $date]);

    json_response([
        'ok' => true,
        'date' => $date,
        'sessions' => $stmt->fetchAll(),
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
