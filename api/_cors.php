<?php
declare(strict_types=1);

/**
 * Send CORS headers for extension and web clients. Include before any output in auth/autofill endpoints.
 */
function cors_headers(): void
{
    $envOrigins = getenv('CORS_ALLOWED_ORIGINS');
    $allowed = [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'http://localhost',
        'http://127.0.0.1',
        'http://localhost:80',
        'http://127.0.0.1:80',
    ];
    if ($envOrigins !== false && $envOrigins !== '') {
        foreach (explode(',', $envOrigins) as $o) {
            $o = trim($o);
            if ($o !== '') {
                $allowed[] = $o;
            }
        }
    }
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
    if (in_array($origin, $allowed, true)) {
        header('Access-Control-Allow-Origin: ' . $origin);
    } else {
        header('Access-Control-Allow-Origin: *');
    }
    header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');

    if (($_SERVER['REQUEST_METHOD'] ?? '') === 'OPTIONS') {
        http_response_code(204);
        exit;
    }
}
