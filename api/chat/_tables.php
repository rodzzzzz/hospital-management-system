<?php
declare(strict_types=1);

require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../users/_tables.php';

function ensure_chat_tables(PDO $pdo): void
{
    ensure_users_tables($pdo);

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS chat_threads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            type VARCHAR(32) NOT NULL,
            module_a VARCHAR(32) NULL,
            module_b VARCHAR(32) NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_chat_thread (type, module_a, module_b),
            INDEX idx_chat_threads_type (type),
            INDEX idx_chat_threads_modules (module_a, module_b)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $colA = $pdo->query("SHOW COLUMNS FROM chat_threads LIKE 'module_a'")->fetch();
        if (!$colA) {
            $pdo->exec("ALTER TABLE chat_threads ADD COLUMN module_a VARCHAR(32) NULL AFTER type");
        }
    } catch (Throwable $e) {
    }

    try {
        $colB = $pdo->query("SHOW COLUMNS FROM chat_threads LIKE 'module_b'")->fetch();
        if (!$colB) {
            $pdo->exec("ALTER TABLE chat_threads ADD COLUMN module_b VARCHAR(32) NULL AFTER module_a");
        }
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE chat_threads ADD INDEX idx_chat_threads_type (type)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE chat_threads ADD INDEX idx_chat_threads_modules (module_a, module_b)");
    } catch (Throwable $e) {
    }

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS chat_messages (
            id INT AUTO_INCREMENT PRIMARY KEY,
            thread_id INT NOT NULL,
            sender_user_id INT NOT NULL,
            sender_module VARCHAR(32) NOT NULL,
            body TEXT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX idx_chat_messages_thread (thread_id, id),
            INDEX idx_chat_messages_sender (sender_user_id),
            CONSTRAINT fk_chat_messages_thread FOREIGN KEY (thread_id) REFERENCES chat_threads(id) ON DELETE CASCADE,
            CONSTRAINT fk_chat_messages_user FOREIGN KEY (sender_user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    $pdo->exec(
        "CREATE TABLE IF NOT EXISTS chat_thread_reads (
            id INT AUTO_INCREMENT PRIMARY KEY,
            thread_id INT NOT NULL,
            user_id INT NOT NULL,
            last_read_message_id INT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uniq_chat_thread_reads (thread_id, user_id),
            INDEX idx_chat_thread_reads_user (user_id),
            INDEX idx_chat_thread_reads_thread (thread_id),
            CONSTRAINT fk_chat_thread_reads_thread FOREIGN KEY (thread_id) REFERENCES chat_threads(id) ON DELETE CASCADE,
            CONSTRAINT fk_chat_thread_reads_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
    );

    try {
        $pdo->exec("ALTER TABLE chat_threads ADD UNIQUE KEY uniq_chat_thread (type, module_a, module_b)");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE chat_messages ADD COLUMN sender_module VARCHAR(32) NOT NULL");
    } catch (Throwable $e) {
    }

    try {
        $pdo->exec("ALTER TABLE chat_thread_reads ADD UNIQUE KEY uniq_chat_thread_reads (thread_id, user_id)");
    } catch (Throwable $e) {
    }
}

function chat_normalize_module(string $module): string
{
    return strtoupper(trim($module));
}

function chat_get_user_modules(array $user): array
{
    $roles = $user['roles'] ?? [];
    if (!is_array($roles)) {
        return [];
    }

    $set = [];
    foreach ($roles as $r) {
        if (!is_array($r)) {
            continue;
        }
        $m = chat_normalize_module((string)($r['module'] ?? ''));
        if ($m !== '') {
            $set[$m] = true;
        }
    }

    return array_values(array_keys($set));
}

function chat_default_sender_module(array $user): string
{
    $mods = chat_get_user_modules($user);
    foreach ($mods as $m) {
        $mm = chat_normalize_module((string)$m);
        if ($mm !== '' && $mm !== 'ADMIN') {
            return $mm;
        }
    }

    foreach ($mods as $m) {
        $mm = chat_normalize_module((string)$m);
        if ($mm !== '') {
            return $mm;
        }
    }

    return '';
}

function chat_user_has_module(array $user, string $module): bool
{
    $m = chat_normalize_module($module);
    if ($m === '') {
        return false;
    }

    foreach (chat_get_user_modules($user) as $um) {
        if ($um === $m) {
            return true;
        }
    }

    return false;
}

function chat_ensure_announcements_thread(PDO $pdo): int
{
    ensure_chat_tables($pdo);

    $stmt = $pdo->prepare("SELECT id FROM chat_threads WHERE type = 'announcements' AND module_a IS NULL AND module_b IS NULL LIMIT 1");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row && (int)($row['id'] ?? 0) > 0) {
        return (int)$row['id'];
    }

    try {
        $ins = $pdo->prepare("INSERT INTO chat_threads (type, module_a, module_b) VALUES ('announcements', NULL, NULL)");
        $ins->execute();
        return (int)$pdo->lastInsertId();
    } catch (Throwable $e) {
        $stmt = $pdo->prepare("SELECT id FROM chat_threads WHERE type = 'announcements' AND module_a IS NULL AND module_b IS NULL LIMIT 1");
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row && (int)($row['id'] ?? 0) > 0) {
            return (int)($row['id'] ?? 0);
        }
        throw $e;
    }
}

