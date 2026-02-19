<?php
$pdo = null;
$authUser = null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <header class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Change Password</h1>
                </div>
            </header>

            <div class="p-6">
                <div class="max-w-2xl bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                    <div id="msg" class="hidden mb-4 text-sm rounded-lg p-3"></div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Current Password</label>
                            <input id="currentPassword" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="current-password">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">New Password</label>
                            <input id="newPassword" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="new-password">
                            <div class="text-xs text-gray-500 mt-1">Minimum 6 characters.</div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Confirm New Password</label>
                            <input id="confirmPassword" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" autocomplete="new-password">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button id="btnSave" type="button" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white">Update Password</button>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        (function () {
            function setMsg(text, ok) {
                const el = document.getElementById('msg');
                if (!el) return;
                if (!text) {
                    el.textContent = '';
                    el.className = 'hidden mb-4 text-sm rounded-lg p-3';
                    return;
                }
                el.textContent = text;
                el.className = 'mb-4 text-sm rounded-lg p-3 ' + (ok ? 'text-green-700 bg-green-50 border border-green-100' : 'text-red-700 bg-red-50 border border-red-100');
            }

            const btn = document.getElementById('btnSave');
            if (!btn) return;

            btn.addEventListener('click', async function () {
                setMsg('', true);

                const current_password = (document.getElementById('currentPassword')?.value || '').toString();
                const new_password = (document.getElementById('newPassword')?.value || '').toString();
                const confirm = (document.getElementById('confirmPassword')?.value || '').toString();

                if (!current_password || !new_password || !confirm) {
                    setMsg('All fields are required.', false);
                    return;
                }

                if (new_password !== confirm) {
                    setMsg('New password does not match confirmation.', false);
                    return;
                }

                const res = await fetch('api/auth/change_password.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ current_password, new_password })
                });

                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    setMsg((json && json.error) ? String(json.error) : 'Unable to change password.', false);
                    return;
                }

                document.getElementById('currentPassword').value = '';
                document.getElementById('newPassword').value = '';
                document.getElementById('confirmPassword').value = '';

                setMsg('Password updated.', true);
            });
        })();
    </script>
</body>

</html>
