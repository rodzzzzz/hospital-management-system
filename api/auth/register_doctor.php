<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $username = trim((string)($data['username'] ?? ''));
    $fullName = trim((string)($data['full_name'] ?? ''));
    $password = (string)($data['password'] ?? '');

    if ($username === '' || $fullName === '' || $password === '') {
        json_response(['ok' => false, 'error' => 'Missing username, full_name, or password'], 400);
    }

    if (strlen($password) < 6) {
        json_response(['ok' => false, 'error' => 'Password must be at least 6 characters'], 400);
    }

    $pdo = db();
    ensure_users_tables($pdo);

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $pdo->beginTransaction();

    $ins = $pdo->prepare('INSERT INTO users (username, full_name, password_hash, status) VALUES (:username, :full_name, :password_hash, :status)');
    try {
        $ins->execute([
            'username' => $username,
            'full_name' => $fullName,
            'password_hash' => $hash,
            'status' => 'active',
        ]);
    } catch (Throwable $e) {
        $pdo->rollBack();
        json_response(['ok' => false, 'error' => 'Username already exists'], 409);
    }

    $userId = (int)$pdo->lastInsertId();

    $roleIns = $pdo->prepare('INSERT IGNORE INTO user_roles (user_id, module, role) VALUES (:user_id, :module, :role)');
    $roleIns->execute([
        'user_id' => $userId,
        'module' => 'DOCTOR',
        'role' => 'Doctor',
    ]);

    $pdo->commit();

    json_response(['ok' => true, 'user_id' => $userId]);
} catch (Throwable $e) {
    if (isset($pdo) && $pdo instanceof PDO && $pdo->inTransaction()) {
        $pdo->rollBack();
    }
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
