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
    <title>Doctor Mobile - Welcome</title>
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

        .hero-bg {
            background-image:
                linear-gradient(180deg, rgba(255, 255, 255, 0.00) 0%, rgba(255, 255, 255, 0.35) 58%, rgba(255, 255, 255, 1) 100%),
                url('../loginbg.jpg');
            background-size: cover;
            background-position: top center;
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
        <div class="relative h-[320px] flex-shrink-0">
            <div class="absolute inset-0 hero-bg"></div>
        </div>

        <div class="relative -mt-[92px] px-8 pb-12 soft-lines flex-1 overflow-y-auto no-scrollbar">
            <div class="mx-auto w-[120px] h-[120px] rounded-full bg-white shadow-xl flex items-center justify-center overflow-hidden">
                <img id="doctorMobileLogo" src="../logo.png" alt="Logo" class="w-[96px] h-[96px] object-contain" />
                <div id="doctorMobileLogoFallback" class="hidden w-full h-full flex items-center justify-center text-slate-400 text-sm font-semibold">LOGO</div>
            </div>

            <div class="mt-8 text-center">
                <h1 class="text-2xl font-extrabold text-emerald-600 leading-tight">Smart Healthcare,
                    simplified</h1>
                <p class="mt-3 text-sm text-slate-600">Manage patients, doctors, and hospital
                    operations seamlessly in one platform.</p>
            </div>

            <div class="mt-10 flex items-center justify-center gap-10">
                <a href="login.php" class="inline-flex items-center justify-center px-10 py-3 rounded-xl bg-emerald-600 text-white font-semibold shadow-lg shadow-emerald-600/25 hover:bg-emerald-700 transition-colors">Login</a>
                <a href="register.php" class="font-semibold text-indigo-700 hover:text-indigo-800">Register</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const img = document.getElementById('doctorMobileLogo');
            const fallback = document.getElementById('doctorMobileLogoFallback');
            if (!img || !fallback) return;

            img.addEventListener('error', function () {
                img.classList.add('hidden');
                fallback.classList.remove('hidden');
            });
        })();
    </script>
</body>

</html>
