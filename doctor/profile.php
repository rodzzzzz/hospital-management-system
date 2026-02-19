<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Profile</title>
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
    <div class="mobile-frame w-full max-w-[420px] h-[820px] max-h-[calc(100vh-2rem)] bg-white shadow-2xl overflow-hidden mobile-surface relative flex flex-col">
        <div id="mobileDrawerOverlay" class="hidden absolute inset-0 bg-black/40 z-40"></div>
        <div id="mobileDrawer" class="absolute top-0 left-0 h-full w-[280px] bg-white z-50 -translate-x-full transition-transform duration-200 overflow-y-auto no-scrollbar">
            <div class="px-5 py-5 bg-emerald-600 text-white">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold">Doctor Menu</div>
                    <button id="drawerClose" type="button" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-3 text-xs opacity-90">DOCTOR</div>
            </div>
            <div class="p-3">
                <a href="patients.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-user-injured text-emerald-600"></i>
                    <span class="font-semibold">Patients</span>
                </a>
                <a href="er.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-truck-medical text-emerald-600"></i>
                    <span class="font-semibold">ER</span>
                </a>
                <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-calendar-check text-emerald-600"></i>
                    <span class="font-semibold">Appointments</span>
                </a>
                <a href="lab-results.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-file-medical text-emerald-600"></i>
                    <span class="font-semibold">Lab Results</span>
                </a>
                <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
                    <i class="fas fa-user-doctor text-emerald-600"></i>
                    <span class="font-semibold">Profile</span>
                </a>
                <div class="my-3 h-px bg-slate-200"></div>
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
                    <div class="text-xs font-semibold opacity-90">PROFILE</div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="text-right leading-tight">
                        <div class="text-[10px] opacity-90">DOCTOR</div>
                        <div class="text-[10px] font-bold opacity-95"><?php echo htmlspecialchars($doctorName !== '' ? $doctorName : 'Doctor', ENT_QUOTES); ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="px-5 py-6 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-user-doctor text-emerald-600"></i>
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-800"><?php echo htmlspecialchars($doctorName !== '' ? $doctorName : 'Doctor', ENT_QUOTES); ?></div>
                        <div class="text-xs text-slate-500"><?php echo htmlspecialchars((string)($doctorUser['username'] ?? ''), ENT_QUOTES); ?></div>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                        <div class="text-[11px] text-slate-500">Department</div>
                        <div class="text-sm font-bold text-slate-800">-</div>
                    </div>
                    <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                        <div class="text-[11px] text-slate-500">Status</div>
                        <div id="doctorAvailabilityBadge" class="text-sm font-bold text-slate-800">Loading...</div>
                    </div>
                </div>

                <div class="mt-4 rounded-xl border border-slate-100 bg-white p-3">
                    <div class="text-[11px] text-slate-500">Set availability</div>
                    <div class="mt-2 flex items-center gap-2">
                        <select id="doctorAvailabilitySelect" class="flex-1 text-sm text-slate-700 outline-none bg-slate-50 border border-slate-200 rounded-xl px-3 py-2">
                            <option value="available">Available</option>
                            <option value="busy">Busy</option>
                            <option value="on_leave">On Leave</option>
                        </select>
                        <button id="doctorAvailabilitySave" type="button" class="px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">Save</button>
                    </div>
                    <div id="doctorAvailabilityMsg" class="mt-2 hidden text-xs font-semibold"></div>
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

            const selectEl = document.getElementById('doctorAvailabilitySelect');
            const badgeEl = document.getElementById('doctorAvailabilityBadge');
            const saveBtn = document.getElementById('doctorAvailabilitySave');
            const msgEl = document.getElementById('doctorAvailabilityMsg');

            function labelForStatus(s) {
                const x = (s ?? '').toString().toLowerCase();
                if (x === 'busy') return 'Busy';
                if (x === 'on_leave') return 'On Leave';
                return 'Available';
            }

            function showMsg(text, ok) {
                if (!msgEl) return;
                msgEl.textContent = text;
                msgEl.classList.remove('hidden');
                msgEl.classList.toggle('text-emerald-700', !!ok);
                msgEl.classList.toggle('text-red-600', !ok);
            }

            async function loadAvailability() {
                if (badgeEl) badgeEl.textContent = 'Loading...';
                try {
                    const res = await fetch('../api/doctor/get_status.php', { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        if (badgeEl) badgeEl.textContent = 'Unknown';
                        return;
                    }
                    const status = (json.status || 'available').toString();
                    if (badgeEl) badgeEl.textContent = labelForStatus(status);
                    if (selectEl) selectEl.value = status;
                } catch (e) {
                    if (badgeEl) badgeEl.textContent = 'Unknown';
                }
            }

            saveBtn?.addEventListener('click', async () => {
                if (msgEl) msgEl.classList.add('hidden');
                const status = (selectEl?.value || 'available').toString();
                try {
                    const res = await fetch('../api/doctor/set_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ status })
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        showMsg((json && json.error) ? json.error : 'Unable to save status.', false);
                        return;
                    }
                    if (badgeEl) badgeEl.textContent = labelForStatus(json.status || status);
                    showMsg('Status updated.', true);
                } catch (e) {
                    showMsg('Unable to save status.', false);
                }
            });

            loadAvailability();
        })();
    </script>
</body>

</html>
