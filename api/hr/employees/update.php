<?php
declare(strict_types=1);

require_once __DIR__ . '/../../_db.php';
require_once __DIR__ . '/../../_response.php';
require_once __DIR__ . '/../../_cors.php';
require_once __DIR__ . '/../_tables.php';

cors_headers();
require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $idRaw = $data['employee_id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit($idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid employee_id'], 400);
    }
    $id = (int)$idRaw;

    $fullName = isset($data['full_name']) ? trim((string)$data['full_name']) : null;
    $employeeCode = array_key_exists('employee_code', $data) ? trim((string)($data['employee_code'] ?? '')) : null;
    $phone = array_key_exists('phone', $data) ? trim((string)($data['phone'] ?? '')) : null;
    $email = array_key_exists('email', $data) ? trim((string)($data['email'] ?? '')) : null;

    if ($employeeCode !== null && $employeeCode === '') $employeeCode = null;
    if ($phone !== null && $phone === '') $phone = null;
    if ($email !== null && $email === '') $email = null;

    $status = isset($data['status']) ? strtolower(trim((string)$data['status'])) : null;
    if ($status !== null && !in_array($status, ['active', 'inactive'], true)) {
        $status = null;
    }

    $deptProvided = array_key_exists('department_id', $data);
    $deptId = null;
    if ($deptProvided) {
        $deptIdRaw = $data['department_id'] ?? null;
        if ($deptIdRaw !== null && $deptIdRaw !== '' && ((is_int($deptIdRaw)) || (is_string($deptIdRaw) && ctype_digit($deptIdRaw)))) {
            $deptId = (int)$deptIdRaw;
            if ($deptId <= 0) $deptId = null;
        }
    }

    $posProvided = array_key_exists('position_id', $data);
    $posId = null;
    if ($posProvided) {
        $posIdRaw = $data['position_id'] ?? null;
        if ($posIdRaw !== null && $posIdRaw !== '' && ((is_int($posIdRaw)) || (is_string($posIdRaw) && ctype_digit($posIdRaw)))) {
            $posId = (int)$posIdRaw;
            if ($posId <= 0) $posId = null;
        }
    }

    $fields = [];
    $params = ['id' => $id];

    if ($fullName !== null && $fullName !== '') {
        $fields[] = 'full_name = :full_name';
        $params['full_name'] = $fullName;
    }

    if ($employeeCode !== null) {
        $fields[] = 'employee_code = :employee_code';
        $params['employee_code'] = $employeeCode;
    }

    if ($phone !== null) {
        $fields[] = 'phone = :phone';
        $params['phone'] = $phone;
    }

    if ($email !== null) {
        $fields[] = 'email = :email';
        $params['email'] = $email;
    }

    if ($deptProvided) {
        $fields[] = 'department_id = :department_id';
        $params['department_id'] = $deptId;
    }

    if ($posProvided) {
        $fields[] = 'position_id = :position_id';
        $params['position_id'] = $posId;
    }

    if ($status !== null) {
        $fields[] = 'status = :status';
        $params['status'] = $status;
    }

    if (count($fields) === 0) {
        json_response(['ok' => true]);
    }

    $pdo = db();
    ensure_hr_tables($pdo);

    $stmt = $pdo->prepare('SELECT id FROM hr_employees WHERE id = :id');
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        json_response(['ok' => false, 'error' => 'Employee not found'], 404);
    }

    $sql = 'UPDATE hr_employees SET ' . implode(', ', $fields) . ' WHERE id = :id';

    try {
        $pdo->prepare($sql)->execute($params);
    } catch (Throwable $e) {
        json_response(['ok' => false, 'error' => 'Unable to update employee'], 409);
    }

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
