<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .vital-card {
            transition: all 0.3s ease;
        }
        .vital-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
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
                            <a href="patients.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
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
        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <!-- Header -->
            <div class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div>
                        <h1 class="text-2xl font-semibold">Patient Management Dashboard</h1>
                        <p class="text-gray-500">Patient ID: P-1001</p>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="addRecordBtn" class="px-4 py-2 bg-violet-600 text-white rounded-lg flex items-center space-x-2 hover:bg-violet-700">
                        <i class="fas fa-plus"></i>
                        <span>Add Record</span>
                    </button>
                    <div class="flex items-center space-x-3">
                        <img src="resources/doctor.jpg" alt="Dr. Mike Taylor" class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-medium">Dr. Mike Taylor</div>
                            <div class="text-sm text-gray-500">Heart Specialist</div>
                        </div>
                    </div>
                </div>

                <!-- Add Record Modal -->
                <div id="addRecordModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-start justify-center z-50 overflow-y-auto py-8">
                    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-4xl mx-4 my-auto relative">
                        <div class="flex justify-between items-center mb-6 bg-white">
                            <h3 class="text-2xl font-semibold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">Add New Record</h3>
                            <button onclick="toggleAddRecordModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        <form id="addRecordForm" class="space-y-6">
                            <!-- Vital Signs Section -->
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Heart Rate</label>
                                    <div class="flex">
                                        <input type="number" name="heartRate" required 
                                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                            placeholder="Enter heart rate">
                                        <span class="ml-2 flex items-center text-gray-500">bpm</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Blood Pressure</label>
                                    <div class="flex space-x-2">
                                        <input type="number" name="systolic" required 
                                            class="w-1/2 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                            placeholder="Systolic">
                                        <span class="flex items-center text-gray-500">/</span>
                                        <input type="number" name="diastolic" required 
                                            class="w-1/2 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                            placeholder="Diastolic">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Temperature</label>
                                    <div class="flex">
                                        <input type="number" name="temperature" step="0.1" required 
                                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                            placeholder="Enter temperature">
                                        <span class="ml-2 flex items-center text-gray-500">Â°C</span>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-2">Glucose Level</label>
                                    <div class="flex">
                                        <input type="number" name="glucoseLevel" required 
                                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                            placeholder="Enter glucose level">
                                        <span class="ml-2 flex items-center text-gray-500">mg/dL</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Clinical Notes</label>
                                <textarea name="notes" rows="4"
                                    class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                    placeholder="Enter clinical notes and observations"></textarea>
                            </div>

                            <!-- Medications Section -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">Prescribed Medications</label>
                                <div id="medicationsList" class="space-y-2">
                                    <div class="flex space-x-2">
                                        <input type="text" name="medications[]" 
                                            class="flex-1 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                                            placeholder="Enter medication name and dosage">
                                        <button type="button" onclick="addMedicationField()"
                                            class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-gray-100 pt-6">
                                <div class="flex justify-end space-x-4">
                                    <button type="button" onclick="toggleAddRecordModal()"
                                        class="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="px-6 py-3 bg-violet-600 text-white rounded-lg hover:bg-violet-700 transition-all">
                                        Save Record
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6 grid grid-cols-3 gap-6">
                <!-- Left Column -->
                <div class="col-span-2 space-y-6">
                    <!-- Vital Signs Section -->
                    <div class="grid grid-cols-2 gap-6">
                        <!-- Heart Rate -->
                        <div class="bg-indigo-600 rounded-2xl p-6 text-white vital-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-indigo-200">Heart Rate</p>
                                    <div class="flex items-baseline mt-1">
                                        <h2 class="text-4xl font-bold">70</h2>
                                        <span class="ml-2 text-lg">/120</span>
                                    </div>
                                </div>
                                <button class="text-indigo-200 hover:text-white">
                                    <i class="fas fa-heart"></i>
                                </button>
                            </div>
                            <div class="mt-4">
                                <div id="heartRateChart"></div>
                            </div>
                        </div>

                        <!-- Blood Pressure -->
                        <div class="bg-white rounded-2xl p-6 shadow-lg vital-card">
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-gray-500">Blood Pressure</p>
                                    <h2 class="text-4xl font-bold text-gray-900 mt-1">98</h2>
                                </div>
                                <button class="text-blue-500 hover:text-blue-600">
                                    <i class="fas fa-bookmark"></i>
                                </button>
                            </div>
                            <div class="mt-4">
                                <div id="bpChart"></div>
                            </div>
                        </div>
                    </div>

                    <!-- ECG Section -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold">Heart ECG</h3>
                            <div class="flex items-center space-x-2 text-sm">
                                <span class="text-gray-500">72 bmp</span>
                                <span class="text-gray-400">Average</span>
                            </div>
                        </div>
                        <div class="h-64">
                            <canvas id="ecgChart"></canvas>
                        </div>
                        <div class="flex justify-between mt-4 text-sm text-gray-500">
                            <span>Sun</span>
                            <span>Mon</span>
                            <span>Tue</span>
                            <span>Wed</span>
                            <span>Thu</span>
                            <span>Fri</span>
                            <span>Sat</span>
                        </div>
                    </div>

                    <!-- Additional Metrics -->
                    <div class="grid grid-cols-4 gap-6">
                        <div class="bg-white p-4 rounded-xl shadow-lg flex items-center space-x-4">
                            <div class="bg-blue-100 p-3 rounded-lg">
                                <i class="fas fa-heart text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Blood Pressure</p>
                                <p class="font-semibold">120/80</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-lg flex items-center space-x-4">
                            <div class="bg-purple-100 p-3 rounded-lg">
                                <i class="fas fa-heartbeat text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Heart Rate</p>
                                <p class="font-semibold">85 bmp</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-lg flex items-center space-x-4">
                            <div class="bg-green-100 p-3 rounded-lg">
                                <i class="fas fa-tint text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Glucose Level</p>
                                <p class="font-semibold">95 mg/dL</p>
                            </div>
                        </div>
                        <div class="bg-white p-4 rounded-xl shadow-lg flex items-center space-x-4">
                            <div class="bg-red-100 p-3 rounded-lg">
                                <i class="fas fa-flask text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Blood Count</p>
                                <p class="font-semibold">9,850</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Patient Info Card -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                <i class="fas fa-user text-3xl text-gray-400"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-semibold">AR Shakir</h3>
                                <p class="text-gray-500">Patient ID: P-1001</p>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Age</span>
                                <span class="font-medium">42 years</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Blood Type</span>
                                <span class="font-medium">A+</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Weight</span>
                                <span class="font-medium">72 kg</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Height</span>
                                <span class="font-medium">175 cm</span>
                            </div>
                        </div>
                    </div>

                    <!-- Scheduled Appointments -->
                    <div class="bg-white rounded-2xl p-6 shadow-lg">
                        <h3 class="text-lg font-semibold mb-4">Scheduled Appointments</h3>
                        <div class="space-y-4">
                            <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium">Dr. Damian Lewis</h4>
                                        <p class="text-sm text-gray-500">Standard Consult</p>
                                    </div>
                                    <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                        10:00am - 11:00am
                                    </span>
                                </div>
                            </div>
                            <div class="p-4 border border-gray-100 rounded-lg hover:bg-gray-50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-medium">Dr. Mike Taylor</h4>
                                        <p class="text-sm text-gray-500">Premium Consult</p>
                                    </div>
                                    <span class="text-xs text-purple-600 bg-purple-100 px-2 py-1 rounded-full">
                                        2:00pm - 3:00pm
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Message Doctor Button -->
                    <button class="w-full bg-blue-600 text-white rounded-lg py-3 flex items-center justify-center space-x-2 hover:bg-blue-700">
                        <i class="fas fa-message"></i>
                        <span>Message</span>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Add Record Modal Functionality
        function toggleAddRecordModal() {
            const modal = document.getElementById('addRecordModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden'; // Prevent background scrolling
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = ''; // Restore background scrolling
            }
        }

        function addMedicationField() {
            const medicationsList = document.getElementById('medicationsList');
            const newField = document.createElement('div');
            newField.className = 'flex space-x-2';
            newField.innerHTML = `
                <input type="text" name="medications[]" 
                    class="flex-1 px-4 py-3 rounded-lg border border-gray-200 focus:border-violet-500 focus:ring-2 focus:ring-violet-200 transition-all outline-none"
                    placeholder="Enter medication name and dosage">
                <button type="button" onclick="this.parentElement.remove()"
                    class="px-4 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200">
                    <i class="fas fa-minus"></i>
                </button>
            `;
            medicationsList.appendChild(newField);
        }

        // Initialize form handlers
        document.addEventListener('DOMContentLoaded', function() {
            const addRecordBtn = document.getElementById('addRecordBtn');
            const addRecordForm = document.getElementById('addRecordForm');

            addRecordBtn.addEventListener('click', toggleAddRecordModal);

            addRecordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Collect form data
                const formData = new FormData(this);
                const record = {
                    timestamp: new Date().toISOString(),
                    vitals: {
                        heartRate: formData.get('heartRate'),
                        bloodPressure: {
                            systolic: formData.get('systolic'),
                            diastolic: formData.get('diastolic')
                        },
                        temperature: formData.get('temperature'),
                        glucoseLevel: formData.get('glucoseLevel')
                    },
                    notes: formData.get('notes'),
                    medications: Array.from(formData.getAll('medications[]')).filter(med => med.trim() !== '')
                };

                // Here you would typically send the data to your backend
                console.log('New Record:', record);

                // Update charts with new data
                updateChartsWithNewData(record);

                // Show success message
                alert('Record added successfully!');
                
                // Close modal and reset form
                toggleAddRecordModal();
                this.reset();
            });
        });

        function updateChartsWithNewData(record) {
            // Update Heart Rate Chart
            const heartRateChart = Chart.getChart(document.getElementById('heartRateChart'));
            if (heartRateChart) {
                heartRateChart.data.datasets[0].data.push(record.vitals.heartRate);
                heartRateChart.data.datasets[0].data.shift();
                heartRateChart.update();
            }

            // Update Blood Pressure Chart
            const bpChart = Chart.getChart(document.getElementById('bpChart'));
            if (bpChart) {
                bpChart.data.datasets[0].data.push(record.vitals.bloodPressure.systolic);
                bpChart.data.datasets[0].data.shift();
                bpChart.update();
            }

            // Update other charts as needed
        }

        // Chart configurations
        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = 'Inter';

        // ECG Chart
        const ecgCtx = document.getElementById('ecgChart').getContext('2d');
        const generateECGData = () => {
            const points = 100;
            const data = [];
            for (let i = 0; i < points; i++) {
                const x = i / points;
                const noise = Math.random() * 0.1;
                if (i % 20 === 0) {
                    data.push(Math.sin(x * Math.PI * 2) * 2 + noise);
                } else {
                    data.push(Math.sin(x * Math.PI * 2) + noise);
                }
            }
            return data;
        };

        new Chart(ecgCtx, {
            type: 'line',
            data: {
                labels: Array(100).fill(''),
                datasets: [{
                    data: generateECGData(),
                    borderColor: '#4f46e5',
                    borderWidth: 2,
                    tension: 0.4,
                    pointRadius: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        display: false
                    },
                    y: {
                        display: false,
                        min: -3,
                        max: 3
                    }
                },
                animation: {
                    duration: 0
                }
            }
        });

        // Update ECG in real-time
        setInterval(() => {
            const chart = Chart.getChart(ecgCtx);
            chart.data.datasets[0].data = generateECGData();
            chart.update();
        }, 2000);

        // Heart Rate and BP Mini Charts
        const createMiniChart = (selector, color, data) => {
            const ctx = document.getElementById(selector);
            if (!ctx) return;

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 60);
            gradient.addColorStop(0, `${color}20`);
            gradient.addColorStop(1, `${color}00`);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: Array(12).fill(''),
                    datasets: [{
                        data: data,
                        borderColor: color,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        backgroundColor: gradient,
                        pointRadius: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    }
                }
            });
        };

        // Initialize mini charts with sample data
        createMiniChart('heartRateChart', '#ffffff', [65, 68, 70, 72, 68, 70, 72, 70, 68, 72, 70, 72]);
        createMiniChart('bpChart', '#3b82f6', [95, 98, 92, 96, 94, 98, 96, 92, 96, 98, 95, 98]);
    </script>
</body>
</html>

