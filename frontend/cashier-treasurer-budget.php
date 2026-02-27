<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer - Budget & Planning</title>
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
                <div class="text-sm text-gray-600">Budget &amp; Planning Data</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-chart-pie"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Total Budget</h2>
                            <p id="budStatBudget" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-money-bill-trend-up"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Total Actual</h2>
                            <p id="budStatActual" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-circle-check"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Under Budget</h2>
                            <p id="budStatUnder" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-triangle-exclamation"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Over Budget</h2>
                            <p id="budStatOver" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Budget vs Actual (by Department)</h3>
                    <canvas id="budgetBar" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Variance Distribution</h3>
                    <canvas id="variancePie" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Annual / Department Budgets (Sample)</h3>
                    <p class="text-sm text-gray-600 mt-1">Annual and departmental budgets, forecasted income/expenses, and variance.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actual</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Variance</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notes</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="budgetTbody"></tbody>
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
            { dept: 'Emergency Room', budget: 450000, actual: 472300, note: 'High consumables usage' },
            { dept: 'Laboratory', budget: 380000, actual: 355100, note: 'Reagent savings' },
            { dept: 'Pharmacy', budget: 520000, actual: 508800, note: 'Stable demand' },
            { dept: 'ICU', budget: 610000, actual: 640500, note: 'Ventilator maintenance' },
            { dept: 'Out-Patient Dept', budget: 290000, actual: 275200, note: 'Lower OPD volume' },
        ].map(r => ({ ...r, variance: (Number(r.budget) || 0) - (Number(r.actual) || 0) }));

        const tbody = document.getElementById('budgetTbody');
        if (tbody) {
            tbody.innerHTML = rows.map(r => {
                const varianceClass = r.variance >= 0 ? 'text-green-700' : 'text-red-700';
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${String(r.dept)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${money(r.budget)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${money(r.actual)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold ${varianceClass} text-right">${money(r.variance)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.note)}</td>
                    </tr>
                `;
            }).join('');
        }

        const totalBudget = rows.reduce((s, r) => s + (Number(r.budget) || 0), 0);
        const totalActual = rows.reduce((s, r) => s + (Number(r.actual) || 0), 0);
        const under = rows.filter(r => r.variance >= 0).length;
        const over = rows.filter(r => r.variance < 0).length;

        const elB = document.getElementById('budStatBudget');
        if (elB) elB.textContent = money(totalBudget);
        const elA = document.getElementById('budStatActual');
        if (elA) elA.textContent = money(totalActual);
        const elU = document.getElementById('budStatUnder');
        if (elU) elU.textContent = String(under);
        const elO = document.getElementById('budStatOver');
        if (elO) elO.textContent = String(over);

        new Chart(document.getElementById('budgetBar'), {
            type: 'bar',
            data: {
                labels: rows.map(r => r.dept),
                datasets: [
                    { label: 'Budget', data: rows.map(r => r.budget), backgroundColor: 'rgba(59, 130, 246, 0.7)', borderWidth: 0 },
                    { label: 'Actual', data: rows.map(r => r.actual), backgroundColor: 'rgba(239, 68, 68, 0.7)', borderWidth: 0 }
                ]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } },
                scales: { y: { ticks: { callback: v => money(v) } } }
            }
        });

        const underSum = rows.filter(r => r.variance >= 0).reduce((s, r) => s + r.variance, 0);
        const overSum = Math.abs(rows.filter(r => r.variance < 0).reduce((s, r) => s + r.variance, 0));

        new Chart(document.getElementById('variancePie'), {
            type: 'doughnut',
            data: {
                labels: ['Under Budget', 'Over Budget'],
                datasets: [{ data: [underSum, overSum], backgroundColor: ['#10B981', '#EF4444'], borderWidth: 0 }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });
    </script>
</body>
</html>
