<?php
declare(strict_types=1);

require_once __DIR__ . '/_session.php';

doctor_logout();

header('Location: login.php');
exit;
