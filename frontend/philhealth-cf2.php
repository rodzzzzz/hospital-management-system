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
    <title>PhilHealth CF2</title>
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
                                    <div class="text-lg font-semibold">CF2</div>
                                    <div class="text-xs text-gray-500">Summarized</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button id="fillInfoBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Fill Info</button>
                                    <button id="cancelClaimBtn" type="button" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">Cancel Claim Registration</button>
                                    <a id="prevCfBtn" href="philhealth-cf1.php<?php echo $isEditMode ? '?mode=edit' : ''; ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Previous (CF1)</a>
                                    <a id="nextCfBtn" href="philhealth-cf3.php<?php echo $isEditMode ? '?mode=edit' : ''; ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Next (CF3)</a>
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

                        <form id="cf2Form" class="space-y-6">
                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">PART II - PATIENT CONFINEMENT INFORMATION</div>
                                <div class="p-5 space-y-5">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Date Admitted</div>
                                            <input class="cf-input mt-2" type="text" name="date_admitted" placeholder="mm/dd/yyyy" />
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Date Discharged</div>
                                            <input class="cf-input mt-2" type="text" name="date_discharged" placeholder="mm/dd/yyyy" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6 cf-box p-4">
                                            <div class="cf-label font-semibold">Patient Disposition</div>
                                            <select class="cf-input mt-2" name="patient_disposition">
                                                <option value=""></option>
                                                <option value="improved">Improved</option>
                                                <option value="recovered">Recovered</option>
                                                <option value="transferred">Transferred/Referred</option>
                                                <option value="hama">HAMA/DAMA</option>
                                                <option value="absconded">Absconded</option>
                                                <option value="expired">Expired</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <input class="cf-input mt-2" type="text" name="patient_disposition_other" placeholder="If other, specify" />
                                        </div>
                                        <div class="col-span-12 md:col-span-6 cf-box p-4">
                                            <div class="cf-label font-semibold">Type of Accommodation</div>
                                            <div class="mt-3 flex flex-wrap gap-6">
                                                <label class="cf-check"><input type="radio" name="accommodation_type" value="private" /> Private</label>
                                                <label class="cf-check"><input type="radio" name="accommodation_type" value="non_private" /> Non-Private/Charity</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Admission Diagnosis</div>
                                            <textarea class="cf-input mt-2" name="admission_diagnosis" rows="3"></textarea>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Discharge Diagnosis</div>
                                            <textarea class="cf-input mt-2" name="discharge_diagnosis" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">PART III - CERTIFICATION OF CONSUMPTION OF BENEFITS AND CONSENT TO ACCESS PATIENT RECORD/S</div>
                                <div class="p-5 space-y-5">
                                    <div class="cf-box p-4">
                                        <div class="cf-label font-semibold">Certification of Consumption of Benefits</div>
                                        <div class="mt-3 grid grid-cols-12 gap-4">
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Benefit is enough to cover charges?</div>
                                                <select class="cf-input mt-2" name="benefit_covers_all">
                                                    <option value=""></option>
                                                    <option value="yes">Yes</option>
                                                    <option value="no">No</option>
                                                </select>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Total HCI Fees</div>
                                                <input class="cf-input mt-2" type="text" name="total_hci_fees" />
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Total Professional Fees</div>
                                                <input class="cf-input mt-2" type="text" name="total_prof_fees" />
                                            </div>
                                        </div>
                                        <div class="mt-4 grid grid-cols-12 gap-4">
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Total Actual Charges</div>
                                                <input class="cf-input mt-2" type="text" name="total_actual_charges" />
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Purchases (outside HCI)</div>
                                                <input class="cf-input mt-2" type="text" name="purchases_total" />
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Diagnostics (outside HCI)</div>
                                                <input class="cf-input mt-2" type="text" name="diagnostics_total" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="cf-box p-4">
                                        <div class="cf-label font-semibold">Consent to Access Patient Record/s</div>
                                        <div class="mt-3 grid grid-cols-12 gap-4 items-end">
                                            <div class="col-span-12 md:col-span-5">
                                                <div class="cf-label font-semibold">Signature Over Printed Name</div>
                                                <input class="cf-input mt-2" type="text" name="consent_name" />
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <div class="cf-label font-semibold">Relationship (if representative)</div>
                                                <input class="cf-input mt-2" type="text" name="consent_relationship" />
                                            </div>
                                            <div class="col-span-12 md:col-span-3">
                                                <div class="cf-label font-semibold">Date Signed</div>
                                                <input class="cf-input mt-2" type="text" name="consent_date_signed" placeholder="mm/dd/yyyy" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </form>

                        <script>
                            (function () {
                                const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
                                const form = document.getElementById('cf2Form');
                                if (!form) return;

                                const isEmbed = <?php echo $isEmbed ? 'true' : 'false'; ?>;
                                if (isEmbed) {
                                    const topActionButtons = document.querySelector('.flex.items-center.justify-between.gap-4.mb-6');
                                    if (topActionButtons) topActionButtons.style.display = 'none';
                                }

                                const ensureAutoNames = () => {
                                    let i = 0;
                                    const els = Array.from(form.querySelectorAll('input, textarea, select'));
                                    els.forEach((el) => {
                                        const tag = (el.tagName || '').toLowerCase();
                                        const type = (el.getAttribute('type') || '').toLowerCase();
                                        if (tag === 'input' && (type === 'button' || type === 'submit' || type === 'reset' || type === 'hidden')) return;
                                        if ((el.getAttribute('name') || '').toString().trim() !== '') return;
                                        el.setAttribute('name', 'cf2_auto_' + (i++));
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
                                const saveDraft = () => writeJson('philhealthCf2Draft', collectDraft());
                                const scheduleSave = () => {
                                    if (t) clearTimeout(t);
                                    t = setTimeout(saveDraft, 150);
                                };

                                applyDraft(readJson('philhealthCf2Draft'));

                                const params = new URLSearchParams(window.location.search);
                                const patientId = params.get('patient_id');
                                const pin = params.get('pin');
                                if (!patientId && !pin) {
                                    try {
                                        const existing = readJson('philhealthCf2Draft');
                                        const pid = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim();
                                        if (!existing && pid) {
                                            (async () => {
                                                const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?patient_id=' + encodeURIComponent(pid), { headers: { 'Accept': 'application/json' } });
                                                const json = await res.json().catch(() => null);
                                                if (!res.ok || !json || !json.ok || !json.forms || !json.forms.cf2 || typeof json.forms.cf2 !== 'object') return;
                                                writeJson('philhealthCf2Draft', json.forms.cf2);
                                                applyDraft(json.forms.cf2);
                                            })().catch(() => { });
                                        }
                                    } catch (e0) {
                                    }
                                }

                                if (patientId || pin) {
                                    (async () => {
                                        const qs = patientId ? ('patient_id=' + encodeURIComponent(patientId)) : ('pin=' + encodeURIComponent(pin));
                                        const res = await fetch(API_BASE_URL + '/philhealth/member_cf2.php?' + qs, { headers: { 'Accept': 'application/json' } });
                                        const json = await res.json().catch(() => null);
                                        if (!res.ok || !json || !json.ok) return;
                                        if (json.cf2 && typeof json.cf2 === 'object') {
                                            writeJson('philhealthCf2Draft', json.cf2);
                                            applyDraft(json.cf2);
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
                                                alert('Please complete all fields in CF2 before proceeding to CF3.');
                                                return;
                                            }
                                            try {
                                                sessionStorage.setItem('philhealthStepCf2Complete', '1');
                                            } catch (e2) {
                                            }
                                            window.location.href = nextLink.getAttribute('href') || 'philhealth-cf3.php?mode=edit';
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
                                if (safeGet('philhealthNewClaimActive') === '1') {
                                    const p = new URLSearchParams(window.location.search);
                                    if (p.get('mode') !== 'edit') {
                                        p.set('mode', 'edit');
                                        window.location.href = window.location.pathname + '?' + p.toString();
                                        return;
                                    }
                                    const wrap = document.getElementById('modeToggleWrap');
                                    if (wrap) wrap.style.display = 'none';
                                    if (safeGet('philhealthStepCf1Complete') !== '1') {
                                        window.location.href = 'philhealth-cf1.php?mode=edit';
                                        return;
                                    }
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

                                const fillBtn = document.getElementById('fillInfoBtn');
                                const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
                                if (!fillBtn) return;

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

                                fillBtn.addEventListener('click', async () => {
                                    if (!isEditMode) {
                                        alert('Switch to Edit mode to fill information.');
                                        return;
                                    }
                                    fillBtn.disabled = true;
                                    showAiFillOverlay('Filling with AI…', 'Preparing CF2 information');
                                    try {
                                        const seed = getAiSeed();
                                        const { targets, fields } = collect();
                                        const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                            body: JSON.stringify({ page: 'cf2', seed, fields }),
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
                                        fillBtn.disabled = false;
                                        hideAiFillOverlay();
                                    }
                                });
                            })();
                        </script>

                        <?php else: ?>
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <button id="fillInfoBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Fill Info</button>
                                    <button id="cancelClaimBtn" type="button" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">Cancel Claim Registration</button>
                                    <a id="prevCfBtn" href="philhealth-cf1.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Previous (CF1)</a>
                                    <a id="nextCfBtn" href="philhealth-cf3.php" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">Next (CF3)</a>
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
                                <div class="col-span-12 md:col-span-4">
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

                                <div class="col-span-12 md:col-span-5 text-center">
                                    <div class="text-xs">Republic of the Philippines</div>
                                    <div class="text-sm font-bold">PHILIPPINE HEALTH INSURANCE CORPORATION</div>
                                </div>

                                <div class="col-span-12 md:col-span-3">
                                    <div class="flex justify-end">
                                        <div class="text-right">
                                            <div class="text-xs">This form may be reproduced and is NOT FOR SALE</div>
                                            <div class="mt-2 inline-block cf-box px-3 py-2">
                                                <div class="text-2xl font-black leading-none">CF-2</div>
                                                <div class="text-xs font-semibold">(Claim Form 2)</div>
                                                <div class="text-[10px] text-gray-600">Revised September 2018</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-3 flex items-center justify-end gap-2">
                                        <div class="text-xs font-semibold">Series #</div>
                                        <div class="cf-pin" aria-label="Series number">
                                            <?php for ($i = 0; $i < 8; $i++): ?>
                                                <input type="text" maxlength="1" inputmode="numeric" />
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="cf-box p-4 bg-white">
                                <div class="text-sm font-semibold">IMPORTANT REMINDERS</div>
                                <div class="cf-small mt-2 text-gray-700">PLEASE WRITE IN CAPITAL LETTERS AND CHECK THE APPROPRIATE BOXES.</div>
                                <div class="cf-small mt-1 text-gray-700">Fill-out this form together with other supporting documents within sixty (60) calendar days from date of discharge.</div>
                                <div class="cf-small mt-1 text-gray-700">All information, fields and tick boxes required in this form are necessary. Claim forms with incomplete information shall not be processed.</div>
                                <div class="cf-small mt-1 text-gray-700 font-semibold">FALSE/INCORRECT INFORMATION OR MISREPRESENTATION SHALL BE SUBJECT TO CRIMINAL, CIVIL OR ADMINISTRATIVE LIABILITIES.</div>
                            </div>

                            <form class="space-y-6">
                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">PART I - HEALTH CARE INSTITUTION (HCI) INFORMATION</div>
                                    <div class="p-5 space-y-5">
                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-7">
                                                <div class="cf-label font-semibold">1. PhilHealth Accreditation Number (PAN) of Health Care Institution:</div>
                                                <div class="mt-2 cf-pin" aria-label="HCI PAN">
                                                    <?php for ($i = 0; $i < 12; $i++): ?>
                                                        <input type="text" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12">
                                                <div class="cf-label font-semibold">2. Name of Health Care Institution:</div>
                                                <input class="cf-input mt-2" type="text" />
                                            </div>
                                        </div>

                                        <div>
                                            <div class="cf-label font-semibold">3. Address:</div>
                                            <div class="mt-2 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Building Number and Street Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">City/Municipality</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Province</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">PART II - PATIENT CONFINEMENT INFORMATION</div>
                                    <div class="p-5 space-y-6">
                                        <div>
                                            <div class="cf-label font-semibold">1. Name of Patient:</div>
                                            <div class="mt-2 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Last Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">First Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Name Extension (JR/SR/III)</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Middle Name</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4 bg-white">
                                            <div class="cf-label font-semibold">2. Was patient referred by another Health Care Institution (HCI)?</div>
                                            <div class="mt-2 flex flex-wrap gap-6">
                                                <label class="cf-check"><input type="radio" name="referred" value="no" /> No</label>
                                                <label class="cf-check"><input type="radio" name="referred" value="yes" /> Yes</label>
                                            </div>
                                            <div class="mt-4 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Name of referring Health Care Institution</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-4">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Building Number and Street Name</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">City/Municipality</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-1">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Province</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-1">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Zip code</div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12">
                                                <div class="cf-label font-semibold">3. Confinement Period:</div>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">a. Date Admitted</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Admit month">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Admit day">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Admit year">
                                                            <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">year</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 cf-label font-semibold">b. Time Admitted</div>
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
                                                <div class="cf-label font-semibold">c. Date Discharge</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharge month">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharge day">
                                                            <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharge year">
                                                            <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                        </div>
                                                        <div class="cf-small mt-1 text-center">year</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 cf-label font-semibold">d. Time Discharge</div>
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

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">4. Patient Disposition <span class="font-normal">(select only 1)</span></div>
                                                <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <label class="cf-check"><input type="radio" name="patient_disposition" value="improved" /> a. Improved</label>
                                                    <label class="cf-check"><input type="radio" name="patient_disposition" value="expired" /> e. Expired</label>
                                                    <label class="cf-check"><input type="radio" name="patient_disposition" value="recovered" /> b. Recovered</label>
                                                    <label class="cf-check"><input type="radio" name="patient_disposition" value="transferred" /> f. Transferred/Referred</label>
                                                    <label class="cf-check"><input type="radio" name="patient_disposition" value="home_against" /> c. Home/Discharged Against Medical Advice</label>
                                                    <label class="cf-check"><input type="radio" name="patient_disposition" value="absconded" /> d. Absconded</label>
                                                </div>
                                                <div class="mt-4 grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Name of Referral Health Care Institution</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Building Number and Street Name</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-2">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">City/Municipality</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-1">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Province</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-1">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Zip code</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-12 lg:col-span-6 space-y-4">
                                                <div class="cf-box p-4">
                                                    <div class="cf-label font-semibold">5. Type of Accommodation:</div>
                                                    <div class="mt-2 flex flex-wrap gap-6">
                                                        <label class="cf-check"><input type="radio" name="accommodation" value="private" /> Private</label>
                                                        <label class="cf-check"><input type="radio" name="accommodation" value="non_private" /> Non-Private (Charity)/Service</label>
                                                    </div>
                                                    <div class="mt-3">
                                                        <div class="cf-label font-semibold">Reason/s for referral/transfer:</div>
                                                        <input class="cf-input mt-2" type="text" />
                                                    </div>
                                                </div>

                                                <div class="cf-box p-4">
                                                    <div class="cf-label font-semibold">6. Admission Diagnosis/es:</div>
                                                    <textarea class="cf-input mt-2" rows="3"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">7. Discharge Diagnosis/es <span class="font-normal">(Use additional CF2 if necessary)</span>:</div>
                                            <div class="mt-4 space-y-2">
                                                <div class="hidden sm:grid grid-cols-12 gap-2 text-xs font-semibold text-gray-600 mb-2">
                                                    <div class="col-span-3">Diagnosis</div>
                                                    <div class="col-span-2">ICD-10 Code/s</div>
                                                    <div class="col-span-3">Related Procedure/s (if there's any)</div>
                                                    <div class="col-span-1">RVS Code</div>
                                                    <div class="col-span-2">Date of Procedure</div>
                                                    <div class="col-span-1">Laterality</div>
                                                </div>
                                                <?php for ($r = 0; $r < 4; $r++): ?>
                                                    <div class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                                                        <div class="min-w-0 sm:col-span-3">
                                                            <div class="sm:hidden text-xs font-semibold text-gray-600 mb-1">Diagnosis</div>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                        <div class="min-w-0 sm:col-span-2">
                                                            <div class="sm:hidden text-xs font-semibold text-gray-600 mb-1">ICD-10 Code/s</div>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                        <div class="min-w-0 sm:col-span-3">
                                                            <div class="sm:hidden text-xs font-semibold text-gray-600 mb-1">Related Procedure/s</div>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                        <div class="min-w-0 sm:col-span-1">
                                                            <div class="sm:hidden text-xs font-semibold text-gray-600 mb-1">RVS Code</div>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                        <div class="min-w-0 sm:col-span-2">
                                                            <div class="sm:hidden text-xs font-semibold text-gray-600 mb-1">Date of Procedure</div>
                                                            <input class="cf-input" type="text" placeholder="mm/dd/yyyy" />
                                                        </div>
                                                        <div class="min-w-0 sm:col-span-1">
                                                            <div class="sm:hidden text-xs font-semibold text-gray-600 mb-1">Laterality</div>
                                                            <select class="cf-input">
                                                                <option value=""></option>
                                                                <option value="left">left</option>
                                                                <option value="right">right</option>
                                                                <option value="both">both</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4 space-y-4">
                                            <div class="cf-label font-semibold">8. Special Considerations:</div>

                                            <div class="cf-small text-gray-700"><span class="font-semibold">a.</span> For the following repetitive procedures, check box that applies and enumerate the procedure/sessions dates (mm-dd-yyyy). For chemotherapy, see guidelines.</div>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                <div class="space-y-2">
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Hemodialysis</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Peritoneal Dialysis</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Radiotherapy (LINAC)</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Radiotherapy (COBALT)</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                </div>
                                                <div class="space-y-2">
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Blood Transfusion</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Brachytherapy</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Chemotherapy</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3">
                                                        <label class="cf-check whitespace-nowrap"><input type="checkbox" /> Simple Debridement</label>
                                                        <input class="cf-input flex-1 min-w-[220px]" type="text" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="cf-small text-gray-700"><span class="font-semibold">b.</span> For Z-Benefit Package</div>
                                            <div class="grid grid-cols-12 gap-3 items-end">
                                                <div class="col-span-12 md:col-span-4">
                                                    <div class="cf-label font-semibold">Z-Benefit Package Code:</div>
                                                    <input class="cf-input mt-2" type="text" />
                                                </div>
                                                <div class="col-span-12 md:col-span-8">
                                                    <div class="cf-label font-semibold">c. For MCP Package (enumerate four dates)</div>
                                                    <div class="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                                                        <input class="cf-input" type="text" placeholder="mm/dd/yyyy" />
                                                        <input class="cf-input" type="text" placeholder="mm/dd/yyyy" />
                                                        <input class="cf-input" type="text" placeholder="mm/dd/yyyy" />
                                                        <input class="cf-input" type="text" placeholder="mm/dd/yyyy" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-12 gap-3 items-start">
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-label font-semibold">d. For TBDOTS Package</div>
                                                    <div class="mt-2 flex flex-wrap gap-6">
                                                        <label class="cf-check"><input type="checkbox" /> Intensive Phase</label>
                                                        <label class="cf-check"><input type="checkbox" /> Maintenance Phase</label>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-label font-semibold">e. For Animal Bite Package (write dates)</div>
                                                    <div class="mt-2 cf-box p-3 bg-gray-50">
                                                        <div class="text-xs font-semibold">Note: Anti Rabies Vaccine (ARV), Rabies Immunoglobulin (RIG)</div>
                                                    </div>
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-2">
                                                        <input class="cf-input" type="text" placeholder="Day 0 ARV" />
                                                        <input class="cf-input" type="text" placeholder="Day 3 ARV" />
                                                        <input class="cf-input" type="text" placeholder="Day 7 ARV" />
                                                        <input class="cf-input" type="text" placeholder="RIG" />
                                                        <input class="cf-input" type="text" placeholder="Others (Specify)" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-1 gap-4">
                                                <div class="cf-box p-4">
                                                    <div class="cf-label font-semibold">f. For Newborn Care Package</div>
                                                    <div class="mt-3 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essential Newborn Care</label>
                                                        <label class="cf-check"><input type="checkbox" /> Newborn Hearing Screening Test</label>
                                                        <label class="cf-check"><input type="checkbox" /> Newborn Screening Test</label>
                                                    </div>

                                                    <div class="mt-4 text-sm font-semibold">For Essential Newborn Care (check applicable boxes)</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Immediate drying of newborn</label>
                                                        <label class="cf-check"><input type="checkbox" /> Timely cord clamping</label>
                                                        <label class="cf-check"><input type="checkbox" /> Early skin-to-skin contact</label>
                                                        <label class="cf-check"><input type="checkbox" /> Weighing of the newborn</label>
                                                        <label class="cf-check"><input type="checkbox" /> Eye prophylaxis</label>
                                                        <label class="cf-check"><input type="checkbox" /> Vitamin K administration</label>
                                                    </div>

                                                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> BCG vaccination</label>
                                                        <label class="cf-check"><input type="checkbox" /> Hepatitis B vaccination</label>
                                                        <label class="cf-check sm:col-span-2"><input type="checkbox" /> Non-separation of mother/baby for early breastfeeding initiation</label>
                                                    </div>

                                                    <div class="mt-4 cf-box p-3 bg-gray-50">
                                                        <div class="text-xs font-semibold">For Newborn Screening</div>
                                                        <div class="text-xs text-gray-600">please attach NBS Filter Sticker here</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="cf-box p-4">
                                                <div class="cf-label font-semibold">9. PhilHealth Benefits</div>
                                                <div class="mt-3 grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Laboratory Number</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">ICD 10 or RVS Code</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">First Case Rate</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Second Case Rate</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">PART III - CERTIFICATION OF CONSUMPTION OF BENEFITS AND CONSENT TO ACCESS PATIENT RECORD/S</div>
                                    <div class="p-5 space-y-6">
                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">A. CERTIFICATION OF CONSUMPTION OF BENEFITS:</div>
                                            <div class="mt-3 space-y-3">
                                                <label class="cf-check"><input type="checkbox" /> PhilHealth benefit is enough to cover HCI and PF charges.</label>
                                                <div class="cf-small text-gray-700">No purchase of drugs/medicines, supplies, diagnostics, and no co-pay for professional fees by the member/patient.</div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="grid grid-cols-12 gap-3">
                                                        <div class="col-span-12">
                                                            <input class="cf-input" type="text" />
                                                            <div class="cf-small text-center mt-1">Total Health Care Institution Fees</div>
                                                        </div>
                                                        <div class="col-span-12">
                                                            <input class="cf-input" type="text" />
                                                            <div class="cf-small text-center mt-1">Total Professional Fees</div>
                                                        </div>
                                                        <div class="col-span-12">
                                                            <input class="cf-input" type="text" />
                                                            <div class="cf-small text-center mt-1">Grand Total</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 md:col-span-6">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Total Actual Charges</div>
                                                </div>
                                            </div>

                                            <div class="mt-5 space-y-3">
                                                <label class="cf-check"><input type="checkbox" /> The benefit of the member/patient was completely consumed prior to co-pay OR the benefit is not completely consumed BUT with purchases/expenses for drugs/medicines, supplies, diagnostics and others.</label>
                                            </div>

                                            <div class="mt-4 overflow-x-auto">
                                                <div class="min-w-[980px]">
                                                    <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-gray-600 mb-2">
                                                        <div class="col-span-2">Type</div>
                                                        <div class="col-span-2">Total Actual Charges</div>
                                                        <div class="col-span-2">Amount after Discount</div>
                                                        <div class="col-span-2">PhilHealth Benefit</div>
                                                        <div class="col-span-2">Amount after PhilHealth Deduction</div>
                                                        <div class="col-span-2">Paid by</div>
                                                    </div>

                                                    <div class="grid grid-cols-12 gap-2 mb-3">
                                                        <div class="col-span-2 text-sm text-gray-700 flex items-center">HCI Fees</div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2 space-y-2">
                                                            <label class="cf-check"><input type="checkbox" /> Member/Patient</label>
                                                            <label class="cf-check"><input type="checkbox" /> HMO</label>
                                                            <label class="cf-check"><input type="checkbox" /> Others</label>
                                                        </div>
                                                    </div>

                                                    <div class="grid grid-cols-12 gap-2">
                                                        <div class="col-span-2 text-sm text-gray-700 flex items-center">Professional Fees</div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2 space-y-2">
                                                            <label class="cf-check"><input type="checkbox" /> Member/Patient</label>
                                                            <label class="cf-check"><input type="checkbox" /> HMO</label>
                                                            <label class="cf-check"><input type="checkbox" /> Others</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-7">
                                                    <div class="cf-label font-semibold">Purchases/Expenses NOT included in the Health Care Institution Charges</div>
                                                    <div class="mt-2 grid grid-cols-12 gap-3">
                                                        <div class="col-span-12">
                                                            <input class="cf-input" type="text" />
                                                            <div class="cf-small text-center mt-1">Total cost of purchases (drugs/medicines and/or medical supplies bought by the patient/member within/outside the HCI during confinement)</div>
                                                        </div>
                                                        <div class="col-span-12">
                                                            <input class="cf-input" type="text" />
                                                            <div class="cf-small text-center mt-1">Total cost of diagnostic/laboratory examinations paid by the patient/member within/outside the HCI during confinement</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 md:col-span-5">
                                                    <div class="cf-label font-semibold">If none</div>
                                                    <div class="mt-2 space-y-3">
                                                        <label class="cf-check"><input type="checkbox" /> None (Purchases)</label>
                                                        <label class="cf-check"><input type="checkbox" /> None (Diagnostics)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">B. CONSENT TO ACCESS PATIENT RECORD/S:</div>
                                            <div class="cf-small mt-2 text-gray-700">
                                                I hereby consent to the submission and examination of the patient's pertinent medical records for the purpose of verifying the veracity of this claim to effect efficient processing of benefit payment.
                                            </div>

                                            <div class="mt-5 grid grid-cols-12 gap-4 items-start">
                                                <div class="col-span-12 lg:col-span-8 space-y-4">
                                                    <div>
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Signature Over Printed Name of Member/Patient/Authorized Representative</div>
                                                    </div>

                                                    <div>
                                                        <div class="cf-label font-semibold">Date Signed</div>
                                                        <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                            <div>
                                                                <div class="cf-date" aria-label="Signed month">
                                                                    <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                </div>
                                                                <div class="cf-small mt-1 text-center">month</div>
                                                            </div>
                                                            <div>
                                                                <div class="cf-date" aria-label="Signed day">
                                                                    <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                </div>
                                                                <div class="cf-small mt-1 text-center">day</div>
                                                            </div>
                                                            <div>
                                                                <div class="cf-date" aria-label="Signed year">
                                                                    <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                                </div>
                                                                <div class="cf-small mt-1 text-center">year</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-12 lg:col-span-4">
                                                    <div class="cf-box bg-gray-50 p-4 min-h-[160px]"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">PART IV - CERTIFICATION OF CONSUMPTION OF HEALTH CARE INSTITUTION</div>
                                    <div class="p-5">
                                        <div class="cf-small text-gray-700">
                                            I certify that services rendered were recorded in the patient's chart and health care institution records and that the herein information given are true and correct.
                                        </div>
                                        <div class="mt-5 grid grid-cols-12 gap-4 items-end">
                                            <div class="col-span-12 md:col-span-5">
                                                <input class="cf-input" type="text" />
                                                <div class="cf-small text-center mt-1">Signature Over Printed Name of Authorized HCI representative</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-4">
                                                <input class="cf-input" type="text" />
                                                <div class="cf-small text-center mt-1">Official Capacity/Designation</div>
                                            </div>
                                            <div class="col-span-12 md:col-span-3">
                                                <div class="cf-label font-semibold">Date Signed</div>
                                                <div class="mt-2 grid grid-cols-3 gap-2">
                                                    <div class="cf-date" aria-label="Part IV month">
                                                        <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                    </div>
                                                    <div class="cf-date" aria-label="Part IV day">
                                                        <?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
                                                    </div>
                                                    <div class="cf-date" aria-label="Part IV year">
                                                        <?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?>
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
            if (safeGet('philhealthStepCf1Complete') !== '1') {
                window.location.href = 'philhealth-cf1.php?mode=edit';
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
                el.setAttribute('name', 'cf2_auto_' + (autoIdx++));
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
                    sessionStorage.setItem('philhealthCf2Draft', JSON.stringify(obj));
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
                        alert('Please complete all fields in CF2 before proceeding to CF3.');
                        return;
                    }
                    try {
                        sessionStorage.setItem('philhealthStepCf2Complete', '1');
                    } catch (e2) {
                    }
                    window.location.href = 'philhealth-cf3.php?mode=edit';
                });
            }
            if (prevLink) prevLink.addEventListener('click', () => save());

            save();
        })();

        (function () {
            const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
            const btn = document.getElementById('fillInfoBtn');
            if (!btn) return;

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
                showAiFillOverlay('Filling with AI…', 'Preparing CF2 information');
                try {
                    const seed = getAiSeed();
                    const { targets, fields } = collect();
                    const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'cf2', seed, fields }),
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
