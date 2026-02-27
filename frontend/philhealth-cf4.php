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
    <title>PhilHealth CF4</title>
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
                                    <div class="text-lg font-semibold">CF4</div>
                                    <div class="text-xs text-gray-500">Summarized</div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button id="fillInfoBtn" type="button" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Fill Info</button>
                                    <button id="cancelClaimBtn" type="button" class="px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition-all">Cancel Claim Registration</button>
                                    <a href="philhealth-cf3.php<?php echo $isEditMode ? '?mode=edit' : ''; ?>" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Previous (CF3)</a>
                                    <?php if ($isEditMode): ?>
                                        <button id="doneCf4Btn" type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Done Registration</button>
                                    <?php endif; ?>
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

                        <form id="cf4Form" class="space-y-6">
                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">I. HEALTH CARE INSTITUTION (HCI) INFORMATION</div>
                                <div class="p-5 space-y-4">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">HCI Name</div>
                                            <input class="cf-input mt-2" type="text" name="hci_name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">HCI Accreditation No.</div>
                                            <input class="cf-input mt-2" type="text" name="hci_accreditation_no" />
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-8">
                                            <div class="cf-label font-semibold">HCI Address</div>
                                            <input class="cf-input mt-2" type="text" name="hci_address" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Contact No.</div>
                                            <input class="cf-input mt-2" type="text" name="hci_contact_no" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">II. PATIENT'S DATA</div>
                                <div class="p-5 space-y-4">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">PhilHealth PIN</div>
                                            <input class="cf-input mt-2" type="text" name="patient_pin" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Sex</div>
                                            <select class="cf-input mt-2" name="patient_sex">
                                                <option value=""></option>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Birthdate</div>
                                            <input class="cf-input mt-2" type="text" name="patient_birthdate" placeholder="yyyy-mm-dd" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Last Name</div>
                                            <input class="cf-input mt-2" type="text" name="patient_last_name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">First Name</div>
                                            <input class="cf-input mt-2" type="text" name="patient_first_name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Middle Name</div>
                                            <input class="cf-input mt-2" type="text" name="patient_middle_name" />
                                        </div>
                                        <div class="col-span-12 md:col-span-3">
                                            <div class="cf-label font-semibold">Name Ext</div>
                                            <input class="cf-input mt-2" type="text" name="patient_name_ext" placeholder="JR/SR/III" />
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-8">
                                            <div class="cf-label font-semibold">Address</div>
                                            <input class="cf-input mt-2" type="text" name="patient_address" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Contact No.</div>
                                            <input class="cf-input mt-2" type="text" name="patient_contact" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">III. REASON FOR ADMISSION</div>
                                <div class="p-5 space-y-4">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Chief Complaint</div>
                                            <textarea class="cf-input mt-2" name="chief_complaint" rows="3"></textarea>
                                        </div>
                                        <div class="col-span-12 md:col-span-6">
                                            <div class="cf-label font-semibold">Working/Admitting Diagnosis</div>
                                            <textarea class="cf-input mt-2" name="admitting_diagnosis" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Date Admitted</div>
                                            <input class="cf-input mt-2" type="text" name="date_admitted" placeholder="mm/dd/yyyy" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Time Admitted</div>
                                            <input class="cf-input mt-2" type="text" name="time_admitted" placeholder="hh:mm AM/PM" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Admitting Physician</div>
                                            <input class="cf-input mt-2" type="text" name="admitting_physician" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">IV. COURSE IN THE WARD</div>
                                <div class="p-5 space-y-4">
                                    <div>
                                        <div class="cf-label font-semibold">Course Summary</div>
                                        <textarea class="cf-input mt-2" name="course_in_ward" rows="5"></textarea>
                                    </div>
                                    <div>
                                        <div class="cf-label font-semibold">Procedures / Operations (if any)</div>
                                        <textarea class="cf-input mt-2" name="procedures" rows="3"></textarea>
                                    </div>
                                </div>
                            </section>

                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">V. DRUGS / MEDICINES</div>
                                <div class="p-5 space-y-4">
                                    <div>
                                        <div class="cf-label font-semibold">Drugs/Medicines Given (name + dose + frequency)</div>
                                        <textarea class="cf-input mt-2" name="drugs_medicines" rows="5"></textarea>
                                    </div>
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Total Medicine Cost (optional)</div>
                                            <input class="cf-input mt-2" type="text" name="medicine_total_cost" />
                                        </div>
                                        <div class="col-span-12 md:col-span-8">
                                            <div class="cf-label font-semibold">Pharmacy Notes</div>
                                            <input class="cf-input mt-2" type="text" name="pharmacy_notes" />
                                        </div>
                                    </div>
                                </div>
                            </section>

                            <section class="cf-section rounded-2xl overflow-hidden">
                                <div class="cf-section-title">VI. OUTCOME OF TREATMENT</div>
                                <div class="p-5 space-y-4">
                                    <div class="grid grid-cols-12 gap-4">
                                        <div class="col-span-12 md:col-span-4 cf-box p-4">
                                            <div class="cf-label font-semibold">Outcome</div>
                                            <select class="cf-input mt-2" name="outcome">
                                                <option value=""></option>
                                                <option value="improved">Improved</option>
                                                <option value="recovered">Recovered</option>
                                                <option value="transferred">Transferred/Referred</option>
                                                <option value="hama">HAMA/DAMA</option>
                                                <option value="expired">Expired</option>
                                                <option value="other">Other</option>
                                            </select>
                                            <input class="cf-input mt-2" type="text" name="outcome_other" placeholder="If other, specify" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Date Discharged</div>
                                            <input class="cf-input mt-2" type="text" name="date_discharged" placeholder="mm/dd/yyyy" />
                                        </div>
                                        <div class="col-span-12 md:col-span-4">
                                            <div class="cf-label font-semibold">Time Discharged</div>
                                            <input class="cf-input mt-2" type="text" name="time_discharged" placeholder="hh:mm AM/PM" />
                                        </div>
                                    </div>
                                    <div>
                                        <div class="cf-label font-semibold">Final Diagnosis / Remarks</div>
                                        <textarea class="cf-input mt-2" name="final_remarks" rows="4"></textarea>
                                    </div>
                                </div>
                            </section>
                        </form>

                        <script>
                            (function () {
                                const isEditMode = <?php echo $isEditMode ? 'true' : 'false'; ?>;
                                const form = document.getElementById('cf4Form');
                                if (!form) return;

                                const isEmbed = <?php echo $isEmbed ? 'true' : 'false'; ?>;

                                const ensureAutoNames = () => {
                                    let i = 0;
                                    const els = Array.from(form.querySelectorAll('input, textarea, select'));
                                    els.forEach((el) => {
                                        const tag = (el.tagName || '').toLowerCase();
                                        const type = (el.getAttribute('type') || '').toLowerCase();
                                        if (tag === 'input' && (type === 'button' || type === 'submit' || type === 'reset' || type === 'hidden')) return;
                                        if ((el.getAttribute('name') || '').toString().trim() !== '') return;
                                        el.setAttribute('name', 'cf4_auto_' + (i++));
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
                                const saveDraft = () => writeJson('philhealthCf4Draft', collectDraft());
                                const scheduleSave = () => {
                                    if (t) clearTimeout(t);
                                    t = setTimeout(saveDraft, 150);
                                };

                                if (isEmbed) {
                                    window.addEventListener('message', function (event) {
                                        const data = event && event.data;
                                        if (!data || data.type !== 'PHILHEALTH_MEMBER_FORMS_SAVE') return;
                                        saveDraft();
                                    });
                                }

                                applyDraft(readJson('philhealthCf4Draft'));

                                const params = new URLSearchParams(window.location.search);
                                const patientId = params.get('patient_id');
                                const pin = params.get('pin');

                                if (!patientId && !pin) {
                                    try {
                                        const existing = readJson('philhealthCf4Draft');
                                        const pid = (sessionStorage.getItem('philhealthPatientId') || '').toString().trim();
                                        if (!existing && pid) {
                                            (async () => {
                                                const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?patient_id=' + encodeURIComponent(pid), { headers: { 'Accept': 'application/json' } });
                                                const json = await res.json().catch(() => null);
                                                if (!res.ok || !json || !json.ok || !json.forms || !json.forms.cf4 || typeof json.forms.cf4 !== 'object') return;
                                                writeJson('philhealthCf4Draft', json.forms.cf4);
                                                applyDraft(json.forms.cf4);
                                            })().catch(() => { });
                                        }
                                    } catch (e0) {
                                    }
                                }

                                if (patientId || pin) {
                                    (async () => {
                                        const qs = patientId ? ('patient_id=' + encodeURIComponent(patientId)) : ('pin=' + encodeURIComponent(pin));
                                        const res = await fetch(API_BASE_URL + '/philhealth/member_cf4.php?' + qs, { headers: { 'Accept': 'application/json' } });
                                        const json = await res.json().catch(() => null);
                                        if (!res.ok || !json || !json.ok) return;
                                        if (json.cf4 && typeof json.cf4 === 'object') {
                                            writeJson('philhealthCf4Draft', json.cf4);
                                            applyDraft(json.cf4);
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
                                    return;
                                }

                                form.addEventListener('input', scheduleSave);
                                form.addEventListener('change', scheduleSave);
                                saveDraft();

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
                                        showAiFillOverlay('Filling with AI…', 'Preparing CF4 information');
                                        try {
                                            const seed = getAiSeed();
                                            const { targets, fields } = collect();
                                            const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                                                method: 'POST',
                                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                                body: JSON.stringify({ page: 'cf4', seed, fields }),
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

                                async function saveToDb() {
                                    const doneBtn = document.getElementById('doneCf4Btn');
                                    if (doneBtn) doneBtn.disabled = true;
                                    try {
                                        let isNewRegistration = false;
                                        try {
                                            isNewRegistration = (sessionStorage.getItem('philhealthNewClaimActive') === '1');
                                        } catch (e) {
                                            isNewRegistration = false;
                                        }
                                        saveDraft();
                                        const payload = {
                                            cf1: readJson('philhealthCf1Draft'),
                                            cf2: readJson('philhealthCf2Draft'),
                                            cf3: readJson('philhealthCf3Draft'),
                                            cf4: readJson('philhealthCf4Draft'),
                                        };

                                        if (isNewRegistration) {
                                            payload.finalize = true;
                                        }

                                        await fetch(API_BASE_URL + '/philhealth/install.php', {
                                            headers: { 'Accept': 'application/json' },
                                        });

                                        const res = await fetch(API_BASE_URL + '/philhealth/save.php', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                            body: JSON.stringify(payload),
                                        });
                                        const json = await res.json().catch(() => null);
                                        if (!res.ok || !json || !json.ok) {
                                            throw new Error((json && json.error) ? json.error : 'Save failed');
                                        }

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

                                        await fetch(API_BASE_URL + '/philhealth/claim_session.php', {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                                            body: JSON.stringify({ action: 'cancel' }),
                                        }).catch(() => { });

                                        window.location.href = 'philhealth-claims.php';
                                    } catch (e) {
                                        alert(e && e.message ? e.message : 'Save failed');
                                    } finally {
                                        if (doneBtn) doneBtn.disabled = false;
                                    }
                                }

                                const doneBtn = document.getElementById('doneCf4Btn');
                                if (doneBtn) {
                                    let isNewRegistration = false;
                                    try {
                                        isNewRegistration = (sessionStorage.getItem('philhealthNewClaimActive') === '1');
                                    } catch (e) {
                                        isNewRegistration = false;
                                    }
                                    doneBtn.textContent = isNewRegistration ? 'Done Registration' : 'Update';
                                    doneBtn.addEventListener('click', () => saveToDb());
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

                                if (safeGet('philhealthStepCf1Complete') !== '1' || safeGet('philhealthStepCf2Complete') !== '1' || safeGet('philhealthStepCf3Complete') !== '1') {
                                    window.location.href = 'philhealth-cf3.php?mode=edit';
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
                                    <a href="philhealth-cf3.php" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100 transition-all">Previous (CF3)</a>
                                    <button id="doneCf4Btn" type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">Done</button>
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
                                                <div class="text-2xl font-black leading-none">CF4</div>
                                                <div class="text-xs font-semibold">(Claim Form)</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form class="space-y-6">
                                <div class="grid grid-cols-12 gap-4 items-start">
                                    <div class="col-span-12 lg:col-span-8">
                                        <div class="cf-box p-4 bg-white">
                                            <div class="text-sm font-semibold">IMPORTANT REMINDERS</div>
                                            <div class="cf-small mt-2 text-gray-700">PLEASE FILL OUT APPROPRIATE FIELDS. WRITE IN CAPITAL LETTERS AND CHECK THE APPROPRIATE BOXES.</div>
                                            <div class="cf-small mt-1 text-gray-700">This form, together with other supporting documents, should be filed within sixty (60) calendar days from date of discharge.</div>
                                            <div class="cf-small mt-1 text-gray-700">All information, fields and tick boxes in this form are necessary. Claim forms with incomplete information shall not be processed.</div>
                                            <div class="cf-small mt-1 text-gray-700 font-semibold">FALSE / INCORRECT INFORMATION OR MISREPRESENTATION SHALL BE SUBJECT TO CRIMINAL, CIVIL OR ADMINISTRATIVE LIABILITIES.</div>
                                        </div>
                                    </div>

                                    <div class="col-span-12 lg:col-span-4">
                                        <div class="cf-box p-4 bg-white">
                                            <div class="flex items-center justify-between">
                                                <div class="text-xs font-semibold">Series #</div>
                                                <div class="cf-pin" aria-label="Series number">
                                                    <?php for ($i = 0; $i < 10; $i++): ?>
                                                        <input type="text" maxlength="1" inputmode="numeric" />
                                                    <?php endfor; ?>
                                                </div>
                                            </div>
                                            <div class="mt-2 text-xs text-gray-600">February 2020</div>
                                        </div>
                                    </div>
                                </div>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">I. HEALTH CARE INSTITUTION (HCI) INFORMATION</div>
                                    <div class="p-5 space-y-5">
                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-8">
                                                <div class="cf-label font-semibold">1. Name of HCI</div>
                                                <input class="cf-input mt-2" type="text" />
                                            </div>
                                            <div class="col-span-12 lg:col-span-4">
                                                <div class="cf-label font-semibold">2. Accreditation Number</div>
                                                <input class="cf-input mt-2" type="text" />
                                            </div>
                                        </div>

                                        <div>
                                            <div class="cf-label font-semibold">3. Address of HCI</div>
                                            <div class="mt-2 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Bldg. No. and Name / Lot / Block</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Street / Subdivision / Village</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-3">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Barangay / City / Municipality</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-2">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Province</div>
                                                </div>
                                                <div class="col-span-12 md:col-span-1">
                                                    <input class="cf-input" type="text" />
                                                    <div class="cf-small text-center mt-1">Zip Code</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">II. PATIENT'S DATA</div>
                                    <div class="p-5 space-y-5">
                                        <div class="grid grid-cols-12 gap-4 items-start">
                                            <div class="col-span-12 lg:col-span-8">
                                                <div class="cf-label font-semibold">1. Name of Patient</div>
                                                <div class="mt-2 grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Last Name</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">First Name</div>
                                                    </div>
                                                    <div class="col-span-12 md:col-span-4">
                                                        <input class="cf-input" type="text" />
                                                        <div class="cf-small text-center mt-1">Middle Name</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-12 lg:col-span-4 space-y-4">
                                                <div>
                                                    <div class="cf-label font-semibold">2. PIN</div>
                                                    <div class="mt-2 cf-pin" aria-label="Patient PIN">
                                                        <?php for ($i = 0; $i < 12; $i++): ?>
                                                            <input type="text" maxlength="1" inputmode="numeric" />
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">3. Age</div>
                                                        <input class="cf-input mt-2" type="text" />
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-label font-semibold">4. Sex</div>
                                                        <div class="mt-2 flex flex-wrap gap-6">
                                                            <label class="cf-check"><input type="radio" name="cf4_sex" value="male" /> Male</label>
                                                            <label class="cf-check"><input type="radio" name="cf4_sex" value="female" /> Female</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">5. Chief Complaint</div>
                                                <textarea class="cf-input mt-2" rows="3"></textarea>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">6. Admitting Diagnosis</div>
                                                <textarea class="cf-input mt-2" rows="3"></textarea>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">7. Discharge Diagnosis</div>
                                                <textarea class="cf-input mt-2" rows="3"></textarea>
                                            </div>
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">8. Case Rate Code</div>
                                                <div class="mt-3 grid grid-cols-12 gap-3">
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-small text-gray-600 font-semibold">a. 1st Case Rate Code</div>
                                                        <input class="cf-input mt-2" type="text" />
                                                    </div>
                                                    <div class="col-span-12 md:col-span-6">
                                                        <div class="cf-small text-gray-600 font-semibold">b. 2nd Case Rate Code</div>
                                                        <input class="cf-input mt-2" type="text" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-12 gap-4">
                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">9. a. Date Admitted</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Admitted month"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Admitted day"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Admitted year"><?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">year</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 cf-label font-semibold">9. b. Time Admitted</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="hh" />
                                                        <span class="text-gray-500">:</span>
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="mm" />
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <label class="cf-check"><input type="radio" name="cf4_time_admit_ampm" value="AM" /> AM</label>
                                                        <label class="cf-check"><input type="radio" name="cf4_time_admit_ampm" value="PM" /> PM</label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-span-12 lg:col-span-6 cf-box p-4">
                                                <div class="cf-label font-semibold">10. a. Date Discharged</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharged month"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharged day"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Discharged year"><?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">year</div>
                                                    </div>
                                                </div>

                                                <div class="mt-4 cf-label font-semibold">10. b. Time Discharged</div>
                                                <div class="mt-2 flex flex-wrap items-center gap-3">
                                                    <div class="flex items-center gap-2">
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="hh" />
                                                        <span class="text-gray-500">:</span>
                                                        <input class="cf-input" style="max-width: 90px;" type="text" placeholder="mm" />
                                                    </div>
                                                    <div class="flex items-center gap-4">
                                                        <label class="cf-check"><input type="radio" name="cf4_time_discharge_ampm" value="AM" /> AM</label>
                                                        <label class="cf-check"><input type="radio" name="cf4_time_discharge_ampm" value="PM" /> PM</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">III. REASON FOR ADMISSION</div>
                                    <div class="p-5 space-y-6">
                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">1. History of Present Illness</div>
                                            <textarea class="cf-input mt-2" rows="5"></textarea>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">2. a. Pertinent Past Medical History</div>
                                            <textarea class="cf-input mt-2" rows="4"></textarea>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">2. b. OB/GYN History</div>
                                            <div class="mt-3 grid grid-cols-12 gap-3 items-start">
                                                <div class="col-span-12 md:col-span-7">
                                                    <div class="grid grid-cols-12 gap-3 items-center">
                                                        <div class="col-span-12 sm:col-span-6">
                                                            <div class="flex items-center gap-2">
                                                                <div class="text-sm font-semibold">G</div>
                                                                <input class="cf-input" style="max-width: 110px;" type="text" />
                                                                <div class="text-sm font-semibold">P</div>
                                                                <input class="cf-input" style="max-width: 110px;" type="text" />
                                                            </div>
                                                        </div>
                                                        <div class="col-span-12 sm:col-span-6">
                                                            <div class="cf-label font-semibold">LMP</div>
                                                            <div class="mt-2 grid grid-cols-3 gap-2 max-w-sm">
                                                                <div class="cf-date" aria-label="LMP month"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                                <div class="cf-date" aria-label="LMP day"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                                <div class="cf-date" aria-label="LMP year"><?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                            </div>
                                                            <div class="cf-small mt-1 text-gray-600">month / day / year</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-span-12 md:col-span-5">
                                                    <label class="cf-check"><input type="checkbox" /> NA</label>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">3. Pertinent Signs and Symptoms on Admission (tick applicable box/es)</div>
                                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                                                <label class="cf-check"><input type="checkbox" /> Altered mental sensorium</label>
                                                <label class="cf-check"><input type="checkbox" /> Abdominal cramp/pain</label>
                                                <label class="cf-check"><input type="checkbox" /> Anorexia</label>
                                                <label class="cf-check"><input type="checkbox" /> Bleeding gums</label>
                                                <label class="cf-check"><input type="checkbox" /> Body weakness</label>
                                                <label class="cf-check"><input type="checkbox" /> Blurring of vision</label>
                                                <label class="cf-check"><input type="checkbox" /> Chest pain/discomfort</label>
                                                <label class="cf-check"><input type="checkbox" /> Constipation</label>
                                                <label class="cf-check"><input type="checkbox" /> Cough</label>
                                                <label class="cf-check"><input type="checkbox" /> Diarrhea</label>
                                                <label class="cf-check"><input type="checkbox" /> Dizziness</label>
                                                <label class="cf-check"><input type="checkbox" /> Dysphagia</label>
                                                <label class="cf-check"><input type="checkbox" /> Dyspnea</label>
                                                <label class="cf-check"><input type="checkbox" /> Dysuria</label>
                                                <label class="cf-check"><input type="checkbox" /> Epistaxis</label>
                                                <label class="cf-check"><input type="checkbox" /> Fever</label>
                                                <label class="cf-check"><input type="checkbox" /> Frequency of urination</label>
                                                <label class="cf-check"><input type="checkbox" /> Headache</label>
                                                <label class="cf-check"><input type="checkbox" /> Hematemesis</label>
                                                <label class="cf-check"><input type="checkbox" /> Hematuria</label>
                                                <label class="cf-check"><input type="checkbox" /> Hemoptysis</label>
                                                <label class="cf-check"><input type="checkbox" /> Irritability</label>
                                                <label class="cf-check"><input type="checkbox" /> Jaundice</label>
                                                <label class="cf-check"><input type="checkbox" /> Lower extremity edema</label>
                                                <label class="cf-check"><input type="checkbox" /> Myalgia</label>
                                                <label class="cf-check"><input type="checkbox" /> Orthopnea</label>
                                                <div class="flex items-center gap-2">
                                                    <label class="cf-check"><input type="checkbox" /> Pain</label>
                                                    <input class="cf-input" type="text" placeholder="site" />
                                                </div>
                                                <label class="cf-check"><input type="checkbox" /> Palpitations</label>
                                                <label class="cf-check"><input type="checkbox" /> Seizures</label>
                                                <label class="cf-check"><input type="checkbox" /> Skin rashes</label>
                                                <label class="cf-check"><input type="checkbox" /> Stool, bloody/black tarry/mucoid</label>
                                                <label class="cf-check"><input type="checkbox" /> Sweating</label>
                                                <label class="cf-check"><input type="checkbox" /> Urgency</label>
                                                <label class="cf-check"><input type="checkbox" /> Vomiting</label>
                                                <label class="cf-check"><input type="checkbox" /> Weight loss</label>
                                                <div class="flex items-center gap-2">
                                                    <label class="cf-check"><input type="checkbox" /> Others</label>
                                                    <input class="cf-input" type="text" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">4. Referred from another health care institution (HCI)</div>
                                            <div class="mt-2 flex flex-wrap gap-6">
                                                <label class="cf-check"><input type="radio" name="cf4_referred" value="no" /> No</label>
                                                <label class="cf-check"><input type="radio" name="cf4_referred" value="yes" /> Yes</label>
                                            </div>
                                            <div class="mt-4 grid grid-cols-12 gap-3">
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-small text-gray-600 font-semibold">Specify Reason</div>
                                                    <input class="cf-input mt-2" type="text" />
                                                </div>
                                                <div class="col-span-12 md:col-span-6">
                                                    <div class="cf-small text-gray-600 font-semibold">Name of Originating HCI</div>
                                                    <input class="cf-input mt-2" type="text" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="grid grid-cols-12 gap-4 items-start">
                                                <div class="col-span-12 lg:col-span-9 space-y-4">
                                                    <div>
                                                        <div class="cf-label font-semibold">5. Physical Examination on Admission (Pertinent Findings per System)</div>
                                                        <div class="mt-3 grid grid-cols-12 gap-3">
                                                            <div class="col-span-12 md:col-span-6">
                                                                <div class="flex flex-wrap items-center gap-6">
                                                                    <div class="cf-label font-semibold">General Survey</div>
                                                                    <label class="cf-check"><input type="checkbox" /> Awake and alert</label>
                                                                    <label class="cf-check"><input type="checkbox" /> Altered sensorium</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-span-12 md:col-span-6">
                                                                <div class="cf-label font-semibold">Vital Signs</div>
                                                                <div class="mt-2 grid grid-cols-12 gap-2">
                                                                    <div class="col-span-6 sm:col-span-3"><input class="cf-input" type="text" placeholder="BP" /></div>
                                                                    <div class="col-span-6 sm:col-span-3"><input class="cf-input" type="text" placeholder="HR" /></div>
                                                                    <div class="col-span-6 sm:col-span-3"><input class="cf-input" type="text" placeholder="RR" /></div>
                                                                    <div class="col-span-6 sm:col-span-3"><input class="cf-input" type="text" placeholder="Temp" /></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div>
                                                        <div class="cf-label font-semibold">HEENT</div>
                                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                                                            <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                            <label class="cf-check"><input type="checkbox" /> Abnormal pupillary reaction</label>
                                                            <label class="cf-check"><input type="checkbox" /> Cervical lymphadenopathy</label>
                                                            <label class="cf-check"><input type="checkbox" /> Icteric sclerae</label>
                                                            <label class="cf-check"><input type="checkbox" /> Pale conjunctivae</label>
                                                            <label class="cf-check"><input type="checkbox" /> Sunken eyeballs</label>
                                                            <label class="cf-check"><input type="checkbox" /> Dry mucous membrane</label>
                                                            <label class="cf-check"><input type="checkbox" /> Sunken fontanelle</label>
                                                            <div class="flex items-center gap-2">
                                                                <span class="text-sm font-semibold">Others</span>
                                                                <input class="cf-input" type="text" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-12 lg:col-span-3">
                                                    <div class="cf-box p-4">
                                                        <div class="cf-label font-semibold">Height (cm)</div>
                                                        <input class="cf-input mt-2" type="text" />
                                                        <div class="mt-4 cf-label font-semibold">Weight (kg)</div>
                                                        <input class="cf-input mt-2" type="text" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-12 gap-4">
                                                <div class="col-span-12 lg:col-span-6">
                                                    <div class="cf-label font-semibold">CHEST/LUNGS</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                        <label class="cf-check"><input type="checkbox" /> Asymmetrical chest expansion</label>
                                                        <label class="cf-check"><input type="checkbox" /> Decreased breath sounds</label>
                                                        <label class="cf-check"><input type="checkbox" /> Wheezes</label>
                                                        <label class="cf-check"><input type="checkbox" /> Lump/s over breast(s)</label>
                                                        <label class="cf-check"><input type="checkbox" /> Rales/crackles/rhonchi</label>
                                                        <label class="cf-check"><input type="checkbox" /> Intercostal rib/clavicular retraction</label>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-semibold">Others</span>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-12 lg:col-span-6">
                                                    <div class="cf-label font-semibold">CVS</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                        <label class="cf-check"><input type="checkbox" /> Displaced apex beat</label>
                                                        <label class="cf-check"><input type="checkbox" /> Heaves and/or thrills</label>
                                                        <label class="cf-check"><input type="checkbox" /> Pericardial bulge</label>
                                                        <label class="cf-check"><input type="checkbox" /> Irregular rhythm</label>
                                                        <label class="cf-check"><input type="checkbox" /> Muffled heart sounds</label>
                                                        <label class="cf-check"><input type="checkbox" /> Murmur</label>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-semibold">Others</span>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-12 gap-4">
                                                <div class="col-span-12 lg:col-span-6">
                                                    <div class="cf-label font-semibold">ABDOMEN</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                        <label class="cf-check"><input type="checkbox" /> Abdominal rigidity</label>
                                                        <label class="cf-check"><input type="checkbox" /> Abdomen tenderness</label>
                                                        <label class="cf-check"><input type="checkbox" /> Hyperactive bowel sounds</label>
                                                        <label class="cf-check"><input type="checkbox" /> Palpable mass(es)</label>
                                                        <label class="cf-check"><input type="checkbox" /> Tympanitic/dull abdomen</label>
                                                        <label class="cf-check"><input type="checkbox" /> Uterine contraction</label>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-semibold">Others</span>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-12 lg:col-span-6">
                                                    <div class="cf-label font-semibold">GU (IE)</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                        <label class="cf-check"><input type="checkbox" /> Blood stained in exam finger</label>
                                                        <label class="cf-check"><input type="checkbox" /> Cervical dilatation</label>
                                                        <label class="cf-check"><input type="checkbox" /> Presence of abnormal discharge</label>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-semibold">Others</span>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4 grid grid-cols-12 gap-4">
                                                <div class="col-span-12 lg:col-span-6">
                                                    <div class="cf-label font-semibold">SKIN/EXTREMITIES</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                        <label class="cf-check"><input type="checkbox" /> Clubbing</label>
                                                        <label class="cf-check"><input type="checkbox" /> Cold clammy skin</label>
                                                        <label class="cf-check"><input type="checkbox" /> Cyanosis/mottled skin</label>
                                                        <label class="cf-check"><input type="checkbox" /> Edema/swelling</label>
                                                        <label class="cf-check"><input type="checkbox" /> Decreased mobility</label>
                                                        <label class="cf-check"><input type="checkbox" /> Pale nailbeds</label>
                                                        <label class="cf-check"><input type="checkbox" /> Poor skin turgor</label>
                                                        <label class="cf-check"><input type="checkbox" /> Rashes/petechiae</label>
                                                        <label class="cf-check"><input type="checkbox" /> Weak pulses</label>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-semibold">Others</span>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-span-12 lg:col-span-6">
                                                    <div class="cf-label font-semibold">NEURO-EXAM</div>
                                                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 gap-2">
                                                        <label class="cf-check"><input type="checkbox" /> Essentially normal</label>
                                                        <label class="cf-check"><input type="checkbox" /> Abnormal gait</label>
                                                        <label class="cf-check"><input type="checkbox" /> Abnormal position sense</label>
                                                        <label class="cf-check"><input type="checkbox" /> Abnormal/decreased sensation</label>
                                                        <label class="cf-check"><input type="checkbox" /> Abnormal reflex(es)</label>
                                                        <label class="cf-check"><input type="checkbox" /> Poor/altered memory</label>
                                                        <label class="cf-check"><input type="checkbox" /> Poor muscle tone/strength</label>
                                                        <label class="cf-check"><input type="checkbox" /> Poor coordination</label>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm font-semibold">Others</span>
                                                            <input class="cf-input" type="text" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">IV. COURSE IN THE WARD</div>
                                    <div class="p-5 space-y-4">
                                        <div class="flex flex-wrap items-center justify-between gap-4">
                                            <div class="cf-small text-gray-600 font-semibold">Attach photocopy of laboratory/imaging results</div>
                                            <label class="cf-check"><input type="checkbox" /> Check box if there is/are additional sheet(s).</label>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <div class="min-w-[980px]">
                                                <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-gray-600 mb-2">
                                                    <div class="col-span-2">Date</div>
                                                    <div class="col-span-10">Doctor's Order/Action</div>
                                                </div>
                                                <?php for ($r = 0; $r < 10; $r++): ?>
                                                    <div class="grid grid-cols-12 gap-2 mb-2">
                                                        <div class="col-span-2"><input class="cf-input" type="text" placeholder="mm/dd/yyyy" /></div>
                                                        <div class="col-span-10"><input class="cf-input" type="text" /></div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>

                                        <div class="cf-box p-4">
                                            <div class="cf-label font-semibold">SURGICAL PROCEDURE / RVS CODE (attach photocopy of OR technique)</div>
                                            <textarea class="cf-input mt-2" rows="3"></textarea>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">V. DRUGS / MEDICINES</div>
                                    <div class="p-5 space-y-4">
                                        <div class="flex justify-end">
                                            <label class="cf-check"><input type="checkbox" /> Check box if there is/are additional sheet(s).</label>
                                        </div>
                                        <div class="overflow-x-auto">
                                            <div class="min-w-[1100px]">
                                                <div class="grid grid-cols-12 gap-2 text-xs font-semibold text-gray-600 mb-2">
                                                    <div class="col-span-3">Generic Name</div>
                                                    <div class="col-span-3">Quantity/Dosage/Route/Frequency</div>
                                                    <div class="col-span-2">Total Cost</div>
                                                    <div class="col-span-2">Generic Name (cont)</div>
                                                    <div class="col-span-1">Qty/Dose (cont)</div>
                                                    <div class="col-span-1">Total Cost (cont)</div>
                                                </div>
                                                <?php for ($r = 0; $r < 6; $r++): ?>
                                                    <div class="grid grid-cols-12 gap-2 mb-2">
                                                        <div class="col-span-3"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-3"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-2"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-1"><input class="cf-input" type="text" /></div>
                                                        <div class="col-span-1"><input class="cf-input" type="text" /></div>
                                                    </div>
                                                <?php endfor; ?>
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">VI. OUTCOME OF TREATMENT</div>
                                    <div class="p-5 space-y-4">
                                        <div class="flex flex-wrap gap-6">
                                            <label class="cf-check"><input type="radio" name="cf4_outcome" value="improved" /> IMPROVED</label>
                                            <label class="cf-check"><input type="radio" name="cf4_outcome" value="recovered" /> RECOVERED</label>
                                            <label class="cf-check"><input type="radio" name="cf4_outcome" value="hama" /> HAMA/DAMA</label>
                                            <label class="cf-check"><input type="radio" name="cf4_outcome" value="expired" /> EXPIRED</label>
                                            <label class="cf-check"><input type="radio" name="cf4_outcome" value="absconded" /> ABSCONDED</label>
                                            <label class="cf-check"><input type="radio" name="cf4_outcome" value="transferred" /> TRANSFERRED</label>
                                        </div>
                                        <div class="grid grid-cols-12 gap-3 items-end">
                                            <div class="col-span-12 md:col-span-6">
                                                <div class="cf-label font-semibold">Specify reason</div>
                                                <input class="cf-input mt-2" type="text" />
                                            </div>
                                        </div>
                                    </div>
                                </section>

                                <section class="cf-section rounded-2xl overflow-hidden">
                                    <div class="cf-section-title">VII. CERTIFICATION OF HEALTH CARE PROFESSIONAL</div>
                                    <div class="p-5 space-y-5">
                                        <div class="cf-small text-gray-700">Certification of Attending Health Care Professional: I certify that the above information given in this form, including all attachments, are true and correct.</div>

                                        <div class="grid grid-cols-12 gap-4 items-end">
                                            <div class="col-span-12 lg:col-span-7">
                                                <div class="cf-label font-semibold">Signature over Printed Name of Attending Health Care Professional</div>
                                                <input class="cf-input mt-2" type="text" />
                                            </div>
                                            <div class="col-span-12 lg:col-span-5">
                                                <div class="cf-label font-semibold">Date Signed</div>
                                                <div class="mt-2 grid grid-cols-3 gap-3 max-w-sm">
                                                    <div>
                                                        <div class="cf-date" aria-label="Signed month"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">month</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Signed day"><?php for ($i = 0; $i < 2; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">day</div>
                                                    </div>
                                                    <div>
                                                        <div class="cf-date" aria-label="Signed year"><?php for ($i = 0; $i < 4; $i++): ?><input type="text" maxlength="1" inputmode="numeric" /><?php endfor; ?></div>
                                                        <div class="cf-small mt-1 text-center">year</div>
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

            if (safeGet('philhealthStepCf1Complete') !== '1' || safeGet('philhealthStepCf2Complete') !== '1' || safeGet('philhealthStepCf3Complete') !== '1') {
                window.location.href = 'philhealth-cf3.php?mode=edit';
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
                el.setAttribute('name', 'cf4_auto_' + (autoIdx++));
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
                    sessionStorage.setItem('philhealthCf4Draft', JSON.stringify(obj));
                } catch (e) {
                }
            };

            const scheduleSave = () => {
                if (t) clearTimeout(t);
                t = setTimeout(save, 150);
            };

            form.addEventListener('input', scheduleSave);
            form.addEventListener('change', scheduleSave);
            save();
        })();

        (function () {
            const form = document.getElementById('cf4Form');
            if (!form) return;

            const isEmbed = <?php echo $isEmbed ? 'true' : 'false'; ?>;

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

            const validateAll = () => {
                const form = document.querySelector('form');
                if (!form) return true;
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

            const readJson = (key) => {
                try {
                    return JSON.parse(sessionStorage.getItem(key) || 'null');
                } catch (e) {
                    return null;
                }
            };

            btn.addEventListener('click', async () => {
                btn.disabled = true;
                try {
                    if (!validateAll()) {
                        alert('Please complete all fields in CF4 before finishing.');
                        btn.disabled = false;
                        return;
                    }
                    try {
                        sessionStorage.setItem('philhealthStepCf4Complete', '1');
                    } catch (e2) {
                    }

                    const installRes = await fetch(API_BASE_URL + '/philhealth/install.php', {
                        headers: { 'Accept': 'application/json' },
                    });
                    const installJson = await installRes.json().catch(() => null);
                    if (!installRes.ok || !installJson || !installJson.ok) {
                        throw new Error((installJson && installJson.error) ? installJson.error : 'Failed to initialize database');
                    }

                    const payload = {
                        cf1: readJson('philhealthCf1Draft'),
                        cf2: readJson('philhealthCf2Draft'),
                        cf3: readJson('philhealthCf3Draft'),
                        cf4: readJson('philhealthCf4Draft'),
                    };

                    const res = await fetch(API_BASE_URL + '/philhealth/save.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'Failed to save');
                    }

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

                    fetch(API_BASE_URL + '/philhealth/claim_session.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ action: 'cancel' }),
                    }).catch(() => { });
                    window.location.href = 'philhealth-claims.php';
                } catch (e) {
                    alert(e && e.message ? e.message : 'Done failed');
                    btn.disabled = false;
                }
            });
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

            if (isEmbed) {
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
                showAiFillOverlay('Filling with AI…', 'Preparing CF4 information');
                try {
                    const seed = getAiSeed();
                    const { targets, fields } = collect();
                    const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'cf4', seed, fields }),
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
