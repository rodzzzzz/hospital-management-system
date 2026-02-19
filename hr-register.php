<?php
declare(strict_types=1);

require_once __DIR__ . '/api/_db.php';
require_once __DIR__ . '/api/auth/_session.php';

$pdo = db();
$u = auth_current_user($pdo);
if ($u && auth_user_has_module($u, 'HR')) {
    header('Location: hr.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Register - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100">
            <div class="text-xl font-bold text-gray-900">HR Registration</div>
            <div class="text-sm text-gray-600 mt-1">Register first. Access is granted only after HR role assignment.</div>
        </div>
        <div class="p-6">
            <div id="err" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>
            <div id="ok" class="hidden mb-4 text-sm text-green-700 bg-green-50 border border-green-100 rounded-lg p-3"></div>
            <form id="registerForm" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Full Name</label>
                    <input id="fullName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="name" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Email</label>
                    <input id="email" type="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="email" required>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Password</label>
                    <input id="password" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="new-password" required>
                    <div class="text-xs text-gray-500 mt-1">Minimum 6 characters.</div>
                </div>
                <button type="submit" class="w-full px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm">Create account</button>
            </form>
            <div class="mt-4 text-sm text-gray-600">
                Already have an account?
                <a href="hr-login.php" class="text-indigo-700 hover:underline">Login</a>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <a href="register.php" class="text-gray-700 hover:underline">Go to Staff Registration</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            function setMsg(id, msg) {
                const el = document.getElementById(id);
                if (!el) return;
                if (!msg) {
                    el.textContent = '';
                    el.classList.add('hidden');
                    return;
                }
                el.textContent = msg;
                el.classList.remove('hidden');
            }

            const form = document.getElementById('registerForm');
            if (!form) return;

            form.addEventListener('submit', async function (e) {
                e.preventDefault();
                setMsg('err', '');
                setMsg('ok', '');

                const full_name = (document.getElementById('fullName')?.value || '').toString().trim();
                const username = (document.getElementById('email')?.value || '').toString().trim();
                const password = (document.getElementById('password')?.value || '').toString();

                if (!full_name || !username || !password) {
                    setMsg('err', 'All fields are required.');
                    return;
                }

                const res = await fetch('api/auth/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ full_name, username, password })
                });
                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {
                    setMsg('err', (json && json.error) ? json.error : 'Registration failed.');
                    return;
                }

                setMsg('ok', 'Registered. Ask HR to assign an HR role, then login.');
                setTimeout(() => { window.location.href = 'hr-login.php'; }, 700);
            });
        })();
    </script>
</body>
</html>
