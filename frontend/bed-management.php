<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bed Management - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Bed Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Track bed availability, transfers, and housekeeping status.</p>
                </div>
            </div>

            <!-- Bed Overview Section -->
            <section id="overview" class="bed-section mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Total Beds</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statTotalBeds">—</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bed text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Occupied</p>
                                <p class="text-2xl font-bold text-red-600 mt-1" id="statOccupied">—</p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-person-bed text-red-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Available</p>
                                <p class="text-2xl font-bold text-green-600 mt-1" id="statAvailable">—</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-circle-check text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">For Cleaning</p>
                                <p class="text-2xl font-bold text-yellow-600 mt-1" id="statCleaning">—</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-broom text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Bed Configuration Section -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-800">Bed Configuration</h2>
                        <button type="button" id="btnAddRoom" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Room
                        </button>
                    </div>
                    
                    <!-- Ward Tabs -->
                    <div class="border-b border-gray-200 mb-4">
                        <nav class="-mb-px flex space-x-8">
                            <button type="button" class="ward-tab active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-ward="icu">
                                ICU
                            </button>
                            <button type="button" class="ward-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-ward="pedia">
                                Pediatrics
                            </button>
                            <button type="button" class="ward-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-ward="obgyne">
                                OB-GYN
                            </button>
                            <button type="button" class="ward-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-ward="surgical">
                                Surgical
                            </button>
                            <button type="button" class="ward-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-ward="medical">
                                Medical
                            </button>
                        </nav>
                    </div>

                    <!-- Bed Grid -->
                    <div id="bedGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        <!-- Beds will be loaded here -->
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Quick Actions</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <button type="button" id="btnBulkClean" class="flex items-center justify-center px-4 py-3 bg-yellow-100 text-yellow-800 rounded-lg hover:bg-yellow-200 transition-colors">
                            <i class="fas fa-broom mr-2"></i>
                            Mark All Available as Clean
                        </button>
                        <button type="button" id="btnBulkMaintenance" class="flex items-center justify-center px-4 py-3 bg-orange-100 text-orange-800 rounded-lg hover:bg-orange-200 transition-colors">
                            <i class="fas fa-tools mr-2"></i>
                            Schedule Maintenance
                        </button>
                        <button type="button" id="btnExportBeds" class="flex items-center justify-center px-4 py-3 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors">
                            <i class="fas fa-file-export mr-2"></i>
                            Export Bed Report
                        </button>
                    </div>
                </div>
            </section>

            <!-- Availability Section -->
            <section id="availability" class="bed-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Bed Availability</h2>
                            <p class="text-sm text-gray-600 mt-1">View available beds by ward and room type.</p>
                        </div>
                        <div class="flex gap-2">
                            <select class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Wards</option>
                                <option value="pedia">Pediatrics</option>
                                <option value="obgyne">OB-GYN</option>
                                <option value="surgical">Surgical</option>
                                <option value="medical">Medical</option>
                            </select>
                            <select class="px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All Room Types</option>
                                <option value="ward">Ward</option>
                                <option value="semi-private">Semi-Private</option>
                                <option value="private">Private</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-bed text-4xl mb-3"></i>
                        <p class="text-sm">Bed availability list will appear here.</p>
                    </div>
                </div>
            </section>

            <!-- Room Transfers Section -->
            <section id="transfers" class="bed-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Room Transfers</h2>
                            <p class="text-sm text-gray-600 mt-1">Manage patient room and ward transfers.</p>
                        </div>
                        <button type="button" id="btnNewTransfer" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-right-left mr-2"></i>New Transfer
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-right-left text-4xl mb-3"></i>
                        <p class="text-sm">No pending room transfers.</p>
                    </div>
                </div>
            </section>

            <!-- Housekeeping Status Section -->
            <section id="housekeeping" class="bed-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Housekeeping Status</h2>
                            <p class="text-sm text-gray-600 mt-1">Beds pending cleaning or maintenance.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-broom text-4xl mb-3"></i>
                        <p class="text-sm">No beds pending housekeeping.</p>
                    </div>
                </div>
            </section>

            <!-- Occupancy Reports Section -->
            <section id="reports" class="bed-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Occupancy Reports</h2>
                            <p class="text-sm text-gray-600 mt-1">Bed occupancy statistics and trends.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-file-export mr-2"></i>Export
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-file-alt text-4xl mb-3"></i>
                        <p class="text-sm">Occupancy reports will appear here.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Add Room Modal -->
    <div id="addRoomModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Room</h3>
                    <button type="button" id="closeAddRoomModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="addRoomForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Ward *</label>
                        <select name="ward" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Ward</option>
                            <option value="icu">ICU</option>
                            <option value="pedia">Pediatrics</option>
                            <option value="obgyne">OB-GYN</option>
                            <option value="surgical">Surgical</option>
                            <option value="medical">Medical</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Number *</label>
                        <input type="text" name="room_number" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="e.g., 101, 201A">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Room Type</label>
                        <select name="room_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="ward">Ward (Multiple beds)</option>
                            <option value="semi-private">Semi-Private (2 beds)</option>
                            <option value="private">Private (1 bed)</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Number of Beds *</label>
                        <input type="number" name="bed_count" required min="1" max="20" value="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelAddRoom" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-plus mr-2"></i>Add Room
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- New Transfer Modal -->
    <div id="transferModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">New Room Transfer</h3>
                    <button type="button" id="closeTransferModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="transferForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                        <select name="patient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Loading patients...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">From Room</label>
                        <input type="text" name="from_room" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" value="Room 101">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">To Room *</label>
                        <select name="to_room" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select Available Room</option>
                            <option value="103">Room 103 - Ward</option>
                            <option value="201">Room 201 - Semi-Private</option>
                            <option value="301">Room 301 - Private</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Reason for Transfer</label>
                        <textarea name="transfer_reason" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Medical necessity, patient request, etc."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelTransfer" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-right-left mr-2"></i>Transfer Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var sections = document.querySelectorAll('.bed-section');
            function showSection(hash) {
                if (!hash) hash = 'overview';
                sections.forEach(function (s) {
                    s.id === hash ? s.classList.remove('hidden') : s.classList.add('hidden');
                });
            }
            function getHash() {
                try { return (window.location.hash || '').replace(/^#/, '') || 'overview'; } catch (e) { return 'overview'; }
            }
            showSection(getHash());
            window.addEventListener('hashchange', function () { showSection(getHash()); });
        })();

        // Modal functionality
        (function () {
            var transferModal = document.getElementById('transferModal');
            var addRoomModal = document.getElementById('addRoomModal');
            var currentWard = 'icu';
            
            // Load admitted patients for bed management
            function loadAdmittedPatients(selectElement) {
                fetch(API_BASE_URL + '/patients/list.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok && data.patients) {
                            selectElement.innerHTML = '<option value="">Select Patient</option>';
                            // Filter for patients who might be admitted
                            const admittedPatients = data.patients.filter(patient => 
                                patient.progress_status && 
                                !patient.progress_status.includes('Completed') &&
                                patient.department
                            );
                            admittedPatients.forEach(patient => {
                                const option = document.createElement('option');
                                option.value = patient.id;
                                option.textContent = `${patient.full_name} - ${patient.patient_code} (${patient.department || 'Ward'})`;
                                selectElement.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading patients:', error);
                        selectElement.innerHTML = '<option value="">Error loading patients</option>';
                    });
            }
            
            // Load beds for current ward from real API
            function loadBeds(ward) {
                const bedGrid = document.getElementById('bedGrid');
                bedGrid.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500"><i class="fas fa-spinner fa-spin mr-2"></i>Loading beds...</div>';

                if (ward === 'icu') {
                    // ICU uses its own existing API
                    fetch(API_BASE_URL + '/icu/patients.php')
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) {
                                // Map ICU patients to bed format
                                const beds = (data.patients || []).map(p => ({
                                    bed_code:     p.bed_code,
                                    patient_name: p.patient_name,
                                    status:       'occupied',
                                    room_no:      'ICU',
                                }));
                                displayBeds(beds, ward);
                            } else {
                                bedGrid.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">Error loading ICU beds</div>';
                            }
                        })
                        .catch(() => {
                            bedGrid.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">Error loading ICU beds</div>';
                        });
                } else {
                    // All other wards use the bed management API
                    fetch(API_BASE_URL + '/bed_management/beds_list.php?ward=' + encodeURIComponent(ward))
                        .then(r => r.json())
                        .then(data => {
                            if (data.ok) {
                                if (data.beds.length === 0) {
                                    bedGrid.innerHTML = `
                                        <div class="col-span-full text-center py-12 text-gray-400">
                                            <i class="fas fa-bed text-4xl mb-3"></i>
                                            <p class="text-sm mb-4">No beds configured for ${ward.toUpperCase()} ward</p>
                                            <button type="button" onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                                <i class="fas fa-plus mr-2"></i>Add First Room
                                            </button>
                                        </div>
                                    `;
                                } else {
                                    displayBeds(data.beds, ward);
                                }
                            } else {
                                bedGrid.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">Error loading beds</div>';
                            }
                        })
                        .catch(() => {
                            bedGrid.innerHTML = '<div class="col-span-full text-center py-8 text-gray-500">Error loading beds</div>';
                        });
                }
            }
            
            // Display beds organized by rooms with collapsible sections
            function displayBeds(beds, ward) {
                const bedGrid = document.getElementById('bedGrid');
                
                if (!beds.length) {
                    bedGrid.innerHTML = `
                        <div class="col-span-full text-center py-12 text-gray-400">
                            <i class="fas fa-bed text-4xl mb-3"></i>
                            <p class="text-sm mb-4">No beds configured for ${ward.toUpperCase()} ward</p>
                            <button type="button" onclick="document.getElementById('addRoomModal').classList.remove('hidden')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>Add First Room
                            </button>
                        </div>
                    `;
                    return;
                }
                
                // Group beds by room
                const roomGroups = groupBedsByRoom(beds, ward);
                
                bedGrid.innerHTML = `
                    <div class="col-span-full space-y-4">
                        ${Object.entries(roomGroups).map(([roomName, roomBeds]) => `
                            <details class="bg-white border border-gray-200 rounded-lg shadow-sm" open>
                                <summary class="flex items-center justify-between p-4 cursor-pointer hover:bg-gray-50 rounded-t-lg">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-door-open text-gray-600"></i>
                                        <span class="font-semibold text-gray-900">${roomName}</span>
                                        <span class="text-sm text-gray-500">(${roomBeds.length} beds)</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        ${getRoomStatusSummary(roomBeds)}
                                        <i class="fas fa-chevron-down text-gray-400 transform transition-transform duration-200"></i>
                                    </div>
                                </summary>
                                <div class="p-4 pt-0 border-t border-gray-100">
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                                        ${roomBeds.map(bed => createBedCard(bed)).join('')}
                                    </div>
                                    <div class="mt-3 pt-3 border-t border-gray-100 flex justify-between items-center">
                                        <div class="text-sm text-gray-600">
                                            Room capacity: ${roomBeds.length} beds
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" onclick="addBedToRoom('${roomName}')" class="text-xs px-3 py-1 bg-blue-100 text-blue-700 rounded hover:bg-blue-200">
                                                <i class="fas fa-plus mr-1"></i>Add Bed
                                            </button>
                                            <button type="button" onclick="editRoom('${roomName}')" class="text-xs px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200">
                                                <i class="fas fa-edit mr-1"></i>Edit Room
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </details>
                        `).join('')}
                    </div>
                `;
                
                // Add event listeners for collapsible functionality
                document.querySelectorAll('details').forEach(details => {
                    details.addEventListener('toggle', function() {
                        const chevron = this.querySelector('.fa-chevron-down');
                        if (this.open) {
                            chevron.style.transform = 'rotate(180deg)';
                        } else {
                            chevron.style.transform = 'rotate(0deg)';
                        }
                    });
                });
            }
            
            // Group beds by room using real room_no from API
            function groupBedsByRoom(beds, ward) {
                const rooms = {};

                beds.forEach(bed => {
                    // Use room_no from API if available, otherwise fall back to ICU grouping
                    const roomLabel = bed.room_no
                        ? `Room ${bed.room_no}`
                        : `Room ${ward.toUpperCase()}`;

                    if (!rooms[roomLabel]) {
                        rooms[roomLabel] = [];
                    }
                    rooms[roomLabel].push(bed);
                });

                return rooms;
            }
            
            // Get room status summary badges
            function getRoomStatusSummary(roomBeds) {
                const statusCount = {
                    occupied: 0,
                    available: 0,
                    cleaning: 0,
                    maintenance: 0
                };
                
                roomBeds.forEach(bed => {
                    const status = bed.status || 'available';
                    statusCount[status]++;
                });
                
                const badges = [];
                if (statusCount.occupied > 0) {
                    badges.push(`<span class="text-xs px-2 py-1 bg-red-100 text-red-700 rounded-full">${statusCount.occupied} occupied</span>`);
                }
                if (statusCount.available > 0) {
                    badges.push(`<span class="text-xs px-2 py-1 bg-green-100 text-green-700 rounded-full">${statusCount.available} available</span>`);
                }
                if (statusCount.cleaning > 0) {
                    badges.push(`<span class="text-xs px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full">${statusCount.cleaning} cleaning</span>`);
                }
                if (statusCount.maintenance > 0) {
                    badges.push(`<span class="text-xs px-2 py-1 bg-orange-100 text-orange-700 rounded-full">${statusCount.maintenance} maintenance</span>`);
                }
                
                return badges.join(' ');
            }
            
            // Create individual bed card
            function createBedCard(bed) {
                const statusColors = {
                    'occupied': 'bg-red-100 border-red-300 text-red-800',
                    'available': 'bg-green-100 border-green-300 text-green-800',
                    'cleaning': 'bg-yellow-100 border-yellow-300 text-yellow-800',
                    'maintenance': 'bg-orange-100 border-orange-300 text-orange-800'
                };
                
                const statusIcons = {
                    'occupied': 'fas fa-user',
                    'available': 'fas fa-check-circle',
                    'cleaning': 'fas fa-broom',
                    'maintenance': 'fas fa-tools'
                };
                
                const status = bed.status || 'available';
                const colorClass = statusColors[status] || statusColors['available'];
                const iconClass = statusIcons[status] || statusIcons['available'];
                
                return `
                    <div class="border-2 rounded-lg p-3 ${colorClass} cursor-pointer hover:shadow-md transition-shadow" onclick="editBed('${bed.bed_code}', '${status}')">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-semibold text-sm">${bed.bed_code}</span>
                            <i class="${iconClass}"></i>
                        </div>
                        <div class="text-xs">
                            ${bed.patient_name ? `<p class="font-medium truncate">${bed.patient_name}</p>` : ''}
                            <p class="capitalize">${status}</p>
                        </div>
                    </div>
                `;
            }
            
            // Add bed to room function
            window.addBedToRoom = function(roomName) {
                alert(`Add bed functionality for ${roomName} will be implemented with backend API.`);
            };
            
            // Edit room function
            window.editRoom = function(roomName) {
                alert(`Edit room functionality for ${roomName} will be implemented with backend API.`);
            };
            
            // Edit bed status
            window.editBed = function(bedCode, currentStatus) {
                const newStatus = prompt(`Change status for ${bedCode}:\n\n1. Available\n2. Occupied\n3. Cleaning\n4. Maintenance\n\nEnter number (1-4):`, '1');
                
                const statusMap = {
                    '1': 'available',
                    '2': 'occupied', 
                    '3': 'cleaning',
                    '4': 'maintenance'
                };
                
                if (statusMap[newStatus]) {
                    // TODO: Update bed status via API
                    alert(`Bed ${bedCode} status will be updated to: ${statusMap[newStatus]}`);
                    loadBeds(currentWard); // Refresh display
                }
            };
            
            // Ward tab switching
            document.querySelectorAll('.ward-tab').forEach(tab => {
                tab.addEventListener('click', function() {
                    // Update active tab
                    document.querySelectorAll('.ward-tab').forEach(t => {
                        t.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');
                    
                    // Load beds for selected ward
                    currentWard = this.dataset.ward;
                    loadBeds(currentWard);
                });
            });
            
            // Add Room Modal
            document.getElementById('btnAddRoom').addEventListener('click', function () {
                addRoomModal.classList.remove('hidden');
            });
            
            document.getElementById('closeAddRoomModal').addEventListener('click', function () {
                addRoomModal.classList.add('hidden');
            });
            
            document.getElementById('cancelAddRoom').addEventListener('click', function () {
                addRoomModal.classList.add('hidden');
            });
            
            document.getElementById('addRoomForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Adding...';

                const data = {
                    ward:       form.querySelector('[name="ward"]')?.value || '',
                    room_no:    form.querySelector('[name="room_number"]')?.value || '',
                    room_type:  form.querySelector('[name="room_type"]')?.value || 'ward',
                    bed_count:  parseInt(form.querySelector('[name="bed_count"]')?.value || '1'),
                };

                fetch(API_BASE_URL + '/bed_management/room_create.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data),
                })
                .then(r => r.json())
                .then(res => {
                    if (res.ok) {
                        alert('Room ' + res.room_no + ' added with ' + res.beds.length + ' bed(s)!');
                        addRoomModal.classList.add('hidden');
                        form.reset();
                        currentWard = data.ward;
                        loadBeds(currentWard);
                    } else {
                        alert('Error: ' + (res.error || 'Failed to add room'));
                    }
                })
                .catch(() => alert('Network error. Please try again.'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Add Room';
                });
            });
            
            // Quick Actions
            document.getElementById('btnBulkClean').addEventListener('click', function () {
                if (confirm('Mark all available beds as clean?')) {
                    alert('Bulk cleaning functionality will be implemented with backend API.');
                }
            });
            
            document.getElementById('btnBulkMaintenance').addEventListener('click', function () {
                alert('Bulk maintenance scheduling will be implemented with backend API.');
            });
            
            document.getElementById('btnExportBeds').addEventListener('click', function () {
                alert('Bed report export will be implemented with backend API.');
            });
            
            // Initialize with ICU beds
            loadBeds('icu');
            
            // Transfer Modal
            document.getElementById('btnNewTransfer').addEventListener('click', function () {
                const patientSelect = transferModal.querySelector('select[name="patient_id"]');
                loadAdmittedPatients(patientSelect);
                transferModal.classList.remove('hidden');
            });
            
            document.getElementById('closeTransferModal').addEventListener('click', function () {
                transferModal.classList.add('hidden');
            });
            
            document.getElementById('cancelTransfer').addEventListener('click', function () {
                transferModal.classList.add('hidden');
            });
            
            // Form submission
            document.getElementById('transferForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Transferring...';

                const data = {
                    patient_id:     parseInt(form.querySelector('[name="patient_id"]')?.value || '0'),
                    to_bed_id:      parseInt(form.querySelector('[name="to_room"]')?.value || '0'),
                    reason:         form.querySelector('[name="reason"]')?.value || '',
                    transferred_at: new Date().toISOString().slice(0, 19).replace('T', ' '),
                };

                // Resolve admission_id from patient
                fetch(API_BASE_URL + '/admissions/list.php?status=admitted')
                    .then(r => r.json())
                    .then(admData => {
                        if (!admData.ok) throw new Error('Could not load admissions');
                        const adm = admData.admissions.find(a => String(a.patient_id) === String(data.patient_id));
                        if (!adm) throw new Error('No active admission found for selected patient');
                        data.admission_id = adm.id;

                        return fetch(API_BASE_URL + '/bed_management/transfer_create.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data),
                        });
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.ok) {
                            alert('Transfer completed! Transfer No: ' + res.transfer_no);
                            transferModal.classList.add('hidden');
                            form.reset();
                            loadBeds(currentWard);
                        } else {
                            alert('Error: ' + (res.error || 'Transfer failed'));
                        }
                    })
                    .catch(err => alert('Error: ' + err.message))
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Transfer';
                    });
            });
            
            // Close modal on outside click
            window.addEventListener('click', function (e) {
                if (e.target === transferModal) {
                    transferModal.classList.add('hidden');
                }
            });
        })();
    </script>
</body>
</html>
