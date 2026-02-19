<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Appointments</title>
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
                <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
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
                    <div class="text-xs font-semibold opacity-90">APPOINTMENTS</div>
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

            <div class="mt-4 bg-white rounded-xl p-1 flex items-center gap-1">
                <button type="button" data-tab="today" class="tabBtn flex-1 px-3 py-2 rounded-lg text-sm font-bold text-emerald-700 bg-emerald-50">Today</button>
                <button type="button" data-tab="upcoming" class="tabBtn flex-1 px-3 py-2 rounded-lg text-sm font-bold text-slate-500">Upcoming</button>
                <button type="button" data-tab="requests" class="tabBtn flex-1 px-3 py-2 rounded-lg text-sm font-bold text-slate-500">Requests</button>
            </div>

            <div class="mt-3 flex items-center gap-3">
                <div class="flex-1 bg-white rounded-xl px-3 py-2 flex items-center gap-2">
                    <i class="fas fa-search text-slate-400"></i>
                    <input id="apptSearch" type="text" placeholder="Search appointment" class="w-full outline-none text-sm text-slate-700" />
                </div>
                <div class="bg-white rounded-xl px-3 py-2">
                    <select id="apptStatus" class="text-sm text-slate-700 outline-none bg-transparent">
                        <option value="">All</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="requested">Requested</option>
                        <option value="waiting">Waiting</option>
                        <option value="checked_in">Checked-In</option>
                        <option value="in_consultation">In-Consultation</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                        <option value="no_show">No Show</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div id="apptList" class="space-y-4"></div>
        </div>
    </div>

    <div id="assessmentHistoryModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[120]">
        <div class="w-full max-w-[420px] px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 bg-emerald-600 text-white flex items-center justify-between">
                    <div class="text-sm font-extrabold">Nursing Assessment</div>
                    <button type="button" id="assessmentHistoryClose" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-5">
                    <div id="assessmentHistoryMeta" class="text-xs text-slate-600"></div>
                    <div class="mt-3">
                        <select id="assessmentHistorySelect" class="w-full px-3 py-2 rounded-xl border border-slate-200 bg-white text-sm text-slate-700 outline-none"></select>
                    </div>
                    <div id="assessmentHistoryView" class="mt-3 max-h-[56vh] overflow-y-auto no-scrollbar"></div>
                    <div class="mt-5">
                        <button type="button" id="assessmentHistoryCloseBtn" class="w-full py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="apptRespondModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[100]">
        <div class="w-full max-w-[420px] px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 bg-emerald-600 text-white flex items-center justify-between">
                    <div class="text-sm font-extrabold">Respond to Request</div>
                    <button type="button" id="apptRespondClose" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="p-5">
                    <div id="apptRespondMeta" class="text-xs text-slate-600"></div>
                    <div id="apptRespondAssessment" class="mt-3 text-xs text-slate-600"></div>

                    <div class="mt-4">
                        <div class="text-xs font-extrabold text-slate-700">Laboratory Tests</div>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="bun" />
                                <span>BUN</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="cbc" />
                                <span>Complete Blood Count (CBC)</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="creatinine" />
                                <span>Creatinine</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="electrolytes" />
                                <span>Electrolytes (Na/K/Cl)</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="fbs" />
                                <span>Fasting Blood Sugar (FBS)</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="pregnancy" />
                                <span>Pregnancy Test</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="rbs" />
                                <span>Random Blood Sugar (RBS)</span>
                            </label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700">
                                <input type="checkbox" class="rounded border-slate-300" name="apptRespondLabTests" value="urinalysis" />
                                <span>Urinalysis</span>
                            </label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-bold text-slate-700">Lab Note</label>
                        <textarea id="apptRespondLabNote" rows="2" class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none text-sm" placeholder="Optional..."></textarea>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-slate-700">Date</label>
                            <input id="apptRespondDate" type="date" class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none" />
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-700">Time</label>
                            <input id="apptRespondTime" type="time" class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none" />
                        </div>
                    </div>

                    <div class="mt-5 flex items-center gap-2">
                        <button type="button" id="apptRespondCancel" class="flex-1 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">Cancel</button>
                        <button type="button" id="apptRespondApprove" class="flex-1 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">Approve</button>
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

            let appts = [];
            let activeTab = 'today';

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function statusChip(status) {
                const s = (status ?? '').toString().toLowerCase();
                if (s === 'requested') return { label: 'Requested', cls: 'chip bg-indigo-100 text-indigo-700' };
                if (s === 'scheduled') return { label: 'Scheduled', cls: 'chip bg-purple-100 text-purple-700' };
                if (s === 'in_consultation') return { label: 'In-Consultation', cls: 'chip bg-blue-100 text-blue-700' };
                if (s === 'waiting') return { label: 'Waiting', cls: 'chip bg-amber-100 text-amber-700' };
                if (s === 'checked_in') return { label: 'Checked-In', cls: 'chip bg-slate-100 text-slate-700' };
                if (s === 'completed') return { label: 'Completed', cls: 'chip bg-emerald-100 text-emerald-700' };
                if (s === 'cancelled') return { label: 'Cancelled', cls: 'chip bg-red-100 text-red-700' };
                if (s === 'no_show') return { label: 'No Show', cls: 'chip bg-slate-100 text-slate-700' };
                if (s === 'rejected') return { label: 'Rejected', cls: 'chip bg-red-100 text-red-700' };
                return { label: status || '-', cls: 'chip bg-slate-100 text-slate-700' };
            }

            function dateStr(offsetDays) {
                const d = new Date();
                d.setHours(0, 0, 0, 0);
                d.setDate(d.getDate() + (Number(offsetDays) || 0));
                return d.toISOString().slice(0, 10);
            }

            function formatApptDateTime(dateTimeStr) {
                const s = (dateTimeStr ?? '').toString();
                const d = new Date(s.replace(' ', 'T'));
                if (Number.isNaN(d.getTime())) return '';
                const day = d.toLocaleDateString([], { month: 'short', day: '2-digit' });
                const time = d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
                return `${day} · ${time}`;
            }

            function parseVitalsJson(v) {
                if (!v) return null;
                if (typeof v === 'object') return v;
                try { return JSON.parse(String(v)); } catch (e) { return null; }
            }

            function vitalsInline(vitals) {
                const v = vitals || {};
                const bpSys = (v.bp_systolic ?? '').toString();
                const bpDia = (v.bp_diastolic ?? '').toString();
                const bp = (bpSys && bpDia) ? (bpSys + '/' + bpDia) : (bpSys || bpDia ? (bpSys + '/' + bpDia) : '');
                const parts = [];
                if (bp) parts.push('BP ' + bp);
                if (v.hr) parts.push('HR ' + v.hr);
                if (v.rr) parts.push('RR ' + v.rr);
                if (v.temp) parts.push('Temp ' + v.temp);
                if (v.spo2) parts.push('SpO₂ ' + v.spo2);
                return parts.length ? parts.join(' • ') : '';
            }

            async function loadAppointments() {
                const list = document.getElementById('apptList');
                if (list) list.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">Loading...</div>';

                const q = (document.getElementById('apptSearch')?.value || '').toString().trim();
                const f = (document.getElementById('apptStatus')?.value || '').toString().trim();

                const doctorName = <?php echo json_encode($doctorName, JSON_UNESCAPED_SLASHES); ?>;

                const isRequests = activeTab === 'requests';
                const isUpcoming = activeTab === 'upcoming';
                const date = isUpcoming ? dateStr(1) : dateStr(0);
                const status = isRequests ? 'requested' : (isUpcoming ? ((f && f !== 'requested') ? f : 'scheduled') : (f === 'requested' ? '' : f));

                const params = new URLSearchParams();
                if (!isRequests && date) {
                    if (isUpcoming) {
                        params.set('date_from', date);
                    } else {
                        params.set('date', date);
                    }
                }
                if (status) params.set('status', status);
                if (q) params.set('q', q);
                if (doctorName) params.set('doctor_name', doctorName);

                try {
                    const res = await fetch('../api/opd/list.php' + (params.toString() ? ('?' + params.toString()) : ''), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        appts = [];
                        render();
                        return;
                    }
                    appts = Array.isArray(json.appointments) ? json.appointments : [];
                    render();
                } catch (e) {
                    appts = [];
                    render();
                }
            }

            function render() {
                const list = document.getElementById('apptList');
                if (!list) return;

                const rows = Array.isArray(appts) ? appts : [];
                if (!rows.length) {
                    if (activeTab === 'today') {
                        list.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No appointments today.</div>';
                    } else if (activeTab === 'upcoming') {
                        list.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No upcoming appointments.</div>';
                    } else {
                        list.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No appointment requests.</div>';
                    }
                    return;
                }

                list.innerHTML = rows.map(a => {
                    const chip = statusChip(a.status);
                    const when = (a.status === 'requested') ? 'Request pending' : (formatApptDateTime(a.appointment_at) || '');
                    const patient = escapeHtml(a.full_name || '');
                    const code = escapeHtml(a.patient_code || ('P-' + String(a.patient_id || '')));
                    const doctor = escapeHtml(a.doctor_name || '');
                    const notes = escapeHtml((a.notes || '').toString().trim() || '-');
                    const canRespond = activeTab === 'requests' && (a.status === 'requested');
                    const id = Number(a.id);
                    const hasAssessment = !!(a.nursing_assessment_id);

                    return `
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-bold text-slate-500">${escapeHtml(when)}</div>
                                    <div class="mt-1 text-sm font-extrabold text-slate-800">${patient}</div>
                                    <div class="text-[11px] text-slate-500">ID: ${code}</div>
                                    <div class="text-[11px] text-slate-500">${doctor}</div>
                                </div>
                                <span class="${chip.cls}">${chip.label}</span>
                            </div>
                            <div class="mt-3 text-xs font-bold text-slate-700">${notes}</div>

                            ${activeTab === 'requests' ? `
                                <div class="mt-3">
                                    <button type="button" class="w-full py-2 rounded-xl ${hasAssessment ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-700'} text-sm font-bold hover:bg-slate-200" onclick="window.__openAssessmentHistory(${id})">View Assessment</button>
                                </div>
                            ` : ''}

                            ${canRespond ? `
                                <div class="mt-4 flex items-center gap-2">
                                    <button type="button" class="flex-1 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700" onclick="window.__openApptRespond(${id})">Approve</button>
                                    <button type="button" class="flex-1 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200" onclick="window.__rejectApptReq(${id})">Reject</button>
                                </div>
                            ` : ''}
                        </div>
                    `;
                }).join('');
            }

            const respondModal = document.getElementById('apptRespondModal');
            const respondClose = document.getElementById('apptRespondClose');
            const respondCancel = document.getElementById('apptRespondCancel');
            const respondApprove = document.getElementById('apptRespondApprove');
            const respondMeta = document.getElementById('apptRespondMeta');
            const respondAssess = document.getElementById('apptRespondAssessment');
            const respondDate = document.getElementById('apptRespondDate');
            const respondTime = document.getElementById('apptRespondTime');
            const respondLabNote = document.getElementById('apptRespondLabNote');

            let respondApptId = null;
            let respondAssignedDoctorName = '';

            const assessModal = document.getElementById('assessmentHistoryModal');
            const assessClose = document.getElementById('assessmentHistoryClose');
            const assessCloseBtn = document.getElementById('assessmentHistoryCloseBtn');
            const assessMeta = document.getElementById('assessmentHistoryMeta');
            const assessSelect = document.getElementById('assessmentHistorySelect');
            const assessView = document.getElementById('assessmentHistoryView');
            let assessRows = [];
            let assessHighlightId = null;

            function toggleAssessmentModal(show) {
                if (!assessModal) return;
                assessModal.classList.toggle('hidden', !show);
                assessModal.classList.toggle('flex', !!show);
            }

            function parseJsonMaybe(v) {
                if (!v) return null;
                if (typeof v === 'object') return v;
                try { return JSON.parse(String(v)); } catch (e) { return null; }
            }

            function renderVitalsGrid(v) {
                if (!v) return '<div class="text-[11px] text-slate-500">No vitals recorded.</div>';
                const bpSys = (v.bp_systolic ?? '').toString();
                const bpDia = (v.bp_diastolic ?? '').toString();
                const bp = (bpSys && bpDia) ? (bpSys + '/' + bpDia) : (bpSys || bpDia ? (bpSys + '/' + bpDia) : '');
                const items = [
                    ['BP', bp],
                    ['HR', v.hr],
                    ['RR', v.rr],
                    ['Temp', v.temp],
                    ['SpO₂', v.spo2],
                    ['Weight', v.weight],
                    ['Height', v.height],
                ].filter(x => x[1] !== null && x[1] !== undefined && String(x[1]).trim() !== '');
                if (!items.length) return '<div class="text-[11px] text-slate-500">No vitals recorded.</div>';
                return '<div class="grid grid-cols-2 gap-2">' + items.map(([k, val]) => {
                    return `<div class="text-[11px] text-slate-700"><span class="font-extrabold">${escapeHtml(k)}:</span> ${escapeHtml(String(val))}</div>`;
                }).join('') + '</div>';
            }

            function renderFullAssessment(row) {
                const vitals = parseVitalsJson(row.vitals_json);
                const extra = parseJsonMaybe(row.assessment_json) || {};
                const hpi = (extra.hpi && typeof extra.hpi === 'object') ? extra.hpi : {};
                const pmh = (extra.pmh && typeof extra.pmh === 'object') ? extra.pmh : {};
                const social = (extra.social && typeof extra.social === 'object') ? extra.social : {};
                const when = row.created_at ? new Date(String(row.created_at)).toLocaleString() : '';
                const nurse = (row.nurse_name || '').toString().trim() || '-';
                const notes = (row.notes || '').toString().trim() || '-';
                const isHi = assessHighlightId && Number(row.id) === Number(assessHighlightId);

                const line = (label, value) => {
                    const v = (value === null || value === undefined || String(value).trim() === '') ? '-' : escapeHtml(String(value));
                    return `<div class="text-[11px] text-slate-700"><span class="font-extrabold">${escapeHtml(label)}:</span> ${v}</div>`;
                };

                return `
                    <div class="space-y-3">
                        ${isHi ? '<span class="chip bg-emerald-600 text-white">Attached</span>' : ''}

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Assessment Info</div>
                            <div class="mt-2 text-[11px] text-slate-600">When: ${when ? escapeHtml(when) : '-'}</div>
                            <div class="text-[11px] text-slate-600">Nurse: ${escapeHtml(nurse)}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Vitals</div>
                            <div class="mt-2">${renderVitalsGrid(vitals)}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">History of Present Illness</div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                ${line('Start', hpi.start)}
                                ${line('Duration/Frequency', hpi.duration)}
                                ${line('Severity', hpi.severity)}
                                ${line('Associated Symptoms', hpi.associated)}
                            </div>
                            <div class="mt-2">${line('Aggravating/Relieving', hpi.factors)}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Past Medical History</div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                ${line('Diabetes', pmh.diabetes ? 'Yes' : 'No')}
                                ${line('Hypertension', pmh.hypertension ? 'Yes' : 'No')}
                                ${line('Asthma', pmh.asthma ? 'Yes' : 'No')}
                                ${line('Heart Disease', pmh.heart_disease ? 'Yes' : 'No')}
                            </div>
                            <div class="mt-2">${line('Other', pmh.other)}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Surgical History</div>
                            <div class="mt-2 text-[11px] text-slate-700 whitespace-pre-wrap">${escapeHtml((extra.surgical_history || '-').toString())}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Current Medications</div>
                            <div class="mt-2 text-[11px] text-slate-700 whitespace-pre-wrap">${escapeHtml((extra.current_medications || '-').toString())}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Allergies</div>
                            <div class="mt-2 text-[11px] text-slate-700">${extra.allergies_none ? 'None' : escapeHtml((extra.allergies_other || '-').toString())}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Family History</div>
                            <div class="mt-2 text-[11px] text-slate-700 whitespace-pre-wrap">${escapeHtml((extra.family_history || '-').toString())}</div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Social History</div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                ${line('Smoking', social.smoking)}
                                ${line('Alcohol', social.alcohol)}
                                ${line('Occupation', social.occupation)}
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Notes</div>
                            <div class="mt-2 text-[11px] text-slate-700 whitespace-pre-wrap">${escapeHtml(notes)}</div>
                        </div>
                    </div>
                `;
            }

            function renderAssessmentHistory(items, highlightId) {
                assessRows = Array.isArray(items) ? items : [];
                assessHighlightId = highlightId ? Number(highlightId) : null;

                if (assessView) assessView.innerHTML = '';
                if (!assessSelect || !assessView) return;

                if (!assessRows.length) {
                    assessSelect.innerHTML = '<option value="">No assessments</option>';
                    assessView.innerHTML = '<div class="text-center text-sm text-slate-500 py-4">No nursing assessments found.</div>';
                    return;
                }

                let rows = assessRows;
                if (assessHighlightId) {
                    const hid = Number(assessHighlightId);
                    const first = rows.find(x => Number(x && x.id) === hid) || null;
                    const rest = rows.filter(x => Number(x && x.id) !== hid);
                    if (first) rows = [first, ...rest];
                }
                assessRows = rows;

                assessSelect.innerHTML = rows.map(x => {
                    const id = Number(x.id || 0);
                    const when = x.created_at ? new Date(String(x.created_at)).toLocaleString() : '';
                    const nurse = (x.nurse_name || '').toString().trim() || '-';
                    return `<option value="${id}">${when ? escapeHtml(when) : '—'} • ${escapeHtml(nurse)}${(assessHighlightId && id === assessHighlightId) ? ' (Attached)' : ''}</option>`;
                }).join('');

                const defaultId = assessHighlightId ? String(assessHighlightId) : String(Number(rows[0].id || 0));
                assessSelect.value = defaultId;
                const selected = rows.find(x => String(Number(x.id || 0)) === String(assessSelect.value)) || rows[0];
                assessView.innerHTML = renderFullAssessment(selected);
            }

            async function openAssessmentHistory(appointmentId) {
                const appt = (appts || []).find(a => Number(a.id) === Number(appointmentId)) || null;
                if (!appt) return;

                const patientId = Number(appt.patient_id || 0);
                const highlightId = appt.nursing_assessment_id ? Number(appt.nursing_assessment_id) : null;
                if (assessMeta) {
                    assessMeta.textContent = (appt.full_name ? String(appt.full_name) : 'Patient') + (appt.patient_code ? (' • ' + String(appt.patient_code)) : '');
                }
                if (assessView) {
                    assessView.innerHTML = '<div class="text-center text-sm text-slate-500 py-4">Loading...</div>';
                }
                toggleAssessmentModal(true);

                if (!patientId) return;
                try {
                    const res = await fetch('../api/opd_assessment/list.php?patient_id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        renderAssessmentHistory([], highlightId);
                        return;
                    }
                    renderAssessmentHistory(json.assessments || [], highlightId);
                } catch (e) {
                    renderAssessmentHistory([], highlightId);
                }
            }

            function showToast(message, isError) {
                const text = (message || '').toString().trim();
                if (!text) return;
                const existing = document.getElementById('apptToast');
                if (existing) existing.remove();

                const el = document.createElement('div');
                el.id = 'apptToast';
                el.className = 'fixed top-4 left-1/2 -translate-x-1/2 z-[200] px-4 py-3 rounded-xl shadow-xl text-sm font-bold ' + (isError ? 'bg-red-600 text-white' : 'bg-emerald-600 text-white');
                el.textContent = text;
                document.body.appendChild(el);
                window.setTimeout(function () {
                    try { el.remove(); } catch (e) { }
                }, 2600);
            }

            function toggleRespondModal(show) {
                if (!respondModal) return;
                respondModal.classList.toggle('hidden', !show);
                respondModal.classList.toggle('flex', !!show);
            }

            async function rejectRequest(id) {
                const res = await fetch('../api/opd/update_status.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ id, status: 'rejected' })
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    return;
                }
                await loadAppointments();
            }

            async function loadLatestAssessment(appointmentId) {
                if (!respondAssess) return;
                respondAssess.innerHTML = '';
                try {
                    const res = await fetch('../api/opd_assessment/latest.php?appointment_id=' + encodeURIComponent(String(appointmentId)), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        respondAssess.innerHTML = '';
                        return;
                    }

                    const a = json.assessment;
                    if (!a) {
                        respondAssess.innerHTML = '<div class="text-[11px] text-slate-500">No nursing assessment yet.</div>';
                        return;
                    }

                    const triage = (a.triage_level === null || a.triage_level === undefined || a.triage_level === '') ? '' : String(a.triage_level);
                    const nurse = (a.nurse_name || '').toString().trim();
                    const when = a.created_at ? new Date(String(a.created_at)).toLocaleString() : '';
                    const vitals = parseVitalsJson(a.vitals_json);
                    const vLine = vitalsInline(vitals);
                    const notes = (a.notes || '').toString().trim();

                    respondAssess.innerHTML = `
                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-3">
                            <div class="text-[11px] font-extrabold text-slate-700">Latest Nursing Assessment</div>
                            <div class="mt-1 text-[11px] text-slate-600">${triage ? ('Triage: ' + escapeHtml(triage) + ' • ') : ''}${nurse ? ('Nurse: ' + escapeHtml(nurse) + ' • ') : ''}${when ? escapeHtml(when) : ''}</div>
                            ${vLine ? `<div class="mt-1 text-[11px] text-slate-700">${escapeHtml(vLine)}</div>` : ''}
                            ${notes ? `<div class="mt-2 text-[11px] text-slate-700">${escapeHtml(notes)}</div>` : ''}
                        </div>
                    `;
                } catch (e) {
                    respondAssess.innerHTML = '';
                }
            }

            function openRespond(id) {
                respondApptId = Number(id);
                const appt = (appts || []).find(x => Number(x.id) === respondApptId);
                respondAssignedDoctorName = appt ? (appt.doctor_name || '').toString().trim() : '';
                if (respondMeta) {
                    if (appt) {
                        respondMeta.textContent = `${(appt.full_name || '').toString()} · ${(appt.patient_code || '').toString()}`;
                    } else {
                        respondMeta.textContent = '';
                    }
                }
                if (respondAssess) {
                    respondAssess.innerHTML = '';
                }
                if (respondDate) respondDate.value = '';
                if (respondTime) respondTime.value = '';
                if (respondLabNote) respondLabNote.value = '';
                document.querySelectorAll('input[name="apptRespondLabTests"]').forEach(el => { el.checked = false; });
                toggleRespondModal(true);
                loadLatestAssessment(respondApptId);
            }

            async function approveRespond() {
                if (!respondApptId) return;
                const date = (respondDate?.value || '').toString().trim();
                const time = (respondTime?.value || '').toString().trim();
                if (!date || !time) {
                    showToast('Please select date and time.', true);
                    return;
                }

                const labTests = Array.from(document.querySelectorAll('input[name="apptRespondLabTests"]:checked'))
                    .map(el => (el.value || '').toString().trim())
                    .filter(Boolean);
                const labNote = (respondLabNote?.value || '').toString().trim();

                if (respondApprove) {
                    respondApprove.disabled = true;
                    respondApprove.classList.add('opacity-70');
                    respondApprove.textContent = 'Approving...';
                }

                try {
                    const res = await fetch('../api/opd/update_status.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ id: respondApptId, status: 'scheduled', date, time, lab_tests: labTests, lab_note: labNote })
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        var msg = (json && json.error) ? json.error : ('Failed to approve (HTTP ' + res.status + ')');
                        if (res.status === 401) {
                            msg = 'Not authenticated. Please login again.';
                        }
                        if (res.status === 403) {
                            const me = <?php echo json_encode($doctorName, JSON_UNESCAPED_SLASHES); ?>;
                            msg = 'Forbidden. Logged-in doctor: ' + (me || '-') + '. Request doctor: ' + (respondAssignedDoctorName || '-') + '.';
                        }
                        showToast(msg, true);
                        return;
                    }

                    showToast('Approved.', false);
                    toggleRespondModal(false);
                    respondApptId = null;
                    await loadAppointments();
                } catch (e) {
                    showToast('Network error. Please try again.', true);
                } finally {
                    if (respondApprove) {
                        respondApprove.disabled = false;
                        respondApprove.classList.remove('opacity-70');
                        respondApprove.textContent = 'Approve';
                    }
                }
            }

            respondClose?.addEventListener('click', () => toggleRespondModal(false));
            respondCancel?.addEventListener('click', () => toggleRespondModal(false));
            respondCancel?.addEventListener('click', function () {
                toggleRespondModal(false);
            });
            respondApprove?.addEventListener('click', approveRespond);

            assessClose?.addEventListener('click', function () {
                toggleAssessmentModal(false);
            });
            assessCloseBtn?.addEventListener('click', function () {
                toggleAssessmentModal(false);
            });

            assessSelect?.addEventListener('change', function () {
                if (!assessView) return;
                const v = (assessSelect.value || '').toString();
                if (!v) return;
                const id = Number(v);
                const row = (assessRows || []).find(x => Number(x && x.id) === id) || null;
                if (!row) return;
                assessView.innerHTML = renderFullAssessment(row);
            });

            window.__openApptRespond = openRespond;
            window.__rejectApptReq = rejectRequest;
            window.__openAssessmentHistory = openAssessmentHistory;

            function setTab(tab) {
                activeTab = tab;
                document.querySelectorAll('.tabBtn').forEach(btn => {
                    const t = (btn.getAttribute('data-tab') || '').toString();
                    const isActive = t === tab;
                    btn.classList.toggle('bg-emerald-50', isActive);
                    btn.classList.toggle('text-emerald-700', isActive);
                    btn.classList.toggle('text-slate-500', !isActive);
                });

                const statusEl = document.getElementById('apptStatus');
                if (statusEl && tab === 'requests') statusEl.value = 'requested';
                if (statusEl && tab !== 'requests' && ((statusEl.value || '').toString() === 'requested')) statusEl.value = '';

                loadAppointments();
            }

            document.querySelectorAll('.tabBtn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const tab = (btn.getAttribute('data-tab') || '').toString();
                    if (tab) setTab(tab);
                });
            });

            document.getElementById('apptSearch')?.addEventListener('input', loadAppointments);
            document.getElementById('apptStatus')?.addEventListener('change', loadAppointments);

            setTab('today');
        })();
    </script>
</body>

</html>
