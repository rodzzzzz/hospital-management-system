<?php require_once __DIR__ . '/../auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Management - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .lbl{display:block;font-size:.75rem;font-weight:500;color:#374151;margin-bottom:.2rem}
        .inp{display:block;width:100%;padding:.35rem .6rem;font-size:.875rem;border:1px solid #d1d5db;border-radius:.5rem;outline:none}
        .inp:focus{border-color:#93c5fd;box-shadow:0 0 0 2px rgba(59,130,246,.2)}
        textarea.inp{resize:vertical}
        .abtn{font-size:.7rem;padding:.25rem .5rem;border-radius:.4rem;cursor:pointer}
        .tab-btn.active{border-bottom:2px solid #2563eb;color:#2563eb;font-weight:600}
        .vtab.active{border-bottom:2px solid #2563eb;color:#2563eb;font-weight:600}
        @media print{.np{display:none!important}main{margin-left:0!important;padding:16px!important}}
    </style>
</head>
<body class="bg-gray-50">
<<<<<<< HEAD:frontend/Ward/ward-management.php
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/ward-sidebar.php'; ?>
        <main class="ml-64 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ward Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Oversee ward patients, nurses' notes, and ward census.</p>
                </div>
            </div>

            <!-- Dashboard Section -->
            <section id="dashboard" class="ward-section mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pediatrics</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statPedia">—</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-child text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">OB-GYN</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statObgyne">—</p>
                            </div>
                            <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-venus text-pink-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Surgical</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statSurgical">—</p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-scalpel text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Medical</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statMedical">—</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-heart-pulse text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ward Overview</h2>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-hospital text-4xl mb-3"></i>
                        <p class="text-sm">Ward overview data will appear here.</p>
                    </div>
                </div>
            </section>

            <!-- Pediatrics Ward Section -->
            <section id="pedia" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Pediatrics Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Pediatrics.</p>
                        </div>
                        <button type="button" id="btnRefreshPedia" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-child text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in Pediatrics Ward.</p>
                    </div>
                </div>
            </section>

            <!-- OB-GYN Ward Section -->
            <section id="obgyne" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">OB-GYN Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Obstetrics & Gynecology.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-venus text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in OB-GYN Ward.</p>
                    </div>
                </div>
            </section>

            <!-- Surgical Ward Section -->
            <section id="surgical" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Surgical Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Surgery.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-scalpel text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in Surgical Ward.</p>
                    </div>
                </div>
            </section>

            <!-- Medical Ward Section -->
            <section id="medical" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Medical Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Internal Medicine.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-heart-pulse text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in Medical Ward.</p>
                    </div>
                </div>
            </section>

            <!-- Ward Census Section -->
            <section id="census" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Ward Census</h2>
                            <p class="text-sm text-gray-600 mt-1">Daily patient census across all wards.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-file-export mr-2"></i>Export
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-list-check text-4xl mb-3"></i>
                        <p class="text-sm">Census data will appear here.</p>
                    </div>
                </div>
            </section>

            <!-- Nurse's Notes Section -->
            <section id="nurses-notes" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Nurse's Notes</h2>
                            <p class="text-sm text-gray-600 mt-1">Nursing notes and shift reports for admitted patients.</p>
                        </div>
                        <button type="button" id="btnAddNote" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Note
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-notes-medical text-4xl mb-3"></i>
                        <p class="text-sm">No nursing notes recorded yet.</p>
                    </div>
                </div>
            </section>
        </main>
=======
<div class="min-h-screen">
<?php include __DIR__ . '/includes/double-sidebar.php'; ?>
<main class="ml-16 lg:ml-80 p-6" id="wMain">
<div class="flex items-center justify-between mb-5">
  <div><h1 class="text-2xl font-bold text-gray-900">Ward Management</h1><p class="text-sm text-gray-500">Patient tracking · Vitals · Fluid Balance · Orders · MAR</p></div>
  <span id="arBadge" class="text-xs text-gray-400 hidden np"><i class="fas fa-rotate-right mr-1"></i>Auto-refresh on</span>
</div>

<!-- DASHBOARD -->
<section id="dashboard" class="ws">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-5">
    <?php foreach([['statPedia','Pediatrics','fa-child','blue'],['statObgyne','OB-GYN','fa-venus','pink'],['statSurgical','Surgical','fa-scalpel','red'],['statMedical','Medical','fa-heart-pulse','green']] as [$id,$lbl,$ico,$c]): ?>
    <div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3">
      <div class="w-10 h-10 bg-<?=$c?>-50 rounded-xl flex items-center justify-center shrink-0"><i class="fas <?=$ico?> text-<?=$c?>-600"></i></div>
      <div><p class="text-xs text-gray-500"><?=$lbl?></p><p class="text-2xl font-bold text-gray-900" id="<?=$id?>">—</p></div>
>>>>>>> 7345180189dfdaa963ba4943c9b3e48c4b5be0f1:frontend/ward-management.php
    </div>
    <?php endforeach; ?>
  </div>
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-5">
    <div class="bg-white rounded-xl shadow-sm p-4"><h3 class="text-sm font-semibold text-gray-700 mb-3">Ward Occupancy</h3><canvas id="cOcc" height="160"></canvas></div>
    <div class="bg-white rounded-xl shadow-sm p-4"><h3 class="text-sm font-semibold text-gray-700 mb-3">Admissions — Last 7 Days</h3><canvas id="cAdm" height="160"></canvas></div>
  </div>
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b flex items-center justify-between"><h3 class="text-sm font-semibold text-gray-700">Recent Admissions</h3><span id="statTot" class="text-xs text-gray-400"></span></div>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Patient</th><th class="px-4 py-3 text-left">Ward</th><th class="px-4 py-3 text-left">Bed</th><th class="px-4 py-3 text-left">Physician</th><th class="px-4 py-3 text-left">Diagnosis</th><th class="px-4 py-3 text-left">LOS</th></tr></thead>
    <tbody id="dashTbody" class="divide-y divide-gray-100 text-sm text-gray-700"><tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Loading...</td></tr></tbody></table></div>
  </div>
</section>

<!-- WARD SECTIONS -->
<?php foreach([['pedia','Pediatrics Ward','fa-child','blue'],['obgyne','OB-GYN Ward','fa-venus','pink'],['surgical','Surgical Ward','fa-scalpel','red'],['medical','Medicine Ward','fa-heart-pulse','green']] as [$id,$lbl,$ico,$c]): ?>
<section id="<?=$id?>" class="ws hidden">
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-4 py-3 border-b flex flex-wrap items-center gap-2">
      <i class="fas <?=$ico?> text-<?=$c?>-600"></i><h3 class="font-semibold text-gray-800 mr-auto"><?=$lbl?></h3>
      <span class="wc-badge text-xs bg-gray-100 text-gray-600 rounded-full px-2 py-0.5"></span>
      <input type="text" placeholder="Search..." class="w-search text-sm border border-gray-200 rounded-lg px-3 py-1.5 w-48 outline-none" data-ward="<?=$id?>">
      <button class="w-bmap-btn text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50" data-ward="<?=$id?>"><i class="fas fa-border-all mr-1"></i>Bed Map</button>
      <button class="w-ref-btn text-xs px-3 py-1.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700" data-ward="<?=$id?>"><i class="fas fa-rotate mr-1"></i>Refresh</button>
    </div>
    <div class="w-bed-map hidden px-4 py-2 bg-gray-50 border-b" id="bmap-<?=$id?>"></div>
    <div class="p-4 grid grid-cols-1 xl:grid-cols-2 gap-4" id="cards-<?=$id?>"><div class="col-span-full text-center py-10 text-gray-400"><i class="fas <?=$ico?> text-3xl mb-2 block"></i><p class="text-sm">Loading...</p></div></div>
  </div>
</section>
<?php endforeach; ?>

<!-- VITALS MONITOR -->
<section id="vitals-monitor" class="ws hidden">
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b flex items-center justify-between"><h3 class="font-semibold text-gray-800"><i class="fas fa-heart-pulse text-rose-500 mr-2"></i>Vitals Monitor — All Wards</h3><button id="btnRefVit" class="text-xs px-3 py-1.5 bg-gray-800 text-white rounded-lg hover:bg-gray-700"><i class="fas fa-rotate mr-1"></i>Refresh</button></div>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Patient</th><th class="px-4 py-3 text-left">Ward</th><th class="px-3 py-3 text-center">Temp</th><th class="px-3 py-3 text-center">BP</th><th class="px-3 py-3 text-center">Pulse</th><th class="px-3 py-3 text-center">RR</th><th class="px-3 py-3 text-center">O₂%</th><th class="px-3 py-3 text-center">Pain</th><th class="px-4 py-3 text-left">Recorded</th><th class="px-3 py-3 text-center">Action</th></tr></thead>
    <tbody id="vmTbody" class="divide-y divide-gray-100 text-sm text-gray-700"><tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">Loading...</td></tr></tbody></table></div>
  </div>
</section>

<!-- FLUID BALANCE -->
<section id="fluid-balance" class="ws hidden">
  <div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex flex-wrap items-center gap-3 mb-4">
      <h3 class="font-semibold text-gray-800 mr-auto"><i class="fas fa-droplet text-blue-500 mr-2"></i>Fluid Balance — I&amp;O</h3>
      <select id="fbPat" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 w-56 outline-none"><option value="">— Select Patient —</option></select>
      <input type="date" id="fbDate" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none">
      <button id="btnAddFluid" class="text-xs px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700"><i class="fas fa-plus mr-1"></i>Add Entry</button>
    </div>
    <div class="grid grid-cols-3 gap-4 mb-4">
      <div class="bg-blue-50 rounded-xl p-4 text-center"><p class="text-xs text-blue-600 font-medium">Total Intake</p><p class="text-2xl font-bold text-blue-800 mt-1" id="fbIn">—</p><p class="text-xs text-blue-500">mL</p></div>
      <div class="bg-orange-50 rounded-xl p-4 text-center"><p class="text-xs text-orange-600 font-medium">Total Output</p><p class="text-2xl font-bold text-orange-800 mt-1" id="fbOut">—</p><p class="text-xs text-orange-500">mL</p></div>
      <div class="bg-gray-50 rounded-xl p-4 text-center"><p class="text-xs text-gray-600 font-medium">Net Balance</p><p class="text-2xl font-bold mt-1" id="fbNet">—</p><p class="text-xs text-gray-500">mL</p></div>
    </div>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Time</th><th class="px-4 py-3 text-left">Shift</th><th class="px-4 py-3 text-left">Type</th><th class="px-4 py-3 text-right">mL</th><th class="px-4 py-3 text-left">Notes</th><th class="px-4 py-3 text-left">By</th></tr></thead>
    <tbody id="fbTbody" class="divide-y divide-gray-100 text-sm text-gray-700"><tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">Select a patient.</td></tr></tbody></table></div>
  </div>
</section>

<!-- MAR -->
<section id="mar" class="ws hidden">
  <div class="bg-white rounded-xl shadow-sm p-5">
    <div class="flex flex-wrap items-center gap-3 mb-4">
      <h3 class="font-semibold text-gray-800 mr-auto"><i class="fas fa-pills text-purple-600 mr-2"></i>Medication Administration Record (MAR)</h3>
      <select id="marPat" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 w-56 outline-none"><option value="">— Select Patient —</option></select>
      <button id="btnAddMed" class="text-xs px-3 py-1.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700"><i class="fas fa-plus mr-1"></i>Add Medication</button>
    </div>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Medication</th><th class="px-4 py-3 text-left">Dose</th><th class="px-4 py-3 text-left">Route</th><th class="px-4 py-3 text-left">Freq</th><th class="px-4 py-3 text-center">Sched.</th><th class="px-4 py-3 text-center">Status</th><th class="px-4 py-3 text-left">Given By/At</th><th class="px-4 py-3 text-center">Action</th></tr></thead>
    <tbody id="marTbody" class="divide-y divide-gray-100 text-sm text-gray-700"><tr><td colspan="8" class="px-4 py-8 text-center text-gray-400">Select a patient.</td></tr></tbody></table></div>
  </div>
</section>

<!-- SHIFT REPORT -->
<section id="shift-report" class="ws hidden">
  <div class="bg-white rounded-xl shadow-sm p-5 np">
    <div class="flex flex-wrap items-center gap-3 mb-4">
      <h3 class="font-semibold text-gray-800 mr-auto"><i class="fas fa-file-medical text-teal-600 mr-2"></i>Shift Report / Handover</h3>
      <select id="srWard" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none"><option value="">All Wards</option><option value="pedia">Pediatrics</option><option value="obgyne">OB-GYN</option><option value="surgical">Surgical</option><option value="medical">Medical</option></select>
      <select id="srShift" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none"><option value="AM">AM (7–3)</option><option value="PM">PM (3–11)</option><option value="NOC">NOC (11–7)</option></select>
      <input type="date" id="srDate" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none">
      <button id="btnGenRep" class="text-xs px-3 py-1.5 bg-teal-600 text-white rounded-lg hover:bg-teal-700"><i class="fas fa-wand-magic-sparkles mr-1"></i>Generate</button>
      <button onclick="window.print()" class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50"><i class="fas fa-print mr-1"></i>Print</button>
    </div>
  </div>
  <div id="srContent" class="mt-4"></div>
</section>

<!-- CENSUS -->
<section id="census" class="ws hidden">
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4" id="censusCards"></div>
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b flex items-center justify-between"><h3 class="font-semibold text-gray-800">Full Census</h3><button onclick="window.print()" class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg hover:bg-gray-50 np"><i class="fas fa-print mr-1"></i>Print</button></div>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Ward</th><th class="px-4 py-3 text-left">Patient</th><th class="px-4 py-3 text-left">Code</th><th class="px-4 py-3 text-left">Sex/Age</th><th class="px-4 py-3 text-left">Bed</th><th class="px-4 py-3 text-left">Physician</th><th class="px-4 py-3 text-left">Diagnosis</th><th class="px-4 py-3 text-left">Admitted</th><th class="px-4 py-3 text-center">LOS</th></tr></thead>
    <tbody id="censusTbody" class="divide-y divide-gray-100 text-sm text-gray-700"><tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">Loading...</td></tr></tbody></table></div>
  </div>
</section>

<!-- NURSE'S NOTES -->
<section id="nurses-notes" class="ws hidden">
  <div class="bg-white rounded-xl shadow-sm overflow-hidden">
    <div class="px-5 py-3 border-b flex flex-wrap items-center gap-2">
      <h3 class="font-semibold text-gray-800 mr-auto"><i class="fas fa-notes-medical text-indigo-600 mr-2"></i>Nurse's Notes</h3>
      <input type="text" id="noteSrch" placeholder="Search patient, type, author..." class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 w-56 outline-none">
      <select id="noteWardF" class="text-sm border border-gray-200 rounded-lg px-3 py-1.5 outline-none"><option value="">All Wards</option><option value="pedia">Pediatrics</option><option value="obgyne">OB-GYN</option><option value="surgical">Surgical</option><option value="medical">Medical</option></select>
      <button id="btnAddNote" class="text-xs px-3 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"><i class="fas fa-plus mr-1"></i>Add Note</button>
    </div>
    <div id="notesList" class="p-4 space-y-3"><div class="text-center py-10 text-gray-400"><i class="fas fa-notes-medical text-3xl mb-2 block"></i><p class="text-sm">Loading...</p></div></div>
  </div>
</section>

</main>
</div>

<!-- ════════ MODALS ════════ -->
<!-- Patient Info -->
<div id="mInfo" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mInfo"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold" id="infoTitle">Patient Info</h4><p class="text-xs text-gray-500" id="infoSub"></p></div><button data-cm="mInfo" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <div class="p-5 overflow-y-auto flex-1" id="infoBody"></div>
  <div class="px-6 py-3 bg-gray-50 border-t flex justify-between"><a href="discharge.php" class="text-xs text-blue-600 hover:underline"><i class="fas fa-door-open mr-1"></i>Discharge Planning</a><button data-cm="mInfo" class="px-4 py-2 text-sm bg-gray-800 text-white rounded-lg">Close</button></div>
</div></div></div>

<!-- Vitals (tabbed) -->
<div id="mVit" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mVit"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold">Vital Signs</h4><p class="text-xs text-gray-500" id="vitSub"></p></div><button data-cm="mVit" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <div class="flex border-b px-5 gap-1">
    <button class="vtab active mr-3 py-2 text-sm" data-vt="vRec">Record</button>
    <button class="vtab mr-3 py-2 text-sm text-gray-500" data-vt="vHist">History</button>
    <button class="vtab py-2 text-sm text-gray-500" data-vt="vTrend">Trend</button>
  </div>
  <div class="p-5 overflow-y-auto flex-1">
    <div id="vRec" class="vtp"><form id="vitFrm" class="grid grid-cols-2 gap-3">
      <input type="hidden" id="vAid"><input type="hidden" id="vPid">
      <div><label class="lbl">Temperature (°C)</label><input type="number" step="0.1" id="vTmp" class="inp"></div>
      <div><label class="lbl">Blood Pressure</label><input type="text" id="vBP" placeholder="120/80" class="inp"></div>
      <div><label class="lbl">Pulse (bpm)</label><input type="number" id="vPul" class="inp"></div>
      <div><label class="lbl">Respiratory Rate</label><input type="number" id="vRR" class="inp"></div>
      <div><label class="lbl">O₂ Sat (%)</label><input type="number" id="vO2" min="0" max="100" class="inp"></div>
      <div><label class="lbl">Pain Scale (0–10)</label><input type="number" id="vPain" min="0" max="10" class="inp"></div>
      <div><label class="lbl">Weight (kg)</label><input type="number" step="0.1" id="vWt" class="inp"></div>
      <div><label class="lbl">Blood Glucose</label><input type="number" step="0.1" id="vBG" class="inp"></div>
      <div><label class="lbl">Recorded By</label><input type="text" id="vBy" class="inp"></div>
      <div><label class="lbl">Recorded At</label><input type="datetime-local" id="vAt" class="inp"></div>
      <div class="col-span-2 flex justify-end"><button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">Save Vitals</button></div>
    </form></div>
    <div id="vHist" class="vtp hidden overflow-x-auto"><table class="min-w-full divide-y divide-gray-100 text-xs"><thead class="bg-gray-50 text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Date/Time</th><th class="px-3 py-2">Temp</th><th class="px-3 py-2">BP</th><th class="px-3 py-2">Pulse</th><th class="px-3 py-2">RR</th><th class="px-3 py-2">O₂%</th><th class="px-3 py-2">Pain</th><th class="px-3 py-2 text-left">By</th></tr></thead><tbody id="vHistTb"></tbody></table></div>
    <div id="vTrend" class="vtp hidden"><canvas id="vTrendC" height="220"></canvas></div>
  </div>
</div></div></div>

<!-- Dextrose / IV -->
<div id="mDext" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mDext"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold">IV / Dextrose Monitoring</h4><p class="text-xs text-gray-500" id="dxtSub"></p></div><button data-cm="mDext" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <div class="p-5 overflow-y-auto flex-1 space-y-4">
    <div id="dxtActive" class="hidden bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-gray-700"></div>
    <div class="border border-gray-200 rounded-xl p-4">
      <h5 class="text-sm font-semibold text-gray-700 mb-3">Add New Bottle</h5>
      <form id="dxtFrm" class="grid grid-cols-2 gap-3">
        <input type="hidden" id="dxtAid"><input type="hidden" id="dxtPid">
        <div class="col-span-2"><label class="lbl">Solution *</label><input type="text" id="dxtSol" placeholder="e.g. D5W 1L" required class="inp"></div>
        <div><label class="lbl">Volume (mL)</label><input type="number" id="dxtVol" value="1000" class="inp"></div>
        <div><label class="lbl">Rate (mL/hr)</label><input type="number" id="dxtRate" class="inp"></div>
        <div><label class="lbl">IV Site</label><input type="text" id="dxtSite" class="inp"></div>
        <div><label class="lbl">Started At</label><input type="datetime-local" id="dxtSt" class="inp"></div>
        <div><label class="lbl">Recorded By</label><input type="text" id="dxtBy" class="inp"></div>
        <div><label class="lbl">Notes</label><input type="text" id="dxtNotes" class="inp"></div>
        <div class="col-span-2 flex justify-end"><button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm">Add Bottle</button></div>
      </form>
    </div>
    <h5 class="text-sm font-semibold text-gray-700">Bottle History</h5>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100 text-xs"><thead class="bg-gray-50 text-gray-500 uppercase"><tr><th class="px-3 py-2">#</th><th class="px-3 py-2 text-left">Solution</th><th class="px-3 py-2">mL</th><th class="px-3 py-2">Rate</th><th class="px-3 py-2 text-left">Site</th><th class="px-3 py-2 text-left">Started</th><th class="px-3 py-2">Status</th><th class="px-3 py-2">Action</th></tr></thead><tbody id="dxtHistTb" class="divide-y divide-gray-100 text-gray-700"></tbody></table></div>
  </div>
</div></div></div>

<!-- Fluid I/O (from card) -->
<div id="mFluid" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mFluid"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold">Fluid Balance (I&amp;O)</h4><p class="text-xs text-gray-500" id="fluidSub"></p></div><button data-cm="mFluid" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <div class="p-5 overflow-y-auto flex-1 space-y-4">
    <div class="grid grid-cols-3 gap-3" id="fluidTots"></div>
    <form id="fluidFrm" class="grid grid-cols-2 gap-3 border border-gray-200 rounded-xl p-4">
      <input type="hidden" id="flAid"><input type="hidden" id="flPid">
      <div><label class="lbl">Type *</label><select id="flType" required class="inp"><option value="oral_intake">Oral Intake</option><option value="iv_intake">IV Intake</option><option value="urine_output">Urine Output</option><option value="drain_output">Drain Output</option><option value="vomit">Vomit</option><option value="other">Other</option></select></div>
      <div><label class="lbl">Shift</label><select id="flShift" class="inp"><option value="AM">AM</option><option value="PM">PM</option><option value="NOC">NOC</option></select></div>
      <div><label class="lbl">Volume (mL) *</label><input type="number" id="flVol" required class="inp"></div>
      <div><label class="lbl">Recorded By</label><input type="text" id="flBy" class="inp"></div>
      <div class="col-span-2"><label class="lbl">Notes</label><input type="text" id="flNotes" class="inp"></div>
      <div class="col-span-2 flex justify-end"><button type="submit" class="px-5 py-2 bg-blue-600 text-white rounded-lg text-sm">Add Entry</button></div>
    </form>
    <div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-100 text-xs"><thead class="bg-gray-50 text-gray-500 uppercase"><tr><th class="px-3 py-2 text-left">Time</th><th class="px-3 py-2 text-left">Shift</th><th class="px-3 py-2 text-left">Type</th><th class="px-3 py-2 text-right">mL</th><th class="px-3 py-2 text-left">By</th></tr></thead><tbody id="fluidHistTb" class="divide-y divide-gray-100 text-gray-700"></tbody></table></div>
  </div>
</div></div></div>

<!-- Orders -->
<div id="mOrders" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mOrders"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold">Physician Orders</h4><p class="text-xs text-gray-500" id="ordSub"></p></div><button data-cm="mOrders" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <div class="p-5 overflow-y-auto flex-1 space-y-4">
    <div id="ordList" class="space-y-2"></div>
    <div class="border border-gray-200 rounded-xl p-4">
      <h5 class="text-sm font-semibold text-gray-700 mb-3">Add New Order</h5>
      <form id="ordFrm" class="grid grid-cols-2 gap-3">
        <input type="hidden" id="ordAid"><input type="hidden" id="ordPid">
        <div><label class="lbl">Order Type</label><select id="ordType" class="inp"><option value="medication">Medication</option><option value="diet">Diet</option><option value="activity">Activity</option><option value="procedure">Procedure</option><option value="lab">Lab</option><option value="imaging">Imaging</option><option value="other">Other</option></select></div>
        <div><label class="lbl">Ordered By</label><input type="text" id="ordBy" class="inp"></div>
        <div class="col-span-2"><label class="lbl">Order Text *</label><textarea id="ordTxt" rows="2" required class="inp"></textarea></div>
        <div><label class="lbl">Noted By</label><input type="text" id="ordNoted" class="inp"></div>
        <div><label class="lbl">Ordered At</label><input type="datetime-local" id="ordAt" class="inp"></div>
        <div class="col-span-2 flex justify-end"><button type="submit" class="px-5 py-2 bg-orange-600 text-white rounded-lg text-sm">Add Order</button></div>
      </form>
    </div>
  </div>
</div></div></div>

<!-- MAR Modal -->
<div id="mMAR" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mMAR"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold">MAR — Medication Administration</h4><p class="text-xs text-gray-500" id="marSub"></p></div><button data-cm="mMAR" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <div class="p-5 overflow-y-auto flex-1 space-y-4">
    <div id="marList" class="space-y-2"></div>
    <div class="border border-gray-200 rounded-xl p-4">
      <h5 class="text-sm font-semibold text-gray-700 mb-3">Add Medication</h5>
      <form id="marFrm" class="grid grid-cols-2 gap-3">
        <input type="hidden" id="marAid"><input type="hidden" id="marPid">
        <div class="col-span-2"><label class="lbl">Medication Name *</label><input type="text" id="marName" required class="inp"></div>
        <div><label class="lbl">Dose</label><input type="text" id="marDose" class="inp"></div>
        <div><label class="lbl">Route</label><select id="marRoute" class="inp"><option value="oral">Oral</option><option value="IV">IV</option><option value="IM">IM</option><option value="SC">SC</option><option value="topical">Topical</option><option value="inhalation">Inhalation</option><option value="other">Other</option></select></div>
        <div><label class="lbl">Frequency</label><input type="text" id="marFreq" placeholder="e.g. Q8H" class="inp"></div>
        <div><label class="lbl">Sched. Time</label><input type="time" id="marTime" class="inp"></div>
        <div class="col-span-2 flex justify-end"><button type="submit" class="px-5 py-2 bg-purple-600 text-white rounded-lg text-sm">Add Medication</button></div>
      </form>
    </div>
  </div>
</div></div></div>

<!-- Notes Modal -->
<div id="mNotes" class="fixed inset-0 z-50 hidden"><div class="absolute inset-0 bg-black/50" data-cm="mNotes"></div>
<div class="relative flex items-center justify-center w-full h-full p-4"><div class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden flex flex-col max-h-[90vh]">
  <div class="flex items-center justify-between px-6 py-4 border-b"><div><h4 class="font-semibold">Nurse's Note</h4><p class="text-xs text-gray-500" id="noteSub"></p></div><button data-cm="mNotes" class="h-8 w-8 rounded-lg hover:bg-gray-100 text-gray-500"><i class="fas fa-xmark"></i></button></div>
  <form id="noteFrm" class="p-5 space-y-3 overflow-y-auto flex-1">
    <input type="hidden" id="noteAid"><input type="hidden" id="notePid"><input type="hidden" id="noteWard">
    <div><label class="lbl">Note Type</label><select id="noteType" class="inp"><option value="assessment">Assessment</option><option value="medication">Medication</option><option value="vital_signs">Vital Signs</option><option value="general">General</option><option value="incident">Incident</option><option value="progress">Progress</option></select></div>
    <div><label class="lbl">Note *</label><textarea id="noteTxt" rows="5" required class="inp"></textarea></div>
    <div><label class="lbl">Author / Nurse</label><input type="text" id="noteAuth" class="inp"></div>
    <div class="flex justify-end"><button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm">Save Note</button></div>
  </form>
</div></div></div>

<script>
/* ── UTILS ── */
const H=s=>String(s??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
function fmtDT(s){const d=new Date(String(s||'').replace(' ','T'));return isNaN(d)?'-':d.toLocaleString([],{month:'short',day:'2-digit',year:'numeric',hour:'numeric',minute:'2-digit'});}
function fmtD(s){const d=new Date(String(s||'').replace(' ','T'));return isNaN(d)?'-':d.toLocaleDateString([],{month:'short',day:'2-digit',year:'numeric'});}
function age(dob){if(!dob)return'?';const d=new Date(dob),n=new Date();let a=n.getFullYear()-d.getFullYear();if(n.getMonth()<d.getMonth()||(n.getMonth()===d.getMonth()&&n.getDate()<d.getDate()))a--;return a;}
function los(ad){return ad?Math.floor((Date.now()-new Date(String(ad).replace(' ','T')))/86400000):0;}
function losBadge(d){const c=d>=10?'bg-red-100 text-red-700':d>=5?'bg-orange-100 text-orange-700':'bg-gray-100 text-gray-600';return`<span class="px-2 py-0.5 rounded-full text-xs font-semibold ${c}">${d}d</span>`;}
function wardChip(w){const m={pedia:'bg-blue-100 text-blue-700',obgyne:'bg-pink-100 text-pink-700',surgical:'bg-red-100 text-red-700',medical:'bg-green-100 text-green-700'};const l={pedia:'Pedia',obgyne:'OB-GYN',surgical:'Surgical',medical:'Medical'};return`<span class="px-2 py-0.5 rounded-full text-xs font-semibold ${m[w]||'bg-gray-100 text-gray-600'}">${H(l[w]||w)}</span>`;}
function fallBadge(f){if(!f||f==='none')return'';const m={'high':'bg-red-100 text-red-700','medium':'bg-yellow-100 text-yellow-700','moderate':'bg-yellow-100 text-yellow-700','low':'bg-green-100 text-green-700'};return`<span class="px-1.5 py-0.5 rounded text-xs ${m[f]||'bg-gray-100 text-gray-600'}">Fall:${H(f.toUpperCase())}</span>`;}
function allergyBadge(n){return n?`<span class="px-1.5 py-0.5 rounded text-xs bg-red-50 text-red-600 border border-red-200" title="${H(n)}"><i class="fas fa-triangle-exclamation mr-0.5"></i>Allergy</span>`:''}
function marChip(s){const m={given:'bg-green-100 text-green-700',held:'bg-yellow-100 text-yellow-700',refused:'bg-red-100 text-red-700',not_available:'bg-gray-100 text-gray-600',pending:'bg-indigo-50 text-indigo-600'};return`<span class="px-2 py-0.5 rounded-full text-xs font-semibold ${m[s]||'bg-gray-100 text-gray-600'}">${H(s)}</span>`;}
function ordChip(t){const m={medication:'bg-purple-100 text-purple-700',diet:'bg-yellow-100 text-yellow-700',activity:'bg-blue-100 text-blue-700',procedure:'bg-red-100 text-red-700',lab:'bg-teal-100 text-teal-700',imaging:'bg-cyan-100 text-cyan-700',other:'bg-gray-100 text-gray-600'};return`<span class="px-2 py-0.5 rounded-full text-xs font-semibold ${m[t]||'bg-gray-100 text-gray-600'}">${H(t)}</span>`;}
function now15(){const d=new Date();d.setSeconds(0,0);return d.toISOString().slice(0,16);}
function toast(msg,ok=true){const t=document.createElement('div');t.className=`fixed bottom-6 right-6 z-[200] px-5 py-3 rounded-xl shadow-lg text-sm font-medium text-white ${ok?'bg-green-600':'bg-red-600'}`;t.textContent=msg;document.body.appendChild(t);setTimeout(()=>t.remove(),3500);}
function api(url,opts={}){return fetch(API_BASE_URL+url,opts).then(r=>r.json()).catch(()=>({ok:false,error:'Network error'}));}

/* ── MODALS ── */
function openM(id){document.getElementById(id).classList.remove('hidden');}
function closeM(id){document.getElementById(id).classList.add('hidden');}
document.addEventListener('click',e=>{const k=e.target.closest('[data-cm]');if(k)closeM(k.dataset.cm);});

/* ── SECTIONS ── */
const ALL_SECTIONS=['dashboard','pedia','obgyne','surgical','medical','census','nurses-notes','vitals-monitor','fluid-balance','mar','shift-report'];
const loaded={};
function getHash(){try{return(window.location.hash||'').replace(/^#/,'')||'dashboard';}catch(e){return'dashboard';}}
async function goSection(h){
  if(!ALL_SECTIONS.includes(h))h='dashboard';
  ALL_SECTIONS.forEach(id=>{const el=document.getElementById(id);if(el)el.classList.toggle('hidden',id!==h);});
  if(!loaded[h]){await loadSection(h);loaded[h]=true;}
}
window.addEventListener('hashchange',()=>goSection(getHash()));
document.getElementById('arBadge').classList.remove('hidden');
setInterval(()=>{loaded[getHash()]=false;goSection(getHash());},5*60*1000);
goSection(getHash());

async function loadSection(id){
  if(id==='dashboard')return loadDash();
  if(['pedia','obgyne','surgical','medical'].includes(id))return loadWard(id);
  if(id==='census')return loadCensus();
  if(id==='nurses-notes')return loadNotes();
  if(id==='vitals-monitor')return loadVM();
  if(id==='fluid-balance')return initFluidSection();
  if(id==='mar')return initMarSection();
  if(id==='shift-report')return initShiftReport();
}

/* ── DASHBOARD ── */
let cOcc=null,cAdm=null;
async function loadDash(){
  const r=await api('/ward_management/census.php');
  if(!r.ok)return;
  const wc={};(r.ward_counts||[]).forEach(w=>wc[w.ward]=w);
  ['pedia','obgyne','surgical','medical'].forEach(w=>{const el=document.getElementById('stat'+w.charAt(0).toUpperCase()+w.slice(1));if(el)el.textContent=wc[w]?.total_admitted||0;});
  const tot=r.totals||{};
  document.getElementById('statTot').textContent=`${tot.total_admitted||0} admitted · ${tot.admitted_today||0} today`;
  const wArr=['pedia','obgyne','surgical','medical'],wL=['Pediatrics','OB-GYN','Surgical','Medical'],wC=['rgba(59,130,246,.7)','rgba(236,72,153,.7)','rgba(239,68,68,.7)','rgba(34,197,94,.7)'];
  if(cOcc)cOcc.destroy();
  cOcc=new Chart(document.getElementById('cOcc'),{type:'bar',data:{labels:wL,datasets:[{label:'Patients',data:wArr.map(w=>parseInt(wc[w]?.total_admitted)||0),backgroundColor:wC,borderRadius:6}]},options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  const cd=r.admissions_chart||[],days=[],today=new Date();
  for(let i=6;i>=0;i--){const d=new Date(today);d.setDate(d.getDate()-i);days.push(d.toISOString().slice(0,10));}
  const dm={};cd.forEach(c=>dm[c.day]=parseInt(c.count)||0);
  if(cAdm)cAdm.destroy();
  cAdm=new Chart(document.getElementById('cAdm'),{type:'line',data:{labels:days.map(d=>new Date(d+'T00:00').toLocaleDateString([],{month:'short',day:'2-digit'})),datasets:[{label:'Admissions',data:days.map(d=>dm[d]||0),borderColor:'#2563eb',backgroundColor:'rgba(37,99,235,.1)',fill:true,tension:.3,pointRadius:4}]},options:{plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
  const rows=(r.patients||[]).slice(0,10),tb=document.getElementById('dashTbody');
  tb.innerHTML=rows.length?rows.map(p=>`<tr class="hover:bg-gray-50"><td class="px-4 py-3 font-medium">${H(p.full_name)}<br><span class="text-xs text-gray-400">${H(p.patient_code)}</span></td><td class="px-4 py-3">${wardChip(p.ward)}</td><td class="px-4 py-3 text-gray-600 text-xs">${H(p.bed_code||p.room_no||'-')}</td><td class="px-4 py-3 text-gray-600 text-xs">${H((p.admitting_physician||'-').substring(0,22))}</td><td class="px-4 py-3 text-gray-600 text-xs">${H((p.admitting_diagnosis||'-').substring(0,40))}</td><td class="px-4 py-3">${losBadge(los(p.admission_date))}</td></tr>`).join(''):'<tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No admitted patients.</td></tr>';
}

/* ── WARD SECTIONS ── */
async function loadWard(ward){
  const cont=document.getElementById('cards-'+ward);
  cont.innerHTML='<div class="col-span-full text-center py-10 text-gray-400"><i class="fas fa-rotate fa-spin text-xl"></i></div>';
  const r=await api(`/admissions/list.php?status=admitted&ward=${encodeURIComponent(ward)}&limit=100`);
  const pts=r.admissions||r.patients||[];
  document.querySelectorAll(`#${ward} .wc-badge`).forEach(b=>b.textContent=pts.length?`${pts.length} patients`:'Empty');
  if(!pts.length){const icons={pedia:'fa-child',obgyne:'fa-venus',surgical:'fa-scalpel',medical:'fa-heart-pulse'};cont.innerHTML=`<div class="col-span-full text-center py-10 text-gray-400"><i class="fas ${icons[ward]||'fa-bed'} text-3xl mb-2 block"></i><p class="text-sm">No patients in this ward.</p></div>`;renderBedMap(ward,[]);return;}
  const vr=await api('/ward_management/vitals_list.php?limit=300');
  const lv={};(vr.vitals||[]).forEach(v=>{if(!lv[v.patient_id])lv[v.patient_id]=v;});
  cont.innerHTML=pts.map(p=>buildCard(p,lv[p.patient_id]||null)).join('');
  renderBedMap(ward,pts);
}

function buildCard(p,vit){
  const d=los(p.admission_date),stale=vit?(Date.now()-new Date(String(vit.recorded_at||'').replace(' ','T')))>8*3600000:true;
  const bdr=stale?'border-l-4 border-l-orange-400':'border-l-4 border-l-green-400';
  const vs=vit?`<div class="flex gap-3 flex-wrap text-xs mt-2">
    ${vit.temperature?`<span class="text-gray-600"><i class="fas fa-thermometer-half text-orange-500 mr-0.5"></i>${vit.temperature}°C</span>`:''}
    ${vit.blood_pressure?`<span class="text-gray-600"><i class="fas fa-heart text-red-500 mr-0.5"></i>${H(vit.blood_pressure)}</span>`:''}
    ${vit.pulse_rate?`<span class="text-gray-600"><i class="fas fa-wave-square text-blue-500 mr-0.5"></i>${vit.pulse_rate}bpm</span>`:''}
    ${vit.oxygen_saturation?`<span class="${parseInt(vit.oxygen_saturation)<94?'text-red-600 font-semibold':'text-gray-600'}"><i class="fas fa-lungs mr-0.5"></i>${vit.oxygen_saturation}%</span>`:''}
    <span class="text-gray-400">${fmtDT(vit.recorded_at)}</span></div>`
    :`<div class="text-xs text-orange-500 mt-1"><i class="fas fa-clock mr-1"></i>No vitals recorded</div>`;
  const aid=p.admission_id||p.id,pid=p.patient_id||p.id,nm=H(p.full_name),w=H(p.ward||'');
  return`<div class="bg-white border border-gray-200 ${bdr} rounded-xl p-4 space-y-2 hover:shadow-md transition-shadow">
  <div class="flex items-start justify-between gap-2">
    <div><p class="font-semibold text-gray-900 text-sm">${nm}</p><p class="text-xs text-gray-400">${H(p.patient_code)} · ${H(p.sex||'?')} · ${age(p.dob)}y</p></div>
    <div class="flex flex-wrap gap-1 justify-end">${losBadge(d)}${fallBadge(p.fall_risk)}${allergyBadge(p.allergy_notes)}${p.code_status?`<span class="px-1.5 py-0.5 rounded text-xs bg-gray-100 text-gray-600">${H(p.code_status)}</span>`:''}</div>
  </div>
  <div class="grid grid-cols-2 gap-x-4 text-xs text-gray-600">
    <span><i class="fas fa-bed text-gray-400 mr-1"></i>${H(p.bed_code||p.room_no||'-')}</span>
    <span><i class="fas fa-user-doctor text-gray-400 mr-1"></i>${H((p.admitting_physician||'-').substring(0,22))}</span>
    <span class="col-span-2 mt-0.5"><i class="fas fa-stethoscope text-gray-400 mr-1"></i>${H((p.admitting_diagnosis||'-').substring(0,60))}</span>
  </div>
  ${vs}
  <div class="flex flex-wrap gap-1 pt-1 border-t border-gray-100">
    <button onclick="openInfo(${aid},${pid},'${nm}')" class="abtn bg-gray-100 text-gray-700 hover:bg-gray-200"><i class="fas fa-circle-info mr-0.5"></i>Info</button>
    <button onclick="openVit(${aid},${pid},'${nm}')" class="abtn bg-blue-50 text-blue-700 hover:bg-blue-100"><i class="fas fa-thermometer-half mr-0.5"></i>Vitals</button>
    <button onclick="openDext(${aid},${pid},'${nm}')" class="abtn bg-cyan-50 text-cyan-700 hover:bg-cyan-100"><i class="fas fa-syringe mr-0.5"></i>Dextrose</button>
    <button onclick="openFluidM(${aid},${pid},'${nm}')" class="abtn bg-sky-50 text-sky-700 hover:bg-sky-100"><i class="fas fa-droplet mr-0.5"></i>I&amp;O</button>
    <button onclick="openNoteM(${aid},${pid},'${nm}','${w}')" class="abtn bg-indigo-50 text-indigo-700 hover:bg-indigo-100"><i class="fas fa-notes-medical mr-0.5"></i>Notes</button>
    <button onclick="openOrd(${aid},${pid},'${nm}')" class="abtn bg-orange-50 text-orange-700 hover:bg-orange-100"><i class="fas fa-clipboard-list mr-0.5"></i>Orders</button>
    <button onclick="openMAR(${aid},${pid},'${nm}')" class="abtn bg-purple-50 text-purple-700 hover:bg-purple-100"><i class="fas fa-pills mr-0.5"></i>MAR</button>
  </div></div>`;}

function renderBedMap(ward,pts){
  const el=document.getElementById('bmap-'+ward);if(!el)return;
  if(!pts.length){el.innerHTML='<p class="text-xs text-gray-400 py-1">No bed data.</p>';return;}
  el.innerHTML='<div class="flex flex-wrap gap-2 py-1">'+pts.map(p=>{const code=H(p.bed_code||p.room_no||'?');return`<div class="flex flex-col items-center" title="${H(p.full_name)}"><div class="w-12 h-10 rounded-lg flex items-center justify-center bg-red-100 text-red-700 border border-red-300 text-xs font-semibold"><i class="fas fa-bed"></i></div><span class="text-xs text-gray-500 mt-0.5 max-w-[3rem] truncate">${code}</span></div>`}).join('')+'</div>';
}

document.querySelectorAll('.w-search').forEach(inp=>inp.addEventListener('input',function(){const q=this.value.toLowerCase(),w=this.dataset.ward;document.querySelectorAll(`#cards-${w} > div`).forEach(c=>c.style.display=c.textContent.toLowerCase().includes(q)?'':'none');}));
document.querySelectorAll('.w-ref-btn').forEach(btn=>btn.addEventListener('click',function(){const w=this.dataset.ward;loaded[w]=false;loadWard(w);}));
document.querySelectorAll('.w-bmap-btn').forEach(btn=>btn.addEventListener('click',function(){document.getElementById('bmap-'+this.dataset.ward)?.classList.toggle('hidden');}));

/* ── PATIENT INFO ── */
async function openInfo(aid,pid,name){
  document.getElementById('infoTitle').textContent=name;document.getElementById('infoSub').textContent='Loading...';
  document.getElementById('infoBody').innerHTML='<div class="text-center py-8 text-gray-400"><i class="fas fa-spinner fa-spin text-xl"></i></div>';
  openM('mInfo');
  const [ar,as]=await Promise.all([api(`/admissions/list.php?id=${aid}`),api(`/admissions/assessment_list.php?admission_id=${aid}`)]);
  const adm=ar?.admissions?.[0]||ar?.admission||null;
  if(!adm){document.getElementById('infoBody').innerHTML='<p class="text-red-500 text-sm">Could not load admission data.</p>';return;}
  document.getElementById('infoSub').textContent=`${adm.patient_code||''} · ${adm.ward||''}`;
  const row=(l,v)=>`<div><p class="text-xs text-gray-500">${l}</p><p class="text-sm font-medium text-gray-800">${H(v||'-')}</p></div>`;
  const ab=adm.allergy_notes?`<div class="bg-red-50 border border-red-200 rounded-lg px-4 py-2 text-sm text-red-700 mb-4"><i class="fas fa-triangle-exclamation mr-2"></i><b>Allergy:</b> ${H(adm.allergy_notes)}</div>`:'';
  const ass=as?.assessment||null;
  document.getElementById('infoBody').innerHTML=`${ab}<div class="grid grid-cols-2 gap-4 mb-4">${row('Admission No',adm.admission_no)}${row('Ward',adm.ward)}${row('Room/Bed',adm.room_no)}${row('Physician',adm.admitting_physician)}${row('Diagnosis',adm.admitting_diagnosis)}${row('Admitted',fmtD(adm.admission_date))}${row('Diet',adm.diet_notes)}${row('Code Status',adm.code_status)}</div>${ass?`<div class="border-t pt-4"><p class="text-xs font-semibold text-gray-500 uppercase mb-3">Nursing Assessment</p><div class="grid grid-cols-2 gap-4">${row('Chief Complaint',ass.chief_complaint)}${row('Fall Risk',ass.fall_risk)}${row('Consciousness',ass.level_of_consciousness)}${row('Mobility',ass.mobility_status)}${row('Pain Location',ass.pain_location)}${row('Skin',ass.skin_integrity)}</div></div>`:''}`;}

/* ── VITALS ── */
let _vAid=null,_tChart=null;
document.querySelectorAll('.vtab').forEach(b=>b.addEventListener('click',function(){
  document.querySelectorAll('.vtab').forEach(x=>{x.classList.remove('active');x.classList.add('text-gray-500');});
  this.classList.add('active');this.classList.remove('text-gray-500');
  document.querySelectorAll('.vtp').forEach(p=>p.classList.add('hidden'));
  document.getElementById(this.dataset.vt)?.classList.remove('hidden');
  if(this.dataset.vt==='vTrend'&&_vAid)loadTrend(_vAid);
}));

async function openVit(aid,pid,name){
  _vAid=aid;document.getElementById('vitSub').textContent=name;
  document.getElementById('vAid').value=aid;document.getElementById('vPid').value=pid;document.getElementById('vAt').value=now15();
  document.querySelectorAll('.vtab').forEach(b=>{b.classList.remove('active');b.classList.add('text-gray-500');});
  document.querySelector('[data-vt="vRec"]').classList.add('active');
  document.querySelectorAll('.vtp').forEach(p=>p.classList.add('hidden'));document.getElementById('vRec').classList.remove('hidden');
  openM('mVit');loadVHist(aid);
}
async function loadVHist(aid){
  const r=await api(`/ward_management/vitals_list.php?admission_id=${aid}&limit=20`);
  const rows=r.vitals||[],tb=document.getElementById('vHistTb');
  const flag=(v,ab)=>ab?`<span class="text-red-600 font-semibold">${H(v??'-')}</span>`:H(v??'-');
  tb.innerHTML=rows.length?rows.map(v=>`<tr class="hover:bg-gray-50"><td class="px-3 py-2 text-gray-500">${fmtDT(v.recorded_at)}</td><td class="px-3 py-2 text-center">${flag(v.temperature,parseFloat(v.temperature)>38.5)}</td><td class="px-3 py-2 text-center">${H(v.blood_pressure||'-')}</td><td class="px-3 py-2 text-center">${flag(v.pulse_rate,parseInt(v.pulse_rate)>120||parseInt(v.pulse_rate)<50)}</td><td class="px-3 py-2 text-center">${H(v.respiratory_rate||'-')}</td><td class="px-3 py-2 text-center">${flag(v.oxygen_saturation,parseInt(v.oxygen_saturation)<94)}</td><td class="px-3 py-2 text-center">${H(v.pain_scale??'-')}</td><td class="px-3 py-2">${H(v.recorded_by||'-')}</td></tr>`).join(''):'<tr><td colspan="8" class="px-3 py-4 text-center text-gray-400">No vitals recorded yet.</td></tr>';}
async function loadTrend(aid){
  const r=await api(`/ward_management/vitals_list.php?admission_id=${aid}&limit=20`);
  const rows=(r.vitals||[]).reverse();if(_tChart)_tChart.destroy();if(!rows.length)return;
  _tChart=new Chart(document.getElementById('vTrendC'),{type:'line',data:{labels:rows.map(v=>fmtDT(v.recorded_at)),datasets:[{label:'Temp°C',data:rows.map(v=>v.temperature||null),borderColor:'#f97316',tension:.3,yAxisID:'y'},{label:'Pulse',data:rows.map(v=>v.pulse_rate||null),borderColor:'#2563eb',tension:.3,yAxisID:'y1'},{label:'O₂%',data:rows.map(v=>v.oxygen_saturation||null),borderColor:'#16a34a',tension:.3,yAxisID:'y1'}]},options:{scales:{y:{type:'linear',position:'left',title:{display:true,text:'Temp°C'}},y1:{type:'linear',position:'right',grid:{drawOnChartArea:false},title:{display:true,text:'Pulse/O₂%'}}},plugins:{legend:{position:'bottom'}}}});}
document.getElementById('vitFrm').addEventListener('submit',async function(e){
  e.preventDefault();const btn=this.querySelector('[type="submit"]');btn.disabled=true;btn.textContent='Saving...';
  const r=await api('/ward_management/vitals_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({admission_id:parseInt(document.getElementById('vAid').value),patient_id:parseInt(document.getElementById('vPid').value),temperature:document.getElementById('vTmp').value,blood_pressure:document.getElementById('vBP').value,pulse_rate:document.getElementById('vPul').value,respiratory_rate:document.getElementById('vRR').value,oxygen_saturation:document.getElementById('vO2').value,pain_scale:document.getElementById('vPain').value,weight_kg:document.getElementById('vWt').value,blood_glucose:document.getElementById('vBG').value,recorded_by:document.getElementById('vBy').value,recorded_at:document.getElementById('vAt').value.replace('T',' ')})});
  btn.disabled=false;btn.textContent='Save Vitals';
  if(r.ok){toast('Vitals saved!');this.reset();document.getElementById('vAt').value=now15();loadVHist(_vAid);loaded['vitals-monitor']=false;}else toast(r.error||'Failed',false);});

/* ── DEXTROSE ── */
async function openDext(aid,pid,name){
  document.getElementById('dxtSub').textContent=name;document.getElementById('dxtAid').value=aid;document.getElementById('dxtPid').value=pid;document.getElementById('dxtSt').value=now15();
  openM('mDext');await loadDextH(aid);}
async function loadDextH(aid){
  const r=await api(`/ward_management/dextrose_list.php?admission_id=${aid}`),rows=r.dextrose||[],active=rows.find(x=>x.status==='running'),ac=document.getElementById('dxtActive');
  if(active){const el=Math.floor((Date.now()-new Date(String(active.started_at).replace(' ','T')))/3600000),rem=Math.max(0,parseInt(active.volume_ml)-(el*(parseInt(active.rate_ml_hr)||0)));ac.classList.remove('hidden');ac.innerHTML=`<p class="font-semibold text-blue-800 mb-1"><i class="fas fa-droplet mr-1"></i>Running: Bottle #${active.bottle_no} — ${H(active.solution)}</p><div class="grid grid-cols-3 gap-2 text-xs"><span><b>Rate:</b>${active.rate_ml_hr||'?'} mL/hr</span><span><b>Site:</b>${H(active.iv_site||'-')}</span><span><b>~Remaining:</b>${rem} mL</span></div><div class="flex gap-2 mt-2"><button onclick="updDext(${active.id},'completed')" class="text-xs px-3 py-1 bg-green-600 text-white rounded-lg">Mark Done</button><button onclick="updDext(${active.id},'discontinued')" class="text-xs px-3 py-1 bg-red-600 text-white rounded-lg">Discontinue</button></div>`;}else ac.classList.add('hidden');
  const sc={running:'bg-blue-100 text-blue-700',completed:'bg-green-100 text-green-700',discontinued:'bg-red-100 text-red-700'},tb=document.getElementById('dxtHistTb');
  tb.innerHTML=rows.length?rows.map(rw=>`<tr class="hover:bg-gray-50"><td class="px-3 py-2 text-center">${rw.bottle_no}</td><td class="px-3 py-2 font-medium">${H(rw.solution)}</td><td class="px-3 py-2 text-center">${rw.volume_ml}</td><td class="px-3 py-2 text-center">${rw.rate_ml_hr||'-'}</td><td class="px-3 py-2">${H(rw.iv_site||'-')}</td><td class="px-3 py-2">${fmtDT(rw.started_at)}</td><td class="px-3 py-2 text-center"><span class="px-2 py-0.5 rounded-full text-xs font-semibold ${sc[rw.status]||'bg-gray-100'}">${rw.status}</span></td><td class="px-3 py-2 text-center">${rw.status==='running'?`<button onclick="updDext(${rw.id},'completed')" class="text-xs text-green-600 hover:underline">Done</button>`:'—'}</td></tr>`).join(''):'<tr><td colspan="8" class="px-3 py-4 text-center text-gray-400">No IV entries.</td></tr>';}
async function updDext(id,action){const r=await api('/ward_management/dextrose_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,action})});if(r.ok){toast('IV updated!');loadDextH(parseInt(document.getElementById('dxtAid').value));}else toast(r.error||'Failed',false);}
document.getElementById('dxtFrm').addEventListener('submit',async function(e){
  e.preventDefault();const btn=this.querySelector('[type="submit"]');btn.disabled=true;btn.textContent='Adding...';
  const aid=parseInt(document.getElementById('dxtAid').value);
  const r=await api('/ward_management/dextrose_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({admission_id:aid,patient_id:parseInt(document.getElementById('dxtPid').value),solution:document.getElementById('dxtSol').value,volume_ml:document.getElementById('dxtVol').value,rate_ml_hr:document.getElementById('dxtRate').value,iv_site:document.getElementById('dxtSite').value,started_at:document.getElementById('dxtSt').value.replace('T',' '),recorded_by:document.getElementById('dxtBy').value,notes:document.getElementById('dxtNotes').value})});
  btn.disabled=false;btn.textContent='Add Bottle';
  if(r.ok){toast('IV bottle added!');this.reset();document.getElementById('dxtSt').value=now15();loadDextH(aid);}else toast(r.error||'Failed',false);});

/* ── FLUID MODAL (from card) ── */
let _flAid=null;
async function openFluidM(aid,pid,name){
  _flAid=aid;document.getElementById('fluidSub').textContent=name;document.getElementById('flAid').value=aid;document.getElementById('flPid').value=pid;
  openM('mFluid');await loadFluidMH(aid);}
async function loadFluidMH(aid){
  const r=await api(`/ward_management/fluid_list.php?admission_id=${aid}`);
  const rows=r.fluid_entries||[],tots=r.totals||{},tb=document.getElementById('fluidHistTb');
  const inp=tots.total_intake||0,out=tots.total_output||0,net=inp-out;
  document.getElementById('fluidTots').innerHTML=`<div class="bg-blue-50 rounded-xl p-3 text-center"><p class="text-xs text-blue-600">Intake</p><p class="text-xl font-bold text-blue-800">${inp}</p><p class="text-xs text-blue-500">mL</p></div><div class="bg-orange-50 rounded-xl p-3 text-center"><p class="text-xs text-orange-600">Output</p><p class="text-xl font-bold text-orange-800">${out}</p><p class="text-xs text-orange-500">mL</p></div><div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-600">Net</p><p class="text-xl font-bold ${net>=0?'text-green-700':'text-red-700'}">${net>=0?'+':''}${net}</p><p class="text-xs text-gray-500">mL</p></div>`;
  const typeLbl={oral_intake:'Oral',iv_intake:'IV',urine_output:'Urine',drain_output:'Drain',vomit:'Vomit',other:'Other'};
  tb.innerHTML=rows.length?rows.map(rw=>`<tr class="hover:bg-gray-50"><td class="px-3 py-2">${fmtDT(rw.recorded_at)}</td><td class="px-3 py-2">${H(rw.shift||'-')}</td><td class="px-3 py-2">${H(typeLbl[rw.entry_type]||rw.entry_type)}</td><td class="px-3 py-2 text-right font-medium">${rw.volume_ml}</td><td class="px-3 py-2">${H(rw.recorded_by||'-')}</td></tr>`).join(''):'<tr><td colspan="5" class="px-3 py-4 text-center text-gray-400">No entries yet.</td></tr>';}
document.getElementById('fluidFrm').addEventListener('submit',async function(e){
  e.preventDefault();const btn=this.querySelector('[type="submit"]');btn.disabled=true;btn.textContent='Adding...';
  const r=await api('/ward_management/fluid_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({admission_id:parseInt(document.getElementById('flAid').value),patient_id:parseInt(document.getElementById('flPid').value),entry_type:document.getElementById('flType').value,shift:document.getElementById('flShift').value,volume_ml:document.getElementById('flVol').value,recorded_by:document.getElementById('flBy').value,notes:document.getElementById('flNotes').value})});
  btn.disabled=false;btn.textContent='Add Entry';
  if(r.ok){toast('Entry added!');this.reset();loadFluidMH(_flAid);}else toast(r.error||'Failed',false);});

/* ── ORDERS ── */
let _oAid=null;
async function openOrd(aid,pid,name){
  _oAid=aid;document.getElementById('ordSub').textContent=name;document.getElementById('ordAid').value=aid;document.getElementById('ordPid').value=pid;document.getElementById('ordAt').value=now15();
  openM('mOrders');await loadOrdList(aid);}
async function loadOrdList(aid){
  const r=await api(`/ward_management/orders_list.php?admission_id=${aid}`);
  const rows=r.orders||[],active=rows.filter(x=>x.status==='active'),el=document.getElementById('ordList');
  el.innerHTML=active.length?active.map(o=>`<div class="flex items-start gap-3 bg-orange-50 border border-orange-100 rounded-xl p-3"><div class="flex-1">${ordChip(o.order_type)}<p class="text-sm text-gray-800 mt-1">${H(o.order_text)}</p><p class="text-xs text-gray-500 mt-1">By: ${H(o.ordered_by||'-')} · ${fmtDT(o.ordered_at)}</p></div><button onclick="updOrd(${o.id},'completed')" class="text-xs px-2 py-1 bg-green-600 text-white rounded-lg shrink-0">Done</button></div>`).join(''):'<p class="text-sm text-gray-400 text-center py-2">No active orders.</p>';}
async function updOrd(id,status){const r=await api('/ward_management/orders_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,action:status})});if(r.ok){toast('Order updated!');loadOrdList(_oAid);}else toast(r.error||'Failed',false);}
document.getElementById('ordFrm').addEventListener('submit',async function(e){
  e.preventDefault();const btn=this.querySelector('[type="submit"]');btn.disabled=true;btn.textContent='Adding...';
  const r=await api('/ward_management/orders_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({admission_id:parseInt(document.getElementById('ordAid').value),patient_id:parseInt(document.getElementById('ordPid').value),order_type:document.getElementById('ordType').value,order_text:document.getElementById('ordTxt').value,ordered_by:document.getElementById('ordBy').value,noted_by:document.getElementById('ordNoted').value,ordered_at:document.getElementById('ordAt').value.replace('T',' ')})});
  btn.disabled=false;btn.textContent='Add Order';
  if(r.ok){toast('Order added!');this.reset();document.getElementById('ordAt').value=now15();loadOrdList(_oAid);}else toast(r.error||'Failed',false);});

/* ── MAR MODAL ── */
let _mAid=null;
async function openMAR(aid,pid,name){
  _mAid=aid;document.getElementById('marSub').textContent=name;document.getElementById('marAid').value=aid;document.getElementById('marPid').value=pid;
  openM('mMAR');await loadMARList(aid);}
async function loadMARList(aid){
  const r=await api(`/ward_management/mar_list.php?admission_id=${aid}`),rows=r.mar||[],el=document.getElementById('marList');
  el.innerHTML=rows.length?rows.map(m=>`<div class="flex items-center gap-3 border border-gray-200 rounded-xl p-3"><div class="flex-1"><p class="text-sm font-semibold text-gray-800">${H(m.medication_name)}</p><p class="text-xs text-gray-500">${H(m.dose||'-')} · ${H(m.route||'-')} · ${H(m.frequency||'-')} · Sched: ${H(m.scheduled_time||'—')}</p></div>${marChip(m.status)}<div class="flex gap-1">${m.status==='pending'?`<button onclick="adminMAR(${m.id},'given')" class="text-xs px-2 py-1 bg-green-600 text-white rounded">Given</button><button onclick="adminMAR(${m.id},'held')" class="text-xs px-2 py-1 bg-yellow-500 text-white rounded">Hold</button><button onclick="adminMAR(${m.id},'refused')" class="text-xs px-2 py-1 bg-red-600 text-white rounded">Refused</button>`:''}</div></div>`).join(''):'<p class="text-sm text-gray-400 text-center py-2">No medications added yet.</p>';}
async function adminMAR(id,action){const r=await api('/ward_management/mar_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({id,action,given_by:'',given_at:now15().replace('T',' ')})});if(r.ok){toast('MAR updated!');loadMARList(_mAid);}else toast(r.error||'Failed',false);}
document.getElementById('marFrm').addEventListener('submit',async function(e){
  e.preventDefault();const btn=this.querySelector('[type="submit"]');btn.disabled=true;btn.textContent='Adding...';
  const r=await api('/ward_management/mar_save.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({admission_id:parseInt(document.getElementById('marAid').value),patient_id:parseInt(document.getElementById('marPid').value),medication_name:document.getElementById('marName').value,dose:document.getElementById('marDose').value,route:document.getElementById('marRoute').value,frequency:document.getElementById('marFreq').value,scheduled_time:document.getElementById('marTime').value})});
  btn.disabled=false;btn.textContent='Add Medication';
  if(r.ok){toast('Medication added!');this.reset();loadMARList(_mAid);}else toast(r.error||'Failed',false);});

/* ── NOTES MODAL ── */
async function openNoteM(aid,pid,name,ward){
  document.getElementById('noteSub').textContent=name;document.getElementById('noteAid').value=aid;document.getElementById('notePid').value=pid;document.getElementById('noteWard').value=ward;
  openM('mNotes');}
document.getElementById('noteFrm').addEventListener('submit',async function(e){
  e.preventDefault();const btn=this.querySelector('[type="submit"]');btn.disabled=true;btn.textContent='Saving...';
  const r=await api('/ward_management/notes_create.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({admission_id:parseInt(document.getElementById('noteAid').value),patient_id:parseInt(document.getElementById('notePid').value),ward:document.getElementById('noteWard').value,note_type:document.getElementById('noteType').value,note_text:document.getElementById('noteTxt').value,author_name:document.getElementById('noteAuth').value})});
  btn.disabled=false;btn.textContent='Save Note';
  if(r.ok){toast('Note saved!');closeM('mNotes');this.reset();loaded['nurses-notes']=false;}else toast(r.error||'Failed',false);});

/* ── VITALS MONITOR SECTION ── */
async function loadVM(){
  const r=await api('/ward_management/vitals_list.php?limit=200'),rows=r.vitals||[],tb=document.getElementById('vmTbody');
  const latest={};rows.forEach(v=>{if(!latest[v.patient_id])latest[v.patient_id]=v;});
  const arr=Object.values(latest);
  tb.innerHTML=arr.length?arr.map(v=>`<tr class="hover:bg-gray-50"><td class="px-4 py-3 font-medium">${H(v.full_name||'-')}<br><span class="text-xs text-gray-400">${H(v.patient_code||'')}</span></td><td class="px-4 py-3">${wardChip(v.ward)}</td><td class="px-3 py-3 text-center ${parseFloat(v.temperature)>38.5?'text-red-600 font-semibold':''}">${H(v.temperature||'-')}</td><td class="px-3 py-3 text-center">${H(v.blood_pressure||'-')}</td><td class="px-3 py-3 text-center ${parseInt(v.pulse_rate)>120||parseInt(v.pulse_rate)<50?'text-red-600 font-semibold':''}">${H(v.pulse_rate||'-')}</td><td class="px-3 py-3 text-center">${H(v.respiratory_rate||'-')}</td><td class="px-3 py-3 text-center ${parseInt(v.oxygen_saturation)<94?'text-red-600 font-semibold':''}">${H(v.oxygen_saturation||'-')}</td><td class="px-3 py-3 text-center">${H(v.pain_scale??'-')}</td><td class="px-4 py-3 text-xs text-gray-500">${fmtDT(v.recorded_at)}</td><td class="px-3 py-3 text-center"><button onclick="openVit(${v.admission_id},${v.patient_id},'${H(v.full_name||'')}')" class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100">Record</button></td></tr>`).join(''):'<tr><td colspan="10" class="px-4 py-8 text-center text-gray-400">No vitals data available.</td></tr>';
}
document.getElementById('btnRefVit').addEventListener('click',()=>{loaded['vitals-monitor']=false;loadVM();});

/* ── FLUID BALANCE SECTION ── */
async function initFluidSection(){
  const today=new Date().toISOString().slice(0,10);
  document.getElementById('fbDate').value=today;
  const r=await api('/ward_management/census.php');
  const pts=r.patients||[],sel=document.getElementById('fbPat');
  sel.innerHTML='<option value="">— Select Patient —</option>'+pts.map(p=>`<option value="${p.admission_id}|${p.patient_id}">${H(p.full_name)} (${H(p.ward)})</option>`).join('');
  sel.addEventListener('change',async function(){
    if(!this.value)return;const[aid,pid]=this.value.split('|');
    const d=document.getElementById('fbDate').value;
    const r=await api(`/ward_management/fluid_list.php?admission_id=${aid}&date=${d}`);
    const rows=r.fluid_entries||[],tots=r.totals||{};
    const inp=tots.total_intake||0,out=tots.total_output||0,net=inp-out;
    document.getElementById('fbIn').textContent=inp;document.getElementById('fbOut').textContent=out;
    document.getElementById('fbNet').textContent=(net>=0?'+':'')+net;document.getElementById('fbNet').className=`text-2xl font-bold mt-1 ${net>=0?'text-green-700':'text-red-700'}`;
    const typeLbl={oral_intake:'Oral',iv_intake:'IV',urine_output:'Urine',drain_output:'Drain',vomit:'Vomit',other:'Other'};
    document.getElementById('fbTbody').innerHTML=rows.length?rows.map(rw=>`<tr class="hover:bg-gray-50"><td class="px-4 py-3">${fmtDT(rw.recorded_at)}</td><td class="px-4 py-3">${H(rw.shift||'-')}</td><td class="px-4 py-3">${H(typeLbl[rw.entry_type]||rw.entry_type)}</td><td class="px-4 py-3 text-right font-medium">${rw.volume_ml}</td><td class="px-4 py-3">${H(rw.notes||'-')}</td><td class="px-4 py-3">${H(rw.recorded_by||'-')}</td></tr>`).join(''):'<tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No entries for this date.</td></tr>';
  });
  document.getElementById('fbDate').addEventListener('change',()=>document.getElementById('fbPat').dispatchEvent(new Event('change')));
  document.getElementById('btnAddFluid').addEventListener('click',()=>{
    const v=document.getElementById('fbPat').value;if(!v){toast('Select a patient first.',false);return;}
    const[aid,pid]=v.split('|');openFluidM(parseInt(aid),parseInt(pid),'Selected Patient');
  });}

/* ── MAR SECTION ── */
async function initMarSection(){
  const r=await api('/ward_management/census.php');
  const pts=r.patients||[],sel=document.getElementById('marPat');
  sel.innerHTML='<option value="">— Select Patient —</option>'+pts.map(p=>`<option value="${p.admission_id}|${p.patient_id}">${H(p.full_name)} (${H(p.ward)})</option>`).join('');
  sel.addEventListener('change',async function(){
    if(!this.value)return;const[aid,pid]=this.value.split('|');
    const r=await api(`/ward_management/mar_list.php?admission_id=${aid}`),rows=r.mar||[],tb=document.getElementById('marTbody');
    tb.innerHTML=rows.length?rows.map(m=>`<tr class="hover:bg-gray-50"><td class="px-4 py-3 font-medium">${H(m.medication_name)}</td><td class="px-4 py-3">${H(m.dose||'-')}</td><td class="px-4 py-3">${H(m.route||'-')}</td><td class="px-4 py-3">${H(m.frequency||'-')}</td><td class="px-4 py-3 text-center">${H(m.scheduled_time||'—')}</td><td class="px-4 py-3 text-center">${marChip(m.status)}</td><td class="px-4 py-3 text-xs">${m.given_by?H(m.given_by):'—'}${m.given_at?'<br>'+fmtDT(m.given_at):''}</td><td class="px-4 py-3 text-center">${m.status==='pending'?`<div class="flex gap-1 justify-center"><button onclick="adminMAR(${m.id},'given')" class="abtn bg-green-100 text-green-700">Given</button><button onclick="adminMAR(${m.id},'held')" class="abtn bg-yellow-100 text-yellow-700">Hold</button></div>`:'—'}</td></tr>`).join(''):'<tr><td colspan="8" class="px-4 py-6 text-center text-gray-400">No medications for this patient.</td></tr>';
  });
  document.getElementById('btnAddMed').addEventListener('click',()=>{
    const v=document.getElementById('marPat').value;if(!v){toast('Select a patient first.',false);return;}
    const[aid,pid]=v.split('|');openMAR(parseInt(aid),parseInt(pid),'Selected Patient');
  });}

/* ── SHIFT REPORT ── */
function initShiftReport(){
  const today=new Date().toISOString().slice(0,10);document.getElementById('srDate').value=today;
  document.getElementById('btnGenRep').addEventListener('click',genShiftReport);
}
async function genShiftReport(){
  const ward=document.getElementById('srWard').value,shift=document.getElementById('srShift').value,date=document.getElementById('srDate').value;
  const r=await api('/ward_management/census.php');
  let pts=r.patients||[];if(ward)pts=pts.filter(p=>p.ward===ward);
  const el=document.getElementById('srContent');
  if(!pts.length){el.innerHTML='<div class="bg-white rounded-xl shadow-sm p-8 text-center text-gray-400"><i class="fas fa-file-medical text-3xl mb-2 block"></i><p>No patients found for the selected criteria.</p></div>';return;}
  el.innerHTML=`<div class="bg-white rounded-xl shadow-sm p-6 print:shadow-none">
    <div class="text-center mb-6 print:mb-4"><h2 class="text-xl font-bold text-gray-900">SHIFT HANDOVER REPORT</h2><p class="text-sm text-gray-500">${H(shift)} Shift · ${fmtD(date)} · ${ward?wardChip(ward):'All Wards'}</p></div>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-5">${['pedia','obgyne','surgical','medical'].map(w=>{const c=pts.filter(p=>p.ward===w).length;return`<div class="bg-gray-50 rounded-xl p-3 text-center"><p class="text-xs text-gray-500">${w.toUpperCase()}</p><p class="text-2xl font-bold text-gray-900">${c}</p></div>`;}).join('')}</div>
    <table class="min-w-full divide-y divide-gray-100 text-sm"><thead class="bg-gray-50 text-xs text-gray-500 uppercase"><tr><th class="px-4 py-3 text-left">Patient</th><th class="px-4 py-3 text-left">Ward/Bed</th><th class="px-4 py-3 text-left">Diagnosis</th><th class="px-4 py-3 text-left">Physician</th><th class="px-4 py-3 text-center">LOS</th><th class="px-4 py-3 text-left">Flags</th></tr></thead>
    <tbody class="divide-y divide-gray-100">${pts.map(p=>`<tr><td class="px-4 py-3"><p class="font-medium">${H(p.full_name)}</p><p class="text-xs text-gray-400">${H(p.patient_code)} · ${H(p.sex||'?')} · ${age(p.dob)}y</p></td><td class="px-4 py-3">${wardChip(p.ward)}<br><span class="text-xs text-gray-500">${H(p.bed_code||p.room_no||'-')}</span></td><td class="px-4 py-3 text-xs">${H((p.admitting_diagnosis||'-').substring(0,50))}</td><td class="px-4 py-3 text-xs">${H((p.admitting_physician||'-').substring(0,22))}</td><td class="px-4 py-3 text-center">${losBadge(los(p.admission_date))}</td><td class="px-4 py-3">${fallBadge(p.fall_risk)} ${allergyBadge(p.allergy_notes)}</td></tr>`).join('')}</tbody></table>
    <div class="mt-8 grid grid-cols-2 gap-8"><div><p class="text-xs font-semibold text-gray-500 uppercase mb-2">Outgoing Nurse Signature</p><div class="border-b-2 border-gray-300 mt-8"></div></div><div><p class="text-xs font-semibold text-gray-500 uppercase mb-2">Incoming Nurse Signature</p><div class="border-b-2 border-gray-300 mt-8"></div></div></div>
  </div>`;}

/* ── CENSUS ── */
async function loadCensus(){
  const r=await api('/ward_management/census.php');if(!r.ok)return;
  const wc={};(r.ward_counts||[]).forEach(w=>wc[w.ward]=w);
  const wArr=[{w:'pedia',l:'Pediatrics',i:'fa-child',c:'blue'},{w:'obgyne',l:'OB-GYN',i:'fa-venus',c:'pink'},{w:'surgical',l:'Surgical',i:'fa-scalpel',c:'red'},{w:'medical',l:'Medical',i:'fa-heart-pulse',c:'green'}];
  document.getElementById('censusCards').innerHTML=wArr.map(({w,l,i,c})=>{const x=wc[w]||{};return`<div class="bg-white rounded-xl shadow-sm p-4 flex items-center gap-3"><div class="w-10 h-10 bg-${c}-50 rounded-xl flex items-center justify-center"><i class="fas ${i} text-${c}-600"></i></div><div><p class="text-xs text-gray-500">${l}</p><p class="text-xl font-bold text-gray-900">${x.total_admitted||0}</p><p class="text-xs text-gray-400">+${x.admitted_today||0} today</p></div></div>`;}).join('');
  const rows=r.patients||[],tb=document.getElementById('censusTbody');
  tb.innerHTML=rows.length?rows.map(p=>`<tr class="hover:bg-gray-50"><td class="px-4 py-3">${wardChip(p.ward)}</td><td class="px-4 py-3 font-medium">${H(p.full_name)}</td><td class="px-4 py-3 text-xs text-gray-500">${H(p.patient_code)}</td><td class="px-4 py-3 text-xs">${H(p.sex||'-')} / ${age(p.dob)}y</td><td class="px-4 py-3 text-xs">${H(p.bed_code||p.room_no||'-')}</td><td class="px-4 py-3 text-xs">${H((p.admitting_physician||'-').substring(0,22))}</td><td class="px-4 py-3 text-xs">${H((p.admitting_diagnosis||'-').substring(0,40))}</td><td class="px-4 py-3 text-xs">${fmtD(p.admission_date)}</td><td class="px-4 py-3 text-center">${losBadge(los(p.admission_date))}</td></tr>`).join(''):'<tr><td colspan="9" class="px-4 py-8 text-center text-gray-400">No admitted patients.</td></tr>';}

/* ── NURSE'S NOTES SECTION ── */
let _allNotes=[];
async function loadNotes(){
  const r=await api('/ward_management/notes_list.php?limit=100'),rows=r.notes||[];
  _allNotes=rows;renderNotes(rows);}
function renderNotes(rows){
  const el=document.getElementById('notesList');
  const typeBg={assessment:'bg-blue-50 border-blue-200',medication:'bg-purple-50 border-purple-200',vital_signs:'bg-rose-50 border-rose-200',general:'bg-gray-50 border-gray-200',incident:'bg-red-50 border-red-200',progress:'bg-green-50 border-green-200'};
  el.innerHTML=rows.length?rows.map(n=>`<div class="border rounded-xl p-4 ${typeBg[n.note_type]||'bg-gray-50 border-gray-200'}"><div class="flex items-center justify-between mb-1"><div class="flex items-center gap-2"><p class="text-sm font-semibold text-gray-800">${H(n.full_name||'Unknown')}</p>${wardChip(n.ward)}<span class="px-2 py-0.5 rounded-full text-xs bg-white border text-gray-600">${H(n.note_type)}</span></div><span class="text-xs text-gray-400">${fmtDT(n.created_at)}</span></div><p class="text-sm text-gray-700 mt-1">${H(n.note_text)}</p>${n.author_name?`<p class="text-xs text-gray-400 mt-1">— ${H(n.author_name)}</p>`:''}</div>`).join(''):'<div class="text-center py-10 text-gray-400"><i class="fas fa-notes-medical text-3xl mb-2 block"></i><p class="text-sm">No notes found.</p></div>';}
document.getElementById('noteSrch').addEventListener('input',function(){const q=this.value.toLowerCase(),wf=document.getElementById('noteWardF').value;renderNotes(_allNotes.filter(n=>(n.full_name||'').toLowerCase().includes(q)||(n.note_text||'').toLowerCase().includes(q)||(n.author_name||'').toLowerCase().includes(q)).filter(n=>!wf||n.ward===wf));});
document.getElementById('noteWardF').addEventListener('change',function(){const q=document.getElementById('noteSrch').value.toLowerCase();renderNotes(_allNotes.filter(n=>(!this.value||n.ward===this.value)&&((n.full_name||'').toLowerCase().includes(q)||(n.note_text||'').toLowerCase().includes(q))));});
document.getElementById('btnAddNote').addEventListener('click',()=>{document.getElementById('noteAid').value='0';document.getElementById('notePid').value='0';document.getElementById('noteWard').value='';document.getElementById('noteSub').textContent='New Note (all wards)';openM('mNotes');});
</script>
</body>
</html>
