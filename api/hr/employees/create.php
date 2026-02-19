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

    $fullName = trim((string)($data['full_name'] ?? ''));
    if ($fullName === '') {
        json_response(['ok' => false, 'error' => 'Missing full_name'], 400);
    }

    $employeeCode = isset($data['employee_code']) ? trim((string)$data['employee_code']) : null;
    if ($employeeCode === '') $employeeCode = null;

    $phone = isset($data['phone']) ? trim((string)$data['phone']) : null;
    if ($phone === '') $phone = null;

    $email = isset($data['email']) ? trim((string)$data['email']) : null;
    if ($email === '') $email = null;

    $status = strtolower(trim((string)($data['status'] ?? 'active')));
    if (!in_array($status, ['active', 'inactive'], true)) {
        $status = 'active';
    }

    $deptIdRaw = $data['department_id'] ?? null;
    $deptId = null;
    if ($deptIdRaw !== null && $deptIdRaw !== '' && ((is_int($deptIdRaw)) || (is_string($deptIdRaw) && ctype_digit($deptIdRaw)))) {
        $deptId = (int)$deptIdRaw;
        if ($deptId <= 0) $deptId = null;
    }

    $posIdRaw = $data['position_id'] ?? null;
    $posId = null;
    if ($posIdRaw !== null && $posIdRaw !== '' && ((is_int($posIdRaw)) || (is_string($posIdRaw) && ctype_digit($posIdRaw)))) {
        $posId = (int)$posIdRaw;
        if ($posId <= 0) $posId = null;
    }

    $pdo = db();
    ensure_hr_tables($pdo);

    $stmt = $pdo->prepare(
        'INSERT INTO hr_employees (employee_code, full_name, phone, email, department_id, position_id, status) VALUES (:employee_code, :full_name, :phone, :email, :department_id, :position_id, :status)'
    );

    try {
        $stmt->execute([
            'employee_code' => $employeeCode,
            'full_name' => $fullName,
            'phone' => $phone,
            'email' => $email,
            'department_id' => $deptId,
            'position_id' => $posId,
            'status' => $status,
        ]);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Employee code already exists'], 409);
    }

    json_response(['ok' => true, 'employee_id' => (int)$pdo->lastInsertId()]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
