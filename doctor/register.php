<?php
require_once __DIR__ . '/../api/_db.php';
require_once __DIR__ . '/../api/auth/_session.php';

$pdo = db();
$u = auth_current_user($pdo);
if ($u && auth_user_has_module($u, 'DOCTOR')) {
    header('Location: patients.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .mobile-surface {
            border-radius: 28px;
        }

        .soft-lines {
            background-image:
                linear-gradient(140deg, rgba(16, 185, 129, 0.10) 0%, rgba(255, 255, 255, 0) 55%),
                linear-gradient(320deg, rgba(59, 130, 246, 0.10) 0%, rgba(255, 255, 255, 0) 55%);
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 520px) {
            body {
                padding: 0 !important;
                background: #ffffff !important;
                display: block !important;
            }

            .mobile-frame {
                width: 100vw !important;
                max-width: 100vw !important;
                height: 100dvh !important;
                max-height: 100dvh !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-200 flex items-center justify-center p-4">
    <div class="mobile-frame w-full max-w-[420px] h-[820px] max-h-[calc(100vh-2rem)] bg-white shadow-2xl overflow-hidden mobile-surface flex flex-col">
        <div class="px-8 pt-10 pb-12 soft-lines flex-1 overflow-y-auto no-scrollbar">
            <div class="flex items-center justify-between">
                <a href="index.php" class="w-10 h-10 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 flex items-center justify-center" aria-label="Back">
                    <i class="fas fa-arrow-left text-slate-700"></i>
                </a>
                <div class="w-10 h-10"></div>
            </div>

            <div class="mt-6 flex items-center justify-center">
                <div class="w-[86px] h-[86px] rounded-full bg-white shadow-md flex items-center justify-center overflow-hidden">
                    <img id="doctorMobileLogo" src="assets/logo.png" alt="Logo" class="w-[64px] h-[64px] object-contain" />
                    <div id="doctorMobileLogoFallback" class="hidden w-full h-full flex items-center justify-center text-slate-400 text-sm font-semibold">LOGO</div>
                </div>
            </div>

            <div class="mt-5 text-center">
                <h1 class="text-2xl font-extrabold text-emerald-600">Create account</h1>
                <p class="mt-2 text-sm font-semibold text-slate-800">Register to continue</p>
            </div>

            <form id="doctorMobileRegisterForm" class="mt-8 space-y-4" action="#" method="post">
                <div>
                    <input id="doctorRegisterFullName" name="fullname" type="text" placeholder="Full name" class="w-full px-4 py-3 rounded-xl border-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 outline-none bg-white" />
                </div>
                <div>
                    <input id="doctorRegisterUsername" name="email" type="email" placeholder="Email" class="w-full px-4 py-3 rounded-xl border-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 outline-none bg-white" />
                </div>
                <div>
                    <input id="doctorRegisterPassword" name="password" type="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border-2 border-emerald-100 focus:border-emerald-500 focus:ring-0 outline-none bg-emerald-50/40" />
                </div>
                <div>
                    <input id="doctorRegisterConfirm" name="confirm_password" type="password" placeholder="Confirm password" class="w-full px-4 py-3 rounded-xl border-2 border-emerald-100 focus:border-emerald-500 focus:ring-0 outline-none bg-emerald-50/40" />
                </div>

                <div id="doctorRegisterError" class="hidden text-sm font-semibold text-red-600"></div>

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-colors">Register</button>

                <div class="pt-1 text-center">
                    <a href="login.php" class="text-xs text-slate-600 hover:text-slate-800">Already have an account? Sign in</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        (function () {
            const img = document.getElementById('doctorMobileLogo');
            const fallback = document.getElementById('doctorMobileLogoFallback');
            if (img && fallback) {
                img.addEventListener('error', function () {
                    img.classList.add('hidden');
                    fallback.classList.remove('hidden');
                });
            }

            const form = document.getElementById('doctorMobileRegisterForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    (async () => {
                        const fullName = (document.getElementById('doctorRegisterFullName')?.value || '').toString().trim();
                        const username = (document.getElementById('doctorRegisterUsername')?.value || '').toString().trim();
                        const password = (document.getElementById('doctorRegisterPassword')?.value || '').toString();
                        const confirm = (document.getElementById('doctorRegisterConfirm')?.value || '').toString();
                        const err = document.getElementById('doctorRegisterError');

                        if (err) {
                            err.textContent = '';
                            err.classList.add('hidden');
                        }

                        if (!fullName || !username || !password) {
                            if (err) {
                                err.textContent = 'Fill out all fields.';
                                err.classList.remove('hidden');
                            }
                            return;
                        }

                        if (password !== confirm) {
                            if (err) {
                                err.textContent = 'Passwords do not match.';
                                err.classList.remove('hidden');
                            }
                            return;
                        }

                        const reg = await fetch('../api/auth/register_doctor.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ username, full_name: fullName, password })
                        });
                        const regJson = await reg.json().catch(() => null);
                        if (!reg.ok || !regJson || !regJson.ok) {
                            if (err) {
                                err.textContent = (regJson && regJson.error) ? regJson.error : 'Registration failed.';
                                err.classList.remove('hidden');
                            }
                            return;
                        }

                        const login = await fetch('../api/auth/login.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ username, password })
                        });
                        const loginJson = await login.json().catch(() => null);
                        if (!login.ok || !loginJson || !loginJson.ok) {
                            window.location.href = 'login.php';
                            return;
                        }

                        window.location.href = 'patients.php';
                    })();
                });
            }
        })();
    </script>
</body>

</html>
