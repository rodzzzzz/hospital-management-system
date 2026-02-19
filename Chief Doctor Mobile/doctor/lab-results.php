<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Lab Results</title>
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

            .mobile-frame .p-4 {
                padding: 0.875rem !important;
            }

            .mobile-frame .space-y-4 > :not([hidden]) ~ :not([hidden]) {
                margin-top: 0.75rem !important;
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
                <a href="lab-results.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
                    <i class="fas fa-file-medical text-emerald-600"></i>
                    <span class="font-semibold">Lab Results</span>
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
                    <div class="text-xs font-semibold opacity-90">LAB RESULTS</div>
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
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-white rounded-xl px-3 py-2 flex items-center gap-2 border border-slate-100">
                    <i class="fas fa-search text-slate-400"></i>
                    <input id="labSearch" type="text" placeholder="Search results" class="w-full outline-none text-sm text-slate-700" />
                </div>
            </div>

            <div id="labResultsList" class="mt-4 space-y-4"></div>
        </div>
    </div>

    <div id="labDetailsModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[100]">
        <div class="w-full max-w-[420px] px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 bg-emerald-600 text-white flex items-center justify-between">
                    <div class="text-sm font-extrabold">Lab Result Details</div>
                    <button type="button" id="labDetailsClose" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="labDetailsContent" class="p-5 max-h-[70vh] overflow-y-auto no-scrollbar"></div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const openBtn = document.getElementById('drawerOpen');
            const closeBtn = document.getElementById('drawerClose');
            const listEl = document.getElementById('labResultsList');
            const searchEl = document.getElementById('labSearch');
            const modal = document.getElementById('labDetailsModal');
            const modalClose = document.getElementById('labDetailsClose');
            const modalContent = document.getElementById('labDetailsContent');

            let rows = [];
            let fetchTimer = null;

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

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function toggleModal(show) {
                if (!modal) return;
                modal.classList.toggle('hidden', !show);
                modal.classList.toggle('flex', !!show);
            }

            async function openDetails(id) {
                if (!modalContent) return;
                modalContent.innerHTML = '<div class="text-sm text-slate-500">Loading...</div>';
                toggleModal(true);

                const res = await fetch('../api/lab/get_request.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    modalContent.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                    return;
                }

                const r = json.request || {};
                const items = Array.isArray(json.items) ? json.items : [];

                modalContent.innerHTML = `
                    <div class="space-y-3">
                        <div>
                            <div class="text-sm font-extrabold text-slate-800">${escapeHtml(r.full_name || '')}</div>
                            <div class="text-xs text-slate-500">${escapeHtml(r.patient_code || '')} · ${escapeHtml(r.request_no || '')}</div>
                        </div>
                        <div class="text-xs text-slate-700"><span class="font-bold">Released:</span> ${escapeHtml(r.released_at || r.completed_at || '-') }</div>

                        <div class="pt-2">
                            <div class="text-xs font-extrabold text-slate-800">Results</div>
                            <div class="mt-2 space-y-2">
                                ${items.map(it => {
                                    const resultText = (it.result_text ?? '').toString().trim();
                                    return `
                                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                            <div class="text-xs font-bold text-slate-800">${escapeHtml(it.test_name || it.test_code || '-') }</div>
                                            <div class="mt-2 text-[11px] text-slate-600 whitespace-pre-wrap">${escapeHtml(resultText || 'No result text.')}</div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }

            async function loadResults() {
                if (!listEl) return;
                listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">Loading...</div>';

                const q = (searchEl?.value || '').toString().trim();
                const params = new URLSearchParams();
                params.set('mode', 'doctor');
                params.set('status', 'completed');
                if (q) params.set('q', q);

                try {
                    const res = await fetch('../api/lab/list_requests.php?' + params.toString(), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        rows = [];
                        render();
                        return;
                    }
                    rows = Array.isArray(json.requests) ? json.requests : [];
                    render();
                } catch (e) {
                    rows = [];
                    render();
                }
            }

            function render() {
                if (!listEl) return;
                const rws = Array.isArray(rows) ? rows : [];
                if (!rws.length) {
                    listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No lab results found.</div>';
                    return;
                }

                listEl.innerHTML = rws.map(r => {
                    const id = Number(r.id);
                    const name = escapeHtml(r.full_name || '');
                    const meta = escapeHtml((r.request_no || '') + (r.patient_code ? (' · ' + r.patient_code) : ''));
                    const tests = escapeHtml(r.tests || '');
                    return `
                        <button type="button" class="w-full text-left bg-white rounded-2xl shadow-sm border border-slate-100 p-4" onclick="window.__openLabResultDetails(${id})">
                            <div>
                                <div class="text-sm font-extrabold text-slate-800">${name}</div>
                                <div class="text-[11px] text-slate-500">${meta}</div>
                            </div>
                            ${tests ? `<div class="mt-2 text-[11px] text-slate-500">${tests}</div>` : ''}
                            <div class="mt-2 text-xs font-bold text-emerald-700">Completed</div>
                        </button>
                    `;
                }).join('');
            }

            modalClose?.addEventListener('click', () => toggleModal(false));
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) toggleModal(false);
            });

            window.__openLabResultDetails = openDetails;

            searchEl?.addEventListener('input', () => {
                if (fetchTimer) window.clearTimeout(fetchTimer);
                fetchTimer = window.setTimeout(loadResults, 250);
            });

            loadResults();
        })();
    </script>
</body>

</html>
