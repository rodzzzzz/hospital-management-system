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

    $employeeIdRaw = $data['employee_id'] ?? null;
    if (!is_int($employeeIdRaw) && !(is_string($employeeIdRaw) && ctype_digit($employeeIdRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid employee_id'], 400);
    }
    $employeeId = (int)$employeeIdRaw;

    $shiftDate = trim((string)($data['shift_date'] ?? ''));
    $startTime = trim((string)($data['start_time'] ?? ''));
    $endTime = trim((string)($data['end_time'] ?? ''));

    if ($shiftDate === '' || $startTime === '' || $endTime === '') {
        json_response(['ok' => false, 'error' => 'Missing shift_date, start_time, or end_time'], 400);
    }

    $notes = isset($data['notes']) ? trim((string)$data['notes']) : null;
    if ($notes === '') $notes = null;

    $pdo = db();
    ensure_hr_tables($pdo);

    $stmt = $pdo->prepare('SELECT id FROM hr_employees WHERE id = :id');
    $stmt->execute(['id' => $employeeId]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Employee not found'], 404);
    }

    $ins = $pdo->prepare('INSERT INTO hr_schedules (employee_id, shift_date, start_time, end_time, notes) VALUES (:employee_id, :shift_date, :start_time, :end_time, :notes)');
    $ins->execute([
        'employee_id' => $employeeId,
        'shift_date' => $shiftDate,
        'start_time' => $startTime,
        'end_time' => $endTime,
        'notes' => $notes,
    ]);

    json_response(['ok' => true, 'schedule_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
