<?php
require_once __DIR__ . '/_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Daily Reports</title>
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
                    <a href="philhealth.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                        <span class="font-semibold">PhilHealth</span>
                    </a>
                    <a href="daily-reports.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
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
                    <a href="lab-results.php" class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-50 text-slate-800">
                        <i class="fas fa-file-medical text-emerald-600"></i>
                        <span class="font-semibold">Lab Results</span>
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
                    <div class="text-xs font-semibold opacity-90">DAILY REPORTS</div>
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
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="flex items-center justify-between">
                <div>
                    <div id="reportTitle" class="text-lg font-extrabold text-slate-900">Daily Report</div>
                    <div id="reportSubtitle" class="text-xs font-semibold text-slate-500 mt-0.5">Loading...</div>
                </div>
                <div class="relative">
                    <button id="reportSelectBtn" type="button" class="h-10 px-4 rounded-2xl bg-white border border-slate-200 text-slate-800 hover:bg-slate-50 flex items-center gap-2 text-xs font-extrabold" aria-haspopup="true" aria-expanded="false">
                        <span id="reportSelectLabel">Select Report</span>
                        <i class="fas fa-chevron-down text-slate-500"></i>
                    </button>
                    <div id="reportSelectMenu" class="hidden absolute right-0 mt-2 w-52 bg-white border border-slate-200 rounded-2xl shadow-lg overflow-hidden z-10">
                        <div id="reportSelectList" class="p-2"></div>
                    </div>
                </div>
            </div>

            <div id="sectionTabs" class="mt-4 flex gap-2 overflow-x-auto no-scrollbar pb-1 touch-pan-x select-none cursor-grab active:cursor-grabbing"></div>

            <div id="mssSection" class="mt-3 bg-white border border-slate-200 rounded-3xl p-4 shadow-sm">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="text-[11px] font-extrabold text-slate-500 tracking-wide">MSS / CASHIER</div>
                        <div id="summaryTotal" class="mt-1 text-2xl font-extrabold text-slate-900">—</div>
                        <div id="summaryDelta" class="mt-1 text-xs font-bold text-emerald-600">—</div>
                    </div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-file-invoice-dollar text-emerald-600"></i>
                    </div>
                </div>

                <div class="mt-4">
                    <div class="inline-flex bg-slate-100 rounded-2xl p-1 text-xs font-extrabold">
                        <button type="button" data-range="day" class="rangeBtn px-4 py-2 rounded-2xl text-slate-700">Day</button>
                        <button type="button" data-range="week" class="rangeBtn px-4 py-2 rounded-2xl text-slate-700">Week</button>
                        <button type="button" data-range="month" class="rangeBtn px-4 py-2 rounded-2xl text-slate-700">Month</button>
                        <button type="button" data-range="year" class="rangeBtn px-4 py-2 rounded-2xl text-slate-700">Year</button>
                    </div>
                </div>

                <div class="mt-4">
                    <div id="summaryChart" class="w-full h-32"></div>
                </div>

                <div class="mt-4">
                    <div class="divide-y divide-slate-100">
                        <div class="flex items-center justify-between py-2">
                            <div class="text-xs font-bold text-slate-600">Today's Revenue</div>
                            <div id="cashToday" class="text-xs font-extrabold text-slate-900">—</div>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="text-xs font-bold text-slate-600">Monthly Revenue</div>
                            <div id="cashMonth" class="text-xs font-extrabold text-slate-900">—</div>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="text-xs font-bold text-slate-600">Total Unpaid</div>
                            <div id="cashUnpaid" class="text-xs font-extrabold text-slate-900">—</div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="otherSection" class="mt-3"></div>
        </div>
    </div>

    <script>
        (function () {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            const openBtn = document.getElementById('drawerOpen');
            const closeBtn = document.getElementById('drawerClose');

            const reportTitle = document.getElementById('reportTitle');
            const reportSubtitle = document.getElementById('reportSubtitle');
            const reportSelectBtn = document.getElementById('reportSelectBtn');
            const reportSelectLabel = document.getElementById('reportSelectLabel');
            const reportSelectMenu = document.getElementById('reportSelectMenu');
            const reportSelectList = document.getElementById('reportSelectList');
            const summaryTotal = document.getElementById('summaryTotal');
            const summaryDelta = document.getElementById('summaryDelta');
            const summaryChart = document.getElementById('summaryChart');
            const cashToday = document.getElementById('cashToday');
            const cashMonth = document.getElementById('cashMonth');
            const cashUnpaid = document.getElementById('cashUnpaid');
            const sectionTabs = document.getElementById('sectionTabs');
            const mssSection = document.getElementById('mssSection');
            const otherSection = document.getElementById('otherSection');
            const rangeBtns = Array.from(document.querySelectorAll('.rangeBtn'));

            const pesos = new Intl.NumberFormat('en-PH', { style: 'currency', currency: 'PHP', maximumFractionDigits: 0 });

            function buildRevenueSeries(report, range) {
                const raw = (report?.series && report.series[range]) ? report.series[range] : (report?.series?.week || []);
                const rawMax = raw.length ? Math.max(...raw, 1) : 1;
                const today = Number(report?.cashier?.todayRevenue || 0);
                const month = Number(report?.cashier?.monthlyRevenue || 0);
                let targetMax = today;

                if (range === 'day') {
                    targetMax = Math.max(today * 1.4, 150000);
                } else if (range === 'week') {
                    targetMax = Math.max(today * 7.5, 800000);
                } else if (range === 'month') {
                    targetMax = Math.max(month * 1.15, 5000000);
                } else if (range === 'year') {
                    targetMax = Math.max(120000000 + (activeReportIndex * 6000000), 100000000);
                }

                const scale = rawMax ? (targetMax / rawMax) : 1;
                const values = raw.length ? raw.map((v) => Math.round(Number(v || 0) * scale)) : [Math.round(targetMax)];
                const displayRevenue = values.length ? values[values.length - 1] : Math.round(targetMax);
                return { values, displayRevenue };
            }

            const reports = [
                {
                    id: 'rep-1',
                    label: 'Today',
                    subtitle: 'Daily Report - MSS/Cashier',
                    cashier: { todayRevenue: 85440, monthlyRevenue: 1272150, unpaid: 18550 },
                    patients: { er: 500, opd: 1500 },
                    lab: [{ name: 'Urine', count: 20 }, { name: 'Blood Test', count: 50 }],
                    xray: { total: 500 },
                    operatingRoom: [{ name: 'Appendicitis', count: 3 }, { name: 'Suture', count: 2 }, { name: 'Stab Wounds', count: 1 }],
                    deliveryRoom: { total: 10 },
                    icu: { totalPatients: 4 },
                    pharmacy: { totalRevenue: 46820 },
                    deltaText: '+2.7% vs yesterday',
                    series: {
                        day: [12, 18, 9, 24, 16, 22, 19, 28, 21, 31, 26, 30],
                        week: [62, 71, 68, 82, 77, 88, 91],
                        month: [210, 245, 232, 270, 260, 288, 310, 295, 320, 338, 355, 372],
                        year: [1100, 1220, 1180, 1350, 1425, 1510, 1490, 1600, 1725, 1810, 1890, 2010]
                    }
                },
                {
                    id: 'rep-2',
                    label: 'Yesterday',
                    subtitle: 'Daily Report - MSS/Cashier',
                    cashier: { todayRevenue: 81210, monthlyRevenue: 1219960, unpaid: 21300 },
                    patients: { er: 420, opd: 1320 },
                    lab: [{ name: 'Urine', count: 18 }, { name: 'Blood Test', count: 44 }, { name: 'Xpert MTB', count: 6 }],
                    xray: { total: 462 },
                    operatingRoom: [{ name: 'Appendicitis', count: 2 }, { name: 'Suture', count: 4 }, { name: 'Gallbladder', count: 1 }],
                    deliveryRoom: { total: 8 },
                    icu: { totalPatients: 5 },
                    pharmacy: { totalRevenue: 50110 },
                    deltaText: '-1.3% vs 2 days ago',
                    series: {
                        day: [10, 14, 13, 20, 17, 19, 15, 23, 18, 22, 20, 24],
                        week: [58, 64, 62, 73, 70, 78, 75],
                        month: [190, 205, 218, 230, 240, 252, 265, 275, 290, 302, 315, 328],
                        year: [980, 1040, 1125, 1200, 1280, 1360, 1410, 1480, 1535, 1600, 1680, 1750]
                    }
                },
                {
                    id: 'rep-3',
                    label: 'Last 7 Days',
                    subtitle: 'Daily Report - Summary',
                    cashier: { todayRevenue: 90510, monthlyRevenue: 1334800, unpaid: 16240 },
                    patients: { er: 610, opd: 1710 },
                    lab: [{ name: 'Urine', count: 24 }, { name: 'Blood Test', count: 58 }, { name: 'Pregnancy Test', count: 12 }],
                    xray: { total: 530 },
                    operatingRoom: [{ name: 'Appendicitis', count: 4 }, { name: 'Suture', count: 3 }, { name: 'C-Section', count: 2 }],
                    deliveryRoom: { total: 12 },
                    icu: { totalPatients: 3 },
                    pharmacy: { totalRevenue: 55790 },
                    deltaText: '+6.1% vs last week',
                    series: {
                        day: [14, 16, 15, 19, 21, 23, 22, 26, 25, 29, 28, 31],
                        week: [66, 72, 74, 80, 86, 92, 95],
                        month: [220, 235, 248, 258, 275, 290, 305, 318, 334, 350, 368, 385],
                        year: [1150, 1210, 1290, 1380, 1450, 1530, 1610, 1690, 1770, 1860, 1960, 2080]
                    }
                }
            ];

            let activeReportIndex = 0;
            let activeRange = 'week';
            let activeSection = 'mss';

            const sections = [
                { id: 'mss', label: 'MSS/Cashier' },
                { id: 'patients', label: 'Patients' },
                { id: 'lab', label: 'Lab Test' },
                { id: 'xray', label: 'Xray' },
                { id: 'or', label: 'Operating Room' },
                { id: 'dr', label: 'Delivery Room' },
                { id: 'icu', label: 'ICU' },
                { id: 'pharmacy', label: 'Pharmacy' }
            ];

            function enableDragScroll(container) {
                if (!container) return;

                let isPointerDown = false;
                let startX = 0;
                let startY = 0;
                let startScrollLeft = 0;
                let didDrag = false;
                let dragActivated = false;
                let suppressNextClick = false;

                container.addEventListener('pointerdown', (e) => {
                    if (e.pointerType !== 'mouse') return;
                    if (e.button !== 0) return;
                    isPointerDown = true;
                    didDrag = false;
                    dragActivated = false;
                    suppressNextClick = false;
                    startX = e.clientX;
                    startY = e.clientY;
                    startScrollLeft = container.scrollLeft;
                });

                container.addEventListener('pointermove', (e) => {
                    if (!isPointerDown) return;
                    const dx = e.clientX - startX;
                    const dy = e.clientY - startY;

                    if (!dragActivated) {
                        const absX = Math.abs(dx);
                        const absY = Math.abs(dy);
                        if (absX < 12) return;
                        if (absX < absY * 1.2) return;
                        dragActivated = true;
                        didDrag = true;
                    }

                    e.preventDefault();
                    container.scrollLeft = startScrollLeft - dx;
                });

                function endPointer() {
                    if (!isPointerDown) return;
                    isPointerDown = false;
                    if (didDrag) suppressNextClick = true;
                    didDrag = false;
                    dragActivated = false;
                }

                container.addEventListener('pointerup', endPointer);
                container.addEventListener('pointercancel', endPointer);
                container.addEventListener('pointerleave', endPointer);

                container.addEventListener('click', (e) => {
                    if (!suppressNextClick) return;
                    e.preventDefault();
                    e.stopPropagation();
                    suppressNextClick = false;
                }, true);
            }

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

            function buildAreaChart(values) {
                const w = 320;
                const h = 120;
                const padX = 8;
                const padY = 10;
                const max = Math.max(...values, 1);
                const min = Math.min(...values, 0);
                const span = Math.max(max - min, 1);

                const step = values.length > 1 ? (w - padX * 2) / (values.length - 1) : 0;

                const pts = values.map((v, i) => {
                    const x = padX + i * step;
                    const y = padY + (h - padY * 2) * (1 - (v - min) / span);
                    return { x, y };
                });

                const line = pts.map((p, i) => `${i === 0 ? 'M' : 'L'} ${p.x.toFixed(2)} ${p.y.toFixed(2)}`).join(' ');
                const area = `${line} L ${pts[pts.length - 1].x.toFixed(2)} ${(h - padY).toFixed(2)} L ${pts[0].x.toFixed(2)} ${(h - padY).toFixed(2)} Z`;

                return `
<svg viewBox="0 0 ${w} ${h}" class="w-full h-32">
    <defs>
        <linearGradient id="repGrad" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#10b981" stop-opacity="0.28" />
            <stop offset="100%" stop-color="#10b981" stop-opacity="0" />
        </linearGradient>
    </defs>
    <path d="${area}" fill="url(#repGrad)" />
    <path d="${line}" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" />
</svg>`;
            }

            function buildBarChart(values) {
                const w = 320;
                const h = 120;
                const padX = 12;
                const padY = 12;
                const max = Math.max(...values, 1);
                const innerW = w - padX * 2;
                const innerH = h - padY * 2;
                const barGap = 8;
                const barW = values.length > 0 ? (innerW - barGap * (values.length - 1)) / values.length : innerW;

                const bars = values.map((v, i) => {
                    const vh = Math.max(2, (v / max) * innerH);
                    const x = padX + i * (barW + barGap);
                    const y = padY + (innerH - vh);
                    return `<rect x="${x.toFixed(2)}" y="${y.toFixed(2)}" width="${barW.toFixed(2)}" height="${vh.toFixed(2)}" rx="10" fill="#10b981" fill-opacity="0.85" />`;
                }).join('');

                return `
<svg viewBox="0 0 ${w} ${h}" class="w-full h-28">
    <rect x="0" y="0" width="${w}" height="${h}" rx="18" fill="#10b981" fill-opacity="0.06" />
    ${bars}
</svg>`;
            }

            function buildDonutChart(percent) {
                const clamped = Math.max(0, Math.min(100, percent || 0));
                const r = 18;
                const c = 2 * Math.PI * r;
                const dash = (clamped / 100) * c;
                const gap = c - dash;
                return `
<svg viewBox="0 0 48 48" class="w-12 h-12">
    <circle cx="24" cy="24" r="18" fill="none" stroke="#e2e8f0" stroke-width="6" />
    <circle cx="24" cy="24" r="18" fill="none" stroke="#10b981" stroke-width="6" stroke-linecap="round" stroke-dasharray="${dash.toFixed(1)} ${gap.toFixed(1)}" transform="rotate(-90 24 24)" />
</svg>`;
            }

            function sectionCard(title, icon, contentHtml) {
                return `
<div class="bg-white border border-slate-200 rounded-3xl p-4">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center">
                <i class="${icon} text-emerald-600"></i>
            </div>
            <div class="text-sm font-extrabold text-slate-900">${title}</div>
        </div>
    </div>
    <div class="mt-3">${contentHtml}</div>
</div>`;
            }

            function kvRow(label, value) {
                return `
<div class="flex items-center justify-between py-2">
    <div class="text-xs font-bold text-slate-600">${label}</div>
    <div class="text-xs font-extrabold text-slate-900">${value}</div>
</div>`;
            }

            function listRows(items) {
                return `
<div class="divide-y divide-slate-100">${items.join('')}</div>`;
            }

            function closeReportMenu() {
                reportSelectMenu?.classList.add('hidden');
                reportSelectBtn?.setAttribute('aria-expanded', 'false');
            }

            function toggleReportMenu() {
                if (!reportSelectMenu) return;
                const isHidden = reportSelectMenu.classList.contains('hidden');
                if (isHidden) {
                    reportSelectMenu.classList.remove('hidden');
                    reportSelectBtn?.setAttribute('aria-expanded', 'true');
                } else {
                    closeReportMenu();
                }
            }

            function renderReportMenu() {
                if (!reportSelectList) return;
                reportSelectList.innerHTML = reports.map((r, idx) => {
                    const active = idx === activeReportIndex;
                    return `
<button type="button" data-report="${idx}" class="reportMenuBtn w-full text-left px-3 py-2 rounded-xl ${active ? 'bg-emerald-50 text-emerald-700' : 'hover:bg-slate-50 text-slate-800'} text-xs font-extrabold flex items-center justify-between">
    <span>${r.label}</span>
    ${active ? '<i class="fas fa-check text-emerald-600"></i>' : ''}
</button>`;
                }).join('');

                Array.from(document.querySelectorAll('.reportMenuBtn')).forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const idx = Number(btn.getAttribute('data-report'));
                        if (Number.isNaN(idx)) return;
                        activeReportIndex = idx;
                        closeReportMenu();
                        render();
                    });
                });
            }

            function renderRangeButtons() {
                rangeBtns.forEach((btn) => {
                    const r = btn.getAttribute('data-range');
                    const isActive = r === activeRange;
                    btn.classList.toggle('bg-white', isActive);
                    btn.classList.toggle('text-slate-900', isActive);
                    btn.classList.toggle('shadow-sm', isActive);
                    btn.classList.toggle('text-slate-700', !isActive);
                });
            }

            function renderSectionTabs() {
                if (!sectionTabs) return;
                sectionTabs.innerHTML = sections.map((s) => {
                    const active = s.id === activeSection;
                    return `
<button type="button" data-section="${s.id}" class="sectionTabBtn shrink-0 px-4 py-2 rounded-2xl border ${active ? 'bg-emerald-600 border-emerald-600 text-white' : 'bg-white border-slate-200 text-slate-700 hover:bg-slate-50'} text-xs font-extrabold">${s.label}</button>`;
                }).join('');

                Array.from(document.querySelectorAll('.sectionTabBtn')).forEach((btn) => {
                    btn.addEventListener('click', () => {
                        const id = btn.getAttribute('data-section');
                        if (!id) return;
                        activeSection = id;
                        render();
                    });
                });
            }

            function renderActiveSection(report) {
                if (!mssSection || !otherSection) return;

                if (activeSection === 'mss') {
                    mssSection.classList.remove('hidden');
                    otherSection.classList.add('hidden');
                    otherSection.innerHTML = '';
                    return;
                }

                mssSection.classList.add('hidden');
                otherSection.classList.remove('hidden');

                const shellStart = (title, icon) => `
<div class="bg-white border border-slate-200 rounded-3xl p-4 shadow-sm">
    <div class="flex items-start justify-between">
        <div>
            <div class="text-[11px] font-extrabold text-slate-500 tracking-wide">${title}</div>
        </div>
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center">
            <i class="${icon} text-emerald-600"></i>
        </div>
    </div>`;

                const shellEnd = `
</div>`;

                const dividerList = (rows) => `
<div class="mt-3 divide-y divide-slate-100">${rows.join('')}</div>`;

                const row = (label, value) => `
<div class="flex items-center justify-between py-2">
    <div class="text-xs font-bold text-slate-600">${label}</div>
    <div class="text-xs font-extrabold text-slate-900">${value}</div>
</div>`;

                const safe = (v) => (v === undefined || v === null ? 0 : v);

                const progressRow = (label, value, max, tone) => {
                    const v = safe(value);
                    const m = Math.max(1, safe(max));
                    const p = Math.max(0, Math.min(100, Math.round((v / m) * 100)));
                    const bar = tone === 'violet' ? 'bg-violet-500' : tone === 'sky' ? 'bg-sky-500' : tone === 'amber' ? 'bg-amber-500' : 'bg-emerald-500';
                    return `
<div class="py-2">
    <div class="flex items-center justify-between">
        <div class="text-xs font-bold text-slate-700">${label}</div>
        <div class="text-xs font-extrabold text-slate-900">${v.toLocaleString()}</div>
    </div>
    <div class="mt-2 h-2 rounded-full bg-slate-100 overflow-hidden">
        <div class="h-full ${bar}" style="width:${p}%"></div>
    </div>
</div>`;
                };

                const pct = (n, d) => {
                    if (!d) return '0%';
                    return `${Math.round((n / d) * 100)}%`;
                };

                if (activeSection === 'patients') {
                    const er = safe(report.patients?.er);
                    const opd = safe(report.patients?.opd);
                    const total = er + opd;
                    const trend = (report.series?.week || []).map((n) => Math.round(total * (0.75 + n / 200)));
                    const peak = trend.length ? Math.max(...trend) : total;
                    const avg = trend.length ? Math.round(trend.reduce((a, b) => a + b, 0) / trend.length) : total;
                    const erShare = total ? Math.round((er / total) * 100) : 0;
                    const opdShare = total ? 100 - erShare : 0;
                    otherSection.innerHTML = shellStart('PATIENTS', 'fas fa-user-injured') + `
    <div class="mt-3 flex items-center justify-between gap-3">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${total.toLocaleString()}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total patients (ER + OPD)</div>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2">
            ${buildDonutChart(erShare)}
            <div>
                <div class="text-[10px] font-extrabold text-slate-500">ER / OPD</div>
                <div class="text-xs font-extrabold text-slate-900">${erShare}% / ${opdShare}%</div>
            </div>
        </div>
    </div>

    <div class="mt-4">${buildAreaChart(trend.length ? trend : [total])}</div>

    <div class="mt-4 grid grid-cols-2 gap-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Peak (Week)</div>
            <div class="mt-1 text-sm font-extrabold text-slate-900">${peak.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Avg (Week)</div>
            <div class="mt-1 text-sm font-extrabold text-slate-900">${avg.toLocaleString()}</div>
        </div>
    </div>

    ${dividerList([
        row('Emergency Room', `${er.toLocaleString()} (${pct(er, total)})`),
        row('Out Patient Department', `${opd.toLocaleString()} (${pct(opd, total)})`)
    ])}
` + shellEnd;
                    return;
                }

                if (activeSection === 'lab') {
                    const lab = Array.isArray(report.lab) ? report.lab : [];
                    const counts = lab.map((t) => safe(t.count));
                    const total = counts.reduce((a, b) => a + b, 0);
                    const max = counts.length ? Math.max(...counts) : total;
                    const avg = counts.length ? Math.round(total / counts.length) : total;
                    const topIdx = counts.length ? counts.indexOf(max) : -1;
                    const topName = topIdx >= 0 ? (lab[topIdx]?.name || 'Top Test') : 'Top Test';
                    const estPending = Math.max(0, Math.round(total * 0.12));
                    otherSection.innerHTML = shellStart('LAB TEST', 'fas fa-vials') + `
    <div class="mt-1 flex items-end justify-between gap-3">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${total.toLocaleString()}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total tests processed</div>
        </div>
        <div class="text-right">
            <div class="text-[10px] font-extrabold text-slate-500">Top Test</div>
            <div class="text-xs font-extrabold text-slate-900">${topName}</div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-2">
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Top Volume</div>
            <div class="mt-1 text-sm font-extrabold text-emerald-700">${max.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Avg/Test</div>
            <div class="mt-1 text-sm font-extrabold text-sky-700">${avg.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Pending</div>
            <div class="mt-1 text-sm font-extrabold text-amber-700">${estPending.toLocaleString()}</div>
        </div>
    </div>

    <div class="mt-4">${buildBarChart(counts.length ? counts : [total])}</div>

    <div class="mt-3">
        ${(lab.length ? lab : [{ name: 'Total', count: total }]).map((t, i) => progressRow(t.name, safe(t.count), max || 1, i % 2 ? 'sky' : 'emerald')).join('')}
    </div>
` + shellEnd;
                    return;
                }

                if (activeSection === 'xray') {
                    const total = safe(report.xray?.total);
                    const trend = (report.series?.day || []).map((n) => Math.round(total * (0.7 + n / 100)));
                    const peak = trend.length ? Math.max(...trend) : total;
                    const avg = trend.length ? Math.round(trend.reduce((a, b) => a + b, 0) / trend.length) : total;
                    const estRepeat = Math.max(0, Math.round(total * 0.04));
                    const estPending = Math.max(0, Math.round(total * 0.09));
                    otherSection.innerHTML = shellStart('XRAY', 'fas fa-x-ray') + `
    <div class="mt-3 flex items-center justify-between">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${total.toLocaleString()}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total XRAY completed</div>
        </div>
        <div class="flex items-center gap-2">
            <span class="px-3 py-1.5 rounded-full bg-amber-50 text-amber-800 text-[11px] font-extrabold border border-slate-200">Pending ${estPending.toLocaleString()}</span>
            <span class="px-3 py-1.5 rounded-full bg-violet-50 text-violet-800 text-[11px] font-extrabold border border-slate-200">Repeat ${estRepeat.toLocaleString()}</span>
        </div>
    </div>

    <div class="mt-4">${buildAreaChart(trend.length ? trend : [total])}</div>

    <div class="mt-4 grid grid-cols-2 gap-3">
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Peak (Day)</div>
            <div class="mt-1 text-sm font-extrabold text-slate-900">${peak.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Avg (Day)</div>
            <div class="mt-1 text-sm font-extrabold text-slate-900">${avg.toLocaleString()}</div>
        </div>
    </div>

    ${dividerList([row('Total XRAY', total.toLocaleString())])}
` + shellEnd;
                    return;
                }

                if (activeSection === 'or') {
                    const ops = Array.isArray(report.operatingRoom) ? report.operatingRoom : [];
                    const counts = ops.map((p) => safe(p.count));
                    const total = counts.reduce((a, b) => a + b, 0);
                    const rows = ops.map((p) => row(p.name, safe(p.count).toLocaleString()));
                    const emerg = Math.max(0, Math.round(total * 0.35));
                    const elective = Math.max(0, total - emerg);
                    const avgDuration = Math.max(30, Math.round(75 + (total * 6)));
                    const utilization = Math.min(99, Math.round(55 + total * 7));
                    otherSection.innerHTML = shellStart('OPERATING ROOM', 'fas fa-procedures') + `
    <div class="mt-3 flex items-center justify-between">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${total.toLocaleString()}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total procedures</div>
        </div>
        <div class="text-right">
            <div class="text-[10px] font-extrabold text-slate-500">Utilization</div>
            <div class="text-sm font-extrabold text-emerald-700">${utilization}%</div>
        </div>
    </div>

    <div class="mt-3 flex flex-wrap gap-2">
        <span class="px-3 py-1.5 rounded-full bg-amber-50 text-amber-800 text-[11px] font-extrabold border border-slate-200">Emergency ${emerg.toLocaleString()}</span>
        <span class="px-3 py-1.5 rounded-full bg-sky-50 text-sky-800 text-[11px] font-extrabold border border-slate-200">Elective ${elective.toLocaleString()}</span>
        <span class="px-3 py-1.5 rounded-full bg-violet-50 text-violet-800 text-[11px] font-extrabold border border-slate-200">Avg ${avgDuration} min</span>
    </div>

    <div class="mt-4">${buildBarChart(counts.length ? counts : [total])}</div>
    ${dividerList(rows)}
` + shellEnd;
                    return;
                }

                if (activeSection === 'dr') {
                    const total = safe(report.deliveryRoom?.total);
                    const trend = (report.series?.month || []).map((n) => Math.max(0, Math.round((total / 10) * (0.6 + n / 500))));
                    const normal = Math.max(0, Math.round(total * 0.78));
                    const cs = Math.max(0, total - normal);
                    const peak = trend.length ? Math.max(...trend) : total;
                    const avg = trend.length ? Math.round(trend.reduce((a, b) => a + b, 0) / trend.length) : total;
                    otherSection.innerHTML = shellStart('DELIVERY ROOM', 'fas fa-baby') + `
    <div class="mt-3 flex items-center justify-between gap-3">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${total.toLocaleString()}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total deliveries</div>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2">
            ${buildDonutChart(pct(normal, total).replace('%', ''))}
            <div>
                <div class="text-[10px] font-extrabold text-slate-500">Normal / C-Section</div>
                <div class="text-xs font-extrabold text-slate-900">${pct(normal, total)} / ${pct(cs, total)}</div>
            </div>
        </div>
    </div>

    <div class="mt-4">${buildAreaChart(trend.length ? trend : [total])}</div>

    <div class="mt-4 grid grid-cols-2 gap-3">
        <div class="rounded-2xl bg-emerald-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Normal</div>
            <div class="mt-1 text-sm font-extrabold text-emerald-700">${normal.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl bg-violet-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">C-Section</div>
            <div class="mt-1 text-sm font-extrabold text-violet-700">${cs.toLocaleString()}</div>
        </div>
    </div>

    ${dividerList([
        row('Peak (Month)', peak.toLocaleString()),
        row('Avg (Month)', avg.toLocaleString()),
        row('Total DR', total.toLocaleString())
    ])}
` + shellEnd;
                    return;
                }

                if (activeSection === 'icu') {
                    const total = safe(report.icu?.totalPatients);
                    const trend = (report.series?.week || []).map((n) => Math.max(0, Math.round(total * (0.7 + n / 250))));
                    const capacity = 12;
                    const occupancy = Math.min(100, Math.round((total / capacity) * 100));
                    const available = Math.max(0, capacity - total);
                    const peak = trend.length ? Math.max(...trend) : total;
                    otherSection.innerHTML = shellStart('ICU', 'fas fa-bed-pulse') + `
    <div class="mt-3 flex items-center justify-between gap-3">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${total.toLocaleString()}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total ICU patients</div>
        </div>
        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-2xl px-3 py-2">
            ${buildDonutChart(occupancy)}
            <div>
                <div class="text-[10px] font-extrabold text-slate-500">Occupancy</div>
                <div class="text-xs font-extrabold text-slate-900">${occupancy}%</div>
            </div>
        </div>
    </div>

    <div class="mt-4 grid grid-cols-3 gap-2">
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Capacity</div>
            <div class="mt-1 text-sm font-extrabold text-sky-700">${capacity.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Available</div>
            <div class="mt-1 text-sm font-extrabold text-emerald-700">${available.toLocaleString()}</div>
        </div>
        <div class="rounded-2xl bg-slate-50 border border-slate-200 p-3">
            <div class="text-[10px] font-extrabold text-slate-500">Peak</div>
            <div class="mt-1 text-sm font-extrabold text-violet-700">${peak.toLocaleString()}</div>
        </div>
    </div>

    <div class="mt-4">${buildAreaChart(trend.length ? trend : [total])}</div>
    ${dividerList([row('Total Patient ICU', total.toLocaleString())])}
` + shellEnd;
                    return;
                }

                if (activeSection === 'pharmacy') {
                    const total = safe(report.pharmacy?.totalRevenue);
                    const trend = (report.series?.week || []).map((n) => Math.round(total * (0.6 + n / 200)));
                    const rx = Math.max(0, Math.round(total / 180));
                    const avgBasket = rx ? Math.round(total / rx) : 0;
                    const otc = Math.max(0, Math.round(total * 0.22));
                    const insured = Math.max(0, total - otc);
                    otherSection.innerHTML = shellStart('PHARMACY', 'fas fa-pills') + `
    <div class="mt-3 flex items-center justify-between">
        <div>
            <div class="text-2xl font-extrabold text-slate-900">${pesos.format(total)}</div>
            <div class="mt-1 text-xs font-bold text-emerald-600">Total revenue</div>
        </div>
        <div class="text-right">
            <div class="text-[10px] font-extrabold text-slate-500">Avg Basket</div>
            <div class="text-sm font-extrabold text-sky-700">${pesos.format(avgBasket)}</div>
        </div>
    </div>

    <div class="mt-3 flex items-center gap-2">
        <span class="px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-800 text-[11px] font-extrabold border border-slate-200">Rx ${rx.toLocaleString()}</span>
        <span class="px-3 py-1.5 rounded-full bg-amber-50 text-amber-800 text-[11px] font-extrabold border border-slate-200">OTC ${pct(otc, total)}</span>
        <span class="px-3 py-1.5 rounded-full bg-violet-50 text-violet-800 text-[11px] font-extrabold border border-slate-200">Insured ${pct(insured, total)}</span>
    </div>

    <div class="mt-4">
        ${progressRow('OTC Sales', otc, total || 1, 'amber')}
        ${progressRow('Insured', insured, total || 1, 'violet')}
    </div>

    <div class="mt-4">${buildAreaChart(trend.length ? trend : [total])}</div>
    ${dividerList([row('Total Revenue', pesos.format(total))])}
` + shellEnd;
                    return;
                }
            }

            function render() {
                const report = reports[activeReportIndex];
                if (!report) return;

                if (reportTitle) reportTitle.textContent = 'Daily Report';
                if (reportSubtitle) reportSubtitle.textContent = report.label + ' • ' + report.subtitle;

                const revenue = buildRevenueSeries(report, activeRange);
                if (summaryTotal) summaryTotal.textContent = pesos.format(revenue.displayRevenue);
                if (summaryDelta) summaryDelta.textContent = report.deltaText || '';

                if (cashToday) cashToday.textContent = pesos.format(report.cashier.todayRevenue);
                if (cashMonth) cashMonth.textContent = pesos.format(report.cashier.monthlyRevenue);
                if (cashUnpaid) cashUnpaid.textContent = pesos.format(report.cashier.unpaid);

                if (summaryChart) summaryChart.innerHTML = buildAreaChart(revenue.values);

                if (reportSelectLabel) reportSelectLabel.textContent = report.label;
                renderReportMenu();
                renderSectionTabs();
                renderRangeButtons();
                renderActiveSection(report);
            }

            openBtn?.addEventListener('click', openDrawer);
            closeBtn?.addEventListener('click', closeDrawer);
            overlay?.addEventListener('click', closeDrawer);

            reportSelectBtn?.addEventListener('click', toggleReportMenu);
            document.addEventListener('click', (e) => {
                const t = e.target;
                if (!t || !reportSelectMenu || !reportSelectBtn) return;
                if (reportSelectMenu.contains(t) || reportSelectBtn.contains(t)) return;
                closeReportMenu();
            });

            rangeBtns.forEach((btn) => {
                btn.addEventListener('click', () => {
                    const r = btn.getAttribute('data-range');
                    if (!r) return;
                    activeRange = r;
                    render();
                });
            });

            enableDragScroll(sectionTabs);

            render();
        })();
    </script>
</body>

</html>
