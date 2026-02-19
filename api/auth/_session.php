<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../users/_tables.php';
require_once __DIR__ . '/_tokens.php';

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

function auth_current_user(PDO $pdo): ?array
{
    auth_session_start();

    $idRaw = $_SESSION['auth_user_id'] ?? null;
    if (!is_int($idRaw) && !(is_string($idRaw) && ctype_digit((string)$idRaw))) {
        return null;
    }
    $id = (int)$idRaw;
    if ($id <= 0) {
        return null;
    }

    ensure_users_tables($pdo);

    $stmt = $pdo->prepare('SELECT id, username, full_name, status FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $u = $stmt->fetch();
    if (!$u) {
        return null;
    }

    if (strtolower((string)($u['status'] ?? '')) !== 'active') {
        return null;
    }

    $rolesStmt = $pdo->prepare('SELECT module, role FROM user_roles WHERE user_id = :id ORDER BY module ASC, role ASC');
    $rolesStmt->execute(['id' => $id]);
    $roles = $rolesStmt->fetchAll();

    return [
        'id' => (int)($u['id'] ?? 0),
        'username' => (string)($u['username'] ?? ''),
        'full_name' => (string)($u['full_name'] ?? ''),
        'status' => (string)($u['status'] ?? ''),
        'roles' => array_map(static function ($r) {
            return [
                'module' => (string)($r['module'] ?? ''),
                'role' => (string)($r['role'] ?? ''),
            ];
        }, is_array($roles) ? $roles : []),
    ];
}

/**
 * Get Bearer token from Authorization header if present.
 */
function auth_bearer_token(): ?string
{
    $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if ($header === '' && function_exists('apache_request_headers')) {
        $headers = apache_request_headers();
        $header = $headers['Authorization'] ?? $headers['authorization'] ?? '';
    }
    if (preg_match('/^\s*Bearer\s+(\S+)\s*$/i', $header, $m)) {
        return $m[1];
    }
    return null;
}

/**
 * Current user from Bearer token (extension) or session (web). Use this in endpoints that support both.
 */
function auth_current_user_optional_token(PDO $pdo): ?array
{
    $bearer = auth_bearer_token();
    if ($bearer !== null) {
        $user = auth_current_user_from_token($pdo, $bearer);
        if ($user !== null) {
            return $user;
        }
    }
    return auth_current_user($pdo);
}

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
