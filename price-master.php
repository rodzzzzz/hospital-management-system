<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Master - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Price Master</h1>
            </div>

            <div id="priceMasterViewLaboratoryFees" class="space-y-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <div class="text-lg font-semibold text-gray-900">Laboratory Fees</div>
                            <div class="text-sm text-gray-600">Set fixed prices per laboratory test code.</div>
                        </div>
                        <button onclick="openLabFeeModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add / Update Fee
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Test Code</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Test Name</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="labFeesTbody" class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="priceMasterViewOpdFees" class="space-y-6 hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <div>
                            <div class="text-lg font-semibold text-gray-900">OPD Fees</div>
                            <div class="text-sm text-gray-600">Set fixed prices for OPD services (e.g., consultation fee).</div>
                        </div>
                        <button onclick="openOpdFeeModal()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add / Update Fee
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee Code</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Fee Name</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="opdFeesTbody" class="bg-white divide-y divide-gray-200">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div id="priceMasterViewRadiologyFees" class="space-y-6 hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="text-lg font-semibold text-gray-900">Radiology Fees</div>
                        <div class="text-sm text-gray-600">Coming soon.</div>
                    </div>
                    <div class="p-6 text-sm text-gray-700">This section will manage radiology pricing.</div>
                </div>
            </div>

            <div id="priceMasterViewProcedureFees" class="space-y-6 hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="text-lg font-semibold text-gray-900">Procedure Fees</div>
                        <div class="text-sm text-gray-600">Coming soon.</div>
                    </div>
                    <div class="p-6 text-sm text-gray-700">This section will manage procedure pricing.</div>
                </div>
            </div>

            <div id="priceMasterViewRoomFees" class="space-y-6 hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="text-lg font-semibold text-gray-900">Room Fees</div>
                        <div class="text-sm text-gray-600">Coming soon.</div>
                    </div>
                    <div class="p-6 text-sm text-gray-700">This section will manage room rate schedules.</div>
                </div>
            </div>

            <div id="priceMasterViewDiscountsPackages" class="space-y-6 hidden">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="text-lg font-semibold text-gray-900">Discounts / Packages</div>
                        <div class="text-sm text-gray-600">Coming soon.</div>
                    </div>
                    <div class="p-6 text-sm text-gray-700">This section will manage discounts and service packages.</div>
                </div>
            </div>
        </main>
    </div>

    <div id="labFeeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add / Update Laboratory Fee</h3>
                <button onclick="closeLabFeeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Test</label>
                    <select id="labFeeTestSelect" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="">Loading...</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Test Name</label>
                    <input id="labFeeTestName" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Selected test name" readonly />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input id="labFeePrice" type="number" step="0.01" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="0.00" />
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeLabFeeModal()" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button onclick="saveLabFee()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </div>
    </div>

    <div id="opdFeeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Add / Update OPD Fee</h3>
                <button onclick="closeOpdFeeModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fee Code</label>
                    <input id="opdFeeCode" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g. consultation" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Fee Name</label>
                    <input id="opdFeeName" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g. OPD Consultation" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Price</label>
                    <input id="opdFeePrice" type="number" step="0.01" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="0.00" />
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end gap-3">
                <button onclick="closeOpdFeeModal()" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</button>
                <button onclick="saveOpdFee()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </div>
    </div>

    <script>
        function show(el, yes) {
            if (!el) return;
            if (yes) el.classList.remove('hidden');
            else el.classList.add('hidden');
        }

        function toast(msg, ok) {
            Toastify({
                text: msg,
                duration: 3000,
                gravity: "top",
                position: 'right',
                backgroundColor: ok ? '#10B981' : '#EF4444',
            }).showToast();
        }

        function viewFromHash() {
            let h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e) { h = ''; }
            if (!h) h = 'laboratory-fees';

            const lab = document.getElementById('priceMasterViewLaboratoryFees');
            const opd = document.getElementById('priceMasterViewOpdFees');
            const rad = document.getElementById('priceMasterViewRadiologyFees');
            const proc = document.getElementById('priceMasterViewProcedureFees');
            const room = document.getElementById('priceMasterViewRoomFees');
            const disc = document.getElementById('priceMasterViewDiscountsPackages');

            show(lab, h === 'laboratory-fees');
            show(opd, h === 'opd-fees');
            show(rad, h === 'radiology-fees');
            show(proc, h === 'procedure-fees');
            show(room, h === 'room-fees');
            show(disc, h === 'discounts-packages');

            if (h === 'laboratory-fees') {
                loadLabFees();
            }
            if (h === 'opd-fees') {
                loadOpdFees();
            }
        }

        window.addEventListener('hashchange', viewFromHash);
        window.addEventListener('load', viewFromHash);

        let labCatalogCache = null;

        async function loadLabCatalog() {
            if (Array.isArray(labCatalogCache)) return labCatalogCache;
            const res = await fetch('api/lab/list_catalog.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok || !Array.isArray(json.tests)) {
                labCatalogCache = [];
                return labCatalogCache;
            }
            labCatalogCache = json.tests;
            return labCatalogCache;
        }

        function fillLabFeeTestSelect(tests) {
            const sel = document.getElementById('labFeeTestSelect');
            if (!sel) return;
            const rows = Array.isArray(tests) ? tests : [];
            sel.innerHTML = '<option value="">Select test...</option>' + rows.map(t => {
                const code = (t.test_code || '').toString();
                const name = (t.test_name || '').toString();
                return `<option value="${escapeHtml(code)}" data-name="${escapeHtml(name)}">${escapeHtml(name)} (${escapeHtml(code.toUpperCase())})</option>`;
            }).join('');
        }

        async function loadOpdFees() {
            const tbody = document.getElementById('opdFeesTbody');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-sm text-gray-600">Loading...</td></tr>';
            const res = await fetch('api/price_master/list_opd_fees.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-sm text-red-600">Unable to load fees.</td></tr>';
                return;
            }

            const rows = Array.isArray(json.fees) ? json.fees : [];
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-sm text-gray-600">No fees configured.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const code = (r.fee_code || '').toString();
                const name = (r.fee_name || '').toString();
                const price = (r.price || '').toString();
                const payload = encodeURIComponent(JSON.stringify({ fee_code: code, fee_name: name, price }));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">${escapeHtml(code)}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">${escapeHtml(name)}</td>
                        <td class="px-4 py-3 text-sm text-gray-800 text-right">₱${escapeHtml(price)}</td>
                        <td class="px-4 py-3 text-right">
                            <button class="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800" onclick="openOpdFeeModal(JSON.parse(decodeURIComponent('${payload}')))" >Edit</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function syncLabFeeNameFromSelect() {
            const sel = document.getElementById('labFeeTestSelect');
            const nameEl = document.getElementById('labFeeTestName');
            if (!sel || !nameEl) return;
            const opt = sel.options[sel.selectedIndex];
            const name = opt ? (opt.getAttribute('data-name') || '') : '';
            nameEl.value = (name || '').toString();
        }

        function openLabFeeModal(prefill) {
            const m = document.getElementById('labFeeModal');
            if (!m) return;
            const selEl = document.getElementById('labFeeTestSelect');
            const nameEl = document.getElementById('labFeeTestName');
            const priceEl = document.getElementById('labFeePrice');

            const p = prefill || {};
            if (priceEl) priceEl.value = (p.price || '').toString();

            if (selEl) {
                selEl.innerHTML = '<option value="">Loading...</option>';
            }
            if (nameEl) nameEl.value = '';

            loadLabCatalog().then(tests => {
                fillLabFeeTestSelect(tests);
                if (selEl) selEl.value = (p.test_code || '').toString();
                syncLabFeeNameFromSelect();
            });

            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeLabFeeModal() {
            const m = document.getElementById('labFeeModal');
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        function openOpdFeeModal(prefill) {
            const m = document.getElementById('opdFeeModal');
            if (!m) return;
            const codeEl = document.getElementById('opdFeeCode');
            const nameEl = document.getElementById('opdFeeName');
            const priceEl = document.getElementById('opdFeePrice');

            const p = prefill || {};
            if (codeEl) codeEl.value = (p.fee_code || '').toString();
            if (nameEl) nameEl.value = (p.fee_name || '').toString();
            if (priceEl) priceEl.value = (p.price || '').toString();

            m.classList.remove('hidden');
            m.classList.add('flex');
        }

        function closeOpdFeeModal() {
            const m = document.getElementById('opdFeeModal');
            if (!m) return;
            m.classList.add('hidden');
            m.classList.remove('flex');
        }

        async function loadLabFees() {
            const tbody = document.getElementById('labFeesTbody');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-sm text-gray-600">Loading...</td></tr>';
            const res = await fetch('api/price_master/list_lab_fees.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-sm text-red-600">Unable to load fees.</td></tr>';
                return;
            }

            const rows = Array.isArray(json.fees) ? json.fees : [];
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="px-4 py-4 text-sm text-gray-600">No fees configured.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(r => {
                const code = (r.test_code || '').toString();
                const name = (r.test_name || '').toString();
                const price = (r.price || '').toString();
                const payload = encodeURIComponent(JSON.stringify({ test_code: code, test_name: name, price }));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">${escapeHtml(code)}</td>
                        <td class="px-4 py-3 text-sm text-gray-800">${escapeHtml(name)}</td>
                        <td class="px-4 py-3 text-sm text-gray-800 text-right">₱${escapeHtml(price)}</td>
                        <td class="px-4 py-3 text-right">
                            <button class="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800" onclick="openLabFeeModal(JSON.parse(decodeURIComponent('${payload}')))" >Edit</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function escapeHtml(str) {
            return (str ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        async function saveLabFee() {
            const selEl = document.getElementById('labFeeTestSelect');
            const nameEl = document.getElementById('labFeeTestName');
            const priceEl = document.getElementById('labFeePrice');
            const test_code = (selEl?.value || '').toString().trim().toLowerCase();
            const test_name = (nameEl?.value || '').toString().trim();
            const price = (priceEl?.value || '').toString().trim();

            if (!test_code) {
                toast('Select a test', false);
                return;
            }
            if (!test_name) {
                toast('Test name is missing', false);
                return;
            }
            if (!price || isNaN(Number(price))) {
                toast('Valid price is required', false);
                return;
            }

            const res = await fetch('api/price_master/save_lab_fee.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ test_code, test_name, price: Number(price) })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                toast((json && json.error) ? json.error : 'Unable to save fee', false);
                return;
            }

            closeLabFeeModal();
            toast('Saved', true);
            loadLabFees();
        }

        async function saveOpdFee() {
            const codeEl = document.getElementById('opdFeeCode');
            const nameEl = document.getElementById('opdFeeName');
            const priceEl = document.getElementById('opdFeePrice');

            const fee_code = (codeEl?.value || '').toString().trim().toLowerCase();
            const fee_name = (nameEl?.value || '').toString().trim();
            const price = (priceEl?.value || '').toString().trim();

            if (!fee_code) {
                toast('Fee code is required', false);
                return;
            }
            if (!fee_name) {
                toast('Fee name is required', false);
                return;
            }
            if (!price || isNaN(Number(price))) {
                toast('Valid price is required', false);
                return;
            }

            const res = await fetch('api/price_master/save_opd_fee.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ fee_code, fee_name, price: Number(price) })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                toast((json && json.error) ? json.error : 'Unable to save fee', false);
                return;
            }

            closeOpdFeeModal();
            toast('Saved', true);
            loadOpdFees();
        }

        const labFeeSelect = document.getElementById('labFeeTestSelect');
        if (labFeeSelect) {
            labFeeSelect.addEventListener('change', syncLabFeeNameFromSelect);
        }
    </script>
</body>

</html>
