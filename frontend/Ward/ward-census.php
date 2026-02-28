<?php require_once __DIR__ . '/../auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Census - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
<div class="min-h-screen">
    <?php include __DIR__ . '/includes/ward-sidebar.php'; ?>
    <main class="ml-64 p-8">

        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Ward Census</h1>
                <p class="text-sm text-gray-600 mt-1">Daily patient census across all wards</p>
            </div>
            <div class="flex gap-3 items-center">
                <input type="date" id="censusDate" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" id="btnRefreshCensus" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-rotate mr-2"></i>Refresh
                </button>
                <button type="button" id="btnPrintCensus" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">Total Patients</p><p class="text-2xl font-bold text-gray-900" id="cTotal">0</p></div>
                    <i class="fas fa-users text-blue-500 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">Pediatrics</p><p class="text-2xl font-bold text-blue-600" id="cPedia">0</p></div>
                    <i class="fas fa-child text-blue-400 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">OB-GYN</p><p class="text-2xl font-bold text-pink-600" id="cObgyn">0</p></div>
                    <i class="fas fa-venus text-pink-400 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">Medical</p><p class="text-2xl font-bold text-green-600" id="cMedical">0</p></div>
                    <i class="fas fa-heart-pulse text-green-400 text-2xl"></i>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div><p class="text-sm text-gray-500">Discharged Today</p><p class="text-2xl font-bold text-gray-600" id="cDischarged">0</p></div>
                    <i class="fas fa-door-open text-gray-400 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Census Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden mb-6">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h2 class="text-lg font-semibold text-gray-800">Patient Census</h2>
                <div class="flex gap-2">
                    <select id="filterWard" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Wards</option>
                        <option value="pediatrics">Pediatrics</option>
                        <option value="obgyn">OB-GYN</option>
                        <option value="medical">Medical</option>
                        <option value="icu">ICU</option>
                    </select>
                    <select id="filterStatus" class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="all">All Status</option>
                        <option value="admitted">Admitted</option>
                        <option value="discharged">Discharged</option>
                        <option value="transferred">Transferred</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="censusTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bed</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ward</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diagnosis</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admitted</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Days</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Physician</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody id="censusTableBody" class="bg-white divide-y divide-gray-200">
                        <tr><td colspan="8" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-spinner fa-spin text-2xl mb-2"></i><p class="text-sm">Loading census data...</p></td></tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ward Occupancy Breakdown -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-child text-blue-500 mr-2"></i>Pediatrics Ward</h3>
                <div id="breakdownPedia" class="space-y-3"></div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-venus text-pink-500 mr-2"></i>OB-GYN Ward</h3>
                <div id="breakdownObgyn" class="space-y-3"></div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-heart-pulse text-green-500 mr-2"></i>Medical Ward</h3>
                <div id="breakdownMedical" class="space-y-3"></div>
            </div>
        </div>

    </main>
</div>

<script>
// ─── Sample census data ───────────────────────────────────────────────────────
const censusData = [
    { id:1, name:'Rosario Dela Cruz',  code:'P-2026-001', bed:'MAT-01', ward:'obgyn',      diagnosis:'G2P1 Active Labor',     admitted:'2026-02-28', physician:'Dr. Reyes',    status:'admitted' },
    { id:2, name:'Maria Santos',       code:'P-2026-002', bed:'MAT-02', ward:'obgyn',      diagnosis:'G1P1 Post-NSD',         admitted:'2026-02-27', physician:'Dr. Reyes',    status:'admitted' },
    { id:3, name:'Ana Reyes',          code:'P-2026-003', bed:'GYN-01', ward:'obgyn',      diagnosis:'Post-op Myomectomy',    admitted:'2026-02-27', physician:'Dr. Cruz',     status:'admitted' },
    { id:4, name:'Baby Santos (F)',    code:'P-2026-004', bed:'NUR-01', ward:'obgyn',      diagnosis:'Term newborn, NSD',     admitted:'2026-02-27', physician:'Dr. Reyes',    status:'admitted' },
    { id:5, name:'Juan Dela Cruz',     code:'P-2026-005', bed:'A-02',   ward:'pediatrics', diagnosis:'Dengue Fever',          admitted:'2026-02-27', physician:'Dr. Santos',   status:'admitted' },
    { id:6, name:'Maria Santos Jr.',   code:'P-2026-006', bed:'A-01',   ward:'pediatrics', diagnosis:'Pneumonia',             admitted:'2026-02-25', physician:'Dr. Santos',   status:'admitted' },
    { id:7, name:'Pedro Lopez',        code:'P-2026-007', bed:'MED-01', ward:'medical',    diagnosis:'Hypertensive Crisis',   admitted:'2026-02-26', physician:'Dr. Garcia',   status:'admitted' },
    { id:8, name:'Lourdes Fernandez',  code:'P-2026-008', bed:'MED-02', ward:'medical',    diagnosis:'Type 2 DM w/ complications', admitted:'2026-02-24', physician:'Dr. Garcia', status:'admitted' },
    { id:9, name:'Roberto Tan',        code:'P-2026-009', bed:'—',      ward:'pediatrics', diagnosis:'Acute Gastroenteritis', admitted:'2026-02-26', physician:'Dr. Santos',   status:'discharged' },
];

