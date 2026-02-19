<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../users/_tables.php';

/** Token lifetime in seconds (2 days). */
const AUTH_TOKEN_LIFETIME = 172800;

function ensure_auth_tokens_table(PDO $pdo): void
{
    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS auth_tokens (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token_hash VARCHAR(64) NOT NULL,
            expires_at TIMESTAMP NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_auth_tokens_hash (token_hash),
            INDEX idx_auth_tokens_expires (expires_at),
            CONSTRAINT fk_auth_tokens_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );
}

/**
 * Create a new auth token for the given user. Returns the plain token (to send to client);
 * only the hash is stored in the database.
 */
function auth_token_create(PDO $pdo, int $userId): string
{
    ensure_users_tables($pdo);
    ensure_auth_tokens_table($pdo);

    $plainToken = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $plainToken);
    $expiresAt = date('Y-m-d H:i:s', time() + AUTH_TOKEN_LIFETIME);

    $stmt = $pdo->prepare('INSERT INTO auth_tokens (user_id, token_hash, expires_at) VALUES (:user_id, :token_hash, :expires_at)');
    $stmt->execute([
        'user_id' => $userId,
        'token_hash' => $tokenHash,
        'expires_at' => $expiresAt,
    ]);

    return $plainToken;
}

/**
 * Invalidate a token by its plain value (e.g. on logout).
 */
function auth_token_invalidate(PDO $pdo, string $token): void
{
    ensure_auth_tokens_table($pdo);
    $tokenHash = hash('sha256', $token);
    $stmt = $pdo->prepare('DELETE FROM auth_tokens WHERE token_hash = :token_hash');
    $stmt->execute(['token_hash' => $tokenHash]);
}

/**
 * Resolve current user from Bearer token. Returns same shape as auth_current_user, or null.
 */
function auth_current_user_from_token(PDO $pdo, string $token): ?array
{
    if ($token === '') {
        return null;
    }

    ensure_users_tables($pdo);
    ensure_auth_tokens_table($pdo);

    $tokenHash = hash('sha256', $token);
    $now = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare(
        'SELECT t.user_id FROM auth_tokens t
         INNER JOIN users u ON u.id = t.user_id
         WHERE t.token_hash = :token_hash AND t.expires_at > :now
         AND LOWER(u.status) = \'active\'
         LIMIT 1'
    );
    $stmt->execute(['token_hash' => $tokenHash, 'now' => $now]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }

    $id = (int)$row['user_id'];
    $stmt = $pdo->prepare('SELECT id, username, full_name, status FROM users WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $id]);
    $u = $stmt->fetch();
    if (!$u) {
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
