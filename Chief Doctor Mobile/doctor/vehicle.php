<?php
require_once __DIR__ . '/_auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Mobile - Vehicle</title>
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
                    <a href="vehicle.php" class="flex items-center gap-3 px-4 py-3 rounded-xl bg-emerald-50 text-slate-800">
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
                    <div class="text-xs font-semibold opacity-90">VEHICLE</div>
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
                    <div class="text-sm font-extrabold">Service Vehicle</div>
                    <div class="text-sm font-extrabold">Management</div>
                </div>
                <button id="addVehicleBtn" type="button" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 font-bold hover:bg-emerald-100">
                    <span class="w-6 h-6 rounded-lg bg-emerald-600 text-white flex items-center justify-center">
                        <i class="fas fa-plus text-xs"></i>
                    </span>
                    <span class="text-sm">Add Vehicle</span>
                </button>
            </div>
        </div>

        <div class="px-5 py-5 bg-slate-50 flex-1 overflow-y-auto no-scrollbar">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl p-4">
                    <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center border border-emerald-200">
                        <i class="fas fa-circle-check text-emerald-600"></i>
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">Available</div>
                    <div id="countAvailable" class="mt-1 text-2xl font-extrabold text-slate-900">0</div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4">
                    <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center border border-blue-200">
                        <i class="fas fa-sun text-blue-600"></i>
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">In Use</div>
                    <div id="countInUse" class="mt-1 text-2xl font-extrabold text-slate-900">0</div>
                </div>
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
                    <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center border border-amber-200">
                        <i class="fas fa-wrench text-amber-600"></i>
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">In Maintenance</div>
                    <div id="countMaintenance" class="mt-1 text-2xl font-extrabold text-slate-900">0</div>
                </div>
                <div class="bg-violet-50 border border-violet-200 rounded-2xl p-4">
                    <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center border border-violet-200">
                        <i class="fas fa-calendar text-violet-600"></i>
                    </div>
                    <div class="mt-2 text-xs font-bold text-slate-600">Scheduled</div>
                    <div id="countScheduled" class="mt-1 text-2xl font-extrabold text-slate-900">0</div>
                </div>
            </div>

            <div class="mt-5 flex items-center gap-3">
                <div class="flex-1 bg-white rounded-xl px-3 py-2 flex items-center gap-2 border border-slate-200">
                    <i class="fas fa-search text-slate-400"></i>
                    <input id="vehicleSearch" type="text" placeholder="Search vehicle" class="w-full outline-none text-sm text-slate-700" />
                </div>
                <div class="bg-white rounded-xl px-3 py-2 border border-slate-200">
                    <select id="statusFilter" class="text-sm text-slate-700 outline-none bg-transparent">
                        <option value="">All Status</option>
                        <option value="available">Available</option>
                        <option value="in_use">In Use</option>
                        <option value="maintenance">In Maintenance</option>
                        <option value="scheduled">Scheduled</option>
                    </select>
                </div>
            </div>

            <div class="mt-5 space-y-3" id="vehicleList"></div>

            <div class="mt-8 text-center">
                <a href="logout.php" class="text-xs text-slate-500 hover:text-slate-700">Logout</a>
            </div>
        </div>
    </div>

    <div id="addVehicleModal" class="hidden fixed inset-0 z-[80]">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-[360px] bg-white rounded-[28px] shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="font-extrabold text-slate-800 text-sm">Add New Vehicle</div>
                    <button id="addVehicleClose" type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times text-slate-600"></i>
                    </button>
                </div>
                <div class="px-5 py-5">
                    <div class="text-xs text-slate-500 font-bold">Vehicle Type</div>
                    <select id="addVehicleType" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm">
                        <option value="Ambulance">Ambulance</option>
                        <option value="Service Vehicle">Service Vehicle</option>
                    </select>

                    <div class="mt-4 text-xs text-slate-500 font-bold">Plate Number</div>
                    <input id="addVehiclePlate" type="text" placeholder="e.g., ABC-1234" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <div class="mt-4 text-xs text-slate-500 font-bold">Assigned Driver</div>
                    <input id="addVehicleDriver" type="text" placeholder="e.g., John Doe" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <div class="mt-4 text-xs text-slate-500 font-bold">Current Mileage (km)</div>
                    <input id="addVehicleMileage" type="number" inputmode="numeric" placeholder="e.g., 50000" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <div id="addVehicleError" class="hidden mt-4 text-sm font-semibold text-red-600"></div>

                    <button id="addVehicleSubmit" type="button" class="mt-6 w-full py-3 rounded-xl bg-emerald-600 text-white font-extrabold text-sm hover:bg-emerald-700">Add Vehicle</button>
                </div>
            </div>
        </div>
    </div>

    <div id="detailsModal" class="hidden fixed inset-0 z-[60]">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-[360px] bg-white rounded-[28px] shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="font-extrabold text-slate-800 text-sm">Vehicle Details</div>
                    <button id="detailsClose" type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times text-slate-600"></i>
                    </button>
                </div>
                <div class="px-5 py-5">
                    <div class="flex items-start gap-3">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-50 flex items-center justify-center border border-emerald-100">
                            <i class="fas fa-truck-medical text-emerald-600"></i>
                        </div>
                        <div class="flex-1">
                            <div id="detailsTitle" class="font-extrabold text-slate-800">-</div>
                            <div id="detailsStatusChip" class="mt-1 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700">-</div>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 gap-4 text-xs">
                        <div>
                            <div class="text-slate-400 font-bold">Driver</div>
                            <div id="detailsDriver" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Location</div>
                            <div id="detailsLocation" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Mileage</div>
                            <div id="detailsMileage" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Fuel Level</div>
                            <div id="detailsFuel" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Last Maintenance</div>
                            <div id="detailsLastMaint" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                        <div>
                            <div class="text-slate-400 font-bold">Next Maintenance</div>
                            <div id="detailsNextMaint" class="mt-1 font-extrabold text-slate-800">-</div>
                        </div>
                    </div>

                    <div id="detailsScheduleBox" class="mt-4 bg-violet-50 border border-violet-200 rounded-2xl p-4 text-xs hidden">
                        <div class="font-extrabold text-slate-800">Scheduled Trip</div>
                        <div class="mt-2 text-slate-700">
                            <div><span class="font-bold">Destination:</span> <span id="detailsDestination">-</span></div>
                            <div class="mt-1"><span class="font-bold">Time:</span> <span id="detailsTime">-</span></div>
                            <div class="mt-1"><span class="font-bold">Purpose:</span> <span id="detailsPurpose">-</span></div>
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3">
                        <button id="scheduleTripBtn" type="button" class="py-3 rounded-xl bg-emerald-600 text-white font-extrabold text-sm hover:bg-emerald-700">Schedule Trip</button>
                        <button id="updateStatusBtn" type="button" class="py-3 rounded-xl bg-white border border-slate-200 text-slate-800 font-extrabold text-sm hover:bg-slate-50">Update Status</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="scheduleModal" class="hidden fixed inset-0 z-[70]">
        <div class="absolute inset-0 bg-black/40"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="w-full max-w-[360px] bg-white rounded-[28px] shadow-2xl overflow-hidden">
                <div class="flex items-center justify-between px-5 py-4 bg-slate-50 border-b border-slate-200">
                    <div class="font-extrabold text-slate-800 text-sm">Schedule Vehicle</div>
                    <button id="scheduleClose" type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center" aria-label="Close">
                        <i class="fas fa-times text-slate-600"></i>
                    </button>
                </div>
                <div class="px-5 py-5">
                    <div class="text-xs text-slate-500 font-bold">Vehicle</div>
                    <select id="scheduleVehicle" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm">
                    </select>

                    <div class="mt-4 text-xs text-slate-500 font-bold">Date</div>
                    <input id="scheduleDate" type="date" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <div class="mt-4 text-xs text-slate-500 font-bold">Time</div>
                    <input id="scheduleTime" type="time" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <div class="mt-4 text-xs text-slate-500 font-bold">Destination</div>
                    <input id="scheduleDestination" type="text" placeholder="e.g, City General Hospital" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <div class="mt-4 text-xs text-slate-500 font-bold">Purpose</div>
                    <input id="schedulePurpose" type="text" placeholder="e.g, Patient Transfer" class="mt-2 w-full px-3 py-3 rounded-xl border border-slate-200 outline-none text-sm" />

                    <button id="confirmScheduleBtn" type="button" class="mt-6 w-full py-3 rounded-xl bg-emerald-600 text-white font-extrabold text-sm hover:bg-emerald-700">Confirm Schedule</button>
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

            const vehiclesSeed = [
                {
                    id: 'AMB-001',
                    name: 'Ambulance - AMB-001',
                    status: 'available',
                    driver: 'John Driver',
                    location: 'Hospital Parking',
                    mileageKm: 12450,
                    fuel: 76,
                    lastMaintenance: '2024-01-10',
                    nextMaintenance: '2024-02-10',
                    schedule: null
                },
                {
                    id: 'AMB-002',
                    name: 'Ambulance - AMB-002',
                    status: 'in_use',
                    driver: 'Mike Wilson',
                    location: 'En Route to City Hospital',
                    mileageKm: 52000,
                    fuel: 60,
                    lastMaintenance: '2024-01-05',
                    nextMaintenance: '2024-02-05',
                    schedule: null
                },
                {
                    id: 'AMB-003',
                    name: 'Ambulance - AMB-003',
                    status: 'scheduled',
                    driver: 'Tom Brown',
                    location: 'Maintenance Bay',
                    mileageKm: 18320,
                    fuel: 82,
                    lastMaintenance: '2024-01-08',
                    nextMaintenance: '2024-02-15',
                    schedule: {
                        destination: 'City Hospital',
                        time: '2024-01-16 14:30',
                        purpose: 'Patient Transfer'
                    }
                },
                {
                    id: 'AMB-004',
                    name: 'Ambulance - AMB-004',
                    status: 'maintenance',
                    driver: '—',
                    location: 'Maintenance Bay',
                    mileageKm: 40300,
                    fuel: 40,
                    lastMaintenance: '2024-01-02',
                    nextMaintenance: '2024-01-30',
                    schedule: null
                }
            ];

            let vehicles = vehiclesSeed.slice();
            let activeVehicleId = null;

            const listEl = document.getElementById('vehicleList');
            const searchEl = document.getElementById('vehicleSearch');
            const filterEl = document.getElementById('statusFilter');

            const countAvailableEl = document.getElementById('countAvailable');
            const countInUseEl = document.getElementById('countInUse');
            const countMaintenanceEl = document.getElementById('countMaintenance');
            const countScheduledEl = document.getElementById('countScheduled');

            const detailsModal = document.getElementById('detailsModal');
            const detailsClose = document.getElementById('detailsClose');
            const scheduleModal = document.getElementById('scheduleModal');
            const scheduleClose = document.getElementById('scheduleClose');

            const detailsTitle = document.getElementById('detailsTitle');
            const detailsStatusChip = document.getElementById('detailsStatusChip');
            const detailsDriver = document.getElementById('detailsDriver');
            const detailsLocation = document.getElementById('detailsLocation');
            const detailsMileage = document.getElementById('detailsMileage');
            const detailsFuel = document.getElementById('detailsFuel');
            const detailsLastMaint = document.getElementById('detailsLastMaint');
            const detailsNextMaint = document.getElementById('detailsNextMaint');
            const detailsScheduleBox = document.getElementById('detailsScheduleBox');
            const detailsDestination = document.getElementById('detailsDestination');
            const detailsTime = document.getElementById('detailsTime');
            const detailsPurpose = document.getElementById('detailsPurpose');

            const scheduleTripBtn = document.getElementById('scheduleTripBtn');
            const updateStatusBtn = document.getElementById('updateStatusBtn');

            const scheduleVehicleSelect = document.getElementById('scheduleVehicle');
            const scheduleDate = document.getElementById('scheduleDate');
            const scheduleTime = document.getElementById('scheduleTime');
            const scheduleDestination = document.getElementById('scheduleDestination');
            const schedulePurpose = document.getElementById('schedulePurpose');
            const confirmScheduleBtn = document.getElementById('confirmScheduleBtn');

            const addVehicleBtn = document.getElementById('addVehicleBtn');

            const addVehicleModal = document.getElementById('addVehicleModal');
            const addVehicleClose = document.getElementById('addVehicleClose');
            const addVehicleSubmit = document.getElementById('addVehicleSubmit');
            const addVehicleType = document.getElementById('addVehicleType');
            const addVehiclePlate = document.getElementById('addVehiclePlate');
            const addVehicleDriver = document.getElementById('addVehicleDriver');
            const addVehicleMileage = document.getElementById('addVehicleMileage');
            const addVehicleError = document.getElementById('addVehicleError');

            function escapeHtml(s) {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function statusLabel(s) {
                const v = (s ?? '').toString();
                if (v === 'in_use') return 'In Use';
                if (v === 'maintenance') return 'In Maintenance';
                if (v === 'scheduled') return 'Scheduled';
                return 'Available';
            }

            function statusChipClass(s) {
                if (s === 'available') return 'bg-emerald-50 text-emerald-700 border border-emerald-200';
                if (s === 'in_use') return 'bg-blue-50 text-blue-700 border border-blue-200';
                if (s === 'maintenance') return 'bg-amber-50 text-amber-700 border border-amber-200';
                if (s === 'scheduled') return 'bg-violet-50 text-violet-700 border border-violet-200';
                return 'bg-slate-100 text-slate-700 border border-slate-200';
            }

            function fmtKm(n) {
                const x = Number(n);
                if (!Number.isFinite(x)) return '-';
                return x.toLocaleString() + ' km';
            }

            function openDetails(id) {
                const v = vehicles.find(x => x.id === id);
                if (!v || !detailsModal) return;
                activeVehicleId = v.id;

                if (detailsTitle) detailsTitle.textContent = v.name;
                if (detailsStatusChip) {
                    detailsStatusChip.textContent = statusLabel(v.status);
                    detailsStatusChip.className = 'mt-1 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold ' + statusChipClass(v.status);
                }
                if (detailsDriver) detailsDriver.textContent = v.driver || '-';
                if (detailsLocation) detailsLocation.textContent = v.location || '-';
                if (detailsMileage) detailsMileage.textContent = fmtKm(v.mileageKm);
                if (detailsFuel) detailsFuel.textContent = (v.fuel ?? '-') + '%';
                if (detailsLastMaint) detailsLastMaint.textContent = v.lastMaintenance || '-';
                if (detailsNextMaint) detailsNextMaint.textContent = v.nextMaintenance || '-';

                const hasSchedule = !!v.schedule;
                if (detailsScheduleBox) {
                    detailsScheduleBox.classList.toggle('hidden', !hasSchedule);
                }
                if (hasSchedule) {
                    if (detailsDestination) detailsDestination.textContent = v.schedule.destination || '-';
                    if (detailsTime) detailsTime.textContent = v.schedule.time || '-';
                    if (detailsPurpose) detailsPurpose.textContent = v.schedule.purpose || '-';
                }

                detailsModal.classList.remove('hidden');
            }

            function closeDetails() {
                detailsModal?.classList.add('hidden');
            }

            function openSchedule(selectedId) {
                if (!scheduleModal || !scheduleVehicleSelect) return;
                scheduleVehicleSelect.innerHTML = vehicles.map(v => {
                    const sel = v.id === selectedId ? ' selected' : '';
                    return '<option value="' + escapeHtml(v.id) + '"' + sel + '>' + escapeHtml(v.name) + '</option>';
                }).join('');
                scheduleModal.classList.remove('hidden');
            }

            function closeSchedule() {
                scheduleModal?.classList.add('hidden');
            }

            function updateCounts() {
                const c = { available: 0, in_use: 0, maintenance: 0, scheduled: 0 };
                vehicles.forEach(v => {
                    if (v.status in c) c[v.status] += 1;
                });
                if (countAvailableEl) countAvailableEl.textContent = String(c.available);
                if (countInUseEl) countInUseEl.textContent = String(c.in_use);
                if (countMaintenanceEl) countMaintenanceEl.textContent = String(c.maintenance);
                if (countScheduledEl) countScheduledEl.textContent = String(c.scheduled);
            }

            function matches(v) {
                const q = (searchEl?.value || '').toString().trim().toLowerCase();
                const f = (filterEl?.value || '').toString().trim().toLowerCase();
                const s = (v.status || '').toString().toLowerCase();

                const hay = (v.name + ' ' + v.id + ' ' + (v.driver || '') + ' ' + (v.location || '')).toLowerCase();
                const okQ = !q || hay.includes(q);
                const okF = !f || s === f;
                return okQ && okF;
            }

            function render() {
                if (!listEl) return;
                updateCounts();

                const rows = vehicles.filter(matches);
                if (!rows.length) {
                    listEl.innerHTML = '<div class="text-center text-sm text-slate-500 py-10">No vehicles found.</div>';
                    return;
                }

                listEl.innerHTML = rows.map(v => {
                    const chip = '<span class="px-3 py-1 rounded-full text-[10px] font-extrabold ' + statusChipClass(v.status) + '">' + escapeHtml(statusLabel(v.status)) + '</span>';
                    return (
                        '<button type="button" data-id="' + escapeHtml(v.id) + '" class="w-full text-left bg-white border border-slate-200 rounded-2xl p-4 hover:bg-slate-50">'
                        + '<div class="flex items-start gap-3">'
                        + '  <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center border border-emerald-100">'
                        + '    <i class="fas fa-truck-medical text-emerald-600"></i>'
                        + '  </div>'
                        + '  <div class="flex-1">'
                        + '    <div class="flex items-start justify-between gap-3">'
                        + '      <div>'
                        + '        <div class="font-extrabold text-slate-800 text-sm">' + escapeHtml(v.name) + '</div>'
                        + '        <div class="mt-1 text-xs text-slate-500">Driver: <span class="font-bold text-slate-700">' + escapeHtml(v.driver || '-') + '</span></div>'
                        + '      </div>'
                        + '      ' + chip
                        + '    </div>'
                        + '    <div class="mt-3 grid grid-cols-2 gap-3 text-xs text-slate-500">'
                        + '      <div class="flex items-center gap-2"><i class="fas fa-location-dot text-slate-400"></i><span>' + escapeHtml(v.location || '-') + '</span></div>'
                        + '      <div class="flex items-center gap-2"><i class="fas fa-calendar text-slate-400"></i><span>Next Maint: ' + escapeHtml(v.nextMaintenance || '-') + '</span></div>'
                        + '    </div>'
                        + '  </div>'
                        + '</div>'
                        + '</button>'
                    );
                }).join('');

                listEl.querySelectorAll('button[data-id]').forEach(btn => {
                    btn.addEventListener('click', () => openDetails(btn.getAttribute('data-id')));
                });
            }

            function cycleStatus(id) {
                const v = vehicles.find(x => x.id === id);
                if (!v) return;

                const order = ['available', 'in_use', 'scheduled', 'maintenance'];
                const idx = Math.max(0, order.indexOf(v.status));
                v.status = order[(idx + 1) % order.length];
                if (v.status !== 'scheduled') {
                    v.schedule = null;
                }
                render();
                openDetails(v.id);
            }

            function openAddVehicle() {
                if (addVehicleError) {
                    addVehicleError.textContent = '';
                    addVehicleError.classList.add('hidden');
                }
                if (addVehiclePlate) addVehiclePlate.value = '';
                if (addVehicleDriver) addVehicleDriver.value = '';
                if (addVehicleMileage) addVehicleMileage.value = '';
                addVehicleModal?.classList.remove('hidden');
            }

            function closeAddVehicle() {
                addVehicleModal?.classList.add('hidden');
            }

            function showAddVehicleError(msg) {
                if (!addVehicleError) return;
                addVehicleError.textContent = msg;
                addVehicleError.classList.remove('hidden');
            }

            function nextVehicleCode(type) {
                const prefix = (type || '').toString().toLowerCase().includes('ambulance') ? 'AMB' : 'VEH';
                let max = 0;
                vehicles.forEach(v => {
                    const m = (v.id || '').toString().match(new RegExp('^' + prefix + '-(\\d+)$'));
                    if (m) {
                        const n = parseInt(m[1], 10);
                        if (Number.isFinite(n) && n > max) max = n;
                    }
                });
                const next = String(max + 1).padStart(3, '0');
                return prefix + '-' + next;
            }

            addVehicleBtn?.addEventListener('click', openAddVehicle);
            addVehicleClose?.addEventListener('click', closeAddVehicle);
            addVehicleModal?.addEventListener('click', (e) => {
                if (e.target === addVehicleModal || e.target === addVehicleModal.firstElementChild) closeAddVehicle();
            });

            addVehicleSubmit?.addEventListener('click', () => {
                if (addVehicleError) {
                    addVehicleError.textContent = '';
                    addVehicleError.classList.add('hidden');
                }

                const type = (addVehicleType?.value || 'Ambulance').toString().trim();
                const plate = (addVehiclePlate?.value || '').toString().trim();
                const driver = (addVehicleDriver?.value || '').toString().trim();
                const mileageRaw = (addVehicleMileage?.value || '').toString().trim();
                const mileage = mileageRaw === '' ? NaN : Number(mileageRaw);

                if (!plate) {
                    showAddVehicleError('Plate number is required.');
                    return;
                }
                if (!driver) {
                    showAddVehicleError('Assigned driver is required.');
                    return;
                }
                if (!Number.isFinite(mileage) || mileage < 0) {
                    showAddVehicleError('Enter a valid mileage.');
                    return;
                }

                const id = nextVehicleCode(type);
                const name = (type || 'Vehicle') + ' - ' + id;
                vehicles.unshift({
                    id,
                    name,
                    status: 'available',
                    driver,
                    location: 'Hospital Parking',
                    mileageKm: Math.round(mileage),
                    fuel: 100,
                    lastMaintenance: '-',
                    nextMaintenance: '-',
                    schedule: null,
                    plate
                });

                closeAddVehicle();
                render();
            });

            searchEl?.addEventListener('input', render);
            filterEl?.addEventListener('change', render);

            detailsClose?.addEventListener('click', closeDetails);
            detailsModal?.addEventListener('click', (e) => {
                if (e.target === detailsModal || e.target === detailsModal.firstElementChild) closeDetails();
            });

            scheduleClose?.addEventListener('click', closeSchedule);
            scheduleModal?.addEventListener('click', (e) => {
                if (e.target === scheduleModal || e.target === scheduleModal.firstElementChild) closeSchedule();
            });

            scheduleTripBtn?.addEventListener('click', () => {
                const id = activeVehicleId;
                if (!id) return;
                closeDetails();
                openSchedule(id);
            });

            updateStatusBtn?.addEventListener('click', () => {
                const id = activeVehicleId;
                if (!id) return;
                cycleStatus(id);
            });

            confirmScheduleBtn?.addEventListener('click', () => {
                const id = (scheduleVehicleSelect?.value || '').toString();
                const v = vehicles.find(x => x.id === id);
                if (!v) return;

                const d = (scheduleDate?.value || '').toString();
                const t = (scheduleTime?.value || '').toString();
                const dest = (scheduleDestination?.value || '').toString().trim();
                const purp = (schedulePurpose?.value || '').toString().trim();

                if (!d || !t || !dest || !purp) {
                    alert('Please complete all fields.');
                    return;
                }

                v.status = 'scheduled';
                v.schedule = {
                    destination: dest,
                    time: d + ' ' + t,
                    purpose: purp
                };
                closeSchedule();
                render();
                openDetails(v.id);
            });

            render();
        })();
    </script>
</body>

</html>
