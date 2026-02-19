<?php

declare(strict_types=1);

function doctor_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    if (!headers_sent()) {
        session_name('doctor_mobile');
    }

    session_start();
}

function doctor_current_user(): ?array
{
    doctor_session_start();
    $u = $_SESSION['doctor_user'] ?? null;
    return is_array($u) ? $u : null;
}

function doctor_set_current_user(array $user): void
{
    doctor_session_start();
    $_SESSION['doctor_user'] = $user;
}

function doctor_logout(): void
{
    doctor_session_start();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', (bool)($params['secure'] ?? false), (bool)($params['httponly'] ?? true));
    }

    session_destroy();
}
