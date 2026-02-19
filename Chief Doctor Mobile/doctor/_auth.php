<?php
declare(strict_types=1);

require_once __DIR__ . '/_session.php';

$user = doctor_current_user();

$doctorUser = is_array($user) ? $user : [];
$doctorName = (string)($doctorUser['full_name'] ?? '');
