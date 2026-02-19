<?php

declare(strict_types=1);

require_once __DIR__ . '/_session.php';

function doctor_users_file_path(): string
{
    return __DIR__ . '/data/users.json';
}

function doctor_users_load(): array
{
    $path = doctor_users_file_path();

    if (!is_file($path)) {
        return [
            [
                'id' => 1,
                'email' => 'doctor@demo.com',
                'full_name' => 'Demo Doctor',
                'password_hash' => password_hash('demo1234', PASSWORD_DEFAULT),
            ],
        ];
    }

    $raw = file_get_contents($path);
    if ($raw === false || trim($raw) === '') {
        return [
            [
                'id' => 1,
                'email' => 'doctor@demo.com',
                'full_name' => 'Demo Doctor',
                'password_hash' => password_hash('demo1234', PASSWORD_DEFAULT),
            ],
        ];
    }

    $json = json_decode($raw, true);
    if (!is_array($json) || count($json) === 0) {
        return [
            [
                'id' => 1,
                'email' => 'doctor@demo.com',
                'full_name' => 'Demo Doctor',
                'password_hash' => password_hash('demo1234', PASSWORD_DEFAULT),
            ],
        ];
    }

    return $json;
}

function doctor_users_save(array $users): void
{
    $path = doctor_users_file_path();
    $dir = dirname($path);

    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    file_put_contents($path, json_encode(array_values($users), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
}

function doctor_user_find_by_email(array $users, string $email): ?array
{
    $needle = strtolower(trim($email));
    foreach ($users as $u) {
        if (!is_array($u)) {
            continue;
        }
        $e = strtolower(trim((string)($u['email'] ?? '')));
        if ($e !== '' && $e === $needle) {
            return $u;
        }
    }
    return null;
}

function doctor_user_create(string $email, string $fullName, string $password): array
{
    $users = doctor_users_load();

    if (doctor_user_find_by_email($users, $email)) {
        throw new RuntimeException('Email already registered.');
    }

    $maxId = 0;
    foreach ($users as $u) {
        $id = (int)($u['id'] ?? 0);
        if ($id > $maxId) {
            $maxId = $id;
        }
    }

    $user = [
        'id' => $maxId + 1,
        'email' => trim($email),
        'full_name' => trim($fullName),
        'password_hash' => password_hash($password, PASSWORD_DEFAULT),
    ];

    $users[] = $user;
    doctor_users_save($users);

    return $user;
}

function doctor_authenticate(string $email, string $password): ?array
{
    $users = doctor_users_load();
    $u = doctor_user_find_by_email($users, $email);
    if (!$u) {
        return null;
    }

    $hash = (string)($u['password_hash'] ?? '');
    if ($hash === '' || !password_verify($password, $hash)) {
        return null;
    }

    return $u;
}
