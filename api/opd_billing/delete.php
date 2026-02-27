<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/_tables.php';
require_once __DIR__ . '/../auth/_session.php';

require_method('POST');

try {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw ?: 'null', true);
    if (!is_array($data)) {
        json_response(['ok' => false, 'error' => 'Invalid JSON body'], 400);
    }

    $idRaw = $data['id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit((string)$idRaw))) {
        json_response(['ok' => false, 'error' => 'Missing or invalid id'], 400);
    }
    $id = (int)$idRaw;

    $pdo = db();
    ensure_opd_billing_tables($pdo);

    $authUser = auth_current_user_optional_token($pdo);
    if (!$authUser) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $isAdmin = auth_user_has_module($authUser, 'ADMIN');
    if (!$isAdmin && !auth_user_has_module($authUser, 'OPD') && !auth_user_has_module($authUser, 'DOCTOR')) {
        json_response(['ok' => false, 'error' => 'Forbidden'], 403);
    }

    $del = $pdo->prepare('DELETE FROM opd_billing_items WHERE id = :id');
    $del->execute(['id' => $id]);

    json_response(['ok' => true]);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
