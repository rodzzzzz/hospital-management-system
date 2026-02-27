<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laboratory Management - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <!-- Sidebar -->
        <?php if (false): ?>
        <aside class="fixed inset-y-0 left-0 bg-white shadow-xl w-64">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 border-b border-gray-200">
                    <img src="resources/logo.png" alt="Logo" class="h-10">
                    <span class="ml-3 text-xl font-bold text-gray-800">Hospital</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="index.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-home w-6 text-center"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="patients.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-injured w-6 text-center"></i>
                                <span>Patients</span>
                            </a>
                        </li>
                        <li>
                            <a href="out-patient-department.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-hospital-user w-6 text-center"></i>
                                <span>OPD</span>
                            </a>
                        </li>
                        <li>
                            <a href="dialysis.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-sync-alt w-6 text-center"></i>
                                <span>Dialysis</span>
                            </a>
                        </li>
                        <li>
                            <a href="cashier.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-cash-register w-6 text-center"></i>
                                <span>Cashier</span>
                            </a>
                        </li>
                        <li>
                            <a href="pharmacy.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-pills w-6 text-center"></i>
                                <span>Pharmacy</span>
                            </a>
                        </li>
                        <li>
                            <a href="laboratory.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
                                <i class="fas fa-flask w-6 text-center"></i>
                                <span>Laboratory</span>
                            </a>
                        </li>
                        <li>
                            <a href="inventory.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-boxes w-6 text-center"></i>
                                <span>Inventory</span>
                            </a>
                        </li>
                        <li>
                            <a href="kitchen.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-utensils w-6 text-center"></i>
                                <span>Kitchen</span>
                            </a>
                        </li>
                        <li>
                            <a href="payroll.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-money-check-alt w-6 text-center"></i>
                                <span>Payroll</span>
                            </a>
                        </li>
                        <li>
                            <a href="chat.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-comments w-6 text-center"></i>
                                <span>Chat Messages</span>
                            </a>
                        </li>
                        <li>
                            <a href="employees.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-friends w-6 text-center"></i>
                                <span>Employees</span>
                            </a>
                        </li>
                        <li>
                            <a href="philhealth-claims.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-file-medical-alt w-6 text-center"></i>
                                <span>PhilHealth Claims</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- User Profile -->
                <div class="mt-auto p-4 border-t border-gray-200">
                    <div class="flex items-center gap-x-4">
                        <img src="resources/doctor.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars((string)($authUser['full_name'] ?? ($authUser['username'] ?? 'User')), ENT_QUOTES); ?></p>
                            <p class="text-sm text-gray-500">User</p>
                        </div>
                        <button class="ml-auto text-gray-500 hover:text-red-600" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <?php endif; ?>

        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Laboratory Dashboard</h1>
                <button onclick="toggleModal('scheduleTestModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Schedule Test
                </button>
            </div>

            <div id="labPendingView" class="hidden space-y-6">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending Lab Tests</h3>
                            <p class="text-sm text-gray-600 mt-1">Approved requests can be processed. Requests needing doctor approval are view-only.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input id="labPendingSearch" type="text" class="w-64 px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search request / patient / test" autocomplete="off">
                            <button id="labPendingRefresh" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="labPendingTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div id="labPatientLabTestView" class="hidden space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Patient Lab Test</h3>
                        <p class="text-sm text-gray-600 mt-1">Fill up the lab result form based on requested tests.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button id="labPatientBackBtn" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Back</button>
                        <button id="labPatientSaveBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
                    </div>
                </div>
                <div id="labPatientLabTestContent" class="space-y-6"></div>
            </div>

            <div id="labResultsView" class="hidden space-y-6">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Lab Test Result</h3>
                            <p class="text-sm text-gray-600 mt-1">Completed lab requests.</p>
                        </div>
                        <button id="labResultsRefresh" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Released</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase whitespace-nowrap w-44">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="labResultsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>

                <div id="labResultsResultViewPanel" class="hidden bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Result View</h3>
                            <p class="text-sm text-gray-600 mt-1">Read-only lab test result.</p>
                        </div>
                        <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="closeLabResultsResultView()">Close</button>
                    </div>
                    <div id="labResultsResultViewContent" class="p-6"></div>
                </div>
            </div>

            <div id="labDashboardView">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-vial"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Today's Tests</h2>
                            <p class="text-2xl font-semibold text-gray-800">154</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-hourglass-half"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Pending Results</h2>
                            <p class="text-2xl font-semibold text-gray-800">32</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-exclamation-triangle"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Critical Alerts</h2>
                            <p class="text-2xl font-semibold text-gray-800">3</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-clock"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Avg. Turnaround</h2>
                            <p class="text-2xl font-semibold text-gray-800">2.5 hrs</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                    <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-users mr-2 text-blue-600"></i>
                        Laboratory Queue
                    </h3>
                    <div class="flex flex-wrap gap-2">
                        <button id="labCallNextBtn" onclick="callLabNextPatient()" class="p-4 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                            <i class="fas fa-bell mr-2"></i> Call Next Patient
                        </button>
                        <button onclick="qecOpenReportModal()" class="p-4 bg-red-600 text-white rounded-lg text-lg font-semibold hover:bg-red-700 transition-colors flex items-center">
                            <i class="fas fa-exclamation-triangle mr-2"></i> Report Wrong Station
                        </button>
                    </div>
                </div>

                <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                    <div class="flex items-center mb-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                        <h4 class="text-lg font-semibold text-gray-800">Currently Serving</h4>
                    </div>
                    <div id="labCurrentlyServingQueue" class="text-center py-3">
                        <div class="text-gray-500">No patient being served</div>
                    </div>
                    <div id="labStationSelection" class="mt-4 hidden flex gap-2 justify-end">
                        <button onclick="callLabNextAndMarkUnavailable()" class="p-4 bg-orange-600 text-white rounded-lg text-lg font-semibold hover:bg-orange-700 transition-colors flex items-center">
                            <i class="fas fa-user-slash mr-2"></i> Mark Unavailable
                        </button>
                        <button onclick="openLabSendPatientModal()" class="p-4 bg-green-600 text-white rounded-lg text-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            Send to Next Station
                        </button>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                        <i class="fas fa-list-ol mr-2 text-blue-600"></i>
                        Waiting Queue
                    </h4>
                    <div id="labQueueList" class="space-y-2">
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-users-slash text-4xl mb-2"></i>
                            <p>No patients in queue</p>
                        </div>
                    </div>
                </div>

                <div class="mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                        <i class="fas fa-user-clock mr-2 text-orange-600"></i>
                        Unavailable Patients
                    </h4>
                    <div id="labUnavailablePatientsList" class="space-y-2">
                        <div class="text-center py-6 text-gray-400">
                            <i class="fas fa-check-circle text-3xl mb-2"></i>
                            <p>No unavailable patients</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <button onclick="openLabDisplayScreen()" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center">
                        <i class="fas fa-tv mr-2"></i>
                        Open Display Screen
                    </button>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Test Requests & Equipment -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Test Requests Table -->
                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Test Requests</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Test</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="labRequestsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Analytics Charts -->
                    <div class="space-y-6">
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Test Volume by Type</h3>
                            <canvas id="testVolumeChart" class="w-full h-64"></canvas>
                        </div>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div class="bg-white rounded-lg shadow-sm p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Throughput</h3>
                                    <canvas id="dailyThroughputChart" class="w-full h-64"></canvas>
                                </div>
                                
                            </div>
                           
                        </div>
                    </div>
                </div>

                <!-- Right Column: Calendar & Bed Capacity -->
                <div class="space-y-6">
                    <!-- Scheduling Calendar -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Test Schedule</h3>
                            <div id="calendarMonthYear" class="text-sm font-medium"></div>
                        </div>
                        <div id="testScheduleCalendar" class="grid grid-cols-7 gap-2 text-center text-xs text-gray-500">
                            <!-- Calendar will be generated by JS -->
                        </div>
                    </div>

                    <!-- Bed Capacity -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Bed Capacity</h3>
                        <div class="space-y-4">
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">General Ward</span>
                                    <span class="text-sm font-medium text-gray-700">85/100</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">ICU</span>
                                    <span class="text-sm font-medium text-gray-700">18/20</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-red-600 h-2.5 rounded-full" style="width: 90%"></div>
                                </div>
                            </div>
                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium text-gray-700">Maternity</span>
                                    <span class="text-sm font-medium text-gray-700">12/30</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-green-600 h-2.5 rounded-full" style="width: 40%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Equipment Status -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Equipment Status</h3>
                        <ul class="space-y-3">
                            <li class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Spectrometer</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Operational</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Centrifuge</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Maintenance Due</span>
                            </li>
                            <li class="flex justify-between items-center">
                                <span class="text-sm text-gray-700">Microscope</span>
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Operational</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            </div>
        </main>
    </div>

    <!-- Schedule Test Modal -->
    <div id="scheduleTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Schedule New Test</h3>
                    <button onclick="toggleModal('scheduleTestModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="scheduleTestForm" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <input type="text" placeholder="Search patient by name or ID" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Test Type</label>
                        <select class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>Complete Blood Count (CBC)</option>
                            <option>Lipid Profile</option>
                            <option>Urinalysis</option>
                            <option>Thyroid Panel</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Scheduled Date</label>
                            <input type="date" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                            <select class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>Routine</option>
                                <option>Urgent</option>
                                <option>STAT</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Requesting Doctor</label>
                        <input type="text" placeholder="Enter doctor's name" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal('scheduleTestModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Schedule Test
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- View Test Details Modal -->
    <div id="viewTestModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Test Details</h3>
                    <button onclick="toggleModal('viewTestModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="testDetailsContent" class="p-6 max-h-[70vh] overflow-y-auto">
                <!-- Content will be populated by JS -->
            </div>
            <div class="p-6 bg-gray-50 border-t flex justify-end space-x-4">
                <button type="button" onclick="toggleModal('viewTestModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                    Close
                </button>
            </div>
        </div>
    </div>

    <div id="labSaveSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Success</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeLabSaveSuccess()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="text-sm text-gray-700">Lab result is released successfully.</div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="confirmLabSaveSuccess()">OK</button>
            </div>
        </div>
    </div>

    <div id="labSendPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[60]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
            <div class="p-8 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                <h3 class="text-2xl font-bold text-gray-900">Send Patient to Next Station</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="closeLabSendPatientModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-8 flex-1 overflow-y-auto">
                <div class="mb-6">
                    <label class="block text-lg font-semibold text-gray-700 mb-4">Select Destination Station:</label>
                    <div id="labStationList" class="space-y-4"></div>
                </div>
            </div>
            <div class="p-8 bg-gray-50 border-t flex justify-end gap-4 flex-shrink-0">
                <button type="button" class="px-8 py-4 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold transition-colors" onclick="closeLabSendPatientModal()">
                    Cancel
                </button>
                <button type="button" id="labConfirmSendBtn" class="px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-paper-plane mr-3"></i>
                    Send Patient
                </button>
            </div>
        </div>
    </div>

    <script>
        let currentLabQueueData = null;

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('labPendingRefresh')?.addEventListener('click', loadLabPending);
            document.getElementById('labResultsRefresh')?.addEventListener('click', loadLabResults);
            document.getElementById('labPatientBackBtn')?.addEventListener('click', () => {
                window.location.href = 'laboratory.php#pending';
            });
            document.getElementById('labPatientSaveBtn')?.addEventListener('click', savePatientLabTest);

            let labPendingSearchTimer = null;
            const labPendingSearchEl = document.getElementById('labPendingSearch');
            if (labPendingSearchEl) {
                labPendingSearchEl.addEventListener('input', (e) => {
                    const q = (e.target.value || '').toString();
                    clearTimeout(labPendingSearchTimer);
                    labPendingSearchTimer = setTimeout(() => loadLabPending(), 250);
                });
            }

            applyLabViewFromHash();
            window.addEventListener('hashchange', applyLabViewFromHash);

            const vr = (new URLSearchParams(window.location.search).get('view_result') || '').toString();
            if (vr && /^\d+$/.test(vr)) {
                try {
                    if ((window.location.hash || '').toString().toLowerCase() !== '#lab-test-result') {
                        history.replaceState(null, '', window.location.pathname + window.location.search + '#lab-test-result');
                    }
                } catch (e) {
                    try { window.location.hash = '#lab-test-result'; } catch (e2) {}
                }
                try { applyLabViewFromHash(); } catch (e) {}
                setTimeout(() => {
                    try { viewLabResult(Number(vr)); } catch (e) {}
                }, 0);
            }

            // Render the dynamic calendar
            try {
                renderCalendar();
            } catch (_) {
            }

            // Charts are optional; if Chart.js fails to load, the rest of the page should still work.
            try {
                if (typeof window.Chart !== 'undefined') {
                    const testVolumeEl = document.getElementById('testVolumeChart');
                    if (testVolumeEl && testVolumeEl.getContext) {
                        const testVolumeCtx = testVolumeEl.getContext('2d');
                        if (testVolumeCtx) {
                            new Chart(testVolumeCtx, {
                                type: 'bar',
                                data: {
                                    labels: ['CBC', 'Lipid', 'Urinalysis', 'Thyroid', 'Glucose'],
                                    datasets: [{
                                        label: 'Tests',
                                        data: [120, 85, 95, 60, 110],
                                        backgroundColor: '#60A5FA80',
                                        borderColor: '#3B82F6',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: { legend: { display: false } },
                                    scales: { y: { beginAtZero: true } }
                                }
                            });
                        }
                    }

                    const dailyThroughputEl = document.getElementById('dailyThroughputChart');
                    if (dailyThroughputEl && dailyThroughputEl.getContext) {
                        const dailyThroughputCtx = dailyThroughputEl.getContext('2d');
                        if (dailyThroughputCtx) {
                            new Chart(dailyThroughputCtx, {
                                type: 'line',
                                data: {
                                    labels: ['8am', '10am', '12pm', '2pm', '4pm', '6pm'],
                                    datasets: [{
                                        label: 'Tests Processed',
                                        data: [15, 25, 22, 35, 40, 30],
                                        borderColor: '#34D399',
                                        backgroundColor: '#A7F3D0',
                                        tension: 0.4,
                                        fill: true
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: { legend: { display: false } },
                                    scales: { y: { beginAtZero: true } }
                                }
                            });
                        }
                    }

                    const turnaroundCanvas = document.getElementById('turnaroundTimeChart');
                    if (turnaroundCanvas && turnaroundCanvas.getContext) {
                        const turnaroundTimeCtx = turnaroundCanvas.getContext('2d');
                        if (turnaroundTimeCtx) {
                            new Chart(turnaroundTimeCtx, {
                                type: 'bar',
                                data: {
                                    labels: ['CBC', 'Lipid Profile', 'Urinalysis', 'Thyroid Panel', 'Glucose'],
                                    datasets: [{
                                        label: 'Avg. Hours',
                                        data: [1.5, 3, 2, 4.5, 1],
                                        backgroundColor: '#A78BFA80',
                                        borderColor: '#8B5CF6',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    indexAxis: 'y',
                                    responsive: true,
                                    plugins: { legend: { display: false } },
                                    scales: {
                                        x: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Hours'
                                            }
                                        }
                                    }
                                }
                            });
                        }
                    }

                    const departmentCanvas = document.getElementById('departmentRequestChart');
                    if (departmentCanvas && departmentCanvas.getContext) {
                        const departmentRequestCtx = departmentCanvas.getContext('2d');
                        if (departmentRequestCtx) {
                            new Chart(departmentRequestCtx, {
                                type: 'doughnut',
                                data: {
                                    labels: ['Emergency', 'ICU', 'Cardiology', 'Surgery', 'General Ward'],
                                    datasets: [{
                                        label: 'Requests',
                                        data: [35, 25, 15, 10, 15],
                                        backgroundColor: [
                                            '#EF4444',
                                            '#F97316',
                                            '#84CC16',
                                            '#3B82F6',
                                            '#6366F1'
                                        ],
                                        borderWidth: 0
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    plugins: { legend: { position: 'bottom' } },
                                    cutout: '70%'
                                }
                            });
                        }
                    }
                }
            } catch (_) {
            }

            loadLabQueue();
            window.setInterval(loadLabQueue, 10000);
        });

        // Modal toggle function
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        function toggleFlexModal(modalId) {
            const el = document.getElementById(modalId);
            if (!el) return;
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        async function loadLabQueue() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/display/6');
                currentLabQueueData = await response.json();
                updateLabQueueDisplay();
            } catch (error) {
                console.error('Error loading Laboratory queue:', error);
            }
        }

        function getLabQueueEntryId(row) {
            if (!row) return 0;
            const v = row.queue_id ?? row.queue_entry_id ?? row.id;
            return Number(v || 0);
        }

        function updateLabQueueDisplay() {
            if (!currentLabQueueData) return;

            const currentlyServingDiv = document.getElementById('labCurrentlyServingQueue');
            if (currentLabQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-lg border border-green-300 flex items-center gap-4">
                        <div class="relative h-16 w-16">
                            <div class="absolute h-12 w-12 left-[calc(50%-1.5rem)] top-[calc(50%-1.5rem)] bg-green-500 rounded animate-ping"></div>
                            <div class="relative h-full w-full bg-green-500 text-white text-2xl rounded-md flex flex-col items-center justify-center font-bold">
                                ${currentLabQueueData.currently_serving.queue_number}
                            </div>
                        </div>
                        <div class="flex flex-col items-start text-left">
                            <div class="text-2xl font-bold text-green-700 line-clamp-1">${currentLabQueueData.currently_serving.full_name}</div>
                            <div class="text-sm text-gray-600">${currentLabQueueData.currently_serving.patient_code || ''}</div>
                        </div>
                    </div>
                `;

                document.getElementById('labStationSelection').classList.remove('hidden');
                loadLabStationOptions();
            } else {
                currentlyServingDiv.innerHTML = `
                    <div class="text-gray-500">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p>No patient being served</p>
                    </div>
                `;
                document.getElementById('labStationSelection').classList.add('hidden');
            }

            const queueListDiv = document.getElementById('labQueueList');
            if (currentLabQueueData.next_patients && currentLabQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentLabQueueData.next_patients.map((patient) => `
                    <div class="flex justify-between items-center p-2 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-md flex items-center justify-center font-bold">
                                ${patient.queue_number}
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-800 line-clamp-1">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                queueListDiv.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-users-slash text-4xl mb-2"></i>
                        <p>No patients in queue</p>
                    </div>
                `;
            }

            const unavailableDiv = document.getElementById('labUnavailablePatientsList');
            if (currentLabQueueData.unavailable_patients && currentLabQueueData.unavailable_patients.length > 0) {
                unavailableDiv.innerHTML = currentLabQueueData.unavailable_patients.map(patient => `
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded-lg border border-orange-200 cursor-pointer hover:bg-orange-100 transition-colors" onclick="recallLabUnavailablePatient(${getLabQueueEntryId(patient)})">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-600 text-white rounded-md flex items-center justify-center font-bold">
                                ${patient.queue_number}
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-800 line-clamp-1">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                unavailableDiv.innerHTML = `
                    <div class="text-center py-6 text-gray-400">
                        <i class="fas fa-check-circle text-3xl mb-2"></i>
                        <p>No unavailable patients</p>
                    </div>
                `;
            }
        }

        function openLabSendPatientModal() {
            if (!currentLabQueueData?.currently_serving) {
                Toastify({
                    text: 'Please call a patient first before sending to next station',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#F59E0B',
                }).showToast();
                return;
            }

            const modal = document.getElementById('labSendPatientModal');
            modal.classList.remove('hidden');
            loadLabStationsForModal();
        }

        function closeLabSendPatientModal() {
            const modal = document.getElementById('labSendPatientModal');
            modal.classList.add('hidden');
            document.getElementById('labConfirmSendBtn').disabled = true;
            document.querySelectorAll('.lab-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });
        }

        function loadLabStationsForModal() {
            const stationList = document.getElementById('labStationList');
            stationList.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i> <p class="mt-2 text-lg">Loading stations...</p></div>';

            const dischargeOption = document.createElement('div');
            dischargeOption.className = 'lab-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200 transform hover:scale-[1.02]';
            dischargeOption.onclick = () => selectLabStation('discharge', dischargeOption);
            dischargeOption.innerHTML = `
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-xl flex items-center justify-center mr-6">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xl font-bold text-gray-800">Complete and Discharge</div>
                        <div class="text-base text-gray-600 mt-1">Patient consultation complete</div>
                    </div>
                </div>
            `;

            fetch(API_BASE_URL + '/queue/stations')
                .then(response => response.json())
                .then(data => {
                    stationList.innerHTML = '';

                    data.stations.forEach(station => {
                        if (station.id !== 6) {
                            const stationOption = document.createElement('div');
                            stationOption.className = 'lab-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.02]';
                            stationOption.onclick = () => selectLabStation(station.id, stationOption);

                            let icon = 'fa-arrow-right';
                            let iconColor = 'bg-blue-600';
                            const stationName = (station.station_display_name || '').toLowerCase();
                            if (stationName.includes('doctor') || stationName.includes('consultation')) {
                                icon = 'fa-user-md';
                                iconColor = 'bg-purple-600';
                            } else if (stationName.includes('pharmacy')) {
                                icon = 'fa-pills';
                                iconColor = 'bg-pink-600';
                            } else if (stationName.includes('cashier') || stationName.includes('payment') || stationName.includes('billing')) {
                                icon = 'fa-cash-register';
                                iconColor = 'bg-yellow-600';
                            } else if (stationName.includes('lab') || stationName.includes('laboratory')) {
                                icon = 'fa-flask';
                                iconColor = 'bg-cyan-600';
                            } else if (stationName.includes('x-ray') || stationName.includes('radiology')) {
                                icon = 'fa-x-ray';
                                iconColor = 'bg-indigo-600';
                            } else if (stationName.includes('nurse') || stationName.includes('triage')) {
                                icon = 'fa-user-nurse';
                                iconColor = 'bg-red-600';
                            }

                            stationOption.innerHTML = `
                                <div class="flex items-center">
                                    <div class="w-16 h-16 ${iconColor} text-white rounded-xl flex items-center justify-center mr-6">
                                        <i class="fas ${icon} text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xl font-bold text-gray-800">${station.station_display_name}</div>
                                        <div class="text-base text-gray-600 mt-1">Move to ${station.station_display_name}</div>
                                    </div>
                                </div>
                            `;
                            stationList.appendChild(stationOption);
                        }
                    });

                    stationList.appendChild(dischargeOption);
                })
                .catch(() => {
                    stationList.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p class="text-lg">Failed to load stations</p></div>';
                });
        }

        function selectLabStation(stationId, element) {
            document.querySelectorAll('.lab-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });

            if (stationId === 'discharge') {
                element.classList.add('ring-4', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            } else {
                element.classList.add('ring-4', 'ring-blue-500', 'bg-blue-50', 'shadow-lg');
            }

            document.getElementById('labConfirmSendBtn').disabled = false;
            document.getElementById('labConfirmSendBtn').onclick = () => sendLabPatientToStation(stationId);
        }

        async function sendLabPatientToStation(stationId) {
            if (!currentLabQueueData?.currently_serving) return;

            try {
                const body = {
                    queue_id: currentLabQueueData.currently_serving.id
                };

                if (stationId !== 'discharge') {
                    body.target_station_id = parseInt(stationId, 10);
                }

                const response = await fetch(API_BASE_URL + '/queue/complete-service', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });

                const result = await response.json();
                const isSuccess = response.ok && (result.ok === true || result.success === true);

                if (isSuccess) {
                    Toastify({
                        text: stationId === 'discharge' ? 'Patient discharged successfully' : 'Patient sent to next station',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();

                    closeLabSendPatientModal();
                    loadLabQueue();
                } else {
                    const errorMessage = result.error || result.message || 'Failed to send patient';
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error sending patient to station:', error);
                Toastify({
                    text: error.message || 'Failed to send patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        function loadLabStationOptions() {
            // Compatibility function. Station selection is handled by modal.
        }

        async function callLabNextPatient() {
            try {
                if (currentLabQueueData?.currently_serving) {
                    Toastify({
                        text: 'Please complete the current patient service before calling the next patient',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                    return;
                }

                const response = await fetch(API_BASE_URL + '/queue/call-next', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ station_id: 6 })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Next patient called successfully',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadLabQueue();
                } else {
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.toLowerCase().includes('no active transaction')) {
                        message = 'No more patients in the waiting queue';
                    }
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error calling next patient:', error);
                Toastify({
                    text: 'Error calling next patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function recallLabUnavailablePatient(queueId) {
            try {
                const response = await fetch(API_BASE_URL + '/queue/recall-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId,
                        notes: 'Recalled from unavailable list'
                    })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Patient recalled successfully',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadLabQueue();
                } else {
                    Toastify({
                        text: result.message || 'Unable to recall patient',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#EF4444',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error recalling unavailable patient:', error);
                Toastify({
                    text: 'Failed to recall patient from unavailable list',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function callLabNextAndMarkUnavailable() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        station_id: 6,
                        notes: 'Patient not available for service'
                    })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Next patient called and previous patient marked as unavailable',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadLabQueue();
                } else {
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.toLowerCase().includes('no active transaction')) {
                        message = 'No more patients in the waiting queue';
                    }
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error calling next and marking unavailable:', error);
                Toastify({
                    text: 'Error calling next patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        function openLabDisplayScreen() {
            window.open('laboratory-display.php', '_blank');
        }

        async function completeService() {
            Toastify({
                text: 'Please use the "Send Patient" button instead',
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#F59E0B',
            }).showToast();
        }

        function escapeHtml(s) {
            return (s ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function formatPatientAddress(r) {
            const parts = [r.street_address, r.barangay, r.city, r.province, r.zip_code]
                .map(x => (x ?? '').toString().trim())
                .filter(Boolean);
            return parts.join(', ');
        }

        function calculateAgeFromDob(dob) {
            const s = (dob ?? '').toString().trim();
            if (!s || s === '0000-00-00') return '';
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return '';
            const now = new Date();
            let age = now.getFullYear() - d.getFullYear();
            const m = now.getMonth() - d.getMonth();
            if (m < 0 || (m === 0 && now.getDate() < d.getDate())) age -= 1;
            return String(Math.max(0, age));
        }

        function formatDateIssued(d) {
            try {
                const dt = (d instanceof Date) ? d : new Date(d);
                if (Number.isNaN(dt.getTime())) return '';
                return dt.toLocaleDateString();
            } catch (_) {
                return '';
            }
        }

        function requestStatusChip(status) {
            const s = (status ?? '').toString();
            if (s === 'approved') return { cls: 'bg-blue-100 text-blue-800', label: 'Approved' };
            if (s === 'collected') return { cls: 'bg-purple-100 text-purple-800', label: 'Collected' };
            if (s === 'in_progress') return { cls: 'bg-indigo-100 text-indigo-800', label: 'In Progress' };
            if (s === 'completed') return { cls: 'bg-green-100 text-green-800', label: 'Completed' };
            if (s === 'pending_approval') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Needs Approval' };
            if (s === 'rejected') return { cls: 'bg-red-100 text-red-800', label: 'Rejected' };
            return { cls: 'bg-gray-100 text-gray-800', label: s };
        }

        function pendingStatusChip(row) {
            const status = (row?.status ?? '').toString();
            if (status === 'pending_approval') {
                return { cls: 'bg-yellow-100 text-yellow-800', label: 'Needs Approval' };
            }
            if (status === 'approved') {
                return { cls: 'bg-blue-100 text-blue-800', label: `Approved by ${escapeHtml(row?.approved_by || 'Doctor')}` };
            }
            if (status === 'in_progress') {
                return { cls: 'bg-purple-100 text-purple-800', label: 'In Progress' };
            }
            return requestStatusChip(status);
        }

        function labCashierStatusChip(cashierStatus) {
            const s = (cashierStatus ?? '').toString().trim().toLowerCase();
            if (s === 'submitted_to_cashier') return { cls: 'bg-green-100 text-green-800', label: 'Submitted to Cashier' };
            if (s === 'pending_fee' || s === '') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Pending Fee' };
            return { cls: 'bg-gray-100 text-gray-800', label: (cashierStatus ?? '').toString() };
        }

        function priorityChip(priority) {
            const p = (priority ?? '').toString().toLowerCase();
            if (p === 'stat') return { cls: 'bg-red-100 text-red-800', label: 'STAT' };
            if (p === 'urgent') return { cls: 'bg-orange-100 text-orange-800', label: 'Urgent' };
            return { cls: 'bg-gray-100 text-gray-800', label: 'Routine' };
        }

        function applyLabViewFromHash() {
            const h = (window.location.hash || '').toString().replace(/^#/, '').toLowerCase();
            const view = h || 'dashboard';

            const pending = document.getElementById('labPendingView');
            const patientTest = document.getElementById('labPatientLabTestView');
            const results = document.getElementById('labResultsView');
            const dashboard = document.getElementById('labDashboardView');
            const patientSave = document.getElementById('labPatientSaveBtn');

            const show = (el, yes) => { if (!el) return; el.classList.toggle('hidden', !yes); };

            show(pending, view === 'pending');
            show(patientTest, view === 'patient-lab-test');
            show(results, view === 'lab-test-result');
            show(dashboard, !(view === 'pending' || view === 'patient-lab-test' || view === 'lab-test-result'));

            if (view === 'pending') {
                if (patientSave) patientSave.classList.add('hidden');
                loadLabPending();
            } else if (view === 'patient-lab-test') {
                const id = (new URLSearchParams(window.location.search).get('request_id') || '').toString();
                if (id && /^\d+$/.test(id)) {
                    if (patientSave) patientSave.classList.add('hidden');
                    loadPatientLabTest(Number(id));
                } else {
                    if (patientSave) patientSave.classList.add('hidden');
                    const content = document.getElementById('labPatientLabTestContent');
                    if (content) {
                        content.innerHTML = `
                            <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                                <div class="p-6">
                                    <div class="text-sm text-gray-900 font-semibold">Select a request to proceed</div>
                                    <div class="text-sm text-gray-600 mt-1">Go to <strong>Pending Lab Tests</strong> and click <strong>Process</strong> to open the Patient Lab Test form.</div>
                                    <div class="mt-4">
                                        <a href="laboratory.php#pending" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Go to Pending Lab Tests</a>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                }
            } else if (view === 'lab-test-result') {
                if (patientSave) patientSave.classList.add('hidden');
                loadLabResults();
            } else {
                if (patientSave) patientSave.classList.add('hidden');
                loadLabRequests();
            }
        }

        async function loadLabPending() {
            const tbody = document.getElementById('labPendingTbody');
            if (!tbody) return;

            const q = (document.getElementById('labPendingSearch')?.value || '').toString().trim();
            const url = API_BASE_URL + '/lab/list_requests.php?mode=lab_pending' + (q ? ('&q=' + encodeURIComponent(q)) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            tbody.innerHTML = rows.map(r => {
                const chip = pendingStatusChip(r);
                const pchip = priorityChip(r.priority);
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const patient = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');

                const canProcess = (r.status === 'approved');
                const isInProgress = (r.status === 'in_progress');
                const processBtn = canProcess
                    ? `<button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="processLabRequest(${Number(r.id)})">Process</button>`
                    : '';
                const continueBtn = isInProgress
                    ? `<button class="px-3 py-1.5 text-sm bg-purple-600 text-white rounded-lg hover:bg-purple-700" onclick="openPatientLabTest(${Number(r.id)})">Continue</button>`
                    : '';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${patient}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.chief_complaint || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${pchip.cls}">${pchip.label}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2">
                            <button onclick="viewTestDetails(${Number(r.id)})" class="px-3 py-1.5 text-sm border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">View</button>
                            ${processBtn}
                            ${continueBtn}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function processLabRequest(id) {
            const res = await fetch(API_BASE_URL + '/lab/update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ request_id: Number(id), status: 'in_progress' })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? json.error : 'Unable to process request');
                return;
            }
            window.location.href = 'laboratory.php?request_id=' + encodeURIComponent(String(id)) + '#patient-lab-test';
        }

        function openPatientLabTest(id) {
            window.location.href = 'laboratory.php?request_id=' + encodeURIComponent(String(id)) + '#patient-lab-test';
        }

        async function loadLabResults() {
            const tbody = document.getElementById('labResultsTbody');
            if (!tbody) return;

            const panel = document.getElementById('labResultsResultViewPanel');
            const content = document.getElementById('labResultsResultViewContent');
            if (panel) panel.classList.add('hidden');
            if (content) content.innerHTML = '';

            const res = await fetch(API_BASE_URL + '/lab/list_requests.php?status=completed', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            tbody.innerHTML = rows.map(r => {
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const patient = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                const releasedBy = escapeHtml(r.released_by || '');
                const releasedAt = escapeHtml(r.released_at || '');
                const released = (releasedBy || releasedAt) ? `${releasedBy}${releasedAt ? (' - ' + releasedAt) : ''}` : '';

                const cashierChip = labCashierStatusChip(r.cashier_status);
                const isSubmitted = ((r.cashier_status ?? '').toString().trim().toLowerCase() === 'submitted_to_cashier');

                const rowId = Number(r.id || 0);
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${patient}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700">${released}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 text-xs rounded-full whitespace-nowrap ${cashierChip.cls}">${cashierChip.label}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex">
                                <button type="button" class="px-3 py-2 text-sm border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" data-action-menu-btn="1" data-row-id="${rowId}" data-is-submitted="${isSubmitted ? '1' : '0'}">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');

            bindActionMenus();
        }

        async function submitLabResultToCashier(requestId) {
            const id = Number(requestId || 0);
            if (!id) return;
            try {
                const res = await fetch(API_BASE_URL + '/lab/submit_to_cashier.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ request_id: id })
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    throw new Error((json && json.error) ? json.error : 'Unable to submit to cashier');
                }

                Toastify({
                    text: 'Submitted to cashier',
                    duration: 2500,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#10B981',
                }).showToast();

                await loadLabResults();
            } catch (e) {
                Toastify({
                    text: (e && e.message) ? e.message : 'Unable to submit to cashier',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        function closeActionMenu(menuId) {
            const portal = document.getElementById('labResActMenuPortal');
            if (portal) portal.classList.add('hidden');
        }

        function bindActionMenus() {
            document.querySelectorAll('[data-action-menu-btn="1"]').forEach(btn => {
                if (btn.dataset.bound === '1') return;
                btn.dataset.bound = '1';
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    openLabResultsActionMenu(btn);
                });
            });

            if (document.body && document.body.dataset.actionMenusBound !== '1') {
                document.body.dataset.actionMenusBound = '1';
                document.addEventListener('click', () => {
                    closeActionMenu('');
                });
            }
        }

        function ensureLabResultsActionMenuPortal() {
            let portal = document.getElementById('labResActMenuPortal');
            if (portal) return portal;

            portal = document.createElement('div');
            portal.id = 'labResActMenuPortal';
            portal.className = 'hidden fixed w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-50 overflow-hidden';
            document.body.appendChild(portal);
            return portal;
        }

        function openLabResultsActionMenu(btn) {
            const rowId = Number(btn.getAttribute('data-row-id') || 0);
            if (!rowId) return;
            const isSubmitted = (btn.getAttribute('data-is-submitted') || '') === '1';

            const portal = ensureLabResultsActionMenuPortal();

            const submitBtn = isSubmitted
                ? `<button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-400 cursor-not-allowed" disabled>Submit to Cashier</button>`
                : `<button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" onclick="submitLabResultToCashier(${rowId}); closeActionMenu('')">Submit to Cashier</button>`;

            portal.innerHTML = `
                <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" onclick="viewLabResult(${rowId}); closeActionMenu('')">View</button>
                <button type="button" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" onclick="printLabResult(${rowId}); closeActionMenu('')">Print Results</button>
                ${submitBtn}
            `;

            const rect = btn.getBoundingClientRect();
            const gap = 8;
            const width = 224;
            const margin = 8;

            let left = rect.left;
            const maxLeft = Math.max(margin, (window.innerWidth || 0) - width - margin);
            if (left > maxLeft) left = maxLeft;
            if (left < margin) left = margin;
            let top = rect.bottom + gap;
            if (top < margin) top = margin;

            portal.style.left = left + 'px';
            portal.style.top = top + 'px';

            portal.classList.remove('hidden');
        }

        async function printLabResult(requestId) {
            const id = Number(requestId || 0);
            if (!id) return;
            await viewLabResult(id);
            printLabResultFromPanel();
        }

        function printLabResultFromPanel() {
            const content = document.getElementById('labResultsResultViewContent');
            if (!content) return;

            const html = content.innerHTML || '';
            if (!html.trim()) return;

            const w = window.open('', '_blank');
            if (!w) return;

            w.document.open();
            w.document.write(`
                <!DOCTYPE html>
                <html lang="en">
                <head>
    <?php include __DIR__ . '/config.js.php'; ?>
                    <meta charset="UTF-8" />
                    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    <title>Lab Test Result</title>
                    <script src="https://cdn.tailwindcss.com"><\/script>
                    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
                    <style>
                        @media print {
                            body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
                        }
                    </style>
                </head>
                <body class="bg-white">
                    <div class="p-6">
                        ${html}
                    </div>
                    <script>
                        window.addEventListener('load', () => {
                            window.focus();
                            window.print();
                            window.close();
                        });
                    <\/script>
                </body>
                </html>
            `);
            w.document.close();
        }

        async function viewLabResult(requestId) {
            const panel = document.getElementById('labResultsResultViewPanel');
            const content = document.getElementById('labResultsResultViewContent');
            if (!panel || !content) return;

            panel.classList.remove('hidden');
            content.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';

            const res = await fetch(API_BASE_URL + '/lab/get_request.php?id=' + encodeURIComponent(String(requestId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load result.</div>';
                panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];

            const age = calculateAgeFromDob(r.dob);
            const sex = (r.sex ?? '').toString();
            const addr = formatPatientAddress(r);
            const dateIssued = formatDateIssued(new Date());
            const releasedByValue = (items[0]?.released_by || '').toString();

            content.innerHTML = `
                <div class="space-y-4" data-readonly-view="1">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Released by / MedTech name</div>
                        <div class="p-6">
                            <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" value="${escapeHtml(releasedByValue)}" readonly />
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Patient’s Basic Info</div>
                        <div class="p-6">
                            <div class="text-center">
                                <div class="text-sm font-semibold text-gray-900">MUNICIPAL HEALTH OFFICE</div>
                            </div>

                            <div class="mt-5 space-y-4">
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-2">
                                        <div class="text-xs font-semibold text-gray-800">NAME:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.full_name || '')}</div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">AGE:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(age)}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">SEX:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(sex)}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-2">
                                        <div class="text-xs font-semibold text-gray-800">ADDRESS:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(addr)}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">DATE ISSUED:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(dateIssued)}</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">BLOOD TYPE:</div>
                                        <input id="labViewHeaderBloodType" type="text" value="${escapeHtml((r.blood_type || '').toString())}" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" readonly />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">HEMATOCRIT:</div>
                                            <input id="labViewHeaderHematocrit" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">HEMOGLOBIN:</div>
                                            <input id="labViewHeaderHemoglobin" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">BLOOD SUGAR:</div>
                                        <div class="mt-1 flex items-end gap-2">
                                            <input id="labViewHeaderBloodSugar" type="text" class="flex-1 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                            <div class="text-xs text-gray-700 whitespace-nowrap">mg/dL</div>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">REQUEST NO:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.request_no || '')}</div>
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">PATIENT ID:</div>
                                            <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.patient_code || '')}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">ER Lab Request Form</div>
                        <div class="p-6 space-y-2">
                            <div><span class="text-xs text-gray-500">Chief Complaint:</span> <span class="text-sm font-semibold">${escapeHtml(r.chief_complaint || '')}</span></div>
                            <div><span class="text-xs text-gray-500">Triage Level:</span> <span class="text-sm font-semibold">${escapeHtml(r.triage_level || '')}</span></div>
                            <div><span class="text-xs text-gray-500">Notes:</span> <span class="text-sm font-semibold">${escapeHtml(r.notes || '')}</span></div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Lab Result Form</div>
                        <div class="p-6 space-y-4">
                            ${items.map(it => {
                                let card = '';
                                if (isUrinalysisTest(it.test_name || '')) {
                                    card = renderUrinalysisEntryCard(it, r);
                                } else if (isCbcTest(it.test_name || '')) {
                                    card = renderCbcEntryCard(it, r);
                                } else if (isRbsTest(it.test_name || '')) {
                                    card = renderRbsEntryCard(it, r);
                                } else if (isBunTest(it.test_name || '')) {
                                    card = renderBunEntryCard(it, r);
                                } else if (isCreatinineTest(it.test_name || '')) {
                                    card = renderCreatinineEntryCard(it, r);
                                } else if (isElectrolytesTest(it.test_name || '')) {
                                    card = renderElectrolytesEntryCard(it, r);
                                } else {
                                    card = `
                                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                                            <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                                            <div class="p-4">
                                                <textarea class="lab-result-text w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" data-item-id="${Number(it.id)}">${escapeHtml(it.result_text || '')}</textarea>
                                            </div>
                                        </div>
                                    `;
                                }

                                const releasedAt = it.released_at ? ('(' + escapeHtml(it.released_at) + ')') : '';
                                const releasedLine = (it.released_by || it.released_at)
                                    ? `<div class="text-xs text-gray-500 mt-2">Released by: ${escapeHtml(it.released_by || '')} ${releasedAt}</div>`
                                    : '';
                                return `
                                    <div>
                                        ${card}
                                        ${releasedLine}
                                    </div>
                                `;
                            }).join('')}
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Laboratory Billing</div>
                        <div class="p-6" id="labResultInvoiceContainer">
                            <div class="text-sm text-gray-600">Loading...</div>
                        </div>
                    </div>
                </div>
            `;

            const cbcItem = items.find(x => isCbcTest(x.test_name || ''));
            const rbsItem = items.find(x => isRbsTest(x.test_name || ''));
            const cbcParsed = cbcItem ? parseLabeledResultText(cbcItem.result_text || '') : {};
            const rbsParsed = rbsItem ? parseLabeledResultText(rbsItem.result_text || '') : {};
            const hb = (cbcParsed.hemoglobin ?? '').toString();
            const hct = (cbcParsed.hematocrit ?? '').toString();
            const bs = (rbsParsed.blood_sugar ?? '').toString();
            const hbEl = document.getElementById('labViewHeaderHemoglobin');
            const hctEl = document.getElementById('labViewHeaderHematocrit');
            const bsEl = document.getElementById('labViewHeaderBloodSugar');
            if (hbEl) hbEl.value = hb.trim();
            if (hctEl) hctEl.value = hct.trim();
            if (bsEl) bsEl.value = bs.trim();

            const roRoot = content.querySelector('[data-readonly-view="1"]');
            if (roRoot) {
                roRoot.querySelectorAll('textarea').forEach(el => {
                    el.readOnly = true;
                });
                roRoot.querySelectorAll('input').forEach(el => {
                    el.readOnly = true;
                });
                roRoot.querySelectorAll('select, button').forEach(el => {
                    el.disabled = true;
                });
            }

            await loadLabResultInvoice(Number(requestId));

            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        function renderInvoiceHtml(inv) {
            if (!inv) {
                return '<div class="text-sm text-gray-600">No invoice found for this lab result.</div>';
            }

            const items = Array.isArray(inv.items) ? inv.items : [];
            const status = (inv.status || '').toString();
            const total = (inv.total || '0.00').toString();
            const paid = (inv.paid || '0.00').toString();
            const balance = (inv.balance || '0.00').toString();

            return `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <div class="text-xs font-semibold text-gray-600">INVOICE #</div>
                            <div class="text-sm font-semibold text-gray-900">${escapeHtml(String(inv.id || ''))}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-600">STATUS</div>
                            <div class="text-sm font-semibold text-gray-900">${escapeHtml(status.toUpperCase())}</div>
                        </div>
                        <div>
                            <div class="text-xs font-semibold text-gray-600">DATE</div>
                            <div class="text-sm font-semibold text-gray-900">${escapeHtml(String(inv.created_at || ''))}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs font-semibold text-gray-600">TOTAL</div>
                            <div class="text-sm font-extrabold text-gray-900">₱${escapeHtml(total)}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-3 py-2 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${items.map(it => `
                                    <tr>
                                        <td class="px-3 py-2 text-sm text-gray-800">${escapeHtml(String(it.description || ''))}</td>
                                        <td class="px-3 py-2 text-sm text-gray-800 text-right">${escapeHtml(String(it.qty ?? ''))}</td>
                                        <td class="px-3 py-2 text-sm text-gray-800 text-right">₱${escapeHtml(String(it.unit_price || '0.00'))}</td>
                                        <td class="px-3 py-2 text-sm text-gray-800 text-right">₱${escapeHtml(String(it.subtotal || '0.00'))}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2"></div>
                        <div class="space-y-1">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Paid</span>
                                <span class="font-semibold text-gray-900">₱${escapeHtml(paid)}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Balance</span>
                                <span class="font-semibold text-gray-900">₱${escapeHtml(balance)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function loadLabResultInvoice(requestId) {
            const container = document.getElementById('labResultInvoiceContainer');
            if (!container) return;

            container.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';
            const url = API_BASE_URL + '/cashier/get_invoice_by_source.php?source_module=lab_request&source_id=' + encodeURIComponent(String(requestId));
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                container.innerHTML = '<div class="text-sm text-red-600">Unable to load invoice.</div>';
                return;
            }

            container.innerHTML = renderInvoiceHtml(json.invoice || null);
        }

        function closeLabResultsResultView() {
            const panel = document.getElementById('labResultsResultViewPanel');
            const content = document.getElementById('labResultsResultViewContent');
            if (content) content.innerHTML = '';
            if (panel) panel.classList.add('hidden');
        }

        let currentPatientLabRequestId = null;
        let labSaveRedirectUrl = null;

        function normalizeTestName(name) {
            return (name ?? '').toString().trim().toLowerCase();
        }

        function isUrinalysisTest(name) {
            const n = normalizeTestName(name);
            return n === 'urinalysis' || n.includes('urinalysis');
        }

        function isCbcTest(name) {
            const n = normalizeTestName(name);
            return n === 'complete blood count (cbc)' || n === 'cbc' || n.includes('complete blood count') || n.includes('cbc');
        }

        function isRbsTest(name) {
            const n = normalizeTestName(name);
            return n === 'random blood sugar (rbs)' || n === 'rbs' || n.includes('random blood sugar') || n.includes('rbs');
        }

        function isBunTest(name) {
            const n = normalizeTestName(name);
            return n === 'bun' || n.includes('bun');
        }

        function isCreatinineTest(name) {
            const n = normalizeTestName(name);
            return n === 'creatinine' || n.includes('creatinine');
        }

        function isElectrolytesTest(name) {
            const n = normalizeTestName(name);
            return n === 'electrolytes (na/k/cl)' || n === 'electrolytes' || n.includes('electrolytes') || n.includes('na/k/cl');
        }

        function parseLabeledResultText(resultText) {
            const out = {};
            const text = (resultText ?? '').toString();
            const lines = text.split(/\r?\n/);
            for (const rawLine of lines) {
                const line = rawLine.trim();
                if (!line) continue;
                const m = line.match(/^([A-Za-z0-9\s\-/()]+)\s*:\s*(.*)$/);
                if (!m) continue;
                const key = m[1]
                    .trim()
                    .toLowerCase()
                    .replace(/[^a-z0-9]+/g, '_')
                    .replace(/^_+|_+$/g, '');
                out[key] = (m[2] ?? '').toString();
            }
            return out;
        }

        function formatUrinalysisResultText(fields) {
            const g = (k) => (fields && fields[k] != null) ? String(fields[k]).trim() : '';
            const lines = [];
            lines.push('URINALYSIS');
            lines.push(`COLOR: ${g('color')}`);
            lines.push(`TRANSPARENCY: ${g('transparency')}`);
            lines.push(`PROTEIN: ${g('protein')}`);
            lines.push(`GLUCOSE: ${g('glucose')}`);
            lines.push(`PH: ${g('ph')}`);
            lines.push(`SPECIFIC GRAVITY: ${g('specific_gravity')}`);
            lines.push('');
            lines.push(`WBC: ${g('wbc')}`);
            lines.push(`RBC: ${g('rbc')}`);
            lines.push(`CAST: ${g('cast')}`);
            lines.push(`BACTERIA: ${g('bacteria')}`);
            lines.push(`EPITHELIAL CELLS: ${g('epithelial_cells')}`);
            lines.push(`CRYSTALS: ${g('crystals')}`);
            lines.push(`OTHERS: ${g('others')}`);
            lines.push('');
            lines.push(`PREGNANCY TEST: ${g('pregnancy_test')}`);
            lines.push(`SYPHILIS TEST: ${g('syphilis_test')}`);
            lines.push(`HIV TEST/ SD BIOLINE HIV 1/ 2: ${g('hiv_test')}`);
            lines.push(`HEPATITIS B SCREENING(HBsAg): ${g('hepatitis_b_screening_hbsag')}`);
            return lines.join('\n');
        }

        function formatCbcResultText(fields) {
            const g = (k) => (fields && fields[k] != null) ? String(fields[k]).trim() : '';
            const lines = [];
            lines.push('CBC');
            lines.push(`HEMOGLOBIN: ${g('hemoglobin')}`);
            lines.push(`HEMATOCRIT: ${g('hematocrit')}`);
            lines.push(`WBC: ${g('wbc')}`);
            lines.push(`RBC: ${g('rbc')}`);
            lines.push(`PLATELET: ${g('platelet')}`);
            lines.push(`OTHERS: ${g('others')}`);
            return lines.join('\n');
        }

        function formatRbsResultText(fields) {
            const g = (k) => (fields && fields[k] != null) ? String(fields[k]).trim() : '';
            const lines = [];
            lines.push('RBS');
            lines.push(`BLOOD SUGAR: ${g('blood_sugar')}`);
            lines.push(`UNIT: ${g('unit') || 'mg/dL'}`);
            lines.push(`REMARKS: ${g('remarks')}`);
            return lines.join('\n');
        }

        function formatBunResultText(fields) {
            const g = (k) => (fields && fields[k] != null) ? String(fields[k]).trim() : '';
            const lines = [];
            lines.push('BUN');
            lines.push(`BUN: ${g('bun')}`);
            lines.push(`UNIT: ${g('unit') || 'mg/dL'}`);
            lines.push(`REMARKS: ${g('remarks')}`);
            return lines.join('\n');
        }

        function formatCreatinineResultText(fields) {
            const g = (k) => (fields && fields[k] != null) ? String(fields[k]).trim() : '';
            const lines = [];
            lines.push('CREATININE');
            lines.push(`CREATININE: ${g('creatinine')}`);
            lines.push(`UNIT: ${g('unit') || 'mg/dL'}`);
            lines.push(`REMARKS: ${g('remarks')}`);
            return lines.join('\n');
        }

        function formatElectrolytesResultText(fields) {
            const g = (k) => (fields && fields[k] != null) ? String(fields[k]).trim() : '';
            const lines = [];
            lines.push('ELECTROLYTES');
            lines.push(`SODIUM (Na): ${g('sodium')}`);
            lines.push(`POTASSIUM (K): ${g('potassium')}`);
            lines.push(`CHLORIDE (Cl): ${g('chloride')}`);
            lines.push(`UNIT: ${g('unit') || 'mmol/L'}`);
            lines.push(`REMARKS: ${g('remarks')}`);
            return lines.join('\n');
        }

        function formalLineInput(opts) {
            const itemId = Number(opts.itemId);
            const field = (opts.field || '').toString();
            const value = escapeHtml(opts.value || '');
            const placeholder = escapeHtml(opts.placeholder || '');
            const unit = escapeHtml(opts.unit || '');
            const label = escapeHtml(opts.label || '');

            return `
                <div class="grid grid-cols-12 items-center gap-2">
                    <div class="text-xs font-semibold text-gray-800 col-span-4">${label}</div>
                    <div class="col-span-7">
                        <input class="formal-field w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1" data-item-id="${itemId}" data-field="${escapeHtml(field)}" data-template="${escapeHtml(opts.template || '')}" value="${value}" placeholder="${placeholder}" />
                    </div>
                    <div class="col-span-1 text-right">
                        ${unit ? `<div class="text-xs text-gray-600 whitespace-nowrap">${unit}</div>` : ''}
                    </div>
                </div>
            `;
        }

        function renderCbcEntryCard(it, request) {
            const itemId = Number(it.id);
            const parsed = parseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">COMPLETE BLOOD COUNT (CBC)</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-4">
                                    ${formalLineInput({ template: 'cbc', itemId, field: 'hemoglobin', label: 'HEMOGLOBIN', value: v('hemoglobin'), unit: 'g/dL' })}
                                    ${formalLineInput({ template: 'cbc', itemId, field: 'hematocrit', label: 'HEMATOCRIT', value: v('hematocrit'), unit: '%' })}
                                    ${formalLineInput({ template: 'cbc', itemId, field: 'wbc', label: 'WBC', value: v('wbc'), unit: '' })}
                                </div>
                                <div class="space-y-4">
                                    ${formalLineInput({ template: 'cbc', itemId, field: 'rbc', label: 'RBC', value: v('rbc'), unit: '' })}
                                    ${formalLineInput({ template: 'cbc', itemId, field: 'platelet', label: 'PLATELET', value: v('platelet'), unit: '' })}
                                    ${formalLineInput({ template: 'cbc', itemId, field: 'others', label: 'OTHERS', value: v('others'), unit: '' })}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderRbsEntryCard(it, request) {
            const itemId = Number(it.id);
            const parsed = parseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">BLOOD SUGAR</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${formalLineInput({ template: 'rbs', itemId, field: 'blood_sugar', label: 'RESULT', value: v('blood_sugar'), unit: 'mg/dL' })}
                                ${formalLineInput({ template: 'rbs', itemId, field: 'remarks', label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderBunEntryCard(it, request) {
            const itemId = Number(it.id);
            const parsed = parseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">BUN</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${formalLineInput({ template: 'bun', itemId, field: 'bun', label: 'BUN', value: v('bun'), unit: 'mg/dL' })}
                                ${formalLineInput({ template: 'bun', itemId, field: 'remarks', label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderCreatinineEntryCard(it, request) {
            const itemId = Number(it.id);
            const parsed = parseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">CREATININE</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${formalLineInput({ template: 'creatinine', itemId, field: 'creatinine', label: 'CREATININE', value: v('creatinine'), unit: 'mg/dL' })}
                                ${formalLineInput({ template: 'creatinine', itemId, field: 'remarks', label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderElectrolytesEntryCard(it, request) {
            const itemId = Number(it.id);
            const parsed = parseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';

            const sodium = v('sodium_na') || v('sodium') || '';
            const potassium = v('potassium_k') || v('potassium') || '';
            const chloride = v('chloride_cl') || v('chloride') || '';

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">ELECTROLYTES (Na/K/Cl)</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${formalLineInput({ template: 'electrolytes', itemId, field: 'sodium', label: 'SODIUM (Na)', value: sodium, unit: 'mmol/L' })}
                                ${formalLineInput({ template: 'electrolytes', itemId, field: 'potassium', label: 'POTASSIUM (K)', value: potassium, unit: 'mmol/L' })}
                                ${formalLineInput({ template: 'electrolytes', itemId, field: 'chloride', label: 'CHLORIDE (Cl)', value: chloride, unit: 'mmol/L' })}
                                ${formalLineInput({ template: 'electrolytes', itemId, field: 'remarks', label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function renderUrinalysisEntryCard(it, request) {
            const itemId = Number(it.id);
            const parsed = parseLabeledResultText(it.result_text || '');
            const v = (k) => escapeHtml(parsed[k] ?? '');

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-center text-base font-extrabold tracking-wide">URINALYSIS</div>

                            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">COLOR</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="color" value="${v('color')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">TRANSPARENCY</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="transparency" value="${v('transparency')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">PROTEIN</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="protein" value="${v('protein')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">GLUCOSE</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="glucose" value="${v('glucose')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">PH</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="ph" value="${v('ph')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">SPECIFIC GRAVITY</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="specific_gravity" value="${v('specific_gravity')}" />
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">WBC</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="wbc" value="${v('wbc')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">RBC</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="rbc" value="${v('rbc')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">CAST</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="cast" value="${v('cast')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">BACTERIA</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="bacteria" value="${v('bacteria')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">EPITHELIAL CELLS</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="epithelial_cells" value="${v('epithelial_cells')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">CRYSTALS</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="crystals" value="${v('crystals')}" />
                                    </div>
                                    <div class="grid grid-cols-3 items-center gap-2">
                                        <div class="text-xs font-semibold text-gray-700">OTHERS</div>
                                        <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="others" value="${v('others')}" />
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div class="grid grid-cols-3 items-center gap-2">
                                    <div class="text-xs font-semibold text-gray-700">PREGNANCY TEST</div>
                                    <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="pregnancy_test" value="${v('pregnancy_test')}" />
                                </div>
                                <div class="grid grid-cols-3 items-center gap-2">
                                    <div class="text-xs font-semibold text-gray-700">SYPHILIS TEST</div>
                                    <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="syphilis_test" value="${v('syphilis_test')}" />
                                </div>
                                <div class="grid grid-cols-3 items-center gap-2">
                                    <div class="text-xs font-semibold text-gray-700">HIV TEST / SD BIOLINE HIV 1/2</div>
                                    <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="hiv_test" value="${v('hiv_test_sd_bioline_hiv_1_2') || v('hiv_test')}" />
                                </div>
                                <div class="grid grid-cols-3 items-center gap-2">
                                    <div class="text-xs font-semibold text-gray-700">HEPATITIS B SCREENING (HBsAg)</div>
                                    <input class="ua-field col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" data-item-id="${itemId}" data-field="hepatitis_b_screening_hbsag" value="${v('hepatitis_b_screening_hbsag')}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function loadPatientLabTest(requestId) {
            currentPatientLabRequestId = requestId;
            const content = document.getElementById('labPatientLabTestContent');
            if (!content) return;

            content.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';

            const res = await fetch(API_BASE_URL + '/lab/get_request.php?id=' + encodeURIComponent(String(requestId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load request.</div>';
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];

            const patientSave = document.getElementById('labPatientSaveBtn');
            if ((r.status || '').toString() !== 'in_progress') {
                if (patientSave) patientSave.classList.add('hidden');
                content.innerHTML = `
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="p-6">
                            <div class="text-sm text-red-600 font-semibold">This request is not yet processed.</div>
                            <div class="text-sm text-gray-600 mt-1">Please go to <strong>Pending Lab Tests</strong> and click <strong>Process</strong> to proceed.</div>
                            <div class="mt-4">
                                <a href="laboratory.php#pending" class="inline-flex items-center px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Go to Pending Lab Tests</a>
                            </div>
                        </div>
                    </div>
                `;
                return;
            }

            if (patientSave) patientSave.classList.remove('hidden');

            const age = calculateAgeFromDob(r.dob);
            const sex = (r.sex ?? '').toString();
            const addr = formatPatientAddress(r);
            const dateIssued = formatDateIssued(new Date());

            content.innerHTML = `
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Released by / MedTech name</div>
                    <div class="p-6">
                        <input id="labReleasedBy" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter MedTech name" value="${escapeHtml((items[0]?.released_by) || '')}" />
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Patient’s Basic Info</div>
                    <div class="p-6">
                        <div class="text-center">
                            <div class="text-sm font-semibold text-gray-900"></div>
                        </div>

                        <div class="mt-5 space-y-4">
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="lg:col-span-2">
                                    <div class="text-xs font-semibold text-gray-800">NAME:</div>
                                    <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.full_name || '')}</div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">AGE:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(age)}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">SEX:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(sex)}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="lg:col-span-2">
                                    <div class="text-xs font-semibold text-gray-800">ADDRESS:</div>
                                    <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(addr)}</div>
                                </div>
                                <div>
                                    <div class="text-xs font-semibold text-gray-800">DATE ISSUED:</div>
                                    <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(dateIssued)}</div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs font-semibold text-gray-800">BLOOD TYPE:</div>
                                    <input id="labHeaderBloodType" type="text" value="${escapeHtml((r.blood_type || '').toString())}" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" />
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">HEMATOCRIT:</div>
                                        <input id="labHeaderHematocrit" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" />
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">HEMOGLOBIN:</div>
                                        <input id="labHeaderHemoglobin" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" />
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div>
                                    <div class="text-xs font-semibold text-gray-800">BLOOD SUGAR:</div>
                                    <div class="mt-1 flex items-end gap-2">
                                        <input id="labHeaderBloodSugar" type="text" class="flex-1 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" />
                                        <div class="text-xs text-gray-700 whitespace-nowrap">mg/dL</div>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">REQUEST NO:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.request_no || '')}</div>
                                    </div>
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">PATIENT ID:</div>
                                        <div class="mt-1 border-b border-gray-400 text-sm font-semibold">${escapeHtml(r.patient_code || '')}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">ER Lab Request Form</div>
                    <div class="p-6 space-y-2">
                        <div><span class="text-xs text-gray-500">Chief Complaint:</span> <span class="text-sm font-semibold">${escapeHtml(r.chief_complaint || '')}</span></div>
                        <div><span class="text-xs text-gray-500">Triage Level:</span> <span class="text-sm font-semibold">${escapeHtml(r.triage_level || '')}</span></div>
                        <div><span class="text-xs text-gray-500">Notes:</span> <span class="text-sm font-semibold">${escapeHtml(r.notes || '')}</span></div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Lab Result Form</div>
                    <div class="p-6 space-y-4">
                        ${items.map(it => {
                            if (isUrinalysisTest(it.test_name || '')) {
                                return renderUrinalysisEntryCard(it, r);
                            }
                            if (isCbcTest(it.test_name || '')) {
                                return renderCbcEntryCard(it, r);
                            }
                            if (isRbsTest(it.test_name || '')) {
                                return renderRbsEntryCard(it, r);
                            }
                            if (isBunTest(it.test_name || '')) {
                                return renderBunEntryCard(it, r);
                            }
                            if (isCreatinineTest(it.test_name || '')) {
                                return renderCreatinineEntryCard(it, r);
                            }
                            if (isElectrolytesTest(it.test_name || '')) {
                                return renderElectrolytesEntryCard(it, r);
                            }
                            return `
                                <div class="border border-gray-200 rounded-lg overflow-hidden">
                                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                                    <div class="p-4">
                                        <textarea class="lab-result-text w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" rows="5" data-item-id="${Number(it.id)}">${escapeHtml(it.result_text || '')}</textarea>
                                    </div>
                                </div>
                            `;
                        }).join('')}
                    </div>
                </div>
            `;

            function syncLabHeaderFromResults() {
                const hb = document.querySelector('.formal-field[data-template="cbc"][data-field="hemoglobin"]')?.value || '';
                const hct = document.querySelector('.formal-field[data-template="cbc"][data-field="hematocrit"]')?.value || '';
                const bs = document.querySelector('.formal-field[data-template="rbs"][data-field="blood_sugar"]')?.value || '';

                const hbEl = document.getElementById('labHeaderHemoglobin');
                const hctEl = document.getElementById('labHeaderHematocrit');
                const bsEl = document.getElementById('labHeaderBloodSugar');

                const markTouched = (el) => {
                    if (!el) return;
                    if (el.dataset && el.dataset.touched === '1') return;
                    el.addEventListener('input', () => {
                        el.dataset.touched = '1';
                    });
                };

                markTouched(hbEl);
                markTouched(hctEl);
                markTouched(bsEl);

                const safeSet = (el, v) => {
                    if (!el) return;
                    const cur = (el.value ?? '').toString();
                    const touched = (el.dataset && el.dataset.touched === '1');
                    if (touched && cur.trim() !== '') return;
                    el.value = (v || '').toString().trim();
                };

                safeSet(hbEl, hb);
                safeSet(hctEl, hct);
                safeSet(bsEl, bs);
            }

            syncLabHeaderFromResults();
            content.querySelectorAll('.formal-field').forEach(el => {
                el.addEventListener('input', syncLabHeaderFromResults);
                el.addEventListener('change', syncLabHeaderFromResults);
            });
        }

        async function savePatientLabTest() {
            if (!currentPatientLabRequestId) {
                alert('Missing request_id');
                return;
            }

            const releasedBy = (document.getElementById('labReleasedBy')?.value || '').toString().trim();
            if (!releasedBy) {
                alert('Released by / MedTech name is required');
                return;
            }

            const resultsByItemId = new Map();

            const root = document.getElementById('labPatientLabTestContent') || document;

            const areas = Array.from(root.querySelectorAll('.lab-result-text'));
            for (const a of areas) {
                const id = Number(a.getAttribute('data-item-id'));
                resultsByItemId.set(id, (a.value || '').toString());
            }

            const uaFields = Array.from(root.querySelectorAll('.ua-field'));
            const uaByItem = new Map();
            for (const el of uaFields) {
                const id = Number(el.getAttribute('data-item-id'));
                const field = (el.getAttribute('data-field') || '').toString();
                if (!id || !field) continue;
                if (!uaByItem.has(id)) uaByItem.set(id, {});
                uaByItem.get(id)[field] = (el.value || '').toString();
            }

            for (const [id, fields] of uaByItem.entries()) {
                resultsByItemId.set(id, formatUrinalysisResultText(fields));
            }

            const formalFields = Array.from(root.querySelectorAll('.formal-field'));
            const formalByItem = new Map();
            for (const el of formalFields) {
                const id = Number(el.getAttribute('data-item-id'));
                const field = (el.getAttribute('data-field') || '').toString();
                const template = (el.getAttribute('data-template') || '').toString();
                if (!id || !field || !template) continue;
                if (!formalByItem.has(id)) formalByItem.set(id, { template, fields: {} });
                const cur = formalByItem.get(id);
                cur.template = template || cur.template;
                cur.fields[field] = (el.value || '').toString();
            }

            for (const [id, payload] of formalByItem.entries()) {
                const t = (payload.template || '').toString();
                if (t === 'cbc') {
                    resultsByItemId.set(id, formatCbcResultText(payload.fields));
                } else if (t === 'rbs') {
                    resultsByItemId.set(id, formatRbsResultText(payload.fields));
                } else if (t === 'bun') {
                    resultsByItemId.set(id, formatBunResultText(payload.fields));
                } else if (t === 'creatinine') {
                    resultsByItemId.set(id, formatCreatinineResultText(payload.fields));
                } else if (t === 'electrolytes') {
                    resultsByItemId.set(id, formatElectrolytesResultText(payload.fields));
                }
            }

            const results = Array.from(resultsByItemId.entries()).map(([id, text]) => ({
                request_item_id: Number(id),
                result_text: (text || '').toString(),
            }));

            const res = await fetch(API_BASE_URL + '/lab/save_results.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ request_id: Number(currentPatientLabRequestId), released_by: releasedBy, results })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? json.error : 'Unable to save results');
                return;
            }

            labSaveRedirectUrl = 'laboratory.php#lab-test-result';
            toggleModal('labSaveSuccessModal');
        }

        function closeLabSaveSuccess() {
            toggleModal('labSaveSuccessModal');
        }

        function confirmLabSaveSuccess() {
            toggleModal('labSaveSuccessModal');
            const url = (labSaveRedirectUrl || 'laboratory.php#lab-test-result').toString();
            window.location.href = url;
        }

        async function loadLabRequests() {
            const tbody = document.getElementById('labRequestsTbody');
            if (!tbody) return;

            const res = await fetch(API_BASE_URL + '/lab/list_requests.php?mode=lab', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            tbody.innerHTML = rows.map(r => {
                const chip = requestStatusChip(r.status);
                const patientName = escapeHtml(r.full_name || '');
                const patientCode = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${patientName}</div>
                            <div class="text-sm text-gray-500">ID: ${patientCode}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${tests}</div>
                            <div class="text-xs text-gray-500">${reqNo}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="viewTestDetails(${Number(r.id)})" class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function viewTestDetails(requestId) {
            const content = document.getElementById('testDetailsContent');
            if (!content) return;

            const res = await fetch(API_BASE_URL + '/lab/get_request.php?id=' + encodeURIComponent(String(requestId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                toggleModal('viewTestModal');
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];

            const chip = requestStatusChip(r.status);

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Patient Information</h4>
                        <p><strong>Name:</strong> ${escapeHtml(r.full_name || '')}</p>
                        <p><strong>Patient ID:</strong> ${escapeHtml(r.patient_code || '')}</p>
                        <p><strong>Request No:</strong> ${escapeHtml(r.request_no || '')}</p>
                    </div>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Request</h4>
                        <p><strong>Source:</strong> ${escapeHtml(r.source_unit || '')}</p>
                        <p><strong>Priority:</strong> ${escapeHtml((r.priority || '').toString().toUpperCase())}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span></p>
                        <p><strong>Submitted By:</strong> ${escapeHtml(r.requested_by || '')}</p>
                        <p><strong>Approved By:</strong> ${escapeHtml(r.approved_by || '')}</p>
                    </div>
                    <div class="col-span-1 md:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Tests</div>
                        <div class="p-4">
                            <ul class="space-y-2">
                                ${items.map(it => `
                                    <li class="flex items-center justify-between">
                                        <div class="text-sm text-gray-800">${escapeHtml(it.test_name || '')}</div>
                                        <div class="text-xs text-gray-500">${escapeHtml(it.specimen || '')}</div>
                                    </li>
                                `).join('')}
                            </ul>
                        </div>
                    </div>
                </div>
            `;

            toggleModal('viewTestModal');
        }

        // --- Dynamic Calendar ---
        function renderCalendar() {
            const calendarContainer = document.getElementById('testScheduleCalendar');
            const monthYearDisplay = document.getElementById('calendarMonthYear');
            
            const date = new Date();
            const year = date.getFullYear();
            const month = date.getMonth();
            const today = date.getDate();

            // Dummy schedule data for demonstration: 'full', 'partial', 'available'
            const scheduleData = {
                3: { status: 'partial', slots: 5 }, 
                5: { status: 'full', slots: 0 }, 
                10: { status: 'available', slots: 10 }, 
                11: { status: 'partial', slots: 3 }, 
                17: { status: 'full', slots: 0 }, 
                22: { status: 'available', slots: 8 }, 
                25: { status: 'partial', slots: 2 }, 
                26: { status: 'available', slots: 10 }
           };

            monthYearDisplay.textContent = `${date.toLocaleString('default', { month: 'long' })} ${year}`;

            const firstDayOfMonth = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            calendarContainer.innerHTML = ''; // Clear existing calendar

            const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
            daysOfWeek.forEach(day => {
                    calendarContainer.innerHTML += `<div class="font-medium text-gray-600">${day}</div>`;
         });

            for (let i = 0; i < firstDayOfMonth; i++) {
                calendarContainer.innerHTML += `<div></div>`;
            }

            for (let day = 1; day <= daysInMonth; day++) {
                let dayClass = 'p-2 rounded-lg cursor-pointer transition-colors duration-200';
                let titleText = 'Schedule available'; // Default tooltip
                const schedule = scheduleData[day];

                if (day === today) {
                    dayClass += ' bg-blue-500 text-white font-bold';
                 titleText = 'Today';
                } else if (schedule) {
                    switch (schedule.status) {
                        case 'full':
                            dayClass += ' bg-red-100 text-red-800 hover:bg-red-200';
                            titleText = 'Schedule is full';
                            break;
                        case 'partial':
                            dayClass += ' bg-yellow-100 text-yellow-800 hover:bg-yellow-200';
                            titleText = `${schedule.slots} slots available`;
                            break;
                        case 'available':
                            dayClass += ' bg-green-100 text-green-800 hover:bg-green-200';
                            titleText = `All ${schedule.slots} slots available`;
                            break;
                        default:
                            dayClass += ' hover:bg-gray-100';
                    }
                } else {
                    dayClass += ' hover:bg-gray-100';
               }
                    calendarContainer.innerHTML += `<div class="${dayClass}" title="${titleText}">${day}</div>`;
         }
        }

        // Form Submissions
        document.getElementById('scheduleTestForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Toastify({
                text: "Test scheduled successfully!",
                duration: 3000,
                gravity: "top",
                position: 'right',
                backgroundColor: '#10B981',
            }).showToast();
            toggleModal('scheduleTestModal');
            this.reset();
        });
    </script>
    <?php include __DIR__ . '/includes/queue-error-correction.php'; ?>
    <script>window.qecStationId = 6; window.qecRefreshQueue = function() { loadLabQueue(); };</script>
    <?php include __DIR__ . '/includes/queue-error-correction-js.php'; ?>
</body>
</html>


