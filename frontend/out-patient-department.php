<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Out-Patient Department - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include __DIR__ . '/includes/websocket-client.php'; ?>
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
                            <a href="out-patient-department.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
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
                            <a href="pharmacy.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-pills w-6 text-center"></i>
                                <span>Pharmacy</span>
                            </a>
                        </li>
                        <li>
                            <a href="laboratory.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
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
                            <a href="cashier.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-cash-register w-6 text-center"></i>
                                <span>Cashier</span>
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
                <h1 id="opdPageTitle" class="text-2xl font-bold text-gray-900">Out-Patient Department</h1>
            </div>

            <section id="opdOverviewSection">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-calendar-check"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Today's Appointments</h2>
                            <p id="opdTodayAppointmentsCount" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-users"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Patients Waiting</h2>
                            <p id="opdWaitingCount" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-check-circle"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Consultations Done</h2>
                            <p id="opdCompletedCount" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-clock"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Avg. Wait Time</h2>
                            <p id="opdAvgWaitTime" class="text-2xl font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column: Appointments Table -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Today's Appointments</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="opdAppointmentsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>

                <!-- Right Column: Queue & Doctor Status -->
                <div class="space-y-6">
                    <!-- Consultation Queue -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <!-- Header with Action Buttons -->
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                            <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                                <i class="fas fa-users mr-2 text-blue-600"></i>
                                OPD Queue
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                <button onclick="callNextPatient()" class="p-4 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                                    <i class="fas fa-bell mr-2"></i> Call Next Patient
                                </button>
                                <button onclick="queueErrorReportOpen()" class="p-4 bg-red-600 text-white rounded-lg text-lg font-semibold hover:bg-red-700 transition-colors flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i> Report Queue Error
                                </button>
                            </div>
                        </div>
                        
                        <!-- Currently Serving Section -->
                        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                            <div class="flex items-center mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                                <h4 class="text-lg font-semibold text-gray-800">Currently Serving</h4>
                            </div>
                            <div id="currentlyServing" class="text-center py-3">
                                <div class="text-gray-500">No patient being served</div>
                            </div>
                            <div id="stationSelection" class="mt-4 hidden flex gap-2 justify-end">
                                <button onclick="callNextAndMarkUnavailable()" class="p-4 bg-orange-600 text-white rounded-lg text-lg font-semibold hover:bg-orange-700 transition-colors flex items-center">
                                    <i class="fas fa-user-slash mr-2"></i> Mark Unavailable
                                </button>
                                <button onclick="openSendPatientModal()" class="p-4 bg-green-600 text-white rounded-lg text-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send to Next Station
                                </button>
                            </div>
                        </div>
                        
                        <!-- Waiting Queue -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                                <i class="fas fa-list-ol mr-2 text-blue-600"></i>
                                Waiting Queue
                            </h4>
                            <div id="opdQueueList" class="space-y-2">
                                <div class="text-center py-8 text-gray-400">
                                    <i class="fas fa-users-slash text-4xl mb-2"></i>
                                    <p>No patients in queue</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Unavailable Patients -->
                        <div class="mb-6">
                            <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                                <i class="fas fa-user-clock mr-2 text-orange-600"></i>
                                Unavailable Patients
                            </h4>
                            <div id="unavailablePatientsList" class="space-y-2">
                                <div class="text-center py-6 text-gray-400">
                                    <i class="fas fa-check-circle text-3xl mb-2"></i>
                                    <p>No unavailable patients</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Display Screen Button -->
                        <div class="pt-4 border-t border-gray-200">
                            <button onclick="openDisplayScreen()" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center">
                                <i class="fas fa-tv mr-2"></i>
                                Open Display Screen
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Send Patient Modal -->
            <div id="sendPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[60]">
                <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
                    <div class="p-8 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                        <h3 class="text-2xl font-bold text-gray-900">
                            Send Patient to Next Station
                        </h3>
                        <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="closeSendPatientModal()">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="p-8 flex-1 overflow-y-auto">
                        <div class="mb-6">
                            <label class="block text-lg font-semibold text-gray-700 mb-4">Select Destination Station:</label>
                            <div id="stationList" class="space-y-4">
                                <!-- Stations will be populated here -->
                            </div>
                        </div>
                    </div>
                    <div class="p-8 bg-gray-50 border-t flex justify-end gap-4 flex-shrink-0">
                        <button type="button" class="px-8 py-4 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold transition-colors" onclick="closeSendPatientModal()">
                            Cancel
                        </button>
                        <button type="button" id="confirmSendBtn" class="px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                            <i class="fas fa-paper-plane mr-3"></i>
                            Send Patient
                        </button>
                    </div>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Patient Volume by Hour</h3>
                    <canvas id="patientVolumeChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Consultation Duration Analysis</h3>
                    <canvas id="consultationDurationChart" class="w-full h-64"></canvas>
                </div>
            </div>
            </section>

            <section id="opdNursingAssessmentSection" class="hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Nursing Assessment</h2>
                            <p class="text-sm text-gray-600 mt-1">Record vitals and submit for doctor review.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="opdAssessAutoFillBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Auto Fill (AI)</button>
                            <button id="opdAssessHistoryBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Assessment History</button>
                        </div>
                    </div>

                    <div id="opdAssessAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                    <div class="mt-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                            <div class="text-sm font-semibold text-gray-800">New Assessment</div>

                            <form id="opdAssessForm" class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Patient</label>
                                    <input id="opdAssessAppointmentSearch" type="text" placeholder="Search patient name / ID" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                                    <input id="opdAssessPatientId" type="hidden" value="">
                                    <input id="opdAssessAppointmentId" type="hidden" value="">
                                    <div id="opdAssessAppointmentResults" class="mt-2 border border-gray-200 rounded-lg max-h-56 overflow-auto hidden"></div>
                                    <div id="opdAssessAppointmentMeta" class="mt-2 text-xs text-gray-600"></div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Nurse Name</label>
                                    <input id="opdAssessNurseName" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Optional">
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">BP Systolic</label>
                                        <input id="opdAssessBpSys" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="mmHg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">BP Diastolic</label>
                                        <input id="opdAssessBpDia" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="mmHg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Heart Rate</label>
                                        <input id="opdAssessHr" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="bpm">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Respiratory Rate</label>
                                        <input id="opdAssessRr" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="/min">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Temperature</label>
                                        <input id="opdAssessTemp" type="number" step="0.1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="°C">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">SpO₂</label>
                                        <input id="opdAssessSpo2" type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="%">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Weight</label>
                                        <input id="opdAssessWeight" type="number" step="0.1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="kg">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Height</label>
                                        <input id="opdAssessHeight" type="number" step="0.1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="cm">
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <div class="text-sm font-semibold text-gray-800">History of Present Illness</div>
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">When did the problem start?</label>
                                            <input id="opdAssessHpiStart" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Duration/Frequency</label>
                                            <input id="opdAssessHpiDuration" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Severity (mild/moderate/severe)</label>
                                            <select id="opdAssessHpiSeverity" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                                <option value="">Select</option>
                                                <option value="mild">mild</option>
                                                <option value="moderate">moderate</option>
                                                <option value="severe">severe</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Associated Symptoms</label>
                                            <input id="opdAssessHpiAssociated" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700">Aggravating/Relieving factors</label>
                                        <textarea id="opdAssessHpiFactors" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <div class="text-sm font-semibold text-gray-800">Past Medical History</div>
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opdAssessPmhDiabetes" type="checkbox" class="h-4 w-4"> Diabetes</label>
                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opdAssessPmhHypertension" type="checkbox" class="h-4 w-4"> Hypertension</label>
                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opdAssessPmhAsthma" type="checkbox" class="h-4 w-4"> Asthma</label>
                                        <label class="flex items-center gap-2 text-sm text-gray-800"><input id="opdAssessPmhHeartDisease" type="checkbox" class="h-4 w-4"> Heart Disease</label>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700">Other</label>
                                        <input id="opdAssessPmhOther" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <label class="block text-sm font-medium text-gray-700">Surgical History (if any)</label>
                                    <textarea id="opdAssessSurgicalHistory" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <label class="block text-sm font-medium text-gray-700">Current Medications</label>
                                    <textarea id="opdAssessCurrentMedications" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <div class="text-sm font-semibold text-gray-800">Allergies</div>
                                    <div class="mt-3">
                                        <input id="opdAssessAllergiesOther" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Enter allergies (leave blank if none)" />
                                    </div>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <label class="block text-sm font-medium text-gray-700">Family History (relevant conditions)</label>
                                    <textarea id="opdAssessFamilyHistory" rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                                </div>

                                <div class="border-t border-gray-100 pt-4">
                                    <div class="text-sm font-semibold text-gray-800">Social History</div>
                                    <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Smoking</div>
                                            <div class="mt-2 flex items-center gap-4">
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="opdAssessSmoking" value="yes" class="h-4 w-4"> Yes</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="opdAssessSmoking" value="no" class="h-4 w-4"> No</label>
                                            </div>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-700">Alcohol</div>
                                            <div class="mt-2 flex items-center gap-4">
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="opdAssessAlcohol" value="yes" class="h-4 w-4"> Yes</label>
                                                <label class="flex items-center gap-2 text-sm text-gray-800"><input type="radio" name="opdAssessAlcohol" value="no" class="h-4 w-4"> No</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <label class="block text-sm font-medium text-gray-700">Occupation</label>
                                        <input id="opdAssessOccupation" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Notes</label>
                                    <textarea id="opdAssessNotes" rows="4" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="Assessment notes..."></textarea>
                                </div>

                                <div class="pt-2">
                                    <button id="opdAssessSaveBtn" type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Assessment</button>
                                    <button id="opdAssessCancelEditBtn" type="button" class="hidden w-full mt-2 px-4 py-3 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Cancel Edit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>

            <section id="opdConsultationNotesSection" class="hidden">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Consultation Notes</h2>
                            <p class="text-sm text-gray-600 mt-1">Document doctor consultation notes linked to an OPD appointment.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="opdConsultAutoFillBtn" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Auto Fill (AI)</button>
                            <button id="opdConsultHistoryBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Notes History</button>
                        </div>
                    </div>

                    <div id="opdConsultAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                    <div class="mt-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                            <div class="text-sm font-semibold text-gray-800">New Note</div>

                            <form id="opdConsultForm" class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Appointment</label>
                                    <input id="opdConsultAppointmentSearch" type="text" placeholder="Search patient name" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                                    <input id="opdConsultAppointmentId" type="hidden" value="">
                                    <div id="opdConsultAppointmentResults" class="mt-2 border border-gray-200 rounded-lg max-h-56 overflow-auto hidden"></div>
                                    <div id="opdConsultAppointmentMeta" class="mt-2 text-xs text-gray-600"></div>
                                </div>

                                <div>
                                    <div class="p-4 border border-gray-200 rounded-lg bg-white">
                                        <div class="text-sm font-semibold text-gray-800">Doctor Consultation Form (Patient-Filled)</div>
                                        <div class="text-[11px] text-gray-500">Patient info is auto-filled</div>

                                        <div class="mt-4">
                                            <div class="text-xs font-semibold text-gray-700">Patient Information</div>
                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Full Name</label>
                                                    <input id="opdConsultPatientName" readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" />
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Date of Birth / Age</label>
                                                    <input id="opdConsultPatientDobAge" readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" />
                                                </div>
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Gender</label>
                                                    <input id="opdConsultPatientGender" readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4">
                                            <div class="text-xs font-semibold text-gray-700">Appointment Details</div>
                                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                <div>
                                                    <label class="block text-[11px] text-gray-600">Date of Visit</label>
                                                    <input id="opdConsultVisitDate" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-6">
                                            <div class="text-xs font-semibold text-gray-700">🩺 Doctor Consultation Note (SOAP Format)</div>

                                            <div class="mt-3">
                                                <div class="text-xs font-semibold text-gray-700">S – Subjective</div>
                                                <div class="mt-2">
                                                    <label class="block text-[11px] text-gray-600">Chief Complaint</label>
                                                    <input id="opdSoapChiefComplaint" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="text-xs font-semibold text-gray-700">O – Objective</div>
                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">BP</label>
                                                        <input id="opdSoapBp" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Pulse</label>
                                                        <input id="opdSoapPulse" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Temp</label>
                                                        <input id="opdSoapTemp" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="block text-[11px] text-gray-600">Physical Examination Findings</label>
                                                    <textarea id="opdSoapExam" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="text-xs font-semibold text-gray-700">A – Assessment</div>
                                                <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Primary Diagnosis</label>
                                                        <input id="opdSoapPrimaryDx" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Differential Diagnosis (if any)</label>
                                                        <input id="opdSoapDifferentialDx" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mt-4">
                                                <div class="text-xs font-semibold text-gray-700">P – Plan</div>
                                                <div class="mt-2">
                                                    <label class="block text-[11px] text-gray-600">Investigations Ordered</label>
                                                    <textarea id="opdSoapInvestigations" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="block text-[11px] text-gray-600">Medications Prescribed</label>
                                                    <textarea id="opdSoapMedications" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                </div>
                                                <div class="mt-3">
                                                    <label class="block text-[11px] text-gray-600">Treatment/Advice</label>
                                                    <textarea id="opdSoapAdvice" rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700"></textarea>
                                                </div>
                                                <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Follow-up</label>
                                                        <input id="opdSoapFollowUp" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                    <div>
                                                        <label class="block text-[11px] text-gray-600">Doctor’s Name & Signature</label>
                                                        <input id="opdSoapDoctorSignature" type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-white text-gray-700" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-2">
                                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Note</button>
                                    <button type="button" id="opdConsultCancelEditBtn" class="hidden w-full mt-2 px-4 py-3 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Cancel Edit</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </section>

            <section id="opdBillingSection" class="hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">OPD Billing</h2>
                            <p class="text-sm text-gray-600 mt-1">Review and create billing items for the OPD visit (consultation + lab + pharmacy).</p>
                        </div>
                    </div>

                    <div id="opdBillingAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-700">Appointment</label>
                        <input id="opdBillingAppointmentSearch" type="text" placeholder="Search patient name" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                        <input id="opdBillingAppointmentId" type="hidden" value="">
                        <div id="opdBillingAppointmentResults" class="mt-2 border border-gray-200 rounded-lg max-h-56 overflow-auto hidden"></div>
                        <div id="opdBillingAppointmentMeta" class="mt-2 text-xs text-gray-600"></div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <div class="bg-white border border-gray-200 rounded-lg p-5">
                            <div class="text-sm font-semibold text-gray-800">Add Billing Item</div>
                            <form id="opdBillingItemForm" class="mt-4 space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Type</label>
                                    <select id="opdBillingItemType" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <option value="misc">Misc</option>
                                        <option value="service">Service</option>
                                        <option value="procedure">Procedure</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Description</label>
                                    <input id="opdBillingDescription" type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="e.g., Dressing change">
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Qty</label>
                                        <input id="opdBillingQty" type="number" min="1" value="1" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Unit Price</label>
                                        <input id="opdBillingUnitPrice" type="number" min="0" step="0.01" value="0" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add Item</button>
                                </div>
                            </form>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-5 min-h-[620px] flex flex-col">
                            <div class="text-sm font-semibold text-gray-800">Summary</div>
                            <div id="opdBillingSummary" class="mt-4 text-sm text-gray-600 flex-1 overflow-auto">Select an appointment to view billing summary.</div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="opdAppointmentsSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Appointments</h3>
                            <p class="text-sm text-gray-600 mt-1">View scheduled OPD appointments.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <input id="opdAppointmentsSearch" type="text" placeholder="Search patient name or ID..." class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <button id="refreshOpdAppointments" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="opdAppointmentsTbodyAppointments" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="opdAppointmentRequestsSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Appointment Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Doctors approve and schedule OPD appointment requests.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <button onclick="toggleModal('scheduleAppointmentModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                                <i class="fas fa-calendar-plus mr-2"></i>
                                Send Request
                            </button>
                            <button id="refreshOpdAppointmentRequests" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved By</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Requested</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="opdAppointmentRequestsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="opdNewLabRequestSection" class="hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">OPD Lab Request</h2>
                            <p class="text-sm text-gray-600 mt-1">Create an OPD lab request for a selected patient.</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                            <i class="fas fa-flask text-blue-600 text-xl"></i>
                        </div>
                    </div>

                    <div id="opdLabAlert" class="hidden mt-4 px-4 py-3 rounded-lg text-sm"></div>

                    <form id="opdLabForm" class="mt-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Patient</label>
                            <input id="opdPatientSearch" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search patient name / ID" autocomplete="off">
                            <input id="opdPatientId" type="hidden">
                            <div id="opdPatientResults" class="mt-2 border border-gray-200 rounded-lg overflow-hidden hidden"></div>
                            <div id="opdPatientSelected" class="mt-2 text-xs text-gray-600"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chief Complaint</label>
                            <input id="opdChiefComplaint" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., Cough, Follow-up">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Priority</label>
                            <select id="opdPriority" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="routine">Routine</option>
                                <option value="urgent">Urgent</option>
                                <option value="stat">STAT</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Requested Tests</label>
                            <input id="opdTestSearch" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Type test name / code" autocomplete="off">
                            <div id="opdTestList" class="mt-2 grid grid-cols-1 gap-2"></div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Submitted by</label>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <select id="opdRequesterRole" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="np_pa">Doctor/NP/PA (direct to lab)</option>
                                    <option value="nurse">Nurse (requires doctor approval)</option>
                                </select>
                                <input id="opdRequestedBy" type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Name">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Notes</label>
                            <textarea id="opdNotes" rows="3" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Optional"></textarea>
                        </div>

                        <div class="pt-2">
                            <button id="opdSubmitLabBtn" type="submit" class="w-full px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Submit Lab Request</button>
                        </div>
                    </form>
                </div>
            </section>

            <section id="opdXraySection" class="hidden">
                <?php
                $includeXrayResultsReleaseModal = true;
                include __DIR__ . '/includes/xray-results-release.php';
                ?>
            </section>

            <section id="opdLabRequestsSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">OPD Lab Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Track OPD lab requests and status.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <button id="btnOpdNewLabRequest" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">New Lab Request</button>
                            <button id="refreshOpdLabRequests" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="opdLabRequestsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="opdLabResultsSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Lab Test Result</h3>
                            <p class="text-sm text-gray-600 mt-1">Completed lab requests.</p>
                        </div>
                        <button id="refreshOpdLabResults" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
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
                            <tbody id="opdLabResultsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>

                    <div id="opdLabResultsResultViewPanel" class="hidden border-t border-gray-100">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Result View</h3>
                                <p class="text-sm text-gray-600 mt-1">Read-only lab test result.</p>
                            </div>
                            <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="closeOpdLabResultsResultView()">Close</button>
                        </div>
                        <div id="opdLabResultsResultViewContent" class="p-6"></div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <div id="opdLabDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Lab Request Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('opdLabDetailsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="opdLabDetailsContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('opdLabDetailsModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- View Appointment Details Modal -->
    <div id="viewAppointmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Appointment Details</h3>
                    <button onclick="toggleModal('viewAppointmentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="appointmentDetailsContent" class="p-5">
                <!-- Content will be populated by JS -->
            </div>
            <div class="p-6 bg-gray-50 border-t flex justify-end space-x-4">
                <button type="button" onclick="toggleModal('viewAppointmentModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                    Close
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-print mr-2"></i>
                    Print Summary
                </button>
            </div>
        </div>
    </div>

    <!-- Schedule Appointment Modal -->
    <div id="scheduleAppointmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[70]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative z-[70]">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Send Appointment Request</h3>
                    <button onclick="toggleModal('scheduleAppointmentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="scheduleAppointmentForm" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Patient</label>
                        <input id="opdApptPatientSearch" type="text" placeholder="Search patient by name or ID" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off">
                        <input id="opdApptPatientId" type="hidden">
                        <input id="opdApptNursingAssessmentId" type="hidden">
                        <div id="opdApptPatientResults" class="mt-2 border border-gray-200 rounded-lg overflow-hidden hidden"></div>
                        <div id="opdApptPatientSelected" class="mt-2 text-xs text-gray-600"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Doctor</label>
                        <select id="opdApptDoctor" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="">Loading doctors...</option>
                        </select>
                        <div id="opdApptDoctorSelected" class="mt-2 text-xs text-gray-600"></div>
                        <div id="opdApptDoctorAvailability" class="mt-2 text-xs"></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="opdApptNotes" placeholder="Reason for visit..." rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal('scheduleAppointmentModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Send Request
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="opdConsultHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-5xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Notes History</h3>
                    <div id="opdConsultHistoryModalMeta" class="mt-1 text-xs text-gray-500"></div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="opdConsultHistoryModalRefreshBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                    <button id="opdConsultHistoryModalEditBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 hidden">Edit</button>
                    <button id="opdConsultHistoryModalSubmitPharmacyBtn" type="button" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 hidden">Submit to Pharmacy</button>
                    <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('opdConsultHistoryModal')">Close</button>
                </div>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <div class="mb-4">
                    <input id="opdConsultHistorySearch" type="text" placeholder="Search patient name / ID..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off" />
                </div>
                <div id="opdConsultHistoryModalList" class="mb-6"></div>
                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                    <div class="text-sm font-semibold text-gray-800">View Note</div>
                    <div id="opdConsultHistoryModalView" class="mt-2 text-sm text-gray-700">Select a note to view.</div>
                </div>
            </div>
        </div>
    </div>

    <div id="opdConsultSubmitSuccessModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-[70]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Submitted Successfully</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleOpdConsultSubmitSuccessModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div id="opdConsultSubmitSuccessText" class="text-sm text-gray-700">Submitted successfully.</div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex items-center justify-end gap-2">
                <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800" onclick="toggleOpdConsultSubmitSuccessModal(false)">OK</button>
            </div>
        </div>
    </div>

    <div id="opdConsultSavedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[80]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="text-sm font-semibold text-gray-900">Consultation Note</div>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleOpdConsultSavedModal(false)">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-check text-emerald-600"></i>
                    </div>
                    <div>
                        <div class="text-base font-semibold text-gray-900">Saved successfully</div>
                        <div id="opdConsultSavedText" class="mt-1 text-sm text-gray-600">The consultation note has been saved successfully.</div>
                    </div>
                </div>
            </div>
            <div class="p-5 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700" onclick="toggleOpdConsultSavedModal(false)">OK</button>
            </div>
        </div>
    </div>

    <div id="opdAssessDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[60]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Nursing Assessment</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('opdAssessDetailsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="opdAssessDetailsContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end gap-3">
                <button type="button" id="opdAssessSendRequestBtn" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700" onclick="toggleModal('scheduleAppointmentModal')">Send Request</button>
                <button type="button" id="opdAssessPrintBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Print</button>
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('opdAssessDetailsModal')">Close</button>
            </div>
        </div>
    </div>

    <div id="opdAssessSavedModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[80]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-sm mx-4">
            <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                <div class="text-sm font-semibold text-gray-900">Nursing Assessment</div>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('opdAssessSavedModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 rounded-full bg-emerald-50 flex items-center justify-center">
                        <i class="fas fa-check text-emerald-600"></i>
                    </div>
                    <div>
                        <div class="text-base font-semibold text-gray-900">Assessment saved</div>
                        <div class="mt-1 text-sm text-gray-600">The nursing assessment has been recorded successfully.</div>
                    </div>
                </div>
            </div>
            <div class="p-5 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700" onclick="toggleModal('opdAssessSavedModal')">OK</button>
            </div>
        </div>
    </div>

    <div id="opdAssessHistoryModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-6xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Assessment History</h3>
                    <div class="text-sm text-gray-600 mt-1">View nursing assessments and search by patient.</div>
                </div>
                <div class="flex items-center gap-2">
                    <button id="opdAssessHistoryRefreshBtn" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Refresh</button>
                    <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('opdAssessHistoryModal')">Close</button>
                </div>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <div id="opdAssessHistoryListContainer">
                    <div class="mb-4">
                        <input id="opdAssessHistorySearch" type="text" placeholder="Search patient name or ID..." class="w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" autocomplete="off" />
                    </div>
                    <div id="opdAssessHistory" class="text-sm text-gray-600">Select a patient to view assessments.</div>
                </div>
                <div id="opdAssessDetailContainer" class="hidden">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="showOpdAssessHistoryList()">← Back</button>
                        <div class="flex items-center gap-3">
                            <button type="button" id="opdAssessDockEditBtn" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>Edit</button>
                            <button type="button" id="opdAssessDockSendRequestBtn" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed" onclick="openScheduleAppointmentFromSelectedAssessment()" disabled>Send Request</button>
                            <button type="button" id="opdAssessDockPrintBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Print</button>
                        </div>
                    </div>
                    <div id="opdAssessDetailContent" class="border border-gray-200 rounded-lg bg-white p-4 max-h-[65vh] overflow-y-auto text-sm text-gray-700"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Patient Volume Chart
            const patientVolumeCtx = document.getElementById('patientVolumeChart').getContext('2d');
            new Chart(patientVolumeCtx, {
                type: 'bar',
                data: {
                    labels: ['9-10am', '10-11am', '11-12pm', '1-2pm', '2-3pm', '3-4pm'],
                    datasets: [{
                        label: 'Patients',
                        data: [12, 19, 15, 22, 18, 14],
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

            // Consultation Duration Chart
            const consultationDurationCtx = document.getElementById('consultationDurationChart').getContext('2d');
            new Chart(consultationDurationCtx, {
                type: 'line',
                data: {
                    labels: ['Dr. Carter', 'Dr. Jacobs', 'Dr. Lee', 'Dr. White', 'Dr. Brown'],
                    datasets: [{
                        label: 'Avg. Duration (min)',
                        data: [15, 18, 12, 20, 16],
                        borderColor: '#8B5CF6',
                        backgroundColor: '#C4B5FD',
                        tension: 0.4,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Minutes' } } }
                }
            });
        });

        let opdAppointments = [];
        let opdAppointmentsSearchQuery = '';

        function opdApptStatusChip(status) {
            const s = (status ?? '').toString();
            if (s === 'requested') return { cls: 'bg-indigo-100 text-indigo-800', label: 'Requested' };
            if (s === 'scheduled') return { cls: 'bg-purple-100 text-purple-800', label: 'Scheduled' };
            if (s === 'in_consultation') return { cls: 'bg-blue-100 text-blue-800', label: 'In-Consultation' };
            if (s === 'waiting') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Waiting' };
            if (s === 'completed') return { cls: 'bg-green-100 text-green-800', label: 'Completed' };
            if (s === 'checked_in') return { cls: 'bg-gray-200 text-gray-800', label: 'Checked-In' };
            if (s === 'cancelled') return { cls: 'bg-red-100 text-red-800', label: 'Cancelled' };
            if (s === 'no_show') return { cls: 'bg-slate-100 text-slate-800', label: 'No Show' };
            if (s === 'rejected') return { cls: 'bg-red-100 text-red-800', label: 'Rejected' };
            return { cls: 'bg-gray-100 text-gray-800', label: s || '-' };
        }

        function formatTime12h(dateTimeStr) {
            const s = (dateTimeStr ?? '').toString();
            const d = new Date(s.replace(' ', 'T'));
            if (Number.isNaN(d.getTime())) return '';
            return d.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });
        }

        function renderOpdAppointments() {
            const tbodyOverview = document.getElementById('opdAppointmentsTbody');
            const tbodyAppointments = document.getElementById('opdAppointmentsTbodyAppointments');
            if (!tbodyOverview && !tbodyAppointments) return;

            const rowsAll = Array.isArray(opdAppointments) ? opdAppointments : [];

            const buildHtml = (rows) => {
                if (!Array.isArray(rows) || rows.length === 0) {
                    return '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No appointments.</td></tr>';
                }

                return rows.map(a => {
                    const chip = opdApptStatusChip(a.status);
                    const id = Number(a.id);
                    const fullName = escapeHtml(a.full_name || '');
                    const code = escapeHtml(a.patient_code || ('P-' + String(a.patient_id || '')));
                    const doctor = escapeHtml(a.doctor_name || '');
                    const time = escapeHtml(formatTime12h(a.appointment_at) || '');
                    const canStart = (a.status === 'scheduled' || a.status === 'waiting' || a.status === 'checked_in');
                    const canComplete = (a.status === 'in_consultation');

                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">${fullName}</div>
                                <div class="text-sm text-gray-500">ID: ${code}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">${doctor}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">${time}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button onclick="viewAppointmentDetails(${id})" class="p-1 text-blue-600 hover:text-blue-800" title="View"><i class="fas fa-eye"></i></button>
                                ${canStart ? `<button onclick="updateOpdAppointmentStatus(${id}, 'in_consultation')" class="p-1 text-green-600 hover:text-green-800" title="Start"><i class="fas fa-play"></i></button>` : ''}
                                ${canComplete ? `<button onclick="updateOpdAppointmentStatus(${id}, 'completed')" class="p-1 text-emerald-600 hover:text-emerald-800" title="Complete"><i class="fas fa-check"></i></button>` : ''}
                            </td>
                        </tr>
                    `;
                }).join('');
            };

            let rowsForAppointments = rowsAll;
            const q = (opdAppointmentsSearchQuery || '').toString().trim().toLowerCase();
            const apptSection = document.getElementById('opdAppointmentsSection');
            const inAppointmentsView = !!(apptSection && !apptSection.classList.contains('hidden'));
            if (inAppointmentsView && q !== '') {
                rowsForAppointments = rowsAll.filter(a => {
                    const name = (a && a.full_name ? String(a.full_name) : '').toLowerCase();
                    const code = (a && a.patient_code ? String(a.patient_code) : '').toLowerCase();
                    return name.includes(q) || code.includes(q);
                });
            }

            if (tbodyOverview) tbodyOverview.innerHTML = buildHtml(rowsAll);
            if (tbodyAppointments) tbodyAppointments.innerHTML = buildHtml(rowsForAppointments);
        }

        function updateOpdStats() {
            const total = Array.isArray(opdAppointments) ? opdAppointments.length : 0;
            const waiting = (opdAppointments || []).filter(a => a.status === 'waiting').length;
            const completed = (opdAppointments || []).filter(a => a.status === 'completed').length;
            const totalEl = document.getElementById('opdTodayAppointmentsCount');
            const waitingEl = document.getElementById('opdWaitingCount');
            const completedEl = document.getElementById('opdCompletedCount');
            if (totalEl) totalEl.textContent = String(total);
            if (waitingEl) waitingEl.textContent = String(waiting);
            if (completedEl) completedEl.textContent = String(completed);
        }

        function ymdToday() {
            const d = new Date();
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const dd = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${dd}`;
        }

        async function loadOpdAppointments(dateStr) {
            const tbodies = [];
            const tbodyOverview = document.getElementById('opdAppointmentsTbody');
            const tbodyAppointments = document.getElementById('opdAppointmentsTbodyAppointments');
            if (tbodyOverview) tbodies.push(tbodyOverview);
            if (tbodyAppointments) tbodies.push(tbodyAppointments);
            tbodies.forEach(tbody => {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            });

            const d = (dateStr || '').toString().trim() || ymdToday();
            const res = await fetch(API_BASE_URL + '/opd/list.php?date=' + encodeURIComponent(d), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                opdAppointments = [];
                tbodies.forEach(tbody => {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-600">Unable to load appointments.</td></tr>';
                });
                updateOpdStats();
                return;
            }

            opdAppointments = Array.isArray(json.appointments) ? json.appointments : [];
            updateOpdStats();
            renderOpdAppointments();
        }

        async function loadOpdAppointmentsAll() {
            const tbodies = [];
            const tbodyOverview = document.getElementById('opdAppointmentsTbody');
            const tbodyAppointments = document.getElementById('opdAppointmentsTbodyAppointments');
            if (tbodyOverview) tbodies.push(tbodyOverview);
            if (tbodyAppointments) tbodies.push(tbodyAppointments);
            tbodies.forEach(tbody => {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            });

            const res = await fetch(API_BASE_URL + '/opd/list.php?all=1', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                opdAppointments = [];
                tbodies.forEach(tbody => {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-600">Unable to load appointments.</td></tr>';
                });
                updateOpdStats();
                return;
            }

            opdAppointments = Array.isArray(json.appointments) ? json.appointments : [];
            updateOpdStats();
            renderOpdAppointments();
        }

        async function loadOpdAppointmentsForToday() {
            await loadOpdAppointments(ymdToday());
        }

        let opdAppointmentRequests = [];

        function renderOpdAppointmentRequests() {
            const tbody = document.getElementById('opdAppointmentRequestsTbody');
            if (!tbody) return;

            const rows = Array.isArray(opdAppointmentRequests) ? opdAppointmentRequests : [];
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">No appointment requests.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(a => {
                const id = Number(a.id);
                const fullName = escapeHtml(a.full_name || '');
                const code = escapeHtml(a.patient_code || ('P-' + String(a.patient_id || '')));
                const doctor = escapeHtml(a.doctor_name || '');
                const approvedBy = escapeHtml(a.approved_by_name || '-');
                const requestedAt = a.created_at ? escapeHtml(new Date(a.created_at).toLocaleString()) : '';
                const notes = escapeHtml(a.notes || '-');
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${fullName}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">${doctor}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${approvedBy}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${requestedAt}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">${notes}</td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="rejectOpdRequest(${id})" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function loadOpdLabResults() {
            const tbody = document.getElementById('opdLabResultsTbody');
            if (!tbody) return;

            const panel = document.getElementById('opdLabResultsResultViewPanel');
            const content = document.getElementById('opdLabResultsResultViewContent');
            if (panel) panel.classList.add('hidden');
            if (content) content.innerHTML = '';

            let resultRows = [];
            try {
                const res = await fetch(API_BASE_URL + '/lab/list_requests.php?status=completed', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (res.ok && json && json.ok) {
                    resultRows = Array.isArray(json.requests) ? json.requests : [];
                }
            } catch (e) {
            }

            if (resultRows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-10 text-center text-sm text-gray-500">No lab results found.</td></tr>';
                return;
            }

            tbody.innerHTML = resultRows.map(r => {
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const name = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                const releasedBy = escapeHtml(r.released_by || '');
                const releasedAt = r.released_at ? escapeHtml(new Date(r.released_at).toLocaleString()) : '';
                const cashier = String(r.cashier_status || '').toLowerCase();
                let statusLabel = 'Completed';
                let statusCls = 'bg-green-100 text-green-800';
                if (cashier === 'submitted') {
                    statusLabel = 'Submitted to Cashier';
                    statusCls = 'bg-green-100 text-green-800';
                } else if (cashier === 'pending_fee') {
                    statusLabel = 'Pending Fee';
                    statusCls = 'bg-yellow-100 text-yellow-800';
                }

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${name}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.chief_complaint || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${releasedBy || '-'}</div>
                            <div class="text-xs text-gray-500">${releasedAt || ''}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${statusCls}">${escapeHtml(statusLabel)}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="viewOpdLabResult(${Number(r.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function opdFormatPatientAddress(r) {
            const parts = [r.street_address, r.barangay, r.city, r.province, r.zip_code]
                .map(x => (x ?? '').toString().trim())
                .filter(Boolean);
            return parts.join(', ');
        }

        function opdCalculateAgeFromDob(dob) {
            const s = (dob ?? '').toString().trim();
            if (!s || s === '0000-00-00') return '';
            const d = new Date(s);
            if (Number.isNaN(d.getTime())) return '';
            const today = new Date();
            let age = today.getFullYear() - d.getFullYear();
            const m = today.getMonth() - d.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < d.getDate())) age--;
            return String(Math.max(0, age));
        }

        function opdFormatDateIssued(d) {
            try {
                const dt = (d instanceof Date) ? d : new Date(d);
                if (Number.isNaN(dt.getTime())) return '';
                return dt.toLocaleDateString([], { year: 'numeric', month: '2-digit', day: '2-digit' });
            } catch (e) {
                return '';
            }
        }

        function collectOpdAssessAiFields() {
            const fields = [];
            const pushField = (id, label, type = 'text') => {
                const el = document.getElementById(id);
                if (!el) return;
                fields.push({ id, label, type });
            };
            const pushRadio = (name, label) => {
                fields.push({ id: name, label, type: 'radio' });
            };
            const pushCheckbox = (id, label) => {
                fields.push({ id, label, type: 'checkbox' });
            };

            pushField('opdAssessNurseName', 'Nurse Name');
            pushField('opdAssessBpSys', 'BP Systolic', 'number');
            pushField('opdAssessBpDia', 'BP Diastolic', 'number');
            pushField('opdAssessHr', 'Heart Rate', 'number');
            pushField('opdAssessRr', 'Respiratory Rate', 'number');
            pushField('opdAssessTemp', 'Temperature', 'number');
            pushField('opdAssessSpo2', 'SpO₂', 'number');
            pushField('opdAssessWeight', 'Weight', 'number');
            pushField('opdAssessHeight', 'Height', 'number');

            pushField('opdAssessHpiStart', 'HPI - Start');
            pushField('opdAssessHpiDuration', 'HPI - Duration/Frequency');
            pushField('opdAssessHpiSeverity', 'HPI - Severity', 'select');
            pushField('opdAssessHpiAssociated', 'HPI - Associated Symptoms');
            pushField('opdAssessHpiFactors', 'HPI - Aggravating/Relieving factors', 'textarea');

            pushCheckbox('opdAssessPmhDiabetes', 'PMH - Diabetes');
            pushCheckbox('opdAssessPmhHypertension', 'PMH - Hypertension');
            pushCheckbox('opdAssessPmhAsthma', 'PMH - Asthma');
            pushCheckbox('opdAssessPmhHeartDisease', 'PMH - Heart Disease');
            pushField('opdAssessPmhOther', 'PMH - Other');

            pushField('opdAssessSurgicalHistory', 'Surgical History', 'textarea');
            pushField('opdAssessCurrentMedications', 'Current Medications', 'textarea');
            pushField('opdAssessAllergiesOther', 'Allergies');
            pushField('opdAssessFamilyHistory', 'Family History', 'textarea');

            pushRadio('opdAssessSmoking', 'Social - Smoking (yes/no)');
            pushRadio('opdAssessAlcohol', 'Social - Alcohol (yes/no)');
            pushField('opdAssessOccupation', 'Social - Occupation');

            pushField('opdAssessNotes', 'Notes', 'textarea');
            return fields;
        }

        function applyAiValuesToOpdAssess(fields, values) {
            const byIdx = new Map();
            (Array.isArray(values) ? values : []).forEach(v => {
                if (v && typeof v === 'object' && v.index !== undefined) {
                    byIdx.set(Number(v.index), v);
                }
            });

            const asYes = (x) => {
                const s = (x ?? '').toString().trim().toLowerCase();
                if (!s) return null;
                if (['yes', 'y', 'true', '1', 'checked', 'present'].includes(s)) return true;
                if (['no', 'n', 'false', '0', 'none', 'absent'].includes(s)) return false;
                return null;
            };

            fields.forEach((f, idx) => {
                const vObj = byIdx.get(idx);
                if (!vObj) return;
                const value = (vObj.value ?? '').toString();

                if (f.type === 'checkbox') {
                    const el = document.getElementById(f.id);
                    if (!el) return;
                    const yn = asYes(value);
                    if (yn === null) return;
                    el.checked = yn;
                    return;
                }

                if (f.type === 'radio') {
                    const yn = asYes(value);
                    if (yn === null) return;
                    const name = f.id;
                    const targetVal = yn ? 'yes' : 'no';
                    const el = document.querySelector(`input[name="${CSS.escape(name)}"][value="${CSS.escape(targetVal)}"]`);
                    if (el) el.checked = true;
                    return;
                }

                const el = document.getElementById(f.id);
                if (!el) return;
                el.value = value;
            });

            const allergiesOther = document.getElementById('opdAssessAllergiesOther');
            if (allergiesOther) {
                allergiesOther.value = (allergiesOther.value || '').toString();
            }
        }

        async function loadOpdAssessmentsAll(q) {
            const history = document.getElementById('opdAssessHistory');
            if (!history) return;

            history.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';

            try {
                const qs = (q || '').toString().trim();
                const url = API_BASE_URL + '/opd_assessment/list.php?all=1' + (qs ? ('&q=' + encodeURIComponent(qs)) : '');
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) throw new Error('Failed');

                const rows = Array.isArray(json.assessments) ? json.assessments : [];
                if (!rows.length) {
                    history.innerHTML = '<div class="text-sm text-gray-500">No assessments found.</div>';
                    return;
                }

                history.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nurse</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${rows.map(r => {
                                    const when = r.created_at ? escapeHtml(new Date(r.created_at).toLocaleString()) : '';
                                    const nurse = escapeHtml(r.nurse_name || '-');
                                    const patient = escapeHtml(r.full_name || '');
                                    const code = escapeHtml(r.patient_code || '');
                                    const apptId = Number(r.appointment_id || 0);
                                    const id = Number(r.id || 0);
                                    return `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-700">${when}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">${patient}<div class="text-xs text-gray-500">${code}</div></td>
                                            <td class="px-4 py-2 text-sm text-gray-700">${nurse}</td>
                                            <td class="px-4 py-2 text-right">
                                                <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openOpdAssessDetailsFromHistory(${id}, ${apptId})">View</button>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (e) {
                history.innerHTML = '<div class="text-sm text-red-600">Unable to load assessments.</div>';
            }
        }

        function opdNormalizeTestName(name) {
            return (name ?? '').toString().trim().toLowerCase();
        }

        function opdIsUrinalysisTest(name) {
            const n = opdNormalizeTestName(name);
            return n === 'urinalysis' || n.includes('urinalysis');
        }

        function opdIsCbcTest(name) {
            const n = opdNormalizeTestName(name);
            return n === 'complete blood count (cbc)' || n === 'cbc' || n.includes('complete blood count') || n.includes('cbc');
        }

        function opdIsRbsTest(name) {
            const n = opdNormalizeTestName(name);
            return n === 'random blood sugar (rbs)' || n === 'rbs' || n.includes('random blood sugar') || n.includes('rbs');
        }

        function opdIsBunTest(name) {
            const n = opdNormalizeTestName(name);
            return n === 'bun' || n.includes('bun');
        }

        function opdIsCreatinineTest(name) {
            const n = opdNormalizeTestName(name);
            return n === 'creatinine' || n.includes('creatinine');
        }

        function opdIsElectrolytesTest(name) {
            const n = opdNormalizeTestName(name);
            return n === 'electrolytes (na/k/cl)' || n === 'electrolytes' || n.includes('electrolytes') || n.includes('na/k/cl');
        }

        function opdParseLabeledResultText(resultText) {
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

        function opdFormalLineInput(opts) {
            const value = escapeHtml(opts.value || '');
            const unit = escapeHtml(opts.unit || '');
            const label = escapeHtml(opts.label || '');
            return `
                <div class="grid grid-cols-12 items-center gap-2">
                    <div class="text-xs font-semibold text-gray-800 col-span-4">${label}</div>
                    <div class="col-span-7">
                        <input class="w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1" value="${value}" readonly />
                    </div>
                    <div class="col-span-1 text-right">
                        ${unit ? `<div class="text-xs text-gray-600 whitespace-nowrap">${unit}</div>` : ''}
                    </div>
                </div>
            `;
        }

        function opdRenderCbcEntryCard(it) {
            const parsed = opdParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">COMPLETE BLOOD COUNT (CBC)</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div class="space-y-4">
                                    ${opdFormalLineInput({ label: 'HEMOGLOBIN', value: v('hemoglobin'), unit: 'g/dL' })}
                                    ${opdFormalLineInput({ label: 'HEMATOCRIT', value: v('hematocrit'), unit: '%' })}
                                    ${opdFormalLineInput({ label: 'WBC', value: v('wbc'), unit: '' })}
                                </div>
                                <div class="space-y-4">
                                    ${opdFormalLineInput({ label: 'RBC', value: v('rbc'), unit: '' })}
                                    ${opdFormalLineInput({ label: 'PLATELET', value: v('platelet'), unit: '' })}
                                    ${opdFormalLineInput({ label: 'OTHERS', value: v('others'), unit: '' })}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function opdRenderRbsEntryCard(it) {
            const parsed = opdParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">BLOOD SUGAR</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${opdFormalLineInput({ label: 'RESULT', value: v('blood_sugar'), unit: 'mg/dL' })}
                                ${opdFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function opdRenderBunEntryCard(it) {
            const parsed = opdParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">BUN</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${opdFormalLineInput({ label: 'BUN', value: v('bun'), unit: 'mg/dL' })}
                                ${opdFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function opdRenderCreatinineEntryCard(it) {
            const parsed = opdParseLabeledResultText(it.result_text || '');
            const v = (k) => parsed[k] ?? '';
            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            <div class="text-center text-base font-extrabold tracking-wide">CREATININE</div>
                            <div class="mt-5 grid grid-cols-1 md:grid-cols-2 gap-6">
                                ${opdFormalLineInput({ label: 'CREATININE', value: v('creatinine'), unit: 'mg/dL' })}
                                ${opdFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function opdRenderElectrolytesEntryCard(it) {
            const parsed = opdParseLabeledResultText(it.result_text || '');
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
                                ${opdFormalLineInput({ label: 'SODIUM (Na)', value: sodium, unit: 'mmol/L' })}
                                ${opdFormalLineInput({ label: 'POTASSIUM (K)', value: potassium, unit: 'mmol/L' })}
                                ${opdFormalLineInput({ label: 'CHLORIDE (Cl)', value: chloride, unit: 'mmol/L' })}
                                ${opdFormalLineInput({ label: 'REMARKS', value: v('remarks'), unit: '' })}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function opdRenderUrinalysisEntryCard(it) {
            const parsed = opdParseLabeledResultText(it.result_text || '');
            const v = (k) => escapeHtml(parsed[k] ?? '');
            const block = (label, field) => `
                <div class="grid grid-cols-3 items-center gap-2">
                    <div class="text-xs font-semibold text-gray-700">${escapeHtml(label)}</div>
                    <input class="col-span-2 w-full px-3 py-2 border border-gray-200 rounded-lg" value="${v(field)}" readonly />
                </div>
            `;

            return `
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                    <div class="p-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="text-center text-base font-extrabold tracking-wide">URINALYSIS</div>

                            <div class="mt-4 grid grid-cols-1 lg:grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    ${block('COLOR', 'color')}
                                    ${block('TRANSPARENCY', 'transparency')}
                                    ${block('PROTEIN', 'protein')}
                                    ${block('GLUCOSE', 'glucose')}
                                    ${block('PH', 'ph')}
                                    ${block('SPECIFIC GRAVITY', 'specific_gravity')}
                                </div>

                                <div class="space-y-3">
                                    ${block('WBC', 'wbc')}
                                    ${block('RBC', 'rbc')}
                                    ${block('CAST', 'cast')}
                                    ${block('BACTERIA', 'bacteria')}
                                    ${block('EPITHELIAL CELLS', 'epithelial_cells')}
                                    ${block('CRYSTALS', 'crystals')}
                                    ${block('OTHERS', 'others')}
                                </div>
                            </div>

                            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-3">
                                ${block('PREGNANCY TEST', 'pregnancy_test')}
                                ${block('SYPHILIS TEST', 'syphilis_test')}
                                ${block('HIV TEST / SD BIOLINE HIV 1/2', 'hiv_test_sd_bioline_hiv_1_2')}
                                ${block('HEPATITIS B SCREENING (HBsAg)', 'hepatitis_b_screening_hbsag')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        function opdRenderInvoiceHtml(inv) {
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

        async function loadOpdLabResultInvoice(requestId) {
            const container = document.getElementById('opdLabResultInvoiceContainer');
            if (!container) return;

            container.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';
            const url = API_BASE_URL + '/cashier/get_invoice_by_source.php?source_module=lab_request&source_id=' + encodeURIComponent(String(requestId));
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                container.innerHTML = '<div class="text-sm text-red-600">Unable to load invoice.</div>';
                return;
            }

            container.innerHTML = opdRenderInvoiceHtml(json.invoice || null);
        }

        async function viewOpdLabResult(requestId) {
            const panel = document.getElementById('opdLabResultsResultViewPanel');
            const content = document.getElementById('opdLabResultsResultViewContent');
            if (!panel || !content) return;

            panel.classList.remove('hidden');
            content.innerHTML = '<div class="text-sm text-gray-600">Loading...</div>';

            const res = await fetch(API_BASE_URL + '/lab/get_request.php?id=' + encodeURIComponent(String(requestId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load result.</div>';
                try { panel.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) {}
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];
            const age = opdCalculateAgeFromDob(r.dob);
            const sex = (r.sex ?? '').toString();
            const addr = opdFormatPatientAddress(r);
            const dateIssued = opdFormatDateIssued(new Date());
            const releasedByValue = (items[0]?.released_by || '').toString();

            content.innerHTML = `
                <div class="space-y-4" data-opd-readonly-view="1">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-6 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Released by / MedTech name</div>
                        <div class="p-6">
                            <input type="text" class="w-full px-4 py-2 border border-gray-200 rounded-lg" value="${escapeHtml(releasedByValue)}" readonly />
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
                                        <input id="opdLabViewHeaderBloodType" type="text" value="${escapeHtml((r.blood_type || '').toString())}" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" readonly />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">HEMATOCRIT:</div>
                                            <input id="opdLabViewHeaderHematocrit" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                        </div>
                                        <div>
                                            <div class="text-xs font-semibold text-gray-800">HEMOGLOBIN:</div>
                                            <input id="opdLabViewHeaderHemoglobin" type="text" class="mt-1 w-full bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
                                        </div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                    <div>
                                        <div class="text-xs font-semibold text-gray-800">BLOOD SUGAR:</div>
                                        <div class="mt-1 flex items-end gap-2">
                                            <input id="opdLabViewHeaderBloodSugar" type="text" class="flex-1 bg-transparent border-0 border-b border-gray-400 focus:ring-0 focus:outline-none px-1 py-1 text-sm font-semibold" value="" readonly />
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
                                if (opdIsUrinalysisTest(it.test_name || '')) {
                                    card = opdRenderUrinalysisEntryCard(it, r);
                                } else if (opdIsCbcTest(it.test_name || '')) {
                                    card = opdRenderCbcEntryCard(it, r);
                                } else if (opdIsRbsTest(it.test_name || '')) {
                                    card = opdRenderRbsEntryCard(it, r);
                                } else if (opdIsBunTest(it.test_name || '')) {
                                    card = opdRenderBunEntryCard(it, r);
                                } else if (opdIsCreatinineTest(it.test_name || '')) {
                                    card = opdRenderCreatinineEntryCard(it, r);
                                } else if (opdIsElectrolytesTest(it.test_name || '')) {
                                    card = opdRenderElectrolytesEntryCard(it, r);
                                } else {
                                    card = `
                                        <div class="border border-gray-200 rounded-lg overflow-hidden">
                                            <div class="px-4 py-2 bg-gray-50 border-b text-sm font-semibold">${escapeHtml(it.test_name || '')}</div>
                                            <div class="p-4">
                                                <textarea class="w-full px-3 py-2 border border-gray-200 rounded-lg" rows="5" readonly>${escapeHtml(it.result_text || '')}</textarea>
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
                        <div class="p-6" id="opdLabResultInvoiceContainer">
                            <div class="text-sm text-gray-600">Loading...</div>
                        </div>
                    </div>
                </div>
            `;

            const cbcItem = items.find(x => opdIsCbcTest(x.test_name || ''));
            const rbsItem = items.find(x => opdIsRbsTest(x.test_name || ''));
            const cbcParsed = cbcItem ? opdParseLabeledResultText(cbcItem.result_text || '') : {};
            const rbsParsed = rbsItem ? opdParseLabeledResultText(rbsItem.result_text || '') : {};
            const hb = (cbcParsed.hemoglobin ?? '').toString();
            const hct = (cbcParsed.hematocrit ?? '').toString();
            const bs = (rbsParsed.blood_sugar ?? '').toString();
            const hbEl = document.getElementById('opdLabViewHeaderHemoglobin');
            const hctEl = document.getElementById('opdLabViewHeaderHematocrit');
            const bsEl = document.getElementById('opdLabViewHeaderBloodSugar');
            if (hbEl) hbEl.value = hb.trim();
            if (hctEl) hctEl.value = hct.trim();
            if (bsEl) bsEl.value = bs.trim();

            await loadOpdLabResultInvoice(Number(requestId));

            try { panel.scrollIntoView({ behavior: 'smooth', block: 'start' }); } catch (e) {}
        }

        function closeOpdLabResultsResultView() {
            const panel = document.getElementById('opdLabResultsResultViewPanel');
            const content = document.getElementById('opdLabResultsResultViewContent');
            if (content) content.innerHTML = '';
            if (panel) panel.classList.add('hidden');
        }

        async function loadOpdAppointmentRequests() {
            const tbody = document.getElementById('opdAppointmentRequestsTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';

            const res = await fetch(API_BASE_URL + '/opd/list.php?status=requested', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                opdAppointmentRequests = [];
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-sm text-red-600">Unable to load requests.</td></tr>';
                return;
            }
            opdAppointmentRequests = Array.isArray(json.appointments) ? json.appointments : [];
            renderOpdAppointmentRequests();
        }

        function showOpdAssessAlert(type, text) {
            const el = document.getElementById('opdAssessAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
        }

        function showOpdConsultAlert(type, text) {
            const el = document.getElementById('opdConsultAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
        }

        function toggleOpdConsultSubmitSuccessModal(show, text) {
            const el = document.getElementById('opdConsultSubmitSuccessModal');
            if (!el) return;
            const msg = document.getElementById('opdConsultSubmitSuccessText');
            if (msg && typeof text === 'string' && text.trim() !== '') {
                msg.textContent = text;
            } else if (msg && show) {
                msg.textContent = 'Submitted successfully.';
            }
            if (show) {
                el.style.zIndex = '9999';
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function toggleOpdConsultSavedModal(show, text) {
            const el = document.getElementById('opdConsultSavedModal');
            if (!el) return;
            const msg = document.getElementById('opdConsultSavedText');
            if (msg && typeof text === 'string' && text.trim() !== '') {
                msg.textContent = text;
            } else if (msg && show) {
                msg.textContent = 'The consultation note has been saved successfully.';
            }
            if (show) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }

        function showOpdBillingAlert(type, text) {
            const el = document.getElementById('opdBillingAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
        }

        let opdAssessAppointments = [];
        let opdAssessAppointmentQuery = '';
        let opdAssessSelectedAppointment = null;
        let opdAssessEditingId = null;

        let opdConsultAppointments = [];
        let opdConsultAppointmentQuery = '';
        let opdConsultEditingNoteId = null;

        let opdBillingAppointments = [];
        let opdBillingAppointmentQuery = '';

        async function renderOpdAssessPatientOptions() {
            const resultsEl = document.getElementById('opdAssessAppointmentResults');
            const inputEl = document.getElementById('opdAssessAppointmentSearch');
            if (!resultsEl || !inputEl) return;

            const q = (opdAssessAppointmentQuery || '').toString().trim();
            if (!q) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">Searching...</div>';

            const res = await fetch(API_BASE_URL + '/patients/list.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (!rows.length) {
                resultsEl.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>';
                return;
            }

            resultsEl.innerHTML = rows.map(p => {
                const name = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                const sex = escapeHtml(p.sex || '');
                const dob = escapeHtml(p.dob || '');
                return `
                    <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50" data-id="${Number(p.id)}" data-name="${name}" data-code="${code}" data-sex="${sex}" data-dob="${dob}">
                        <div class="text-sm font-medium text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${code}${sex ? ' • ' + sex : ''}${dob ? ' • DOB: ' + dob : ''}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', async () => {
                    const pid = Number(btn.getAttribute('data-id') || 0);
                    const name = (btn.getAttribute('data-name') || '').toString();
                    const code = (btn.getAttribute('data-code') || '').toString();
                    const metaEl = document.getElementById('opdAssessAppointmentMeta');
                    const pidEl = document.getElementById('opdAssessPatientId');
                    const apptIdEl = document.getElementById('opdAssessAppointmentId');

                    if (pidEl) pidEl.value = String(pid);
                    if (inputEl) inputEl.value = name;
                    if (metaEl) metaEl.textContent = 'Selected: ' + name + ' (' + code + ')';
                    resultsEl.classList.add('hidden');
                    resultsEl.innerHTML = '';
                    opdAssessAppointmentQuery = '';

                    if (apptIdEl) apptIdEl.value = '';
                    opdAssessSelectedAppointment = null;
                    opdAssessAppointments = [];

                    if (metaEl) metaEl.textContent = 'Selected: ' + name + ' (' + code + ') • Resolving latest appointment...';
                    try {
                        const aRes = await fetch(API_BASE_URL + '/opd/list.php?patient_id=' + encodeURIComponent(String(pid)) + '&latest=1', { headers: { 'Accept': 'application/json' } });
                        const aJson = await aRes.json().catch(() => null);
                        const appt = (aRes.ok && aJson && aJson.ok && Array.isArray(aJson.appointments) && aJson.appointments[0]) ? aJson.appointments[0] : null;
                        if (!appt) {
                            if (metaEl) metaEl.textContent = 'Selected: ' + name + ' (' + code + ') • No OPD appointment found.';
                            const alertEl = document.getElementById('opdAssessAlert');
                            if (alertEl) alertEl.classList.add('hidden');
                            await handleOpdAssessAppointmentChange();
                            return;
                        }

                        opdAssessSelectedAppointment = appt;
                        opdAssessAppointments = [appt];
                        if (apptIdEl) apptIdEl.value = String(Number(appt.id || 0));

                        const status = (appt.status || '').toString();
                        const when = appt.appointment_at ? formatTime12h(appt.appointment_at) : '';
                        if (metaEl) metaEl.textContent = 'Selected: ' + name + ' (' + code + ')' + (status ? (' • Status: ' + status) : '') + (when ? (' • Time: ' + when) : '');
                        await handleOpdAssessAppointmentChange();
                    } catch (e) {
                        if (metaEl) metaEl.textContent = 'Selected: ' + name + ' (' + code + ') • Unable to resolve appointment.';
                        showOpdAssessAlert('err', 'Unable to resolve appointment for selected patient');
                    }
                });
            });
        }

        // NOTE: Nursing assessment selection is patient-based; we resolve the latest appointment per patient on selection.

        function renderOpdConsultAppointmentOptions() {
            const results = document.getElementById('opdConsultAppointmentResults');
            const idEl = document.getElementById('opdConsultAppointmentId');
            const inputEl = document.getElementById('opdConsultAppointmentSearch');
            if (!results || !idEl || !inputEl) return;

            const q = (opdConsultAppointmentQuery || '').toString().trim().toLowerCase();
            const prev = (idEl.value || '').toString();
            const prevId = (prev && /^\d+$/.test(prev)) ? Number(prev) : null;

            const all = Array.isArray(opdConsultAppointments) ? opdConsultAppointments : [];
            const selected = (prevId ? all.find(a => Number(a.id) === prevId) : null) || null;

            const toOptionLabel = (a) => {
                const t = (a && a.appointment_at) ? String(formatTime12h(a.appointment_at) || '') : '';
                return (a.full_name || '') + ' (' + (a.patient_code || '') + ')' + (t ? (' - ' + t) : '');
            };

            const matches = (a) => {
                if (!q) return true;
                const name = (a && a.full_name) ? String(a.full_name).toLowerCase() : '';
                const code = (a && a.patient_code) ? String(a.patient_code).toLowerCase() : '';
                return name.includes(q) || code.includes(q);
            };

            const filtered = all.filter(a => {
                const s = (a.status || '').toString();
                if (s === 'rejected') return false;
                return matches(a);
            });

            results.innerHTML = '';
            if (!q) {
                results.classList.add('hidden');
                return;
            }

            if (!filtered.length) {
                results.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">No matching appointments.</div>';
                results.classList.remove('hidden');
                return;
            }

            filtered.slice(0, 50).forEach(a => {
                const id = Number(a && a.id);
                if (!Number.isFinite(id) || id <= 0) return;

                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'w-full text-left px-4 py-2 text-sm hover:bg-gray-50 focus:bg-gray-50';
                if (selected && Number(selected.id) === id) {
                    row.className += ' bg-blue-50';
                }
                row.textContent = toOptionLabel(a);
                row.addEventListener('click', () => {
                    idEl.value = String(id);
                    inputEl.value = (a.full_name || '').toString();
                    opdConsultAppointmentQuery = '';
                    results.classList.add('hidden');
                    handleOpdConsultAppointmentChange();
                });
                results.appendChild(row);
            });

            results.classList.remove('hidden');
        }

        async function loadOpdConsultAppointments() {
            const idEl = document.getElementById('opdConsultAppointmentId');
            const meta = document.getElementById('opdConsultAppointmentMeta');
            if (!idEl) return;

            if (meta) meta.textContent = '';

            const d = ymdToday();
            try {
                const [todayRes, inConsultationRes, completedRes] = await Promise.all([
                    fetch(API_BASE_URL + '/opd/list.php?date=' + encodeURIComponent(d), { headers: { 'Accept': 'application/json' } }),
                    fetch(API_BASE_URL + '/opd/list.php?all=1&status=in_consultation', { headers: { 'Accept': 'application/json' } }),
                    fetch(API_BASE_URL + '/opd/list.php?all=1&status=completed', { headers: { 'Accept': 'application/json' } }),
                ]);

                const todayJson = await todayRes.json().catch(() => null);
                const inConsultationJson = await inConsultationRes.json().catch(() => null);
                const completedJson = await completedRes.json().catch(() => null);

                const todayRows = (todayRes.ok && todayJson && todayJson.ok && Array.isArray(todayJson.appointments)) ? todayJson.appointments : [];
                const inConsultationRows = (inConsultationRes.ok && inConsultationJson && inConsultationJson.ok && Array.isArray(inConsultationJson.appointments)) ? inConsultationJson.appointments : [];
                const completedRows = (completedRes.ok && completedJson && completedJson.ok && Array.isArray(completedJson.appointments)) ? completedJson.appointments : [];

                const byId = new Map();
                [...todayRows, ...inConsultationRows, ...completedRows].forEach(a => {
                    const id = Number(a && a.id);
                    if (!Number.isFinite(id) || id <= 0) return;
                    if (!byId.has(id)) byId.set(id, a);
                });

                opdConsultAppointments = Array.from(byId.values()).filter(a => (a && String(a.status || '') !== 'rejected'));
                opdConsultAppointments.sort((a, b) => {
                    const at = (a && a.appointment_at) ? new Date(String(a.appointment_at).replace(' ', 'T')).getTime() : 0;
                    const bt = (b && b.appointment_at) ? new Date(String(b.appointment_at).replace(' ', 'T')).getTime() : 0;
                    if (at !== bt) return at - bt;
                    return Number(a && a.id) - Number(b && b.id);
                });

                renderOpdConsultAppointmentOptions();
                handleOpdConsultAppointmentChange();
            } catch (e) {
                opdConsultAppointments = [];
            }
        }

        function getSelectedConsultAppointment() {
            const idEl = document.getElementById('opdConsultAppointmentId');
            if (!idEl) return null;
            const v = (idEl.value || '').toString().trim();
            if (!v || !/^\d+$/.test(v)) return null;
            const id = Number(v);
            return (opdConsultAppointments || []).find(a => Number(a.id) === id) || null;
        }

        async function handleOpdConsultAppointmentChange() {
            const meta = document.getElementById('opdConsultAppointmentMeta');
            const appt = getSelectedConsultAppointment();
            if (!appt) {
                if (meta) meta.textContent = '';
                const nameEl = document.getElementById('opdConsultPatientName');
                const dobAgeEl = document.getElementById('opdConsultPatientDobAge');
                const genderEl = document.getElementById('opdConsultPatientGender');
                const visitEl = document.getElementById('opdConsultVisitDate');
                if (nameEl) nameEl.value = '';
                if (dobAgeEl) dobAgeEl.value = '';
                if (genderEl) genderEl.value = '';
                if (visitEl) visitEl.value = '';
                return;
            }

            if (meta) {
                const status = (appt.status || '').toString();
                const when = appt.appointment_at ? formatTime12h(appt.appointment_at) : '';
                meta.textContent = 'Doctor: ' + (appt.doctor_name || '') + (status ? (' • Status: ' + status) : '') + (when ? (' • Time: ' + when) : '');
            }

            const visitEl = document.getElementById('opdConsultVisitDate');
            if (visitEl) visitEl.value = '';
            const p = await loadOpdConsultPatientDetails(appt.patient_id);
            setOpdConsultPatientInfo(appt, p);
        }

        let opdConsultNotesCache = [];
        let opdConsultHistorySelectedKey = '';

        function setOpdConsultHistoryPharmacySubmitVisible(visible) {
            const pharmacyBtn = document.getElementById('opdConsultHistoryModalSubmitPharmacyBtn');
            const editBtn = document.getElementById('opdConsultHistoryModalEditBtn');
            
            if (pharmacyBtn) {
                if (visible) {
                    pharmacyBtn.classList.remove('hidden');
                } else {
                    pharmacyBtn.classList.add('hidden');
                }
            }
            
            if (editBtn) {
                if (visible) {
                    editBtn.classList.remove('hidden');
                } else {
                    editBtn.classList.add('hidden');
                }
            }
        }

        function getOpdRadioValue(name) {
            const el = document.querySelector(`input[name="${name}"]:checked`);
            return el ? (el.value || '').toString() : '';
        }

        function buildOpdConsultNoteText() {
            const v = (id) => (document.getElementById(id)?.value || '').toString().trim();

            const lines = [];

            lines.push('Patient Name: ' + v('opdConsultPatientName'));
            lines.push('Date: ' + v('opdConsultVisitDate'));
            lines.push('Age/Gender: ' + v('opdConsultPatientDobAge') + ' / ' + v('opdConsultPatientGender'));
            lines.push('');

            lines.push('🩺 Doctor Consultation Note (SOAP Format)');
            lines.push('');
            lines.push('S – Subjective');
            lines.push('Chief Complaint: ' + v('opdSoapChiefComplaint'));
            lines.push('');
            lines.push('O – Objective');
            lines.push('Vital Signs: BP: ' + v('opdSoapBp') + '  Pulse: ' + v('opdSoapPulse') + '  Temp: ' + v('opdSoapTemp'));
            lines.push('Physical Examination Findings:');
            lines.push(v('opdSoapExam'));
            lines.push('');
            lines.push('A – Assessment');
            lines.push('Primary Diagnosis: ' + v('opdSoapPrimaryDx'));
            lines.push('Differential Diagnosis (if any): ' + v('opdSoapDifferentialDx'));
            lines.push('');
            lines.push('P – Plan');
            lines.push('Investigations Ordered:');
            lines.push(v('opdSoapInvestigations'));
            lines.push('Medications Prescribed:');
            lines.push(v('opdSoapMedications'));
            lines.push('Treatment/Advice:');
            lines.push(v('opdSoapAdvice'));
            lines.push('Follow-up: ' + v('opdSoapFollowUp'));
            lines.push('Doctor’s Name & Signature: ' + v('opdSoapDoctorSignature'));

            return lines.join('\n');
        }

        function setOpdConsultEditingMode(noteId) {
            const btn = document.querySelector('#opdConsultForm button[type="submit"]');
            const cancelBtn = document.getElementById('opdConsultCancelEditBtn');
            
            if (!noteId) {
                opdConsultEditingNoteId = null;
                if (btn) btn.textContent = 'Save Note';
                if (cancelBtn) cancelBtn.classList.add('hidden');
                return;
            }
            
            opdConsultEditingNoteId = Number(noteId);
            if (btn) btn.textContent = 'Update Note';
            if (cancelBtn) cancelBtn.classList.remove('hidden');
        }

        function cancelOpdConsultEdit() {
            setOpdConsultEditingMode(null);
            clearOpdConsultForm();
            showOpdConsultAlert('ok', 'Edit cancelled');
        }

        function clearOpdConsultForm() {
            const fields = [
                'opdConsultPatientName', 'opdConsultVisitDate', 'opdConsultPatientDobAge',
                'opdSoapChiefComplaint', 'opdSoapBp', 'opdSoapPulse', 'opdSoapTemp',
                'opdSoapExam', 'opdSoapPrimaryDx', 'opdSoapDifferentialDx',
                'opdSoapInvestigations', 'opdSoapMedications', 'opdSoapAdvice',
                'opdSoapFollowUp', 'opdSoapDoctorSignature'
            ];
            
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            const selected = document.getElementById('opdConsultPatientSelected');
            if (selected) selected.textContent = '';
            
            const patientId = document.getElementById('opdConsultPatientId');
            if (patientId) patientId.value = '';
            
            const search = document.getElementById('opdConsultPatientSearch');
            if (search) search.value = '';
        }

        function applyParsedConsultNoteToOpdForm(parsed) {
            if (!parsed) return;
            const setVal = (id, val) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = (val ?? '').toString();
            };

            setVal('opdConsultPatientName', parsed.patientName || '');
            setVal('opdConsultVisitDate', parsed.visitDate || '');
            setVal('opdConsultPatientDobAge', parsed.dobAgeGender || '');

            setVal('opdSoapChiefComplaint', parsed.soapChiefComplaint || '');
            setVal('opdSoapBp', parsed.soapBp || '');
            setVal('opdSoapPulse', parsed.soapPulse || '');
            setVal('opdSoapTemp', parsed.soapTemp || '');
            setVal('opdSoapExam', parsed.soapExam || '');
            setVal('opdSoapPrimaryDx', parsed.soapPrimaryDx || '');
            setVal('opdSoapDifferentialDx', parsed.soapDifferentialDx || '');
            setVal('opdSoapInvestigations', parsed.soapInvestigations || '');
            setVal('opdSoapMedications', parsed.soapMedications || '');
            setVal('opdSoapAdvice', parsed.soapAdvice || '');
            setVal('opdSoapFollowUp', parsed.soapFollowUp || '');
            setVal('opdSoapDoctorSignature', parsed.soapDoctorSignature || '');
        }

        function opdConsultHasAnyInput() {
            const v = (id) => (document.getElementById(id)?.value || '').toString().trim();

            const ids = [
                'opdSoapChiefComplaint','opdSoapBp','opdSoapPulse','opdSoapTemp','opdSoapExam','opdSoapPrimaryDx','opdSoapDifferentialDx',
                'opdSoapInvestigations','opdSoapMedications','opdSoapAdvice','opdSoapFollowUp','opdSoapDoctorSignature'
            ];
            if (ids.some(id => v(id) !== '')) return true;

            return false;
        }

        function collectOpdConsultAiFields() {
            const fields = [];
            const pushInput = (id, label, type = 'text') => {
                const el = document.getElementById(id);
                if (!el) return;
                fields.push({ kind: 'input', name: id, type, label, current_value: (el.value ?? '').toString() });
            };
            const pushSelect = (id, label) => {
                const el = document.getElementById(id);
                if (!el) return;
                const options = Array.from(el.options || []).map(o => ({ value: o.value, label: o.textContent || '' })).filter(o => o.value !== '');
                fields.push({ kind: 'select', name: id, label, options, current_value: (el.value ?? '').toString() });
            };
            const pushCheckbox = (id, label) => {
                const el = document.getElementById(id);
                if (!el) return;
                fields.push({ kind: 'checkbox', name: id, label, current_checked: !!el.checked });
            };
            const pushRadio = (name, label) => {
                const radios = Array.from(document.querySelectorAll(`input[name="${name}"]`));
                if (!radios.length) return;
                const options = radios.map(r => ({ value: r.value || '', label: (r.parentElement && r.parentElement.textContent) ? r.parentElement.textContent.trim() : '' })).filter(o => o.value !== '');
                const selected = radios.find(r => r.checked) || null;
                fields.push({ kind: 'radio', name, label, options, current_value: selected ? (selected.value || '').toString() : '' });
            };

            pushInput('opdHpiStart', 'When did the problem start?');
            pushInput('opdHpiDuration', 'Duration/Frequency');
            pushSelect('opdHpiSeverity', 'Severity');
            pushInput('opdHpiAssociated', 'Associated Symptoms');
            pushInput('opdHpiFactors', 'Aggravating/Relieving factors', 'text');

            pushInput('opdSoapChiefComplaint', 'SOAP Chief Complaint');
            pushInput('opdSoapBp', 'SOAP BP');
            pushInput('opdSoapPulse', 'SOAP Pulse');
            pushInput('opdSoapTemp', 'SOAP Temp');
            pushInput('opdSoapExam', 'SOAP Physical Examination Findings', 'text');
            pushInput('opdSoapPrimaryDx', 'SOAP Primary Diagnosis');
            pushInput('opdSoapDifferentialDx', 'SOAP Differential Diagnosis');
            pushInput('opdSoapInvestigations', 'SOAP Investigations Ordered', 'text');
            pushInput('opdSoapMedications', 'SOAP Medications Prescribed', 'text');
            pushInput('opdSoapAdvice', 'SOAP Treatment/Advice', 'text');
            pushInput('opdSoapFollowUp', 'SOAP Follow-up');
            pushInput('opdSoapDoctorSignature', 'Doctor Name & Signature');

            return fields;
        }

        function applyAiValuesToOpdConsult(fields, values) {
            const byIdx = new Map();
            (Array.isArray(values) ? values : []).forEach(v => {
                if (v && typeof v === 'object' && v.index !== undefined) {
                    const idx = Number(v.index);
                    if (Number.isFinite(idx)) byIdx.set(idx, v);
                }
            });

            const toBool = (v) => {
                if (typeof v === 'boolean') return v;
                if (typeof v === 'number') return v !== 0;
                const s = (v ?? '').toString().trim().toLowerCase();
                if (s === '1' || s === 'true' || s === 'yes' || s === 'y' || s === 'checked') return true;
                if (s === '0' || s === 'false' || s === 'no' || s === 'n' || s === '') return false;
                return true;
            };

            const norm = (s) => (s ?? '').toString().trim().toLowerCase();

            fields.forEach((f, idx) => {
                const item = byIdx.get(idx);
                if (!item) return;
                if (f.kind === 'select') {
                    const el = document.getElementById(f.name);
                    if (!el) return;
                    const wanted = (item.value ?? '').toString();
                    const ok = Array.from(el.options || []).some(o => o.value === wanted);
                    if (ok) el.value = wanted;
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    return;
                }
                if (f.kind === 'checkbox') {
                    const el = document.getElementById(f.name);
                    if (!el) return;
                    const raw = (item.checked !== undefined) ? item.checked : ((item.value !== undefined) ? item.value : item.current_checked);
                    el.checked = toBool(raw);
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                    return;
                }
                if (f.kind === 'radio') {
                    const radios = Array.from(document.querySelectorAll(`input[name="${f.name}"]`));
                    if (!radios.length) return;
                    const wanted = norm(item.value);
                    const selected = radios.find(r => norm(r.value) === wanted) || null;
                    if (selected) {
                        selected.checked = true;
                        selected.dispatchEvent(new Event('change', { bubbles: true }));
                    }
                    return;
                }
                if (f.kind === 'input') {
                    const el = document.getElementById(f.name);
                    if (!el) return;
                    el.value = (item.value ?? '').toString();
                    el.dispatchEvent(new Event('input', { bubbles: true }));
                    el.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        }

        const opdAssessAutoFillBtn = document.getElementById('opdAssessAutoFillBtn');
        if (opdAssessAutoFillBtn) {
            opdAssessAutoFillBtn.addEventListener('click', async () => {
                const appt = getSelectedAssessmentAppointment();
                if (!appt) {
                    showOpdAssessAlert('err', 'Select a patient');
                    return;
                }

                opdAssessAutoFillBtn.disabled = true;
                try {
                    const fields = collectOpdAssessAiFields();
                    const seed = String(appt.patient_id || appt.id || '') + '|' + String(appt.id || '');
                    const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'nursing_assessment_opd', seed, fields }),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'AI fill failed');
                    }
                    applyAiValuesToOpdAssess(fields, json.values || []);
                    showOpdAssessAlert('ok', 'Auto fill complete');
                } catch (e) {
                    showOpdAssessAlert('err', e && e.message ? e.message : 'AI fill failed');
                } finally {
                    opdAssessAutoFillBtn.disabled = false;
                }
            });
        }

        function renderOpdConsultHistoryModal(notes) {
            const viewEl = document.getElementById('opdConsultHistoryModalView');
            const listEl = document.getElementById('opdConsultHistoryModalList');
            if (!viewEl) return;
            const rows = Array.isArray(notes) ? notes : [];

            if (rows.length === 0) {
                viewEl.textContent = 'No notes yet.';
                if (listEl) listEl.innerHTML = '<div class="text-sm text-gray-500">No notes found.</div>';
                setOpdConsultHistoryPharmacySubmitVisible(false);
                return;
            }

            if (listEl) {
                listEl.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Doctor</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${rows.map(r => {
                                    const id = Number(r.id || 0);
                                    const when = r.created_at ? escapeHtml(new Date(r.created_at).toLocaleString()) : '';
                                    const patient = escapeHtml(r.full_name || '');
                                    const code = escapeHtml(r.patient_code || '');
                                    const doctor = escapeHtml(r.doctor_name || '-');
                                    return `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-700">${when}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">${patient}<div class="text-xs text-gray-500">${code}</div></td>
                                            <td class="px-4 py-2 text-sm text-gray-700">${doctor}</td>
                                            <td class="px-4 py-2 text-right">
                                                <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="opdConsultHistorySelectAndView(${id})">View</button>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            }

            const selected = rows.find(x => String(x.id || '') === String(opdConsultHistorySelectedKey || '')) || null;
            if (selected) {
                renderSelectedOpdConsultHistoryNote();
                setOpdConsultHistoryPharmacySubmitVisible(true);
            } else {
                viewEl.textContent = 'Select a note to view.';
                setOpdConsultHistoryPharmacySubmitVisible(false);
            }
        }

        function opdConsultHistorySelectAndView(noteId) {
            opdConsultHistorySelectedKey = String(Number(noteId || 0) || '');
            renderSelectedOpdConsultHistoryNote();
            setOpdConsultHistoryPharmacySubmitVisible(true);
        }

        function renderSelectedOpdConsultHistoryNote() {
            const viewEl = document.getElementById('opdConsultHistoryModalView');
            if (!viewEl) return;

            const rows = Array.isArray(opdConsultNotesCache) ? opdConsultNotesCache : [];
            const selected = rows.find(x => String(x.id || '') === String(opdConsultHistorySelectedKey || '')) || null;
            const noteText = selected ? (selected.note_text || '').toString() : '';
            const noteWhen = (selected && selected.created_at) ? new Date(selected.created_at).toLocaleString() : '';
            const noteDoctor = (selected && selected.doctor_name) ? String(selected.doctor_name) : '';
            const parsed = parseConsultNoteText(noteText);
            if (parsed) {
                viewEl.innerHTML = renderConsultNoteFormReadOnly({ ...parsed, noteWhen, noteDoctor });
            } else {
                viewEl.innerHTML = '<pre class="whitespace-pre-wrap">' + escapeHtml(noteText) + '</pre>';
            }
        }

        function getSelectedOpdConsultHistoryRow() {
            const rows = Array.isArray(opdConsultNotesCache) ? opdConsultNotesCache : [];
            return rows.find(x => String(x.id || '') === String(opdConsultHistorySelectedKey || '')) || null;
        }

        async function submitSelectedOpdConsultHistoryToPharmacy() {
            const row = getSelectedOpdConsultHistoryRow();
            const noteId = row ? Number(row.id || 0) : 0;
            if (!Number.isFinite(noteId) || noteId <= 0) {
                const metaEl = document.getElementById('opdConsultHistoryModalMeta');
                if (metaEl) metaEl.textContent = 'Select a note first.';
                return;
            }

            const btn = document.getElementById('opdConsultHistoryModalSubmitPharmacyBtn');
            if (btn) btn.disabled = true;
            try {
                const res = await fetch(API_BASE_URL + '/pharmacy/submit_consult_note.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ source: 'OPD', note_id: noteId }),
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    throw new Error((json && json.error) ? json.error : 'Failed to submit to pharmacy');
                }

                toggleOpdConsultSubmitSuccessModal(true, 'Submitted to pharmacy.');
            } catch (e) {
                const metaEl = document.getElementById('opdConsultHistoryModalMeta');
                if (metaEl) metaEl.textContent = (e && e.message) ? String(e.message) : 'Failed to submit to pharmacy';
            } finally {
                if (btn) btn.disabled = false;
            }
        }

        async function editSelectedOpdConsultHistoryNote() {
            const row = getSelectedOpdConsultHistoryRow();
            const noteId = row ? Number(row.id || 0) : 0;
            if (!Number.isFinite(noteId) || noteId <= 0) {
                const metaEl = document.getElementById('opdConsultHistoryModalMeta');
                if (metaEl) metaEl.textContent = 'Select a note first.';
                return;
            }

            const btn = document.getElementById('opdConsultHistoryModalEditBtn');
            if (btn) btn.disabled = true;

            try {
                const rows = Array.isArray(opdConsultNotesCache) ? opdConsultNotesCache : [];
                const note = rows.find(r => Number(r.id) === Number(noteId)) || null;
                if (!note) {
                    showOpdConsultAlert('err', 'Note not found');
                    return;
                }

                const pidEl = document.getElementById('opdConsultPatientId');
                if (pidEl) pidEl.value = String(note.patient_id || '');

                const input = document.getElementById('opdConsultPatientSearch');
                if (input) input.value = (note.patient_code || '').toString() || String(note.patient_id || '');

                const selected = document.getElementById('opdConsultPatientSelected');
                if (selected) {
                    const nm = (note.full_name || '').toString();
                    const code = (note.patient_code || '').toString();
                    selected.textContent = nm ? (nm + (code ? (' (' + code + ')') : '')) : '';
                }

                setOpdConsultEditingMode(Number(noteId));
                const noteText = (note.note_text || '').toString();
                const parsed = parseConsultNoteText(noteText);
                if (parsed) {
                    applyParsedConsultNoteToOpdForm(parsed);
                } else {
                    showOpdConsultAlert('err', 'This note format cannot be edited');
                    return;
                }

                toggleModal('opdConsultHistoryModal');
                showOpdConsultAlert('ok', 'Note loaded for editing');
            } catch (e) {
                showOpdConsultAlert('err', e && e.message ? e.message : 'Failed to load note for editing');
            } finally {
                if (btn) btn.disabled = false;
            }
        }

        function parseConsultNoteText(noteText) {
            const text = (noteText ?? '').toString();
            if (!text.trim()) return null;

            const lines = text.split(/\r?\n/);
            const t = (s) => (s ?? '').toString().trim();

            const out = {
                patientName: '',
                visitDate: '',
                dobAgeGender: '',
                hpiStart: '',
                hpiDuration: '',
                hpiSeverity: '',
                hpiFactors: '',
                hpiAssociated: '',
                pmhList: '',
                pmhOther: '',
                surgicalHistory: '',
                currentMedications: '',
                allergiesNone: false,
                allergiesOther: '',
                familyHistory: '',
                socialSmoking: '',
                socialAlcohol: '',
                socialOccupation: '',
                additionalNotes: '',
                patientSignature: '',
                patientSignatureDate: '',
                soapChiefComplaint: '',
                soapBp: '',
                soapPulse: '',
                soapTemp: '',
                soapExam: '',
                soapPrimaryDx: '',
                soapDifferentialDx: '',
                soapInvestigations: '',
                soapMedications: '',
                soapAdvice: '',
                soapFollowUp: '',
                soapDoctorSignature: '',
            };

            const takeUntil = (startIdx, stopFn) => {
                const buf = [];
                let i = startIdx;
                for (; i < lines.length; i++) {
                    const s = t(lines[i]);
                    if (stopFn(s)) break;
                    buf.push(lines[i]);
                }
                return { text: buf.join('\n').trim(), next: i };
            };

            for (let i = 0; i < lines.length; i++) {
                const s = t(lines[i]);
                let m;

                if ((m = s.match(/^Patient Name:\s*(.*)$/i))) { out.patientName = t(m[1]); continue; }
                if ((m = s.match(/^Date:\s*(.*)$/i))) { out.visitDate = t(m[1]); continue; }
                if ((m = s.match(/^Age\/Gender:\s*(.*)$/i))) { out.dobAgeGender = t(m[1]); continue; }

                if ((m = s.match(/^When did the problem start\?\s*(.*)$/i))) { out.hpiStart = t(m[1]); continue; }
                if ((m = s.match(/^Duration\/Frequency:\s*(.*)$/i))) { out.hpiDuration = t(m[1]); continue; }
                if ((m = s.match(/^Severity \(mild\/moderate\/severe\):\s*(.*)$/i))) { out.hpiSeverity = t(m[1]); continue; }
                if ((m = s.match(/^Aggravating\/Relieving factors:\s*(.*)$/i))) { out.hpiFactors = t(m[1]); continue; }
                if ((m = s.match(/^Associated Symptoms:\s*(.*)$/i))) { out.hpiAssociated = t(m[1]); continue; }

                if (s === 'Past Medical History') {
                    for (let j = i + 1; j < lines.length; j++) {
                        const nx = t(lines[j]);
                        if (!nx) continue;
                        if (/^Other:\s*/i.test(nx)) break;
                        if (nx === 'Surgical History (if any)' || nx === 'Current Medications' || nx === 'Allergies') break;
                        out.pmhList = nx;
                        break;
                    }
                    continue;
                }
                if ((m = s.match(/^Other:\s*(.*)$/i))) { out.pmhOther = t(m[1]); continue; }

                if (s === 'Surgical History (if any)') {
                    const block = takeUntil(i + 1, (x) => x === 'Current Medications');
                    out.surgicalHistory = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Current Medications') {
                    const block = takeUntil(i + 1, (x) => x === 'Allergies');
                    out.currentMedications = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Allergies') {
                    let next = '';
                    for (let j = i + 1; j < lines.length; j++) {
                        const nx = t(lines[j]);
                        if (!nx) continue;
                        next = nx;
                        break;
                    }
                    if (/^none$/i.test(next)) out.allergiesNone = true;
                    const mm = next.match(/^Drugs\/Food\/Other:\s*(.*)$/i);
                    if (mm) out.allergiesOther = t(mm[1]);
                    continue;
                }
                if (s === 'Family History (relevant conditions)') {
                    const block = takeUntil(i + 1, (x) => x === 'Social History');
                    out.familyHistory = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Smoking:\s*(.*)$/i))) { out.socialSmoking = t(m[1]); continue; }
                if ((m = s.match(/^Alcohol:\s*(.*)$/i))) { out.socialAlcohol = t(m[1]); continue; }
                if ((m = s.match(/^Occupation:\s*(.*)$/i))) { out.socialOccupation = t(m[1]); continue; }

                if (s === 'Additional Notes') {
                    const block = takeUntil(i + 1, (x) => /^Patient Signature:/i.test(x));
                    out.additionalNotes = block.text;
                    i = block.next - 1;
                    continue;
                }
                if ((m = s.match(/^Patient Signature:\s*(.*?)\s*Date:\s*(.*)$/i))) {
                    out.patientSignature = t(m[1]);
                    out.patientSignatureDate = t(m[2]);
                    continue;
                }

                if ((m = s.match(/^Chief Complaint:\s*(.*)$/i))) { out.soapChiefComplaint = t(m[1]); continue; }
                if ((m = s.match(/^Vital Signs:\s*BP:\s*(.*?)\s*Pulse:\s*(.*?)\s*Temp:\s*(.*)$/i))) {
                    out.soapBp = t(m[1]);
                    out.soapPulse = t(m[2]);
                    out.soapTemp = t(m[3]);
                    continue;
                }

                if (s === 'Physical Examination Findings:') {
                    const block = takeUntil(i + 1, (x) => x === 'A – Assessment');
                    out.soapExam = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Primary Diagnosis:\s*(.*)$/i))) { out.soapPrimaryDx = t(m[1]); continue; }
                if ((m = s.match(/^Differential Diagnosis \(if any\):\s*(.*)$/i))) { out.soapDifferentialDx = t(m[1]); continue; }

                if (s === 'Investigations Ordered:') {
                    const block = takeUntil(i + 1, (x) => x === 'Medications Prescribed:');
                    out.soapInvestigations = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Medications Prescribed:') {
                    const block = takeUntil(i + 1, (x) => x === 'Treatment/Advice:');
                    out.soapMedications = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Treatment/Advice:') {
                    const block = takeUntil(i + 1, (x) => /^Follow-up:/i.test(x));
                    out.soapAdvice = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Follow-up:\s*(.*)$/i))) { out.soapFollowUp = t(m[1]); continue; }
                if ((m = s.match(/^Doctor’s Name & Signature:\s*(.*)$/i))) { out.soapDoctorSignature = t(m[1]); continue; }
            }

            const ok = !!out.patientName || !!out.soapChiefComplaint || !!out.visitDate;
            return ok ? out : null;
        }

        function renderConsultNoteFormReadOnly(d) {
            const esc = (s) => escapeHtml((s ?? '').toString());

            return `
                <div class="space-y-4 w-full">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">Doctor Consultation Form (Read-only)</div>
                        <div class="mt-1 text-xs text-gray-500">${esc(d.noteWhen || '')}${((d.noteWhen || '') && (d.noteDoctor || '')) ? ' • ' : ''}${esc(d.noteDoctor || '')}</div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Patient Information</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-gray-600">Full Name</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.patientName)}" />
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Date of Birth / Age / Gender</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.dobAgeGender)}" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Appointment Details</div>
                        <div class="mt-2">
                            <label class="block text-[11px] text-gray-600">Date of Visit</label>
                            <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.visitDate)}" />
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">🩺 Doctor Consultation Note (SOAP Format)</div>

                        <div class="mt-3">
                            <div class="text-xs font-semibold text-gray-700">S – Subjective</div>
                            <div class="mt-2">
                                <label class="block text-[11px] text-gray-600">Chief Complaint</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapChiefComplaint)}" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">O – Objective</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">BP</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapBp)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Pulse</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapPulse)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Temp</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapTemp)}" />
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Physical Examination Findings</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapExam)}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">A – Assessment</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">Primary Diagnosis</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapPrimaryDx)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Differential Diagnosis (if any)</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapDifferentialDx)}" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">P – Plan</div>
                            <div class="mt-2">
                                <label class="block text-[11px] text-gray-600">Investigations Ordered</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapInvestigations)}</textarea>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Medications Prescribed</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapMedications)}</textarea>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Treatment/Advice</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapAdvice)}</textarea>
                            </div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">Follow-up</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapFollowUp)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Doctor’s Name & Signature</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapDoctorSignature)}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        async function loadOpdConsultNotesForModal(appointmentId, q) {
            const listEl = document.getElementById('opdConsultHistoryModalList');
            const viewEl = document.getElementById('opdConsultHistoryModalView');
            if (listEl) listEl.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';
            if (viewEl) viewEl.textContent = 'Loading...';
            setOpdConsultHistoryPharmacySubmitVisible(false);

            try {
                const qs = (q || '').toString().trim();
                const hasAppt = Number.isFinite(Number(appointmentId)) && Number(appointmentId) > 0;
                const url = hasAppt
                    ? (API_BASE_URL + '/opd_notes/list.php?appointment_id=' + encodeURIComponent(String(appointmentId)) + (qs ? ('&q=' + encodeURIComponent(qs)) : ''))
                    : (API_BASE_URL + '/opd_notes/list.php?all=1' + (qs ? ('&q=' + encodeURIComponent(qs)) : ''));
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) throw new Error((json && json.error) ? json.error : 'Failed to load notes');
                opdConsultNotesCache = Array.isArray(json.notes) ? json.notes : [];
                const stillExists = opdConsultNotesCache.some(x => String(x.id || '') === String(opdConsultHistorySelectedKey || ''));
                if (!stillExists) {
                    opdConsultHistorySelectedKey = '';
                }
                renderOpdConsultHistoryModal(opdConsultNotesCache);
            } catch (e) {
                opdConsultNotesCache = [];
                if (listEl) listEl.innerHTML = '<div class="text-sm text-red-600">Unable to load notes.</div>';
                if (viewEl) viewEl.textContent = '';
                setOpdConsultHistoryPharmacySubmitVisible(false);
            }
        }

        async function openOpdConsultHistoryModal() {
            const appt = getSelectedConsultAppointment();
            const metaEl = document.getElementById('opdConsultHistoryModalMeta');
            if (metaEl) {
                if (appt) {
                    const status = (appt.status || '').toString();
                    const when = appt.appointment_at ? formatTime12h(appt.appointment_at) : '';
                    metaEl.textContent = (appt.full_name ? String(appt.full_name) : '') + (appt.patient_code ? (' • ' + String(appt.patient_code)) : '') + (status ? (' • ' + status) : '') + (when ? (' • ' + when) : '');
                } else {
                    metaEl.textContent = 'All consultation notes';
                }
            }

            opdConsultHistorySelectedKey = '';
            setOpdConsultHistoryPharmacySubmitVisible(false);
            const viewEl = document.getElementById('opdConsultHistoryModalView');
            if (viewEl) viewEl.textContent = 'Select a note to view.';

            toggleModal('opdConsultHistoryModal');
            const q = (document.getElementById('opdConsultHistorySearch')?.value || '').toString();
            await loadOpdConsultNotesForModal(appt ? Number(appt.id) : null, q);
        }

        async function submitOpdConsultNote() {
            const appt = getSelectedConsultAppointment();
            if (!appt) {
                showOpdConsultAlert('err', 'Select an appointment');
                return;
            }

            if (!opdConsultHasAnyInput()) {
                showOpdConsultAlert('err', 'Enter note details');
                return;
            }

            const noteText = buildOpdConsultNoteText().trim();
            if (!noteText) {
                showOpdConsultAlert('err', 'Enter note details');
                return;
            }

            const isEditing = Number.isFinite(Number(opdConsultEditingNoteId)) && Number(opdConsultEditingNoteId) > 0;
            const url = isEditing ? API_BASE_URL + '/opd_notes/update.php' : API_BASE_URL + '/opd_notes/create.php';
            const payload = isEditing
                ? { note_id: Number(opdConsultEditingNoteId), appointment_id: Number(appt.id), note_text: noteText }
                : { appointment_id: Number(appt.id), note_text: noteText };

            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showOpdConsultAlert('err', (json && json.error) ? json.error : 'Failed to save note');
                return;
            }

            showOpdConsultAlert('ok', isEditing ? 'Note updated' : 'Note saved');
            toggleOpdConsultSavedModal(true, isEditing ? 'The consultation note has been updated successfully.' : 'The consultation note has been saved successfully.');
            setOpdConsultEditingMode(null);
            const resetIds = [
                'opdSoapChiefComplaint','opdSoapBp','opdSoapPulse','opdSoapTemp','opdSoapExam','opdSoapPrimaryDx','opdSoapDifferentialDx',
                'opdSoapInvestigations','opdSoapMedications','opdSoapAdvice','opdSoapFollowUp','opdSoapDoctorSignature'
            ];
            resetIds.forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });

            const modalOpen = !document.getElementById('opdConsultHistoryModal')?.classList.contains('hidden');
            if (modalOpen) {
                const q = (document.getElementById('opdConsultHistorySearch')?.value || '').toString();
                await loadOpdConsultNotesForModal(Number(appt.id), q);
            }
        }

        function moneyFmt(v) {
            const n = Number(v);
            if (!Number.isFinite(n)) return '0.00';
            return n.toFixed(2);
        }

        function renderOpdBillingAppointmentOptions() {
            const results = document.getElementById('opdBillingAppointmentResults');
            const idEl = document.getElementById('opdBillingAppointmentId');
            const inputEl = document.getElementById('opdBillingAppointmentSearch');
            if (!results || !idEl || !inputEl) return;

            const q = (opdBillingAppointmentQuery || '').toString().trim().toLowerCase();
            const prev = (idEl.value || '').toString();
            const prevId = (prev && /^\d+$/.test(prev)) ? Number(prev) : null;

            const all = Array.isArray(opdBillingAppointments) ? opdBillingAppointments : [];
            const selected = (prevId ? all.find(a => Number(a.id) === prevId) : null) || null;

            const toOptionLabel = (a) => {
                const t = (a && a.appointment_at) ? String(formatTime12h(a.appointment_at) || '') : '';
                return (a.full_name || '') + ' (' + (a.patient_code || '') + ')' + (t ? (' - ' + t) : '');
            };

            const matches = (a) => {
                if (!q) return true;
                const name = (a && a.full_name) ? String(a.full_name).toLowerCase() : '';
                const code = (a && a.patient_code) ? String(a.patient_code).toLowerCase() : '';
                return name.includes(q) || code.includes(q);
            };

            const filtered = all.filter(a => {
                const s = (a.status || '').toString();
                if (s === 'rejected') return false;
                return matches(a);
            });

            results.innerHTML = '';
            if (!q) {
                results.classList.add('hidden');
                return;
            }

            if (!filtered.length) {
                results.innerHTML = '<div class="px-4 py-2 text-sm text-gray-500">No matching appointments.</div>';
                results.classList.remove('hidden');
                return;
            }

            filtered.slice(0, 50).forEach(a => {
                const id = Number(a && a.id);
                if (!Number.isFinite(id) || id <= 0) return;

                const row = document.createElement('button');
                row.type = 'button';
                row.className = 'w-full text-left px-4 py-2 text-sm hover:bg-gray-50 focus:bg-gray-50';
                if (selected && Number(selected.id) === id) {
                    row.className += ' bg-blue-50';
                }
                row.textContent = toOptionLabel(a);
                row.addEventListener('click', () => {
                    idEl.value = String(id);
                    inputEl.value = (a.full_name || '').toString();
                    opdBillingAppointmentQuery = '';
                    results.classList.add('hidden');
                    handleOpdBillingAppointmentChange();
                });
                results.appendChild(row);
            });

            results.classList.remove('hidden');
        }

        async function loadOpdBillingAppointments() {
            const idEl = document.getElementById('opdBillingAppointmentId');
            const meta = document.getElementById('opdBillingAppointmentMeta');
            const summaryEl = document.getElementById('opdBillingSummary');
            if (!idEl) return;

            if (meta) meta.textContent = '';
            if (summaryEl) summaryEl.textContent = 'Loading...';

            const d = ymdToday();
            try {
                const [todayRes, inConsultationRes, completedRes] = await Promise.all([
                    fetch(API_BASE_URL + '/opd/list.php?date=' + encodeURIComponent(d), { headers: { 'Accept': 'application/json' } }),
                    fetch(API_BASE_URL + '/opd/list.php?all=1&status=in_consultation', { headers: { 'Accept': 'application/json' } }),
                    fetch(API_BASE_URL + '/opd/list.php?all=1&status=completed', { headers: { 'Accept': 'application/json' } }),
                ]);

                const todayJson = await todayRes.json().catch(() => null);
                const inConsultationJson = await inConsultationRes.json().catch(() => null);
                const completedJson = await completedRes.json().catch(() => null);

                const todayRows = (todayRes.ok && todayJson && todayJson.ok && Array.isArray(todayJson.appointments)) ? todayJson.appointments : [];
                const inConsultationRows = (inConsultationRes.ok && inConsultationJson && inConsultationJson.ok && Array.isArray(inConsultationJson.appointments)) ? inConsultationJson.appointments : [];
                const completedRows = (completedRes.ok && completedJson && completedJson.ok && Array.isArray(completedJson.appointments)) ? completedJson.appointments : [];

                const byId = new Map();
                [...todayRows, ...inConsultationRows, ...completedRows].forEach(a => {
                    const id = Number(a && a.id);
                    if (!Number.isFinite(id) || id <= 0) return;
                    if (!byId.has(id)) byId.set(id, a);
                });

                opdBillingAppointments = Array.from(byId.values()).filter(a => (a && String(a.status || '') !== 'rejected'));
                opdBillingAppointments.sort((a, b) => {
                    const at = (a && a.appointment_at) ? new Date(String(a.appointment_at).replace(' ', 'T')).getTime() : 0;
                    const bt = (b && b.appointment_at) ? new Date(String(b.appointment_at).replace(' ', 'T')).getTime() : 0;
                    if (at !== bt) return at - bt;
                    return Number(a && a.id) - Number(b && b.id);
                });

                renderOpdBillingAppointmentOptions();
                handleOpdBillingAppointmentChange();
            } catch (e) {
                opdBillingAppointments = [];
                if (summaryEl) summaryEl.textContent = 'Unable to load appointments.';
            }
        }

        function getSelectedBillingAppointment() {
            const idEl = document.getElementById('opdBillingAppointmentId');
            if (!idEl) return null;
            const v = (idEl.value || '').toString().trim();
            if (!v || !/^\d+$/.test(v)) return null;
            const id = Number(v);
            return (opdBillingAppointments || []).find(a => Number(a.id) === id) || null;
        }

        async function handleOpdBillingAppointmentChange() {
            const meta = document.getElementById('opdBillingAppointmentMeta');
            const appt = getSelectedBillingAppointment();
            const summaryEl = document.getElementById('opdBillingSummary');
            if (!appt) {
                if (meta) meta.textContent = '';
                if (summaryEl) summaryEl.textContent = 'Select an appointment to view billing summary.';
                return;
            }

            if (meta) {
                const status = (appt.status || '').toString();
                const when = appt.appointment_at ? formatTime12h(appt.appointment_at) : '';
                meta.textContent = 'Doctor: ' + (appt.doctor_name || '') + (status ? (' • Status: ' + status) : '') + (when ? (' • Time: ' + when) : '');
            }

            await loadOpdBillingForAppointment(Number(appt.id));
        }

        async function loadOpdBillingForAppointment(appointmentId) {
            const summaryEl = document.getElementById('opdBillingSummary');
            if (summaryEl) summaryEl.textContent = 'Loading...';

            let appt = null;
            const apptRow = (opdBillingAppointments || []).find(a => Number(a.id) === Number(appointmentId)) || null;
            if (apptRow) appt = apptRow;
            if (!appt) {
                const resAppt = await fetch(API_BASE_URL + '/opd/get.php?id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } });
                const apptJson = await resAppt.json().catch(() => null);
                if (resAppt.ok && apptJson && apptJson.ok) appt = apptJson.appointment || null;
            }

            const patientId = appt ? Number(appt.patient_id || 0) : 0;
            const patientCode = appt ? String(appt.patient_code || '') : '';
            const apptStatus = appt ? String(appt.status || '') : '';

            try {
                const [itemsRes, cashierInvRes, chargesRes] = await Promise.all([
                    fetch(API_BASE_URL + '/opd_billing/list.php?appointment_id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } }),
                    fetch(API_BASE_URL + '/cashier/get_invoice_by_source.php?source_module=' + encodeURIComponent('opd_billing_summary') + '&source_id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } }),
                    (patientCode ? fetch(API_BASE_URL + '/cashier/list_charges.php?group=patient&status=pending_invoice&q=' + encodeURIComponent(patientCode), { headers: { 'Accept': 'application/json' } }) : Promise.resolve(null)),
                ]);

                const itemsJson = await itemsRes.json().catch(() => null);
                const cashierInvJson = await cashierInvRes.json().catch(() => null);
                const chargesJson = chargesRes ? await chargesRes.json().catch(() => null) : null;

                const opdItems = (itemsRes.ok && itemsJson && itemsJson.ok && Array.isArray(itemsJson.items)) ? itemsJson.items : [];
                const opdTotal = (itemsRes.ok && itemsJson && itemsJson.ok) ? Number(itemsJson.total || 0) : 0;

                const cashierInv = (cashierInvRes.ok && cashierInvJson && cashierInvJson.ok) ? (cashierInvJson.invoice || null) : null;

                let labTotal = 0;
                let pharmTotal = 0;
                let labChargeId = null;
                let pharmChargeId = null;
                let labInvoiceId = null;
                let pharmInvoiceId = null;
                if (chargesRes && chargesRes.ok && chargesJson && chargesJson.ok && Array.isArray(chargesJson.charges)) {
                    const match = chargesJson.charges.find(x => Number(x.patient_id || 0) === patientId) || null;
                    if (match) {
                        if (match.lab_total !== null && match.lab_total !== undefined) labTotal = Number(match.lab_total || 0) || 0;
                        if (match.pharmacy_total !== null && match.pharmacy_total !== undefined) pharmTotal = Number(match.pharmacy_total || 0) || 0;
                        labChargeId = match.lab_charge_id ? Number(match.lab_charge_id) : null;
                        pharmChargeId = match.pharmacy_charge_id ? Number(match.pharmacy_charge_id) : null;
                        labInvoiceId = match.lab_invoice_id ? Number(match.lab_invoice_id) : null;
                        pharmInvoiceId = match.pharmacy_invoice_id ? Number(match.pharmacy_invoice_id) : null;
                    }
                }

                if (summaryEl) {
                    const consultationItems = opdItems.filter(x => String(x.item_type || '') === 'consultation');
                    const manualItems = opdItems.filter(x => String(x.item_type || '') !== 'consultation');

                    const consultationTotal = consultationItems.reduce((sum, x) => sum + (Number(x.subtotal || 0) || 0), 0);
                    const manualTotal = manualItems.reduce((sum, x) => sum + (Number(x.subtotal || 0) || 0), 0);

                    const combined = opdTotal + labTotal + pharmTotal;
                    const canSend = apptStatus === 'completed' && !cashierInv;

                    summaryEl.innerHTML = `
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-gray-800">Consultation</div>
                                    ${cashierInv ? `<div class=\"text-xs text-gray-500\">Submitted • Invoice #${escapeHtml(String(cashierInv.id || ''))} • ${escapeHtml(String(cashierInv.status || ''))}</div>` : ''}
                                </div>
                                ${consultationItems.length ? `
                                    <div class="mt-3 space-y-2">
                                        ${consultationItems.map(x => {
                                            const desc = escapeHtml(x.description || '');
                                            const qty = Number(x.qty || 0) || 0;
                                            const unit = Number(x.unit_price || 0) || 0;
                                            const subtotal = Number(x.subtotal || 0) || (qty * unit);
                                            return `<div class="flex items-center justify-between text-sm"><div class="text-gray-800">${desc} × ${qty}</div><div class="text-gray-700">₱${moneyFmt(subtotal)}</div></div>`;
                                        }).join('')}
                                    </div>
                                    <div class="mt-3 text-right text-sm font-semibold text-gray-800">Consultation total: ₱${moneyFmt(consultationTotal)}</div>
                                ` : `<div class="mt-2 text-sm text-gray-500">No consultation fee yet.</div>`}
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm font-semibold text-gray-800">Manual Items</div>
                                ${manualItems.length ? `
                                    <div class="mt-3 overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-white">
                                                <tr>
                                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Qty</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Unit</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                ${manualItems.map(it => {
                                                    const id = Number(it.id || 0);
                                                    const desc = escapeHtml(it.description || '');
                                                    const qty = Number(it.qty || 0) || 0;
                                                    const unit = Number(it.unit_price || 0) || 0;
                                                    const subtotal = Number(it.subtotal || (qty * unit)) || 0;
                                                    return `
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-2 text-sm text-gray-800">${desc}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-700 text-right">${qty}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-700 text-right">₱${moneyFmt(unit)}</td>
                                                            <td class="px-4 py-2 text-sm text-gray-700 text-right">₱${moneyFmt(subtotal)}</td>
                                                            <td class="px-4 py-2 text-right">
                                                                <button type="button" class="px-3 py-1.5 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700" onclick="deleteOpdBillingItem(${id}, ${appointmentId})">Remove</button>
                                                            </td>
                                                        </tr>
                                                    `;
                                                }).join('')}
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-3 text-right text-sm font-semibold text-gray-800">Manual total: ₱${moneyFmt(manualTotal)}</div>
                                ` : `<div class="mt-2 text-sm text-gray-500">No manual items.</div>`}
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm font-semibold text-gray-800">Laboratory Charges</div>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="text-gray-800">Lab (pending)</div>
                                        <div class="text-gray-700">₱${moneyFmt(labTotal)}</div>
                                    </div>
                                    ${labChargeId ? `<div class=\"text-xs text-gray-500\">Lab charge: #${escapeHtml(String(labChargeId))}${labInvoiceId ? ' • Invoice: #' + escapeHtml(String(labInvoiceId)) : ''}</div>` : ''}
                                </div>
                            </div>

                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="text-sm font-semibold text-gray-800">Pharmacy Charges</div>
                                <div class="mt-2 space-y-2">
                                    <div class="flex items-center justify-between text-sm">
                                        <div class="text-gray-800">Pharmacy (pending)</div>
                                        <div class="text-gray-700">₱${moneyFmt(pharmTotal)}</div>
                                    </div>
                                    ${pharmChargeId ? `<div class=\"text-xs text-gray-500\">Pharmacy charge: #${escapeHtml(String(pharmChargeId))}${pharmInvoiceId ? ' • Invoice: #' + escapeHtml(String(pharmInvoiceId)) : ''}</div>` : ''}
                                </div>
                            </div>

                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="text-sm font-semibold text-gray-800">Grand Total</div>
                                    <div class="text-lg font-bold text-gray-900">₱${moneyFmt(combined)}</div>
                                </div>
                                <div class="mt-2 text-xs text-gray-500">Includes consultation + manual items (OPD), plus lab/pharmacy pending charges.</div>
                                ${apptStatus === 'completed' ? `
                                    <div class="mt-4">
                                        ${cashierInv ? `<div class=\"text-xs text-gray-500\">Already submitted to cashier.</div>` : `<button type=\"button\" class=\"w-full px-4 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700\" ${canSend ? '' : 'disabled'} onclick=\"submitOpdBillToCashier(${appointmentId})\">Send Summary Bill to Cashier</button>`}
                                    </div>
                                ` : `<div class="mt-4 text-xs text-gray-500">Complete the appointment to enable cashier submission.</div>`}
                            </div>
                        </div>
                    `;
                }
            } catch (e) {
                if (summaryEl) summaryEl.innerHTML = '<div class="text-sm text-red-600">Unable to load billing summary.</div>';
            }
        }

        async function submitOpdBillToCashier(appointmentId) {
            const id = Number(appointmentId || 0);
            if (!Number.isFinite(id) || id <= 0) return;

            const res = await fetch(API_BASE_URL + '/opd_billing/submit_to_cashier.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ appointment_id: id }),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showOpdBillingAlert('err', (json && json.error) ? json.error : 'Failed to submit to cashier');
                return;
            }

            showOpdBillingAlert('ok', 'Submitted to cashier (Invoice #' + String(json.invoice_id || '') + ')');
            await loadOpdBillingForAppointment(id);
        }

        async function submitOpdBillingItem() {
            const appt = getSelectedBillingAppointment();
            if (!appt) {
                showOpdBillingAlert('err', 'Select an appointment');
                return;
            }

            const itemType = (document.getElementById('opdBillingItemType')?.value || 'misc').toString().trim();
            const description = (document.getElementById('opdBillingDescription')?.value || '').toString().trim();
            const qtyRaw = (document.getElementById('opdBillingQty')?.value || '1').toString().trim();
            const unitRaw = (document.getElementById('opdBillingUnitPrice')?.value || '0').toString().trim();

            if (!description) {
                showOpdBillingAlert('err', 'Enter description');
                return;
            }

            const qty = /^\d+$/.test(qtyRaw) ? Number(qtyRaw) : 1;
            const unitPrice = unitRaw !== '' && !Number.isNaN(Number(unitRaw)) ? Number(unitRaw) : 0;

            const res = await fetch(API_BASE_URL + '/opd_billing/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({
                    appointment_id: Number(appt.id),
                    item_type: itemType,
                    description,
                    qty,
                    unit_price: unitPrice,
                }),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showOpdBillingAlert('err', (json && json.error) ? json.error : 'Failed to add item');
                return;
            }

            showOpdBillingAlert('ok', 'Item added');
            const descEl = document.getElementById('opdBillingDescription');
            if (descEl) descEl.value = '';
            const qtyEl = document.getElementById('opdBillingQty');
            if (qtyEl) qtyEl.value = '1';
            const unitEl = document.getElementById('opdBillingUnitPrice');
            if (unitEl) unitEl.value = '0';
            await loadOpdBillingForAppointment(Number(appt.id));
        }

        async function deleteOpdBillingItem(itemId, appointmentId) {
            const id = Number(itemId || 0);
            if (!Number.isFinite(id) || id <= 0) return;
            const res = await fetch(API_BASE_URL + '/opd_billing/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ id }),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showOpdBillingAlert('err', (json && json.error) ? json.error : 'Failed to remove item');
                return;
            }
            showOpdBillingAlert('ok', 'Item removed');
            await loadOpdBillingForAppointment(Number(appointmentId));
        }

        function getSelectedAssessmentAppointment() {
            const idEl = document.getElementById('opdAssessAppointmentId');
            if (!idEl) return null;
            const v = (idEl.value || '').toString().trim();
            if (!v || !/^\d+$/.test(v)) return null;
            const id = Number(v);
            if (opdAssessSelectedAppointment && Number(opdAssessSelectedAppointment.id) === id) return opdAssessSelectedAppointment;
            return (opdAssessAppointments || []).find(a => Number(a.id) === id) || null;
        }

        async function handleOpdAssessAppointmentChange() {
            const meta = document.getElementById('opdAssessAppointmentMeta');
            const history = document.getElementById('opdAssessHistory');
            const historyModal = document.getElementById('opdAssessHistoryModal');
            const historyModalOpen = !!historyModal && !historyModal.classList.contains('hidden');
            const appt = getSelectedAssessmentAppointment();
            if (!appt) {
                if (meta) meta.textContent = '';
                if (history && !historyModalOpen) history.textContent = 'Select a patient to view assessments.';
                return;
            }

            if (meta) {
                const status = (appt.status || '').toString();
                const when = appt.appointment_at ? formatTime12h(appt.appointment_at) : '';
                meta.textContent = 'Doctor: ' + (appt.doctor_name || '') + (status ? (' • Status: ' + status) : '') + (when ? (' • Time: ' + when) : '');
            }

            if (history) history.textContent = 'Loading...';
            await loadOpdAssessments(Number(appt.id));
        }

        const opdAssessCancelEditBtn = document.getElementById('opdAssessCancelEditBtn');
        if (opdAssessCancelEditBtn) {
            opdAssessCancelEditBtn.addEventListener('click', () => {
                setOpdAssessEditMode(null);
            });
        }

        const opdAssessDockEditBtn = document.getElementById('opdAssessDockEditBtn');
        if (opdAssessDockEditBtn) {
            opdAssessDockEditBtn.addEventListener('click', async () => {
                const id = opdSelectedAssessmentContext && opdSelectedAssessmentContext.assessment_id ? Number(opdSelectedAssessmentContext.assessment_id) : 0;
                if (!id) {
                    showOpdAssessAlert('err', 'Select an assessment from history');
                    return;
                }
                try {
                    const res = await fetch(API_BASE_URL + '/opd_assessment/list.php?appointment_id=' + encodeURIComponent(String(opdSelectedAssessmentContext.appointment_id || '')), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) throw new Error('Failed');
                    const rows = Array.isArray(json.assessments) ? json.assessments : [];
                    const row = rows.find(r => Number(r.id) === id) || null;
                    if (!row) {
                        showOpdAssessAlert('err', 'Assessment not found');
                        return;
                    }
                    fillOpdAssessmentFormFromRow(row);
                    setOpdAssessEditMode(id);
                    toggleModal('opdAssessHistoryModal');
                    const section = document.getElementById('opdNursingAssessmentSection');
                    if (section) section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                } catch (e) {
                    showOpdAssessAlert('err', 'Unable to load assessment');
                }
            });
        }

        function parseVitalsJson(v) {
            if (!v) return null;
            if (typeof v === 'object') return v;
            try { return JSON.parse(String(v)); } catch (e) { return null; }
        }

        function renderVitals(vitals) {
            const v = vitals || {};
            const bpSys = (v.bp_systolic ?? '').toString();
            const bpDia = (v.bp_diastolic ?? '').toString();
            const bp = (bpSys && bpDia) ? (bpSys + '/' + bpDia) : (bpSys || bpDia ? (bpSys + '/' + bpDia) : '');
            const items = [
                ['BP', bp],
                ['HR', v.hr],
                ['RR', v.rr],
                ['Temp', v.temp],
                ['SpO₂', v.spo2],
                ['Weight', v.weight],
                ['Height', v.height],
            ].filter(x => x[1] !== null && x[1] !== undefined && String(x[1]).trim() !== '');
            if (!items.length) return '<div class="text-xs text-gray-500">No vitals recorded.</div>';
            return '<div class="grid grid-cols-2 gap-2">' + items.map(([k, val]) => {
                return `<div class="text-xs text-gray-700"><span class="font-semibold">${escapeHtml(k)}:</span> ${escapeHtml(String(val))}</div>`;
            }).join('') + '</div>';
        }

        async function loadOpdAssessments(appointmentId) {
            const history = document.getElementById('opdAssessHistory');
            if (!history) return;

            try {
                const res = await fetch(API_BASE_URL + '/opd_assessment/list.php?appointment_id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) throw new Error('Failed');

                const rows = Array.isArray(json.assessments) ? json.assessments : [];
                if (!rows.length) {
                    history.innerHTML = '<div class="text-sm text-gray-500">No assessments yet.</div>';
                    return;
                }

                history.innerHTML = `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">When</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Nurse</th>
                                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${rows.map(r => {
                                    const when = r.created_at ? escapeHtml(new Date(r.created_at).toLocaleString()) : '';
                                    const nurse = escapeHtml(r.nurse_name || '-');
                                    return `
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-4 py-2 text-sm text-gray-700">${when}</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">${nurse}</td>
                                            <td class="px-4 py-2 text-right">
                                                <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openOpdAssessDetails(${Number(r.id)}, ${appointmentId})">View</button>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    </div>
                `;
            } catch (e) {
                history.innerHTML = '<div class="text-sm text-red-600">Unable to load assessments.</div>';
            }
        }

        async function openOpdAssessDetails(assessmentId, appointmentId) {
            const content = document.getElementById('opdAssessDetailsContent');
            const printBtn = document.getElementById('opdAssessPrintBtn');
            if (!content) return;

            const res = await fetch(API_BASE_URL + '/opd_assessment/list.php?appointment_id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                toggleModal('opdAssessDetailsModal');
                return;
            }
            const rows = Array.isArray(json.assessments) ? json.assessments : [];
            const row = rows.find(r => Number(r.id) === Number(assessmentId)) || null;
            if (!row) {
                content.innerHTML = '<div class="text-sm text-red-600">Assessment not found.</div>';
                toggleModal('opdAssessDetailsModal');
                return;
            }

            const vitals = parseVitalsJson(row.vitals_json);
            const assessExtra = parseVitalsJson(row.assessment_json) || {};
            const pmh = (assessExtra.pmh && typeof assessExtra.pmh === 'object') ? assessExtra.pmh : {};
            const social = (assessExtra.social && typeof assessExtra.social === 'object') ? assessExtra.social : {};
            const hpi = (assessExtra.hpi && typeof assessExtra.hpi === 'object') ? assessExtra.hpi : {};
            const detailsHtml = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Patient</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(row.full_name || '')}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(row.patient_code || '')}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Assessment</div>
                            <div class="text-xs text-gray-500 mt-2">When: ${row.created_at ? escapeHtml(new Date(row.created_at).toLocaleString()) : ''}</div>
                            <div class="text-xs text-gray-500">Nurse: ${escapeHtml(row.nurse_name || '-')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Vitals</div>
                        <div class="mt-3">${renderVitals(vitals)}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">History of Present Illness</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                            <div><span class="text-xs text-gray-500">Start:</span> <span class="font-semibold">${escapeHtml(hpi.start || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Duration/Frequency:</span> <span class="font-semibold">${escapeHtml(hpi.duration || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Severity:</span> <span class="font-semibold">${escapeHtml(hpi.severity || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Associated Symptoms:</span> <span class="font-semibold">${escapeHtml(hpi.associated || '-')}</span></div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700"><span class="text-xs text-gray-500">Aggravating/Relieving:</span> <span class="font-semibold">${escapeHtml(hpi.factors || '-')}</span></div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Past Medical History</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-semibold">Diabetes:</span> ${pmh.diabetes ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Hypertension:</span> ${pmh.hypertension ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Asthma:</span> ${pmh.asthma ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Heart Disease:</span> ${pmh.heart_disease ? 'Yes' : 'No'}</div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700"><span class="font-semibold">Other:</span> ${escapeHtml(pmh.other || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Surgical History</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.surgical_history || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Current Medications</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.current_medications || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Allergies</div>
                        <div class="mt-3 text-sm text-gray-700">${assessExtra.allergies_none ? 'None' : escapeHtml(assessExtra.allergies_other || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Family History</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.family_history || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Social History</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-semibold">Smoking:</span> ${escapeHtml(social.smoking || '-')}</div>
                            <div><span class="font-semibold">Alcohol:</span> ${escapeHtml(social.alcohol || '-')}</div>
                            <div class="md:col-span-2"><span class="font-semibold">Occupation:</span> ${escapeHtml(social.occupation || '-')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Notes</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(row.notes || '-')}</div>
                    </div>
                </div>
            `;

            content.innerHTML = detailsHtml;

            if (printBtn) {
                printBtn.onclick = function () {
                    const w = window.open('', '_blank');
                    if (!w) return;
                    w.document.write('<html><head><title>Nursing Assessment</title>');
                    w.document.write('<meta charset="utf-8"/>');
                    w.document.write('</head><body>');
                    w.document.write(content.innerHTML);
                    w.document.write('</body></html>');
                    w.document.close();
                    w.focus();
                    w.print();
                };
            }

            toggleModal('opdAssessDetailsModal');
        }

        function closeOpdAssessDock() {
            const el = document.getElementById('opdAssessDockContent');
            if (el) el.innerHTML = 'Select an assessment to view.';
            setSelectedAssessmentPatient(null);
        }

        let opdSelectedAssessmentContext = null;

        function setSelectedAssessmentPatient(ctx) {
            opdSelectedAssessmentContext = ctx;
            const btn = document.getElementById('opdAssessDockSendRequestBtn');
            if (btn) {
                btn.disabled = !opdSelectedAssessmentContext;
            }
        }

        function openScheduleAppointmentFromSelectedAssessment() {
            if (!opdSelectedAssessmentContext) return;

            const input = document.getElementById('opdApptPatientSearch');
            const pid = document.getElementById('opdApptPatientId');
            const naid = document.getElementById('opdApptNursingAssessmentId');
            const sel = document.getElementById('opdApptPatientSelected');
            const results = document.getElementById('opdApptPatientResults');

            if (pid) pid.value = String(opdSelectedAssessmentContext.patient_id || '');
            if (naid) naid.value = String(opdSelectedAssessmentContext.assessment_id || '');
            if (input) input.value = String(opdSelectedAssessmentContext.full_name || '');
            if (sel) {
                const code = (opdSelectedAssessmentContext.patient_code || '').toString();
                sel.textContent = code ? ('Selected: ' + String(opdSelectedAssessmentContext.full_name || '') + ' • ' + code) : ('Selected: ' + String(opdSelectedAssessmentContext.full_name || ''));
            }
            if (results) results.classList.add('hidden');

            toggleModal('scheduleAppointmentModal');
        }

        function shouldUseOpdAssessDock() {
            const m = document.getElementById('opdAssessHistoryModal');
            if (!m || m.classList.contains('hidden')) return false;
            return window.matchMedia && window.matchMedia('(min-width: 1024px)').matches;
        }

        async function openOpdAssessDetailsFromHistory(assessmentId, appointmentId) {
            const detailContent = document.getElementById('opdAssessDetailContent');
            if (detailContent) detailContent.innerHTML = '<div class="text-sm text-gray-500">Loading...</div>';
            showOpdAssessDetail();

            const res = await fetch(API_BASE_URL + '/opd_assessment/list.php?appointment_id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                if (detailContent) detailContent.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                return;
            }
            const rows = Array.isArray(json.assessments) ? json.assessments : [];
            const row = rows.find(r => Number(r.id) === Number(assessmentId)) || null;
            if (!row) {
                if (detailContent) detailContent.innerHTML = '<div class="text-sm text-red-600">Assessment not found.</div>';
                return;
            }

            setSelectedAssessmentPatient({
                patient_id: Number(row.patient_id || 0),
                full_name: (row.full_name || '').toString(),
                patient_code: (row.patient_code || '').toString(),
                assessment_id: Number(row.id || 0),
            });

            const vitals = parseVitalsJson(row.vitals_json);
            const assessExtra = parseVitalsJson(row.assessment_json) || {};
            const pmh = (assessExtra.pmh && typeof assessExtra.pmh === 'object') ? assessExtra.pmh : {};
            const social = (assessExtra.social && typeof assessExtra.social === 'object') ? assessExtra.social : {};
            const hpi = (assessExtra.hpi && typeof assessExtra.hpi === 'object') ? assessExtra.hpi : {};

            const detailHtml = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Patient</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(row.full_name || '')}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(row.patient_code || '')}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Assessment</div>
                            <div class="text-xs text-gray-500 mt-2">When: ${row.created_at ? escapeHtml(new Date(row.created_at).toLocaleString()) : ''}</div>
                            <div class="text-xs text-gray-500">Nurse: ${escapeHtml(row.nurse_name || '-')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Vitals</div>
                        <div class="mt-3">${renderVitals(vitals)}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">History of Present Illness</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3 text-sm text-gray-700">
                            <div><span class="text-xs text-gray-500">Start:</span> <span class="font-semibold">${escapeHtml(hpi.start || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Duration/Frequency:</span> <span class="font-semibold">${escapeHtml(hpi.duration || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Severity:</span> <span class="font-semibold">${escapeHtml(hpi.severity || '-')}</span></div>
                            <div><span class="text-xs text-gray-500">Associated Symptoms:</span> <span class="font-semibold">${escapeHtml(hpi.associated || '-')}</span></div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700"><span class="text-xs text-gray-500">Aggravating/Relieving:</span> <span class="font-semibold">${escapeHtml(hpi.factors || '-')}</span></div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Past Medical History</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-semibold">Diabetes:</span> ${pmh.diabetes ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Hypertension:</span> ${pmh.hypertension ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Asthma:</span> ${pmh.asthma ? 'Yes' : 'No'}</div>
                            <div><span class="font-semibold">Heart Disease:</span> ${pmh.heart_disease ? 'Yes' : 'No'}</div>
                        </div>
                        <div class="mt-3 text-sm text-gray-700"><span class="font-semibold">Other:</span> ${escapeHtml(pmh.other || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Surgical History</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.surgical_history || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Current Medications</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.current_medications || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Allergies</div>
                        <div class="mt-3 text-sm text-gray-700">${assessExtra.allergies_none ? 'None' : escapeHtml(assessExtra.allergies_other || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Family History</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(assessExtra.family_history || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Social History</div>
                        <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-2 text-sm text-gray-700">
                            <div><span class="font-semibold">Smoking:</span> ${escapeHtml(social.smoking || '-')}</div>
                            <div><span class="font-semibold">Alcohol:</span> ${escapeHtml(social.alcohol || '-')}</div>
                            <div class="md:col-span-2"><span class="font-semibold">Occupation:</span> ${escapeHtml(social.occupation || '-')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Notes</div>
                        <div class="mt-3 text-sm text-gray-700">${escapeHtml(row.notes || '-')}</div>
                    </div>
                </div>
            `;

            if (detailContent) detailContent.innerHTML = detailHtml;

            const printBtn = document.getElementById('opdAssessDockPrintBtn');
            if (printBtn) {
                printBtn.onclick = function () {
                    const w = window.open('', '_blank');
                    if (!w) return;
                    w.document.write('<html><head><title>Nursing Assessment</title>');
                    w.document.write('<meta charset="utf-8"/>');
                    w.document.write('</head><body>');
                    w.document.write(detailHtml);
                    w.document.write('</body></html>');
                    w.document.close();
                    w.focus();
                    w.print();
                };
            }

            const editBtn = document.getElementById('opdAssessDockEditBtn');
            if (editBtn) editBtn.disabled = !(opdSelectedAssessmentContext && opdSelectedAssessmentContext.assessment_id);
        }

        function setOpdAssessEditMode(id) {
            opdAssessEditingId = (id && Number(id) > 0) ? Number(id) : null;
            const saveBtn = document.getElementById('opdAssessSaveBtn');
            const cancelBtn = document.getElementById('opdAssessCancelEditBtn');
            if (saveBtn) saveBtn.textContent = opdAssessEditingId ? 'Update Assessment' : 'Save Assessment';
            if (cancelBtn) cancelBtn.classList.toggle('hidden', !opdAssessEditingId);
        }

        function fillOpdAssessmentFormFromRow(row) {
            if (!row) return;

            const pidEl = document.getElementById('opdAssessPatientId');
            const apptIdEl = document.getElementById('opdAssessAppointmentId');
            const inputEl = document.getElementById('opdAssessAppointmentSearch');
            const metaEl = document.getElementById('opdAssessAppointmentMeta');

            if (pidEl) pidEl.value = String(Number(row.patient_id || 0) || '');
            if (apptIdEl) apptIdEl.value = String(Number(row.appointment_id || 0) || '');
            if (inputEl) inputEl.value = (row.full_name || '').toString();
            if (metaEl) metaEl.textContent = 'Selected: ' + (row.full_name || '') + ' (' + (row.patient_code || '') + ')';

            opdAssessSelectedAppointment = { id: Number(row.appointment_id || 0) };
            opdAssessAppointments = [opdAssessSelectedAppointment];

            const vitals = parseVitalsJson(row.vitals_json) || {};
            const assessExtra = parseVitalsJson(row.assessment_json) || {};
            const pmh = (assessExtra.pmh && typeof assessExtra.pmh === 'object') ? assessExtra.pmh : {};
            const social = (assessExtra.social && typeof assessExtra.social === 'object') ? assessExtra.social : {};
            const hpi = (assessExtra.hpi && typeof assessExtra.hpi === 'object') ? assessExtra.hpi : {};

            const setVal = (id, v) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = (v ?? '').toString();
            };

            setVal('opdAssessBpSys', vitals.bp_systolic ?? vitals.bp_sys ?? '');
            setVal('opdAssessBpDia', vitals.bp_diastolic ?? vitals.bp_dia ?? '');
            setVal('opdAssessHr', vitals.hr);
            setVal('opdAssessRr', vitals.rr);
            setVal('opdAssessTemp', vitals.temp);
            setVal('opdAssessSpo2', vitals.spo2);
            setVal('opdAssessWeight', vitals.weight);
            setVal('opdAssessHeight', vitals.height);

            setVal('opdAssessNurseName', row.nurse_name);

            setVal('opdAssessHpiStart', hpi.start);
            setVal('opdAssessHpiDuration', hpi.duration);
            setVal('opdAssessHpiSeverity', hpi.severity);
            setVal('opdAssessHpiAssociated', hpi.associated);
            setVal('opdAssessHpiFactors', hpi.factors);

            const setCheck = (id, v) => {
                const el = document.getElementById(id);
                if (el) el.checked = !!v;
            };
            setCheck('opdAssessPmhDiabetes', pmh.diabetes);
            setCheck('opdAssessPmhHypertension', pmh.hypertension);
            setCheck('opdAssessPmhAsthma', pmh.asthma);
            setCheck('opdAssessPmhHeartDisease', pmh.heart_disease);
            setVal('opdAssessPmhOther', pmh.other);

            setVal('opdAssessSurgicalHistory', assessExtra.surgical_history);
            setVal('opdAssessCurrentMedications', assessExtra.current_medications);
            setVal('opdAssessAllergiesOther', assessExtra.allergies_other);
            setVal('opdAssessFamilyHistory', assessExtra.family_history);
            setVal('opdAssessOccupation', social.occupation);
            setVal('opdAssessNotes', row.notes);

            document.querySelectorAll('input[name="opdAssessSmoking"], input[name="opdAssessAlcohol"]').forEach(el => {
                el.checked = false;
            });
            const smoke = (social.smoking || '').toString().trim().toLowerCase();
            if (smoke) {
                const el = document.querySelector(`input[name="opdAssessSmoking"][value="${CSS.escape(smoke)}"]`);
                if (el) el.checked = true;
            }
            const alcohol = (social.alcohol || '').toString().trim().toLowerCase();
            if (alcohol) {
                const el = document.querySelector(`input[name="opdAssessAlcohol"][value="${CSS.escape(alcohol)}"]`);
                if (el) el.checked = true;
            }
        }

        function showOpdAssessDetail() {
            const listContainer = document.getElementById('opdAssessHistoryListContainer');
            const detailContainer = document.getElementById('opdAssessDetailContainer');
            if (listContainer) listContainer.classList.add('hidden');
            if (detailContainer) detailContainer.classList.remove('hidden');
        }

        function showOpdAssessHistoryList() {
            const listContainer = document.getElementById('opdAssessHistoryListContainer');
            const detailContainer = document.getElementById('opdAssessDetailContainer');
            if (listContainer) listContainer.classList.remove('hidden');
            if (detailContainer) detailContainer.classList.add('hidden');
        }

        async function resolveLatestOpdAppointmentForPatient(patientId) {
            const pid = Number(patientId || 0);
            if (!Number.isFinite(pid) || pid <= 0) return null;
            try {
                const aRes = await fetch(API_BASE_URL + '/opd/list.php?patient_id=' + encodeURIComponent(String(pid)) + '&latest=1', { headers: { 'Accept': 'application/json' } });
                const aJson = await aRes.json().catch(() => null);
                return (aRes.ok && aJson && aJson.ok && Array.isArray(aJson.appointments) && aJson.appointments[0]) ? aJson.appointments[0] : null;
            } catch (e) {
                return null;
            }
        }

        async function createOpdAppointmentForAssessment(patientId) {
            const pid = Number(patientId || 0);
            if (!Number.isFinite(pid) || pid <= 0) return null;
            try {
                const res = await fetch(API_BASE_URL + '/opd/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        patient_id: pid,
                        doctor_name: 'TBD',
                        status: 'requested',
                        notes: 'Auto-created from Nursing Assessment',
                    }),
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !json.appointment) return null;
                return json.appointment;
            } catch (e) {
                return null;
            }
        }

        async function submitOpdAssessment() {
            const pidRaw = (document.getElementById('opdAssessPatientId')?.value || '').toString().trim();
            const pid = (pidRaw && /^\d+$/.test(pidRaw)) ? Number(pidRaw) : 0;
            if (!pid) {
                showOpdAssessAlert('err', 'Select a patient');
                return;
            }

            let appt = getSelectedAssessmentAppointment();
            if (!appt) {
                const resolved = await resolveLatestOpdAppointmentForPatient(pid);
                if (resolved && Number(resolved.id || 0) > 0) {
                    const apptIdEl = document.getElementById('opdAssessAppointmentId');
                    if (apptIdEl) apptIdEl.value = String(Number(resolved.id || 0));
                    opdAssessSelectedAppointment = resolved;
                    opdAssessAppointments = [resolved];
                    appt = resolved;
                }
            }

            if (!appt) {
                const created = await createOpdAppointmentForAssessment(pid);
                if (created && Number(created.id || 0) > 0) {
                    const apptIdEl = document.getElementById('opdAssessAppointmentId');
                    if (apptIdEl) apptIdEl.value = String(Number(created.id || 0));
                    opdAssessSelectedAppointment = created;
                    opdAssessAppointments = [created];
                    appt = created;
                }
            }

            if (!appt || !Number(appt.id || 0)) {
                showOpdAssessAlert('err', 'Unable to create OPD appointment for selected patient');
                return;
            }

            const nurseName = (document.getElementById('opdAssessNurseName')?.value || '').toString().trim();

            const vitals = {
                bp_systolic: (document.getElementById('opdAssessBpSys')?.value || '').toString().trim(),
                bp_diastolic: (document.getElementById('opdAssessBpDia')?.value || '').toString().trim(),
                hr: (document.getElementById('opdAssessHr')?.value || '').toString().trim(),
                rr: (document.getElementById('opdAssessRr')?.value || '').toString().trim(),
                temp: (document.getElementById('opdAssessTemp')?.value || '').toString().trim(),
                spo2: (document.getElementById('opdAssessSpo2')?.value || '').toString().trim(),
                weight: (document.getElementById('opdAssessWeight')?.value || '').toString().trim(),
                height: (document.getElementById('opdAssessHeight')?.value || '').toString().trim(),
            };

            Object.keys(vitals).forEach(k => {
                if (vitals[k] === '') delete vitals[k];
            });

            const assessment = {
                hpi: {
                    start: (document.getElementById('opdAssessHpiStart')?.value || '').toString().trim() || null,
                    duration: (document.getElementById('opdAssessHpiDuration')?.value || '').toString().trim() || null,
                    severity: (document.getElementById('opdAssessHpiSeverity')?.value || '').toString().trim() || null,
                    associated: (document.getElementById('opdAssessHpiAssociated')?.value || '').toString().trim() || null,
                    factors: (document.getElementById('opdAssessHpiFactors')?.value || '').toString().trim() || null,
                },
                pmh: {
                    diabetes: !!document.getElementById('opdAssessPmhDiabetes')?.checked,
                    hypertension: !!document.getElementById('opdAssessPmhHypertension')?.checked,
                    asthma: !!document.getElementById('opdAssessPmhAsthma')?.checked,
                    heart_disease: !!document.getElementById('opdAssessPmhHeartDisease')?.checked,
                    other: (document.getElementById('opdAssessPmhOther')?.value || '').toString().trim() || null,
                },
                surgical_history: (document.getElementById('opdAssessSurgicalHistory')?.value || '').toString().trim() || null,
                current_medications: (document.getElementById('opdAssessCurrentMedications')?.value || '').toString().trim() || null,
                allergies_other: (document.getElementById('opdAssessAllergiesOther')?.value || '').toString().trim() || null,
                family_history: (document.getElementById('opdAssessFamilyHistory')?.value || '').toString().trim() || null,
                social: {
                    smoking: (document.querySelector('input[name="opdAssessSmoking"]:checked')?.value || '').toString() || null,
                    alcohol: (document.querySelector('input[name="opdAssessAlcohol"]:checked')?.value || '').toString() || null,
                    occupation: (document.getElementById('opdAssessOccupation')?.value || '').toString().trim() || null,
                }
            };

            assessment.allergies_none = !assessment.allergies_other;

            const notes = (document.getElementById('opdAssessNotes')?.value || '').toString().trim();

            const body = {
                appointment_id: Number(appt.id),
                nurse_name: nurseName || null,
                vitals: Object.keys(vitals).length ? vitals : null,
                assessment,
                notes: notes || null,
            };

            const isEditing = !!opdAssessEditingId;
            const url = isEditing ? API_BASE_URL + '/opd_assessment/update.php' : API_BASE_URL + '/opd_assessment/create.php';
            const reqBody = isEditing ? { ...body, id: Number(opdAssessEditingId) } : body;

            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(reqBody),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showOpdAssessAlert('err', (json && json.error) ? json.error : (isEditing ? 'Failed to update assessment' : 'Failed to save assessment'));
                return;
            }

            if (isEditing) {
                setOpdAssessEditMode(null);
            }

            const alertEl = document.getElementById('opdAssessAlert');
            if (alertEl) alertEl.classList.add('hidden');
            toggleModal('opdAssessSavedModal');
            window.setTimeout(() => {
                const m = document.getElementById('opdAssessSavedModal');
                if (m && !m.classList.contains('hidden')) {
                    toggleModal('opdAssessSavedModal');
                }
            }, 1200);
            await loadOpdAssessments(Number(appt.id));
        }

        async function openOpdAssessHistoryModal() {
            const history = document.getElementById('opdAssessHistory');
            toggleModal('opdAssessHistoryModal');
            if (history) history.textContent = 'Loading...';
            const q = (document.getElementById('opdAssessHistorySearch')?.value || '').toString();
            await loadOpdAssessmentsAll(q);
        }

        async function rejectOpdRequest(id) {
            await updateOpdAppointmentStatus(id, 'rejected');
            await loadOpdAppointmentRequests();
        }

        async function updateOpdAppointmentStatus(id, status) {
            const res = await fetch(API_BASE_URL + '/opd/update_status.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ id, status })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                Toastify({
                    text: (json && json.error) ? json.error : 'Failed to update status',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
                return;
            }
            await loadOpdAppointmentsForToday();
        }

        async function viewAppointmentDetails(appointmentId) {
            let appointment = (opdAppointments || []).find(a => Number(a.id) === Number(appointmentId));
            if (!appointment) {
                const res = await fetch(API_BASE_URL + '/opd/get.php?id=' + encodeURIComponent(String(appointmentId)), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) return;
                appointment = json.appointment;
            }
            if (!appointment) return;

            const content = document.getElementById('appointmentDetailsContent');
            if (!content) return;
            const chip = opdApptStatusChip(appointment.status);

            const formatApptDate = (dateTimeStr) => {
                const s = (dateTimeStr ?? '').toString();
                const d = new Date(s.replace(' ', 'T'));
                if (Number.isNaN(d.getTime())) return '';
                return d.toLocaleDateString([], { year: 'numeric', month: 'short', day: '2-digit' });
            };

            const parseJsonMaybe = (v) => {
                if (!v) return null;
                if (typeof v === 'object') return v;
                try { return JSON.parse(String(v)); } catch (e) { return null; }
            };

            const labCatalog = {
                bun: 'BUN',
                cbc: 'Complete Blood Count (CBC)',
                creatinine: 'Creatinine',
                electrolytes: 'Electrolytes (Na/K/Cl)',
                fbs: 'Fasting Blood Sugar (FBS)',
                pregnancy: 'Pregnancy Test',
                rbs: 'Random Blood Sugar (RBS)',
                urinalysis: 'Urinalysis',
            };

            const labTests = (() => {
                const arr = parseJsonMaybe(appointment.lab_tests_json);
                if (!Array.isArray(arr)) return [];
                return arr.map(x => (x ?? '').toString().trim().toLowerCase()).filter(Boolean);
            })();
            const labNote = (appointment.lab_note ?? '').toString().trim();
            const labTestsLabel = labTests.length
                ? labTests.map(code => escapeHtml(labCatalog[code] || code)).join(', ')
                : '-';
            const apptDate = formatApptDate(appointment.appointment_at);

            content.innerHTML = `
                <div class="space-y-5">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <div>
                            <div class="text-xs text-gray-500">Appointment</div>
                            <div class="text-lg font-extrabold text-gray-900">${escapeHtml(appointment.full_name || '')}</div>
                            <div class="text-sm text-gray-600">${escapeHtml(appointment.patient_code || '')}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full border border-gray-200 bg-white text-gray-700">${escapeHtml(apptDate || '-')} · ${escapeHtml(formatTime12h(appointment.appointment_at) || '-')}</span>
                            <span class="px-3 py-1 text-xs font-semibold rounded-full ${chip.cls}">${chip.label}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-bold text-gray-900">Patient</div>
                                <div class="text-xs text-gray-500">Info</div>
                            </div>
                            <div class="mt-3 space-y-3">
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">Name</div>
                                    <div class="text-sm font-semibold text-gray-900">${escapeHtml(appointment.full_name || '-')}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">Patient ID</div>
                                    <div class="text-sm font-semibold text-gray-900">${escapeHtml(appointment.patient_code || '-')}</div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-xl p-4">
                            <div class="flex items-center justify-between">
                                <div class="text-sm font-bold text-gray-900">Appointment</div>
                                <div class="text-xs text-gray-500">Details</div>
                            </div>
                            <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">Doctor</div>
                                    <div class="text-sm font-semibold text-gray-900">${escapeHtml(appointment.doctor_name || '-')}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">Approved by</div>
                                    <div class="text-sm font-semibold text-gray-900">${escapeHtml(appointment.approved_by_name || '-')}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">Date</div>
                                    <div class="text-sm font-semibold text-gray-900">${escapeHtml(apptDate || '-')}</div>
                                </div>
                                <div>
                                    <div class="text-[11px] uppercase tracking-wide text-gray-500">Time</div>
                                    <div class="text-sm font-semibold text-gray-900">${escapeHtml(formatTime12h(appointment.appointment_at) || '-')}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="text-sm font-bold text-gray-900">Notes</div>
                        <div class="mt-2 text-sm text-gray-700 whitespace-pre-wrap">${escapeHtml(((appointment.notes || '') + '').trim() || '-')}</div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-bold text-gray-900">Laboratory</div>
                            <div class="text-xs text-gray-500">Request</div>
                        </div>
                        <div class="mt-3">
                            <div class="text-[11px] uppercase tracking-wide text-gray-500">Selected tests</div>
                            <div class="mt-1 text-sm font-semibold text-gray-900">${labTestsLabel}</div>
                        </div>
                        <div class="mt-3">
                            <div class="text-[11px] uppercase tracking-wide text-gray-500">Lab note</div>
                            <div class="mt-1 text-sm text-gray-700 whitespace-pre-wrap break-words max-h-20 overflow-auto pr-1">${escapeHtml(labNote || '-')}</div>
                        </div>
                    </div>
                </div>
            `;
            toggleModal('viewAppointmentModal');
        }

        // Modal toggle function
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
                if (modalId === 'scheduleAppointmentModal' && !modal.classList.contains('hidden')) {
                    loadOpdDoctors();
                    setTimeout(refreshOpdDoctorAvailability, 0);
                }
            }
        }

        function escapeHtml(s) {
            return (s ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function calcAgeFromDob(dob) {
            if (!dob || typeof dob !== 'string') return '';
            const m = dob.match(/^(\d{4})-(\d{2})-(\d{2})/);
            if (!m) return '';
            const y = parseInt(m[1], 10);
            const mo = parseInt(m[2], 10) - 1;
            const d = parseInt(m[3], 10);
            const birth = new Date(y, mo, d);
            if (isNaN(birth.getTime())) return '';
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const mDiff = today.getMonth() - birth.getMonth();
            if (mDiff < 0 || (mDiff === 0 && today.getDate() < birth.getDate())) age--;
            if (age < 0 || age > 150) return '';
            return String(age);
        }

        function dateOnlyFromDateTime(s) {
            const str = (s || '').toString();
            const m = str.match(/^(\d{4}-\d{2}-\d{2})/);
            return m ? m[1] : str;
        }

        async function loadOpdConsultPatientDetails(patientId) {
            const id = Number(patientId);
            if (!Number.isFinite(id) || id <= 0) return null;
            try {
                const res = await fetch(API_BASE_URL + '/patients/list.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                const p = (res.ok && json && json.ok && Array.isArray(json.patients) && json.patients[0]) ? json.patients[0] : null;
                return p;
            } catch (e) {
                return null;
            }
        }

        function setOpdConsultPatientInfo(appt, patientRow) {
            const nameEl = document.getElementById('opdConsultPatientName');
            const dobAgeEl = document.getElementById('opdConsultPatientDobAge');
            const genderEl = document.getElementById('opdConsultPatientGender');
            const visitEl = document.getElementById('opdConsultVisitDate');

            const fullName = appt && appt.full_name ? String(appt.full_name) : '';
            const dob = patientRow && patientRow.dob ? String(patientRow.dob) : '';
            const sex = patientRow && (patientRow.sex || patientRow.gender) ? String(patientRow.sex || patientRow.gender) : '';
            const age = calcAgeFromDob(dob);
            const dobAge = dob ? (age ? (dob + ' / ' + age) : dob) : (age ? age : '');

            if (nameEl) nameEl.value = fullName;
            if (dobAgeEl) dobAgeEl.value = dobAge;
            if (genderEl) genderEl.value = sex;

            if (visitEl && !visitEl.value) {
                const d = appt && appt.appointment_at ? dateOnlyFromDateTime(appt.appointment_at) : '';
                visitEl.value = d;
            }
        }

        const OPD_DEFAULT_TESTS = [
            { test_code: 'cbc', test_name: 'Complete Blood Count (CBC)', price: null },
            { test_code: 'urinalysis', test_name: 'Urinalysis', price: null },
            { test_code: 'rbs', test_name: 'Random Blood Sugar (RBS)', price: null },
            { test_code: 'ecg', test_name: 'Electrocardiogram (ECG)', price: null },
            { test_code: 'xray', test_name: 'X-Ray', price: null },
        ];

        let OPD_DOCTORS = [];

        function availabilityChip(status, effectiveAvailable) {
            const s = (status ?? '').toString();
            if (effectiveAvailable === false) {
                if (s === 'on_leave') return { cls: 'bg-red-50 text-red-700 border border-red-200', label: 'On Leave' };
                if (s === 'busy') return { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', label: 'Busy' };
                return { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', label: 'Not Available' };
            }
            if (s === 'on_leave') return { cls: 'bg-red-50 text-red-700 border border-red-200', label: 'On Leave' };
            if (s === 'busy') return { cls: 'bg-yellow-50 text-yellow-700 border border-yellow-200', label: 'Busy' };
            return { cls: 'bg-green-50 text-green-700 border border-green-200', label: 'Available' };
        }

        function getOpdSelectedDoctorId() {
            const el = document.getElementById('opdApptDoctor');
            if (!el) return null;
            const v = (el.value || '').toString().trim();
            if (!v) return null;
            if (/^\d+$/.test(v)) return Number(v);
            return null;
        }

        function getOpdSelectedDoctor() {
            const id = getOpdSelectedDoctorId();
            if (!id) return null;
            return (Array.isArray(OPD_DOCTORS) ? OPD_DOCTORS : []).find(d => Number(d.id) === Number(id)) || null;
        }

        async function loadOpdDoctors() {
            const sel = document.getElementById('opdApptDoctor');
            if (!sel) return;

            try {
                const res = await fetch(API_BASE_URL + '/opd/list_doctors.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !Array.isArray(json.doctors)) {
                    throw new Error('Failed');
                }

                OPD_DOCTORS = json.doctors;

                const prev = (sel.value || '').toString();
                sel.innerHTML = '<option value="">Select doctor</option>';
                OPD_DOCTORS.forEach(d => {
                    const opt = document.createElement('option');
                    opt.value = String(d.id);
                    opt.textContent = (d.full_name || d.username || '').toString();
                    sel.appendChild(opt);
                });
                if (prev) sel.value = prev;
            } catch (e) {
                sel.innerHTML = '<option value="">Unable to load doctors</option>';
                OPD_DOCTORS = [];
            }

            refreshOpdDoctorAvailability();
        }

        async function refreshOpdDoctorAvailability() {
            const doc = getOpdSelectedDoctor();
            const selectedEl = document.getElementById('opdApptDoctorSelected');
            const avEl = document.getElementById('opdApptDoctorAvailability');
            if (selectedEl) selectedEl.textContent = '';
            if (avEl) avEl.innerHTML = '';

            if (selectedEl) {
                if (doc) {
                    const username = (doc.username || '').toString();
                    selectedEl.textContent = username ? ('Selected: ' + (doc.full_name || '') + ' (' + username + ')') : ('Selected: ' + (doc.full_name || ''));
                } else {
                    selectedEl.textContent = '';
                }
            }

            const doctorId = getOpdSelectedDoctorId();
            if (!doctorId) return;

            if (avEl) {
                avEl.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-gray-50 text-gray-700 border border-gray-200">Checking availability...</span>';
            }

            try {
                const url = API_BASE_URL + '/opd/check_doctor_availability.php?doctor_id=' + encodeURIComponent(String(doctorId));
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) throw new Error('Failed');

                const status = (json.availability && json.availability.status) ? String(json.availability.status) : 'available';
                const effective = (json.effective_available === undefined) ? true : !!json.effective_available;
                const chip = availabilityChip(status, effective);
                const reason = (json.reason || '').toString();
                const updatedAt = (json.availability && json.availability.updated_at) ? String(json.availability.updated_at) : '';
                const conflict = !!(json.slot && json.slot.has_conflict);

                if (avEl) {
                    avEl.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${escapeHtml(chip.label)}</span>
                            ${reason ? `<span class="text-xs text-gray-500">${escapeHtml(reason)}</span>` : ''}
                        </div>
                        <div class="mt-1 text-xs text-gray-500">${updatedAt ? ('Last updated: ' + escapeHtml(updatedAt)) : ''}${conflict ? (updatedAt ? ' • ' : '') + 'Slot conflict detected' : ''}</div>
                    `;
                }
            } catch (e) {
                if (avEl) {
                    avEl.innerHTML = '<span class="px-2 py-1 text-xs rounded-full bg-gray-50 text-gray-700 border border-gray-200">Availability unknown</span>';
                }
            }
        }

        function renderOpdTestList(tests) {
            const listEl = document.getElementById('opdTestList');
            if (!listEl) return;

            const rows = Array.isArray(tests) ? tests : [];
            if (rows.length === 0) {
                listEl.innerHTML = '<div class="text-sm text-gray-500">No laboratory fees configured. Configure in Price Master → Laboratory Fees.</div>';
                return;
            }

            listEl.innerHTML = rows.map(t => {
                const code = (t.test_code || '').toString().trim();
                const name = (t.test_name || '').toString().trim();
                const price = (t.price === null || t.price === undefined || t.price === '') ? null : Number(t.price);
                const label = price !== null && !Number.isNaN(price)
                    ? `${name} (${code.toUpperCase()}) - ₱${price.toFixed(2)}`
                    : `${name} (${code.toUpperCase()})`;
                return `
                    <label class="flex items-center gap-2 text-sm text-gray-800" data-test-label="1" data-test-code="${escapeHtml(code.toLowerCase())}" data-test-name="${escapeHtml(name.toLowerCase())}">
                        <input type="checkbox" class="opdTestChk" value="${escapeHtml(code.toLowerCase())}">
                        ${escapeHtml(label)}
                    </label>
                `;
            }).join('');
        }

        function filterOpdTestList(q) {
            const needle = (q || '').toString().trim().toLowerCase();
            document.querySelectorAll('#opdTestList [data-test-label="1"]').forEach(el => {
                if (!needle) {
                    el.classList.remove('hidden');
                    return;
                }
                const code = (el.getAttribute('data-test-code') || '').toLowerCase();
                const name = (el.getAttribute('data-test-name') || '').toLowerCase();
                const ok = code.includes(needle) || name.includes(needle);
                el.classList.toggle('hidden', !ok);
            });
        }

        async function loadOpdTestsFromFees() {
            let tests = [];
            try {
                const res = await fetch(API_BASE_URL + '/price_master/list_lab_fees.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (res.ok && json && json.ok && Array.isArray(json.fees)) {
                    tests = json.fees;
                }
            } catch (e) {}

            if (!Array.isArray(tests) || tests.length === 0) {
                tests = OPD_DEFAULT_TESTS;
            }

            tests = tests
                .filter(t => (t && (t.test_code || '').toString().trim() !== ''))
                .slice()
                .sort((a, b) => ((a.test_name || a.test_code || '').toString()).localeCompare((b.test_name || b.test_code || '').toString()));

            renderOpdTestList(tests);
            filterOpdTestList(document.getElementById('opdTestSearch')?.value || '');
        }

        function statusChip(status) {
            const s = (status ?? '').toString();
            if (s === 'pending_approval') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Pending Approval' };
            if (s === 'approved') return { cls: 'bg-blue-100 text-blue-800', label: 'Approved' };
            if (s === 'rejected') return { cls: 'bg-red-100 text-red-800', label: 'Rejected' };
            if (s === 'collected') return { cls: 'bg-purple-100 text-purple-800', label: 'Collected' };
            if (s === 'in_progress') return { cls: 'bg-indigo-100 text-indigo-800', label: 'In Progress' };
            if (s === 'completed') return { cls: 'bg-green-100 text-green-800', label: 'Completed' };
            if (s === 'cancelled') return { cls: 'bg-gray-100 text-gray-800', label: 'Cancelled' };
            return { cls: 'bg-gray-100 text-gray-800', label: s };
        }

        function showOpdLabAlert(type, text) {
            const el = document.getElementById('opdLabAlert');
            if (!el) return;
            el.classList.remove('hidden');
            el.classList.remove('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            el.classList.remove('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            if (type === 'ok') {
                el.classList.add('bg-green-50', 'text-green-700', 'border', 'border-green-200');
            } else {
                el.classList.add('bg-red-50', 'text-red-700', 'border', 'border-red-200');
            }
            el.textContent = text;
            try { el.scrollIntoView({ behavior: 'smooth', block: 'nearest' }); } catch (e) {}
        }

        async function opdPatientSearch(q, resultsElId, patientIdElId, inputElId, selectedElId) {
            const resultsEl = document.getElementById(resultsElId);
            if (!resultsEl) return;
            if (!q) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500 bg-white">Searching...</div>`;

            const res = await fetch(API_BASE_URL + '/patients/list.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>`;
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.classList.remove('hidden');
                resultsEl.innerHTML = `<div class="px-4 py-2 text-sm text-gray-500 bg-white">No result</div>`;
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = rows.map(p => {
                const name = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                const sex = escapeHtml(p.sex || '');
                const dob = escapeHtml(p.dob || '');
                const contact = escapeHtml(p.contact || '');
                return `
                    <button type="button" class="w-full text-left px-4 py-2 hover:bg-gray-50" data-id="${Number(p.id)}" data-name="${name}" data-code="${code}" data-sex="${sex}" data-dob="${dob}" data-contact="${contact}">
                        <div class="text-sm font-medium text-gray-900">${name}</div>
                        <div class="text-xs text-gray-500">${code}${sex ? ' • ' + sex : ''}${dob ? ' • DOB: ' + dob : ''}${contact ? ' • ' + contact : ''}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-id');
                    const name = btn.getAttribute('data-name') || '';
                    const code = btn.getAttribute('data-code') || '';
                    const sex = btn.getAttribute('data-sex') || '';
                    const dob = btn.getAttribute('data-dob') || '';
                    const contact = btn.getAttribute('data-contact') || '';
                    const pidEl = document.getElementById(patientIdElId);
                    const inputEl = document.getElementById(inputElId);
                    const selectedEl = document.getElementById(selectedElId);
                    if (pidEl) pidEl.value = id;
                    if (inputEl) inputEl.value = code;
                    if (selectedEl) {
                        let extra = '';
                        if (sex) extra += (extra ? ' • ' : ' • ') + 'Sex: ' + sex;
                        if (dob) extra += (extra ? ' • ' : ' • ') + 'DOB: ' + dob;
                        if (contact) extra += (extra ? ' • ' : ' • ') + 'Contact: ' + contact;
                        selectedEl.textContent = 'Selected: ' + name + ' (' + code + ')' + extra;
                    }
                    resultsEl.classList.add('hidden');
                    resultsEl.innerHTML = '';
                });
            });
        }

        async function loadOpdLabRequests() {
            const tbody = document.getElementById('opdLabRequestsTbody');
            if (!tbody) return;

            const res = await fetch(API_BASE_URL + '/lab/list_requests.php?mode=opd', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            tbody.innerHTML = rows.map(r => {
                const chip = statusChip(r.status);
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const name = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${name}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.chief_complaint || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openOpdLabDetails(${Number(r.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openOpdLabDetails(id) {
            const content = document.getElementById('opdLabDetailsContent');
            if (!content) return;

            const res = await fetch(API_BASE_URL + '/lab/get_request.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                toggleModal('opdLabDetailsModal');
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];

            content.innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Request</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(r.request_no || '')}</div>
                            <div class="text-xs text-gray-500">Status: ${escapeHtml(r.status || '')}</div>
                            <div class="text-xs text-gray-500">Source: ${escapeHtml(r.source_unit || '')}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="text-sm font-semibold text-gray-800">Patient</div>
                            <div class="text-sm text-gray-700 mt-2">${escapeHtml(r.full_name || '')}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.patient_code || '')}</div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
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

            toggleModal('opdLabDetailsModal');
        }

        function setOpdView(view) {
            const secOverview = document.getElementById('opdOverviewSection');
            const secAppointments = document.getElementById('opdAppointmentsSection');
            const secApptRequests = document.getElementById('opdAppointmentRequestsSection');
            const secNursing = document.getElementById('opdNursingAssessmentSection');
            const secConsult = document.getElementById('opdConsultationNotesSection');
            const secXray = document.getElementById('opdXraySection');
            const secNewLab = document.getElementById('opdNewLabRequestSection');
            const secLabReq = document.getElementById('opdLabRequestsSection');
            const secLabResults = document.getElementById('opdLabResultsSection');
            const titleEl = document.getElementById('opdPageTitle');

            const sections = [
                { el: secOverview, key: 'overview' },
                { el: secAppointments, key: 'appointments' },
                { el: secApptRequests, key: 'appointment-requests' },
                { el: secNursing, key: 'nursing-assessment' },
                { el: secConsult, key: 'consultation-notes' },
                { el: secXray, key: 'xray' },
                { el: secNewLab, key: 'lab-new' },
                { el: secLabReq, key: 'lab-requests' },
                { el: secLabResults, key: 'lab-results' },
            ];

            sections.forEach(s => {
                if (!s.el) return;
                s.el.classList.toggle('hidden', view !== s.key);
            });

            if (titleEl) {
                const titles = {
                    'overview': 'Overview',
                    'appointments': 'Appointments',
                    'appointment-requests': 'Appointment Requests',
                    'nursing-assessment': 'Nursing Assessment',
                    'consultation-notes': 'Consultation Notes',
                    'xray': 'X-ray',
                    'lab-new': 'New Lab Request',
                    'lab-requests': 'Lab Requests',
                    'lab-results': 'Lab Test Result',
                };
                titleEl.textContent = titles[view] || 'Out-Patient Department';
            }

            if (view === 'overview') {
                loadOpdAppointmentsForToday();
            }

            if (view === 'appointments') {
                loadOpdAppointmentsAll();
            }

            if (view === 'appointment-requests') {
                loadOpdAppointmentRequests();
            }

            if (view === 'lab-requests') {
                loadOpdLabRequests();
            }

            if (view === 'lab-results') {
                loadOpdLabResults();
            }

            if (view === 'consultation-notes') {
                loadOpdConsultAppointments();
            }

            if (view === 'xray') {
                (async () => {
                    try {
                        if (!window.__xrayInstalledOnce) {
                            window.__xrayInstalledOnce = true;
                            await fetch(API_BASE_URL + '/xray/install.php', { headers: { 'Accept': 'application/json' } });
                        }
                    } catch (e) {
                    }
                    if (window.xrayResultsRelease && typeof window.xrayResultsRelease.render === 'function') {
                        await window.xrayResultsRelease.render();
                    }
                })();
            }

            if (view === 'billing') {
                setOpdView('overview');
                return;
            }
        }

        function applyOpdViewFromHash() {
            const h = (window.location.hash || '').toLowerCase();
            if (h === '#appointments') setOpdView('appointments');
            else if (h === '#appointment-requests') setOpdView('appointment-requests');
            else if (h === '#nursing-assessment') setOpdView('nursing-assessment');
            else if (h === '#consultation-notes') setOpdView('consultation-notes');
            else if (h === '#xray') setOpdView('xray');
            else if (h === '#lab-new') setOpdView('lab-new');
            else if (h === '#lab-requests') setOpdView('lab-requests');
            else if (h === '#lab-results') setOpdView('lab-results');
            else setOpdView('overview');
        }

        let opdSearchTimer = null;
        const opdPatientSearchEl = document.getElementById('opdPatientSearch');
        if (opdPatientSearchEl) {
            opdPatientSearchEl.addEventListener('input', (e) => {
                const q = e.target.value.trim();
                clearTimeout(opdSearchTimer);
                opdSearchTimer = setTimeout(() => opdPatientSearch(q, 'opdPatientResults', 'opdPatientId', 'opdPatientSearch', 'opdPatientSelected'), 250);
            });
        }

        let opdApptSearchTimer = null;
        const opdApptPatientSearchEl = document.getElementById('opdApptPatientSearch');
        if (opdApptPatientSearchEl) {
            opdApptPatientSearchEl.addEventListener('input', (e) => {
                const q = e.target.value.trim();
                clearTimeout(opdApptSearchTimer);
                opdApptSearchTimer = setTimeout(() => opdPatientSearch(q, 'opdApptPatientResults', 'opdApptPatientId', 'opdApptPatientSearch', 'opdApptPatientSelected'), 250);
            });
        }

        const opdApptDoctorEl = document.getElementById('opdApptDoctor');
        if (opdApptDoctorEl) {
            opdApptDoctorEl.addEventListener('change', refreshOpdDoctorAvailability);
        }

        const refreshLabBtn = document.getElementById('refreshOpdLabRequests');
        if (refreshLabBtn) refreshLabBtn.addEventListener('click', loadOpdLabRequests);

        const btnOpdNewLabRequest = document.getElementById('btnOpdNewLabRequest');
        if (btnOpdNewLabRequest) {
            btnOpdNewLabRequest.addEventListener('click', () => {
                try {
                    window.location.hash = '#lab-new';
                } catch (e) {
                    setOpdView('lab-new');
                }
            });
        }

        const refreshLabResultsBtn = document.getElementById('refreshOpdLabResults');
        if (refreshLabResultsBtn) refreshLabResultsBtn.addEventListener('click', loadOpdLabResults);

        const refreshApptBtn = document.getElementById('refreshOpdAppointments');
        if (refreshApptBtn) {
            refreshApptBtn.addEventListener('click', () => {
                loadOpdAppointmentsAll();
            });
        }

        const opdAppointmentsSearchEl = document.getElementById('opdAppointmentsSearch');
        if (opdAppointmentsSearchEl) {
            opdAppointmentsSearchEl.addEventListener('input', (e) => {
                opdAppointmentsSearchQuery = (e && e.target && e.target.value ? String(e.target.value) : '').trim();
                renderOpdAppointments();
            });
        }

        const refreshApptReqBtn = document.getElementById('refreshOpdAppointmentRequests');
        if (refreshApptReqBtn) refreshApptReqBtn.addEventListener('click', loadOpdAppointmentRequests);

        const opdAssessAppointmentSearchEl = document.getElementById('opdAssessAppointmentSearch');
        if (opdAssessAppointmentSearchEl) {
            opdAssessAppointmentSearchEl.addEventListener('input', (e) => {
                opdAssessAppointmentQuery = (e.target && e.target.value ? String(e.target.value) : '').trim();
                const idEl = document.getElementById('opdAssessAppointmentId');
                const pidEl = document.getElementById('opdAssessPatientId');
                opdAssessSelectedAppointment = null;
                opdAssessAppointments = [];
                if (idEl) idEl.value = '';
                if (pidEl) pidEl.value = '';
                renderOpdAssessPatientOptions();
            });
        }

        const opdConsultAppointmentSearchEl = document.getElementById('opdConsultAppointmentSearch');
        if (opdConsultAppointmentSearchEl) {
            opdConsultAppointmentSearchEl.addEventListener('input', (e) => {
                opdConsultAppointmentQuery = (e.target && e.target.value ? String(e.target.value) : '').trim();
                const idEl = document.getElementById('opdConsultAppointmentId');
                if (idEl) idEl.value = '';
                renderOpdConsultAppointmentOptions();
                handleOpdConsultAppointmentChange();
            });
        }

        const opdConsultForm = document.getElementById('opdConsultForm');
        if (opdConsultForm) {
            opdConsultForm.addEventListener('submit', (e) => {
                e.preventDefault();
                submitOpdConsultNote();
            });
        }

        const opdConsultCancelEditBtn = document.getElementById('opdConsultCancelEditBtn');
        if (opdConsultCancelEditBtn) {
            opdConsultCancelEditBtn.addEventListener('click', () => {
                cancelOpdConsultEdit();
            });
        }

        const opdConsultHistoryBtn = document.getElementById('opdConsultHistoryBtn');
        if (opdConsultHistoryBtn) {
            opdConsultHistoryBtn.addEventListener('click', async () => {
                await openOpdConsultHistoryModal();
            });
        }

        const opdConsultHistoryModalRefreshBtn = document.getElementById('opdConsultHistoryModalRefreshBtn');
        if (opdConsultHistoryModalRefreshBtn) {
            opdConsultHistoryModalRefreshBtn.addEventListener('click', async () => {
                const appt = getSelectedConsultAppointment();
                const q = (document.getElementById('opdConsultHistorySearch')?.value || '').toString();
                await loadOpdConsultNotesForModal(appt ? Number(appt.id) : null, q);
            });
        }

        const opdConsultHistorySearchEl = document.getElementById('opdConsultHistorySearch');
        if (opdConsultHistorySearchEl) {
            let t = null;
            opdConsultHistorySearchEl.addEventListener('input', (e) => {
                const q = (e && e.target && e.target.value) ? String(e.target.value) : '';
                if (t) window.clearTimeout(t);
                t = window.setTimeout(async () => {
                    const modalOpen = !document.getElementById('opdConsultHistoryModal')?.classList.contains('hidden');
                    if (!modalOpen) return;
                    const appt = getSelectedConsultAppointment();
                    await loadOpdConsultNotesForModal(appt ? Number(appt.id) : null, q);
                }, 250);
            });
        }

        const opdConsultHistoryModalSubmitPharmacyBtn = document.getElementById('opdConsultHistoryModalSubmitPharmacyBtn');
        if (opdConsultHistoryModalSubmitPharmacyBtn) {
            opdConsultHistoryModalSubmitPharmacyBtn.addEventListener('click', async () => {
                await submitSelectedOpdConsultHistoryToPharmacy();
            });
        }

        const opdConsultHistoryModalEditBtn = document.getElementById('opdConsultHistoryModalEditBtn');
        if (opdConsultHistoryModalEditBtn) {
            opdConsultHistoryModalEditBtn.addEventListener('click', async () => {
                await editSelectedOpdConsultHistoryNote();
            });
        }

        const opdConsultAutoFillBtn = document.getElementById('opdConsultAutoFillBtn');
        if (opdConsultAutoFillBtn) {
            opdConsultAutoFillBtn.addEventListener('click', async () => {
                const appt = getSelectedConsultAppointment();
                if (!appt) {
                    showOpdConsultAlert('err', 'Select an appointment');
                    return;
                }

                opdConsultAutoFillBtn.disabled = true;
                try {
                    const fields = collectOpdConsultAiFields();
                    const seed = String(appt.patient_id || appt.id || '') + '|' + String(appt.id || '');
                    const res = await fetch(API_BASE_URL + '/philhealth/ai_fill.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ page: 'consultation_notes_opd', seed, fields }),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'AI fill failed');
                    }
                    applyAiValuesToOpdConsult(fields, json.values || []);
                    showOpdConsultAlert('ok', 'Auto fill complete');
                } catch (e) {
                    showOpdConsultAlert('err', e && e.message ? e.message : 'AI fill failed');
                } finally {
                    opdConsultAutoFillBtn.disabled = false;
                }
            });
        }

        const opdBillingAppointmentSearchEl = document.getElementById('opdBillingAppointmentSearch');
        if (opdBillingAppointmentSearchEl) {
            opdBillingAppointmentSearchEl.addEventListener('input', (e) => {
                opdBillingAppointmentQuery = (e.target && e.target.value ? String(e.target.value) : '').trim();
                const idEl = document.getElementById('opdBillingAppointmentId');
                if (idEl) idEl.value = '';
                renderOpdBillingAppointmentOptions();
                handleOpdBillingAppointmentChange();
            });
        }

        const opdBillingItemForm = document.getElementById('opdBillingItemForm');
        if (opdBillingItemForm) {
            opdBillingItemForm.addEventListener('submit', (e) => {
                e.preventDefault();
                submitOpdBillingItem();
            });
        }

        const opdAssessHistoryBtn = document.getElementById('opdAssessHistoryBtn');
        if (opdAssessHistoryBtn) {
            opdAssessHistoryBtn.addEventListener('click', () => {
                openOpdAssessHistoryModal();
            });
        }

        const opdAssessHistoryRefreshBtn = document.getElementById('opdAssessHistoryRefreshBtn');
        if (opdAssessHistoryRefreshBtn) {
            opdAssessHistoryRefreshBtn.addEventListener('click', () => {
                const q = (document.getElementById('opdAssessHistorySearch')?.value || '').toString();
                loadOpdAssessmentsAll(q);
            });
        }

        const opdAssessHistorySearchEl = document.getElementById('opdAssessHistorySearch');
        if (opdAssessHistorySearchEl) {
            let t = null;
            opdAssessHistorySearchEl.addEventListener('input', (e) => {
                const q = (e && e.target && e.target.value) ? String(e.target.value) : '';
                if (t) window.clearTimeout(t);
                t = window.setTimeout(() => {
                    loadOpdAssessmentsAll(q);
                }, 250);
            });
        }

        const opdAssessForm = document.getElementById('opdAssessForm');
        if (opdAssessForm) {
            opdAssessForm.addEventListener('submit', (e) => {
                e.preventDefault();
                submitOpdAssessment();
            });
        }

        const opdLabForm = document.getElementById('opdLabForm');
        if (opdLabForm) {
            opdLabForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const patientId = (document.getElementById('opdPatientId')?.value || '').toString().trim();
                const chiefComplaint = (document.getElementById('opdChiefComplaint')?.value || '').toString().trim();
                const priority = (document.getElementById('opdPriority')?.value || 'routine').toString();
                const requestedBy = (document.getElementById('opdRequestedBy')?.value || '').toString().trim();
                const requesterRole = (document.getElementById('opdRequesterRole')?.value || 'np_pa').toString();
                const notes = (document.getElementById('opdNotes')?.value || '').toString().trim();
                const tests = Array.from(document.querySelectorAll('.opdTestChk')).filter(x => x.checked).map(x => x.value);

                if (!patientId) {
                    showOpdLabAlert('err', 'Select a patient');
                    return;
                }
                if (!chiefComplaint) {
                    showOpdLabAlert('err', 'Enter chief complaint');
                    return;
                }
                if (tests.length === 0) {
                    showOpdLabAlert('err', 'Select at least 1 test');
                    return;
                }

                const body = {
                    patient_id: Number(patientId),
                    source_unit: 'OPD',
                    encounter_type: 'OPD',
                    triage_level: 5,
                    chief_complaint: chiefComplaint,
                    priority,
                    requested_by: requestedBy,
                    requester_role: requesterRole,
                    notes,
                    vitals: null,
                    tests,
                };

                const res = await fetch(API_BASE_URL + '/lab/create_request.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    showOpdLabAlert('err', (json && json.error) ? json.error : 'Failed to submit request');
                    return;
                }

                const reqNo = (json.request?.request_no || 'Request created').toString();
                showOpdLabAlert('ok', 'OPD lab request created: ' + reqNo);
                e.target.reset();
                const pid = document.getElementById('opdPatientId');
                const sel = document.getElementById('opdPatientSelected');
                const input = document.getElementById('opdPatientSearch');
                if (pid) pid.value = '';
                if (sel) sel.textContent = '';
                if (input) input.value = '';
            });
        }

        window.openOpdLabDetails = openOpdLabDetails;
        window.deleteOpdBillingItem = deleteOpdBillingItem;
        window.addEventListener('hashchange', applyOpdViewFromHash);
        applyOpdViewFromHash();

        let opdTestSearchTimer = null;
        const opdTestSearchEl = document.getElementById('opdTestSearch');
        if (opdTestSearchEl) {
            opdTestSearchEl.addEventListener('input', (e) => {
                const q = (e.target.value || '').toString();
                clearTimeout(opdTestSearchTimer);
                opdTestSearchTimer = setTimeout(() => filterOpdTestList(q), 150);
            });
        }

        loadOpdTestsFromFees();

        // Form Submissions
        const scheduleApptForm = document.getElementById('scheduleAppointmentForm');
        if (scheduleApptForm) scheduleApptForm.addEventListener('submit', function(e) {
            e.preventDefault();
            (async () => {
                const patientId = (document.getElementById('opdApptPatientId')?.value || '').toString().trim();
                const doctorId = (document.getElementById('opdApptDoctor')?.value || '').toString().trim();
                const doc = getOpdSelectedDoctor();
                const doctorName = doc ? (doc.full_name || '').toString().trim() : '';
                const notes = (document.getElementById('opdApptNotes')?.value || '').toString().trim();

                if (!patientId) {
                    Toastify({ text: 'Select a patient', duration: 3000, gravity: 'top', position: 'right', backgroundColor: '#EF4444' }).showToast();
                    return;
                }
                if (!doctorId || !doctorName) {
                    Toastify({ text: 'Select a doctor', duration: 3000, gravity: 'top', position: 'right', backgroundColor: '#EF4444' }).showToast();
                    return;
                }
                try {
                    const avUrl = API_BASE_URL + '/opd/check_doctor_availability.php?doctor_id=' + encodeURIComponent(String(doctorId));
                    const avRes = await fetch(avUrl, { headers: { 'Accept': 'application/json' } });
                    const avJson = await avRes.json().catch(() => null);
                    if (avRes.ok && avJson && avJson.ok) {
                        const effective = (avJson.effective_available === undefined) ? true : !!avJson.effective_available;
                        if (!effective) {
                            const reason = (avJson.reason || '').toString().trim();
                            Toastify({ text: reason || 'Doctor is not available. Please select another doctor.', duration: 3500, gravity: 'top', position: 'right', backgroundColor: '#EF4444' }).showToast();
                            return;
                        }
                    }
                } catch (e) {
                }

                const status = 'requested';
                const appointmentAt = null;
                const nursingAssessmentId = (document.getElementById('opdApptNursingAssessmentId')?.value || '').toString().trim();

                const body = {
                    patient_id: Number(patientId),
                    doctor_name: doctorName,
                    notes,
                    status,
                    appointment_at: appointmentAt,
                    nursing_assessment_id: nursingAssessmentId ? Number(nursingAssessmentId) : null,
                };

                const res = await fetch(API_BASE_URL + '/opd/create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(body)
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    Toastify({
                        text: (json && json.error) ? json.error : 'Failed to send request',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#EF4444',
                    }).showToast();
                    return;
                }

                Toastify({
                    text: status === 'scheduled' ? 'Appointment scheduled!' : 'Appointment request sent!',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#10B981',
                }).showToast();

                toggleModal('scheduleAppointmentModal');
                e.target.reset();

                const pid = document.getElementById('opdApptPatientId');
                const naid = document.getElementById('opdApptNursingAssessmentId');
                const sel = document.getElementById('opdApptPatientSelected');
                const input = document.getElementById('opdApptPatientSearch');
                const results = document.getElementById('opdApptPatientResults');
                if (pid) pid.value = '';
                if (naid) naid.value = '';
                if (sel) sel.textContent = '';
                if (input) input.value = '';
                if (results) { results.classList.add('hidden'); results.innerHTML = ''; }

                await loadOpdAppointmentsAll();
                await loadOpdAppointmentRequests();
            })();
        });

        window.updateOpdAppointmentStatus = updateOpdAppointmentStatus;
        window.viewAppointmentDetails = viewAppointmentDetails;
        window.rejectOpdRequest = rejectOpdRequest;

        // Queue Management Functions
        let currentQueueData = null;

        async function loadOpdQueue() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/display/1'); // OPD station ID is 1
                currentQueueData = await response.json();
                updateQueueDisplay();
            } catch (error) {
                console.error('Error loading OPD queue:', error);
            }
        }

        function updateQueueDisplay() {
            if (!currentQueueData) return;

            // Update currently serving
            const currentlyServingDiv = document.getElementById('currentlyServing');
            if (currentQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-lg border border-green-300 flex items-center gap-4">
                        <div class="relative h-16 w-16">
                            <div class="absolute h-12 w-12 left-[calc(50%-1.5rem)] top-[calc(50%-1.5rem)] bg-green-500 rounded animate-ping"></div>
                            <div class="relative h-full w-full bg-green-500 text-white text-2xl rounded-md flex flex-col items-center justify-center font-bold">
                                ${currentQueueData.currently_serving.queue_number}
                            </div>
                        </div>
                        <div class="flex flex-col items-start text-left">
                            <div class="text-2xl font-bold text-green-700 line-clamp-1">${currentQueueData.currently_serving.full_name}</div>
                            <div class="text-sm text-gray-600">${currentQueueData.currently_serving.patient_code || ''}</div>
                        </div>
                    </div>
                `;
                
                // Show station selection dropdown
                document.getElementById('stationSelection').classList.remove('hidden');
                loadStationOptions();
            } else {
                currentlyServingDiv.innerHTML = `
                    <div class="text-gray-500">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p>No patient being served</p>
                    </div>
                `;
                document.getElementById('stationSelection').classList.add('hidden');
            }

            // Update queue list
            const queueListDiv = document.getElementById('opdQueueList');
            if (currentQueueData.next_patients && currentQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentQueueData.next_patients.map((patient, index) => `
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

            // Update unavailable patients
            const unavailableDiv = document.getElementById('unavailablePatientsList');
            if (currentQueueData.unavailable_patients && currentQueueData.unavailable_patients.length > 0) {
                unavailableDiv.innerHTML = currentQueueData.unavailable_patients.map(patient => `
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded-lg border border-orange-200 cursor-pointer hover:bg-orange-100 transition-colors" onclick="recallUnavailablePatient(${patient.id})">
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

            // Update stats - check if elements exist before updating
            const waitingCountEl = document.getElementById('opdWaitingCount');
            if (waitingCountEl) {
                waitingCountEl.textContent = currentQueueData.queue_count || 0;
            }
            
            const unavailableCountEl = document.getElementById('unavailableCount');
            if (unavailableCountEl) {
                unavailableCountEl.textContent = currentQueueData.unavailable_patients ? currentQueueData.unavailable_patients.length : 0;
            }
            
            const avgWaitTimeEl = document.getElementById('opdAvgWaitTime');
            if (avgWaitTimeEl) {
                avgWaitTimeEl.textContent = `${currentQueueData.estimated_wait_time || 0} min`;
            }
        }

        // Modal functions for Send Patient functionality
        function openSendPatientModal() {
            if (!currentQueueData?.currently_serving) {
                Toastify({
                    text: 'Please call a patient first before sending to next station',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#F59E0B',
                }).showToast();
                return;
            }
            
            const modal = document.getElementById('sendPatientModal');
            modal.classList.remove('hidden');
            loadStationsForModal();
        }

        function closeSendPatientModal() {
            const modal = document.getElementById('sendPatientModal');
            modal.classList.add('hidden');
            // Reset selection
            document.getElementById('confirmSendBtn').disabled = true;
            document.querySelectorAll('.station-option').forEach(btn => {
                btn.classList.remove('ring-2', 'ring-blue-500', 'bg-blue-50');
            });
        }

        function loadStationsForModal() {
            const stationList = document.getElementById('stationList');
            stationList.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i> <p class="mt-2 text-lg">Loading stations...</p></div>';
            
            // Create discharge option (will be added at the end)
            const dischargeOption = document.createElement('div');
            dischargeOption.className = 'station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200 transform hover:scale-[1.02]';
            dischargeOption.onclick = () => selectStation('discharge', dischargeOption);
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
            
            // Add other stations first
            fetch(API_BASE_URL + '/queue/stations')
                .then(response => response.json())
                .then(data => {
                    stationList.innerHTML = '';
                    
                    // Add all other stations first
                    data.stations.forEach(station => {
                        if (station.id !== 1) { // Don't show current station
                            const stationOption = document.createElement('div');
                            stationOption.className = 'station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.02]';
                            stationOption.onclick = () => selectStation(station.id, stationOption);
                            
                            // Determine appropriate icon based on station name
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
                    
                    // Add discharge option at the end
                    stationList.appendChild(dischargeOption);
                })
                .catch(error => {
                    stationList.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p class="text-lg">Failed to load stations</p></div>';
                });
        }

        function selectStation(stationId, element) {
            // Remove previous selection
            document.querySelectorAll('.station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });
            
            // Add selection to clicked element with enhanced visual feedback
            if (stationId === 'discharge') {
                element.classList.add('ring-4', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            } else {
                element.classList.add('ring-4', 'ring-blue-500', 'bg-blue-50', 'shadow-lg');
            }
            
            // Enable send button
            document.getElementById('confirmSendBtn').disabled = false;
            document.getElementById('confirmSendBtn').onclick = () => sendPatientToStation(stationId);
        }

        async function sendPatientToStation(stationId) {
            if (!currentQueueData?.currently_serving) return;
            
            try {
                let endpoint = API_BASE_URL + '/queue/complete-service';
                let body = { 
                    queue_id: currentQueueData.currently_serving.id
                };
                
                if (stationId !== 'discharge') {
                    body.target_station_id = parseInt(stationId);
                }
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                
                const result = await response.json();
                
                // Check for success - handle both 'ok' and 'success' response formats
                const isSuccess = response.ok && (result.ok === true || result.success === true);
                
                if (isSuccess) {
                    Toastify({
                        text: stationId === 'discharge' ? 'Patient discharged successfully' : 'Patient sent to next station',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    
                    closeSendPatientModal();
                    loadOpdQueue(); // Use the correct queue refresh function
                } else {
                    // Handle different error response formats
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

        // Keep existing loadStationOptions for compatibility (but simplified)
        function loadStationOptions() {
            // This function is kept for compatibility but no longer needed
            // Station selection is now handled by the modal
        }

        async function callNextPatient() {
            try {
                // Check if there's already a patient being served
                if (currentQueueData?.currently_serving) {
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
                    body: JSON.stringify({ station_id: 1 })
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
                    loadOpdQueue();
                } else {
                    // Handle specific error messages and make them more user-friendly
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.includes('no active transaction')) {
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

        async function recallUnavailablePatient(queueId) {
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
                    loadOpdQueue();
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

        async function callNextAndMarkUnavailable() {
            try {
                // Use the new combined API endpoint
                const response = await fetch(API_BASE_URL + '/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        station_id: 1,
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
                    loadOpdQueue();
                } else {
                    // Handle specific error messages and make them more user-friendly
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.includes('no active transaction')) {
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

        // This function is deprecated - use sendPatientToStation instead
        async function completeService() {
            // Functionality moved to modal-based sendPatientToStation
            Toastify({
                text: 'Please use the "Send Patient" button instead',
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#F59E0B',
            }).showToast();
        }   

        function openDisplayScreen() {
            window.open('opd-display.php', '_blank');
        }

        // Initial load
        loadOpdQueue();

        // Subscribe to WebSocket for real-time queue updates
        HospitalWS.subscribe('queue-1');
        HospitalWS.subscribe('global');
        HospitalWS.on('queue_update', function() { loadOpdQueue(); });
        HospitalWS.on('fallback_poll', function() { loadOpdQueue(); });
    </script>
    <?php include __DIR__ . '/includes/xray-results-release-js.php'; ?>
    <?php include __DIR__ . '/includes/queue-error-report-modal.php'; ?>
    <?php include __DIR__ . '/includes/queue-error-correction.php'; ?>
    <script>window.qecStationId = 1; window.qecRefreshQueue = function() { loadOpdQueue(); };</script>
    <?php include __DIR__ . '/includes/queue-error-correction-js.php'; ?>
    <?php include __DIR__ . '/includes/queue-return-request.php'; ?>
    <script>window.qrrStationId = 1; window.qrrRefreshQueue = function() { loadOpdQueue(); };</script>
    <?php include __DIR__ . '/includes/queue-return-request-js.php'; ?>
    </body>
    </html>
