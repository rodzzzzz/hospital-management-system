<?php
declare(strict_types=1);

require_once __DIR__ . '/api/_db.php';
require_once __DIR__ . '/api/auth/_session.php';

$pdo = db();
$u = auth_current_user($pdo);
if ($u) {
    $roles = $u['roles'] ?? [];
    if (is_array($roles) && count($roles) > 0) {
        header('Location: dashboard.php');
        exit;
    }
    header('Location: not-assigned.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row h-[680px] md:h-auto">
        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center">
            <div class="text-center mb-4">
                <img src="resources/logo.png" alt="Hospital Logo" class="h-16 mx-auto">
            </div>
            <h1 class="text-2xl font-bold mb-4 text-center">Login</h1>

            <div id="err" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>

            <form id="loginForm" class="space-y-4 z-50">
                <div>
                    <label for="email" class="sr-only">Username</label>
                    <input id="email" type="text" placeholder="Username" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 border placeholder-gray-400" autocomplete="username" required>
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" type="password" placeholder="Password" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2.5 border placeholder-gray-400" autocomplete="current-password" required>
                </div>
                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-3.5 w-3.5 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember-me" class="ml-2 block text-gray-900">Remember Me</label>
                    </div>
                    <div>
                        <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot password?</a>
                    </div>
                </div>
                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">LOGIN</button>
                </div>
            </form>

            <p class="text-gray-500 mt-4 text-sm text-center">Don't have an account? <a href="#" class="text-indigo-600 font-medium hover:underline">Message Admin</a></p>

            <div class="mt-6 text-center text-gray-500 relative">
                <span class="relative z-10 bg-white px-2 text-sm">Login Process</span>
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300"></div>
                </div>
            </div>

            <div class="mt-4 text-sm text-gray-600 text-center">
                <a href="index.php" class="text-gray-700 hover:underline">Back to Welcome</a>
            </div>
        </div>

        <div class="w-full md:w-1/2 flex items-start justify-end p-14 text-white">
            <img src="login.png" alt="Hospital Login Background" class="absolute inset-0 w-full h-full object-cover opacity-80">
            <div class="relative z-20 text-right mt-5">
                <h2 class="text-xl lg:text-xl font-extrabold mb-4">DR. SERAPIO B. MONTANER JR., AL-HAJ MEMORIAL HOSPITAL</h2>
                <p class="text-sm opacity-90 max-w-sm">Securely access your hospital management dashboard.</p>
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

                const res = await fetch('api/auth/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ username, password })
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    showErr((json && json.error) ? json.error : 'Login failed.');
                    return;
                }

                const roles = (json.user && Array.isArray(json.user.roles)) ? json.user.roles : [];
                if (roles.length === 0) {
                    window.location.href = 'not-assigned.php';
                } else {
                    window.location.href = 'dashboard.php';
                }
            });
        })();
    </script>
</body>
</html>
