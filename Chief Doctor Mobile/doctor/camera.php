<?php
require_once __DIR__ . '/_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Camera</title>
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
                    <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-house text-emerald-600"></i>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="vehicle.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-truck-medical text-emerald-600"></i>
                        <span class="font-semibold">Vehicle</span>
                    </a>
                    <a href="camera.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
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
                    <div class="text-xs font-semibold opacity-90">CAMERA</div>
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
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="text-sm font-extrabold text-slate-800">CCTV Monitoring</div>

            <div id="camsGrid" class="mt-4 grid grid-cols-2 gap-3"></div>
        </div>
    </div>

    <div id="camModal" class="hidden fixed inset-0 z-[90]">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-[380px] bg-white rounded-[28px] shadow-2xl overflow-hidden">
                <div class="relative">
                    <button id="camClose" type="button" class="absolute top-3 right-3 w-9 h-9 rounded-xl bg-black/35 hover:bg-black/45 text-white flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-xmark"></i>
                    </button>
                    <div class="h-[210px] bg-slate-900 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-5xl text-white/25">
                                <i class="fas fa-camera"></i>
                            </div>
                            <div class="mt-2 text-white/80 text-sm font-bold" id="camModalTitle">Camera</div>
                            <div class="text-white/60 text-xs" id="camModalSub">-</div>
                        </div>
                    </div>
                </div>
                <div class="px-5 py-4 bg-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-extrabold text-slate-800" id="camModalName">-</div>
                            <div class="text-xs text-slate-500" id="camModalLoc">-</div>
                        </div>
                        <div id="camModalStatus" class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Online</div>
                    </div>

                    <div class="mt-4">
                        <button id="camCloseBtn" type="button" class="w-full h-11 rounded-2xl bg-slate-900 text-white text-xs font-extrabold hover:bg-slate-800">Close</button>
                    </div>
                </div>
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

            const cams = [
                { id: 'ER1', name: 'ER Cam 1', location: 'Emergency Room', status: 'online' },
                { id: 'LOB', name: 'Lobby Cam', location: 'Main Lobby', status: 'online' },
                { id: 'HALL3', name: 'Hallway Cam 3', location: 'West Wing, 2nd Floor', status: 'online' },
                { id: 'PARK', name: 'Parking Lot Cam', location: 'Main Parking', status: 'online' },
                { id: 'ICU', name: 'ICU Cam', location: 'ICU Ward A', status: 'online' },
                { id: 'PHARM', name: 'Pharmacy Cam', location: 'Pharmacy', status: 'offline' },
                { id: 'OR1', name: 'OR 1 Cam', location: 'Operating Room 1', status: 'online' },
                { id: 'CAF', name: 'Cafeteria Cam', location: 'Cafeteria', status: 'maintenance' },
            ];

            const grid = document.getElementById('camsGrid');
            const modal = document.getElementById('camModal');
            const modalClose = document.getElementById('camClose');
            const modalCloseBtn = document.getElementById('camCloseBtn');

            const camModalTitle = document.getElementById('camModalTitle');
            const camModalSub = document.getElementById('camModalSub');
            const camModalName = document.getElementById('camModalName');
            const camModalLoc = document.getElementById('camModalLoc');
            const camModalStatus = document.getElementById('camModalStatus');

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function statusChip(s) {
                if (s === 'online') return { cls: 'bg-emerald-50 text-emerald-700 border border-emerald-200', label: 'Online' };
                if (s === 'offline') return { cls: 'bg-rose-50 text-rose-700 border border-rose-200', label: 'Offline' };
                return { cls: 'bg-amber-50 text-amber-700 border border-amber-200', label: 'Maintenance' };
            }

            function render() {
                if (!grid) return;
                grid.innerHTML = cams.map(c => {
                    const st = statusChip(c.status);
                    return (
                        '<button type="button" data-id="' + escapeHtml(c.id) + '" class="relative text-left rounded-2xl overflow-hidden border border-slate-200 bg-slate-900 h-[96px]">'
                        + '  <div class="absolute inset-0 bg-gradient-to-b from-slate-800/30 to-slate-950/70"></div>'
                        + '  <div class="absolute top-2 right-2 px-2 py-1 rounded-full text-[10px] font-extrabold ' + st.cls + '">' + escapeHtml(st.label) + '</div>'
                        + '  <div class="relative p-3 h-full flex flex-col justify-end">'
                        + '    <div class="text-white/20 text-2xl absolute top-3 left-3"><i class="fas fa-camera"></i></div>'
                        + '    <div class="text-white font-extrabold text-xs">' + escapeHtml(c.name) + '</div>'
                        + '    <div class="text-white/70 text-[10px]">' + escapeHtml(c.location) + '</div>'
                        + '  </div>'
                        + '</button>'
                    );
                }).join('');

                grid.querySelectorAll('button[data-id]').forEach(btn => {
                    btn.addEventListener('click', () => openCam(btn.getAttribute('data-id')));
                });
            }

            function openCam(id) {
                const c = cams.find(x => x.id === id);
                if (!c || !modal) return;
                const st = statusChip(c.status);

                if (camModalTitle) camModalTitle.textContent = c.name;
                if (camModalSub) camModalSub.textContent = c.location;
                if (camModalName) camModalName.textContent = c.name;
                if (camModalLoc) camModalLoc.textContent = c.location;
                if (camModalStatus) {
                    camModalStatus.textContent = st.label;
                    camModalStatus.className = 'px-3 py-1 rounded-full text-[10px] font-extrabold ' + st.cls;
                }

                modal.classList.remove('hidden');
            }

            function closeCam() {
                modal?.classList.add('hidden');
            }

            modalClose?.addEventListener('click', closeCam);
            modalCloseBtn?.addEventListener('click', closeCam);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal || e.target === modal.firstElementChild) closeCam();
            });

            render();
        })();
    </script>
</body>

</html>
