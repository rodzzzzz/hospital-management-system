<?php
require_once __DIR__ . '/_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - PhilHealth</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .mobile-surface {
            border-radius: 28px;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 520px) {
            html {
                font-size: 15px;
            }

            body {
                padding: 0 !important;
                background: #ffffff !important;
                display: block !important;
            }

            .mobile-frame {
                width: 100vw !important;
                max-width: 100vw !important;
                height: 100dvh !important;
                max-height: 100dvh !important;
                border-radius: 0 !important;
                box-shadow: none !important;
            }

            .mobile-frame .px-5 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }

            .mobile-frame .pt-5 {
                padding-top: 1rem !important;
            }

            .mobile-frame .pb-4 {
                padding-bottom: 0.75rem !important;
            }

            .mobile-frame .py-5 {
                padding-top: 1rem !important;
                padding-bottom: 1rem !important;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-200 flex items-center justify-center p-4">
    <div class="mobile-frame w-full max-w-[420px] h-[820px] max-h-[calc(100vh-2rem)] bg-white shadow-2xl overflow-hidden mobile-surface relative flex flex-col">
        <div id="mobileDrawerOverlay" class="hidden absolute inset-0 bg-black/40 z-40"></div>
        <div id="mobileDrawer" class="absolute top-0 left-0 h-full w-[280px] bg-white z-50 -translate-x-full transition-transform duration-200 overflow-hidden no-scrollbar flex flex-col">
            <div class="px-5 py-5 bg-emerald-600 text-white">
                <div class="flex items-center justify-between">
                    <div class="font-extrabold">Chief Doctor Menu</div>
                    <button id="drawerClose" type="button" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Close menu">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="mt-3 text-xs opacity-90">CHIEF DOCTOR</div>
            </div>
            <div class="flex-1 overflow-y-auto no-scrollbar">
                <div class="p-3">
                    <a href="index.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-house text-emerald-600"></i>
                        <span class="font-semibold">Dashboard</span>
                    </a>
                    <a href="vehicle.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-truck-medical text-emerald-600"></i>
                        <span class="font-semibold">Vehicle</span>
                    </a>
                    <a href="camera.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-video text-emerald-600"></i>
                        <span class="font-semibold">Camera</span>
                    </a>
                    <a href="philhealth.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
                        <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                        <span class="font-semibold">PhilHealth</span>
                    </a>
                    <a href="daily-reports.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-chart-line text-emerald-600"></i>
                        <span class="font-semibold">Daily Reports</span>
                    </a>
                    <a href="chat.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-comments text-emerald-600"></i>
                        <span class="font-semibold">Chat Messages</span>
                    </a>
                    <a href="patients.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-user-injured text-emerald-600"></i>
                        <span class="font-semibold">Patients</span>
                    </a>
                    <a href="appointments.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-calendar-check text-emerald-600"></i>
                        <span class="font-semibold">Appointments</span>
                    </a>
                    <a href="lab-requests.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-vials text-emerald-600"></i>
                        <span class="font-semibold">Lab Requests</span>
                    </a>
                    <a href="profile.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-user-doctor text-emerald-600"></i>
                        <span class="font-semibold">Profile</span>
                    </a>
                </div>
            </div>

            <div class="p-3 border-t border-slate-200">
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                    <i class="fas fa-right-from-bracket text-emerald-600"></i>
                    <span class="font-semibold">Logout</span>
                </a>
            </div>
        </div>

        <div class="bg-emerald-600 text-white px-5 pt-5 pb-4">
            <div class="flex items-center justify-between">
                <button id="drawerOpen" type="button" class="w-10 h-10 rounded-xl bg-white/15 hover:bg-white/20 flex items-center justify-center" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="text-center">
                    <div class="text-xs font-semibold opacity-90">PHILHEALTH</div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="text-right leading-tight">
                        <div class="text-[10px] opacity-90">CHIEF DOCTOR</div>
                        <div class="text-[10px] font-bold opacity-95"><?php echo htmlspecialchars($doctorName !== '' ? $doctorName : 'Chief Doctor', ENT_QUOTES); ?></div>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-white/20 flex items-center justify-center overflow-hidden">
                        <i class="fas fa-user text-white"></i>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex items-center justify-between gap-3">
                <div>
                    <div class="text-sm font-extrabold">PhilHealth eClaims</div>
                </div>
                <button id="submitClaimBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">
                    <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                        <i class="fas fa-plus text-xs"></i>
                    </span>
                    <span class="text-sm">Submit Claim</span>
                </button>
            </div>
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="grid grid-cols-3 gap-3">
                <div class="bg-white border border-slate-200 rounded-2xl p-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-file-lines text-emerald-600"></i>
                    </div>
                    <div class="mt-2 text-[10px] font-bold text-slate-500">Total Claims</div>
                    <div id="statTotal" class="mt-1 text-lg font-extrabold text-slate-900">0</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-3">
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-circle-check text-emerald-600"></i>
                    </div>
                    <div class="mt-2 text-[10px] font-bold text-slate-500">Approved</div>
                    <div id="statApproved" class="mt-1 text-lg font-extrabold text-slate-900">0</div>
                </div>
                <div class="bg-white border border-slate-200 rounded-2xl p-3">
                    <div class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center">
                        <i class="fas fa-hourglass-half text-amber-600"></i>
                    </div>
                    <div class="mt-2 text-[10px] font-bold text-slate-500">Processing</div>
                    <div id="statProcessing" class="mt-1 text-lg font-extrabold text-slate-900">0</div>
                </div>
            </div>

            <div class="mt-5 bg-white border border-slate-200 rounded-2xl p-4">
                <div class="text-xs font-extrabold text-slate-700">Approval Rate Trend</div>
                <div class="mt-3 h-[90px] bg-emerald-50/60 rounded-xl border border-emerald-100 overflow-hidden flex items-end">
                    <svg viewBox="0 0 300 90" class="w-full h-full">
                        <path d="M0,70 C50,40 80,50 120,40 C160,30 180,50 220,35 C250,25 275,30 300,20 L300,90 L0,90 Z" fill="rgba(16,185,129,0.25)"></path>
                        <path d="M0,70 C50,40 80,50 120,40 C160,30 180,50 220,35 C250,25 275,30 300,20" fill="none" stroke="rgba(16,185,129,0.9)" stroke-width="3"></path>
                    </svg>
                </div>
            </div>

            <div class="mt-5 bg-white border border-slate-200 rounded-2xl p-4">
                <div class="text-xs font-extrabold text-slate-700">Claim Types</div>
                <div class="mt-3 flex items-center gap-4">
                    <div class="w-[120px] h-[120px] rounded-full bg-emerald-50 border border-emerald-100 flex items-center justify-center relative overflow-hidden">
                        <div class="absolute inset-0" style="background: conic-gradient(#10b981 0 55%, #34d399 55% 78%, #a7f3d0 78% 100%);"></div>
                        <div class="absolute inset-[14px] rounded-full bg-white border border-slate-200"></div>
                    </div>
                    <div class="flex-1 text-xs">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span><span class="font-bold text-slate-700">Outpatient</span></div>
                            <div class="font-extrabold text-slate-800">55%</div>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span><span class="font-bold text-slate-700">Inpatient</span></div>
                            <div class="font-extrabold text-slate-800">23%</div>
                        </div>
                        <div class="mt-2 flex items-center justify-between">
                            <div class="flex items-center gap-2"><span class="w-2.5 h-2.5 rounded-full bg-emerald-200"></span><span class="font-bold text-slate-700">Maternity</span></div>
                            <div class="font-extrabold text-slate-800">22%</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <div class="text-xs font-extrabold text-slate-700">Claims Overview</div>
                <div id="claimsList" class="mt-3 space-y-4"></div>
            </div>
        </div>
    </div>

    <div id="claimModal" class="hidden fixed inset-0 z-[90]">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-[380px] bg-white rounded-[28px] shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="font-extrabold text-slate-800 text-sm">Claim Details</div>
                    <button id="claimClose" type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times text-slate-600"></i>
                    </button>
                </div>
                <div class="px-5 py-5">
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <div id="modalCode" class="text-sm font-extrabold text-slate-800">-</div>
                            <div class="text-xs text-slate-500">Patient ID: <span id="modalPatient" class="font-bold text-slate-700">-</span></div>
                        </div>
                        <div id="modalStatus" class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-emerald-50 text-emerald-700 border border-emerald-200">Approved</div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-3">
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-3">
                            <div class="text-[10px] font-bold text-slate-600">Total Claim Amount</div>
                            <div id="modalTotal" class="mt-1 text-lg font-extrabold text-slate-900">-</div>
                        </div>
                        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-3">
                            <div class="text-[10px] font-bold text-slate-600">Approved Amount</div>
                            <div id="modalApproved" class="mt-1 text-lg font-extrabold text-slate-900">-</div>
                        </div>
                    </div>

                    <div class="mt-3 bg-rose-50 border border-rose-100 rounded-2xl p-3">
                        <div class="text-[10px] font-bold text-slate-600">Remaining Patient Balance</div>
                        <div id="modalRemaining" class="mt-1 text-lg font-extrabold text-rose-700">-</div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <div class="text-slate-400 font-bold">Claim Type</div>
                            <div id="modalType" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Diagnosis</div>
                            <div id="modalDiagnosis" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Submitted Date</div>
                            <div id="modalSubmitted" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Approved Date</div>
                            <div id="modalApprovedDate" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="text-xs font-extrabold text-slate-700">Processing Progress</div>
                        <div class="mt-2 w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div id="modalProgressBar" class="h-full bg-emerald-600" style="width: 0%"></div>
                        </div>
                        <div class="mt-2 text-[10px] text-slate-500"><span id="modalProgressText" class="font-bold text-slate-700">0%</span></div>
                    </div>

                    <div class="mt-4">
                        <div class="text-xs font-extrabold text-slate-700">Services Covered</div>
                        <div id="modalServices" class="mt-2 space-y-2 text-xs"></div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <button id="modalUpdateBtn" type="button" class="py-3 rounded-xl bg-emerald-600 text-white font-extrabold text-sm hover:bg-emerald-700">Update Status</button>
                        <button id="modalDownloadBtn" type="button" class="py-3 rounded-xl bg-white border border-slate-200 text-slate-800 font-extrabold text-sm hover:bg-slate-50">Download Report</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const openBtn = document.getElementById('drawerOpen');
            const closeBtn = document.getElementById('drawerClose');

            function openDrawer() {
                if (!drawer || !overlay) return;
                drawer.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');
            }

            function closeDrawer() {
                if (!drawer || !overlay) return;
                drawer.classList.add('-translate-x-full');
                overlay.classList.add('hidden');
            }

            openBtn?.addEventListener('click', openDrawer);
            closeBtn?.addEventListener('click', closeDrawer);
            overlay?.addEventListener('click', closeDrawer);

            const claims = [
                {
                    id: 'PH-2024-001',
                    patient: 'Juan Dela Cruz',
                    patient_id: 'P-001',
                    type: 'Claim Type',
                    diagnosis: 'Essential Hypertension',
                    total_amount: 5130,
                    approved_amount: 2050,
                    remaining_balance: 3080,
                    status: 'approved',
                    progress: 100,
                    submitted_date: '2024-01-12',
                    approved_date: '2024-01-15',
                    services: ['Laboratory Tests', 'Endocrinology Consultation', 'Medications'],
                    processor: 'Ms. Garcia'
                },
                {
                    id: 'PH-2024-002',
                    patient: 'Maria Santos',
                    patient_id: 'P-002',
                    type: 'Outpatient',
                    diagnosis: 'Type 2 Diabetes Mellitus',
                    total_amount: 7140,
                    approved_amount: 3570,
                    remaining_balance: 3570,
                    status: 'processing',
                    progress: 65,
                    submitted_date: '2024-01-13',
                    approved_date: 'Pending',
                    services: ['Laboratory Tests', 'Endocrinology Consultation', 'Medications'],
                    processor: 'Ms. Dela Cruz'
                },
                {
                    id: 'PH-2024-003',
                    patient: 'Roberto Garcia',
                    patient_id: 'P-003',
                    type: 'Inpatient',
                    diagnosis: 'Acute Myocardial Infarction',
                    total_amount: 125300,
                    approved_amount: 87500,
                    remaining_balance: 37800,
                    status: 'approved',
                    progress: 100,
                    submitted_date: '2024-01-10',
                    approved_date: '2024-01-20',
                    services: ['ER Services', 'Cardiology Consultation', 'Medications'],
                    processor: 'Ms. Ramos'
                },
                {
                    id: 'PH-2024-004',
                    patient: 'Ana Manandia',
                    patient_id: 'P-004',
                    type: 'Outpatient',
                    diagnosis: 'Prenatal Care',
                    total_amount: 7860,
                    approved_amount: 4716,
                    remaining_balance: 3144,
                    status: 'processing',
                    progress: 45,
                    submitted_date: '2024-01-21',
                    approved_date: 'Pending',
                    services: ['Laboratory Tests', 'OB Consultation'],
                    processor: 'Ms. Santos'
                }
            ];

            const statTotal = document.getElementById('statTotal');
            const statApproved = document.getElementById('statApproved');
            const statProcessing = document.getElementById('statProcessing');

            const list = document.getElementById('claimsList');
            const modal = document.getElementById('claimModal');
            const modalClose = document.getElementById('claimClose');

            const modalCode = document.getElementById('modalCode');
            const modalPatient = document.getElementById('modalPatient');
            const modalStatus = document.getElementById('modalStatus');
            const modalTotal = document.getElementById('modalTotal');
            const modalApproved = document.getElementById('modalApproved');
            const modalRemaining = document.getElementById('modalRemaining');
            const modalType = document.getElementById('modalType');
            const modalDiagnosis = document.getElementById('modalDiagnosis');
            const modalSubmitted = document.getElementById('modalSubmitted');
            const modalApprovedDate = document.getElementById('modalApprovedDate');
            const modalProgressBar = document.getElementById('modalProgressBar');
            const modalProgressText = document.getElementById('modalProgressText');
            const modalServices = document.getElementById('modalServices');

            const submitClaimBtn = document.getElementById('submitClaimBtn');
            const modalUpdateBtn = document.getElementById('modalUpdateBtn');
            const modalDownloadBtn = document.getElementById('modalDownloadBtn');

            function peso(n) {
                const x = Number(n);
                if (!Number.isFinite(x)) return '₱0';
                return '₱' + x.toLocaleString();
            }

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function statusLabel(s) {
                if (s === 'approved') return 'Approved';
                return 'Processing';
            }

            function statusClass(s) {
                if (s === 'approved') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                return 'bg-amber-50 text-amber-700 border border-amber-200';
            }

            function renderStats() {
                const total = claims.length;
                const approved = claims.filter(c => c.status === 'approved').length;
                const processing = claims.filter(c => c.status === 'processing').length;

                if (statTotal) statTotal.textContent = String(total);
                if (statApproved) statApproved.textContent = String(approved);
                if (statProcessing) statProcessing.textContent = String(processing);
            }

            function renderList() {
                if (!list) return;
                list.innerHTML = claims.map(c => {
                    const chip = '<span class="px-3 py-1 rounded-full text-[10px] font-extrabold ' + statusClass(c.status) + '">' + escapeHtml(statusLabel(c.status)) + '</span>';
                    const pct = Math.max(0, Math.min(100, Number(c.progress) || 0));
                    return (
                        '<button type="button" data-id="' + escapeHtml(c.id) + '" class="w-full text-left bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">'
                        + '  <div class="flex items-start gap-3">'
                        + '    <div class="w-10 h-10 rounded-2xl bg-blue-50 flex items-center justify-center border border-blue-100">'
                        + '      <i class="fas fa-shield-halved text-blue-600"></i>'
                        + '    </div>'
                        + '    <div class="flex-1">'
                        + '      <div class="flex items-start justify-between gap-3">'
                        + '        <div>'
                        + '          <div class="font-extrabold text-slate-800 text-sm">' + escapeHtml(c.id) + '</div>'
                        + '          <div class="text-xs text-slate-500">' + escapeHtml(c.patient) + '</div>'
                        + '        </div>'
                        + '        <div class="text-right">'
                        + '          <div class="font-extrabold text-slate-900 text-sm">' + escapeHtml(peso(c.approved_amount)) + '</div>'
                        + '          <div class="mt-1">' + chip + '</div>'
                        + '        </div>'
                        + '      </div>'
                        + '      <div class="mt-3 grid grid-cols-2 gap-3 text-xs">'
                        + '        <div class="text-slate-500">Claim Amount<br><span class="font-extrabold text-slate-700">' + escapeHtml(peso(c.total_amount)) + '</span></div>'
                        + '        <div class="text-slate-500">Remaining Balance<br><span class="font-extrabold text-rose-700">' + escapeHtml(peso(c.remaining_balance)) + '</span></div>'
                        + '      </div>'
                        + '      <div class="mt-3">'
                        + '        <div class="text-[10px] text-slate-500 font-bold">Progress</div>'
                        + '        <div class="mt-1 w-full h-2 bg-slate-100 rounded-full overflow-hidden">'
                        + '          <div class="h-full bg-emerald-600" style="width: ' + pct + '%"></div>'
                        + '        </div>'
                        + '        <div class="mt-1 text-[10px] text-slate-500"><span class="font-bold text-slate-700">' + pct + '%</span></div>'
                        + '      </div>'
                        + '      <div class="mt-3 flex items-center justify-between text-[10px] text-slate-500">'
                        + '        <div>' + escapeHtml(c.type) + ' • ' + escapeHtml(c.diagnosis) + '</div>'
                        + '        <div>' + escapeHtml(c.processor) + '</div>'
                        + '      </div>'
                        + '    </div>'
                        + '  </div>'
                        + '</button>'
                    );
                }).join('');

                list.querySelectorAll('button[data-id]').forEach(btn => {
                    btn.addEventListener('click', () => openModal(btn.getAttribute('data-id')));
                });
            }

            function openModal(id) {
                const c = claims.find(x => x.id === id);
                if (!c || !modal) return;

                if (modalCode) modalCode.textContent = c.id;
                if (modalPatient) modalPatient.textContent = c.patient_id;
                if (modalStatus) {
                    modalStatus.textContent = statusLabel(c.status);
                    modalStatus.className = 'px-3 py-1 rounded-full text-[10px] font-extrabold ' + statusClass(c.status);
                }

                if (modalTotal) modalTotal.textContent = peso(c.total_amount);
                if (modalApproved) modalApproved.textContent = peso(c.approved_amount);
                if (modalRemaining) modalRemaining.textContent = peso(c.remaining_balance);

                if (modalType) modalType.textContent = c.type;
                if (modalDiagnosis) modalDiagnosis.textContent = c.diagnosis;
                if (modalSubmitted) modalSubmitted.textContent = c.submitted_date;
                if (modalApprovedDate) modalApprovedDate.textContent = c.approved_date;

                const pct = Math.max(0, Math.min(100, Number(c.progress) || 0));
                if (modalProgressBar) modalProgressBar.style.width = pct + '%';
                if (modalProgressText) modalProgressText.textContent = pct + '%';

                if (modalServices) {
                    modalServices.innerHTML = (c.services || []).map(s => {
                        return '<div class="flex items-center gap-2"><i class="fas fa-check text-emerald-600"></i><span class="font-bold text-slate-700">' + escapeHtml(s) + '</span></div>';
                    }).join('');
                }

                modal.classList.remove('hidden');
            }

            function closeModal() {
                modal?.classList.add('hidden');
            }

            modalClose?.addEventListener('click', closeModal);
            modal?.addEventListener('click', (e) => {
                if (e.target === modal || e.target === modal.firstElementChild) closeModal();
            });

            submitClaimBtn?.addEventListener('click', () => {
                alert('Submit Claim is not connected to a backend yet.');
            });

            modalUpdateBtn?.addEventListener('click', () => {
                alert('Update Status is not connected to a backend yet.');
            });

            modalDownloadBtn?.addEventListener('click', () => {
                alert('Download Report is not connected to a backend yet.');
            });

            renderStats();
            renderList();
        })();
    </script>
</body>

</html>
