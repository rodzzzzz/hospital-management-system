<?php
declare(strict_types=1);

function json_response(mixed $data, int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode($data);
    exit;
}

function require_method(string $method): void
{
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET') !== strtoupper($method)) {
        json_response([
            'ok' => false,
            'error' => 'Method Not Allowed',
        ], 405);
    }
}

function require_int(string $key): int
{
    $value = $_GET[$key] ?? $_POST[$key] ?? null;
    if ($value === null || $value === '' || !ctype_digit((string)$value)) {
        json_response([
            'ok' => false,
            'error' => "Missing or invalid {$key}",
        ], 400);
    }
    return (int)$value;
}

function require_string(string $key): string
{
    $value = $_GET[$key] ?? $_POST[$key] ?? null;
    if ($value === null) {
        json_response([
            'ok' => false,
            'error' => "Missing {$key}",
        ], 400);
    }
    $value = trim((string)$value);
    if ($value === '') {
        json_response([
            'ok' => false,
            'error' => "Missing {$key}",
        ], 400);
    }
    return $value;
}
