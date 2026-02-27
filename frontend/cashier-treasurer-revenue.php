<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer - Revenue Data</title>
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
                <div class="text-sm text-gray-600">Revenue Data</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-coins"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Today's Collections</h2>
                            <p id="revStatToday" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-calendar"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Month-to-Date</h2>
                            <p id="revStatMtd" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-amber-100 text-amber-600"><i class="fas fa-hand-holding-heart"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Donations (MTD)</h2>
                            <p id="revStatDonations" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-file-invoice"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Insurance (MTD)</h2>
                            <p id="revStatInsurance" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend (Last 7 Days)</h3>
                    <canvas id="revenueTrendChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Payment Method</h3>
                    <canvas id="revenueMethodChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Department (Sample)</h3>
                    <canvas id="revenueDeptChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Records (Sample)</h3>
                        <p class="text-sm text-gray-600 mt-1">Patient payments, insurance reimbursements, government payments, donations & grants, and other hospital income.</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200" id="revenueTbody"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function money(v) {
            const n = Number(v ?? 0) || 0;
            try {
                return new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', minimumFractionDigits: 2, maximumFractionDigits: 2 }).format(n);
            } catch (e) {
                return '₱' + n.toFixed(2);
            }
        }

        const revenueRows = [
            { source: 'Patient payments (cash)', ref: 'INV-10021', date: '2026-02-03', amount: 1850 },
            { source: 'Patient payments (card)', ref: 'INV-10022', date: '2026-02-03', amount: 4250 },
            { source: 'Patient payments (online)', ref: 'INV-10023', date: '2026-02-02', amount: 1600 },
            { source: 'Checks', ref: 'CHK-00018', date: '2026-02-01', amount: 3200 },
            { source: 'Insurance reimbursements', ref: 'INS-AXA-0941', date: '2026-02-01', amount: 62500 },
            { source: 'Government health program payments', ref: 'GOV-PHIC-2026-013', date: '2026-01-29', amount: 118000 },
            { source: 'Donations & grants', ref: 'DNG-2026-004', date: '2026-01-20', amount: 25000 },
            { source: 'Other hospital income (parking)', ref: 'PKG-2026-020', date: '2026-02-03', amount: 450 },
            { source: 'Other hospital income (canteen)', ref: 'CNT-2026-033', date: '2026-02-03', amount: 1780 },
        ];

        const tbody = document.getElementById('revenueTbody');
        if (tbody) {
            tbody.innerHTML = revenueRows.map(r => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${String(r.source)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.ref)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 text-right">${money(r.amount)}</td>
                </tr>
            `).join('');
        }

        const todayTotal = revenueRows.filter(r => r.date === '2026-02-03').reduce((s, r) => s + (Number(r.amount) || 0), 0);
        const mtdTotal = revenueRows.reduce((s, r) => s + (Number(r.amount) || 0), 0);
        const donationTotal = revenueRows.filter(r => (r.source || '').toLowerCase().indexOf('donations') >= 0).reduce((s, r) => s + (Number(r.amount) || 0), 0);
        const insuranceTotal = revenueRows.filter(r => (r.source || '').toLowerCase().indexOf('insurance') >= 0).reduce((s, r) => s + (Number(r.amount) || 0), 0);

        const elToday = document.getElementById('revStatToday');
        if (elToday) elToday.textContent = money(todayTotal);
        const elMtd = document.getElementById('revStatMtd');
        if (elMtd) elMtd.textContent = money(mtdTotal);
        const elDon = document.getElementById('revStatDonations');
        if (elDon) elDon.textContent = money(donationTotal);
        const elIns = document.getElementById('revStatInsurance');
        if (elIns) elIns.textContent = money(insuranceTotal);

        const trendLabels = ['Jan 28', 'Jan 29', 'Jan 30', 'Jan 31', 'Feb 01', 'Feb 02', 'Feb 03'];
        const trendValues = [48000, 118000, 55200, 60100, 69000, 52300, todayTotal];

        new Chart(document.getElementById('revenueTrendChart'), {
            type: 'line',
            data: {
                labels: trendLabels,
                datasets: [{
                    label: 'Revenue',
                    data: trendValues,
                    borderColor: '#2563EB',
                    backgroundColor: 'rgba(37, 99, 235, 0.12)',
                    fill: true,
                    tension: 0.35,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: {
                            callback: function (v) { return money(v); }
                        }
                    }
                }
            }
        });

        new Chart(document.getElementById('revenueMethodChart'), {
            type: 'doughnut',
            data: {
                labels: ['Cash', 'Card', 'Online', 'Checks'],
                datasets: [{
                    data: [1850, 4250, 1600, 3200],
                    backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#A855F7'],
                    borderWidth: 0
                }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });

        new Chart(document.getElementById('revenueDeptChart'), {
            type: 'bar',
            data: {
                labels: ['ER', 'OPD', 'Laboratory', 'Pharmacy', 'ICU'],
                datasets: [{
                    label: 'Revenue',
                    data: [12500, 8400, 16200, 22300, 19800],
                    backgroundColor: ['#60A5FA', '#34D399', '#FBBF24', '#A78BFA', '#F87171'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        ticks: {
                            callback: function (v) { return money(v); }
                        }
                    }
                }
            }
        });
    </script>
</body>

</html>
