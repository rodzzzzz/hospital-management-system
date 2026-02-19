<?php
declare(strict_types=1);

require_once __DIR__ . '/api/_db.php';
require_once __DIR__ . '/api/auth/_session.php';

$pdo = db();
$u = auth_current_user($pdo);
if (!$u) {
    header('Location: login.php');
    exit;
}

$roles = $u['roles'] ?? [];
if (is_array($roles) && count($roles) > 0) {
    header('Location: dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Not Assigned - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-lg bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="text-xl font-bold text-gray-900">Not assigned yet</div>
            <div class="text-sm text-gray-600 mt-1">Your account is registered but HR has not assigned access roles yet.</div>
        </div>
        <div class="p-6">
            <div class="text-sm text-gray-700">
                Signed in as:
                <span class="font-semibold"><?php echo htmlspecialchars((string)($u['username'] ?? ''), ENT_QUOTES); ?></span>
            </div>
            <div class="mt-4 text-sm text-gray-600">Please contact HR to assign your department role (e.g., ER Nurse, NP/PA).</div>

            <div class="mt-6 flex items-center gap-2">
                <button id="btnLogout" class="px-4 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white">Logout</button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btnLogout').addEventListener('click', async function () {
            try {
                await fetch('api/auth/logout.php', { method: 'POST', headers: { 'Accept': 'application/json' } });
            } catch (e) {}
            window.location.href = 'login.php';
        });
    </script>
</body>
</html>
