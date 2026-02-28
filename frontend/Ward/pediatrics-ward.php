<?php require_once __DIR__ . '/../auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pediatrics Ward - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .bed-card { transition: all 0.3s ease; }
        .bed-card:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        .status-occupied { border-left: 4px solid #ef4444; }
        .status-available { border-left: 4px solid #10b981; }
        .status-cleaning { border-left: 4px solid #f59e0b; }
        .status-critical { border-left: 4px solid #dc2626; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.7; } }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/ward-sidebar.php'; ?>
        <main class="ml-64 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Pediatrics Ward</h1>
                    <p class="text-sm text-gray-600 mt-1">Daily patient bed monitoring and care tracking</p>
                </div>
                <div class="flex gap-3">
                    <button type="button" id="btnAddPatient" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Admit Patient
                    </button>
                    <button type="button" id="btnRefreshPedia" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                        <i class="fas fa-rotate mr-2"></i>Refresh
                    </button>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Total Beds</p>
                            <p class="text-2xl font-bold text-gray-900" id="statTotalBeds">20</p>
                        </div>
                        <i class="fas fa-bed text-blue-600 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Occupied</p>
                            <p class="text-2xl font-bold text-red-600" id="statOccupied">0</p>
                        </div>
                        <i class="fas fa-user-injured text-red-600 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Available</p>
                            <p class="text-2xl font-bold text-green-600" id="statAvailable">20</p>
                        </div>
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Critical</p>
                            <p class="text-2xl font-bold text-red-700" id="statCritical">0</p>
                        </div>
                        <i class="fas fa-exclamation-triangle text-red-700 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Ward Tabs -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex gap-4">
                        <button class="ward-tab px-4 py-3 font-medium text-blue-600 border-b-2 border-blue-600" data-ward="ward-a">
                            Pedia Ward A
                        </button>
                        <button class="ward-tab px-4 py-3 font-medium text-gray-500 hover:text-gray-700" data-ward="ward-b">
                            Pedia Ward B
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Pedia Ward A -->
            <div id="ward-a" class="ward-content">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pedia Ward A - Bed Overview</h2>
                    <div id="wardABeds" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <!-- Beds will be loaded here -->
                    </div>
                </div>
            </div>

            <!-- Pedia Ward B -->
            <div id="ward-b" class="ward-content hidden">
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Pedia Ward B - Bed Overview</h2>
                    <div id="wardBBeds" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <!-- Beds will be loaded here -->
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Patient Details Modal -->
    <div id="patientDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white flex items-center justify-between p-6 border-b z-10">
                    <h3 class="text-lg font-semibold text-gray-900">Patient Details</h3>
                    <button type="button" id="closeDetailsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <div id="patientDetailsContent" class="p-6">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Update Vitals Modal -->
    <div id="vitalsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Update Vital Signs</h3>
                    <button type="button" id="closeVitalsModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="vitalsForm" class="p-6">
                    <input type="hidden" name="bed_id" value="">
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Temperature (°C)</label>
                            <input type="number" step="0.1" name="temperature" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="36.5">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Heart Rate (bpm)</label>
                            <input type="number" name="heart_rate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="80">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Blood Pressure</label>
                            <input type="text" name="blood_pressure" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="120/80">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Respiratory Rate</label>
                            <input type="number" name="respiratory_rate" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="20">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">O2 Saturation (%)</label>
                            <input type="number" name="oxygen_saturation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="98">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="25.5">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Additional observations..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelVitals" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Save Vitals
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Progress Note Modal -->
    <div id="progressNoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Add Progress Note</h3>
                    <button type="button" id="closeProgressModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="progressNoteForm" class="p-6">
                    <input type="hidden" name="bed_id" value="">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note Type</label>
                        <select name="note_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="daily_rounds">Daily Rounds</option>
                            <option value="condition_change">Condition Change</option>
                            <option value="treatment_update">Treatment Update</option>
                            <option value="medication">Medication</option>
                            <option value="general">General Note</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Progress Note *</label>
                        <textarea name="note_text" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter detailed progress note..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Recorded By</label>
                        <input type="text" name="recorded_by" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your name">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelProgress" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Save Note
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Sample data structure for beds (will be replaced with API calls)
        const bedsData = {
            'ward-a': [
                {
                    id: 1,
                    bedNumber: 'A-01',
                    status: 'occupied',
                    patient: {
                        name: 'Maria Santos',
                        age: 8,
                        gender: 'Female',
                        admissionDate: '2026-02-25',
                        diagnosis: 'Pneumonia',
                        condition: 'Stable',
                        allergies: 'Penicillin',
                        bloodType: 'O+',
                        guardian: 'Rosa Santos (Mother)',
                        contact: '0917-123-4567'
                    },
                    vitals: {
                        temperature: 37.2,
                        heartRate: 95,
                        bloodPressure: '110/70',
                        respiratoryRate: 22,
                        oxygenSaturation: 97,
                        lastUpdated: '2026-02-28 14:30'
                    },
                    treatment: 'IV Antibiotics, Oxygen support',
                    notes: []
                },
                {
                    id: 2,
                    bedNumber: 'A-02',
                    status: 'occupied',
                    patient: {
                        name: 'Juan Dela Cruz',
                        age: 5,
                        gender: 'Male',
                        admissionDate: '2026-02-27',
                        diagnosis: 'Dengue Fever',
                        condition: 'Critical',
                        allergies: 'None',
                        bloodType: 'A+',
                        guardian: 'Pedro Dela Cruz (Father)',
                        contact: '0918-234-5678'
                    },
                    vitals: {
                        temperature: 39.5,
                        heartRate: 120,
                        bloodPressure: '90/60',
                        respiratoryRate: 28,
                        oxygenSaturation: 94,
                        lastUpdated: '2026-02-28 15:00'
                    },
                    treatment: 'IV Fluids, Platelet monitoring',
                    notes: []
                }
            ],
            'ward-b': [
                {
                    id: 11,
                    bedNumber: 'B-01',
                    status: 'occupied',
                    patient: {
                        name: 'Anna Reyes',
                        age: 3,
                        gender: 'Female',
                        admissionDate: '2026-02-26',
                        diagnosis: 'Gastroenteritis',
                        condition: 'Improving',
                        allergies: 'None',
                        bloodType: 'B+',
                        guardian: 'Linda Reyes (Mother)',
                        contact: '0919-345-6789'
                    },
                    vitals: {
                        temperature: 36.8,
                        heartRate: 100,
                        bloodPressure: '95/65',
                        respiratoryRate: 24,
                        oxygenSaturation: 99,
                        lastUpdated: '2026-02-28 13:45'
                    },
                    treatment: 'Oral rehydration, Probiotics',
                    notes: []
                }
            ]
        };

        // Add empty beds for demonstration
        for (let i = 2; i <= 10; i++) {
            if (i > 2) {
                bedsData['ward-a'].push({
                    id: i,
                    bedNumber: `A-${String(i).padStart(2, '0')}`,
                    status: 'available',
                    patient: null
                });
            }
        }

        for (let i = 2; i <= 10; i++) {
            bedsData['ward-b'].push({
                id: 10 + i,
                bedNumber: `B-${String(i).padStart(2, '0')}`,
                status: 'available',
                patient: null
            });
        }

        let currentWard = 'ward-a';

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            loadWardBeds(currentWard);
            updateStatistics();
            setupEventListeners();
        });

        function setupEventListeners() {
            // Ward tabs
            document.querySelectorAll('.ward-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    const ward = this.dataset.ward;
                    switchWard(ward);
                });
            });

            // Refresh button
            document.getElementById('btnRefreshPedia').addEventListener('click', function() {
                loadWardBeds(currentWard);
                updateStatistics();
            });

            // Modal close buttons
            document.getElementById('closeDetailsModal').addEventListener('click', () => {
                document.getElementById('patientDetailsModal').classList.add('hidden');
            });

            document.getElementById('closeVitalsModal').addEventListener('click', () => {
                document.getElementById('vitalsModal').classList.add('hidden');
            });

            document.getElementById('cancelVitals').addEventListener('click', () => {
                document.getElementById('vitalsModal').classList.add('hidden');
            });

            document.getElementById('closeProgressModal').addEventListener('click', () => {
                document.getElementById('progressNoteModal').classList.add('hidden');
            });

            document.getElementById('cancelProgress').addEventListener('click', () => {
                document.getElementById('progressNoteModal').classList.add('hidden');
            });

            // Forms
            document.getElementById('vitalsForm').addEventListener('submit', handleVitalsSubmit);
            document.getElementById('progressNoteForm').addEventListener('submit', handleProgressNoteSubmit);
        }

        function switchWard(ward) {
            currentWard = ward;
            
            // Update tabs
            document.querySelectorAll('.ward-tab').forEach(tab => {
                if (tab.dataset.ward === ward) {
                    tab.classList.add('text-blue-600', 'border-b-2', 'border-blue-600');
                    tab.classList.remove('text-gray-500');
                } else {
                    tab.classList.remove('text-blue-600', 'border-b-2', 'border-blue-600');
                    tab.classList.add('text-gray-500');
                }
            });

            // Update content
            document.querySelectorAll('.ward-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(ward).classList.remove('hidden');

            loadWardBeds(ward);
        }

        function loadWardBeds(ward) {
            const containerId = ward === 'ward-a' ? 'wardABeds' : 'wardBBeds';
            const container = document.getElementById(containerId);
            const beds = bedsData[ward] || [];

            container.innerHTML = beds.map(bed => createBedCard(bed)).join('');
        }

        function createBedCard(bed) {
            const statusColors = {
                'occupied': 'status-occupied',
                'available': 'status-available',
                'cleaning': 'status-cleaning',
                'critical': 'status-critical'
            };

            const statusClass = bed.patient && bed.patient.condition === 'Critical' ? 'status-critical' : statusColors[bed.status];

            if (bed.status === 'available') {
                return `
                    <div class="bed-card bg-white rounded-lg shadow p-4 ${statusClass}">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-lg font-bold text-gray-900">${bed.bedNumber}</span>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Available
                            </span>
                        </div>
                        <div class="text-center py-8 text-gray-400">
                            <i class="fas fa-bed text-3xl mb-2"></i>
                            <p class="text-sm">Bed Available</p>
                        </div>
                    </div>
                `;
            }

            const patient = bed.patient;
            const conditionColors = {
                'Stable': 'bg-green-100 text-green-800',
                'Improving': 'bg-blue-100 text-blue-800',
                'Critical': 'bg-red-100 text-red-800',
                'Monitoring': 'bg-yellow-100 text-yellow-800'
            };

            return `
                <div class="bed-card bg-white rounded-lg shadow p-4 ${statusClass} cursor-pointer" onclick="showPatientDetails(${bed.id})">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-lg font-bold text-gray-900">${bed.bedNumber}</span>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${conditionColors[patient.condition] || 'bg-gray-100 text-gray-800'}">
                            ${patient.condition}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <h4 class="font-semibold text-gray-900">${patient.name}</h4>
                        <p class="text-sm text-gray-600">${patient.age} years • ${patient.gender}</p>
                        <p class="text-sm text-gray-600 mt-1"><i class="fas fa-stethoscope mr-1"></i>${patient.diagnosis}</p>
                    </div>

                    <div class="border-t pt-3 mb-3">
                        <p class="text-xs text-gray-500 mb-2">Latest Vitals (${bed.vitals.lastUpdated.split(' ')[1]})</p>
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <span class="text-gray-500">Temp:</span>
                                <span class="font-semibold ml-1">${bed.vitals.temperature}°C</span>
                            </div>
                            <div>
                                <span class="text-gray-500">HR:</span>
                                <span class="font-semibold ml-1">${bed.vitals.heartRate} bpm</span>
                            </div>
                            <div>
                                <span class="text-gray-500">BP:</span>
                                <span class="font-semibold ml-1">${bed.vitals.bloodPressure}</span>
                            </div>
                            <div>
                                <span class="text-gray-500">SpO2:</span>
                                <span class="font-semibold ml-1">${bed.vitals.oxygenSaturation}%</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-2">
                        <button onclick="event.stopPropagation(); openVitalsModal(${bed.id})" class="flex-1 px-3 py-2 text-xs bg-blue-50 text-blue-600 rounded hover:bg-blue-100">
                            <i class="fas fa-heartbeat mr-1"></i>Vitals
                        </button>
                        <button onclick="event.stopPropagation(); openProgressModal(${bed.id})" class="flex-1 px-3 py-2 text-xs bg-green-50 text-green-600 rounded hover:bg-green-100">
                            <i class="fas fa-notes-medical mr-1"></i>Note
                        </button>
                    </div>
                </div>
            `;
        }

        function showPatientDetails(bedId) {
            const bed = findBedById(bedId);
            if (!bed || !bed.patient) return;

            const patient = bed.patient;
            const vitals = bed.vitals;

            const content = `
                <div class="space-y-6">
                    <!-- Patient Demographics -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-blue-600"></i>Patient Information
                        </h4>
                        <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg">
                            <div><span class="text-sm text-gray-500">Name:</span> <span class="font-semibold">${patient.name}</span></div>
                            <div><span class="text-sm text-gray-500">Age:</span> <span class="font-semibold">${patient.age} years</span></div>
                            <div><span class="text-sm text-gray-500">Gender:</span> <span class="font-semibold">${patient.gender}</span></div>
                            <div><span class="text-sm text-gray-500">Blood Type:</span> <span class="font-semibold">${patient.bloodType}</span></div>
                            <div><span class="text-sm text-gray-500">Bed:</span> <span class="font-semibold">${bed.bedNumber}</span></div>
                            <div><span class="text-sm text-gray-500">Admission:</span> <span class="font-semibold">${patient.admissionDate}</span></div>
                            <div class="col-span-2"><span class="text-sm text-gray-500">Guardian:</span> <span class="font-semibold">${patient.guardian}</span></div>
                            <div class="col-span-2"><span class="text-sm text-gray-500">Contact:</span> <span class="font-semibold">${patient.contact}</span></div>
                        </div>
                    </div>

                    <!-- Medical Condition -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-stethoscope mr-2 text-red-600"></i>Medical Condition
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="mb-2"><span class="text-sm text-gray-500">Diagnosis:</span> <span class="font-semibold">${patient.diagnosis}</span></div>
                            <div class="mb-2"><span class="text-sm text-gray-500">Condition:</span> <span class="font-semibold">${patient.condition}</span></div>
                            <div><span class="text-sm text-gray-500">Allergies:</span> <span class="font-semibold ${patient.allergies !== 'None' ? 'text-red-600' : ''}">${patient.allergies}</span></div>
                        </div>
                    </div>

                    <!-- Vital Signs -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-heartbeat mr-2 text-pink-600"></i>Current Vital Signs
                        </h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-blue-50 p-4 rounded-lg text-center">
                                <i class="fas fa-thermometer-half text-blue-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Temperature</p>
                                <p class="text-xl font-bold text-gray-900">${vitals.temperature}°C</p>
                            </div>
                            <div class="bg-red-50 p-4 rounded-lg text-center">
                                <i class="fas fa-heartbeat text-red-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Heart Rate</p>
                                <p class="text-xl font-bold text-gray-900">${vitals.heartRate} bpm</p>
                            </div>
                            <div class="bg-purple-50 p-4 rounded-lg text-center">
                                <i class="fas fa-tint text-purple-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Blood Pressure</p>
                                <p class="text-xl font-bold text-gray-900">${vitals.bloodPressure}</p>
                            </div>
                            <div class="bg-green-50 p-4 rounded-lg text-center">
                                <i class="fas fa-lungs text-green-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Respiratory</p>
                                <p class="text-xl font-bold text-gray-900">${vitals.respiratoryRate}/min</p>
                            </div>
                            <div class="bg-cyan-50 p-4 rounded-lg text-center">
                                <i class="fas fa-wind text-cyan-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">SpO2</p>
                                <p class="text-xl font-bold text-gray-900">${vitals.oxygenSaturation}%</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg text-center">
                                <i class="fas fa-clock text-gray-600 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600">Last Updated</p>
                                <p class="text-sm font-semibold text-gray-900">${vitals.lastUpdated}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Treatment Plan -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-prescription mr-2 text-green-600"></i>Treatment Plan
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">${bed.treatment}</p>
                        </div>
                    </div>

                    <!-- Progress Notes -->
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
                            <i class="fas fa-clipboard-list mr-2 text-yellow-600"></i>Daily Progress Notes
                        </h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            ${bed.notes && bed.notes.length > 0 ? 
                                bed.notes.map(note => `
                                    <div class="mb-3 pb-3 border-b last:border-b-0">
                                        <div class="flex justify-between items-start mb-1">
                                            <span class="text-sm font-semibold text-gray-900">${note.type}</span>
                                            <span class="text-xs text-gray-500">${note.timestamp}</span>
                                        </div>
                                        <p class="text-sm text-gray-700">${note.text}</p>
                                        <p class="text-xs text-gray-500 mt-1">By: ${note.recordedBy}</p>
                                    </div>
                                `).join('') :
                                '<p class="text-sm text-gray-500 text-center py-4">No progress notes recorded yet.</p>'
                            }
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex gap-3 pt-4 border-t">
                        <button onclick="openVitalsModal(${bed.id})" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-heartbeat mr-2"></i>Update Vitals
                        </button>
                        <button onclick="openProgressModal(${bed.id})" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-notes-medical mr-2"></i>Add Progress Note
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('patientDetailsContent').innerHTML = content;
            document.getElementById('patientDetailsModal').classList.remove('hidden');
        }

        function openVitalsModal(bedId) {
            const bed = findBedById(bedId);
            if (!bed) return;

            document.querySelector('#vitalsForm input[name="bed_id"]').value = bedId;
            
            if (bed.vitals) {
                document.querySelector('#vitalsForm input[name="temperature"]').value = bed.vitals.temperature;
                document.querySelector('#vitalsForm input[name="heart_rate"]').value = bed.vitals.heartRate;
                document.querySelector('#vitalsForm input[name="blood_pressure"]').value = bed.vitals.bloodPressure;
                document.querySelector('#vitalsForm input[name="respiratory_rate"]').value = bed.vitals.respiratoryRate;
                document.querySelector('#vitalsForm input[name="oxygen_saturation"]').value = bed.vitals.oxygenSaturation;
            }

            document.getElementById('vitalsModal').classList.remove('hidden');
        }

        function openProgressModal(bedId) {
            document.querySelector('#progressNoteForm input[name="bed_id"]').value = bedId;
            document.getElementById('progressNoteModal').classList.remove('hidden');
        }

        function handleVitalsSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const bedId = parseInt(formData.get('bed_id'));
            
            // In real implementation, this would call an API
            console.log('Saving vitals for bed:', bedId, Object.fromEntries(formData));
            
            alert('Vital signs updated successfully!');
            document.getElementById('vitalsModal').classList.add('hidden');
            e.target.reset();
            
            // Refresh the display
            loadWardBeds(currentWard);
        }

        function handleProgressNoteSubmit(e) {
            e.preventDefault();
            const formData = new FormData(e.target);
            const bedId = parseInt(formData.get('bed_id'));
            
            // In real implementation, this would call an API
            console.log('Saving progress note for bed:', bedId, Object.fromEntries(formData));
            
            alert('Progress note added successfully!');
            document.getElementById('progressNoteModal').classList.add('hidden');
            e.target.reset();
        }

        function findBedById(bedId) {
            for (const ward in bedsData) {
                const bed = bedsData[ward].find(b => b.id === bedId);
                if (bed) return bed;
            }
            return null;
        }

        function updateStatistics() {
            let totalBeds = 0;
            let occupied = 0;
            let critical = 0;

            for (const ward in bedsData) {
                totalBeds += bedsData[ward].length;
                bedsData[ward].forEach(bed => {
                    if (bed.status === 'occupied') {
                        occupied++;
                        if (bed.patient && bed.patient.condition === 'Critical') {
                            critical++;
                        }
                    }
                });
            }

            const available = totalBeds - occupied;

            document.getElementById('statTotalBeds').textContent = totalBeds;
            document.getElementById('statOccupied').textContent = occupied;
            document.getElementById('statAvailable').textContent = available;
            document.getElementById('statCritical').textContent = critical;
        }
    </script>
</body>
</html>
