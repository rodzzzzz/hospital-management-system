<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/../doctor/_tables.php';

require_method('GET');

try {
    $pdo = db();
    ensure_users_tables($pdo);
    ensure_doctor_tables($pdo);

    $q = trim((string)($_GET['q'] ?? ''));

    $sql =
        "SELECT DISTINCT u.id, u.username, u.full_name, u.status, da.status AS availability_status, da.updated_at AS availability_updated_at
         FROM users u
         JOIN user_roles ur ON ur.user_id = u.id
         LEFT JOIN doctor_availability da ON da.user_id = u.id
         WHERE ur.module = 'DOCTOR'
           AND u.status = 'active'
           AND COALESCE(NULLIF(TRIM(u.full_name), ''), '') NOT IN ('Delivery Room Staff', 'Operating Room Staff')";

    $params = [];

    if ($q !== '') {
        $sql .= " AND (u.full_name LIKE :q OR u.username LIKE :q)";
        $params['q'] = '%' . $q . '%';
    }

    $sql .= " ORDER BY u.full_name ASC, u.id ASC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $rows = $stmt->fetchAll();

    $doctors = [];
    foreach ($rows as $r) {
        $id = (int)($r['id'] ?? 0);
        if ($id <= 0) {
            continue;
        }
        $doctors[] = [
            'id' => $id,
            'username' => (string)($r['username'] ?? ''),
            'full_name' => (string)($r['full_name'] ?? ''),
            'user_status' => (string)($r['status'] ?? ''),
            'availability_status' => (string)($r['availability_status'] ?? 'available'),
            'availability_updated_at' => $r['availability_updated_at'] ?? null,
        ];
    }

    json_response([
        'ok' => true,
        'doctors' => $doctors,
    ]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
