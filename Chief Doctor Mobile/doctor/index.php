<?php
require_once __DIR__ . '/_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Dashboard</title>
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

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 520px) {
            html {
                font-size: 15px;
            }

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

            .mobile-frame .px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .mobile-frame .pt-5 {
                padding-top: 1rem !important;
            }

            .mobile-frame .pb-4 {
                padding-bottom: 0.75rem !important;
            }

            .mobile-frame .py-5 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-200 flex items-center justify-center p-4">
    <div class="mobile-frame w-full max-w-[420px] h-[820px] max-h-[calc(100vh-2rem)] bg-white shadow-2xl overflow-hidden mobile-surface relative flex flex-col">
        <div id="mobileDrawerOverlay" class="hidden absolute inset-0 bg-black/40 z-40"></div>
        <div id="mobileDrawer" class="absolute top-0 left-0 h-full w-[280px] bg-white z-50 -translate-x-full transition-transform duration-200 overflow-hidden no-scrollbar flex flex-col">
            <div class="px-5 py-5 bg-emerald-600 text-white">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold">Chief Doctor Menu</div>
                    <button id="drawerClose" type="button" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-3 text-xs opacity-90">CHIEF DOCTOR</div>
            </div>
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="p-3">
                    <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
                        <i class="fas fa-house text-emerald-600"></i>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="vehicle.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-truck-medical text-emerald-600"></i>
                        <span class="font-semibold">Vehicle</span>
                    </a>
                    <a href="camera.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-video text-emerald-600"></i>
                        <span class="font-semibold">Camera</span>
                    </a>
                    <a href="philhealth.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                        <span class="font-semibold">PhilHealth</span>
                    </a>
                    <a href="daily-reports.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-chart-line text-emerald-600"></i>
                        <span class="font-semibold">Daily Reports</span>
                    </a>
                    <a href="chat.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-comments text-emerald-600"></i>
                        <span class="font-semibold">Chat Messages</span>
                    </a>
                    <a href="patients.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-user-injured text-emerald-600"></i>
                        <span class="font-semibold">Patients</span>
                    </a>
                    <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-calendar-check text-emerald-600"></i>
                        <span class="font-semibold">Appointments</span>
                    </a>
                    <a href="lab-requests.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-vials text-emerald-600"></i>
                        <span class="font-semibold">Lab Requests</span>
                    </a>
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-user-doctor text-emerald-600"></i>
                        <span class="font-semibold">Profile</span>
                    </a>
                </div>
            </div>

            <div class="p-3 border-t border-slate-200">
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-right-from-bracket text-emerald-600"></i>
                    <span class="font-semibold">Logout</span>
                </a>
            </div>
        </div>

        <div class="bg-emerald-600 text-white px-5 pt-5 pb-4">
            <div class="flex items-center justify-between">
                <button id="drawerOpen" type="button" class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="text-center">
                    <div class="text-xs font-semibold opacity-90">DASHBOARD</div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="text-right leading-tight">
                        <div class="text-[10px] opacity-90">CHIEF DOCTOR</div>
                        <div class="text-[10px] font-bold opacity-95"><?php echo htmlspecialchars($doctorName !== '' ? $doctorName : 'Chief Doctor', ENT_QUOTES); ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
            </div>

            <div class="mt-4 bg-white/10 rounded-2xl px-4 py-3">
                <div class="text-sm font-extrabold">Overview</div>
                <div class="text-xs opacity-90 mt-1">Choose a module to continue</div>
            </div>
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="grid grid-cols-2 gap-4">
                <a href="vehicle.php" class="bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-truck-medical text-emerald-600"></i>
                    </div>
                    <div class="mt-3 font-extrabold text-slate-800 text-sm">Vehicle</div>
                    <div class="mt-1 text-xs text-slate-500">Service vehicles</div>
                </a>
                <a href="camera.php" class="bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-video text-emerald-600"></i>
                    </div>
                    <div class="mt-3 font-extrabold text-slate-800 text-sm">Camera</div>
                    <div class="mt-1 text-xs text-slate-500">CCTV monitoring</div>
                </a>
                <a href="patients.php" class="bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-user-injured text-emerald-600"></i>
                    </div>
                    <div class="mt-3 font-extrabold text-slate-800 text-sm">Patients</div>
                    <div class="mt-1 text-xs text-slate-500">Patient records</div>
                </a>
                <a href="appointments.php" class="bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-calendar-check text-emerald-600"></i>
                    </div>
                    <div class="mt-3 font-extrabold text-slate-800 text-sm">Appointments</div>
                    <div class="mt-1 text-xs text-slate-500">Schedule</div>
                </a>
                <a href="lab-requests.php" class="bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">
                    <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-vials text-emerald-600"></i>
                    </div>
                    <div class="mt-3 font-extrabold text-slate-800 text-sm">Lab</div>
                    <div class="mt-1 text-xs text-slate-500">Requests & results</div>
                </a>
            </div>

            <div class="mt-8 text-center">
                <a href="logout.php" class="text-xs text-slate-500 hover:text-slate-700">Logout</a>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const openBtn = document.getElementById('drawerOpen');
            const closeBtn = document.getElementById('drawerClose');

            function openDrawer() {
                if (!drawer || !overlay) return;
                drawer.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeDrawer() {
                if (!drawer || !overlay) return;
                drawer.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            openBtn?.addEventListener('click', openDrawer);
            closeBtn?.addEventListener('click', closeDrawer);
            overlay?.addEventListener('click', closeDrawer);
        })();
    </script>
</body>

</html>
