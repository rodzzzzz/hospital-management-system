<?php require_once __DIR__ . '/_auth.php'; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - ER</title>
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
                <a href="er.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
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
                    <div class="text-xs font-semibold opacity-90">ER</div>
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
                <div class="flex-1 bg-white rounded-xl px-3 py-2 flex items-center gap-2 border border-white/0">
                    <i class="fas fa-search text-slate-400"></i>
                    <input id="erSearch" type="text" placeholder="Search patient" class="w-full outline-none text-sm text-slate-700" />
                </div>
                <button id="erRefresh" type="button" class="bg-white/15 hover:bg-white/20 w-10 h-10 rounded-xl flex items-center justify-center" aria-label="Refresh">
                    <i class="fas fa-rotate"></i>
                </button>
            </div>
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div id="erList" class="space-y-4"></div>
        </div>
    </div>

    <div id="erDetailsModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[110]">
        <div class="w-full max-w-[420px] px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 bg-emerald-600 text-white flex items-center justify-between">
                    <div class="text-sm font-extrabold">ER Nursing Assessment</div>
                    <button type="button" id="erDetailsClose" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="erDetailsMeta" class="p-5 text-xs text-slate-600"></div>
                <div id="erDetailsAssessment" class="px-5 pb-4 max-h-[52vh] overflow-y-auto no-scrollbar"></div>
                <div class="p-5 border-t border-slate-100">
                    <button type="button" id="erSendFeedback" class="w-full py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">Send Feedback</button>
                </div>
            </div>
        </div>
    </div>

    <div id="erFeedbackModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-[120]">
        <div class="w-full max-w-[420px] px-4">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <div class="px-5 py-4 bg-emerald-600 text-white flex items-center justify-between">
                    <div class="text-sm font-extrabold">Doctor Feedback</div>
                    <button type="button" id="erFeedbackClose" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="p-5">
                    <div id="erFeedbackMeta" class="text-xs text-slate-600"></div>

                    <div class="mt-4">
                        <div class="text-xs font-extrabold text-slate-700">Laboratory Tests</div>
                        <div class="mt-2 space-y-2">
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="bun" /><span>BUN</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="cbc" /><span>Complete Blood Count (CBC)</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="creatinine" /><span>Creatinine</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="electrolytes" /><span>Electrolytes (Na/K/Cl)</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="fbs" /><span>Fasting Blood Sugar (FBS)</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="pregnancy" /><span>Pregnancy Test</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="rbs" /><span>Random Blood Sugar (RBS)</span></label>
                            <label class="flex items-center gap-2 text-[11px] text-slate-700"><input type="checkbox" class="rounded border-slate-300" name="erFeedbackLabTests" value="urinalysis" /><span>Urinalysis</span></label>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-xs font-bold text-slate-700">Lab Note</label>
                        <textarea id="erFeedbackLabNote" rows="2" class="mt-1 w-full px-3 py-2 rounded-xl border border-slate-200 bg-white outline-none text-sm" placeholder="Optional..."></textarea>
                    </div>

                    <div class="mt-5 flex items-center gap-2">
                        <button type="button" id="erFeedbackCancel" class="flex-1 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-bold hover:bg-slate-200">Cancel</button>
                        <button type="button" id="erFeedbackSend" class="flex-1 py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700">Send</button>
                    </div>

                    <div id="erFeedbackMsg" class="mt-3 hidden text-xs font-bold"></div>
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

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function parseVitalsJson(v) {
                if (!v) return null;
                if (typeof v === 'object') return v;
                try { return JSON.parse(String(v)); } catch (e) { return null; }
            }

            function parseJsonMaybe(v) {
                if (!v) return null;
                if (typeof v === 'object') return v;
                try { return JSON.parse(String(v)); } catch (e) { return null; }
            }

            function renderVitalsGrid(v) {
                if (!v) return '<div class="text-[11px] text-slate-500">No vitals recorded.</div>';
                const bpSys = (v.bp_systolic ?? v.bp_sys ?? '').toString();
                const bpDia = (v.bp_diastolic ?? v.bp_dia ?? '').toString();
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

            function renderAssessment(assess) {
                if (!assess) return '<div class="text-sm text-slate-500">No assessment.</div>';
                const vitals = parseVitalsJson(assess.vitals_json);
                const extra = parseJsonMaybe(assess.assessment_json) || {};
                const when = assess.created_at ? new Date(String(assess.created_at)).toLocaleString() : '';
                const nurse = (assess.nurse_name || '').toString().trim() || '-';
                const notes = (assess.notes || '').toString().trim();

                const line = (label, value) => {
                    const v = (value === null || value === undefined || String(value).trim() === '') ? '-' : escapeHtml(String(value));
                    return `<div class="text-[11px] text-slate-700"><span class="font-extrabold">${escapeHtml(label)}:</span> ${v}</div>`;
                };

                return `
                    <div class="space-y-3">
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
                                ${line('Start', extra.hpi_start)}
                                ${line('Duration/Frequency', extra.hpi_duration)}
                                ${line('Severity', extra.hpi_severity)}
                                ${line('Associated Symptoms', extra.hpi_associated)}
                            </div>
                            <div class="mt-2">${line('Aggravating/Relieving', extra.hpi_factors)}</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Past Medical History</div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                ${line('Diabetes', extra.pmh_diabetes ? 'Yes' : 'No')}
                                ${line('Hypertension', extra.pmh_hypertension ? 'Yes' : 'No')}
                                ${line('Asthma', extra.pmh_asthma ? 'Yes' : 'No')}
                                ${line('Heart Disease', extra.pmh_heart_disease ? 'Yes' : 'No')}
                            </div>
                            <div class="mt-2">${line('Other', extra.pmh_other)}</div>
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
                            <div class="mt-2 text-[11px] text-slate-700">${escapeHtml((extra.allergies_other || '-').toString())}</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Family History</div>
                            <div class="mt-2 text-[11px] text-slate-700 whitespace-pre-wrap">${escapeHtml((extra.family_history || '-').toString())}</div>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4">
                            <div class="text-xs font-extrabold text-slate-800">Social History</div>
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                ${line('Smoking', extra.social_smoking)}
                                ${line('Alcohol', extra.social_alcohol)}
                                ${line('Occupation', extra.occupation)}
                            </div>
                        </div>
                        ${notes ? `
                            <div class="rounded-2xl border border-slate-200 bg-white p-4">
                                <div class="text-xs font-extrabold text-slate-800">Notes</div>
                                <div class="mt-2 text-[11px] text-slate-700 whitespace-pre-wrap">${escapeHtml(notes)}</div>
                            </div>
                        ` : ''}
                    </div>
                `;
            }

            const listEl = document.getElementById('erList');
            const searchEl = document.getElementById('erSearch');
            const refreshBtn = document.getElementById('erRefresh');

            const detailsModal = document.getElementById('erDetailsModal');
            const detailsClose = document.getElementById('erDetailsClose');
            const detailsMeta = document.getElementById('erDetailsMeta');
            const detailsAssessment = document.getElementById('erDetailsAssessment');
            const sendFeedbackBtn = document.getElementById('erSendFeedback');

            const feedbackModal = document.getElementById('erFeedbackModal');
            const feedbackClose = document.getElementById('erFeedbackClose');
            const feedbackCancel = document.getElementById('erFeedbackCancel');
            const feedbackSend = document.getElementById('erFeedbackSend');
            const feedbackMeta = document.getElementById('erFeedbackMeta');
            const feedbackLabNote = document.getElementById('erFeedbackLabNote');
            const feedbackMsg = document.getElementById('erFeedbackMsg');

            let rows = [];
            let activeSubmission = null;
            let activeAssessment = null;

            function toggleDetails(show) {
                if (!detailsModal) return;
                detailsModal.classList.toggle('hidden', !show);
                detailsModal.classList.toggle('flex', !!show);
            }

            function toggleFeedback(show) {
                if (!feedbackModal) return;
                feedbackModal.classList.toggle('hidden', !show);
                feedbackModal.classList.toggle('flex', !!show);
                if (!show) {
                    document.querySelectorAll('input[name="erFeedbackLabTests"]').forEach(el => { el.checked = false; });
                    if (feedbackLabNote) feedbackLabNote.value = '';
                    if (feedbackMsg) {
                        feedbackMsg.classList.add('hidden');
                        feedbackMsg.textContent = '';
                    }
                }
            }

            function showFeedbackMsg(ok, text) {
                if (!feedbackMsg) return;
                feedbackMsg.classList.remove('hidden');
                feedbackMsg.classList.remove('text-emerald-700', 'text-red-600');
                feedbackMsg.classList.add(ok ? 'text-emerald-700' : 'text-red-600');
                feedbackMsg.textContent = text || '';
            }

            function render() {
                if (!listEl) return;
                const q = (searchEl?.value || '').toString().trim().toLowerCase();
                const filtered = (Array.isArray(rows) ? rows : []).filter(r => {
                    if (!q) return true;
                    return ((r.full_name || '').toString().toLowerCase().includes(q) || (r.patient_code || '').toString().toLowerCase().includes(q) || (r.encounter_no || '').toString().toLowerCase().includes(q));
                });

                if (!filtered.length) {
                    listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">No ER submissions.</div>';
                    return;
                }

                listEl.innerHTML = filtered.map(r => {
                    const id = Number(r.id);
                    const patient = escapeHtml(r.full_name || '');
                    const code = escapeHtml(r.patient_code || ('P-' + String(r.patient_id || '')));
                    const enc = escapeHtml(r.encounter_no || '-');
                    const when = r.submitted_at ? new Date(String(r.submitted_at)).toLocaleString() : '';
                    const by = escapeHtml(r.submitted_by || '-');
                    return `
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <div class="text-xs font-bold text-slate-500">${escapeHtml(when || '')}</div>
                                    <div class="mt-1 text-sm font-extrabold text-slate-800">${patient}</div>
                                    <div class="text-[11px] text-slate-500">ID: ${code}</div>
                                    <div class="text-[11px] text-slate-500">Encounter: ${enc}</div>
                                    <div class="text-[11px] text-slate-500">Submitted by: ${by}</div>
                                </div>
                                <span class="chip bg-indigo-100 text-indigo-700">Submitted</span>
                            </div>
                            <div class="mt-4">
                                <button type="button" class="w-full py-2 rounded-xl bg-emerald-600 text-white text-sm font-bold hover:bg-emerald-700" onclick="window.__openErSubmission(${id})">View</button>
                            </div>
                        </div>
                    `;
                }).join('');
            }

            async function load() {
                if (listEl) listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-8">Loading...</div>';
                const q = (searchEl?.value || '').toString().trim();
                const params = new URLSearchParams();
                if (q) params.set('q', q);

                try {
                    const res = await fetch('../api/er_assessment/doctor_submissions_list.php' + (params.toString() ? ('?' + params.toString()) : ''), {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        rows = [];
                        render();
                        return;
                    }
                    rows = Array.isArray(json.submissions) ? json.submissions : [];
                    render();
                } catch (e) {
                    rows = [];
                    render();
                }
            }

            async function openSubmission(id) {
                activeSubmission = null;
                activeAssessment = null;
                if (detailsMeta) detailsMeta.textContent = '';
                if (detailsAssessment) detailsAssessment.innerHTML = '<div class="text-sm text-slate-500 py-4">Loading...</div>';
                toggleDetails(true);

                try {
                    const res = await fetch('../api/er_assessment/submission_get.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok || !json.submission) {
                        if (detailsAssessment) detailsAssessment.innerHTML = '<div class="text-sm text-slate-500 py-4">Not found.</div>';
                        return;
                    }

                    activeSubmission = json.submission;
                    activeAssessment = json.assessment || null;

                    const sub = json.submission;
                    const patient = (sub.full_name || '').toString();
                    const code = (sub.patient_code || '').toString();
                    const when = sub.submitted_at ? new Date(String(sub.submitted_at)).toLocaleString() : '';
                    const by = (sub.submitted_by || '').toString();

                    if (detailsMeta) {
                        detailsMeta.textContent = [patient, code ? ('· ' + code) : '', when ? ('· ' + when) : '', by ? ('· by ' + by) : ''].filter(Boolean).join(' ');
                    }

                    if (detailsAssessment) {
                        detailsAssessment.innerHTML = renderAssessment(activeAssessment);
                    }
                } catch (e) {
                    if (detailsAssessment) detailsAssessment.innerHTML = '<div class="text-sm text-slate-500 py-4">Failed to load.</div>';
                }
            }

            async function sendFeedback() {
                if (!activeSubmission) return;

                const labTests = Array.from(document.querySelectorAll('input[name="erFeedbackLabTests"]:checked'))
                    .map(el => (el.value || '').toString().trim())
                    .filter(Boolean);
                const labNote = (feedbackLabNote?.value || '').toString().trim();

                if (feedbackSend) {
                    feedbackSend.disabled = true;
                    feedbackSend.classList.add('opacity-70');
                    feedbackSend.textContent = 'Sending...';
                }

                try {
                    const body = {
                        submission_id: Number(activeSubmission.id),
                        patient_id: Number(activeSubmission.patient_id),
                        encounter_id: Number(activeSubmission.encounter_id),
                        er_assessment_id: Number(activeSubmission.er_assessment_id),
                        lab_tests: labTests,
                        lab_note: labNote || null,
                    };

                    const res = await fetch('../api/er_assessment/feedback_create.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(body),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'Failed to send');
                    }

                    showFeedbackMsg(true, 'Sent.');
                    setTimeout(() => {
                        toggleFeedback(false);
                        toggleDetails(false);
                        load();
                    }, 600);
                } catch (e) {
                    showFeedbackMsg(false, e && e.message ? e.message : 'Failed to send');
                } finally {
                    if (feedbackSend) {
                        feedbackSend.disabled = false;
                        feedbackSend.classList.remove('opacity-70');
                        feedbackSend.textContent = 'Send';
                    }
                }
            }

            detailsClose?.addEventListener('click', () => toggleDetails(false));
            feedbackClose?.addEventListener('click', () => toggleFeedback(false));
            feedbackCancel?.addEventListener('click', () => toggleFeedback(false));

            sendFeedbackBtn?.addEventListener('click', () => {
                if (!activeSubmission) return;
                const patient = (activeSubmission.full_name || '').toString();
                const code = (activeSubmission.patient_code || '').toString();
                if (feedbackMeta) {
                    feedbackMeta.textContent = patient + (code ? (' · ' + code) : '');
                }
                toggleFeedback(true);
            });

            feedbackSend?.addEventListener('click', sendFeedback);

            let searchTimer = null;
            searchEl?.addEventListener('input', () => {
                if (searchTimer) clearTimeout(searchTimer);
                searchTimer = setTimeout(load, 250);
            });

            refreshBtn?.addEventListener('click', load);

            window.__openErSubmission = openSubmission;

            load();
        })();
    </script>
</body>

</html>
