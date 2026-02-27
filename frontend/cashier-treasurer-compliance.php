<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Treasurer - Compliance & Audit</title>
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
                <div class="text-sm text-gray-600">Compliance &amp; Audit Data</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-clipboard-check"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Filed / Submitted</h2>
                            <p id="cmpStatFiled" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-hourglass-half"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">In Review</h2>
                            <p id="cmpStatReview" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-circle-xmark"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Pending</h2>
                            <p id="cmpStatPending" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-shield-halved"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Policies Active</h2>
                            <p id="cmpStatPolicies" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Compliance Status</h3>
                    <canvas id="cmpStatusPie" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Audit Findings (Sample)</h3>
                    <canvas id="cmpFindings" class="w-full h-64"></canvas>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Compliance Records (Sample)</h3>
                    <p class="text-sm text-gray-600 mt-1">Tax records, financial policies, audit logs/reports, regulatory filings.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200" id="cmpTbody"></tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        const rows = [
            { rec: 'Tax records', ref: 'BIR-1601EQ', period: 'Q1 2026', status: 'Filed', owner: 'Accounting' },
            { rec: 'Regulatory filings', ref: 'SEC-GIS', period: '2026', status: 'Submitted', owner: 'Legal' },
            { rec: 'Audit logs and reports', ref: 'AUD-2026-01', period: 'Jan 2026', status: 'In Review', owner: 'Internal Audit' },
            { rec: 'Financial policies', ref: 'POL-FIN-002', period: '2026', status: 'Active', owner: 'Treasurer' },
            { rec: 'Regulatory filings', ref: 'DOH-ANNUAL', period: '2025', status: 'Pending', owner: 'Compliance' },
        ];

        const tbody = document.getElementById('cmpTbody');
        if (tbody) {
            tbody.innerHTML = rows.map(r => {
                const st = (r.status || '').toLowerCase();
                const chip = st === 'filed' || st === 'submitted'
                    ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">' + r.status + '</span>'
                    : (st === 'in review'
                        ? '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">' + r.status + '</span>'
                        : (st === 'active'
                            ? '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">' + r.status + '</span>'
                            : '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">' + r.status + '</span>'));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${String(r.rec)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.ref)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.period)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">${chip}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${String(r.owner)}</td>
                    </tr>
                `;
            }).join('');
        }

        const filed = rows.filter(r => ['filed', 'submitted'].includes((r.status || '').toLowerCase())).length;
        const review = rows.filter(r => (r.status || '').toLowerCase() === 'in review').length;
        const pending = rows.filter(r => (r.status || '').toLowerCase() === 'pending').length;
        const policies = rows.filter(r => (r.rec || '').toLowerCase().indexOf('polic') >= 0 && (r.status || '').toLowerCase() === 'active').length;

        const elF = document.getElementById('cmpStatFiled');
        if (elF) elF.textContent = String(filed);
        const elR = document.getElementById('cmpStatReview');
        if (elR) elR.textContent = String(review);
        const elP = document.getElementById('cmpStatPending');
        if (elP) elP.textContent = String(pending);
        const elPol = document.getElementById('cmpStatPolicies');
        if (elPol) elPol.textContent = String(policies);

        new Chart(document.getElementById('cmpStatusPie'), {
            type: 'doughnut',
            data: {
                labels: ['Filed/Submitted', 'In Review', 'Pending', 'Policies Active'],
                datasets: [{ data: [filed, review, pending, policies], backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#3B82F6'], borderWidth: 0 }]
            },
            options: { responsive: true, plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });

        new Chart(document.getElementById('cmpFindings'), {
            type: 'bar',
            data: {
                labels: ['Access Logs', 'Missing Docs', 'Late Filings', 'Policy Gaps', 'Reconciliation'],
                datasets: [{ data: [6, 3, 1, 2, 4], backgroundColor: '#6366F1', borderWidth: 0 }]
            },
            options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    </script>
</body>
</html>
