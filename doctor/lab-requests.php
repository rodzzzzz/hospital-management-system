<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Lab Requests</title>
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
                    <div class="text-xs font-semibold opacity-90">LAB REQUESTS</div>
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

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="flex items-center gap-3">
                <div class="flex-1 bg-white rounded-xl px-3 py-2 flex items-center gap-2 border border-slate-100">
                    <i class="fas fa-search text-slate-400"></i>
                    <input id="labSearch" type="text" placeholder="Search request" class="w-full outline-none text-sm text-slate-700" />
                </div>
                <div class="bg-white rounded-xl px-3 py-2 border border-slate-100">
                    <select id="labStatus" class="text-sm text-slate-700 outline-none bg-transparent">
                        <option value="">All</option>
                        <option value="pending_approval">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="collected">Collected</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <div id="labRequestsList" class="mt-4 space-y-4"></div>
        </div>
    </div>

    <div id="labDetailsModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[100]">
        <div class="w-full max-w-[420px] px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 bg-emerald-600 text-white flex items-center justify-between">
                    <div class="text-sm font-extrabold">Lab Request Details</div>
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
            const listEl = document.getElementById('labRequestsList');
            const searchEl = document.getElementById('labSearch');
            const statusEl = document.getElementById('labStatus');
            const modal = document.getElementById('labDetailsModal');
            const modalClose = document.getElementById('labDetailsClose');
            const modalContent = document.getElementById('labDetailsContent');

            const doctorName = <?php echo json_encode(trim($doctorName) !== '' ? $doctorName : (string)($doctorUser['username'] ?? 'Doctor')); ?>;

            let requests = [];
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

            function chipForStatus(status) {
                const s = (status ?? '').toString().toLowerCase();
                if (s === 'pending_approval') return { label: 'Pending', cls: 'chip bg-amber-100 text-amber-700' };
                if (s === 'approved') return { label: 'Approved', cls: 'chip bg-emerald-100 text-emerald-700' };
                if (s === 'rejected') return { label: 'Rejected', cls: 'chip bg-red-100 text-red-700' };
                if (s === 'collected') return { label: 'Collected', cls: 'chip bg-slate-100 text-slate-700' };
                if (s === 'in_progress') return { label: 'In Progress', cls: 'chip bg-blue-100 text-blue-700' };
                if (s === 'completed') return { label: 'Completed', cls: 'chip bg-emerald-100 text-emerald-700' };
                if (s === 'cancelled') return { label: 'Cancelled', cls: 'chip bg-slate-100 text-slate-700' };
                return { label: status || '-', cls: 'chip bg-slate-100 text-slate-700' };
            }

            function toggleModal(show) {
                if (!modal) return;
                modal.classList.toggle('hidden', !show);
                modal.classList.toggle('flex', !!show);
            }

            async function actOnRequest(requestId, action) {
                const id = Number(requestId);
                if (!id || !Number.isFinite(id)) return;

                const a = (action ?? '').toString().toLowerCase();
                if (a !== 'approve' && a !== 'reject') return;

                let reason = '';
                if (a === 'approve') {
                    if (!confirm('Approve this lab request?')) return;
                } else {
                    reason = (prompt('Rejection reason:') ?? '').toString().trim();
                    if (!reason) return;
                }

                try {
                    const res = await fetch('../api/lab/approve.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({
                            request_id: id,
                            doctor_name: (doctorName ?? '').toString().trim() || 'Doctor',
                            action: a,
                            reason,
                        }),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        alert((json && json.error) ? json.error : 'Action failed');
                        return;
                    }

                    await loadRequests();
                    if (modal && !modal.classList.contains('hidden')) {
                        await openDetails(id);
                    }
                } catch (e) {
                    alert('Action failed');
                }
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
                const chip = chipForStatus(r.status);
                const pending = ((r.status ?? '').toString().toLowerCase() === 'pending_approval');

                modalContent.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <div class="text-sm font-extrabold text-slate-800">${escapeHtml(r.full_name || '')}</div>
                                <div class="text-xs text-slate-500">${escapeHtml(r.patient_code || '')} · ${escapeHtml(r.request_no || '')}</div>
                            </div>
                            <span class="${chip.cls}">${chip.label}</span>
                        </div>

                        ${pending ? `
                            <div class="pt-1 flex gap-2">
                                <button type="button" data-action="approve" data-id="${escapeHtml(String(r.id || id))}" class="flex-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold py-2">Approve</button>
                                <button type="button" data-action="reject" data-id="${escapeHtml(String(r.id || id))}" class="flex-1 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold py-2">Reject</button>
                            </div>
                        ` : ''}

                        <div class="text-xs text-slate-700"><span class="font-bold">Complaint:</span> ${escapeHtml(r.chief_complaint || '-') }</div>
                        <div class="text-xs text-slate-700"><span class="font-bold">Source:</span> ${escapeHtml(r.source_unit || '-') }</div>
                        <div class="text-xs text-slate-700"><span class="font-bold">Priority:</span> ${escapeHtml(r.priority || '-') }</div>

                        <div class="pt-2">
                            <div class="text-xs font-extrabold text-slate-800">Tests</div>
                            <div class="mt-2 space-y-2">
                                ${items.map(it => {
                                    const hasResult = (it.result_text || it.released_at);
                                    return `
                                        <div class="rounded-xl border border-slate-100 bg-slate-50 p-3">
                                            <div class="text-xs font-bold text-slate-800">${escapeHtml(it.test_name || it.test_code || '-') }</div>
                                            <div class="mt-1 text-[11px] text-slate-500">${hasResult ? 'Result available' : 'No result yet'}</div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    </div>
                `;
            }

            async function loadRequests() {
                if (!listEl) return;
                listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">Loading...</div>';

                const q = (searchEl?.value || '').toString().trim();
                const status = (statusEl?.value || '').toString().trim();
                const params = new URLSearchParams();
                params.set('mode', 'doctor');
                if (q) params.set('q', q);
                if (status) params.set('status', status);

                try {
                    const res = await fetch('../api/lab/list_requests.php?' + params.toString(), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        requests = [];
                        render();
                        return;
                    }
                    requests = Array.isArray(json.requests) ? json.requests : [];
                    render();
                } catch (e) {
                    requests = [];
                    render();
                }
            }

            function render() {
                if (!listEl) return;
                const rows = Array.isArray(requests) ? requests : [];
                if (!rows.length) {
                    listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No lab requests found.</div>';
                    return;
                }

                listEl.innerHTML = rows.map(r => {
                    const chip = chipForStatus(r.status);
                    const title = escapeHtml(r.full_name || '');
                    const subtitle = escapeHtml((r.request_no || '') + (r.patient_code ? (' · ' + r.patient_code) : ''));
                    const tests = escapeHtml(r.tests || '');
                    const complaint = escapeHtml(r.chief_complaint || '-');
                    const id = Number(r.id);
                    const pending = ((r.status ?? '').toString().toLowerCase() === 'pending_approval');

                    const actions = pending ? `
                        <div class="mt-3 flex gap-2">
                            <button type="button" data-action="approve" data-id="${id}" class="flex-1 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-extrabold py-2">Approve</button>
                            <button type="button" data-action="reject" data-id="${id}" class="flex-1 rounded-xl bg-red-600 hover:bg-red-700 text-white text-xs font-extrabold py-2">Reject</button>
                        </div>
                    ` : '';

                    return `
                        <div class="w-full text-left bg-white rounded-2xl shadow-sm border border-slate-100 p-4 cursor-pointer" data-request-id="${id}">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-sm font-extrabold text-slate-800">${title}</div>
                                    <div class="text-[11px] text-slate-500">${subtitle}</div>
                                </div>
                                <span class="${chip.cls}">${chip.label}</span>
                            </div>
                            <div class="mt-2 text-xs font-bold text-slate-700">${complaint}</div>
                            ${tests ? `<div class="mt-2 text-[11px] text-slate-500">${tests}</div>` : ''}
                            ${actions}
                        </div>
                    `;
                }).join('');
            }

            modalClose?.addEventListener('click', () => toggleModal(false));
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) toggleModal(false);
            });

            listEl?.addEventListener('click', (e) => {
                const btn = e.target?.closest?.('button[data-action][data-id]');
                if (btn && listEl.contains(btn)) {
                    e.preventDefault();
                    e.stopPropagation();
                    actOnRequest(btn.getAttribute('data-id'), btn.getAttribute('data-action'));
                    return;
                }

                const card = e.target?.closest?.('[data-request-id]');
                if (card && listEl.contains(card)) {
                    const id = Number(card.getAttribute('data-request-id'));
                    if (id) openDetails(id);
                }
            });

            modalContent?.addEventListener('click', (e) => {
                const btn = e.target?.closest?.('button[data-action][data-id]');
                if (!btn || !modalContent.contains(btn)) return;
                e.preventDefault();
                e.stopPropagation();
                actOnRequest(btn.getAttribute('data-id'), btn.getAttribute('data-action'));
            });

            window.__openLabReqDetails = openDetails;

            searchEl?.addEventListener('input', () => {
                if (fetchTimer) window.clearTimeout(fetchTimer);
                fetchTimer = window.setTimeout(loadRequests, 250);
            });
            statusEl?.addEventListener('change', loadRequests);

            loadRequests();
        })();
    </script>
</body>

</html>
