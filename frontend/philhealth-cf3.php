<?php
require_once __DIR__ . '/auth.php';
auth_session_start();

$requireClaimSession = true;
if ($requireClaimSession) {
    $claimActive = ($_SESSION['philhealth_claim_active'] ?? false) === true;
    $hasPatientId = isset($_GET['patient_id']) && trim((string)$_GET['patient_id']) !== '';
    if (!$claimActive && !$hasPatientId) {
        header('Location: philhealth-claims.php');
        exit;
    }
}

$resetContainer = true;

$isEditMode = isset($_GET['mode']) && $_GET['mode'] === 'edit';
$selfPath = basename($_SERVER['PHP_SELF']);
$editQuery = $_GET;
$editQuery['mode'] = 'edit';
$editUrl = $selfPath . '?' . http_build_query($editQuery);
$viewQuery = $_GET;
unset($viewQuery['mode']);
$viewUrl = $selfPath . (count($viewQuery) ? ('?' . http_build_query($viewQuery)) : '');

$isEmbed = isset($_GET['embed']) && $_GET['embed'] === '1';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhilHealth CF3</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .cf-box {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }
        .cf-label {
            font-size: 13px;
            color: #374151;
        }
        .cf-input {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            width: 100%;
            padding: 8px 10px;
            background: #ffffff;
            font-size: 14px;
            line-height: 1.25rem;
            color: #111827;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .cf-input::placeholder {
            color: #9ca3af;
        }
        .cf-input:hover {
            border-color: #9ca3af;
        }
        .cf-input:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }
        .cf-section {
            border: 1px solid #e5e7eb;
        }
        .cf-section-title {
            background: #f3f4f6;
            color: #111827;
            font-weight: 700;
            font-size: 14px;
            padding: 10px 14px;
            letter-spacing: 0.02em;
            border-bottom: 1px solid #e5e7eb;
        }
        .cf-pin {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 24px;
            gap: 4px;
        }
        .cf-pin input {
            width: 24px;
            height: 26px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            text-align: center;
            font-size: 12px;
            outline: none;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .cf-pin input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }
        .cf-date {
            display: grid;
            grid-auto-flow: column;
            grid-auto-columns: 24px;
            gap: 4px;
            align-items: center;
        }
        .cf-date input {
            width: 24px;
            height: 26px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            background: #ffffff;
            text-align: center;
            font-size: 12px;
            outline: none;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }
        .cf-date input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.18);
        }
        .cf-small {
            font-size: 12px;
        }
        .cf-check {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
        }
        .cf-check input {
            width: 14px;
            height: 14px;
            accent-color: #2563eb;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php if (!$isEmbed): ?>
            <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <?php endif; ?>
        <main class="flex-1 overflow-auto relative <?php echo $isEmbed ? 'p-2' : 'p-6'; ?>">
            <div class="mx-auto w-full <?php echo $isEmbed ? 'max-w-none' : 'max-w-[1400px]'; ?>">
                <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                    <div class="p-6 min-h-[80vh]">
                        <?php if ($resetContainer): ?>
                        <?php if (!$isEmbed): ?>
                            <div class="flex items-center justify-between gap-4 mb-6">
                                <div>
                                    <div class="text-lg font-semibold">CF3</div>
                                    <div class="text-xs text-gray-500">Part I - Patient's Clinical Record (Summarized)</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button id="fillInfoBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Fill Info</button>
                                    <button id="cancelClaimBtn" type="button" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">Cancel Claim Registration</button>
                                    <a id="prevCfBtn" href="philhealth-cf2.php<?php echo $isEditMode ? '?mode=edit' : ''; ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Previous (CF2)</a>
                                    <a id="nextCfBtn" href="philhealth-cf4.php<?php echo $isEditMode ? '?mode=edit' : ''; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Next (CF4)</a>
                                    <span id="modeToggleWrap">
                                        <?php if ($isEditMode): ?>
                                            <a href="<?php echo htmlspecialchars($viewUrl); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">View</a>
                                        <?php else: ?>
                                            <a href="<?php echo htmlspecialchars($editUrl); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Edit</a>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            </div>
                        <?php endif; ?>

                        <form id="cf3Form" class="space-y-6">
                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">PART I - PATIENT'S CLINICAL RECORD</div>
                                <div class="p-5 space-y-5">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Institutional PAN</div>
                                            <input class="cf-input mt-2" type="text" name="institution_pan" />
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Chief Complaint / Reason for Admission</div>
                                            <textarea class="cf-input mt-2" name="chief_complaint" rows="3"></textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12">
                                            <div class="cf-label font-semibold">Name of Patient</div>
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="patient_last_name" type="text" placeholder="Last Name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="patient_first_name" type="text" placeholder="First Name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="patient_middle_name" type="text" placeholder="Middle Name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <input class="cf-input" name="patient_name_ext" type="text" placeholder="Name Ext (JR/SR/III)" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Date Admitted</div>
                                            <input class="cf-input mt-2" type="text" name="date_admitted" placeholder="mm/dd/yyyy" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Time Admitted</div>
                                            <input class="cf-input mt-2" type="text" name="time_admitted" placeholder="hh:mm AM/PM" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Date Discharged</div>
                                            <input class="cf-input mt-2" type="text" name="date_discharged" placeholder="mm/dd/yyyy" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Time Discharged</div>
                                            <input class="cf-input mt-2" type="text" name="time_discharged" placeholder="hh:mm AM/PM" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Brief History of Present Illness / OB History</div>
                                            <textarea class="cf-input mt-2" name="history_present_illness" rows="4"></textarea>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Physical Examination (Pertinent Findings)</div>
                                            <textarea class="cf-input mt-2" name="physical_exam" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Course in the Wards</div>
                                            <textarea class="cf-input mt-2" name="course_in_wards" rows="4"></textarea>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Pertinent Laboratory / Diagnostic Findings</div>
                                            <textarea class="cf-input mt-2" name="labs_diagnostics" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6 cf-box p-4">
                                            <div class="cf-label font-semibold">Disposition on Discharge</div>
                                            <select class="cf-input mt-2" name="disposition">
                                                <option value=""></option>
                                                <option value="improved">Improved</option>
                                                <option value="recovered">Recovered</option>
                                                <option value="transferred">Transferred</option>
                                                <option value="hama">HAMA/DAMA</option>
                                                <option value="absconded">Absconded</option>
                                                <option value="expired">Expired</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <input class="cf-input mt-2" type="text" name="disposition_other" placeholder="If other, specify" />
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>

                        <script>
                            (function () {
                                const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
                                const form = document.getElementById('cf3Form');
                                if (!form) return;

                                const isEmbed = <?php echo $isEmbed ? 'true' : 'false'; ?>;
                                if (isEmbed) {
                                    const topActions = document.querySelector('.flex.items-center.justify-between.gap-4.mb-6');
                                    if (topActions) topActions.style.display = 'none';
                                }

                                const ensureAutoNames = () => {
                                    let i = 0;
                                    const els = Array.from(form.querySelectorAll('input, textarea, select'));
                                    els.forEach((el) => {
                                        const tag = (el.tagName || '').toLowerCase();
                                        const type = (el.getAttribute('type') || '').toLowerCase();
                                        if (tag === 'input' && (type === 'button' || type === 'submit' || type === 'reset' || type === 'hidden')) return;
                                        if ((el.getAttribute('name') || '').toString().trim() !== '') return;
                                        el.setAttribute('name', 'cf3_auto_' + (i++));
                                    });
                                };

                                ensureAutoNames();

                                const readJson = (key) => {
                                    try { return JSON.parse(sessionStorage.getItem(key) || 'null'); } catch (e) { return null; }
                                };

                                const writeJson = (key, value) => {
                                    try { sessionStorage.setItem(key, JSON.stringify(value)); } catch (e) { }
                                };

                                const applyDraft = (draft) => {
                                    if (!draft || typeof draft !== 'object') return;
                                    Object.keys(draft).forEach((k) => {
                                        const els = form.querySelectorAll('[name="' + CSS.escape(k) + '"]');
                                        els.forEach((el) => {
                                            const tag = (el.tagName || '').toLowerCase();
                                            const type = (el.getAttribute('type') || '').toLowerCase();
                                            const v = draft[k];
                                            if (tag === 'input' && (type === 'radio' || type === 'checkbox')) {
                                                if (type === 'radio') el.checked = (el.value === String(v));
                                                else el.checked = !!v;
                                            } else {
                                                el.value = (v ?? '').toString();
                                            }
                                        });
                                    });
                                };

                                const collectDraft = () => {
                                    const fd = new FormData(form);
                                    const obj = {};
                                    for (const [k, v] of fd.entries()) obj[k] = v;
                                    form.querySelectorAll('input[type="checkbox"]').forEach((el) => {
                                        if (!el.name) return;
                                        obj[el.name] = el.checked;
                                    });
                                    return obj;
                                };

                                let t = null;
                                const saveDraft = () => writeJson('philhealthCf3Draft', collectDraft());
                                const scheduleSave = () => {
                                    if (t) clearTimeout(t);
                                    t = setTimeout(saveDraft, 150);
                                };

                                applyDraft(readJson('philhealthCf3Draft'));

                                const params = new URLSearchParams(window.location.search);
                                const patientId = params.get('patient_id');
                                const pin = params.get('pin');
                                if (!patientId && !pin) {
                                    try {
                                        const existing = readJson('philhealthCf3Draft');
                                        const pid = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim();
                                        if (!existing && pid) {
                                            (async () => {
                                                const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?patient_id=' + encodeURIComponent(pid), { headers: { 'Accept': 'application/json' } });
                                                const json = await res.json().catch(() => null);
                                                if (!res.ok || !json || !json.ok || !json.forms || !json.forms.cf3 || typeof json.forms.cf3 !== 'object') return;
                                                writeJson('philhealthCf3Draft', json.forms.cf3);
                                                applyDraft(json.forms.cf3);
                                            })().catch(() => { });
                                        }
                                    } catch (e0) {
                                    }
                                }

                                if (patientId || pin) {
                                    (async () => {
                                        const qs = patientId ? ('patient_id=' + encodeURIComponent(patientId)) : ('pin=' + encodeURIComponent(pin));
                                        const res = await fetch(API_BASE_URL + '/philhealth/member_cf3.php?' + qs, { headers: { 'Accept': 'application/json' } });
                                        const json = await res.json().catch(() => null);
                                        if (!res.ok || !json || !json.ok) return;
                                        if (json.cf3 && typeof json.cf3 === 'object') {
                                            writeJson('philhealthCf3Draft', json.cf3);
                                            applyDraft(json.cf3);
                                        }
                                    })().catch(() => { });
                                }

                                if (!isEditMode) {
                                    form.querySelectorAll('input, textarea').forEach((el) => {
                                        const type = (el.getAttribute('type') || '').toLowerCase();
                                        if (type === 'checkbox' || type === 'radio') el.disabled = true;
                                        else el.readOnly = true;
                                        el.tabIndex = -1;
                                    });
                                    form.querySelectorAll('select').forEach((el) => {
                                        el.disabled = true;
                                        el.tabIndex = -1;
                                    });
                                } else {
                                    form.addEventListener('input', scheduleSave);
                                    form.addEventListener('change', scheduleSave);

                                    const fillBtn = document.getElementById('fillInfoBtn');
                                    if (fillBtn) {
                                        const getAiSeed = () => {
                                            let seed = '';
                                            try {
                                                seed = (sessionStorage.getItem('philhealthAiSeed') || '').toString();
                                            } catch (e) {
                                                seed = '';
                                            }
                                            if (seed.trim() !== '') return seed.trim();
                                            seed = Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);
                                            try {
                                                sessionStorage.setItem('philhealthAiSeed', seed);
                                            } catch (e) {
                                            }
                                            return seed;
                                        };

                                        function ensureAiFillOverlay() {
                                            let style = document.getElementById('aiFillOverlayStyle');
                                            if (!style) {
                                                style = document.createElement('style');
                                                style.id = 'aiFillOverlayStyle';
                                                style.textContent = '@keyframes aiFillSpin{to{transform:rotate(360deg)}}.ai-fill-spin{animation:aiFillSpin 1s linear infinite}';
                                                document.head.appendChild(style);
                                            }
                                            let overlay = document.getElementById('aiFillOverlay');
                                            if (!overlay) {
                                                overlay = document.createElement('div');
                                                overlay.id = 'aiFillOverlay';
                                                overlay.className = 'hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center';
                                                overlay.innerHTML = `
                                                    <div class="bg-white rounded-xl shadow-xl px-6 py-5 w-[92%] max-w-sm">
                                                        <div class="flex items-center gap-4">
                                                            <div class="w-10 h-10 rounded-full border-4 border-gray-200 border-t-blue-600 ai-fill-spin"></div>
                                                            <div>
                                                                <div class="text-sm font-semibold text-gray-900" id="aiFillOverlayTitle">Filling with AI…</div>
                                                                <div class="text-xs text-gray-500" id="aiFillOverlaySubtitle">Please wait</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                `;
                                                document.body.appendChild(overlay);
                                            }
                                            return overlay;
                                        }

                                        function showAiFillOverlay(title, subtitle) {
                                            const overlay = ensureAiFillOverlay();
                                            const t = overlay.querySelector('#aiFillOverlayTitle');
                                            const s = overlay.querySelector('#aiFillOverlaySubtitle');
                                            if (t) t.textContent = title || 'Filling with AI…';
                                            if (s) s.textContent = subtitle || 'Please wait';
                                            overlay.classList.remove('hidden');
                                        }

                                        function hideAiFillOverlay() {
                                            const overlay = document.getElementById('aiFillOverlay');
                                            if (overlay) overlay.classList.add('hidden');
                                        }

                                        const collect = () => {
                                            const targets = [];
                                            const fields = [];

                                            const digitContainers = Array.from(form.querySelectorAll('.cf-pin, .cf-date'));
                                            digitContainers.forEach(container => {
                                                const inputs = Array.from(container.querySelectorAll('input'));
                                                if (!inputs.length) return;
                                                const aria = container.getAttribute('aria-label') || '';
                                                targets.push({ kind: 'digits', container, inputs });
                                                fields.push({ kind: 'digits', label: aria || 'digits', length: inputs.length });
                                            });

                                            const skip = (el) => !!el.closest('.cf-pin, .cf-date');

                                            const radiosByName = new Map();
                                            Array.from(form.querySelectorAll('input[type="radio"]')).forEach(r => {
                                                if (!r.name) return;
                                                if (skip(r)) return;
                                                if (!radiosByName.has(r.name)) radiosByName.set(r.name, []);
                                                radiosByName.get(r.name).push(r);
                                            });

                                            Array.from(form.querySelectorAll('input, textarea, select')).forEach(el => {
                                                const tag = (el.tagName || '').toLowerCase();
                                                const type = (el.getAttribute('type') || '').toLowerCase();
                                                if (skip(el)) return;
                                                if (tag === 'input' && type === 'radio') return;
                                                if (type === 'file') return;
                                                if (el.disabled) return;

                                                if (tag === 'select') {
                                                    const options = Array.from(el.options).map(o => ({ value: o.value, label: o.textContent || '' })).filter(o => o.value !== '');
                                                    targets.push({ kind: 'select', el, options });
                                                    fields.push({ kind: 'select', name: el.name || '', required: !!el.required, options });
                                                    return;
                                                }

                                                if (tag === 'input' && type === 'checkbox') {
                                                    targets.push({ kind: 'checkbox', el });
                                                    fields.push({ kind: 'checkbox', name: el.name || '', required: !!el.required });
                                                    return;
                                                }

                                                targets.push({ kind: 'input', el, type: type || 'text' });
                                                fields.push({ kind: 'input', name: el.name || '', type: type || 'text', required: !!el.required, placeholder: el.getAttribute('placeholder') || '' });
                                            });

                                            Array.from(radiosByName.entries()).forEach(([name, radios]) => {
                                                const options = radios.map(r => ({ value: r.value || '', label: (r.parentElement && r.parentElement.textContent) ? r.parentElement.textContent.trim() : '' })).filter(o => o.value !== '');
                                                if (!options.length) return;
                                                targets.push({ kind: 'radio', name, radios, options });
                                                fields.push({ kind: 'radio', name, required: radios.some(r => r.required), options });
                                            });

                                            return { targets, fields };
                                        };

                                        fillBtn.addEventListener('click', async () => {
                                            if (!isEditMode) {
                                                alert('Switch to Edit mode to fill information.');
                                                return;
                                            }
                                            fillBtn.disabled = true;
                                            showAiFillOverlay('Filling with AI…', 'Preparing CF3 information');
                                            try {
                                                const seed = getAiSeed();
                                                const { targets, fields } = collect();
                                                const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                                                    method: 'POST',
                                                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                                    body: JSON.stringify({ page: 'cf3', seed, fields }),
                                                });
                                                const json = await res.json().catch(() => null);
                                                if (!res.ok || !json || !json.ok) {
                                                    throw new Error((json && json.error) ? json.error : 'AI fill failed');
                                                }
                                                const values = Array.isArray(json.values) ? json.values : [];
                                                values.forEach(item => {
                                                    if (!item || typeof item !== 'object') return;
                                                    const idx = Number(item.index);
                                                    if (!Number.isFinite(idx)) return;
                                                    const target = targets[idx];
                                                    if (!target) return;

                                                    if (target.kind === 'digits') {
                                                        const s = (item.value ?? '').toString().replace(/\D/g, '');
                                                        target.inputs.forEach((el, i) => {
                                                            el.value = s[i] || '';
                                                            el.dispatchEvent(new Event('input', { bubbles: true }));
                                                            el.dispatchEvent(new Event('change', { bubbles: true }));
                                                        });
                                                        return;
                                                    }

                                                    if (target.kind === 'select') {
                                                        const v = item.value;
                                                        const has = target.options.some(o => o.value === v);
                                                        if (has) target.el.value = v;
                                                        else if (target.options[0]) target.el.value = target.options[0].value;
                                                        target.el.dispatchEvent(new Event('input', { bubbles: true }));
                                                        target.el.dispatchEvent(new Event('change', { bubbles: true }));
                                                        return;
                                                    }

                                                    if (target.kind === 'checkbox') {
                                                        target.el.checked = !!(item.checked ?? item.value);
                                                        target.el.dispatchEvent(new Event('change', { bubbles: true }));
                                                        return;
                                                    }

                                                    if (target.kind === 'radio') {
                                                        const v = item.value;
                                                        const selected = target.radios.find(r => r.value === v) || target.radios[0];
                                                        if (selected) {
                                                            selected.checked = true;
                                                            selected.dispatchEvent(new Event('change', { bubbles: true }));
                                                        }
                                                        return;
                                                    }

                                                    if (target.kind === 'input') {
                                                        target.el.value = (item.value ?? '').toString();
                                                        target.el.dispatchEvent(new Event('input', { bubbles: true }));
                                                        target.el.dispatchEvent(new Event('change', { bubbles: true }));
                                                    }
                                                });

                                                saveDraft();
                                            } catch (e) {
                                                alert(e && e.message ? e.message : 'AI fill failed');
                                            } finally {
                                                fillBtn.disabled = false;
                                                hideAiFillOverlay();
                                            }
                                        });
                                    }

                                    const validateAll = () => {
                                        let ok = true;
                                        const els = Array.from(form.querySelectorAll('input, textarea, select'));
                                        els.forEach(el => {
                                            const tag = (el.tagName || '').toLowerCase();
                                            const type = (el.getAttribute('type') || '').toLowerCase();
                                            if (el.disabled) return;
                                            if (type === 'file' || type === 'hidden' || type === 'button' || type === 'submit' || type === 'reset') return;
                                            if (type === 'checkbox' || type === 'radio') return;
                                            if (tag === 'select') {
                                                const v = (el.value || '').toString().trim();
                                                if (v === '') {
                                                    ok = false;
                                                    el.style.borderColor = '#ef4444';
                                                } else {
                                                    el.style.borderColor = '';
                                                }
                                                return;
                                            }
                                            const v = (el.value || '').toString().trim();
                                            if (v === '') {
                                                ok = false;
                                                el.style.borderColor = '#ef4444';
                                            } else {
                                                el.style.borderColor = '';
                                            }
                                        });
                                        return ok;
                                    };

                                    const nextLink = document.getElementById('nextCfBtn');
                                    const prevLink = document.getElementById('prevCfBtn');
                                    if (nextLink) {
                                        nextLink.addEventListener('click', (e) => {
                                            e.preventDefault();
                                            saveDraft();
                                            if (!validateAll()) {
                                                alert('Please complete all fields in CF3 before proceeding to CF4.');
                                                return;
                                            }
                                            try {
                                                sessionStorage.setItem('philhealthStepCf3Complete', '1');
                                            } catch (e2) {
                                            }
                                            window.location.href = nextLink.getAttribute('href') || 'philhealth-cf4.php?mode=edit';
                                        });
                                    }
                                    if (prevLink) {
                                        prevLink.addEventListener('click', () => saveDraft());
                                    }

                                    saveDraft();
                                }
                            })();
                        </script>

                        <script>
                            (function () {
                                function safeGet(key) {
                                    try { return sessionStorage.getItem(key); } catch (e) { return null; }
                                }

                                if (safeGet('philhealthNewClaimActive') !== '1') return;

                                const p = new URLSearchParams(window.location.search);
                                if (p.get('mode') !== 'edit') {
                                    p.set('mode', 'edit');
                                    window.location.href = window.location.pathname + '?' + p.toString();
                                    return;
                                }

                                const wrap = document.getElementById('modeToggleWrap');
                                if (wrap) wrap.style.display = 'none';

                                if (safeGet('philhealthStepCf1Complete') !== '1' || safeGet('philhealthStepCf2Complete') !== '1') {
                                    window.location.href = 'philhealth-cf2.php?mode=edit';
                                    return;
                                }

                                const cancelBtn = document.getElementById('cancelClaimBtn');
                                if (cancelBtn) {
                                    cancelBtn.addEventListener('click', () => {
                                        if (!confirm('Cancel claim registration? This will discard the current claim registration.')) return;

                                        fetch(API_BASE_URL + '/philhealth/claim_session.php', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                            body: JSON.stringify({ action: 'cancel' }),
                                        }).catch(() => { });
                                        try {
                                            sessionStorage.removeItem('philhealthPatientId');
                                            sessionStorage.removeItem('philhealthCf1Draft');
                                            sessionStorage.removeItem('philhealthCf2Draft');
                                            sessionStorage.removeItem('philhealthCf3Draft');
                                            sessionStorage.removeItem('philhealthCf4Draft');
                                            sessionStorage.removeItem('philhealthAiSeed');
                                            sessionStorage.removeItem('philhealthNewClaimActive');
                                            sessionStorage.removeItem('philhealthStepCf1Complete');
                                            sessionStorage.removeItem('philhealthStepCf2Complete');
                                            sessionStorage.removeItem('philhealthStepCf3Complete');
                                            sessionStorage.removeItem('philhealthStepCf4Complete');
                                        } catch (e) {
                                        }
                                        window.location.href = 'philhealth-claims.php';
                                    });
                                }
                            })();
                        </script>

                        <?php else: ?>
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button id="fillInfoBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Fill Info</button>
                                    <button id="cancelClaimBtn" type="button" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">Cancel Claim Registration</button>
                                    <a id="prevCfBtn" href="philhealth-cf2.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Previous (CF2)</a>
                                    <a id="nextCfBtn" href="philhealth-cf4.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Next (CF4)</a>
                                </div>
                                <div id="modeToggleWrap">
                                    <?php if ($isEditMode): ?>
                                        <a href="<?php echo htmlspecialchars($viewUrl); ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">View</a>
                                    <?php else: ?>
                                        <a href="<?php echo htmlspecialchars($editUrl); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Edit</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="grid grid-cols-12 gap-4 items-start">
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-lg bg-gray-100 flex items-center justify-center cf-box">
                                            <span class="text-xs font-semibold">Logo</span>
                                        </div>
                                        <div>
                                            <div class="text-base font-semibold">PhilHealth</div>
                                            <div class="text-xs text-gray-600">Your Partner in Health</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex justify-end">
                                        <div class="text-right">
                                            <div class="text-xs">This form may be reproduced and is NOT FOR SALE</div>
                                            <div class="mt-2 inline-block cf-box px-3 py-2">
                                                <div class="text-2xl font-black leading-none">CF3</div>
                                                <div class="text-xs font-semibold">(Claim Form)</div>
                                                <div class="text-[10px] text-gray-600">Revised November 2013</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="space-y-6">
                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">PART I - PATIENT'S CLINICAL RECORD</div>
                                    <div class="p-5 space-y-6">
                                        <div class="grid grid-cols-12 gap-4 items-start">
                                            <div class="col-span-12 lg:col-span-7">
                                                <div class="cf-label font-semibold">1. PhilHealth Accreditation No. (PAN) - Institutional Health Care Provider</div>
                                                <div class="mt-2 cf-pin" aria-label="Institutional PAN">
                                                    <?php for ($i = 0; $i < 12; $i++): ?>
                                                        <input type="text" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-5">
                                                <div class="cf-label font-semibold">3. Chief Complaint / Reason for Admission</div>
                                                <textarea class="cf-input mt-2" rows="4"></textarea>
                                            </div>
                                        </div>

                                        <div>
                                            <div class="cf-label font-semibold">2. Name of Patient</div>
                                            <div class="mt-2 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" name="patient_last_name" type="text" />
                                                    <div class="cf-small text-center mt-1">Last Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" name="patient_first_name" type="text" />
                                                    <div class="cf-small text-center mt-1">First Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" name="patient_middle_name" type="text" />
                                                    <div class="cf-small text-center mt-1">Middle Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" name="patient_name_ext" type="text" />
                                                    <div class="cf-small text-center mt-1">Name Extension</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">4. Date Admitted</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Admitted month">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Admitted day">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Admitted year">
                                                            <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Year</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 cf-label font-semibold">Time Admitted</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="hh" />
                                                        <span class="text-gray-500">:</span>
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="mm" />
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <label class="cf-check"><input type="radio" name="time_admit_ampm" value="AM" /> AM</label>
                                                        <label class="cf-check"><input type="radio" name="time_admit_ampm" value="PM" /> PM</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">5. Date Discharged</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharged month">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharged day">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharged year">
                                                            <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Year</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 cf-label font-semibold">Time Discharged</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="hh" />
                                                        <span class="text-gray-500">:</span>
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="mm" />
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <label class="cf-check"><input type="radio" name="time_discharge_ampm" value="AM" /> AM</label>
                                                        <label class="cf-check"><input type="radio" name="time_discharge_ampm" value="PM" /> PM</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">6. Brief History of Present Illness / OB History</div>
                                            <textarea class="cf-input mt-2" rows="4"></textarea>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">7. Physical Examination (Pertinent Findings per System)</div>
                                            <div class="mt-4 space-y-4">
                                                <div>
                                                    <div class="cf-label font-semibold">General Survey</div>
                                                    <textarea class="cf-input mt-2" rows="2"></textarea>
                                                </div>

                                                <div>
                                                    <div class="cf-label font-semibold">Vital Signs</div>
                                                    <div class="mt-2 grid grid-cols-12 gap-3">
                                                        <div class="col-span-12 sm:col-span-3">
                                                            <input class="cf-input" type="text" placeholder="BP" />
                                                        </div>
                                                        <div class="col-span-12 sm:col-span-3">
                                                            <input class="cf-input" type="text" placeholder="CR" />
                                                        </div>
                                                        <div class="col-span-12 sm:col-span-3">
                                                            <input class="cf-input" type="text" placeholder="RR" />
                                                        </div>
                                                        <div class="col-span-12 sm:col-span-3">
                                                            <input class="cf-input" type="text" placeholder="Temperature" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">HEENT</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">Abdomen</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">Chest/Lungs</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">GU (IE)</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">CVS</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">Skin/Extremities</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                    <div class="col-span-12">
                                                        <div class="cf-label font-semibold">Neuro Examination</div>
                                                        <textarea class="cf-input mt-2" rows="2"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">8. Course in the Wards <span class="font-normal">(attach additional sheets if necessary)</span></div>
                                            <textarea class="cf-input mt-2" rows="5"></textarea>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">9. Pertinent Laboratory and Diagnostic Findings <span class="font-normal">(CBC, Urinalysis, Fecalysis, X-ray, Biopsy, etc.)</span></div>
                                            <textarea class="cf-input mt-2" rows="5"></textarea>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">10. Disposition on Discharge</div>
                                            <div class="mt-3 flex flex-wrap gap-6">
                                                <label class="cf-check"><input type="checkbox" /> Improved</label>
                                                <label class="cf-check"><input type="checkbox" /> Transferred</label>
                                                <label class="cf-check"><input type="checkbox" /> HAMA</label>
                                                <label class="cf-check"><input type="checkbox" /> Absconded</label>
                                                <label class="cf-check"><input type="checkbox" /> Expired</label>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">PART II - MATERNITY CARE PACKAGE</div>
                                    <div class="p-5 space-y-6">
                                        <div class="text-sm font-semibold text-red-600">PRENATAL CONSULTATION</div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">1. Initial Prenatal Consultation</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Initial consult month">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Initial consult day">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Initial consult year">
                                                            <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">Year</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">2. Clinical History and Physical Examination</div>
                                                <div class="mt-3 space-y-3">
                                                    <div class="flex flex-wrap items-center gap-6">
                                                        <div class="cf-label font-semibold">a. Vital signs are normal</div>
                                                        <label class="cf-check"><input type="checkbox" /> </label>
                                                    </div>
                                                    <div class="grid grid-cols-12 gap-3">
                                                        <div class="col-span-12 md:col-span-6">
                                                            <div class="cf-label font-semibold">c. Menstrual History (LMP)</div>
                                                            <div class="mt-2 grid grid-cols-3 gap-2 max-w-sm">
                                                                <div class="cf-date" aria-label="LMP month">
                                                                    <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                </div>
                                                                <div class="cf-date" aria-label="LMP day">
                                                                    <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                </div>
                                                                <div class="cf-date" aria-label="LMP year">
                                                                    <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                </div>
                                                            </div>
                                                            <div class="cf-small mt-1 text-gray-600">Month / Day / Year</div>
                                                        </div>
                                                        <div class="col-span-12 md:col-span-6">
                                                            <div class="cf-label font-semibold">Age of Menarche</div>
                                                            <input class="cf-input mt-2" type="text" />
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-12 gap-3">
                                                        <div class="col-span-12 md:col-span-6">
                                                            <div class="cf-label font-semibold">d. Obstetric History</div>
                                                            <div class="mt-2 grid grid-cols-12 gap-2 items-center">
                                                                <div class="col-span-2"><span class="text-sm font-semibold">G</span></div>
                                                                <div class="col-span-4"><input class="cf-input" type="text" /></div>
                                                                <div class="col-span-2"><span class="text-sm font-semibold">P</span></div>
                                                                <div class="col-span-4"><input class="cf-input" type="text" /></div>
                                                            </div>
                                                            <div class="mt-2 grid grid-cols-4 gap-2">
                                                                <input class="cf-input" type="text" placeholder="T" />
                                                                <input class="cf-input" type="text" placeholder="P" />
                                                                <input class="cf-input" type="text" placeholder="A" />
                                                                <input class="cf-input" type="text" placeholder="L" />
                                                            </div>
                                                        </div>
                                                        <div class="col-span-12 md:col-span-6">
                                                            <div class="cf-label font-semibold">b. Ascertain the present Pregnancy is low-risk</div>
                                                            <div class="mt-2 flex gap-6">
                                                                <label class="cf-check"><input type="radio" name="low_risk" value="yes" /> Yes</label>
                                                                <label class="cf-check"><input type="radio" name="low_risk" value="no" /> No</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">3. Obstetric risk factors</div>
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                                <label class="cf-check"><input type="checkbox" /> a. Multiple pregnancy</label>
                                                <label class="cf-check"><input type="checkbox" /> b. Ovarian cyst</label>
                                                <label class="cf-check"><input type="checkbox" /> c. Myoma uteri</label>
                                                <label class="cf-check"><input type="checkbox" /> d. Placenta previa</label>
                                                <label class="cf-check"><input type="checkbox" /> e. History of 3 miscarriages</label>
                                                <label class="cf-check"><input type="checkbox" /> f. History of stillbirth</label>
                                                <label class="cf-check"><input type="checkbox" /> g. History of pre-eclampsia</label>
                                                <label class="cf-check"><input type="checkbox" /> h. History of eclampsia</label>
                                                <label class="cf-check"><input type="checkbox" /> i. Premature contraction</label>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">4. Medical/Surgical risk factors</div>
                                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2">
                                                <label class="cf-check"><input type="checkbox" /> a. Hypertension</label>
                                                <label class="cf-check"><input type="checkbox" /> b. Heart Disease</label>
                                                <label class="cf-check"><input type="checkbox" /> c. Diabetes</label>
                                                <label class="cf-check"><input type="checkbox" /> d. Thyroid Disorder</label>
                                                <label class="cf-check"><input type="checkbox" /> e. Obesity</label>
                                                <label class="cf-check"><input type="checkbox" /> f. Moderate to severe asthma</label>
                                                <label class="cf-check"><input type="checkbox" /> g. Epilepsy</label>
                                                <label class="cf-check"><input type="checkbox" /> h. Renal disease</label>
                                                <label class="cf-check"><input type="checkbox" /> i. Bleeding disorders</label>
                                                <label class="cf-check"><input type="checkbox" /> j. History of previous cesarean section</label>
                                                <label class="cf-check"><input type="checkbox" /> k. History of uterine myomectomy</label>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">5. Admitting Diagnosis</div>
                                                <textarea class="cf-input mt-2" rows="3"></textarea>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">6. Delivery Plan</div>
                                                <textarea class="cf-input mt-2" rows="3"></textarea>

                                                <div class="mt-4 grid grid-cols-12 gap-3 items-end">
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">a. Orientation to MCP/Availment of Benefits</div>
                                                        <div class="mt-2 flex gap-6">
                                                            <label class="cf-check"><input type="radio" name="orientation" value="yes" /> Yes</label>
                                                            <label class="cf-check"><input type="radio" name="orientation" value="no" /> No</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">b. Expected date of delivery</div>
                                                        <div class="mt-2 grid grid-cols-3 gap-2 max-w-sm">
                                                            <div class="cf-date" aria-label="EDD month">
                                                                <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                            </div>
                                                            <div class="cf-date" aria-label="EDD day">
                                                                <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                            </div>
                                                            <div class="cf-date" aria-label="EDD year">
                                                                <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                            </div>
                                                        </div>
                                                        <div class="cf-small mt-1 text-gray-600">Month / Day / Year</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <section class="cf-box p-4">
                                            <div class="cf-label font-semibold">7. Follow-up Prenatal Consultation</div>
                                            <div class="mt-3 overflow-x-auto">
                                                <div class="min-w-[1200px]">
                                                    <?php
                                                    $visits = ['2nd','3rd','4th','5th','6th','7th','8th','9th','10th','11th','12th'];
                                                    $rows = [
                                                        'a. Prenatal Consultation No.' => 'text',
                                                        'b. Date of visit (mm/dd/yyyy)' => 'text',
                                                        'c. AOG in weeks' => 'text',
                                                        'd. Weight' => 'text',
                                                        'd.1 Blood Pressure' => 'text',
                                                        'd.2 Cardiac Rate' => 'text',
                                                        'd.3 Respiratory Rate' => 'text',
                                                        'd.4 Body Temperature' => 'text',
                                                    ];
                                                    ?>
                                                    <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-gray-600 mb-2">
                                                        <div class="col-span-2">&nbsp;</div>
                                                        <?php foreach ($visits as $v): ?>
                                                            <div class="col-span-1 text-center"><?php echo htmlspecialchars($v, ENT_QUOTES); ?></div>
                                                        <?php endforeach; ?>
                                                    </div>

                                                    <?php foreach ($rows as $label => $type): ?>
                                                        <div class="grid grid-cols-12 gap-2 mb-2 items-center">
                                                            <div class="col-span-2 text-xs text-gray-700 font-semibold"><?php echo htmlspecialchars($label, ENT_QUOTES); ?></div>
                                                            <?php foreach ($visits as $v): ?>
                                                                <div class="col-span-1">
                                                                    <input class="cf-input" type="<?php echo htmlspecialchars($type, ENT_QUOTES); ?>" />
                                                                </div>
                                                            <?php endforeach; ?>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </section>

                                        <div class="text-sm font-semibold text-red-600">DELIVERY OUTCOME</div>

                                        <div class="cf-box p-4 space-y-4">
                                            <div>
                                                <div class="cf-label font-semibold">8. Date and Time of Delivery</div>
                                                <div class="mt-2 grid grid-cols-12 gap-4 items-end">
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">Date</div>
                                                        <div class="mt-2 grid grid-cols-3 gap-2 max-w-sm">
                                                            <div class="cf-date" aria-label="Delivery month">
                                                                <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                            </div>
                                                            <div class="cf-date" aria-label="Delivery day">
                                                                <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                            </div>
                                                            <div class="cf-date" aria-label="Delivery year">
                                                                <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">Time</div>
                                                        <div class="mt-2 flex flex-wrap items-center gap-3">
                                                            <div class="flex items-center gap-2">
                                                                <input class="cf-input" style="max-width: 90px;" type="text" placeholder="hh" />
                                                                <span class="text-gray-500">:</span>
                                                                <input class="cf-input" style="max-width: 90px;" type="text" placeholder="mm" />
                                                            </div>
                                                            <div class="flex items-center gap-4">
                                                                <label class="cf-check"><input type="radio" name="delivery_ampm" value="AM" /> AM</label>
                                                                <label class="cf-check"><input type="radio" name="delivery_ampm" value="PM" /> PM</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12">
                                                    <div class="cf-label font-semibold">9. Maternal Outcome</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Pregnancy Uterine</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Obstetric Index</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">AOG by LMP</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Manner of Delivery</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Presentation</div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12">
                                                    <div class="cf-label font-semibold">10. Birth Outcome</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-5">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Fetal Outcome</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Sex</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Birth Weight (grams)</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">APGAR Score</div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-label font-semibold">11. Scheduled Postpartum follow-up consultation 1 week after delivery</div>
                                                    <input class="cf-input mt-2" type="text" placeholder="mm/dd/yyyy" />
                                                </div>
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-label font-semibold">12. Date and Time of Discharge</div>
                                                    <div class="mt-2 space-y-4">
                                                        <div>
                                                            <div class="cf-label font-semibold">Date</div>
                                                            <div class="mt-2 grid grid-cols-3 gap-2 max-w-sm">
                                                                <div>
                                                                    <div class="cf-small mb-1 text-center text-gray-600">Month</div>
                                                                    <div class="cf-date" aria-label="Discharge2 month">
                                                                        <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="cf-small mb-1 text-center text-gray-600">Day</div>
                                                                    <div class="cf-date" aria-label="Discharge2 day">
                                                                        <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <div class="cf-small mb-1 text-center text-gray-600">Year</div>
                                                                    <div class="cf-date" aria-label="Discharge2 year">
                                                                        <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div>
                                                            <div class="cf-label font-semibold">Time</div>
                                                            <div class="mt-2 flex flex-wrap items-center gap-3">
                                                                <div class="flex items-center gap-2 flex-nowrap">
                                                                    <input class="cf-input" style="max-width: 90px;" type="text" placeholder="hh" />
                                                                    <span class="text-gray-500">:</span>
                                                                    <input class="cf-input" style="max-width: 90px;" type="text" placeholder="mm" />
                                                                </div>
                                                                <div class="flex items-center gap-4 whitespace-nowrap">
                                                                    <label class="cf-check"><input type="radio" name="discharge2_ampm" value="AM" /> AM</label>
                                                                    <label class="cf-check"><input type="radio" name="discharge2_ampm" value="PM" /> PM</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="text-sm font-semibold text-red-600">POSTPARTUM CARE</div>

                                        <div class="cf-box p-4">
                                            <div class="grid grid-cols-12 gap-4">
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-label font-semibold">done</div>
                                                    <div class="mt-3 space-y-3">
                                                        <label class="cf-check"><input type="checkbox" /> 13. Perineal wound care</label>
                                                        <label class="cf-check"><input type="checkbox" /> 14. Signs of Maternal Postpartum Complications</label>
                                                        <label class="cf-check"><input type="checkbox" /> 15. Counseling and Education - Breastfeeding and Nutrition</label>
                                                        <label class="cf-check"><input type="checkbox" /> 15. Counseling and Education - Family Planning</label>
                                                        <label class="cf-check"><input type="checkbox" /> 16. Provided family planning service to patient</label>
                                                        <label class="cf-check"><input type="checkbox" /> 17. Referred to partner physician for VSS</label>
                                                        <label class="cf-check"><input type="checkbox" /> 18. Schedule the next postpartum follow-up</label>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-label font-semibold">Remarks</div>
                                                    <div class="mt-2 space-y-3">
                                                        <textarea class="cf-input" rows="2"></textarea>
                                                        <textarea class="cf-input" rows="2"></textarea>
                                                        <textarea class="cf-input" rows="2"></textarea>
                                                        <textarea class="cf-input" rows="2"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">19. Certification of Attending Physician/Midwife</div>
                                            <div class="cf-small mt-2 text-gray-700">I certify that the above information given in this form are true and correct.</div>
                                            <div class="mt-5 grid grid-cols-12 gap-4 items-end">
                                                <div class="col-span-12 md:col-span-8">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Signature Over Printed Name of Attending Physician/Midwife</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <div class="cf-label font-semibold">Date Signed</div>
                                                    <div class="mt-2 grid grid-cols-3 gap-2">
                                                        <div class="cf-date" aria-label="CF3 signed month">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-date" aria-label="CF3 signed day">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-date" aria-label="CF3 signed year">
                                                            <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>
                            </form>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php if (!$resetContainer): ?>
    <script>
        (function () {
            function safeGet(key) {
                try { return sessionStorage.getItem(key); } catch (e) { return null; }
            }
            if (safeGet('philhealthNewClaimActive') !== '1') return;

            const p = new URLSearchParams(window.location.search);
            if (p.get('mode') !== 'edit') {
                p.set('mode', 'edit');
                window.location.href = window.location.pathname + '?' + p.toString();
                return;
            }
            const wrap = document.getElementById('modeToggleWrap');
            if (wrap) wrap.style.display = 'none';

            if (safeGet('philhealthStepCf1Complete') !== '1' || safeGet('philhealthStepCf2Complete') !== '1') {
                window.location.href = 'philhealth-cf2.php?mode=edit';
            }
        })();

        (function () {
            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            if (isEditMode) return;

            document.querySelectorAll('form input, form textarea').forEach(el => {
                const type = (el.getAttribute('type') || '').toLowerCase();
                if (type === 'checkbox' || type === 'radio' || type === 'file') {
                    el.disabled = true;
                } else {
                    el.readOnly = true;
                }
                el.tabIndex = -1;
            });

            document.querySelectorAll('form select').forEach(el => {
                el.disabled = true;
                el.tabIndex = -1;
            });
        })();

        (function () {
            let draft = null;
            try {
                draft = JSON.parse(sessionStorage.getItem('philhealthCf1Draft') || 'null');
            } catch (e) {
                draft = null;
            }
            if (!draft) return;

            const fillText = (el, value) => {
                if (!el) return;
                if ((el.value || '').trim() !== '') return;
                el.value = (value || '').toString();
            };

            const parseName = (raw) => {
                const s = (raw || '').toString();
                const parts = s.split(',').map(x => x.trim()).filter(Boolean);
                const last = parts[0] || '';
                const first = parts[1] || '';
                const middle = parts.slice(2).join(' ') || '';
                return { last, first, middle };
            };

            const name = {
                last: (draft.patient_last_name || '').toString(),
                first: (draft.patient_first_name || '').toString(),
                middle: (draft.patient_middle_name || '').toString(),
            };

            const labels = Array.from(document.querySelectorAll('.cf-label'));
            const label = labels.find(el => (el.textContent || '').trim().toLowerCase() === '2. name of patient');
            if (!label) return;

            let container = label.nextElementSibling;
            while (container && (!container.querySelectorAll || container.querySelectorAll('input.cf-input').length === 0)) {
                container = container.nextElementSibling;
            }
            if (!container) return;

            const inputs = Array.from(container.querySelectorAll('input.cf-input'));
            if (inputs.length >= 4) {
                fillText(inputs[0], name.last);
                fillText(inputs[1], name.first);
                fillText(inputs[2], name.middle);
            }
        })();

        (function () {
            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            if (!isEditMode) return;

            const form = document.querySelector('form');
            if (!form) return;

            let autoIdx = 0;
            Array.from(form.querySelectorAll('input, textarea, select')).forEach((el) => {
                const tag = (el.tagName || '').toLowerCase();
                const type = (el.getAttribute('type') || '').toLowerCase();
                if (tag === 'input' && (type === 'button' || type === 'submit' || type === 'reset' || type === 'hidden')) return;
                if ((el.getAttribute('name') || '').toString().trim() !== '') return;
                el.setAttribute('name', 'cf3_auto_' + (autoIdx++));
            });

            let t = null;
            const save = () => {
                const fd = new FormData(form);
                const obj = {};
                for (const [k, v] of fd.entries()) {
                    if (obj[k] !== undefined) {
                        if (Array.isArray(obj[k])) obj[k].push(v);
                        else obj[k] = [obj[k], v];
                    } else {
                        obj[k] = v;
                    }
                }
                try {
                    sessionStorage.setItem('philhealthCf3Draft', JSON.stringify(obj));
                } catch (e) {
                }
            };

            const readJson = (key) => {
                try {
                    const raw = sessionStorage.getItem(key);
                    if (!raw) return null;
                    const v = JSON.parse(raw);
                    return (v && typeof v === 'object') ? v : null;
                } catch (e) {
                    return null;
                }
            };

            if (isEmbed) {
                window.addEventListener('message', function (event) {
                    const data = event && event.data;
                    if (!data || data.type !== 'PHILHEALTH_MEMBER_FORMS_SAVE') return;
                    save();
                });
            }

            const scheduleSave = () => {
                if (t) clearTimeout(t);
                t = setTimeout(save, 150);
            };

            form.addEventListener('input', scheduleSave);
            form.addEventListener('change', scheduleSave);

            const nextLink = document.getElementById('nextCfBtn');
            const prevLink = document.getElementById('prevCfBtn');
            if (nextLink) {
                const validateAll = () => {
                    let ok = true;
                    const els = Array.from(form.querySelectorAll('input, textarea, select'));
                    els.forEach(el => {
                        const tag = (el.tagName || '').toLowerCase();
                        const type = (el.getAttribute('type') || '').toLowerCase();
                        if (el.disabled) return;
                        if (type === 'file' || type === 'hidden' || type === 'button' || type === 'submit' || type === 'reset') return;
                        if (type === 'checkbox' || type === 'radio') return;
                        if (tag === 'select') {
                            const v = (el.value || '').toString().trim();
                            if (v === '') {
                                ok = false;
                                el.style.borderColor = '#ef4444';
                            } else {
                                el.style.borderColor = '';
                            }
                            return;
                        }
                        const v = (el.value || '').toString().trim();
                        if (v === '') {
                            ok = false;
                            el.style.borderColor = '#ef4444';
                        } else {
                            el.style.borderColor = '';
                        }
                    });
                    return ok;
                };

                nextLink.addEventListener('click', (e) => {
                    e.preventDefault();
                    save();
                    if (!validateAll()) {
                        alert('Please complete all fields in CF3 before proceeding to CF4.');
                        return;
                    }
                    try {
                        sessionStorage.setItem('philhealthStepCf3Complete', '1');
                    } catch (e2) {
                    }
                    window.location.href = 'philhealth-cf4.php?mode=edit';
                });
            }
            if (prevLink) prevLink.addEventListener('click', () => save());

            save();
        })();

        (function () {
            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            const btn = document.getElementById('fillInfoBtn');
            if (!btn) return;

            const cancelBtn = document.getElementById('cancelClaimBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', () => {
                    if (!confirm('Cancel claim registration? This will discard the current claim registration.')) return;

                    fetch(API_BASE_URL + '/philhealth/claim_session.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ action: 'cancel' }),
                    }).catch(() => { });
                    try {
                        sessionStorage.removeItem('philhealthPatientId');
                        sessionStorage.removeItem('philhealthCf1Draft');
                        sessionStorage.removeItem('philhealthCf2Draft');
                        sessionStorage.removeItem('philhealthCf3Draft');
                        sessionStorage.removeItem('philhealthCf4Draft');
                        sessionStorage.removeItem('philhealthNewClaimActive');
                        sessionStorage.removeItem('philhealthStepCf1Complete');
                        sessionStorage.removeItem('philhealthStepCf2Complete');
                        sessionStorage.removeItem('philhealthStepCf3Complete');
                        sessionStorage.removeItem('philhealthStepCf4Complete');
                    } catch (e) {
                    }
                    window.location.href = 'philhealth-claims.php';
                });
            }

            function ensureAiFillOverlay() {
                let style = document.getElementById('aiFillOverlayStyle');
                if (!style) {
                    style = document.createElement('style');
                    style.id = 'aiFillOverlayStyle';
                    style.textContent = '@keyframes aiFillSpin{to{transform:rotate(360deg)}}.ai-fill-spin{animation:aiFillSpin 1s linear infinite}';
                    document.head.appendChild(style);
                }
                let overlay = document.getElementById('aiFillOverlay');
                if (!overlay) {
                    overlay = document.createElement('div');
                    overlay.id = 'aiFillOverlay';
                    overlay.className = 'hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center';
                    overlay.innerHTML = `
                        <div class="bg-white rounded-xl shadow-xl px-6 py-5 w-[92%] max-w-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full border-4 border-gray-200 border-t-blue-600 ai-fill-spin"></div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900" id="aiFillOverlayTitle">Filling with AI…</div>
                                    <div class="text-xs text-gray-500" id="aiFillOverlaySubtitle">Please wait</div>
                                </div>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(overlay);
                }
                return overlay;
            }

            function showAiFillOverlay(title, subtitle) {
                const overlay = ensureAiFillOverlay();
                const t = overlay.querySelector('#aiFillOverlayTitle');
                const s = overlay.querySelector('#aiFillOverlaySubtitle');
                if (t) t.textContent = title || 'Filling with AI…';
                if (s) s.textContent = subtitle || 'Please wait';
                overlay.classList.remove('hidden');
            }

            function hideAiFillOverlay() {
                const overlay = document.getElementById('aiFillOverlay');
                if (overlay) overlay.classList.add('hidden');
            }

            const collect = () => {
                const form = document.querySelector('form');
                const targets = [];
                const fields = [];
                if (!form) return { targets, fields };

                const digitContainers = Array.from(form.querySelectorAll('.cf-pin, .cf-date'));
                digitContainers.forEach(container => {
                    const inputs = Array.from(container.querySelectorAll('input'));
                    if (!inputs.length) return;
                    const aria = container.getAttribute('aria-label') || '';
                    targets.push({ kind: 'digits', container, inputs });
                    fields.push({ kind: 'digits', label: aria || 'digits', length: inputs.length });
                });

                const skip = (el) => !!el.closest('.cf-pin, .cf-date');

                const radiosByName = new Map();
                Array.from(form.querySelectorAll('input[type="radio"]')).forEach(r => {
                    if (!r.name) return;
                    if (skip(r)) return;
                    if (!radiosByName.has(r.name)) radiosByName.set(r.name, []);
                    radiosByName.get(r.name).push(r);
                });

                Array.from(form.querySelectorAll('input, textarea, select')).forEach(el => {
                    const tag = (el.tagName || '').toLowerCase();
                    const type = (el.getAttribute('type') || '').toLowerCase();
                    if (skip(el)) return;
                    if (tag === 'input' && type === 'radio') return;
                    if (type === 'file') return;
                    if (el.disabled) return;

                    if (tag === 'select') {
                        const options = Array.from(el.options).map(o => ({ value: o.value, label: o.textContent || '' })).filter(o => o.value !== '');
                        targets.push({ kind: 'select', el, options });
                        fields.push({ kind: 'select', name: el.name || '', required: !!el.required, options });
                        return;
                    }

                    if (tag === 'input' && type === 'checkbox') {
                        targets.push({ kind: 'checkbox', el });
                        fields.push({ kind: 'checkbox', name: el.name || '', required: !!el.required });
                        return;
                    }

                    targets.push({ kind: 'input', el, type: type || 'text' });
                    fields.push({ kind: 'input', name: el.name || '', type: type || 'text', required: !!el.required, placeholder: el.getAttribute('placeholder') || '' });
                });

                Array.from(radiosByName.entries()).forEach(([name, radios]) => {
                    const options = radios.map(r => ({ value: r.value || '', label: (r.parentElement && r.parentElement.textContent) ? r.parentElement.textContent.trim() : '' })).filter(o => o.value !== '');
                    if (!options.length) return;
                    targets.push({ kind: 'radio', name, radios, options });
                    fields.push({ kind: 'radio', name, required: radios.some(r => r.required), options });
                });

                return { targets, fields };
            };

            btn.addEventListener('click', async () => {
                if (!isEditMode) {
                    alert('Switch to Edit mode to fill information.');
                    return;
                }
                btn.disabled = true;
                showAiFillOverlay('Filling with AI…', 'Preparing CF3 information');
                try {
                    const getAiSeed = () => {
                        let seed = '';
                        try {
                            seed = (sessionStorage.getItem('philhealthAiSeed') || '').toString();
                        } catch (e) {
                            seed = '';
                        }
                        if (seed.trim() !== '') return seed.trim();
                        seed = Date.now().toString(36) + '-' + Math.random().toString(36).slice(2, 10);
                        try {
                            sessionStorage.setItem('philhealthAiSeed', seed);
                        } catch (e) {
                        }
                        return seed;
                    };

                    const seed = getAiSeed();
                    const { targets, fields } = collect();
                    const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'cf3', seed, fields }),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'AI fill failed');
                    }
                    const values = Array.isArray(json.values) ? json.values : [];
                    values.forEach(item => {
                        if (!item || typeof item !== 'object') return;
                        const idx = Number(item.index);
                        if (!Number.isFinite(idx)) return;
                        const target = targets[idx];
                        if (!target) return;

                        if (target.kind === 'digits') {
                            const s = (item.value ?? '').toString().replace(/\D/g, '');
                            target.inputs.forEach((el, i) => {
                                el.value = s[i] || '';
                                el.dispatchEvent(new Event('input', { bubbles: true }));
                                el.dispatchEvent(new Event('change', { bubbles: true }));
                            });
                            return;
                        }

                        if (target.kind === 'select') {
                            const v = item.value;
                            const has = target.options.some(o => o.value === v);
                            if (has) target.el.value = v;
                            else if (target.options[0]) target.el.value = target.options[0].value;
                            target.el.dispatchEvent(new Event('input', { bubbles: true }));
                            target.el.dispatchEvent(new Event('change', { bubbles: true }));
                            return;
                        }

                        if (target.kind === 'checkbox') {
                            target.el.checked = !!(item.checked ?? item.value);
                            target.el.dispatchEvent(new Event('change', { bubbles: true }));
                            return;
                        }

                        if (target.kind === 'radio') {
                            const v = item.value;
                            const selected = target.radios.find(r => r.value === v) || target.radios[0];
                            if (selected) {
                                selected.checked = true;
                                selected.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                            return;
                        }

                        if (target.kind === 'input') {
                            target.el.value = (item.value ?? '').toString();
                            target.el.dispatchEvent(new Event('input', { bubbles: true }));
                            target.el.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    });
                } catch (e) {
                    alert(e && e.message ? e.message : 'AI fill failed');
                } finally {
                    btn.disabled = false;
                    hideAiFillOverlay();
                }
            });
        })();
    </script>
    <?php endif; ?>
</body>
</html>
