<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ICU - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">ICU</h1>
                    <p class="text-sm text-gray-600 mt-1">Critical care census and bed utilization overview.</p>
                </div>
            </div>

            <section id="overview" class="icu-section">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-700"><i class="fas fa-user-injured"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Active Patients</h2>
                                <p id="icuActivePatients" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-emerald-100 text-emerald-700"><i class="fas fa-bed"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Available Beds</h2>
                                <p id="icuAvailableBeds" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-rose-100 text-rose-700"><i class="fas fa-procedures"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Occupied Beds</h2>
                                <p id="icuOccupiedBeds" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-700"><i class="fas fa-clock"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Avg LOS (days)</h2>
                                <p id="icuAvgLos" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Occupancy Trend (7 days)</h3>
                            <span class="text-xs text-gray-500">% beds occupied</span>
                        </div>
                        <canvas id="icuOccupancyChart" class="w-full h-64"></canvas>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Admissions by Shift (Today)</h3>
                            <span class="text-xs text-gray-500">count</span>
                        </div>
                        <canvas id="icuAdmissionsShiftChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </section>

            <section id="patients" class="icu-section mt-6 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Patients List</h3>
                        <p class="text-sm text-gray-600 mt-1">Active ICU patients, bed assignment, and attending physician.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Attending</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Diagnosis</th>
                                </tr>
                            </thead>
                            <tbody id="icuPatientsTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="labs" class="icu-section mt-6 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Labs / Results</h3>
                        <p class="text-sm text-gray-600 mt-1">Pending and abnormal critical labs.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Result</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Collected</th>
                                </tr>
                            </thead>
                            <tbody id="icuLabsTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="transfers" class="icu-section mt-6 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Transfers / Discharge Planning</h3>
                        <p class="text-sm text-gray-600 mt-1">Stepdown candidates, transfer requests, and discharge summaries.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stepdown</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Transfer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Destination</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ETA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discharge Summary</th>
                                </tr>
                            </thead>
                            <tbody id="icuTransfersTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="billing" class="icu-section mt-6 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Admit Billing</h3>
                        <p class="text-sm text-gray-600 mt-1">Billing breakdown for admitted ICU patients: room charges, services, foods, lab fees, and other items.</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end">
                            <div class="lg:col-span-1">
                                <label for="icuBillingPatientSelect" class="block text-sm font-medium text-gray-700">Patient</label>
                                <select id="icuBillingPatientSelect" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 bg-white text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"></select>
                            </div>
                            <div class="lg:col-span-2">
                                <div class="rounded-lg border border-gray-200 p-4 bg-gray-50">
                                    <div class="text-xs text-gray-500">Room / Bed</div>
                                    <div id="icuBillingRoomBed" class="text-sm font-semibold text-gray-900 break-words">-</div>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-lg border border-gray-200 overflow-hidden mt-6">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Unit</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="icuBillingTbody" class="bg-white divide-y divide-gray-200">
                                        <tr>
                                            <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="admission-status" class="icu-section mt-6 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Admission Status</h3>
                        <p class="text-sm text-gray-600 mt-1">Track if ICU patients are still admitted or already released.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Bed</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Admitted</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Released</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="icuAdmissionStatusTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>
        </main>

        <div id="icuAdmissionModal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
            <div class="absolute inset-0 bg-black/50" data-icu-modal-close></div>
            <div class="relative w-full h-full flex items-center justify-center p-4">
                <div class="w-[95%] max-w-2xl bg-white rounded-lg shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                        <div>
                            <h4 id="icuAdmissionModalTitle" class="text-lg font-semibold text-gray-900">Admission</h4>
                            <p id="icuAdmissionModalSubtitle" class="text-sm text-gray-600 mt-0.5">-</p>
                        </div>
                        <button type="button" class="h-9 w-9 inline-flex items-center justify-center rounded-lg hover:bg-gray-100 text-gray-600" data-icu-modal-close aria-label="Close">
                            <i class="fas fa-xmark"></i>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto flex-1">
                        <div id="icuAdmissionModalBody" class="space-y-4"></div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="button" class="px-4 py-2 rounded-lg bg-gray-800 text-white text-sm hover:bg-gray-900" data-icu-modal-close>Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let icuOccChart = null;
        let icuShiftChart = null;

        async function ensureIcuInstalled() {
            try {
                await fetch('api/icu/install.php', { headers: { 'Accept': 'application/json' } });
            } catch (e) {
            }
        }

        async function loadIcuStats() {
            const res = await fetch('api/icu/stats.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json.stats || null;
        }

        async function loadIcuAnalytics() {
            const res = await fetch('api/icu/analytics.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json;
        }

        function setText(id, val) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = (val === null || val === undefined || val === '') ? '-' : String(val);
        }

        async function loadIcuPatients() {
            const res = await fetch('api/icu/patients.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return Array.isArray(json.patients) ? json.patients : [];
        }

        async function loadIcuLabs() {
            const res = await fetch('api/icu/labs.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return Array.isArray(json.labs) ? json.labs : [];
        }

        async function loadIcuTransfers() {
            const res = await fetch('api/icu/transfers.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return Array.isArray(json.transfers) ? json.transfers : [];
        }

        const icuSections = ['overview', 'patients', 'labs', 'transfers', 'billing', 'admission-status'];
        const icuLoaded = {
            overview: false,
            patients: false,
            labs: false,
            transfers: false,
            billing: false,
            'admission-status': false,
        };

        function getIcuSectionFromHash() {
            let h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) return 'overview';
            if (icuSections.indexOf(h) !== -1) return h;
            return 'overview';
        }

        function setIcuActiveSection(active) {
            icuSections.forEach(function (id) {
                const el = document.getElementById(id);
                if (!el) return;
                if (id === active) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }

        async function ensureIcuSectionLoaded(section) {
            if (section === 'overview') {
                if (!icuLoaded.overview) {
                    const stats = await loadIcuStats();
                    if (stats) {
                        setText('icuActivePatients', stats.active_patients);
                        setText('icuAvailableBeds', stats.available_beds);
                        setText('icuOccupiedBeds', stats.occupied_beds);
                        setText('icuAvgLos', stats.avg_los_days === null ? '-' : stats.avg_los_days);
                    }

                    const analytics = await loadIcuAnalytics();
                    renderIcuCharts(analytics);
                    icuLoaded.overview = true;
                } else {
                    window.setTimeout(function () {
                        if (icuOccChart && typeof icuOccChart.resize === 'function') icuOccChart.resize();
                        if (icuShiftChart && typeof icuShiftChart.resize === 'function') icuShiftChart.resize();
                    }, 0);
                }
                return;
            }

            if (section === 'patients') {
                if (icuLoaded.patients) return;
                await renderIcuPatients();
                icuLoaded.patients = true;
                return;
            }

            if (section === 'labs') {
                if (icuLoaded.labs) return;
                await renderIcuLabs();
                icuLoaded.labs = true;
                return;
            }

            if (section === 'transfers') {
                if (icuLoaded.transfers) return;
                await renderIcuTransfers();
                icuLoaded.transfers = true;
                return;
            }

            if (section === 'billing') {
                if (icuLoaded.billing) return;
                renderIcuBilling();
                icuLoaded.billing = true;
                return;
            }

            if (section === 'admission-status') {
                if (icuLoaded['admission-status']) return;
                renderIcuAdmissionStatus();
                icuLoaded['admission-status'] = true;
                return;
            }
        }

        function escapeHtml(s) {
            return String(s ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function fmtDateTime(s) {
            const d = new Date(String(s || '').replace(' ', 'T'));
            if (Number.isNaN(d.getTime())) return '';
            return d.toLocaleString([], { year: 'numeric', month: 'short', day: '2-digit', hour: 'numeric', minute: '2-digit' });
        }

        function fmtMoney(n) {
            const v = Number(n);
            if (!Number.isFinite(v)) return '-';
            return v.toLocaleString([], { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function icuStatusChip(kind, value) {
            const v = String(value || '').toLowerCase();
            if (kind === 'lab') {
                if (v === 'pending') return { cls: 'bg-amber-100 text-amber-800', label: 'Pending' };
                return { cls: 'bg-emerald-100 text-emerald-800', label: 'Resulted' };
            }
            if (kind === 'flag') {
                if (v === 'critical') return { cls: 'bg-red-100 text-red-800', label: 'Critical' };
                if (v === 'abnormal') return { cls: 'bg-amber-100 text-amber-800', label: 'Abnormal' };
                return { cls: 'bg-slate-100 text-slate-800', label: 'Normal' };
            }
            if (kind === 'transfer') {
                if (v === 'approved') return { cls: 'bg-emerald-100 text-emerald-800', label: 'Approved' };
                if (v === 'requested') return { cls: 'bg-indigo-100 text-indigo-800', label: 'Requested' };
                return { cls: 'bg-slate-100 text-slate-800', label: 'None' };
            }
            if (kind === 'summary') {
                if (v === 'ready') return { cls: 'bg-emerald-100 text-emerald-800', label: 'Ready' };
                if (v === 'in_progress') return { cls: 'bg-indigo-100 text-indigo-800', label: 'In Progress' };
                return { cls: 'bg-amber-100 text-amber-800', label: 'Pending' };
            }
            return { cls: 'bg-slate-100 text-slate-800', label: v || '-' };
        }

        async function renderIcuPatients() {
            const tbody = document.getElementById('icuPatientsTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            const rows = await loadIcuPatients();
            if (!rows) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-600">Unable to load patients list.</td></tr>';
                return;
            }
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No active patients.</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(function (p) {
                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(p.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(p.bed_code) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(p.attending_physician) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(p.admitted_at)) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(p.diagnosis || '-') + '</td>' +
                    '</tr>'
                );
            }).join('');
        }

        async function renderIcuLabs() {
            const tbody = document.getElementById('icuLabsTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            const rows = await loadIcuLabs();
            if (!rows) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-red-600">Unable to load labs.</td></tr>';
                return;
            }
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No lab items.</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(function (l) {
                const st = icuStatusChip('lab', l.status);
                const flag = icuStatusChip('flag', l.flag || '');
                const resultTxt = (l.status === 'pending')
                    ? '<span class="text-gray-500">-</span>'
                    : ('<span class="font-medium">' + escapeHtml(l.value) + '</span> <span class="text-gray-500">' + escapeHtml(l.unit) + '</span>');

                const flagChip = (l.status === 'pending')
                    ? ''
                    : (' <span class="ml-2 px-2 py-1 text-xs rounded-full ' + flag.cls + '">' + escapeHtml(flag.label) + '</span>');

                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(l.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(l.bed_code) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(l.test_name) + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + st.cls + '">' + escapeHtml(st.label) + '</span></td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + resultTxt + flagChip + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(l.collected_at)) + '</td>' +
                    '</tr>'
                );
            }).join('');
        }

        async function renderIcuTransfers() {
            const tbody = document.getElementById('icuTransfersTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            const rows = await loadIcuTransfers();
            if (!rows) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-sm text-red-600">Unable to load transfers.</td></tr>';
                return;
            }
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">No transfer planning items.</td></tr>';
                return;
            }
            tbody.innerHTML = rows.map(function (t) {
                const transferChip = icuStatusChip('transfer', t.transfer_request_status);
                const summaryChip = icuStatusChip('summary', t.discharge_summary_status);
                const step = t.stepdown_candidate ? { cls: 'bg-emerald-100 text-emerald-800', label: 'Candidate' } : { cls: 'bg-slate-100 text-slate-800', label: 'No' };
                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(t.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(t.bed_code) + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + step.cls + '">' + escapeHtml(step.label) + '</span></td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + transferChip.cls + '">' + escapeHtml(transferChip.label) + '</span></td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(t.destination || '-') + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(t.eta)) + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + summaryChip.cls + '">' + escapeHtml(summaryChip.label) + '</span></td>' +
                    '</tr>'
                );
            }).join('');
        }

        const icuBillingSample = [
            {
                patient_id: 'P-1001',
                patient_name: 'Juan Dela Cruz',
                bed_code: 'ICU-01',
                room_name: 'ICU Room A',
                items: [
                    { date: '2026-02-01 09:10', category: 'Room', description: 'ICU Room Rate (Daily)', qty: 2, unit: 6500, amount: 13000 },
                    { date: '2026-02-01 10:30', category: 'Services', description: 'Respiratory Therapy Session', qty: 1, unit: 1200, amount: 1200 },
                    { date: '2026-02-01 12:00', category: 'Food', description: 'Dietary Tray (Soft Diet)', qty: 2, unit: 180, amount: 360 },
                    { date: '2026-02-01 14:15', category: 'Laboratory', description: 'CBC', qty: 1, unit: 450, amount: 450 },
                    { date: '2026-02-02 08:45', category: 'Laboratory', description: 'ABG', qty: 1, unit: 900, amount: 900 },
                    { date: '2026-02-02 11:10', category: 'Med/Supplies', description: 'IV Set + Consumables', qty: 1, unit: 320, amount: 320 },
                ]
            },
            {
                patient_id: 'P-1002',
                patient_name: 'Maria Santos',
                bed_code: 'ICU-03',
                room_name: 'ICU Room B',
                items: [
                    { date: '2026-02-02 07:30', category: 'Room', description: 'ICU Room Rate (Daily)', qty: 1, unit: 6500, amount: 6500 },
                    { date: '2026-02-02 08:20', category: 'Services', description: 'Nursing Intensive Care Fee', qty: 1, unit: 1500, amount: 1500 },
                    { date: '2026-02-02 12:00', category: 'Food', description: 'Dietary Tray (Renal)', qty: 1, unit: 200, amount: 200 },
                    { date: '2026-02-02 13:40', category: 'Laboratory', description: 'Electrolytes Panel', qty: 1, unit: 700, amount: 700 },
                ]
            }
        ];

        function renderIcuBilling() {
            const select = document.getElementById('icuBillingPatientSelect');
            const tbody = document.getElementById('icuBillingTbody');
            const roomBed = document.getElementById('icuBillingRoomBed');
            if (!select || !tbody || !roomBed) return;

            select.innerHTML = icuBillingSample.map(function (p, idx) {
                return '<option value="' + escapeHtml(p.patient_id) + '"' + (idx === 0 ? ' selected' : '') + '>' + escapeHtml(p.patient_name) + ' (' + escapeHtml(p.bed_code) + ')</option>';
            }).join('');

            function buildBillingSummary(p) {
                const items = Array.isArray(p.items) ? p.items : [];
                const total = items.reduce(function (sum, it) { return sum + Number(it.amount || 0); }, 0);
                const byCat = {};
                items.forEach(function (it) {
                    const c = String(it.category || 'Other');
                    byCat[c] = (byCat[c] || 0) + Number(it.amount || 0);
                });
                return { total: total, byCat: byCat, items: items };
            }

            function openBillingPatientModal(p, item) {
                const modal = document.getElementById('icuAdmissionModal');
                const title = document.getElementById('icuAdmissionModalTitle');
                const sub = document.getElementById('icuAdmissionModalSubtitle');
                const body = document.getElementById('icuAdmissionModalBody');
                if (!modal || !title || !sub || !body) return;

                const summary = buildBillingSummary(p);
                const cats = Object.keys(summary.byCat || {}).sort();
                const catHtml = cats.length
                    ? ('<div class="space-y-2">' + cats.map(function (c) {
                        return '<div class="flex items-center justify-between"><span class="text-gray-700">' + escapeHtml(c) + '</span><span class="font-semibold text-gray-900">' + fmtMoney(summary.byCat[c]) + '</span></div>';
                    }).join('') + '</div>')
                    : '<div class="text-sm text-gray-600">No category totals.</div>';

                const itemBox = item
                    ? (
                        '<div class="rounded-lg border border-gray-200 p-4 bg-gray-50">' +
                            '<div class="text-xs text-gray-500 mb-2">Selected Item</div>' +
                            '<div class="grid grid-cols-1 md:grid-cols-2 gap-3">' +
                                '<div><div class="text-xs text-gray-500">Date</div><div class="text-sm text-gray-900">' + escapeHtml(fmtDateTime(item.date)) + '</div></div>' +
                                '<div><div class="text-xs text-gray-500">Category</div><div class="text-sm text-gray-900">' + escapeHtml(item.category) + '</div></div>' +
                                '<div class="md:col-span-2"><div class="text-xs text-gray-500">Description</div><div class="text-sm text-gray-900">' + escapeHtml(item.description) + '</div></div>' +
                                '<div><div class="text-xs text-gray-500">Qty</div><div class="text-sm text-gray-900">' + escapeHtml(item.qty) + '</div></div>' +
                                '<div><div class="text-xs text-gray-500">Amount</div><div class="text-sm font-semibold text-gray-900">' + fmtMoney(item.amount) + '</div></div>' +
                            '</div>' +
                        '</div>'
                    )
                    : '';

                title.textContent = (p.patient_name || 'Patient') + ' - Billing';
                sub.textContent = (p.patient_id ? ('Patient ' + p.patient_id) : '-') + (p.bed_code ? (' • ' + p.bed_code) : '');
                body.innerHTML = (
                    '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
                        '<div class="rounded-lg border border-gray-200 p-4 bg-gray-50">' +
                            '<div class="text-xs text-gray-500">Room / Bed</div>' +
                            '<div class="mt-1 text-sm font-semibold text-gray-900 break-words">' + escapeHtml((p.room_name || '-') + ' • ' + (p.bed_code || '-')) + '</div>' +
                        '</div>' +
                        '<div class="rounded-lg border border-gray-200 p-4">' +
                            '<div class="text-xs text-gray-500">Running Total</div>' +
                            '<div class="mt-1 text-xl font-semibold text-gray-900">' + fmtMoney(summary.total) + '</div>' +
                        '</div>' +
                    '</div>' +
                    '<div class="rounded-lg border border-gray-200 p-4">' +
                        '<div class="text-xs text-gray-500 mb-2">Category Totals</div>' +
                        catHtml +
                    '</div>' +
                    itemBox +
                    '<div class="rounded-lg border border-gray-200 p-4">' +
                        '<div class="text-xs text-gray-500 mb-2">Items</div>' +
                        '<div class="overflow-x-auto">' +
                            '<table class="min-w-full divide-y divide-gray-200">' +
                                '<thead class="bg-gray-50">' +
                                    '<tr>' +
                                        '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>' +
                                        '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Category</th>' +
                                        '<th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>' +
                                        '<th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody class="bg-white divide-y divide-gray-200">' +
                                    summary.items.map(function (it) {
                                        return '<tr>' +
                                            '<td class="px-3 py-2 text-sm text-gray-600">' + escapeHtml(fmtDateTime(it.date)) + '</td>' +
                                            '<td class="px-3 py-2 text-sm text-gray-700">' + escapeHtml(it.category) + '</td>' +
                                            '<td class="px-3 py-2 text-sm text-gray-900">' + escapeHtml(it.description) + '</td>' +
                                            '<td class="px-3 py-2 text-sm text-gray-900 font-medium text-right">' + fmtMoney(it.amount) + '</td>' +
                                        '</tr>';
                                    }).join('') +
                                '</tbody>' +
                            '</table>' +
                        '</div>' +
                    '</div>'
                );

                modal.classList.remove('hidden');
                modal.setAttribute('aria-hidden', 'false');
            }

            function renderForPatient(patientId) {
                const p = icuBillingSample.find(function (x) { return x.patient_id === patientId; }) || icuBillingSample[0];
                if (!p) return;

                roomBed.textContent = (p.room_name || '-') + ' • ' + (p.bed_code || '-');

                const items = Array.isArray(p.items) ? p.items : [];
                if (items.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">No billing items.</td></tr>';
                    return;
                }

                tbody.innerHTML = items.map(function (it) {
                    const itemKey = escapeHtml([patientId, it.date, it.category, it.description].join('|'));
                    return (
                        '<tr class="hover:bg-gray-50">' +
                            '<td class="px-4 py-3 text-sm text-gray-600">' + escapeHtml(fmtDateTime(it.date)) + '</td>' +
                            '<td class="px-4 py-3 text-sm text-gray-700">' + escapeHtml(it.category) + '</td>' +
                            '<td class="px-4 py-3 text-sm text-gray-900">' + escapeHtml(it.description) + '</td>' +
                            '<td class="px-4 py-3 text-sm text-gray-700 text-right">' + escapeHtml(it.qty) + '</td>' +
                            '<td class="px-4 py-3 text-sm text-gray-700 text-right">' + fmtMoney(it.unit) + '</td>' +
                            '<td class="px-4 py-3 text-sm text-gray-900 font-medium text-right">' + fmtMoney(it.amount) + '</td>' +
                            '<td class="px-4 py-3 text-right">' +
                                '<button type="button" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700" data-icu-billing-view-item="' + itemKey + '">View</button>' +
                            '</td>' +
                        '</tr>'
                    );
                }).join('');

                tbody.querySelectorAll('button[data-icu-billing-view-item]').forEach(function (btn) {
                    btn.addEventListener('click', function () {
                        const p2 = icuBillingSample.find(function (x) { return x.patient_id === patientId; }) || icuBillingSample[0];
                        if (!p2) return;
                        const key = (btn.getAttribute('data-icu-billing-view-item') || '').toString();
                        const it2 = (Array.isArray(p2.items) ? p2.items : []).find(function (z) {
                            return [p2.patient_id, z.date, z.category, z.description].join('|') === key;
                        });
                        openBillingPatientModal(p2, it2 || null);
                    });
                });
            }

            renderForPatient(select.value);
            select.addEventListener('change', function () {
                renderForPatient(select.value);
            });
        }

        const icuAdmissionStatusSample = [
            {
                admission_id: 'A-9001',
                patient_name: 'Juan Dela Cruz',
                bed_code: 'ICU-01',
                status: 'Admitted',
                admitted_at: '2026-02-01 09:05',
                released_at: '',
                attending: 'Dr. Reyes',
                diagnosis: 'Severe Pneumonia',
                remarks: 'On oxygen therapy; monitor vitals q1h.'
            },
            {
                admission_id: 'A-9002',
                patient_name: 'Maria Santos',
                bed_code: 'ICU-03',
                status: 'Released',
                admitted_at: '2026-01-29 18:20',
                released_at: '2026-02-02 10:00',
                attending: 'Dr. Lim',
                diagnosis: 'Post-op Monitoring',
                remarks: 'Transferred to ward; stable condition.'
            }
        ];

        function icuAdmissionStatusChip(v) {
            const s = String(v || '').toLowerCase();
            if (s === 'released') return { cls: 'bg-slate-100 text-slate-800', label: 'Released' };
            return { cls: 'bg-emerald-100 text-emerald-800', label: 'Admitted' };
        }

        function openIcuAdmissionModal(row) {
            const modal = document.getElementById('icuAdmissionModal');
            const title = document.getElementById('icuAdmissionModalTitle');
            const sub = document.getElementById('icuAdmissionModalSubtitle');
            const body = document.getElementById('icuAdmissionModalBody');
            if (!modal || !title || !sub || !body) return;

            title.textContent = row.patient_name || 'Admission';
            sub.textContent = (row.admission_id ? ('Admission ' + row.admission_id) : '-') + (row.bed_code ? (' • ' + row.bed_code) : '');

            const chip = icuAdmissionStatusChip(row.status);
            body.innerHTML = (
                '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
                    '<div class="rounded-lg border border-gray-200 p-4">' +
                        '<div class="text-xs text-gray-500">Status</div>' +
                        '<div class="mt-1"><span class="px-2 py-1 text-xs rounded-full ' + chip.cls + '">' + escapeHtml(chip.label) + '</span></div>' +
                    '</div>' +
                    '<div class="rounded-lg border border-gray-200 p-4">' +
                        '<div class="text-xs text-gray-500">Attending</div>' +
                        '<div class="mt-1 text-sm font-semibold text-gray-900">' + escapeHtml(row.attending || '-') + '</div>' +
                    '</div>' +
                    '<div class="rounded-lg border border-gray-200 p-4">' +
                        '<div class="text-xs text-gray-500">Admitted</div>' +
                        '<div class="mt-1 text-sm text-gray-900">' + escapeHtml(fmtDateTime(row.admitted_at)) + '</div>' +
                    '</div>' +
                    '<div class="rounded-lg border border-gray-200 p-4">' +
                        '<div class="text-xs text-gray-500">Released</div>' +
                        '<div class="mt-1 text-sm text-gray-900">' + escapeHtml(row.released_at ? fmtDateTime(row.released_at) : '-') + '</div>' +
                    '</div>' +
                '</div>' +
                '<div class="rounded-lg border border-gray-200 p-4">' +
                    '<div class="text-xs text-gray-500">Diagnosis</div>' +
                    '<div class="mt-1 text-sm text-gray-900">' + escapeHtml(row.diagnosis || '-') + '</div>' +
                '</div>' +
                '<div class="rounded-lg border border-gray-200 p-4">' +
                    '<div class="text-xs text-gray-500">Remarks</div>' +
                    '<div class="mt-1 text-sm text-gray-900">' + escapeHtml(row.remarks || '-') + '</div>' +
                '</div>'
            );

            modal.classList.remove('hidden');
            modal.setAttribute('aria-hidden', 'false');
        }

        function closeIcuAdmissionModal() {
            const modal = document.getElementById('icuAdmissionModal');
            if (!modal) return;
            modal.classList.add('hidden');
            modal.setAttribute('aria-hidden', 'true');
        }

        function renderIcuAdmissionStatus() {
            const tbody = document.getElementById('icuAdmissionStatusTbody');
            if (!tbody) return;

            const rows = icuAdmissionStatusSample;
            if (!rows || rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No admissions.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(function (r) {
                const chip = icuAdmissionStatusChip(r.status);
                const rel = r.released_at ? escapeHtml(fmtDateTime(r.released_at)) : '<span class="text-gray-500">-</span>';
                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(r.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(r.bed_code || '-') + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + chip.cls + '">' + escapeHtml(chip.label) + '</span></td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(r.admitted_at)) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + rel + '</td>' +
                        '<td class="px-6 py-4 text-right">' +
                            '<button type="button" class="px-3 py-1.5 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700" data-icu-view-admission="' + escapeHtml(r.admission_id) + '">View</button>' +
                        '</td>' +
                    '</tr>'
                );
            }).join('');

            tbody.querySelectorAll('button[data-icu-view-admission]').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const id = (btn.getAttribute('data-icu-view-admission') || '').toString();
                    const row = icuAdmissionStatusSample.find(function (x) { return x.admission_id === id; });
                    if (row) openIcuAdmissionModal(row);
                });
            });
        }

        function renderIcuCharts(analytics) {
            if (!analytics) return;

            const occ = analytics.occupancy_trend || { labels: [], data: [] };
            const shift = analytics.admissions_by_shift || { labels: [], data: [] };

            const occCtx = document.getElementById('icuOccupancyChart').getContext('2d');
            if (icuOccChart) icuOccChart.destroy();
            icuOccChart = new Chart(occCtx, {
                type: 'line',
                data: {
                    labels: occ.labels || [],
                    datasets: [{
                        label: 'Occupancy %',
                        data: occ.data || [],
                        borderColor: '#4F46E5',
                        backgroundColor: '#A5B4FC',
                        tension: 0.35,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, max: 100, title: { display: true, text: '%' } } }
                }
            });

            const shiftCtx = document.getElementById('icuAdmissionsShiftChart').getContext('2d');
            if (icuShiftChart) icuShiftChart.destroy();
            icuShiftChart = new Chart(shiftCtx, {
                type: 'doughnut',
                data: {
                    labels: shift.labels || [],
                    datasets: [{
                        data: shift.data || [],
                        backgroundColor: ['#22C55E80', '#60A5FA80', '#F59E0B80'],
                        borderColor: ['#16A34A', '#3B82F6', '#D97706'],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', async function () {
            await ensureIcuInstalled();

            document.querySelectorAll('[data-icu-modal-close]').forEach(function (el) {
                el.addEventListener('click', closeIcuAdmissionModal);
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape') closeIcuAdmissionModal();
            });

            async function handle() {
                const active = getIcuSectionFromHash();
                setIcuActiveSection(active);
                await ensureIcuSectionLoaded(active);
            }

            await handle();
            window.addEventListener('hashchange', handle);
        });
    </script>
</body>

</html>
