<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer - Expense Data</title>
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
                <div class="text-sm text-gray-600">Expense Data</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-receipt"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Total (Sample)</h2>
                            <p id="expStatTotal" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-users"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Payroll</h2>
                            <p id="expStatPayroll" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600"><i class="fas fa-bolt"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Utilities</h2>
                            <p id="expStatUtilities" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-people-carry-box"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Outsourced</h2>
                            <p id="expStatOutsourced" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense by Category</h3>
                    <canvas id="expByCategory" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Expense Trend (Last 6 Months)</h3>
                    <canvas id="expTrend" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Expense Records (Sample)</h3>
                    <p class="text-sm text-gray-600 mt-1">Salaries &amp; wages, supplies/drugs, equipment, utilities, outsourced services.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="expenseTbody"></tbody>
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

        const rows = [
            { cat: 'Salaries & wages', payee: 'Payroll Batch JAN-2026', date: '2026-01-31', amount: 1240000 },
            { cat: 'Medical supplies & drugs', payee: 'MedSupply Trading', date: '2026-01-27', amount: 185450 },
            { cat: 'Equipment purchase & maintenance', payee: 'BioMed Services', date: '2026-01-22', amount: 64900 },
            { cat: 'Utilities (water, electricity, internet)', payee: 'City Utilities', date: '2026-01-18', amount: 92300 },
            { cat: 'Outsourced services (labs, laundry, security)', payee: 'SecureGuard Inc.', date: '2026-01-15', amount: 48000 },
            { cat: 'Outsourced services (laundry)', payee: 'CleanWave Laundry', date: '2026-01-14', amount: 26500 },
        ];

        const tbody = document.getElementById('expenseTbody');
        if (tbody) {
            tbody.innerHTML = rows.map(r => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${String(r.cat)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.payee)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${money(r.amount)}</td>
                </tr>
            `).join('');
        }

        const total = rows.reduce((s, r) => s + (Number(r.amount) || 0), 0);
        const payroll = rows.filter(r => (r.cat || '').toLowerCase().indexOf('salaries') >= 0).reduce((s, r) => s + (Number(r.amount) || 0), 0);
        const utilities = rows.filter(r => (r.cat || '').toLowerCase().indexOf('utilities') >= 0).reduce((s, r) => s + (Number(r.amount) || 0), 0);
        const outsourced = rows.filter(r => (r.cat || '').toLowerCase().indexOf('outsourced') >= 0).reduce((s, r) => s + (Number(r.amount) || 0), 0);

        const elT = document.getElementById('expStatTotal');
        if (elT) elT.textContent = money(total);
        const elP = document.getElementById('expStatPayroll');
        if (elP) elP.textContent = money(payroll);
        const elU = document.getElementById('expStatUtilities');
        if (elU) elU.textContent = money(utilities);
        const elO = document.getElementById('expStatOutsourced');
        if (elO) elO.textContent = money(outsourced);

        new Chart(document.getElementById('expByCategory'), {
            type: 'bar',
            data: {
                labels: ['Payroll', 'Supplies/Drugs', 'Equipment', 'Utilities', 'Outsourced'],
                datasets: [{
                    data: [payroll, 185450, 64900, utilities, outsourced],
                    backgroundColor: ['#3B82F6', '#F59E0B', '#A855F7', '#10B981', '#EF4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { ticks: { callback: v => money(v) } } }
            }
        });

        new Chart(document.getElementById('expTrend'), {
            type: 'line',
            data: {
                labels: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
                datasets: [{
                    label: 'Expense',
                    data: [1420000, 1385000, 1502000, 1468000, 1524000, total],
                    borderColor: '#EF4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { ticks: { callback: v => money(v) } } }
            }
        });
    </script>
</body>
</html>