function chat_ensure_dept_pair_thread(PDO $pdo, string $moduleA, string $moduleB): int
{
    ensure_chat_tables($pdo);

    $a = chat_normalize_module($moduleA);
    $b = chat_normalize_module($moduleB);
    if ($a === '' || $b === '' || $a === $b) {
        throw new RuntimeException('Invalid modules');
    }

    $min = $a;
    $max = $b;
    if (strcmp($min, $max) > 0) {
        $min = $b;
        $max = $a;
    }

    $stmt = $pdo->prepare("SELECT id FROM chat_threads WHERE type = 'dept_pair' AND module_a = :a AND module_b = :b LIMIT 1");
    $stmt->execute(['a' => $min, 'b' => $max]);
    $row = $stmt->fetch();
    if ($row && (int)($row['id'] ?? 0) > 0) {
        return (int)$row['id'];
    }

    try {
        $ins = $pdo->prepare("INSERT INTO chat_threads (type, module_a, module_b) VALUES ('dept_pair', :a, :b)");
        $ins->execute(['a' => $min, 'b' => $max]);
        return (int)$pdo->lastInsertId();
    } catch (Throwable $e) {
        $stmt = $pdo->prepare("SELECT id FROM chat_threads WHERE type = 'dept_pair' AND module_a = :a AND module_b = :b LIMIT 1");
        $stmt->execute(['a' => $min, 'b' => $max]);
        $row = $stmt->fetch();
        if ($row && (int)($row['id'] ?? 0) > 0) {
            return (int)($row['id'] ?? 0);
        }
        throw $e;
    }
}

function chat_get_thread(PDO $pdo, int $threadId): ?array
{
    ensure_chat_tables($pdo);

    $stmt = $pdo->prepare('SELECT id, type, module_a, module_b, created_at, updated_at FROM chat_threads WHERE id = :id LIMIT 1');
    $stmt->execute(['id' => $threadId]);
    $row = $stmt->fetch();
    if (!$row) {
        return null;
    }

    return [
        'id' => (int)($row['id'] ?? 0),
        'type' => (string)($row['type'] ?? ''),
        'module_a' => $row['module_a'] === null ? null : (string)$row['module_a'],
        'module_b' => $row['module_b'] === null ? null : (string)$row['module_b'],
        'created_at' => (string)($row['created_at'] ?? ''),
        'updated_at' => (string)($row['updated_at'] ?? ''),
    ];
}

function chat_thread_accessible(array $user, array $thread): bool
{
    $type = (string)($thread['type'] ?? '');
    if ($type === 'announcements') {
        return true;
    }

    if ($type !== 'dept_pair') {
        return false;
    }

    $a = chat_normalize_module((string)($thread['module_a'] ?? ''));
    $b = chat_normalize_module((string)($thread['module_b'] ?? ''));

    if ($a === '' || $b === '') {
        return false;
    }

    return chat_user_has_module($user, $a) || chat_user_has_module($user, $b);
}

function chat_unread_count(PDO $pdo, int $threadId, int $userId): int
{
    ensure_chat_tables($pdo);

    $stmt = $pdo->prepare(
        'SELECT COUNT(*) AS c
         FROM chat_messages m
         LEFT JOIN chat_thread_reads r ON r.thread_id = m.thread_id AND r.user_id = :uid
         WHERE m.thread_id = :tid
           AND m.sender_user_id <> :uid
           AND (r.last_read_message_id IS NULL OR m.id > r.last_read_message_id)'
    );
    $stmt->execute(['tid' => $threadId, 'uid' => $userId]);
    $row = $stmt->fetch();
    return (int)($row['c'] ?? 0);
}

function chat_mark_read(PDO $pdo, int $threadId, int $userId, int $lastMessageId): void
{
    ensure_chat_tables($pdo);

    $stmt = $pdo->prepare(
        'INSERT INTO chat_thread_reads (thread_id, user_id, last_read_message_id)
         VALUES (:tid, :uid, :mid)
         ON DUPLICATE KEY UPDATE last_read_message_id = GREATEST(COALESCE(last_read_message_id, 0), VALUES(last_read_message_id))'
    );
    $stmt->execute(['tid' => $threadId, 'uid' => $userId, 'mid' => $lastMessageId]);
}
