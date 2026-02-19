<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Monitoring</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                    <button id="addNewBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700">
                        <i class="fas fa-plus"></i>
                        <span>Add Patient</span>
                    </button>
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
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Blood Type</label>
                        <input type="text" id="patientBloodType"
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="e.g., O+, A-, AB+">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Civil Status</label>
                        <select id="patientCivilStatus" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select civil status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                            <option value="widowed">Widowed</option>
                            <option value="separated">Separated</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Contact Number</label>
                        <input type="text" id="patientContact" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter contact number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Email</label>
                        <input type="email" id="patientEmail" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter email">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">PhilHealth PIN (Optional)</label>
                        <input type="text" id="patientPhilhealthPin" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="12-345678901-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Initial Location</label>
                        <select id="patientLocation" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select location</option>
                            <option value="emergency">Emergency Room</option>
                            <option value="ward">General Ward</option>
                            <option value="icu">ICU</option>
                            <option value="or">Operating Room</option>
                            <option value="pharmacy">Pharmacy</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Diagnosis / Chief Complaint</label>
                        <input type="text" id="patientDiagnosis" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="e.g., CKD Stage 5 / ESRD">
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

        async function loadPatients(q = '') {
            const tbody = document.getElementById('patientsTbody');
            if (!tbody) return;
            const url = 'api/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button class="text-blue-600 hover:text-blue-900 mr-3" type="button" onclick="openEditPatient(${Number(p.id)})">Edit</button>
                            <button class="text-green-600 hover:text-green-900" type="button">Update Status</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function loadPatientStats() {
            const res = await fetch('api/patients/stats.php', { headers: { 'Accept': 'application/json' } });
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
                const res = await fetch('api/patients/autofill.php', { headers: { 'Accept': 'application/json' } });
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
                setVal('patientEmail', p.email);
                setVal('patientPhilhealthPin', p.philhealth_pin);
                setVal('patientLocation', p.initial_location);
                setVal('patientDiagnosis', p.diagnosis);
                setVal('patientStreet', p.street_address);
                setVal('patientBarangay', p.barangay);
                setVal('patientCity', p.city);
                setVal('patientProvince', p.province);
                setVal('patientZip', p.zip_code);
                setVal('patientEmployerName', p.employer_name);
                setVal('patientEmployerAddress', p.employer_address);
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
            const view = (raw === 'progress') ? 'progress' : 'dashboard';

            const dash = document.getElementById('patientsDashboardView');
            const prog = document.getElementById('patientsProgressView');

            if (dash) dash.classList.toggle('hidden', view !== 'dashboard');
            if (prog) prog.classList.toggle('hidden', view !== 'progress');

            if (view === 'dashboard') {
                if (patientsProgressPoll) {
                    window.clearInterval(patientsProgressPoll);
                    patientsProgressPoll = null;
                }
                loadPatientStats();
            } else {
                const q = (document.getElementById('patientSearch')?.value ?? '').toString();
                loadPatients(q);

                if (!patientsProgressPoll) {
                    patientsProgressPoll = window.setInterval(() => {
                        const q2 = (document.getElementById('patientSearch')?.value ?? '').toString();
                        loadPatients(q2);
                    }, 8000);
                }
            }
        }

        // Initialize event listeners
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('addNewBtn').addEventListener('click', function() {
                document.getElementById('modalTitle').textContent = 'Add New Patient';
                document.getElementById('patientForm').reset();
                const idEl = document.getElementById('patientId');
                if (idEl) idEl.value = '';
                const submitBtn = document.getElementById('patientSubmitBtn');
                if (submitBtn) submitBtn.textContent = 'Add Patient';
                syncAgeFromDob();
                toggleModal('patientModal');
            });
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

            const payload = {
                id: isEdit ? Number(patientIdRaw) : undefined,
                full_name: document.getElementById('patientName').value,
                dob: document.getElementById('patientDob').value,
                sex: document.getElementById('patientSex').value,
                blood_type: document.getElementById('patientBloodType').value,
                civil_status: document.getElementById('patientCivilStatus').value,
                contact: document.getElementById('patientContact').value,
                email: document.getElementById('patientEmail').value,
                philhealth_pin: document.getElementById('patientPhilhealthPin').value,
                initial_location: document.getElementById('patientLocation').value,
                diagnosis: document.getElementById('patientDiagnosis').value,
                street_address: document.getElementById('patientStreet').value,
                barangay: document.getElementById('patientBarangay').value,
                city: document.getElementById('patientCity').value,
                province: document.getElementById('patientProvince').value,
                zip_code: document.getElementById('patientZip').value,
                employer_name: document.getElementById('patientEmployerName').value,
                employer_address: document.getElementById('patientEmployerAddress').value,
                emergency_contact_name: document.getElementById('emergencyName').value,
                emergency_contact_relationship: document.getElementById('emergencyRelationship').value,
                emergency_contact_phone: document.getElementById('emergencyPhone').value,
            };

            const url = isEdit ? 'api/patients/update.php' : 'api/patients/create.php';
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

            toggleModal('patientModal');

            const res = await fetch('api/patients/get.php?id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
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
            setVal('patientDiagnosis', p.diagnosis);
            setVal('patientStreet', p.street_address);
            setVal('patientBarangay', p.barangay);
            setVal('patientCity', p.city);
            setVal('patientProvince', p.province);
            setVal('patientZip', p.zip_code);
            setVal('patientEmployerName', p.employer_name);
            setVal('patientEmployerAddress', p.employer_address);
            setVal('emergencyName', p.emergency_contact_name);
            setVal('emergencyRelationship', p.emergency_contact_relationship);
            setVal('emergencyPhone', p.emergency_contact_phone);
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
    </script>
</body>
</html>

