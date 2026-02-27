<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

/**
 * Frontend Auth Helper
 * 
 * Replaces direct require_once of backend API files.
 * Uses local PHP sessions to cache auth token and user info,
 * and communicates with the backend via HTTP (cURL).
 */

function auth_session_start(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

/**
 * Make an authenticated HTTP request to the backend API.
 *
 * @param string $endpoint  Relative endpoint path (e.g. 'auth/login.php')
 * @param string $method    HTTP method (GET, POST, PUT, DELETE)
 * @param array|null $data  Body data for POST/PUT (will be JSON-encoded)
 * @return array            Decoded JSON response
 */
function api_fetch(string $endpoint, string $method = 'GET', ?array $data = null): array
{
    $url = rtrim(API_BASE_URL, '/') . '/' . ltrim($endpoint, '/');

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    $headers = [
        'Accept: application/json',
    ];

    // Attach Bearer token if available
    $token = auth_get_token();
    if ($token !== null) {
        $headers[] = 'Authorization: Bearer ' . $token;
    }

    if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        if ($data !== null) {
            $jsonBody = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonBody);
            $headers[] = 'Content-Type: application/json';
        }
    } elseif ($method === 'DELETE') {
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);

    if ($response === false) {
        return ['ok' => false, 'error' => 'API request failed: ' . $error, '_http_code' => 0];
    }

    $decoded = json_decode($response, true);
    if (!is_array($decoded)) {
        return ['ok' => false, 'error' => 'Invalid API response', '_http_code' => $httpCode];
    }

    $decoded['_http_code'] = $httpCode;
    return $decoded;
}

/**
 * Get the stored auth token from the local session.
 */
function auth_get_token(): ?string
{
    auth_session_start();
    $token = $_SESSION['auth_token'] ?? null;
    return is_string($token) && $token !== '' ? $token : null;
}

/**
 * Store auth token and user data in the local session after login.
 */
function auth_store_login(string $token, array $user): void
{
    auth_session_start();
    $_SESSION['auth_token'] = $token;
    $_SESSION['auth_user'] = $user;
    $_SESSION['auth_user_id'] = (int)($user['id'] ?? 0);
}

/**
 * Clear auth data from local session (logout).
 */
function auth_clear(): void
{
    auth_session_start();
    unset($_SESSION['auth_token'], $_SESSION['auth_user'], $_SESSION['auth_user_id']);
}

/**
 * Get the current authenticated user from local session cache.
 * Returns the same array shape as the backend's auth_current_user().
 * Returns null if not logged in.
 */
function auth_current_user(): ?array
{
    auth_session_start();
    $user = $_SESSION['auth_user'] ?? null;
    if (!is_array($user) || empty($user['id'])) {
        return null;
    }
    return $user;
}

/**
 * Verify the current token is still valid by calling the backend.
 * Updates cached user data if valid, clears session if not.
 * Returns user array or null.
 */
function auth_verify_token(): ?array
{
    $token = auth_get_token();
    if ($token === null) {
        return null;
    }

    $result = api_fetch('auth/me.php', 'GET');
    if (!empty($result['ok']) && !empty($result['user']) && is_array($result['user'])) {
        $_SESSION['auth_user'] = $result['user'];
        return $result['user'];
    }

    auth_clear();
    return null;
}

/**
 * Check if a user has a specific module role.
 * Mirrors the backend auth_user_has_module().
 */
function auth_user_has_module(array $user, string $module): bool
{
    $m = strtoupper(trim($module));
    $roles = $user['roles'] ?? [];
    if (!is_array($roles)) {
        return false;
    }

    foreach ($roles as $r) {
        if (!is_array($r)) {
            continue;
        }
        if (strtoupper((string)($r['module'] ?? '')) === $m) {
            return true;
        }
    }

    return false;
}

/**
 * Check if a user has a specific role within a module.
 * Mirrors the backend auth_user_has_role().
 */
function auth_user_has_role(array $user, string $module, string $role): bool
{
    $m = strtoupper(trim($module));
    $needle = trim($role);
    if ($m === '' || $needle === '') {
        return false;
    }

    $roles = $user['roles'] ?? [];
    if (!is_array($roles)) {
        return false;
    }

    foreach ($roles as $r) {
        if (!is_array($r)) {
            continue;
        }
        if (strtoupper((string)($r['module'] ?? '')) === $m && (string)($r['role'] ?? '') === $needle) {
            return true;
        }
    }

    return false;
}

/**
 * Check if a user has any of the specified roles within a module.
 * Mirrors the backend auth_user_has_any_role().
 */
function auth_user_has_any_role(array $user, string $module, array $roles): bool
{
    $m = strtoupper(trim($module));
    if ($m === '' || count($roles) === 0) {
        return false;
    }

    $set = [];
    foreach ($roles as $r) {
        if (!is_string($r)) {
            continue;
        }
        $x = trim($r);
        if ($x !== '') {
            $set[$x] = true;
        }
    }
    if (count($set) === 0) {
        return false;
    }

    $userRoles = $user['roles'] ?? [];
    if (!is_array($userRoles)) {
        return false;
    }

    foreach ($userRoles as $r) {
        if (!is_array($r)) {
            continue;
        }
        if (strtoupper((string)($r['module'] ?? '')) !== $m) {
            continue;
        }
        $role = (string)($r['role'] ?? '');
        if ($role !== '' && isset($set[$role])) {
            return true;
        }
    }

    return false;
}
