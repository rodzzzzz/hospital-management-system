<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

cors_headers();
require_method('POST');

auth_session_start();

try {
    $pdo = db();
    ensure_users_tables($pdo);

    $actor = auth_current_user_optional_token($pdo);
    if (!$actor) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $actorIsAdmin = auth_user_has_module($actor, 'ADMIN');
    if (!$actorIsAdmin) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    $accounts = (is_array($data) && isset($data['accounts']) && is_array($data['accounts'])) ? $data['accounts'] : null;

    if ($accounts === null) {
        $accounts = [
            [
                'username' => 'ernurse@gmail.com',
                'full_name' => 'ER Nurse',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'ER', 'role' => 'ER Nurse']],
            ],
            [
                'username' => 'np@gmail.com',
                'full_name' => 'NP User',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'ER', 'role' => 'NP/PA']],
            ],
            [
                'username' => 'pa@gmail.com',
                'full_name' => 'PA User',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'ER', 'role' => 'NP/PA']],
            ],
            [
                'username' => 'medtech@gmail.com',
                'full_name' => 'MedTech',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'LAB', 'role' => 'MedTech']],
            ],
            [
                'username' => 'labsupervisor@gmail.com',
                'full_name' => 'Lab Supervisor',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'LAB', 'role' => 'Lab Supervisor']],
            ],
            [
                'username' => 'cashier@gmail.com',
                'full_name' => 'Cashier',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'CASHIER', 'role' => 'Cashier']],
            ],
            [
                'username' => 'billing@gmail.com',
                'full_name' => 'Billing',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'CASHIER', 'role' => 'Billing']],
            ],
            [
                'username' => 'pharmacist@gmail.com',
                'full_name' => 'Pharmacist',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'PHARMACY', 'role' => 'Pharmacist']],
            ],
            [
                'username' => 'pharmacyassistant@gmail.com',
                'full_name' => 'Pharmacy Assistant',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'PHARMACY', 'role' => 'Pharmacy Assistant']],
            ],
            [
                'username' => 'opdnurse@gmail.com',
                'full_name' => 'OPD Nurse',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'OPD', 'role' => 'OPD Nurse']],
            ],
            [
                'username' => 'opdclerk@gmail.com',
                'full_name' => 'OPD Clerk',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'OPD', 'role' => 'OPD Clerk']],
            ],
            [
                'username' => 'hrstaff@gmail.com',
                'full_name' => 'HR Staff',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'HR', 'role' => 'HR Staff']],
            ],
            [
                'username' => 'hradmin@gmail.com',
                'full_name' => 'HR Admin',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'HR', 'role' => 'HR Admin']],
            ],
            [
                'username' => 'icunurse@gmail.com',
                'full_name' => 'ICU Nurse',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'ICU', 'role' => 'ICU Nurse']],
            ],
            [
                'username' => 'icustaff@gmail.com',
                'full_name' => 'ICU Staff',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'ICU', 'role' => 'ICU Staff']],
            ],
            [
                'username' => 'xraytech@gmail.com',
                'full_name' => 'Xray Tech',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'XRAY', 'role' => 'Xray Tech']],
            ],
            [
                'username' => 'radiologist@gmail.com',
                'full_name' => 'Radiologist',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'XRAY', 'role' => 'Radiologist']],
            ],
            [
                'username' => 'doctor@gmail.com',
                'full_name' => 'Doctor',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'DOCTOR', 'role' => 'Doctor']],
            ],
            [
                'username' => 'orstaff@gmail.com',
                'full_name' => 'Operating Room Staff',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'DOCTOR', 'role' => 'Operating Room Staff']],
            ],
            [
                'username' => 'drstaff@gmail.com',
                'full_name' => 'Delivery Room Staff',
                'status' => 'active',
                'password' => 'testacc123',
                'roles' => [['module' => 'DOCTOR', 'role' => 'Delivery Room Staff']],
            ],
        ];
    }

    $accounts = array_values(array_filter($accounts, function ($a) {
        return is_array($a) && isset($a['username']);
    }));

    if (count($accounts) === 0) {
        json_response(['ok' => false, 'error' => 'No accounts provided'], 400);
    }

    $selectUser = $pdo->prepare('SELECT id FROM users WHERE username = :username LIMIT 1');
    $insertUser = $pdo->prepare('INSERT INTO users (username, full_name, status, password_hash) VALUES (:username, :full_name, :status, :password_hash)');
    $updateUser = $pdo->prepare('UPDATE users SET full_name = :full_name, status = :status, password_hash = :password_hash WHERE id = :id');
    $deleteRoles = $pdo->prepare('DELETE FROM user_roles WHERE user_id = :id');
    $insertRole = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, module, role) VALUES (:user_id, :module, :role)');

    $created = 0;
    $updated = 0;

    $pdo->beginTransaction();

    foreach ($accounts as $a) {
        $username = strtolower(trim((string)($a['username'] ?? '')));
        $fullName = trim((string)($a['full_name'] ?? ''));
        $status = strtolower(trim((string)($a['status'] ?? 'active')));
        $password = (string)($a['password'] ?? '');
        $roles = isset($a['roles']) && is_array($a['roles']) ? $a['roles'] : [];

        if ($username === '' || $fullName === '') {
            continue;
        }
        if (!in_array($status, ['active', 'inactive'], true)) {
            $status = 'active';
        }

        $passwordHash = $password !== '' ? password_hash($password, PASSWORD_DEFAULT) : null;
        if ($passwordHash === null) {
            continue;
        }

        $selectUser->execute(['username' => $username]);
        $row = $selectUser->fetch();

        if (!$row) {
            $insertUser->execute([
                'username' => $username,
                'full_name' => $fullName,
                'status' => $status,
                'password_hash' => $passwordHash,
            ]);
            $userId = (int)$pdo->lastInsertId();
            $created++;
        } else {
            $userId = (int)($row['id'] ?? 0);
            $updateUser->execute([
                'id' => $userId,
                'full_name' => $fullName,
                'status' => $status,
                'password_hash' => $passwordHash,
            ]);
            $updated++;
        }

        if ($userId > 0) {
            $deleteRoles->execute(['id' => $userId]);
            foreach ($roles as $r) {
                if (!is_array($r)) {
                    continue;
                }
                $m = strtoupper(trim((string)($r['module'] ?? '')));
                $role = trim((string)($r['role'] ?? ''));
                if ($m === '' || $role === '' || $m === 'ADMIN') {
                    continue;
                }
                $insertRole->execute([
                    'user_id' => $userId,
                    'module' => $m,
                    'role' => $role,
                ]);
            }
        }
    }

    $pdo->commit();

    json_response([
        'ok' => true,
        'created' => $created,
        'updated' => $updated,
    ]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
