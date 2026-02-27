<?php
declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$u = auth_current_user();
if ($u && auth_user_has_module($u, 'HR')) {
    header('Location: hr.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Login - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="text-xl font-bold text-gray-900">HR Login</div>
            <div class="text-sm text-gray-600 mt-1">Only accounts with HR roles can sign in here.</div>
        </div>
        <div class="p-6">
            <div id="err" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>
            <form id="loginForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Email</label>
                    <input id="email" type="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="username" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Password</label>
                    <input id="password" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="current-password" required>
                </div>
                <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm">Sign in</button>
            </form>
            <div class="mt-4 text-sm text-gray-600">
                Need an HR account?
                <a href="hr-register.php" class="text-indigo-700 hover:underline">Register</a>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <a href="login.php" class="text-gray-700 hover:underline">Go to Staff Login</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function showErr(msg) {
                const el = document.getElementById('err');
                if (!el) return;
                if (!msg) {
                    el.textContent = '';
                    el.classList.add('hidden');
                    return;
                }
                el.textContent = msg;
                el.classList.remove('hidden');
            }

            const form = document.getElementById('loginForm');
            if (!form) return;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                showErr('');

                const username = (document.getElementById('email')?.value || '').toString().trim();
                const password = (document.getElementById('password')?.value || '').toString();
                if (!username || !password) {
                    showErr('Enter email and password.');
                    return;
                }

                const res = await fetch(API_BASE_URL + '/auth/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    showErr((json && json.error) ? json.error : 'Login failed.');
                    return;
                }

                // Store token and user in frontend PHP session
                await fetch('auth-store.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ token: json.token, user: json.user })
                });

                const roles = (json.user && Array.isArray(json.user.roles)) ? json.user.roles : [];
                const hasHr = roles.some(r => r && String(r.module || '').toUpperCase() === 'HR');
                if (!hasHr) {
                    try {
                        await fetch('auth-logout.php', { method: 'POST', headers: { 'Accept': 'application/json' } });
                    } catch (e) {}
                    showErr('This account is not allowed in HR portal. Ask HR to assign an HR role.');
                    return;
                }

                window.location.href = 'hr.php';
            });
        })();
    </script>
</body>
</html>
