<?php
require_once __DIR__ . '/../_db.php';
require_once __DIR__ . '/../auth/_session.php';
require_once __DIR__ . '/../users/_tables.php';

auth_session_start();

$pdo = db();
ensure_users_tables($pdo);

$adminCountStmt = $pdo->query("SELECT COUNT(*) AS c FROM user_roles WHERE module = 'ADMIN'");
$adminCountRow = $adminCountStmt ? $adminCountStmt->fetch() : null;
$adminExists = ((int)($adminCountRow['c'] ?? 0)) > 0;

$error = '';
$ok = '';

if ($adminExists) {
    $ok = 'Admin is already configured.';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim((string)($_POST['email'] ?? ''));
    $fullName = trim((string)($_POST['full_name'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    if ($email === '' || $fullName === '' || $password === '') {
        $error = 'All fields are required.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo->beginTransaction();

            $ins = $pdo->prepare('INSERT INTO users (username, full_name, password_hash, status) VALUES (:username, :full_name, :password_hash, :status)');
            $ins->execute([
                'username' => $email,
                'full_name' => $fullName,
                'password_hash' => $hash,
                'status' => 'active',
            ]);

            $userId = (int)$pdo->lastInsertId();

            $roleIns = $pdo->prepare('INSERT INTO user_roles (user_id, module, role) VALUES (:user_id, :module, :role)');
            $roleIns->execute([
                'user_id' => $userId,
                'module' => 'ADMIN',
                'role' => 'Administrator',
            ]);

            $pdo->commit();

            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'] ?? '/', $params['domain'] ?? '', (bool)($params['secure'] ?? false), (bool)($params['httponly'] ?? true));
            }
            session_destroy();

            header('Location: login.php');
            exit;
        } catch (Throwable $e) {
            if ($pdo instanceof PDO && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            $error = 'Unable to create admin. Email may already exist.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Admin - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="text-xl font-bold text-gray-900">Initial Admin Setup</div>
            <div class="text-sm text-gray-600 mt-1">This page works only if no admin exists yet.</div>
        </div>
        <div class="p-6">
            <?php if ($error !== ''): ?>
                <div class="mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
            <?php endif; ?>
            <?php if ($ok !== ''): ?>
                <div class="mb-4 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg p-3"><?php echo htmlspecialchars($ok, ENT_QUOTES); ?></div>
            <?php endif; ?>

            <?php if ($adminExists): ?>
                <a href="login.php" class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Go to Login</a>
            <?php else: ?>
                <form method="post" class="space-y-4">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Full Name</label>
                        <input name="full_name" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Admin Email</label>
                        <input name="email" type="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200" required>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Password</label>
                        <input name="password" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-200" required>
                        <div class="text-xs text-gray-500 mt-1">Minimum 6 characters.</div>
                    </div>
                    <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white">Create Admin Account</button>
                </form>
                <div class="mt-4 text-xs text-gray-500">After creation, you will be logged out and redirected to login.</div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
