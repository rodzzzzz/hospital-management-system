<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer - Accounting Data</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex flex-col gap-1 mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Treasurer</h1>
                <div class="text-sm text-gray-600">Accounting Data</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-wallet"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Total Assets</h2>
                            <p id="accStatAssets" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-file-invoice"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Total Liabilities</h2>
                            <p id="accStatLiab" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-scale-balanced"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Equity</h2>
                            <p id="accStatEquity" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600"><i class="fas fa-book"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Trial Balance</h2>
                            <p class="text-2xl font-semibold text-gray-800">Balanced</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Assets vs Liabilities</h3>
                    <canvas id="accPie" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Journal Entries (Sample)</h3>
                    <canvas id="accBars" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Chart of Accounts / Ledger (Sample)</h3>
                    <p class="text-sm text-gray-600 mt-1">General ledger, chart of accounts, journals, trial balance, assets/liabilities/equity.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="accTbody"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function money(v) {
            const n = Number(v ?? 0) || 0;
            try { return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n); }
            catch (e) { return '₱' + n.toFixed(2); }
        }

        const accounts = [
            { name: 'Cash on Hand', code: '1010', type: 'Asset', bal: 78500 },
            { name: 'Cash in Bank - Operating', code: '1020', type: 'Asset', bal: 1245900 },
            { name: 'Accounts Receivable', code: '1030', type: 'Asset', bal: 362500 },
            { name: 'Medical Supplies Inventory', code: '1040', type: 'Asset', bal: 210000 },
            { name: 'Accounts Payable', code: '2010', type: 'Liability', bal: -142300 },
            { name: 'Accrued Salaries', code: '2020', type: 'Liability', bal: -98000 },
            { name: 'Retained Earnings', code: '3000', type: 'Equity', bal: 3560000 },
        ];

        const tbody = document.getElementById('accTbody');
        if (tbody) {
            tbody.innerHTML = accounts.map(a => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${String(a.name)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(a.code)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(a.type)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${money(a.bal)}</td>
                </tr>
            `).join('');
        }

        const assets = accounts.filter(a => a.type === 'Asset').reduce((s, a) => s + (Number(a.bal) || 0), 0);
        const liab = Math.abs(accounts.filter(a => a.type === 'Liability').reduce((s, a) => s + (Number(a.bal) || 0), 0));
        const equity = accounts.filter(a => a.type === 'Equity').reduce((s, a) => s + (Number(a.bal) || 0), 0);

        const elA = document.getElementById('accStatAssets');
        if (elA) elA.textContent = money(assets);
        const elL = document.getElementById('accStatLiab');
        if (elL) elL.textContent = money(liab);
        const elE = document.getElementById('accStatEquity');
        if (elE) elE.textContent = money(equity);

        new Chart(document.getElementById('accPie'), {
            type: 'doughnut',
            data: {
                labels: ['Assets', 'Liabilities'],
                datasets: [{
                    data: [assets, liab],
                    backgroundColor: ['#10B981', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });

        new Chart(document.getElementById('accBars'), {
            type: 'bar',
            data: {
                labels: ['Jan 27', 'Jan 28', 'Jan 29', 'Jan 30', 'Jan 31', 'Feb 01', 'Feb 02'],
                datasets: [{
                    data: [12, 18, 14, 9, 21, 16, 11],
                    backgroundColor: '#3B82F6',
                    borderWidth: 0
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>
