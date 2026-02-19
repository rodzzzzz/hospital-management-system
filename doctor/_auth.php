<?php
declare(strict_types=1);

require_once __DIR__ . '/../api/_db.php';
require_once __DIR__ . '/../api/auth/_session.php';

$pdo = db();
$user = auth_current_user($pdo);

if (!$user || !auth_user_has_module($user, 'DOCTOR')) {
    header('Location: login.php');
    exit;
}

$doctorUser = $user;
$doctorName = (string)($user['full_name'] ?? '');
