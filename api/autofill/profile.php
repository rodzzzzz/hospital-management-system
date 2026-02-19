<?php
declare(strict_types=1);

require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';

cors_headers();
require_method('GET');

try {
    $pdo = db();
    $user = auth_current_user_optional_token($pdo);
    if (!$user) {
        json_response(['ok' => false, 'error' => 'Not authenticated'], 401);
    }

    $fullName = (string)($user['full_name'] ?? '');

    // Extension profile shape: name, email, phone, address_line1, address_line2, city, state, postal_code, country
    $profile = [
        'name' => $fullName,
        'email' => '',
        'phone' => '',
        'address_line1' => '',
        'address_line2' => '',
        'city' => '',
        'state' => '',
        'postal_code' => '',
        'country' => '',
    ];

    json_response($profile);
} catch (Throwable $e) {
    json_response(['ok' => false, 'error' => $e->getMessage()], 500);
}
