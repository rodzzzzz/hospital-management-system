<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer - Cash & Bank</title>
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
                <div class="text-sm text-gray-600">Cash &amp; Bank Data</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600"><i class="fas fa-money-bill"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Cash-on-Hand</h2>
                            <p id="cbStatCash" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-building-columns"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Total Bank</h2>
                            <p id="cbStatBank" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-arrow-down"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Deposits (Sample)</h2>
                            <p id="cbStatDeposits" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-arrow-up"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Withdrawals (Sample)</h2>
                            <p id="cbStatWithdrawals" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Balances by Account</h3>
                    <canvas id="cbBalanceBar" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Cash Movement (Last 7 Days)</h3>
                    <canvas id="cbMovement" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Accounts &amp; Transactions (Sample)</h3>
                    <p class="text-sm text-gray-600 mt-1">Cash-on-hand records, bank balances, deposits/withdrawals, and reconciliation references.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Account</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reconciliation</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="cbTbody"></tbody>
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
            { account: 'Cash-on-hand', type: 'Cash', last: 'Deposit to Bank', bal: 78500, recon: 'RCN-2026-01' },
            { account: 'BPI - Operating', type: 'Bank', last: '2026-02-02', bal: 1245900, recon: 'RCN-2026-01' },
            { account: 'BDO - Payroll', type: 'Bank', last: '2026-01-31', bal: 320000, recon: 'RCN-2026-01' },
            { account: 'Landbank - Grants', type: 'Bank', last: '2026-01-20', bal: 185000, recon: 'RCN-2026-01' },
        ];

        const tbody = document.getElementById('cbTbody');
        if (tbody) {
            tbody.innerHTML = accounts.map(a => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${String(a.account)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(a.type)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(a.last)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${money(a.bal)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(a.recon)}</td>
                </tr>
            `).join('');
        }

        const cash = accounts.filter(a => a.type === 'Cash').reduce((s, a) => s + (Number(a.bal) || 0), 0);
        const bank = accounts.filter(a => a.type === 'Bank').reduce((s, a) => s + (Number(a.bal) || 0), 0);

        const deposits = 185000 + 96500;
        const withdrawals = 72000 + 38000;

        const elC = document.getElementById('cbStatCash');
        if (elC) elC.textContent = money(cash);
        const elB = document.getElementById('cbStatBank');
        if (elB) elB.textContent = money(bank);
        const elD = document.getElementById('cbStatDeposits');
        if (elD) elD.textContent = money(deposits);
        const elW = document.getElementById('cbStatWithdrawals');
        if (elW) elW.textContent = money(withdrawals);

        new Chart(document.getElementById('cbBalanceBar'), {
            type: 'bar',
            data: {
                labels: accounts.map(a => a.account),
                datasets: [{
                    data: accounts.map(a => a.bal),
                    backgroundColor: ['#F59E0B', '#3B82F6', '#60A5FA', '#A855F7'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { ticks: { callback: v => money(v) } } } }
        });

        new Chart(document.getElementById('cbMovement'), {
            type: 'line',
            data: {
                labels: ['Jan 28', 'Jan 29', 'Jan 30', 'Jan 31', 'Feb 01', 'Feb 02', 'Feb 03'],
                datasets: [
                    { label: 'Deposits', data: [15000, 22000, 18000, 96500, 14000, 26000, 18500], borderColor: '#10B981', backgroundColor: 'rgba(16,185,129,0.12)', fill: true, tension: 0.35, pointRadius: 3 },
                    { label: 'Withdrawals', data: [9000, 12000, 8000, 38000, 5000, 72000, 6000], borderColor: '#EF4444', backgroundColor: 'rgba(239,68,68,0.12)', fill: true, tension: 0.35, pointRadius: 3 }
                ]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, scales: { y: { ticks: { callback: v => money(v) } } } }
        });
    </script>
</body>
</html>
