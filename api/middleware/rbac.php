<?php
declare(strict_types=1);

require_once __DIR__ . '/../_response.php';
require_once __DIR__ . '/../auth/_session.php';

/**
 * Require that the user has access to the given module.
 * Must be called after require_auth().
 */
function require_module(array $user, string $module): void
{
    if (!auth_user_has_module($user, 'ADMIN') && !auth_user_has_module($user, $module)) {
        json_response([
            'ok' => false,
            'error' => 'Access denied: requires module ' . strtoupper($module),
        ], 403);
    }
}

/**
 * Require that the user has a specific role within a module.
 * Must be called after require_auth().
 */
function require_role(array $user, string $module, string $role): void
{
    if (!auth_user_has_module($user, 'ADMIN') && !auth_user_has_role($user, $module, $role)) {
        json_response([
            'ok' => false,
            'error' => 'Access denied: requires role ' . $role . ' in ' . strtoupper($module),
        ], 403);
    }
}

/**
 * Require that the user has any of the given roles within a module.
 * Must be called after require_auth().
 */
function require_any_role(array $user, string $module, array $roles): void
{
    if (!auth_user_has_module($user, 'ADMIN') && !auth_user_has_any_role($user, $module, $roles)) {
        json_response([
            'ok' => false,
            'error' => 'Access denied: requires one of [' . implode(', ', $roles) . '] in ' . strtoupper($module),
        ], 403);
    }
}

/**
 * Require that the user has access to any of the given modules.
 * Must be called after require_auth().
 */
function require_any_module(array $user, array $modules): void
{
    if (auth_user_has_module($user, 'ADMIN')) {
        return;
    }

    foreach ($modules as $m) {
        if (is_string($m) && auth_user_has_module($user, $m)) {
            return;
        }
    }

    json_response([
        'ok' => false,
        'error' => 'Access denied: requires one of [' . implode(', ', $modules) . ']',
    ], 403);
}
