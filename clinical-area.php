<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clinical Area - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Clinical Area</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Outpatient Department (OPD)</h2>
                            <p class="text-sm text-gray-600 mt-1">Appointments, nursing assessment, consultation notes, billing, and lab requests.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-hospital-user text-blue-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="out-patient-department.php#overview" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                            <i class="fas fa-arrow-right"></i>
                            <span>Open OPD</span>
                        </a>
                        <a href="out-patient-department.php#appointment-requests" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <i class="fas fa-inbox"></i>
                            <span>Appointment Requests</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Emergency Room (ER)</h2>
                            <p class="text-sm text-gray-600 mt-1">ER nurse lab requests and tracking of ER lab request workflow.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-red-50 flex items-center justify-center">
                            <i class="fas fa-truck-medical text-red-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="er.php#new" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition">
                            <i class="fas fa-arrow-right"></i>
                            <span>Open ER</span>
                        </a>
                        <a href="er.php#requests" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <i class="fas fa-list-check"></i>
                            <span>ER Lab Requests</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Operating Room (OR)</h2>
                            <p class="text-sm text-gray-600 mt-1">Surgery schedule, active cases, and theatre availability.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center">
                            <i class="fas fa-syringe text-emerald-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="operating-room.php#dashboard" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 transition">
                            <i class="fas fa-arrow-right"></i>
                            <span>Open OR</span>
                        </a>
                        <a href="operating-room.php#schedule" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <i class="fas fa-calendar-check"></i>
                            <span>Surgery Schedule</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Delivery Room (DR)</h2>
                            <p class="text-sm text-gray-600 mt-1">Labor queue, delivery records, and newborn care overview.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-pink-50 flex items-center justify-center">
                            <i class="fas fa-baby text-pink-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="delivery-room.php#dashboard" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-pink-600 text-white hover:bg-pink-700 transition">
                            <i class="fas fa-arrow-right"></i>
                            <span>Open DR</span>
                        </a>
                        <a href="delivery-room.php#labor-queue" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <i class="fas fa-users"></i>
                            <span>Labor Queue</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Intensive Care Unit (ICU)</h2>
                            <p class="text-sm text-gray-600 mt-1">Bed availability, active admissions, and occupancy trends.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center">
                            <i class="fas fa-procedures text-indigo-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="icu.php#overview" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 transition">
                            <i class="fas fa-arrow-right"></i>
                            <span>Open ICU</span>
                        </a>
                        <a href="icu.php#overview" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <i class="fas fa-chart-line"></i>
                            <span>Occupancy</span>
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900">Xray</h2>
                            <p class="text-sm text-gray-600 mt-1">Imaging workload, pending orders, and turnaround time insights.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-sky-50 flex items-center justify-center">
                            <i class="fas fa-x-ray text-sky-600 text-xl"></i>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3">
                        <a href="xray.php#overview" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 transition">
                            <i class="fas fa-arrow-right"></i>
                            <span>Open Xray</span>
                        </a>
                        <a href="xray.php#overview" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                            <i class="fas fa-stopwatch"></i>
                            <span>Turnaround</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900">Quick Links</h2>
                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <a href="out-patient-department.php#nursing-assessment" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-notes-medical text-gray-500"></i>
                            <span>Nursing Assessment</span>
                        </div>
                    </a>
                    <a href="out-patient-department.php#consultation-notes" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-stethoscope text-gray-500"></i>
                            <span>Consultation Notes</span>
                        </div>
                    </a>
                    <a href="out-patient-department.php#lab-new" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-vial text-gray-500"></i>
                            <span>New Lab Request</span>
                        </div>
                    </a>
                    <a href="er.php#np-pa" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user-nurse text-gray-500"></i>
                            <span>NP/PA Lab Req</span>
                        </div>
                    </a>
                    <a href="operating-room.php#cases" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-procedures text-gray-500"></i>
                            <span>OR Active Cases</span>
                        </div>
                    </a>
                    <a href="delivery-room.php#deliveries" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-file-medical text-gray-500"></i>
                            <span>DR Delivery Records</span>
                        </div>
                    </a>

                    <a href="icu.php#overview" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-procedures text-gray-500"></i>
                            <span>ICU Overview</span>
                        </div>
                    </a>

                    <a href="xray.php#overview" class="px-4 py-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition text-sm text-gray-700">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-x-ray text-gray-500"></i>
                            <span>Xray Overview</span>
                        </div>
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>

</html>
