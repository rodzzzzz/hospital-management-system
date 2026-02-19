<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <header class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Medical Records</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-500"></i>
                    </button>
                    <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                </div>
            </header>

            <div class="p-6">
                <section class="bg-white rounded-lg shadow p-4 mb-6">
                    <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div class="min-w-0">
                            <div class="text-xs text-gray-500">Selected Patient</div>
                            <div id="mrSelectedPatient" class="text-base font-semibold text-gray-900 truncate">None</div>
                            <div id="mrSelectedEncounter" class="text-sm text-gray-600 truncate">No encounter selected</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="mrChangePatientBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Change Patient</button>
                        </div>
                    </div>
                </section>

                <div id="mrDashboardView" class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Dashboard</h2>
                            <p class="text-sm text-gray-600 mt-1">Summary for selected patient and encounter.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-notes-medical text-blue-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-6">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs text-gray-500">ER Forms</div>
                            <div id="mrDashErCount" class="text-2xl font-semibold text-gray-900 mt-1">0</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs text-gray-500">Lab Results (Released)</div>
                            <div id="mrDashLabCount" class="text-2xl font-semibold text-gray-900 mt-1">0</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs text-gray-500">Resita</div>
                            <div id="mrDashResitaCount" class="text-2xl font-semibold text-gray-900 mt-1">0</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-xs text-gray-500">Invoices</div>
                            <div id="mrDashInvoiceCount" class="text-2xl font-semibold text-gray-900 mt-1">0</div>
                        </div>
                    </div>

                    <div id="mrDashHint" class="mt-4 text-sm text-gray-600"></div>
                </div>

                <div id="mrPatientsView" class="bg-white rounded-lg shadow p-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Patients</h2>
                            <p class="text-sm text-gray-600 mt-1">Select a patient to view encounters and full medical record.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                            <i class="fas fa-user-injured text-green-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Search Patient</label>
                        <input id="mrPatientSearch" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search patient name / patient code / PhilHealth PIN" autocomplete="off">
                        <div id="mrPatientResults" class="mt-2 border border-gray-200 rounded-lg overflow-hidden hidden"></div>
                        <div id="mrPatientResultsHint" class="mt-2 text-xs text-gray-500"></div>
                    </div>
                </div>

                <div id="mrEncountersView" class="bg-white rounded-lg shadow p-6 hidden">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Encounters</h2>
                            <p class="text-sm text-gray-500">View visits (ER/OPD/IPD/Pharmacy) grouped by encounter.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <select id="mrNewEncounterType" class="px-3 py-2 border border-gray-200 rounded-lg">
                                <option value="ER">ER</option>
                                <option value="OPD">OPD</option>
                                <option value="IPD">IPD</option>
                                <option value="PHARMACY">PHARMACY</option>
                            </select>
                            <button id="mrCreateEncounterBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Create Encounter</button>
                            <button id="mrRefreshEncountersBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Refresh</button>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Encounter</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Includes</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Started</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="mrEncountersTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="mrEncountersHint" class="mt-2 text-xs text-gray-500"></div>
                </div>

                <div id="mrErFormsView" class="bg-white rounded-lg shadow p-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">ER Forms</h2>
                            <p class="text-sm text-gray-600 mt-1">ER requests and related lab orders per encounter.</p>
                        </div>
                        <button id="mrRefreshErBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Refresh</button>
                    </div>

                    <div class="overflow-x-auto mt-6 border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="mrErTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="mrErHint" class="mt-2 text-xs text-gray-500"></div>
                </div>

                <div id="mrLabResultsView" class="bg-white rounded-lg shadow p-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Lab Results</h2>
                            <p class="text-sm text-gray-600 mt-1">Lab results released per encounter.</p>
                        </div>
                        <button id="mrRefreshLabBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Refresh</button>
                    </div>

                    <div class="overflow-x-auto mt-6 border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Released</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="mrLabTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="mrLabHint" class="mt-2 text-xs text-gray-500"></div>
                </div>

                <div id="mrResitaView" class="bg-white rounded-lg shadow p-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Receipt / Resita</h2>
                            <p class="text-sm text-gray-600 mt-1">Pharmacy resita and generated charges per encounter.</p>
                        </div>
                        <button id="mrRefreshResitaBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Refresh</button>
                    </div>

                    <div class="overflow-x-auto mt-6 border border-gray-200 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Receipt</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prescribed By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="mrResitaTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="mrResitaHint" class="mt-2 text-xs text-gray-500"></div>
                </div>

                <div id="mrBillingView" class="bg-white rounded-lg shadow p-6 hidden">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Cashier / Billing</h2>
                            <p class="text-sm text-gray-600 mt-1">Invoices and payments per encounter.</p>
                        </div>
                        <button id="mrRefreshBillingBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Refresh</button>
                    </div>

                    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 text-sm font-semibold text-gray-800">Invoices</div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Paid</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Balance</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="mrInvoicesTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                        </div>

                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 text-sm font-semibold text-gray-800">Payments</div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-white">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Change</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        </tr>
                                    </thead>
                                    <tbody id="mrPaymentsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div id="mrBillingHint" class="mt-2 text-xs text-gray-500"></div>
                </div>
            </div>
        </main>
    </div>

    <div id="mrLabRequestModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Lab Request Details</h3>
                    <div id="mrLabRequestModalSub" class="text-xs text-gray-500 mt-1"></div>
                </div>
                <button type="button" class="w-9 h-9 rounded-lg hover:bg-gray-100" onclick="mrToggleModal('mrLabRequestModal', false)">
                    <i class="fas fa-xmark text-gray-600"></i>
                </button>
            </div>
            <div class="px-6 py-4">
                <div id="mrLabRequestModalBody" class="text-sm text-gray-700"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="mrToggleModal('mrLabRequestModal', false)">Close</button>
            </div>
        </div>
    </div>

    <div id="mrResitModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-3xl">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Receipt Details</h3>
                    <div id="mrResitModalSub" class="text-xs text-gray-500 mt-1"></div>
                </div>
                <button type="button" class="w-9 h-9 rounded-lg hover:bg-gray-100" onclick="mrToggleModal('mrResitModal', false)">
                    <i class="fas fa-xmark text-gray-600"></i>
                </button>
            </div>
            <div class="px-6 py-4">
                <div id="mrResitModalBody" class="text-sm text-gray-700"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="mrToggleModal('mrResitModal', false)">Close</button>
            </div>
        </div>
    </div>

    <div id="mrInvoiceModal" class="fixed inset-0 bg-gray-900/50 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-4xl">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Invoice Details</h3>
                    <div id="mrInvoiceModalSub" class="text-xs text-gray-500 mt-1"></div>
                </div>
                <button type="button" class="w-9 h-9 rounded-lg hover:bg-gray-100" onclick="mrToggleModal('mrInvoiceModal', false)">
                    <i class="fas fa-xmark text-gray-600"></i>
                </button>
            </div>
            <div class="px-6 py-4">
                <div id="mrInvoiceModalBody" class="text-sm text-gray-700"></div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="mrToggleModal('mrInvoiceModal', false)">Close</button>
            </div>
        </div>
    </div>

    <script>
        const MR_STORAGE_PATIENT = 'mr_selected_patient';
        const MR_STORAGE_ENCOUNTER = 'mr_selected_encounter';

        const mrState = {
            patient: null,
            encounter: null,
        };

        const mrCache = {
            patients: new Map(),
            encounters: new Map(),
            resits: new Map(),
        };

        function escapeHtml(str) {
            return (str ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function debounce(fn, wait) {
            let t = null;
            return function (...args) {
                if (t) window.clearTimeout(t);
                t = window.setTimeout(() => fn.apply(this, args), wait);
            };
        }

        function fmtDateTime(s) {
            const raw = (s ?? '').toString();
            if (!raw) return '';
            const d = new Date(raw);
            if (!isFinite(d.getTime())) return raw;
            const pad = (n) => String(n).padStart(2, '0');
            return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()) + ' ' + pad(d.getHours()) + ':' + pad(d.getMinutes());
        }

        function money(v) {
            const n = Number(v);
            const safe = isFinite(n) ? n : 0;
            return '₱' + safe.toFixed(2);
        }

        function mrToggleModal(id, show) {
            const el = document.getElementById(id);
            if (!el) return;
            const open = !!show;
            el.classList.toggle('hidden', !open);
            el.classList.toggle('flex', open);
        }

        function mrGetSelectedPatient() {
            try {
                const raw = sessionStorage.getItem(MR_STORAGE_PATIENT);
                if (!raw) return null;
                const obj = JSON.parse(raw);
                if (!obj || !obj.id) return null;
                return obj;
            } catch (e) {
                return (mrState.patient && mrState.patient.id) ? mrState.patient : null;
            }
        }

        function mrSetSelectedPatient(p) {
            mrState.patient = p ? p : null;
            try {
                if (!p) {
                    sessionStorage.removeItem(MR_STORAGE_PATIENT);
                    return;
                }
                sessionStorage.setItem(MR_STORAGE_PATIENT, JSON.stringify(p));
            } catch (e) { }
        }

        function mrGetSelectedEncounter() {
            return (mrState.encounter && mrState.encounter.id) ? mrState.encounter : null;
        }

        function mrSetSelectedEncounter(enc) {
            mrState.encounter = enc ? enc : null;
        }

        async function mrResolveLatestEncounter(patientId) {
            const pid = Number(patientId || 0);
            if (!pid) return null;

            const url = 'api/encounters/list.php?patient_id=' + encodeURIComponent(String(pid)) + '&status=open';
            const { res, json } = await mrFetchJson(url);
            if (res.ok && json && json.ok && Array.isArray(json.encounters) && json.encounters.length > 0) {
                const e = json.encounters[0];
                if (e && e.id) {
                    mrCache.encounters.set(Number(e.id), e);
                    return e;
                }
            }

            const url2 = 'api/encounters/list.php?patient_id=' + encodeURIComponent(String(pid));
            const { res: res2, json: json2 } = await mrFetchJson(url2);
            if (res2.ok && json2 && json2.ok && Array.isArray(json2.encounters) && json2.encounters.length > 0) {
                const e = json2.encounters[0];
                if (e && e.id) {
                    mrCache.encounters.set(Number(e.id), e);
                    return e;
                }
            }
            return null;
        }

        async function mrResolveLatestEncounterForTab(patientId, tab) {
            const pid = Number(patientId || 0);
            if (!pid) return null;
            const key = (tab ?? '').toString().trim().toLowerCase();

            const { res, json } = await mrFetchJson('api/encounters/list.php?patient_id=' + encodeURIComponent(String(pid)));
            if (res.ok && json && json.ok && Array.isArray(json.encounters) && json.encounters.length > 0) {
                const rows = json.encounters;
                const pick = (fn) => {
                    try { return rows.find(fn) || null; } catch (e) { return null; }
                };

                let found = null;
                if (key === 'er-forms') {
                    found = pick(e => (Number(e?.er_forms_count || 0) || 0) > 0);
                } else if (key === 'lab-results') {
                    found = pick(e => (Number(e?.lab_results_count || 0) || 0) > 0)
                        || pick(e => (Number(e?.er_forms_count || 0) || 0) > 0);
                } else if (key === 'resita') {
                    found = pick(e => (Number(e?.resits_count || 0) || 0) > 0);
                } else if (key === 'billing') {
                    found = pick(e => ((Number(e?.invoices_count || 0) || 0) + (Number(e?.payments_count || 0) || 0) + (Number(e?.charges_count || 0) || 0)) > 0);
                }

                if (found && found.id) {
                    mrCache.encounters.set(Number(found.id), found);
                    return found;
                }
            }

            if (key === 'er-forms' || key === 'lab-results') {
                return await mrResolveLatestEncounterByType(pid, 'ER') || await mrResolveLatestEncounter(pid);
            }
            if (key === 'resita') {
                return await mrResolveLatestEncounterByType(pid, 'PHARMACY') || await mrResolveLatestEncounter(pid);
            }
            if (key === 'billing') {
                return await mrResolveLatestEncounter(pid);
            }
            return await mrResolveLatestEncounter(pid);
        }

        async function mrEnsureLatestEncounterForTab(tab) {
            const p = mrGetSelectedPatient();
            if (!p || !p.id) return null;

            const latest = await mrResolveLatestEncounterForTab(p.id, tab);
            const current = mrGetSelectedEncounter();
            const latestId = latest ? Number(latest.id) : null;
            const currentId = current ? Number(current.id) : null;
            if (latestId && latestId !== currentId) {
                mrSetSelectedEncounter(latest);
                mrUpdateHeader();
            }
            if (!latestId && currentId) {
                return null;
            }
            return latest;
        }

        async function mrResolveLatestEncounterByType(patientId, type) {
            const pid = Number(patientId || 0);
            if (!pid) return null;
            const t = (type ?? '').toString().trim().toUpperCase();
            if (!t) return null;

            const url = 'api/encounters/list.php?patient_id=' + encodeURIComponent(String(pid)) + '&status=open&type=' + encodeURIComponent(t);
            const { res, json } = await mrFetchJson(url);
            if (res.ok && json && json.ok && Array.isArray(json.encounters) && json.encounters.length > 0) {
                const e = json.encounters[0];
                if (e && e.id) {
                    mrCache.encounters.set(Number(e.id), e);
                    return e;
                }
            }

            const url2 = 'api/encounters/list.php?patient_id=' + encodeURIComponent(String(pid)) + '&type=' + encodeURIComponent(t);
            const { res: res2, json: json2 } = await mrFetchJson(url2);
            if (res2.ok && json2 && json2.ok && Array.isArray(json2.encounters) && json2.encounters.length > 0) {
                const e = json2.encounters[0];
                if (e && e.id) {
                    mrCache.encounters.set(Number(e.id), e);
                    return e;
                }
            }
            return null;
        }

        async function mrEnsureLatestEncounterByType(type) {
            const p = mrGetSelectedPatient();
            if (!p || !p.id) return null;

            const latest = await mrResolveLatestEncounterByType(p.id, type);
            const current = mrGetSelectedEncounter();
            const latestId = latest ? Number(latest.id) : null;
            const currentId = current ? Number(current.id) : null;
            if (latestId && latestId !== currentId) {
                mrSetSelectedEncounter(latest);
                mrUpdateHeader();
            }
            if (!latestId && currentId) {
                return null;
            }
            return latest;
        }

        async function mrEnsureLatestEncounter() {
            const p = mrGetSelectedPatient();
            if (!p || !p.id) return null;

            const latest = await mrResolveLatestEncounter(p.id);
            const current = mrGetSelectedEncounter();
            const latestId = latest ? Number(latest.id) : null;
            const currentId = current ? Number(current.id) : null;
            if (latestId && latestId !== currentId) {
                mrSetSelectedEncounter(latest);
                mrUpdateHeader();
            }
            if (!latestId && currentId) {
                mrSetSelectedEncounter(null);
                mrUpdateHeader();
            }
            return latest;
        }

        function mrUpdateHeader() {
            const p = mrGetSelectedPatient();
            const e = mrGetSelectedEncounter();
            const pEl = document.getElementById('mrSelectedPatient');
            const eEl = document.getElementById('mrSelectedEncounter');
            if (pEl) {
                if (!p) {
                    pEl.textContent = 'None';
                } else {
                    const code = (p.patient_code ?? ('P-' + String(p.id))).toString();
                    pEl.textContent = (p.full_name ?? '').toString() + ' (' + code + ')';
                }
            }
            if (eEl) {
                if (!e) {
                    eEl.textContent = 'No encounter found';
                } else {
                    const no = (e.encounter_no ?? ('ENC-' + String(e.id))).toString();
                    const type = (e.type ?? '').toString();
                    const status = (e.status ?? '').toString();
                    const started = fmtDateTime(e.started_at ?? e.created_at ?? '');
                    eEl.textContent = no + (type ? (' • ' + type) : '') + (status ? (' • ' + status) : '') + (started ? (' • ' + started) : '');
                }
            }
        }

        async function mrFetchJson(url, options) {
            const res = await fetch(url, Object.assign({ headers: { 'Accept': 'application/json' } }, options || {}));
            const json = await res.json().catch(() => null);
            return { res, json };
        }

        function mrRequirePatientOrHint(hintElId) {
            const p = mrGetSelectedPatient();
            const hint = document.getElementById(hintElId);
            if (!p) {
                if (hint) hint.textContent = 'Select a patient first.';
                return null;
            }
            if (hint) hint.textContent = '';
            return p;
        }

        function mrRequireEncounterOrHint(hintElId) {
            const e = mrGetSelectedEncounter();
            const hint = document.getElementById(hintElId);
            if (!e) {
                if (hint) hint.textContent = 'No encounter found for this patient yet.';
                return null;
            }
            if (hint) hint.textContent = '';
            return e;
        }

        async function mrLoadPatientsDropdown(q) {
            const resultsEl = document.getElementById('mrPatientResults');
            const hintEl = document.getElementById('mrPatientResultsHint');
            if (!resultsEl) return;

            const query = (q ?? '').toString().trim();
            if (query.length === 0) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                if (hintEl) hintEl.textContent = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 bg-white">Searching...</div>';
            if (hintEl) hintEl.textContent = '';

            const { res, json } = await mrFetchJson('api/patients/list.php?q=' + encodeURIComponent(query));
            if (!res.ok || !json || !json.ok) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = '<div class="px-4 py-3 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            rows.forEach(p => {
                if (p && p.id) mrCache.patients.set(Number(p.id), p);
            });

            resultsEl.innerHTML = rows.map(p => {
                const id = Number(p.id);
                const name = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(id)));
                const sex = escapeHtml(p.sex || '');
                return `
                    <button type="button" class="w-full text-left px-4 py-3 hover:bg-gray-50" data-id="${id}">
                        <div class="text-sm font-semibold text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${code}${sex ? (' • ' + sex) : ''}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const id = Number(btn.getAttribute('data-id') || '0');
                    const p = mrCache.patients.get(id) || { id };
                    mrSetSelectedPatient(p);
                    mrSetSelectedEncounter(null);
                    mrUpdateHeader();
                    resultsEl.classList.add('hidden');
                    resultsEl.innerHTML = '';
                    const input = document.getElementById('mrPatientSearch');
                    if (input) input.value = '';
                    if (hintEl) hintEl.textContent = '';
                    const latest = await mrResolveLatestEncounter(p.id);
                    mrSetSelectedEncounter(latest);
                    mrUpdateHeader();
                    await mrLoadEncounters();
                    await mrLoadDashboard();
                    if (!window.location.hash || window.location.hash === '#patients') {
                        window.location.hash = '#encounters';
                    }
                });
            });
        }

        async function mrLoadEncounters() {
            const tbody = document.getElementById('mrEncountersTbody');
            const hint = document.getElementById('mrEncountersHint');
            if (!tbody) return;

            const p = mrGetSelectedPatient();
            if (!p) {
                tbody.innerHTML = '';
                if (hint) hint.textContent = 'Select a patient first.';
                return;
            }

            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-6 text-sm text-gray-500">Loading...</td></tr>';
            if (hint) hint.textContent = '';

            const { res, json } = await mrFetchJson('api/encounters/list.php?patient_id=' + encodeURIComponent(String(p.id)));
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                if (hint) hint.textContent = 'Unable to load encounters.';
                return;
            }

            const rows = Array.isArray(json.encounters) ? json.encounters : [];
            rows.forEach(e => {
                if (e && e.id) mrCache.encounters.set(Number(e.id), e);
            });

            const current = mrGetSelectedEncounter();
            const currentId = current ? Number(current.id) : null;
            if (rows.length > 0) {
                const latestRowId = Number(rows[0].id);
                if (!currentId || currentId !== latestRowId) {
                    mrSetSelectedEncounter(rows[0]);
                    mrUpdateHeader();
                }
            } else if (currentId) {
                mrSetSelectedEncounter(null);
                mrUpdateHeader();
            }

            if (rows.length === 0) {
                tbody.innerHTML = '';
                if (hint) hint.textContent = 'No encounters found. Create a new encounter to begin tracking.';
                return;
            }

            const selected = mrGetSelectedEncounter();
            const selectedId = selected ? Number(selected.id) : null;

            tbody.innerHTML = rows.map(e => {
                const id = Number(e.id);
                const no = escapeHtml(e.encounter_no || ('ENC-' + String(id)));
                const type = escapeHtml(e.type || '');
                const erCnt = Number(e.er_forms_count || 0) || 0;
                const labCnt = Number(e.lab_results_count || 0) || 0;
                const resitCnt = Number(e.resits_count || 0) || 0;
                const billingCnt = (Number(e.invoices_count || 0) || 0) + (Number(e.payments_count || 0) || 0) + (Number(e.charges_count || 0) || 0);
                const status = escapeHtml(e.status || '');
                const started = escapeHtml(fmtDateTime(e.started_at || e.created_at || ''));
                const isSel = selectedId !== null && id === selectedId;

                const chips = [
                    erCnt > 0 ? `<span class="px-2 py-0.5 text-xs rounded-full bg-blue-50 text-blue-700 border border-blue-100">ER Forms (${erCnt})</span>` : '',
                    labCnt > 0 ? `<span class="px-2 py-0.5 text-xs rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">Lab Results (${labCnt})</span>` : '',
                    resitCnt > 0 ? `<span class="px-2 py-0.5 text-xs rounded-full bg-purple-50 text-purple-700 border border-purple-100">Resita (${resitCnt})</span>` : '',
                    billingCnt > 0 ? `<span class="px-2 py-0.5 text-xs rounded-full bg-emerald-50 text-emerald-700 border border-emerald-100">Billing (${billingCnt})</span>` : '',
                ].filter(Boolean).join(' ');

                return `
                    <tr class="hover:bg-gray-50${isSel ? ' bg-blue-50' : ''}">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">${no}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${type}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${chips || '<span class="text-xs text-gray-400">-</span>'}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${status}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${started}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                ${String(e.status || '').toLowerCase() === 'open' ? `<button type="button" class="px-3 py-1.5 text-sm border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-100" onclick="mrCloseEncounter(${id})">Close</button>` : ''}
                                <button type="button" class="px-3 py-1.5 text-sm border border-red-200 text-red-700 rounded-lg hover:bg-red-50" onclick="mrDeleteEncounter(${id})">Delete</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function mrCloseEncounter(id) {
            const encId = Number(id || 0);
            if (!encId) return;

            const confirmMsg = 'Close this encounter? It will remain in history but no longer be open.';
            if (!window.confirm(confirmMsg)) return;

            const hint = document.getElementById('mrEncountersHint');
            if (hint) hint.textContent = 'Closing...';

            const { res, json } = await mrFetchJson('api/encounters/close.php', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ encounter_id: encId }),
            });

            if (!res.ok || !json || !json.ok) {
                if (hint) hint.textContent = (json && json.error) ? String(json.error) : 'Unable to close encounter.';
                return;
            }

            const updated = json.encounter || null;
            if (updated && updated.id) {
                mrCache.encounters.set(Number(updated.id), updated);
                const selected = mrGetSelectedEncounter();
                if (selected && Number(selected.id) === encId) {
                    mrSetSelectedEncounter(updated);
                    mrUpdateHeader();
                }
            }

            if (hint) hint.textContent = 'Encounter closed.';
            await mrLoadEncounters();
        }

        async function mrDeleteEncounter(id) {
            const encId = Number(id || 0);
            if (!encId) return;

            const confirmMsg = 'Delete this encounter? This will only work if there are no linked records (lab requests, resits, invoices/payments).';
            if (!window.confirm(confirmMsg)) return;

            const hint = document.getElementById('mrEncountersHint');
            if (hint) hint.textContent = 'Deleting...';

            const { res, json } = await mrFetchJson('api/encounters/delete.php', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ encounter_id: encId }),
            });

            if (!res.ok || !json || !json.ok) {
                if (hint) hint.textContent = (json && json.error) ? String(json.error) : 'Unable to delete encounter.';
                return;
            }

            const selected = mrGetSelectedEncounter();
            if (selected && Number(selected.id) === encId) {
                mrSetSelectedEncounter(null);
                mrUpdateHeader();
                await mrLoadDashboard();
            }

            if (hint) hint.textContent = 'Encounter deleted.';
            await mrLoadEncounters();
        }

        async function mrCreateEncounter() {
            const hint = document.getElementById('mrEncountersHint');
            const p = mrGetSelectedPatient();
            if (!p) {
                if (hint) hint.textContent = 'Select a patient first.';
                return;
            }
            if (hint) hint.textContent = '';

            const type = (document.getElementById('mrNewEncounterType')?.value ?? 'OPD').toString();
            const { res, json } = await mrFetchJson('api/encounters/create.php', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json' },
                body: JSON.stringify({ patient_id: p.id, type }),
            });
            if (!res.ok || !json || !json.ok) {
                if (hint) hint.textContent = (json && json.error) ? String(json.error) : 'Unable to create encounter.';
                return;
            }
            const enc = json.encounter || {};
            if (enc && enc.id) mrCache.encounters.set(Number(enc.id), enc);
            mrSetSelectedEncounter(enc);
            mrUpdateHeader();
            await mrLoadEncounters();
            await mrLoadDashboard();
            await mrLoadErForms();
            await mrLoadLabResults();
            await mrLoadResita();
            await mrLoadBilling();
        }

        async function mrOpenLabRequest(id) {
            const sub = document.getElementById('mrLabRequestModalSub');
            const body = document.getElementById('mrLabRequestModalBody');
            if (!body) return;

            mrToggleModal('mrLabRequestModal', true);
            if (sub) sub.textContent = 'Loading...';
            body.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';

            const { res, json } = await mrFetchJson('api/lab/get_request.php?id=' + encodeURIComponent(String(id)));
            if (!res.ok || !json || !json.ok) {
                if (sub) sub.textContent = '';
                body.innerHTML = '<div class="text-sm text-red-600">Unable to load request.</div>';
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];
            const reqNo = escapeHtml(r.request_no || ('REQ-' + String(r.id || id)));
            const status = escapeHtml(r.status || '');
            const created = escapeHtml(fmtDateTime(r.created_at || ''));
            const complaint = escapeHtml(r.chief_complaint || '');
            const encId = (r.encounter_id ?? '').toString().trim();
            const encLabel = encId ? ('Encounter ID: ' + encId) : '';
            if (sub) sub.textContent = reqNo + (status ? (' • ' + status) : '') + (created ? (' • ' + created) : '') + (encLabel ? (' • ' + encLabel) : '');

            const rows = items.map(it => {
                const name = escapeHtml(it.test_name || '');
                const result = escapeHtml(it.result_text || '');
                const releasedAt = escapeHtml(fmtDateTime(it.released_at || ''));
                const releasedBy = escapeHtml(it.released_by || '');
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${result || '<span class="text-gray-400">No result</span>'}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${releasedAt || ''}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${releasedBy || ''}</td>
                    </tr>
                `;
            }).join('');

            body.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Chief Complaint</div>
                        <div class="text-sm text-gray-900 mt-1">${complaint || '<span class="text-gray-400">-</span>'}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Source Unit</div>
                        <div class="text-sm text-gray-900 mt-1">${escapeHtml(r.source_unit || '')}</div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Released</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Released By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${rows || '<tr><td colspan="4" class="px-4 py-6 text-sm text-gray-500">No items</td></tr>'}
                        </tbody>
                    </table>
                </div>
            `;
        }

        function mrOpenResit(id) {
            const resit = mrCache.resits.get(Number(id));
            const sub = document.getElementById('mrResitModalSub');
            const body = document.getElementById('mrResitModalBody');
            if (!body) return;

            mrToggleModal('mrResitModal', true);
            if (!resit) {
                if (sub) sub.textContent = '';
                body.innerHTML = '<div class="text-sm text-gray-500">Receipt not found in current list.</div>';
                return;
            }

            const rid = Number(resit.id);
            const created = escapeHtml(fmtDateTime(resit.created_at || ''));
            const prescribedBy = escapeHtml(resit.prescribed_by || '');
            if (sub) sub.textContent = 'RECEIPT-' + String(rid) + (created ? (' • ' + created) : '');

            const items = Array.isArray(resit.items) ? resit.items : [];
            const rows = items.map(it => {
                const name = escapeHtml(it.name || '');
                const qty = escapeHtml(it.qty || '');
                const sig = escapeHtml(it.sig || '');
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${qty}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${sig}</td>
                    </tr>
                `;
            }).join('');

            body.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Prescribed By</div>
                        <div class="text-sm text-gray-900 mt-1">${prescribedBy || '<span class="text-gray-400">-</span>'}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Notes</div>
                        <div class="text-sm text-gray-900 mt-1">${escapeHtml(resit.notes || '') || '<span class="text-gray-400">-</span>'}</div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Medicine</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Instructions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${rows || '<tr><td colspan="3" class="px-4 py-6 text-sm text-gray-500">No items</td></tr>'}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function mrOpenInvoice(id) {
            const sub = document.getElementById('mrInvoiceModalSub');
            const body = document.getElementById('mrInvoiceModalBody');
            if (!body) return;

            mrToggleModal('mrInvoiceModal', true);
            if (sub) sub.textContent = 'Loading...';
            body.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';

            const { res, json } = await mrFetchJson('api/cashier/get_invoice.php?invoice_id=' + encodeURIComponent(String(id)));
            if (!res.ok || !json || !json.ok) {
                if (sub) sub.textContent = '';
                body.innerHTML = '<div class="text-sm text-red-600">Unable to load invoice.</div>';
                return;
            }

            const inv = json.invoice || {};
            const items = Array.isArray(inv.items) ? inv.items : [];

            const invId = Number(inv.id || id);
            const created = escapeHtml(fmtDateTime(inv.created_at || ''));
            const status = escapeHtml(inv.status || '');
            if (sub) sub.textContent = 'INV-' + String(invId) + (status ? (' • ' + status) : '') + (created ? (' • ' + created) : '');

            const itemRows = items.map(it => {
                const desc = escapeHtml(it.description || '');
                const qty = Number(it.qty || 0);
                const unit = money(it.unit_price || 0);
                const subTotal = money(it.subtotal || 0);
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">${desc}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 text-right">${qty}</td>
                        <td class="px-4 py-3 text-sm text-gray-700 text-right">${unit}</td>
                        <td class="px-4 py-3 text-sm text-gray-900 font-semibold text-right">${subTotal}</td>
                    </tr>
                `;
            }).join('');

            body.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Total</div>
                        <div class="text-base font-semibold text-gray-900 mt-1">${money(inv.total || 0)}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Paid</div>
                        <div class="text-base font-semibold text-gray-900 mt-1">${money(inv.paid || 0)}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Balance</div>
                        <div class="text-base font-semibold text-gray-900 mt-1">${money(inv.balance || 0)}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-xs text-gray-500">Last Change</div>
                        <div class="text-base font-semibold text-gray-900 mt-1">${money(inv.last_change || 0)}</div>
                    </div>
                </div>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${itemRows || '<tr><td colspan="4" class="px-4 py-6 text-sm text-gray-500">No items</td></tr>'}
                        </tbody>
                    </table>
                </div>
            `;
        }

        async function mrLoadErForms() {
            const tbody = document.getElementById('mrErTbody');
            const p = mrRequirePatientOrHint('mrErHint');
            if (!tbody) return;
            if (!p) {
                tbody.innerHTML = '';
                return;
            }
            await mrEnsureLatestEncounterForTab('er-forms');
            const e = mrRequireEncounterOrHint('mrErHint');
            if (!e) {
                tbody.innerHTML = '';
                return;
            }

            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-6 text-sm text-gray-500">Loading...</td></tr>';
            const url = 'api/lab/list_requests.php?mode=er&patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const { res, json } = await mrFetchJson(url);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                const hint = document.getElementById('mrErHint');
                if (hint) hint.textContent = 'Unable to load ER forms.';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            if (rows.length === 0) {
                tbody.innerHTML = '';
                const hint = document.getElementById('mrErHint');
                if (hint) hint.textContent = 'No ER forms found for this encounter.';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const id = Number(r.id);
                const no = escapeHtml(r.request_no || ('REQ-' + String(id)));
                const tests = escapeHtml(r.tests || '');
                const status = escapeHtml(r.status || '');
                const created = escapeHtml(fmtDateTime(r.created_at || ''));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">${no}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${tests}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${status}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${created}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-100" onclick="mrOpenLabRequest(${id})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function mrLoadLabResults() {
            const tbody = document.getElementById('mrLabTbody');
            const p = mrRequirePatientOrHint('mrLabHint');
            if (!tbody) return;
            if (!p) {
                tbody.innerHTML = '';
                return;
            }
            await mrEnsureLatestEncounterForTab('lab-results');
            const e = mrRequireEncounterOrHint('mrLabHint');
            if (!e) {
                tbody.innerHTML = '';
                return;
            }

            tbody.innerHTML = '<tr><td colspan="4" class="px-6 py-6 text-sm text-gray-500">Loading...</td></tr>';
            const url = 'api/lab/list_requests.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const { res, json } = await mrFetchJson(url);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                const hint = document.getElementById('mrLabHint');
                if (hint) hint.textContent = 'Unable to load lab results.';
                return;
            }

            const all = Array.isArray(json.requests) ? json.requests : [];
            const rows = all.filter(r => (r.released_at ?? '').toString().trim() !== '');

            if (rows.length === 0) {
                tbody.innerHTML = '';
                const hint = document.getElementById('mrLabHint');
                if (hint) hint.textContent = 'No released lab results found for this encounter.';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const id = Number(r.id);
                const no = escapeHtml(r.request_no || ('REQ-' + String(id)));
                const tests = escapeHtml(r.tests || '');
                const released = escapeHtml(fmtDateTime(r.released_at || ''));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">${no}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${tests}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${released}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-100" onclick="mrOpenLabRequest(${id})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function mrLoadResita() {
            const tbody = document.getElementById('mrResitaTbody');
            const p = mrRequirePatientOrHint('mrResitaHint');
            if (!tbody) return;
            if (!p) {
                tbody.innerHTML = '';
                return;
            }
            await mrEnsureLatestEncounterForTab('resita');
            const e = mrRequireEncounterOrHint('mrResitaHint');
            if (!e) {
                tbody.innerHTML = '';
                return;
            }

            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-6 text-sm text-gray-500">Loading...</td></tr>';
            const url = 'api/pharmacy/list_resits.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const { res, json } = await mrFetchJson(url);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                const hint = document.getElementById('mrResitaHint');
                if (hint) hint.textContent = (json && json.error) ? String(json.error) : 'Unable to load resita.';
                return;
            }

            const rows = Array.isArray(json.resits) ? json.resits : [];
            mrCache.resits.clear();
            rows.forEach(r => {
                if (r && r.id) mrCache.resits.set(Number(r.id), r);
            });

            if (rows.length === 0) {
                tbody.innerHTML = '';
                const hint = document.getElementById('mrResitaHint');
                if (hint) hint.textContent = 'No resita found for this encounter.';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const id = Number(r.id);
                const prescribedBy = escapeHtml(r.prescribed_by || '');
                const items = Array.isArray(r.items) ? r.items : [];
                const itemText = escapeHtml(items.map(it => (it.name || '') + (it.qty ? (' x' + it.qty) : '')).slice(0, 3).join(', '));
                const created = escapeHtml(fmtDateTime(r.created_at || ''));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">RECEIPT-${id}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${prescribedBy}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${itemText}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${created}</td>
                        <td class="px-6 py-4 text-right">
                            <button type="button" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-100" onclick="mrOpenResit(${id})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function mrLoadBilling() {
            const invTbody = document.getElementById('mrInvoicesTbody');
            const payTbody = document.getElementById('mrPaymentsTbody');
            const p = mrRequirePatientOrHint('mrBillingHint');
            if (!invTbody || !payTbody) return;
            if (!p) {
                invTbody.innerHTML = '';
                payTbody.innerHTML = '';
                return;
            }
            await mrEnsureLatestEncounterForTab('billing');
            const e = mrRequireEncounterOrHint('mrBillingHint');
            if (!e) {
                invTbody.innerHTML = '';
                payTbody.innerHTML = '';
                return;
            }

            invTbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-sm text-gray-500">Loading...</td></tr>';
            payTbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-sm text-gray-500">Loading...</td></tr>';

            const invUrl = 'api/cashier/list_invoices.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const payUrl = 'api/cashier/list_payments.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));

            const invResp = await mrFetchJson(invUrl);
            const payResp = await mrFetchJson(payUrl);

            if (!invResp.res.ok || !invResp.json || !invResp.json.ok) {
                invTbody.innerHTML = '';
                const hint = document.getElementById('mrBillingHint');
                if (hint) hint.textContent = 'Unable to load invoices.';
            } else {
                const rows = Array.isArray(invResp.json.invoices) ? invResp.json.invoices : [];
                if (rows.length === 0) {
                    invTbody.innerHTML = '';
                } else {
                    invTbody.innerHTML = rows.map(inv => {
                        const id = Number(inv.id);
                        const status = escapeHtml(inv.status || '');
                        const total = money(inv.total || 0);
                        const paid = money(inv.paid || 0);
                        const bal = money(inv.balance || 0);
                        return `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">INV-${id}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">${status}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">${total}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">${paid}</td>
                                <td class="px-4 py-3 text-sm text-gray-900 font-semibold text-right">${bal}</td>
                                <td class="px-4 py-3 text-right">
                                    <button type="button" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg hover:bg-gray-100" onclick="mrOpenInvoice(${id})">View</button>
                                </td>
                            </tr>
                        `;
                    }).join('');
                }
            }

            if (!payResp.res.ok || !payResp.json || !payResp.json.ok) {
                payTbody.innerHTML = '';
            } else {
                const rows = Array.isArray(payResp.json.payments) ? payResp.json.payments : [];
                if (rows.length === 0) {
                    payTbody.innerHTML = '';
                } else {
                    payTbody.innerHTML = rows.map(pay => {
                        const id = Number(pay.id);
                        const invId = Number(pay.invoice_id);
                        const method = escapeHtml(pay.method || '');
                        const amt = money(pay.amount || 0);
                        const change = money(pay.change_amount || 0);
                        const created = escapeHtml(fmtDateTime(pay.created_at || ''));
                        return `
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-sm text-gray-900 font-semibold">PAY-${id} <span class="text-xs text-gray-500 font-normal">(INV-${invId})</span></td>
                                <td class="px-4 py-3 text-sm text-gray-700">${method}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">${amt}</td>
                                <td class="px-4 py-3 text-sm text-gray-700 text-right">${change}</td>
                                <td class="px-4 py-3 text-sm text-gray-700">${created}</td>
                            </tr>
                        `;
                    }).join('');
                }
            }

            const hint = document.getElementById('mrBillingHint');
            if (hint) {
                if (invTbody.innerHTML === '' && payTbody.innerHTML === '') {
                    hint.textContent = 'No billing records found for this encounter.';
                } else if (!hint.textContent) {
                    hint.textContent = '';
                }
            }
        }

        async function mrLoadDashboard() {
            const hint = document.getElementById('mrDashHint');
            const p = mrGetSelectedPatient();
            await mrEnsureLatestEncounter();
            const e = mrGetSelectedEncounter();

            const setCount = (id, n) => {
                const el = document.getElementById(id);
                if (el) el.textContent = String(n);
            };

            if (!p) {
                setCount('mrDashErCount', 0);
                setCount('mrDashLabCount', 0);
                setCount('mrDashResitaCount', 0);
                setCount('mrDashInvoiceCount', 0);
                if (hint) hint.textContent = 'Select a patient to begin.';
                return;
            }
            if (!e) {
                setCount('mrDashErCount', 0);
                setCount('mrDashLabCount', 0);
                setCount('mrDashResitaCount', 0);
                setCount('mrDashInvoiceCount', 0);
                if (hint) hint.textContent = 'No encounter found for this patient yet.';
                return;
            }

            if (hint) hint.textContent = 'Loading...';

            const erUrl = 'api/lab/list_requests.php?mode=er&patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const labUrl = 'api/lab/list_requests.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const resitUrl = 'api/pharmacy/list_resits.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));
            const invUrl = 'api/cashier/list_invoices.php?patient_id=' + encodeURIComponent(String(p.id)) + '&encounter_id=' + encodeURIComponent(String(e.id));

            const [erResp, labResp, resitResp, invResp] = await Promise.all([
                mrFetchJson(erUrl),
                mrFetchJson(labUrl),
                mrFetchJson(resitUrl),
                mrFetchJson(invUrl),
            ]);

            const erRows = (erResp.res.ok && erResp.json && erResp.json.ok && Array.isArray(erResp.json.requests)) ? erResp.json.requests : [];
            const labAll = (labResp.res.ok && labResp.json && labResp.json.ok && Array.isArray(labResp.json.requests)) ? labResp.json.requests : [];
            const labRows = labAll.filter(r => (r.released_at ?? '').toString().trim() !== '');
            const resitRows = (resitResp.res.ok && resitResp.json && resitResp.json.ok && Array.isArray(resitResp.json.resits)) ? resitResp.json.resits : [];
            const invRows = (invResp.res.ok && invResp.json && invResp.json.ok && Array.isArray(invResp.json.invoices)) ? invResp.json.invoices : [];

            setCount('mrDashErCount', erRows.length);
            setCount('mrDashLabCount', labRows.length);
            setCount('mrDashResitaCount', resitRows.length);
            setCount('mrDashInvoiceCount', invRows.length);

            if (hint) hint.textContent = '';
        }

        function setMrView(view) {
            const views = {
                dashboard: document.getElementById('mrDashboardView'),
                patients: document.getElementById('mrPatientsView'),
                encounters: document.getElementById('mrEncountersView'),
                'er-forms': document.getElementById('mrErFormsView'),
                'lab-results': document.getElementById('mrLabResultsView'),
                resita: document.getElementById('mrResitaView'),
                billing: document.getElementById('mrBillingView'),
            };

            Object.keys(views).forEach(k => {
                const el = views[k];
                if (!el) return;
                el.classList.toggle('hidden', k !== view);
            });

            try { window.scrollTo(0, 0); } catch (e) { }

            if (view === 'dashboard') {
                mrLoadDashboard();
            } else if (view === 'patients') {
                const input = document.getElementById('mrPatientSearch');
                if (input) input.focus();
            } else if (view === 'encounters') {
                mrLoadEncounters();
            } else if (view === 'er-forms') {
                mrLoadErForms();
            } else if (view === 'lab-results') {
                mrLoadLabResults();
            } else if (view === 'resita') {
                mrLoadResita();
            } else if (view === 'billing') {
                mrLoadBilling();
            }
        }

        function applyMrViewFromHash() {
            let h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';
            if (!['dashboard', 'patients', 'encounters', 'er-forms', 'lab-results', 'resita', 'billing'].includes(h)) {
                h = 'dashboard';
            }
            setMrView(h);
        }

        window.addEventListener('hashchange', applyMrViewFromHash);

        function mrInit() {
            mrUpdateHeader();

            window.mrDeleteEncounter = mrDeleteEncounter;
            window.mrCloseEncounter = mrCloseEncounter;
            window.mrOpenLabRequest = mrOpenLabRequest;
            window.mrOpenResit = mrOpenResit;
            window.mrOpenInvoice = mrOpenInvoice;

            const search = document.getElementById('mrPatientSearch');
            if (search) {
                const doSearch = debounce(function () {
                    mrLoadPatientsDropdown(search.value);
                }, 250);
                search.addEventListener('input', doSearch);
            }

            const changePatientBtn = document.getElementById('mrChangePatientBtn');
            if (changePatientBtn) {
                changePatientBtn.addEventListener('click', function () {
                    window.location.hash = '#patients';
                });
            }

            const createEncounterBtn = document.getElementById('mrCreateEncounterBtn');
            if (createEncounterBtn) {
                createEncounterBtn.addEventListener('click', mrCreateEncounter);
            }

            const refreshEncountersBtn = document.getElementById('mrRefreshEncountersBtn');
            if (refreshEncountersBtn) {
                refreshEncountersBtn.addEventListener('click', mrLoadEncounters);
            }

            const refreshErBtn = document.getElementById('mrRefreshErBtn');
            if (refreshErBtn) {
                refreshErBtn.addEventListener('click', mrLoadErForms);
            }

            const refreshLabBtn = document.getElementById('mrRefreshLabBtn');
            if (refreshLabBtn) {
                refreshLabBtn.addEventListener('click', mrLoadLabResults);
            }

            const refreshResitaBtn = document.getElementById('mrRefreshResitaBtn');
            if (refreshResitaBtn) {
                refreshResitaBtn.addEventListener('click', mrLoadResita);
            }

            const refreshBillingBtn = document.getElementById('mrRefreshBillingBtn');
            if (refreshBillingBtn) {
                refreshBillingBtn.addEventListener('click', mrLoadBilling);
            }

            applyMrViewFromHash();
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', mrInit);
        } else {
            mrInit();
        }
    </script>
</body>

</html>
