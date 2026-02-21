<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Queue Dashboard - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .station-card {
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
        }
        .station-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .station-opd { border-left-color: #3b82f6; }
        .station-doctor { border-left-color: #10b981; }
        .station-pharmacy { border-left-color: #f59e0b; }
        .station-cashier { border-left-color: #ef4444; }
        .queue-item {
            transition: all 0.2s ease;
        }
        .queue-item:hover {
            background-color: #f9fafb;
            transform: translateX(4px);
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        
        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Queue Management Dashboard</h1>
                    <p class="text-gray-600 mt-1">Real-time patient queue monitoring across all stations</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="refreshAllQueues()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center space-x-2">
                        <i class="fas fa-sync-alt"></i>
                        <span>Refresh</span>
                    </button>
                    <button onclick="toggleAutoRefresh()" id="autoRefreshBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2">
                        <i class="fas fa-play"></i>
                        <span>Auto Refresh: ON</span>
                    </button>
                </div>
            </div>

            <!-- Station Overview Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8" id="stationCards">
                <!-- Station cards will be dynamically loaded -->
            </div>

            <!-- Queue Details Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- OPD Queue -->
                <div class="station-card station-opd bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-blue-600 text-white p-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-hospital-user mr-2"></i>
                            Out-Patient Department
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Currently Serving:</span>
                            <div id="opd-current" class="text-lg font-semibold text-blue-600">-</div>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Queue Count:</span>
                            <span id="opd-count" class="text-lg font-semibold">0</span>
                        </div>
                        <div id="opd-queue" class="space-y-2 max-h-64 overflow-y-auto">
                            <!-- Queue items will be loaded here -->
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="callNext('opd')" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                <i class="fas fa-bell"></i> Call Next
                            </button>
                            <button onclick="completeService('opd')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fas fa-check"></i> Complete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Doctor Queue -->
                <div class="station-card station-doctor bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-green-600 text-white p-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-user-md mr-2"></i>
                            Doctor's Office
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Currently Serving:</span>
                            <div id="doctor-current" class="text-lg font-semibold text-green-600">-</div>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Queue Count:</span>
                            <span id="doctor-count" class="text-lg font-semibold">0</span>
                        </div>
                        <div id="doctor-queue" class="space-y-2 max-h-64 overflow-y-auto">
                            <!-- Queue items will be loaded here -->
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="callNext('doctor')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fas fa-bell"></i> Call Next
                            </button>
                            <button onclick="completeService('doctor')" class="px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700 text-sm">
                                <i class="fas fa-check"></i> Complete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Pharmacy Queue -->
                <div class="station-card station-pharmacy bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-yellow-600 text-white p-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-pills mr-2"></i>
                            Pharmacy
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Currently Serving:</span>
                            <div id="pharmacy-current" class="text-lg font-semibold text-yellow-600">-</div>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Queue Count:</span>
                            <span id="pharmacy-count" class="text-lg font-semibold">0</span>
                        </div>
                        <div id="pharmacy-queue" class="space-y-2 max-h-64 overflow-y-auto">
                            <!-- Queue items will be loaded here -->
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="callNext('pharmacy')" class="px-3 py-1 bg-yellow-600 text-white rounded hover:bg-yellow-700 text-sm">
                                <i class="fas fa-bell"></i> Call Next
                            </button>
                            <button onclick="completeService('pharmacy')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fas fa-check"></i> Complete
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Cashier Queue -->
                <div class="station-card station-cashier bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-red-600 text-white p-4">
                        <h3 class="text-lg font-semibold flex items-center">
                            <i class="fas fa-cash-register mr-2"></i>
                            Cashier
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Currently Serving:</span>
                            <div id="cashier-current" class="text-lg font-semibold text-red-600">-</div>
                        </div>
                        <div class="mb-4">
                            <span class="text-sm text-gray-600">Queue Count:</span>
                            <span id="cashier-count" class="text-lg font-semibold">0</span>
                        </div>
                        <div id="cashier-queue" class="space-y-2 max-h-64 overflow-y-auto">
                            <!-- Queue items will be loaded here -->
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="callNext('cashier')" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-sm">
                                <i class="fas fa-bell"></i> Call Next
                            </button>
                            <button onclick="completeService('cashier')" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                <i class="fas fa-check"></i> Complete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Panel -->
            <div class="mt-8 bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <button onclick="showAddPatientModal()" class="px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center justify-center space-x-2">
                        <i class="fas fa-user-plus"></i>
                        <span>Add Patient to Queue</span>
                    </button>
                    <button onclick="showMovePatientModal()" class="px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 flex items-center justify-center space-x-2">
                        <i class="fas fa-exchange-alt"></i>
                        <span>Move Patient</span>
                    </button>
                    <button onclick="showRemovePatientModal()" class="px-4 py-3 bg-red-600 text-white rounded-lg hover:bg-red-700 flex items-center justify-center space-x-2">
                        <i class="fas fa-user-minus"></i>
                        <span>Remove Patient</span>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Patient Modal -->
    <div id="addPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <h3 class="text-lg font-semibold mb-4">Add Patient to Queue</h3>
            <form id="addPatientForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                    <select id="patientSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Patient</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Station</label>
                    <select id="stationSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Select Station</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="hideModal('addPatientModal')" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add to Queue</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let autoRefresh = true;
        let refreshInterval;
        let stations = {};
        let currentQueues = {};

        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            loadStations();
            loadPatients();
            refreshAllQueues();
            startAutoRefresh();
        });

        // Load stations
        async function loadStations() {
            try {
                const response = await fetch('api/queue/stations');
                const data = await response.json();
                stations = data.stations.reduce((acc, station) => {
                    acc[station.station_name] = station;
                    return acc;
                }, {});
                
                // Populate station select
                const stationSelect = document.getElementById('stationSelect');
                stationSelect.innerHTML = '<option value="">Select Station</option>';
                data.stations.forEach(station => {
                    stationSelect.innerHTML += `<option value="${station.id}">${station.station_display_name}</option>`;
                });
            } catch (error) {
                console.error('Error loading stations:', error);
            }
        }

        // Load patients
        async function loadPatients() {
            try {
                const response = await fetch('/api/patients');
                const data = await response.json();
                
                const patientSelect = document.getElementById('patientSelect');
                patientSelect.innerHTML = '<option value="">Select Patient</option>';
                data.patients.forEach(patient => {
                    patientSelect.innerHTML += `<option value="${patient.id}">${patient.full_name} (${patient.patient_code})</option>`;
                });
            } catch (error) {
                console.error('Error loading patients:', error);
            }
        }

        // Refresh all queues
        async function refreshAllQueues() {
            try {
                const response = await fetch('api/queue/display/all');
                const data = await response.json();
                
                Object.keys(data.displays).forEach(stationName => {
                    updateStationDisplay(stationName, data.displays[stationName]);
                });
                
                updateStationCards(data.displays);
            } catch (error) {
                console.error('Error refreshing queues:', error);
            }
        }

        // Update station display
        function updateStationDisplay(stationName, displayData) {
            const currentDiv = document.getElementById(`${stationName}-current`);
            const countDiv = document.getElementById(`${stationName}-count`);
            const queueDiv = document.getElementById(`${stationName}-queue`);
            
            if (currentDiv) {
                if (displayData.currently_serving) {
                    currentDiv.innerHTML = `
                        <div class="font-semibold">${displayData.currently_serving.full_name}</div>
                        <div class="text-sm text-gray-600">${displayData.currently_serving.queue_number}</div>
                    `;
                } else {
                    currentDiv.innerHTML = '<span class="text-gray-400">No one being served</span>';
                }
            }
            
            if (countDiv) {
                countDiv.textContent = displayData.queue_count;
            }
            
            if (queueDiv) {
                if (displayData.next_patients.length > 0) {
                    queueDiv.innerHTML = displayData.next_patients.map((patient, index) => `
                        <div class="queue-item flex items-center justify-between p-2 bg-gray-50 rounded">
                            <div class="flex items-center space-x-3">
                                <span class="text-lg font-semibold text-gray-600">${index + 1}</span>
                                <div>
                                    <div class="font-medium">${patient.full_name}</div>
                                    <div class="text-sm text-gray-600">${patient.queue_number}</div>
                                </div>
                            </div>
                            <div class="text-sm text-gray-500">
                                Est. ${index * 15} min
                            </div>
                        </div>
                    `).join('');
                } else {
                    queueDiv.innerHTML = '<div class="text-center text-gray-400 py-4">No patients in queue</div>';
                }
            }
        }

        // Update station cards
        function updateStationCards(displays) {
            const stationCards = document.getElementById('stationCards');
            stationCards.innerHTML = '';
            
            Object.keys(displays).forEach(stationName => {
                const display = displays[stationName];
                const colors = {
                    opd: 'blue',
                    doctor: 'green',
                    pharmacy: 'yellow',
                    cashier: 'red'
                };
                const color = colors[stationName];
                const icons = {
                    opd: 'hospital-user',
                    doctor: 'user-md',
                    pharmacy: 'pills',
                    cashier: 'cash-register'
                };
                const icon = icons[stationName];
                
                stationCards.innerHTML += `
                    <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-${color}-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-${icon} text-${color}-600"></i>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">${display.station.station_display_name}</h3>
                                    <p class="text-sm text-gray-600">Queue Management</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Currently Serving</span>
                                <span class="font-semibold text-${color}-600">
                                    ${display.currently_serving ? display.currently_serving.queue_number : 'None'}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Waiting</span>
                                <span class="font-semibold">${display.queue_count}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Est. Wait</span>
                                <span class="font-semibold">${display.estimated_wait_time} min</span>
                            </div>
                        </div>
                        <div class="mt-4 flex space-x-2">
                            <button onclick="openDisplay('${stationName}')" class="flex-1 px-3 py-2 bg-${color}-600 text-white rounded hover:bg-${color}-700 text-sm">
                                <i class="fas fa-tv"></i> Display
                            </button>
                            <button onclick="manageStation('${stationName}')" class="flex-1 px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 text-sm">
                                <i class="fas fa-cog"></i> Manage
                            </button>
                        </div>
                    </div>
                `;
            });
        }

        // Call next patient
        async function callNext(stationName) {
            try {
                const stationId = stations[stationName].id;
                const response = await fetch('api/queue/call-next', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        station_id: stationId
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    showToast('Next patient called successfully', 'success');
                    playSound();
                    refreshAllQueues();
                } else {
                    showToast(result.message || 'No patients in queue', 'warning');
                }
            } catch (error) {
                console.error('Error calling next patient:', error);
                showToast('Error calling next patient', 'error');
            }
        }

        // Complete service
        async function completeService(stationName) {
            try {
                const display = await getStationDisplay(stationName);
                if (!display.currently_serving) {
                    showToast('No patient currently being served', 'warning');
                    return;
                }
                
                const response = await fetch('api/queue/complete-service', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        queue_id: display.currently_serving.id
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    showToast('Service completed successfully', 'success');
                    refreshAllQueues();
                } else {
                    showToast('Error completing service', 'error');
                }
            } catch (error) {
                console.error('Error completing service:', error);
                showToast('Error completing service', 'error');
            }
        }

        // Get station display data
        async function getStationDisplay(stationName) {
            const stationId = stations[stationName].id;
            const response = await fetch(`api/queue/display/${stationId}`);
            return await response.json();
        }

        // Auto refresh management
        function startAutoRefresh() {
            if (autoRefresh) {
                refreshInterval = setInterval(refreshAllQueues, 10000); // Refresh every 10 seconds
            }
        }

        function stopAutoRefresh() {
            if (refreshInterval) {
                clearInterval(refreshInterval);
            }
        }

        function toggleAutoRefresh() {
            autoRefresh = !autoRefresh;
            const btn = document.getElementById('autoRefreshBtn');
            
            if (autoRefresh) {
                startAutoRefresh();
                btn.innerHTML = '<i class="fas fa-pause"></i><span>Auto Refresh: ON</span>';
                btn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center space-x-2';
            } else {
                stopAutoRefresh();
                btn.innerHTML = '<i class="fas fa-play"></i><span>Auto Refresh: OFF</span>';
                btn.className = 'px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 flex items-center space-x-2';
            }
        }

        // Modal management
        function showAddPatientModal() {
            document.getElementById('addPatientModal').classList.remove('hidden');
        }

        function hideModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        // Form submissions
        document.getElementById('addPatientForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const patientId = document.getElementById('patientSelect').value;
            const stationId = document.getElementById('stationSelect').value;
            
            try {
                const response = await fetch('api/queue/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        patient_id: patientId,
                        station_id: stationId
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    showToast('Patient added to queue successfully', 'success');
                    hideModal('addPatientModal');
                    refreshAllQueues();
                    document.getElementById('addPatientForm').reset();
                } else {
                    showToast('Error adding patient to queue', 'error');
                }
            } catch (error) {
                console.error('Error adding patient:', error);
                showToast('Error adding patient to queue', 'error');
            }
        });

        // Utility functions
        function showToast(message, type = 'info') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : type === 'warning' ? '#f59e0b' : '#3b82f6',
            }).showToast();
        }

        function playSound() {
            // Play notification sound (you can add an audio element here)
            const audio = new Audio('data:audio/wav;base64,UklGRnoGAABXQVZFZm10IBAAAAABAAEAQB8AAEAfAAABAAgAZGF0YQoGAACBhYqFbF1fdJivrJBhNjVgodDbq2EcBj+a2/LDciUFLIHO8tiJNwgZaLvt559NEAxQp+PwtmMcBjiR1/LMeSwFJHfH8N2QQAoUXrTp66hVFApGn+DyvmwhBTGH0fPTgjMGHm7A7+OZURE');
            audio.play().catch(e => console.log('Could not play sound'));
        }

        function openDisplay(stationName) {
            window.open(`/${stationName}-display.php`, '_blank');
        }

        function manageStation(stationName) {
            // Navigate to station management page
            window.location.href = `/${stationName}.php`;
        }

        function showMovePatientModal() {
            // TODO: Implement move patient modal
            showToast('Move patient feature coming soon', 'info');
        }

        function showRemovePatientModal() {
            // TODO: Implement remove patient modal
            showToast('Remove patient feature coming soon', 'info');
        }
    </script>
</body>
</html>
