<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include __DIR__ . '/includes/websocket-client.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .chart-container {
            position: relative;
            width: 100%;
            height: 300px;
        }
    </style>
</head>
    <body class="bg-gray-50">
        <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <!-- Header -->
            <div class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Patient Monitoring</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <?php if (!isset($authUser) || !is_array($authUser) || !auth_user_has_module($authUser, 'OPD')): ?>
                        <button id="addNewBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700">
                            <i class="fas fa-plus"></i>
                            <span>Add Patient</span>
                        </button>
                    <?php endif; ?>
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-500"></i>
                    </button>
                    <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6">
                <div id="patientsDashboardView">
                    <!-- Modern Stats Section -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
                        <!-- Total Patients -->
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500/10 to-blue-600/10 opacity-50"></div>
                            <div class="relative p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center transform transition-transform group-hover:scale-110">
                                        <i class="fas fa-users text-white text-2xl"></i>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">
                                        +12%
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-baseline space-x-1">
                                        <h2 id="statTotalPatients" class="text-4xl font-bold text-gray-800">0</h2>
                                        <span class="text-sm font-medium text-gray-500">patients</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">Total Patients</p>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-blue-600 transform origin-left transition-transform group-hover:scale-x-100"></div>
                            </div>
                        </div>

                        <!-- In Treatment -->
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-emerald-600/10 opacity-50"></div>
                            <div class="relative p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-emerald-500 to-emerald-600 flex items-center justify-center transform transition-transform group-hover:scale-110">
                                        <i class="fas fa-procedures text-white text-2xl"></i>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-emerald-100 text-emerald-700">
                                        Active
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-baseline space-x-1">
                                        <h2 id="statInTreatment" class="text-4xl font-bold text-gray-800">0</h2>
                                        <span class="text-sm font-medium text-gray-500">patients</span>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">In Treatment</p>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600 transform origin-left transition-transform group-hover:scale-x-100"></div>
                            </div>
                        </div>

                        <!-- Waiting -->
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-amber-600/10 opacity-50"></div>
                            <div class="relative p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-amber-500 to-amber-600 flex items-center justify-center transform transition-transform group-hover:scale-110">
                                        <i class="fas fa-clock text-white text-2xl"></i>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                        <span class="text-xs font-semibold">Waiting</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-baseline space-x-1">
                                        <h2 id="statWaiting" class="text-4xl font-bold text-gray-800">0</h2>
                                        <span class="text-sm font-medium text-gray-500">patients</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5 mt-3">
                                        <div id="statWaitingBar" class="bg-amber-500 h-1.5 rounded-full" style="width: 0%"></div>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600 transform origin-left transition-transform group-hover:scale-x-100"></div>
                            </div>
                        </div>

                        <!-- Operating Room -->
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="absolute inset-0 bg-gradient-to-r from-purple-500/10 to-purple-600/10 opacity-50"></div>
                            <div class="relative p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-purple-500 to-purple-600 flex items-center justify-center transform transition-transform group-hover:scale-110">
                                        <i class="fas fa-bed text-white text-2xl"></i>
                                    </div>
                                    <div class="flex items-center space-x-1">
                                        <div class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></div>
                                        <span class="text-xs font-semibold">In Progress</span>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-baseline space-x-1">
                                        <h2 id="statSurgeries" class="text-4xl font-bold text-gray-800">0</h2>
                                        <span class="text-sm font-medium text-gray-500">surgeries</span>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-purple-500 to-purple-600 transform origin-left transition-transform group-hover:scale-x-100"></div>
                            </div>
                        </div>

                        <!-- Discharged -->
                        <div class="group relative bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                            <div class="absolute inset-0 bg-gradient-to-r from-teal-500/10 to-teal-600/10 opacity-50"></div>
                            <div class="relative p-6">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-teal-500 to-teal-600 flex items-center justify-center transform transition-transform group-hover:scale-110">
                                        <i class="fas fa-check-circle text-white text-2xl"></i>
                                    </div>
                                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-teal-100 text-teal-700">
                                        Today
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <div class="flex items-baseline space-x-1">
                                        <h2 id="statDischarged" class="text-4xl font-bold text-gray-800">0</h2>
                                        <span class="text-sm font-medium text-gray-500">discharged</span>
                                    </div>
                                </div>
                                <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 to-teal-600 transform origin-left transition-transform group-hover:scale-x-100"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Patient Flow Chart -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Patient Flow</h3>
                            <div class="chart-container">
                                <canvas id="patientFlowChart"></canvas>
                            </div>
                        </div>

                        <!-- Department Distribution -->
                        <div class="bg-white p-6 rounded-lg shadow">
                            <h3 class="text-lg font-semibold mb-4">Department Distribution</h3>
                            <div class="chart-container">
                                <canvas id="departmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="patientsProgressView" class="hidden">
                    <!-- Patient Status Table -->
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <div class="flex justify-between items-center">
                                <h3 class="text-lg font-semibold">Current Patients</h3>
                                <div class="flex space-x-2">
                                    <input id="patientSearch" type="text" placeholder="Search patients..." class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg">
                                        <i class="fas fa-filter"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Location</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Done Process</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Next Procedure</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    </tr>
                                </thead>
                                <tbody id="patientsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    <span id="patientsResultsLabel">Showing 0 results</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="patientsQueueView" class="hidden">
                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold">Patient Information</h3>
                                <p class="text-sm text-gray-500">Registered patients from kiosk and manual registration</p>
                            </div>
                            <button type="button" onclick="loadPatientQueue()" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                                Refresh
                            </button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Code</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Age/Sex</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purpose of Visit</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Registered At</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="patientQueueTbody" class="bg-white divide-y divide-gray-200"></tbody>
                            </table>
                        </div>
                        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-700">
                                    <span id="patientQueueResultsLabel">Showing 0 results</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Patient Modal -->
    <div id="patientModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 w-full max-w-6xl mx-4 transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent" id="modalTitle">Add New Patient</h3>
                <div class="flex items-center space-x-3">
                    <button id="autoFillAiBtn" type="button" class="px-3 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                        Auto Fill (AI)
                    </button>
                    <button onclick="toggleModal('patientModal')" class="text-gray-400 hover:text-gray-600 transition-colors" type="button">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>
            <form id="patientForm" onsubmit="handlePatientSubmit(event)" class="space-y-6">
                <input type="hidden" id="patientId" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Full Name</label>
                        <input type="text" id="patientName" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter patient name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Date of Birth</label>
                        <input type="date" id="patientDob" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="YYYY-MM-DD">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Age</label>
                        <input type="text" id="patientAge" readonly
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 bg-gray-50 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Sex</label>
                        <select id="patientSex" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select sex</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Blood Type</label>
                        <select id="patientBloodType" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select blood type</option>
                            <option value="A+">A+</option>
                            <option value="A-">A-</option>
                            <option value="B+">B+</option>
                            <option value="B-">B-</option>
                            <option value="AB+">AB+</option>
                            <option value="AB-">AB-</option>
                            <option value="O+">O+</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Civil Status</label>
                        <select id="patientCivilStatus" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select civil status</option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Widowed">Widowed</option>
                            <option value="Separated">Separated</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Contact Number</label>
                        <input type="text" id="patientContact" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter contact number">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Street Address</label>
                        <input type="text" id="patientStreet" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="House no., street, subdivision">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Barangay</label>
                        <input type="text" id="patientBarangay" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter barangay">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">City</label>
                        <input type="text" id="patientCity" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter city">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Province</label>
                        <input type="text" id="patientProvince" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter province">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">ZIP Code</label>
                        <input type="text" id="patientZip" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter ZIP">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Emergency Contact Name</label>
                        <input type="text" id="emergencyName" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter contact person">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Relationship</label>
                        <input type="text" id="emergencyRelationship" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="e.g., Spouse">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Emergency Contact Phone</label>
                        <input type="text" id="emergencyPhone" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter contact phone">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">PhilHealth ID</label>
                        <input type="text" id="patientPhilhealth" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="e.g., 01-234567890-1">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Purpose of Visit</label>
                        <select id="patientPurposeOfVisit" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select purpose</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Laboratory Results">Laboratory Results</option>
                            <option value="Pharmacy">Pharmacy</option>
                            <option value="Billing/Cashier">Billing/Cashier</option>
                            <option value="Radiology/Imaging">Radiology/Imaging</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Other Purpose (if selected above)</label>
                        <input type="text" id="patientOtherPurpose" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Please specify your concern">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Employer Name</label>
                        <input type="text" id="patientEmployerName" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter employer name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Employer Address</label>
                        <input type="text" id="patientEmployerAddress" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter employer address">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Patient Type</label>
                        <select id="patientType" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select patient type</option>
                            <option value="Private">Private</option>
                            <option value="Company">Company</option>
                            <option value="Senior Citizen">Senior Citizen</option>
                            <option value="PWD">PWD</option>
                            <option value="PHIC">PHIC</option>
                        </select>
                    </div>
                </div>
                <div class="border-t border-gray-100 pt-6">
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="toggleModal('patientModal')"
                            class="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            id="patientSubmitBtn"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            Add Patient
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="patientSuccessModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all">
            <div class="flex items-start">
                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-green-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Success</h3>
                    <p id="patientSuccessMessage" class="text-sm text-gray-600 mt-1">Patient added successfully.</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="button" onclick="toggleModal('patientSuccessModal')"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                    OK
                </button>
            </div>
        </div>
    </div>

    <div id="queueConfirmModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-4 transform transition-all">
            <div class="flex items-start">
                <div class="h-10 w-10 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-question text-emerald-600"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-semibold text-gray-900">Confirm Patient</h3>
                    <p class="text-sm text-gray-600 mt-1">Confirm this patient details and move to Patient's Progress?</p>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="toggleModal('queueConfirmModal')"
                    class="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-all">
                    Cancel
                </button>
                <button type="button" id="queueConfirmYesBtn"
                    class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-all">
                    Confirm
                </button>
            </div>
        </div>
    </div>

    <!-- View Patient Details Modal -->
    <div id="viewPatientDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 w-full max-w-6xl mx-4 transform transition-all max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">Patient Details</h3>
                <button onclick="closeViewPatientDetailsModal()" class="text-gray-400 hover:text-gray-600 transition-colors" type="button">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="viewPatientDetailsContent" class="space-y-6">
                <!-- Content will be populated by JavaScript -->
            </div>
            <div class="border-t border-gray-100 pt-6 mt-6">
                <div class="flex justify-end gap-3">
                    <button id="viewPatientEditToggleBtn" type="button" onclick="togglePatientDetailsEdit()"
                        class="hidden px-4 py-2 rounded-lg border border-blue-600 text-blue-600 hover:bg-blue-50 transition-all">
                        Edit
                    </button>
                    <button id="viewPatientCancelBtn" type="button" onclick="togglePatientDetailsEdit()"
                        class="hidden px-4 py-2 rounded-lg border border-gray-600 text-gray-600 hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                    <button id="viewPatientCloseBtn" type="button" onclick="closeViewPatientDetailsModal()"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                        Close
                    </button>
                    <button id="viewPatientSaveBtn" type="button" onclick="savePatientDetailsChanges()"
                        class="hidden px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-all">
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Chart initialization
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        };

        // Patient Flow Chart (Line Chart)
        const patientFlowChart = new Chart(document.getElementById('patientFlowChart'), {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Emergency Room',
                    data: [],
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.4
                }, {
                    label: 'Operating Room',
                    data: [],
                    borderColor: 'rgb(147, 51, 234)',
                    tension: 0.4
                }, {
                    label: 'Pharmacy',
                    data: [],
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.4
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Patients'
                        }
                    }
                }
            }
        });

        // Department Distribution Chart (Doughnut)
        const departmentChart = new Chart(document.getElementById('departmentChart'), {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        'rgb(239, 68, 68)',
                        'rgb(147, 51, 234)',
                        'rgb(59, 130, 246)',
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(107, 114, 128)'
                    ]
                }]
            },
            options: {
                ...chartOptions,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        let currentViewedPatient = null;
        let isPatientDetailsEditing = false;

        const patientDetailsSexOptions = ['Male', 'Female'];
        const patientDetailsBloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        const patientDetailsCivilStatusOptions = ['Single', 'Married', 'Widowed', 'Separated', 'Divorced'];
        const patientDetailsPurposeOptions = ['Consultation', 'Laboratory Results', 'Pharmacy', 'Billing/Cashier', 'Radiology/Imaging', 'Other'];

        // Modal functionality
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function calcAge(dob) {
            const d = new Date(dob);
            if (!Number.isFinite(d.getTime())) return null;
            const now = new Date();
            let age = now.getFullYear() - d.getFullYear();
            const m = now.getMonth() - d.getMonth();
            if (m < 0 || (m === 0 && now.getDate() < d.getDate())) age -= 1;
            return age;
        }

        function escapeHtml(s) {
            return (s ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function showPatientSuccess(message) {
            const el = document.getElementById('patientSuccessMessage');
            if (el) el.textContent = (message ?? '').toString();
            const modal = document.getElementById('patientSuccessModal');
            if (modal && modal.classList.contains('hidden')) {
                toggleModal('patientSuccessModal');
            }
        }

        const viewPatientFieldClasses = 'w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none';

        function renderPatientDetailsSelectOptions(options, selected) {
            const normalized = (selected ?? '').toString().trim();
            return options.map(opt => `<option value="${escapeHtml(opt)}"${opt === normalized ? ' selected' : ''}>${escapeHtml(opt)}</option>`).join('');
        }

        function renderViewPatientDetailsContent(patient) {
            const content = document.getElementById('viewPatientDetailsContent');
            if (!content) return;
            if (!patient) {
                content.innerHTML = '';
                return;
            }

            const ageValue = patient.dob ? calcAge(patient.dob) : null;
            const ageText = (ageValue !== null && ageValue !== undefined) ? `${ageValue} years` : '-';
            const ageInputValue = (ageValue !== null && ageValue !== undefined) ? String(ageValue) : '';
            const formatDate = (d) => d ? new Date(d).toLocaleDateString() : '-';
            const normalizedSex = normalizeSelectValue('patientSex', patient.sex);
            const normalizedBloodType = normalizeSelectValue('patientBloodType', patient.blood_type);
            const normalizedCivilStatus = normalizeSelectValue('patientCivilStatus', patient.civil_status);
            const normalizedPurpose = normalizeSelectValue('patientPurposeOfVisit', patient.purpose_of_visit);
            const header = `
                <div class="relative bg-gradient-to-br from-blue-600 via-teal-600 to-green-600 rounded-2xl p-8 mb-8 shadow-xl overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white opacity-10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white opacity-10 rounded-full -ml-12 -mb-12"></div>
                    <div class="relative z-10 flex items-center gap-6">
                        <div class="relative">
                            <div class="h-24 w-24 rounded-full bg-white bg-opacity-20 backdrop-blur-sm flex items-center justify-center text-white text-4xl font-bold border-4 border-white border-opacity-30 shadow-lg">
                                ${(patient.full_name || '?').charAt(0).toUpperCase()}
                            </div>
                            <div class="absolute -bottom-2 -right-2 h-8 w-8 bg-green-400 rounded-full flex items-center justify-center border-4 border-white shadow-lg">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-3xl font-bold text-white mb-2 tracking-tight">${escapeHtml(patient.full_name || '-')}</h4>
                            <div class="flex items-center gap-6">
                                <div>
                                    <p class="text-white text-sm font-medium opacity-80">Patient ID</p>
                                    <p class="text-white text-xl font-bold">${escapeHtml(patient.patient_code || '-')}</p>
                                </div>
                                <div>
                                    <p class="text-white text-sm font-medium opacity-80">Age</p>
                                    <p class="text-white text-xl font-bold">${escapeHtml(ageText)}</p>
                                </div>
                                <div>
                                    <p class="text-white text-sm font-medium opacity-80">Sex</p>
                                    <p class="text-white text-xl font-bold">${escapeHtml(patient.sex || '-')}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const personalView = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Personal Information</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-lg text-gray-600">Date of Birth</p>
                            <p class="font-bold text-gray-900 text-lg">${formatDate(patient.dob)}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Age</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(ageText)}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Sex</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.sex || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Blood Type</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.blood_type || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Civil Status</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.civil_status || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Contact Number</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.contact || '-')}</p>
                        </div>
                    </div>
                </div>
            `;

            const personalEdit = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Personal Information</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Full Name</label>
                            <input type="text" id="viewPatientFullName"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.full_name || '')}"
                                placeholder="Enter full name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Date of Birth</label>
                            <input type="date" id="viewPatientDob"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.dob || '')}"
                                oninput="syncViewPatientAgeFromDob()">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Age</label>
                            <input type="text" id="viewPatientAge" readonly
                                class="${viewPatientFieldClasses} bg-gray-50 cursor-not-allowed"
                                value="${escapeHtml(ageInputValue)}" placeholder="Age">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Sex</label>
                            <select id="viewPatientSex" class="${viewPatientFieldClasses}">
                                <option value="">Select sex</option>
                                ${renderPatientDetailsSelectOptions(patientDetailsSexOptions, normalizedSex)}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Blood Type</label>
                            <select id="viewPatientBloodType" class="${viewPatientFieldClasses}">
                                <option value="">Select blood type</option>
                                ${renderPatientDetailsSelectOptions(patientDetailsBloodTypes, normalizedBloodType)}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Civil Status</label>
                            <select id="viewPatientCivilStatus" class="${viewPatientFieldClasses}">
                                <option value="">Select civil status</option>
                                ${renderPatientDetailsSelectOptions(patientDetailsCivilStatusOptions, normalizedCivilStatus)}
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-600 mb-2">Contact Number</label>
                            <input type="text" id="viewPatientContact"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.contact || '')}"
                                placeholder="Enter contact number">
                        </div>
                    </div>
                </div>
            `;

            const medicalView = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Medical Information</h5>
                    <div class="space-y-3">
                        <div>
                            <p class="text-lg text-gray-600">PhilHealth ID</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.philhealth_pin || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Purpose of Visit</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.purpose_of_visit || '-')}</p>
                        </div>
                    </div>
                </div>
            `;

            const medicalEdit = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Medical Information</h5>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">PhilHealth ID</label>
                            <input type="text" id="viewPatientPhilhealth"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.philhealth_pin || '')}"
                                placeholder="Enter PhilHealth ID">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Purpose of Visit</label>
                            <select id="viewPatientPurposeOfVisit" class="${viewPatientFieldClasses}">
                                <option value="">Select purpose</option>
                                ${renderPatientDetailsSelectOptions(patientDetailsPurposeOptions, normalizedPurpose)}
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Other Purpose (if selected above)</label>
                            <input type="text" id="viewPatientOtherPurpose"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.purpose_of_visit_other || '')}"
                                placeholder="Please specify your concern">
                        </div>
                    </div>
                </div>
            `;

            const addressView = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Address</h5>
                    <div class="space-y-3">
                        <div>
                            <p class="text-lg text-gray-600">Street Address</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.street_address || '-')}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-lg text-gray-600">Barangay</p>
                                <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.barangay || '-')}</p>
                            </div>
                            <div>
                                <p class="text-lg text-gray-600">City</p>
                                <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.city || '-')}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-lg text-gray-600">Province</p>
                                <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.province || '-')}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const addressEdit = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Address</h5>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Street Address</label>
                            <input type="text" id="viewPatientStreet"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.street_address || '')}"
                                placeholder="Enter street address">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Barangay</label>
                                <input type="text" id="viewPatientBarangay"
                                    class="${viewPatientFieldClasses}" value="${escapeHtml(patient.barangay || '')}"
                                    placeholder="Enter barangay">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">City</label>
                                <input type="text" id="viewPatientCity"
                                    class="${viewPatientFieldClasses}" value="${escapeHtml(patient.city || '')}"
                                    placeholder="Enter city">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Province</label>
                                <input type="text" id="viewPatientProvince"
                                    class="${viewPatientFieldClasses}" value="${escapeHtml(patient.province || '')}"
                                    placeholder="Enter province">
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const emergencyView = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Emergency Contact</h5>
                    <div class="space-y-3">
                        <div>
                            <p class="text-lg text-gray-600">Contact Name</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.emergency_contact_name || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Relationship</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.emergency_contact_relationship || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Phone Number</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.emergency_contact_phone || '-')}</p>
                        </div>
                    </div>
                </div>
            `;

            const emergencyEdit = `
                <div class="space-y-4">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Emergency Contact</h5>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Contact Name</label>
                            <input type="text" id="viewPatientEmergencyName"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.emergency_contact_name || '')}"
                                placeholder="Contact name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Relationship</label>
                            <input type="text" id="viewPatientEmergencyRelationship"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.emergency_contact_relationship || '')}"
                                placeholder="Relationship">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Phone Number</label>
                            <input type="text" id="viewPatientEmergencyPhone"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.emergency_contact_phone || '')}"
                                placeholder="Phone number">
                        </div>
                    </div>
                </div>
            `;

            const employmentView = (patient.employer_name || patient.employer_address) ? `
                <div class="space-y-4 md:col-span-2">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Employment Information</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-lg text-gray-600">Employer Name</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.employer_name || '-')}</p>
                        </div>
                        <div>
                            <p class="text-lg text-gray-600">Employer Address</p>
                            <p class="font-bold text-gray-900 text-lg">${escapeHtml(patient.employer_address || '-')}</p>
                        </div>
                    </div>
                </div>
            ` : '';

            const employmentEdit = `
                <div class="space-y-4 md:col-span-2">
                    <h5 class="text-xl font-bold text-gray-900 border-b pb-2">Employment Information</h5>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Employer Name</label>
                            <input type="text" id="viewPatientEmployerName"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.employer_name || '')}"
                                placeholder="Enter employer name">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-2">Employer Address</label>
                            <input type="text" id="viewPatientEmployerAddress"
                                class="${viewPatientFieldClasses}" value="${escapeHtml(patient.employer_address || '')}"
                                placeholder="Enter employer address">
                        </div>
                    </div>
                </div>
            `;

            const registeredAt = patient.created_at ? new Date(patient.created_at).toLocaleString() : '-';
            const updatedAt = patient.updated_at ? new Date(patient.updated_at).toLocaleString() : '-';

            const sections = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    ${isPatientDetailsEditing ? personalEdit : personalView}
                    ${isPatientDetailsEditing ? medicalEdit : medicalView}
                    ${isPatientDetailsEditing ? addressEdit : addressView}
                    ${isPatientDetailsEditing ? emergencyEdit : emergencyView}
                    ${isPatientDetailsEditing ? employmentEdit : employmentView}
                    <div class="md:col-span-2 bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 text-lg">
                            <div>
                                <p class="text-gray-600">Registered At</p>
                                <p class="font-bold text-gray-900 text-lg">${escapeHtml(registeredAt)}</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Last Updated</p>
                                <p class="font-bold text-gray-900 text-lg">${escapeHtml(updatedAt)}</p>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            content.innerHTML = `${header}${sections}`;
        }

        function syncViewPatientAgeFromDob() {
            const dobEl = document.getElementById('viewPatientDob');
            const ageEl = document.getElementById('viewPatientAge');
            if (!dobEl || !ageEl) return;
            const age = calcAge(dobEl.value);
            ageEl.value = (age !== null && age !== undefined) ? String(age) : '';
        }

        function updatePatientDetailsEditControls() {
            const editBtn = document.getElementById('viewPatientEditToggleBtn');
            if (editBtn) {
                editBtn.classList.toggle('hidden', !currentViewedPatient);
                editBtn.textContent = isPatientDetailsEditing ? 'Cancel' : 'Edit';
            }
            const saveBtn = document.getElementById('viewPatientSaveBtn');
            if (saveBtn) {
                saveBtn.classList.toggle('hidden', !isPatientDetailsEditing);
            }
        }

        function togglePatientDetailsEdit(forceState) {
            if (!currentViewedPatient) return;
            const nextState = (typeof forceState === 'boolean') ? forceState : !isPatientDetailsEditing;
            isPatientDetailsEditing = nextState;
            renderViewPatientDetailsContent(currentViewedPatient);
            updatePatientDetailsEditControls();
        }

        function closeViewPatientDetailsModal() {
            const modal = document.getElementById('viewPatientDetailsModal');
            if (modal && !modal.classList.contains('hidden')) {
                toggleModal('viewPatientDetailsModal');
            }
            currentViewedPatient = null;
            isPatientDetailsEditing = false;
            const content = document.getElementById('viewPatientDetailsContent');
            if (content) content.innerHTML = '';
            updatePatientDetailsEditControls();
        }

        async function savePatientDetailsChanges() {
            if (!currentViewedPatient || !isPatientDetailsEditing) return;
            const saveBtn = document.getElementById('viewPatientSaveBtn');
            if (saveBtn) saveBtn.disabled = true;

            const getVal = (id) => (document.getElementById(id)?.value ?? '').toString().trim();

            const payload = {
                id: Number(currentViewedPatient.id),
                full_name: getVal('viewPatientFullName'),
                philhealth_pin: getVal('viewPatientPhilhealth'),
                dob: getVal('viewPatientDob'),
                sex: getVal('viewPatientSex'),
                blood_type: getVal('viewPatientBloodType'),
                civil_status: getVal('viewPatientCivilStatus'),
                contact: getVal('viewPatientContact'),
                purpose_of_visit: getVal('viewPatientPurposeOfVisit'),
                purpose_of_visit_other: getVal('viewPatientOtherPurpose'),
                street_address: getVal('viewPatientStreet'),
                barangay: getVal('viewPatientBarangay'),
                city: getVal('viewPatientCity'),
                province: getVal('viewPatientProvince'),
                employer_name: getVal('viewPatientEmployerName'),
                employer_address: getVal('viewPatientEmployerAddress'),
                emergency_contact_name: getVal('viewPatientEmergencyName'),
                emergency_contact_relationship: getVal('viewPatientEmergencyRelationship'),
                emergency_contact_phone: getVal('viewPatientEmergencyPhone'),
            };

            try {
                const res = await fetch(API_BASE_URL + '/patients/update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload),
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !json.patient) {
                    alert((json && json.error) ? json.error : 'Failed to update patient details');
                    return;
                }

                currentViewedPatient = json.patient;
                isPatientDetailsEditing = false;
                renderViewPatientDetailsContent(currentViewedPatient);
                updatePatientDetailsEditControls();
            } finally {
                if (saveBtn) saveBtn.disabled = false;
            }
        }

        function progressChip(status) {
            const s = (status ?? '').toString().trim();
            const sl = s.toLowerCase();
            if (sl === 'completed') return { label: 'Completed', cls: 'bg-green-100 text-green-800' };
            if (sl.includes('billing')) return { label: s, cls: 'bg-amber-100 text-amber-800' };
            if (sl.includes('awaiting')) return { label: s, cls: 'bg-amber-100 text-amber-800' };
            if (sl.includes('lab: in progress')) return { label: s, cls: 'bg-blue-100 text-blue-800' };
            if (sl.includes('lab: collected')) return { label: s, cls: 'bg-blue-100 text-blue-800' };
            if (sl.includes('lab: approved')) return { label: s, cls: 'bg-blue-100 text-blue-800' };
            if (sl.includes('lab: pending')) return { label: s, cls: 'bg-gray-100 text-gray-800' };
            if (sl.includes('rejected') || sl.includes('cancelled')) return { label: s, cls: 'bg-red-100 text-red-800' };
            if (sl.includes('lab completed')) return { label: s, cls: 'bg-green-100 text-green-800' };
            if (sl === '' || sl === 'registered') return { label: 'Registered', cls: 'bg-gray-100 text-gray-800' };
            return { label: s, cls: 'bg-gray-100 text-gray-800' };
        }

        async function loadPatientQueue() {
            const tbody = document.getElementById('patientQueueTbody');
            if (!tbody) return;

            const res = await fetch(API_BASE_URL + '/patients/list.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                const label = document.getElementById('patientQueueResultsLabel');
                if (label) label.textContent = 'Showing 0 results';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients : [];
            const label = document.getElementById('patientQueueResultsLabel');
            if (label) label.textContent = 'Showing ' + String(rows.length) + ' results';

            tbody.innerHTML = rows.map(patient => {
                const fullName = escapeHtml(patient.full_name || '');
                const patientCode = escapeHtml(patient.patient_code || '-');
                const age = patient.dob ? calcAge(patient.dob) : '-';
                const sex = escapeHtml(patient.sex || '-');
                const ageSex = age !== '-' ? `${age} / ${sex}` : sex;
                const contact = escapeHtml(patient.contact || '-');
                const purposeOfVisit = escapeHtml(patient.purpose_of_visit || patient.diagnosis || '-');
                const registeredAt = patient.created_at ? new Date(patient.created_at).toLocaleString() : '-';
                const patientId = Number(patient.id);

                return `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${patientCode}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${fullName}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${ageSex}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${contact}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${purposeOfVisit}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${registeredAt}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center">
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all" type="button" onclick="viewPatientDetails(${patientId})">View Details</button>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        let currentQueueEditId = null;
        let pendingQueueConfirmId = null;

        function normalizeSelectValue(selectId, val) {
            const v = (val ?? '').toString().trim();
            if (v === '') return '';

            const key = v.toLowerCase();
            if (selectId === 'patientSex') {
                if (key === 'male' || key === 'm') return 'Male';
                if (key === 'female' || key === 'f') return 'Female';
            }
            if (selectId === 'patientCivilStatus') {
                if (key === 'single') return 'Single';
                if (key === 'married') return 'Married';
                if (key === 'widowed') return 'Widowed';
                if (key === 'separated') return 'Separated';
                if (key === 'divorced') return 'Separated';
            }
            if (selectId === 'patientBloodType') {
                return v.toUpperCase().replace(/\s+/g, '');
            }
            if (selectId === 'patientPurposeOfVisit') {
                const purposeMap = {
                    'consultation': 'Consultation',
                    'laboratory': 'Laboratory Results',
                    'pharmacy': 'Pharmacy',
                    'billing': 'Billing/Cashier',
                    'radiology': 'Radiology/Imaging',
                    'other': 'Other'
                };
                return purposeMap[key] || v;
            }
            if (selectId === 'patientType') {
                const typeMap = {
                    'private': 'Private',
                    'company': 'Company',
                    'senior citizen': 'Senior Citizen',
                    'senior': 'Senior Citizen',
                    'pwd': 'PWD',
                    'phic': 'PHIC',
                    'philhealth': 'PHIC'
                };
                return typeMap[key] || v;
            }
            return v;
        }

        async function openEditQueueItem(queueId) {
            currentQueueEditId = Number(queueId);
            const res = await fetch(API_BASE_URL + '/queue/list.php?status=queued', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? json.error : 'Failed to load queue');
                return;
            }

            const rows = Array.isArray(json.queue) ? json.queue : [];
            const item = rows.find(r => Number(r.id) === Number(queueId));
            if (!item || !item.payload) {
                alert('Queue item not found');
                return;
            }

            const p = item.payload;
            const setVal = (id, v) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = (v ?? '').toString();
            };

            document.getElementById('modalTitle').textContent = 'Edit Queue Patient';
            const form = document.getElementById('patientForm');
            if (form) form.reset();

            const submitBtn = document.getElementById('patientSubmitBtn');
            if (submitBtn) submitBtn.textContent = 'Save Queue Changes';

            setVal('patientName', p.full_name);
            setVal('patientDob', p.dob);
            setVal('patientSex', normalizeSelectValue('patientSex', p.sex));
            setVal('patientBloodType', p.blood_type);
            syncAgeFromDob();
            setVal('patientCivilStatus', normalizeSelectValue('patientCivilStatus', p.civil_status));
            setVal('patientContact', p.contact);
            setVal('patientStreet', p.street_address);
            setVal('patientBarangay', p.barangay);
            setVal('patientCity', p.city);
            setVal('patientProvince', p.province);
            setVal('patientZip', p.zip_code);
            setVal('emergencyName', p.emergency_contact_name);
            setVal('emergencyRelationship', p.emergency_contact_relationship);
            setVal('emergencyPhone', p.emergency_contact_phone);
            setVal('patientPhilhealth', p.philhealth_pin);
            setVal('patientPurposeOfVisit', normalizeSelectValue('patientPurposeOfVisit', p.purpose_of_visit));
            setVal('patientOtherPurpose', p.purpose_of_visit_other);
            setVal('patientEmployerName', p.employer_name);
            setVal('patientEmployerAddress', p.employer_address);
            setVal('patientType', normalizeSelectValue('patientType', p.patient_type));

            toggleModal('patientModal');
        }

        function requestConfirmQueueItem(queueId) {
            pendingQueueConfirmId = Number(queueId);
            toggleModal('queueConfirmModal');
        }

        async function confirmQueueItem(queueId) {
            const res = await fetch(API_BASE_URL + '/queue/confirm.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ queue_id: queueId }),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? json.error : 'Failed to confirm patient');
                return;
            }

            await loadPatientQueue();
            await loadPatients((document.getElementById('patientSearch')?.value ?? '').toString());
            showPatientSuccess('Patient confirmed and moved to progress.');
        }

        async function loadPatients(q = '') {
            const tbody = document.getElementById('patientsTbody');
            if (!tbody) return;
            const url = API_BASE_URL + '/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                const label = document.getElementById('patientsResultsLabel');
                if (label) label.textContent = 'Showing 0 results';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients : [];
            const label = document.getElementById('patientsResultsLabel');
            if (label) label.textContent = 'Showing ' + String(rows.length) + ' results';
            tbody.innerHTML = rows.map(p => {
                const fullName = escapeHtml(p.full_name || '');
                const age = p.dob ? calcAge(p.dob) : null;
                const sub = (age !== null) ? (String(age) + ' years') : '';
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                const loc = escapeHtml(p.initial_location || '');
                const dept = escapeHtml(p.department || '');
                const doneProcessRaw = (p.done_process ?? '-').toString();
                const doneProcessClean = doneProcessRaw
                    .split('•')
                    .map(s => s.trim().replace(/^done\s+/i, ''))
                    .join(' • ');
                const doneProcess = escapeHtml(doneProcessClean);
                const nextProc = escapeHtml((p.next_procedure ?? '-').toString());
                const chip = progressChip(p.progress_status);
                const updated = escapeHtml(((p.progress_time || p.updated_at) || '').toString());

                return `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <i class="fas fa-user text-gray-500"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${fullName}</div>
                                    <div class="text-sm text-gray-500">${escapeHtml(sub)}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${code}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${loc}</div>
                            <div class="text-xs text-gray-500">${dept}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${doneProcess}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${nextProc}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${chip.cls}">
                                ${escapeHtml(chip.label)}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${updated}</td>
                    </tr>
                `;
            }).join('');
        }

        async function loadPatientStats() {
            const res = await fetch(API_BASE_URL + '/patients/stats.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                return;
            }

            const cards = json.cards || {};
            const total = Number(cards.total_patients ?? 0) || 0;
            const inTreatment = Number(cards.in_treatment ?? 0) || 0;
            const waiting = Number(cards.waiting ?? 0) || 0;
            const surgeries = Number(cards.surgeries ?? 0) || 0;
            const discharged = Number(cards.discharged ?? 0) || 0;

            const elTotal = document.getElementById('statTotalPatients');
            if (elTotal) elTotal.textContent = String(total);
            const elInTreatment = document.getElementById('statInTreatment');
            if (elInTreatment) elInTreatment.textContent = String(inTreatment);
            const elWaiting = document.getElementById('statWaiting');
            if (elWaiting) elWaiting.textContent = String(waiting);
            const elSurgeries = document.getElementById('statSurgeries');
            if (elSurgeries) elSurgeries.textContent = String(surgeries);
            const elDischarged = document.getElementById('statDischarged');
            if (elDischarged) elDischarged.textContent = String(discharged);

            const waitingBar = document.getElementById('statWaitingBar');
            if (waitingBar) {
                const pct = total > 0 ? Math.round((waiting / total) * 100) : 0;
                waitingBar.style.width = String(Math.max(0, Math.min(100, pct))) + '%';
            }

            const charts = json.charts || {};
            const flow = charts.flow || {};
            const flowLabels = Array.isArray(flow.labels) ? flow.labels : [];
            const flowSeries = flow.series || {};

            patientFlowChart.data.labels = flowLabels;
            patientFlowChart.data.datasets[0].data = Array.isArray(flowSeries.emergency) ? flowSeries.emergency : [];
            patientFlowChart.data.datasets[1].data = Array.isArray(flowSeries.or) ? flowSeries.or : [];
            patientFlowChart.data.datasets[2].data = Array.isArray(flowSeries.pharmacy) ? flowSeries.pharmacy : [];
            patientFlowChart.update();

            const dept = charts.department || {};
            const preferredOrder = ['surgery', 'cardiology', 'neurology', 'pediatrics', 'orthopedics', 'other'];
            const deptLabel = (k) => {
                if (k === 'surgery') return 'Surgery';
                if (k === 'cardiology') return 'Cardiology';
                if (k === 'neurology') return 'Neurology';
                if (k === 'pediatrics') return 'Pediatrics';
                if (k === 'orthopedics') return 'Orthopedics';
                return 'Other';
            };

            const orderedKeys = preferredOrder.filter(k => Object.prototype.hasOwnProperty.call(dept, k));
            Object.keys(dept).forEach(k => {
                if (!orderedKeys.includes(k)) orderedKeys.push(k);
            });

            departmentChart.data.labels = orderedKeys.map(deptLabel);
            departmentChart.data.datasets[0].data = orderedKeys.map(k => Number(dept[k] ?? 0) || 0);
            departmentChart.update();
        }

        async function autoFillPatientForm() {
            const btn = document.getElementById('autoFillAiBtn');
            if (btn) btn.disabled = true;

            try {
                const res = await fetch(API_BASE_URL + '/patients/autofill.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok || !json.patient) {
                    alert((json && json.error) ? json.error : 'Failed to autofill');
                    return;
                }

                const p = json.patient;

                const setVal = (id, v) => {
                    const el = document.getElementById(id);
                    if (!el) return;
                    el.value = (v ?? '').toString();
                };

                setVal('patientName', p.full_name);
                setVal('patientDob', p.dob);
                setVal('patientSex', p.sex);
                setVal('patientBloodType', p.blood_type);
                syncAgeFromDob();
                setVal('patientCivilStatus', p.civil_status);
                setVal('patientContact', p.contact);
                setVal('patientStreet', p.street_address);
                setVal('patientBarangay', p.barangay);
                setVal('patientCity', p.city);
                setVal('patientProvince', p.province);
                setVal('patientZip', p.zip_code);
                setVal('emergencyName', p.emergency_contact_name);
                setVal('emergencyRelationship', p.emergency_contact_relationship);
                setVal('emergencyPhone', p.emergency_contact_phone);
            } finally {
                if (btn) btn.disabled = false;
            }
        }

        let patientsProgressPoll = null;

        function applyPatientsViewFromHash() {
            const raw = (window.location.hash || '').toString().replace(/^#/, '').toLowerCase();
            const view = (raw === 'progress') ? 'progress' : (raw === 'queue' ? 'queue' : 'dashboard');

            const dash = document.getElementById('patientsDashboardView');
            const prog = document.getElementById('patientsProgressView');
            const queue = document.getElementById('patientsQueueView');

            if (dash) dash.classList.toggle('hidden', view !== 'dashboard');
            if (prog) prog.classList.toggle('hidden', view !== 'progress');
            if (queue) queue.classList.toggle('hidden', view !== 'queue');

            if (view === 'dashboard') {
                if (patientsProgressPoll) {
                    patientsProgressPoll = null;
                    if (window._patientsWsHandler) {
                        HospitalWS.off('queue_update', window._patientsWsHandler);
                        HospitalWS.off('fallback_poll', window._patientsWsHandler);
                        window._patientsWsHandler = null;
                    }
                }
                loadPatientStats();
            } else if (view === 'progress') {
                const q = (document.getElementById('patientSearch')?.value ?? '').toString();
                loadPatients(q);

                // Subscribe to WebSocket for real-time patient progress updates
                if (!patientsProgressPoll) {
                    patientsProgressPoll = true;
                    HospitalWS.subscribe('global');
                    window._patientsWsHandler = function() {
                        if (patientsProgressPoll) {
                            const q2 = (document.getElementById('patientSearch')?.value ?? '').toString();
                            loadPatients(q2);
                        }
                    };
                    HospitalWS.on('queue_update', window._patientsWsHandler);
                    HospitalWS.on('fallback_poll', window._patientsWsHandler);
                }
            } else {
                if (patientsProgressPoll) {
                    patientsProgressPoll = null;
                    if (window._patientsWsHandler) {
                        HospitalWS.off('queue_update', window._patientsWsHandler);
                        HospitalWS.off('fallback_poll', window._patientsWsHandler);
                        window._patientsWsHandler = null;
                    }
                }
                loadPatientQueue();
            }
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            const addNewBtn = document.getElementById('addNewBtn');
            if (addNewBtn) {
                addNewBtn.addEventListener('click', function() {
                    document.getElementById('modalTitle').textContent = 'Add New Patient';
                    document.getElementById('patientForm').reset();
                    const idEl = document.getElementById('patientId');
                    if (idEl) idEl.value = '';
                    const submitBtn = document.getElementById('patientSubmitBtn');
                    if (submitBtn) submitBtn.textContent = 'Add Patient';
                    syncAgeFromDob();
                    toggleModal('patientModal');
                });
            }
            const qYes = document.getElementById('queueConfirmYesBtn');
            if (qYes) {
                qYes.addEventListener('click', async function() {
                    const id = pendingQueueConfirmId;
                    if (!id) {
                        toggleModal('queueConfirmModal');
                        return;
                    }
                    toggleModal('queueConfirmModal');
                    pendingQueueConfirmId = null;
                    await confirmQueueItem(id);
                });
            }
            const autoFillBtn = document.getElementById('autoFillAiBtn');
            if (autoFillBtn) {
                autoFillBtn.addEventListener('click', autoFillPatientForm);
            }
            const search = document.getElementById('patientSearch');
            if (search) {
                let t = null;
                search.addEventListener('input', function() {
                    if (t) window.clearTimeout(t);
                    t = window.setTimeout(() => loadPatients(search.value), 250);
                });
            }
            const dobEl = document.getElementById('patientDob');
            if (dobEl) {
                dobEl.addEventListener('change', syncAgeFromDob);
                dobEl.addEventListener('input', syncAgeFromDob);
            }
            window.addEventListener('hashchange', applyPatientsViewFromHash);
            applyPatientsViewFromHash();
        });

        // Form submission
        async function handlePatientSubmit(event) {
            event.preventDefault();

            const patientIdRaw = (document.getElementById('patientId')?.value ?? '').toString().trim();
            const isEdit = patientIdRaw !== '' && /^\d+$/.test(patientIdRaw);

            if (currentQueueEditId !== null && !isEdit) {
                const payload = {
                    full_name: document.getElementById('patientName').value,
                    dob: document.getElementById('patientDob').value,
                    sex: document.getElementById('patientSex').value,
                    blood_type: document.getElementById('patientBloodType').value,
                    civil_status: document.getElementById('patientCivilStatus').value,
                    contact: document.getElementById('patientContact').value,
                    initial_location: 'OPD',
                    street_address: document.getElementById('patientStreet').value,
                    barangay: document.getElementById('patientBarangay').value,
                    city: document.getElementById('patientCity').value,
                    province: document.getElementById('patientProvince').value,
                    zip_code: document.getElementById('patientZip').value,
                    emergency_contact_name: document.getElementById('emergencyName').value,
                    emergency_contact_relationship: document.getElementById('emergencyRelationship').value,
                    emergency_contact_phone: document.getElementById('emergencyPhone').value,
                    philhealth_pin: document.getElementById('patientPhilhealth').value,
                    purpose_of_visit: document.getElementById('patientPurposeOfVisit').value,
                    purpose_of_visit_other: document.getElementById('patientOtherPurpose').value,
                    employer_name: document.getElementById('patientEmployerName').value,
                    employer_address: document.getElementById('patientEmployerAddress').value,
                    patient_type: document.getElementById('patientType').value,
                };

                const res = await fetch(API_BASE_URL + '/queue/update.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ queue_id: currentQueueEditId, payload }),
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    alert((json && json.error) ? json.error : 'Failed to update queue item');
                    return;
                }

                toggleModal('patientModal');
                currentQueueEditId = null;
                await loadPatientQueue();
                showPatientSuccess('Queue item updated successfully.');
                return;
            }

            const payload = {
                id: isEdit ? Number(patientIdRaw) : undefined,
                full_name: document.getElementById('patientName').value,
                dob: document.getElementById('patientDob').value,
                sex: document.getElementById('patientSex').value,
                blood_type: document.getElementById('patientBloodType').value,
                civil_status: document.getElementById('patientCivilStatus').value,
                contact: document.getElementById('patientContact').value,
                initial_location: 'OPD',
                street_address: document.getElementById('patientStreet').value,
                barangay: document.getElementById('patientBarangay').value,
                city: document.getElementById('patientCity').value,
                province: document.getElementById('patientProvince').value,
                zip_code: document.getElementById('patientZip').value,
                emergency_contact_name: document.getElementById('emergencyName').value,
                emergency_contact_relationship: document.getElementById('emergencyRelationship').value,
                emergency_contact_phone: document.getElementById('emergencyPhone').value,
                philhealth_pin: document.getElementById('patientPhilhealth').value,
            };

            const url = isEdit ? API_BASE_URL + '/patients/update.php' : API_BASE_URL + '/patients/create.php';
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? json.error : (isEdit ? 'Failed to update patient' : 'Failed to add patient'));
                return;
            }

            toggleModal('patientModal');
            await loadPatients();
            showPatientSuccess(isEdit ? 'Patient updated successfully.' : 'Patient added successfully.');
        }

        async function openEditPatient(patientId) {
            document.getElementById('modalTitle').textContent = 'Edit Patient';
            const form = document.getElementById('patientForm');
            if (form) form.reset();
            const idEl = document.getElementById('patientId');
            if (idEl) idEl.value = String(patientId);
            const submitBtn = document.getElementById('patientSubmitBtn');
            if (submitBtn) submitBtn.textContent = 'Save Changes';

            const res = await fetch(API_BASE_URL + '/patients/get.php?id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok || !json.patient) {
                alert((json && json.error) ? json.error : 'Failed to load patient');
                return;
            }

            const p = json.patient;
            const setVal = (id, v) => {
                const el = document.getElementById(id);
                if (!el) return;
                el.value = (v ?? '').toString();
            };

            setVal('patientName', p.full_name);
            setVal('patientDob', p.dob);
            setVal('patientSex', p.sex);
            setVal('patientBloodType', p.blood_type);
            syncAgeFromDob();
            setVal('patientCivilStatus', p.civil_status);
            setVal('patientContact', p.contact);
            setVal('patientEmail', p.email);
            setVal('patientPhilhealthPin', p.philhealth_pin);
            setVal('patientLocation', p.initial_location);
            setVal('patientStreet', p.street_address);
            setVal('patientBarangay', p.barangay);
            setVal('patientCity', p.city);
            setVal('patientProvince', p.province);
            setVal('patientZip', p.zip_code);
            setVal('emergencyName', p.emergency_contact_name);
            setVal('emergencyRelationship', p.emergency_contact_relationship);
            setVal('emergencyPhone', p.emergency_contact_phone);

            toggleModal('patientModal');
        }

        function calcAge(dobStr) {
            const s = (dobStr ?? '').toString().trim();
            if (!/^\d{4}-\d{2}-\d{2}$/.test(s)) return '';
            const parts = s.split('-').map(n => parseInt(n, 10));
            const y = parts[0];
            const m = parts[1];
            const d = parts[2];
            if (!y || !m || !d) return '';

            const today = new Date();
            let age = today.getFullYear() - y;
            const thisYearBirthday = new Date(today.getFullYear(), m - 1, d);
            if (today < thisYearBirthday) age -= 1;
            if (age < 0) return '';
            return String(age);
        }

        function syncAgeFromDob() {
            const dob = document.getElementById('patientDob')?.value ?? '';
            const ageEl = document.getElementById('patientAge');
            if (!ageEl) return;
            ageEl.value = calcAge(dob);
        }

        async function viewPatientDetails(patientId) {
            const content = document.getElementById('viewPatientDetailsContent');
            if (!content) return;

            content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-3xl text-blue-600"></i><p class="mt-2 text-gray-600">Loading patient details...</p></div>';
            toggleModal('viewPatientDetailsModal');

            const res = await fetch(API_BASE_URL + '/patients/get.php?id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok || !json.patient) {
                content.innerHTML = '<div class="text-center py-8 text-red-600"><i class="fas fa-exclamation-circle text-3xl"></i><p class="mt-2">Failed to load patient details</p></div>';
                currentViewedPatient = null;
                isPatientDetailsEditing = false;
                updatePatientDetailsEditControls();
                return;
            }

            currentViewedPatient = json.patient;
            isPatientDetailsEditing = false;
            renderViewPatientDetailsContent(currentViewedPatient);
            updatePatientDetailsEditControls();
        }
    </script>
</body>
</html>

