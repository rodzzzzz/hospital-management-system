<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';

cors_headers();
require_method('POST');

try {
    $pdo = db();
    ensure_hr_tables($pdo);

    $countEmployees = (int)$pdo->query('SELECT COUNT(*) FROM hr_employees')->fetchColumn();
    if ($countEmployees > 0) {
        json_response(['ok' => true, 'seeded' => false, 'message' => 'Seed data already exists']);
    }

    $pdo->exec("INSERT INTO hr_departments (name, status) VALUES
        ('Human Resources','active'),
        ('Nursing','active'),
        ('Laboratory','active')");

    $deptHr = (int)$pdo->query("SELECT id FROM hr_departments WHERE name='Human Resources' LIMIT 1")->fetchColumn();
    $deptNursing = (int)$pdo->query("SELECT id FROM hr_departments WHERE name='Nursing' LIMIT 1")->fetchColumn();
    $deptLab = (int)$pdo->query("SELECT id FROM hr_departments WHERE name='Laboratory' LIMIT 1")->fetchColumn();

    $stmtPos = $pdo->prepare('INSERT INTO hr_positions (department_id, name, status) VALUES (:department_id, :name, :status)');
    $stmtPos->execute(['department_id' => $deptHr ?: null, 'name' => 'HR Assistant', 'status' => 'active']);
    $stmtPos->execute(['department_id' => $deptNursing ?: null, 'name' => 'Staff Nurse', 'status' => 'active']);
    $stmtPos->execute(['department_id' => $deptLab ?: null, 'name' => 'Med Tech', 'status' => 'active']);

    $posHr = (int)$pdo->query("SELECT id FROM hr_positions WHERE name='HR Assistant' LIMIT 1")->fetchColumn();
    $posNurse = (int)$pdo->query("SELECT id FROM hr_positions WHERE name='Staff Nurse' LIMIT 1")->fetchColumn();
    $posLab = (int)$pdo->query("SELECT id FROM hr_positions WHERE name='Med Tech' LIMIT 1")->fetchColumn();

    $stmtEmp = $pdo->prepare('INSERT INTO hr_employees (employee_code, full_name, phone, email, department_id, position_id, status) VALUES (:employee_code, :full_name, :phone, :email, :department_id, :position_id, :status)');
    $stmtEmp->execute([
        'employee_code' => 'EMP-0001',
        'full_name' => 'Maria Santos',
        'phone' => '0917-000-0001',
        'email' => 'maria.santos@example.com',
        'department_id' => $deptHr ?: null,
        'position_id' => $posHr ?: null,
        'status' => 'active',
    ]);
    $emp1 = (int)$pdo->lastInsertId();

    $stmtEmp->execute([
        'employee_code' => 'EMP-0002',
        'full_name' => 'John Dela Cruz',
        'phone' => '0917-000-0002',
        'email' => 'john.delacruz@example.com',
        'department_id' => $deptNursing ?: null,
        'position_id' => $posNurse ?: null,
        'status' => 'active',
    ]);
    $emp2 = (int)$pdo->lastInsertId();

    $stmtEmp->execute([
        'employee_code' => 'EMP-0003',
        'full_name' => 'Anne Reyes',
        'phone' => '0917-000-0003',
        'email' => 'anne.reyes@example.com',
        'department_id' => $deptLab ?: null,
        'position_id' => $posLab ?: null,
        'status' => 'active',
    ]);
    $emp3 = (int)$pdo->lastInsertId();

    $stmtSched = $pdo->prepare('INSERT INTO hr_schedules (employee_id, shift_date, start_time, end_time, notes) VALUES (:employee_id, :shift_date, :start_time, :end_time, :notes)');
    $today = new DateTimeImmutable('now');

    for ($i = 0; $i < 10; $i++) {
        $d = $today->sub(new DateInterval('P' . $i . 'D'))->format('Y-m-d');

        $stmtSched->execute([
            'employee_id' => $emp1,
            'shift_date' => $d,
            'start_time' => '08:00:00',
            'end_time' => '17:00:00',
            'notes' => $i % 4 === 0 ? 'On-site' : null,
        ]);

        if ($i % 2 === 0) {
            $stmtSched->execute([
                'employee_id' => $emp2,
                'shift_date' => $d,
                'start_time' => '07:00:00',
                'end_time' => '19:00:00',
                'notes' => $i % 3 === 0 ? 'Overtime' : null,
            ]);
        }

        if ($i % 3 === 0) {
            $stmtSched->execute([
                'employee_id' => $emp3,
                'shift_date' => $d,
                'start_time' => '09:00:00',
                'end_time' => '18:00:00',
                'notes' => null,
            ]);
        }
    }

    json_response(['ok' => true, 'seeded' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
