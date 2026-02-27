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
    ensure_patient_queue_table($pdo);

    $status = trim((string)($_GET['status'] ?? 'waiting'));
    if ($status === '') $status = 'waiting';
    
    // Map new status names to old database values
    $statusMap = [
        'queued' => 'waiting',
        'confirmed' => 'in_progress',
        'cancelled' => 'cancelled'
    ];
    $dbStatus = $statusMap[$status] ?? $status;

    $limit = 200;

    $stmt = $pdo->prepare('SELECT id, patient_id, station_id, queue_number, queue_position, status, staff_user_id, arrived_at, started_at, completed_at, notes, estimated_wait_minutes, service_duration_minutes, created_at, updated_at FROM patient_queue WHERE status = :status ORDER BY created_at DESC LIMIT ' . $limit);
    $stmt->execute(['status' => $dbStatus]);
    $rows = $stmt->fetchAll();

    // Map old database status values back to new format
    $reverseStatusMap = [
        'waiting' => 'queued',
        'in_progress' => 'confirmed',
        'completed' => 'completed',
        'cancelled' => 'cancelled',
        'skipped' => 'skipped',
        'unavailable' => 'unavailable'
    ];
    
    $items = [];
    foreach ($rows as $r) {
        $items[] = [
            'id' => (int)$r['id'],
            'patient_id' => (int)$r['patient_id'],
            'station_id' => (int)$r['station_id'],
            'queue_number' => (int)$r['queue_number'],
            'queue_position' => (int)$r['queue_position'],
            'status' => $reverseStatusMap[$r['status']] ?? $r['status'],
            'staff_user_id' => $r['staff_user_id'] !== null ? (int)$r['staff_user_id'] : null,
            'arrived_at' => $r['arrived_at'],
            'started_at' => $r['started_at'],
            'completed_at' => $r['completed_at'],
            'notes' => $r['notes'],
            'estimated_wait_minutes' => $r['estimated_wait_minutes'] !== null ? (int)$r['estimated_wait_minutes'] : null,
            'service_duration_minutes' => $r['service_duration_minutes'] !== null ? (int)$r['service_duration_minutes'] : null,
            'created_at' => $r['created_at'],
            'updated_at' => $r['updated_at'],
        ];
    }

    json_response([
        'ok' => true,
        'queue' => $items,
    ]);
} catch (Throwable $e) {
    json_response([
        'ok' => false,
        'error' => $e->getMessage(),
    ], 500);
}