const wardLabels = { pediatrics:'Pediatrics', obgyn:'OB-GYN', medical:'Medical', icu:'ICU' };
const statusColors = {
    admitted:   'bg-green-100 text-green-800',
    discharged: 'bg-gray-100 text-gray-600',
    transferred:'bg-blue-100 text-blue-800',
};

function daysSince(dateStr) {
    const d = new Date(dateStr);
    const now = new Date();
    return Math.max(0, Math.floor((now - d) / 86400000));
}

function renderTable(data) {
    const tbody = document.getElementById('censusTableBody');
    if (!data.length) {
        tbody.innerHTML = '<tr><td colspan="8" class="px-4 py-12 text-center text-gray-400"><i class="fas fa-list-check text-2xl mb-2"></i><p class="text-sm">No records found.</p></td></tr>';
        return;
    }
    tbody.innerHTML = data.map(r => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">
                <div class="font-medium text-gray-900 text-sm">${r.name}</div>
                <div class="text-xs text-gray-500">${r.code}</div>
            </td>
            <td class="px-4 py-3 text-sm text-gray-700">${r.bed}</td>
            <td class="px-4 py-3 text-sm">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700">${wardLabels[r.ward] || r.ward}</span>
            </td>
            <td class="px-4 py-3 text-sm text-gray-700">${r.diagnosis}</td>
            <td class="px-4 py-3 text-sm text-gray-600">${r.admitted}</td>
            <td class="px-4 py-3 text-sm font-semibold text-gray-800">${r.status === 'admitted' ? daysSince(r.admitted) + 'd' : '—'}</td>
            <td class="px-4 py-3 text-sm text-gray-700">${r.physician}</td>
            <td class="px-4 py-3">
                <span class="px-2 py-1 rounded-full text-xs font-semibold ${statusColors[r.status] || 'bg-gray-100 text-gray-600'}">${r.status.charAt(0).toUpperCase()+r.status.slice(1)}</span>
            </td>
        </tr>`).join('');
}

function renderBreakdown(containerId, ward) {
    const patients = censusData.filter(r => r.ward === ward && r.status === 'admitted');
    const el = document.getElementById(containerId);
    if (!patients.length) {
        el.innerHTML = '<p class="text-sm text-gray-400 text-center py-4">No admitted patients.</p>';
        return;
    }
    el.innerHTML = patients.map(p => `
        <div class="flex items-center justify-between py-2 border-b last:border-b-0">
            <div>
                <p class="text-sm font-medium text-gray-900">${p.name}</p>
                <p class="text-xs text-gray-500">${p.bed} &bull; ${p.diagnosis}</p>
            </div>
            <span class="text-xs font-semibold text-blue-600">${daysSince(p.admitted)}d</span>
        </div>`).join('');
}

function updateSummary(data) {
    document.getElementById('cTotal').textContent     = data.filter(r => r.status === 'admitted').length;
    document.getElementById('cPedia').textContent     = data.filter(r => r.ward === 'pediatrics' && r.status === 'admitted').length;
    document.getElementById('cObgyn').textContent     = data.filter(r => r.ward === 'obgyn' && r.status === 'admitted').length;
    document.getElementById('cMedical').textContent   = data.filter(r => r.ward === 'medical' && r.status === 'admitted').length;
    document.getElementById('cDischarged').textContent = data.filter(r => r.status === 'discharged').length;
}

function applyFilters() {
    const ward   = document.getElementById('filterWard').value;
    const status = document.getElementById('filterStatus').value;
    let filtered = censusData;
    if (ward !== 'all')   filtered = filtered.filter(r => r.ward === ward);
    if (status !== 'all') filtered = filtered.filter(r => r.status === status);
    renderTable(filtered);
}

document.addEventListener('DOMContentLoaded', function () {
    // Set today's date
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('censusDate').value = today;

    renderTable(censusData);
    updateSummary(censusData);
    renderBreakdown('breakdownPedia',   'pediatrics');
    renderBreakdown('breakdownObgyn',   'obgyn');
    renderBreakdown('breakdownMedical', 'medical');

    document.getElementById('filterWard').addEventListener('change', applyFilters);
    document.getElementById('filterStatus').addEventListener('change', applyFilters);

    document.getElementById('btnRefreshCensus').addEventListener('click', function () {
        renderTable(censusData);
        updateSummary(censusData);
        renderBreakdown('breakdownPedia',   'pediatrics');
        renderBreakdown('breakdownObgyn',   'obgyn');
        renderBreakdown('breakdownMedical', 'medical');
    });

    document.getElementById('btnPrintCensus').addEventListener('click', function () {
        window.print();
    });
});
</script>
</body>
</html>
