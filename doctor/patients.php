<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Patients</title>
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

        .chip {
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            white-space: nowrap;
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

            .mobile-frame .p-4 {
                padding: 0.875rem !important;
            }

            .mobile-frame .space-y-4 > :not([hidden]) ~ :not([hidden]) {
                margin-top: 0.75rem !important;
            }

            .chip {
                font-size: 10px;
                padding: 2px 8px;
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
                <a href="patients.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
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
                <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
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
                    <div class="text-xs font-semibold opacity-90">PATIENTS</div>
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

            <div class="mt-4 flex items-center gap-3">
                <div class="flex-1 bg-white rounded-xl px-3 py-2 flex items-center gap-2">
                    <i class="fas fa-search text-slate-400"></i>
                    <input id="patientSearch" type="text" placeholder="Search patient" class="w-full outline-none text-sm text-slate-700" />
                </div>
                <div class="bg-white rounded-xl px-3 py-2">
                    <select id="statusFilter" class="text-sm text-slate-700 outline-none bg-transparent">
                        <option value="">All Status</option>
                        <option value="registered">Registered</option>
                        <option value="lab">Lab</option>
                        <option value="billing">Billing</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div id="patientsList" class="space-y-4"></div>

            <div class="mt-6">
                <h3 class="text-sm font-extrabold text-slate-700">Recent Patient Activity</h3>
                <div id="recentList" class="mt-3 space-y-3"></div>
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

            let patients = [];
            let fetchTimer = null;

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function ageFromDob(dob) {
                const s = (dob ?? '').toString().trim();
                if (!s) return '-';
                const d = new Date(s);
                if (Number.isNaN(d.getTime())) return '-';
                const now = new Date();
                let age = now.getFullYear() - d.getFullYear();
                const m = now.getMonth() - d.getMonth();
                if (m < 0 || (m === 0 && now.getDate() < d.getDate())) age -= 1;
                return age < 0 ? '-' : String(age);
            }

            function bucketFromProgress(progressStatus) {
                const s = (progressStatus ?? '').toString().toLowerCase();
                if (s.includes('completed') || s.includes('done payment')) return 'completed';
                if (s.includes('billing') || s.includes('invoice') || s.includes('payment')) return 'billing';
                if (s.includes('lab')) return 'lab';
                return 'registered';
            }

            function statusChip(progressStatus) {
                const bucket = bucketFromProgress(progressStatus);
                if (bucket === 'completed') return { label: 'Completed', cls: 'chip bg-emerald-100 text-emerald-700' };
                if (bucket === 'billing') return { label: 'Billing', cls: 'chip bg-amber-100 text-amber-700' };
                if (bucket === 'lab') return { label: 'Lab', cls: 'chip bg-blue-100 text-blue-700' };
                return { label: 'Registered', cls: 'chip bg-slate-100 text-slate-700' };
            }

            function chipClass(seed) {
                const x = (seed ?? '').toString().toLowerCase();
                if (x.includes('lab')) return 'bg-blue-100 text-blue-700';
                if (x.includes('billing') || x.includes('invoice') || x.includes('payment')) return 'bg-amber-100 text-amber-700';
                if (x.includes('completed') || x.includes('done')) return 'bg-emerald-100 text-emerald-700';
                if (x.includes('pharmacy') || x.includes('resit')) return 'bg-purple-100 text-purple-700';
                return 'bg-slate-100 text-slate-700';
            }

            function render() {
                const q = (document.getElementById('patientSearch')?.value || '').toLowerCase().trim();
                const filter = (document.getElementById('statusFilter')?.value || '').toLowerCase().trim();

                const list = document.getElementById('patientsList');
                const recent = document.getElementById('recentList');
                if (!list || !recent) return;

                const rows = patients.filter(p => {
                    const name = (p.full_name ?? '').toString().toLowerCase();
                    const code = (p.patient_code ?? '').toString().toLowerCase();
                    const matchQ = !q || name.includes(q) || code.includes(q);
                    const bucket = bucketFromProgress(p.progress_status);
                    const matchF = !filter || bucket === filter;
                    return matchQ && matchF;
                });

                if (!patients.length) {
                    list.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No patients found.</div>';
                    recent.innerHTML = '';
                    return;
                }

                list.innerHTML = rows.map(p => {
                    const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                    const fullName = escapeHtml(p.full_name || '');
                    const age = escapeHtml(ageFromDob(p.dob));
                    const progressStatus = escapeHtml(p.progress_status || 'Registered');
                    const doneProcess = escapeHtml(p.done_process || '-');
                    const nextProcedure = escapeHtml(p.next_procedure || '-');
                    const department = escapeHtml(p.department || p.initial_location || '-');
                    const chip = statusChip(p.progress_status);

                    return `
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                                    <i class="fas fa-user text-emerald-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <div class="text-sm font-extrabold text-slate-800">${fullName}</div>
                                            <div class="text-xs text-slate-500">ID: ${code} · Age: ${age}</div>
                                        </div>
                                        <span class="${chip.cls}">${chip.label}</span>
                                    </div>
                                    <div class="mt-2 text-xs font-bold text-slate-700">${progressStatus}</div>
                                    <div class="text-[11px] text-slate-500">${department}</div>
                                    <div class="mt-3 flex items-center gap-2 flex-wrap">
                                        <span class="chip ${chipClass(progressStatus)}">${progressStatus}</span>
                                        <span class="chip ${chipClass(doneProcess)}">${doneProcess}</span>
                                        <span class="chip ${chipClass(nextProcedure)}">Next: ${nextProcedure}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');

                recent.innerHTML = patients.slice(0, 2).map(p => {
                    const fullName = escapeHtml(p.full_name || '');
                    const progressStatus = escapeHtml(p.progress_status || 'Registered');
                    const doneProcess = escapeHtml(p.done_process || '-');
                    const chip = statusChip(p.progress_status);
                    return `
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                                    <i class="fas fa-user text-emerald-600"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-extrabold text-slate-800">${fullName}</div>
                                    <div class="text-[11px] text-slate-500">${doneProcess}</div>
                                </div>
                                <span class="${chip.cls}">${chip.label}</span>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            async function loadPatients() {
                const list = document.getElementById('patientsList');
                const recent = document.getElementById('recentList');
                if (list) list.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">Loading...</div>';
                if (recent) recent.innerHTML = '';

                const q = (document.getElementById('patientSearch')?.value || '').toString().trim();
                const url = '../api/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');

                try {
                    const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        patients = [];
                        if (list) list.innerHTML = '<div class="text-center text-sm text-red-600 py-8">Unable to load patients.</div>';
                        return;
                    }
                    patients = Array.isArray(json.patients) ? json.patients : [];
                    render();
                } catch (e) {
                    patients = [];
                    if (list) list.innerHTML = '<div class="text-center text-sm text-red-600 py-8">Unable to load patients.</div>';
                }
            }

            document.getElementById('patientSearch')?.addEventListener('input', () => {
                if (fetchTimer) window.clearTimeout(fetchTimer);
                fetchTimer = window.setTimeout(loadPatients, 250);
            });
            document.getElementById('statusFilter')?.addEventListener('change', render);

            loadPatients();
        })();
    </script>
</body>

</html>
