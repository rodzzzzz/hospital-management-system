<?php
header('Location: index.php');
exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Login</title>
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
            <div class="flex items-center justify-center">
                <div class="w-[86px] h-[86px] rounded-full bg-white shadow-md flex items-center justify-center overflow-hidden">
                    <img id="doctorMobileLogo" src="assets/logo.png" alt="Logo" class="w-[64px] h-[64px] object-contain" />
                    <div id="doctorMobileLogoFallback" class="hidden w-full h-full flex items-center justify-center text-slate-400 text-sm font-semibold">LOGO</div>
                </div>
            </div>

            <div class="mt-5 text-center">
                <h1 class="text-2xl font-extrabold text-emerald-600">Login here</h1>
                <p class="mt-2 text-sm font-semibold text-slate-800">Welcome back you've<br>been missed!</p>
            </div>

            <form id="doctorMobileLoginForm" class="mt-8 space-y-4" action="#" method="post">
                <div>
                    <input id="doctorLoginUsername" name="email" type="email" placeholder="Email" class="w-full px-4 py-3 rounded-xl border-2 border-emerald-200 focus:border-emerald-500 focus:ring-0 outline-none bg-white" />
                </div>
                <div>
                    <input id="doctorLoginPassword" name="password" type="password" placeholder="Password" class="w-full px-4 py-3 rounded-xl border-2 border-emerald-100 focus:border-emerald-500 focus:ring-0 outline-none bg-emerald-50/40" />
                </div>
                <div id="doctorLoginError" class="hidden text-sm font-semibold text-red-600"></div>
                <div class="text-right">
                    <a href="#" class="text-xs font-semibold text-indigo-700 hover:text-indigo-800">Forgot your password?</a>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-colors">Sign in</button>

                <div class="pt-1 text-center">
                    <a href="register.php" class="text-xs text-slate-600 hover:text-slate-800">Create new account</a>
                </div>

                <div class="pt-4">
                    <div class="text-center text-xs text-slate-400 font-semibold">Or continue with</div>
                    <div class="mt-3 flex items-center justify-center gap-3">
                        <button type="button" class="w-10 h-10 rounded-lg border border-slate-200 bg-white hover:bg-slate-50" aria-label="Continue with Google">
                            <i class="fa-brands fa-google text-slate-700"></i>
                        </button>
                        <button type="button" class="w-10 h-10 rounded-lg border border-slate-200 bg-white hover:bg-slate-50" aria-label="Continue with Facebook">
                            <i class="fa-brands fa-facebook-f text-slate-700"></i>
                        </button>
                        <button type="button" class="w-10 h-10 rounded-lg border border-slate-200 bg-white hover:bg-slate-50" aria-label="Continue with Apple">
                            <i class="fa-brands fa-apple text-slate-700"></i>
                        </button>
                    </div>
                </div>
            </form>

            <div class="mt-8 text-center">
                <a href="index.php" class="text-xs text-slate-500 hover:text-slate-700">Back to Welcome</a>
            </div>
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

            const form = document.getElementById('doctorMobileLoginForm');
            if (form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    (async () => {
                        const username = (document.getElementById('doctorLoginUsername')?.value || '').toString().trim();
                        const password = (document.getElementById('doctorLoginPassword')?.value || '').toString();
                        const err = document.getElementById('doctorLoginError');

                        if (err) {
                            err.textContent = '';
                            err.classList.add('hidden');
                        }

                        if (!username || !password) {
                            if (err) {
                                err.textContent = 'Enter email and password.';
                                err.classList.remove('hidden');
                            }
                            return;
                        }

                        const res = await fetch('login.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({ email: username, password })
                        });
                        const json = await res.json().catch(() => null);

                        if (!res.ok || !json || !json.ok) {
                            if (err) {
                                err.textContent = (json && json.error) ? json.error : 'Login failed.';
                                err.classList.remove('hidden');
                            }
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
