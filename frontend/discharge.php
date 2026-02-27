<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discharge - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Discharge</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage patient discharge planning, orders, and final clearance.</p>
                </div>
            </div>

            <!-- Dashboard Section -->
            <section id="dashboard" class="discharge-section mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Today's Discharges</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statTodayDischarges">—</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-door-open text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pending Planning</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statPendingPlanning">—</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Awaiting Clearance</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statAwaitingClearance">—</p>
                            </div>
                            <div class="w-12 h-12 bg-orange-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-circle-check text-orange-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Follow-ups Scheduled</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statFollowups">—</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-calendar-check text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Discharge Queue</h2>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-door-open text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in discharge queue.</p>
                    </div>
                </div>
            </section>

            <!-- Discharge Planning Section -->
            <section id="planning" class="discharge-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Discharge Planning</h2>
                            <p class="text-sm text-gray-600 mt-1">Plan and coordinate patient discharge from wards.</p>
                        </div>
                        <button type="button" id="btnNewPlan" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>New Plan
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-clipboard-list text-4xl mb-3"></i>
                        <p class="text-sm">No discharge plans created yet.</p>
                    </div>
                </div>
            </section>

            <!-- Discharge Orders Section -->
            <section id="orders" class="discharge-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Discharge Orders</h2>
                            <p class="text-sm text-gray-600 mt-1">Doctor-issued discharge orders for admitted patients.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-file-medical text-4xl mb-3"></i>
                        <p class="text-sm">No discharge orders pending.</p>
                    </div>
                </div>
            </section>

            <!-- Patient Instructions Section -->
            <section id="instructions" class="discharge-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Patient Instructions</h2>
                            <p class="text-sm text-gray-600 mt-1">Discharge instructions and home care guidelines for patients.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>New Instructions
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-file-lines text-4xl mb-3"></i>
                        <p class="text-sm">No patient instructions generated yet.</p>
                    </div>
                </div>
            </section>

            <!-- Follow-up Schedule Section -->
            <section id="followup" class="discharge-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Follow-up Schedule</h2>
                            <p class="text-sm text-gray-600 mt-1">Schedule post-discharge follow-up appointments.</p>
                        </div>
                        <button type="button" id="btnScheduleFollowup" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-calendar-plus mr-2"></i>Schedule Follow-up
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-calendar-check text-4xl mb-3"></i>
                        <p class="text-sm">No follow-up appointments scheduled.</p>
                    </div>
                </div>
            </section>

            <!-- Final Clearance Section -->
            <section id="clearance" class="discharge-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Final Clearance</h2>
                            <p class="text-sm text-gray-600 mt-1">Verify all discharge requirements are met before patient leaves.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-file-invoice-dollar text-gray-500"></i>
                                <span class="font-medium text-sm text-gray-700">Billing Cleared</span>
                            </div>
                            <p class="text-xs text-gray-500">Final SOA settled by cashier</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-pills text-gray-500"></i>
                                <span class="font-medium text-sm text-gray-700">Pharmacy Cleared</span>
                            </div>
                            <p class="text-xs text-gray-500">Medications dispensed</p>
                        </div>
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center gap-3 mb-2">
                                <i class="fas fa-user-doctor text-gray-500"></i>
                                <span class="font-medium text-sm text-gray-700">Doctor Sign-off</span>
                            </div>
                            <p class="text-xs text-gray-500">Physician discharge order signed</p>
                        </div>
                    </div>

                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-circle-check text-4xl mb-3"></i>
                        <p class="text-sm">No patients awaiting final clearance.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- New Discharge Plan Modal -->
    <div id="dischargePlanModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">New Discharge Plan</h3>
                    <button type="button" id="closePlanModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="dischargePlanForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                        <select name="patient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Loading patients...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Expected Discharge Date *</label>
                        <input type="date" name="discharge_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discharge Destination</label>
                        <select name="destination" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="home">Home</option>
                            <option value="rehab">Rehabilitation Facility</option>
                            <option value="nursing_home">Nursing Home</option>
                            <option value="transfer">Transfer to Another Hospital</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Discharge Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Special instructions, medications, follow-up care..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelPlan" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Create Plan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Schedule Follow-up Modal -->
    <div id="followupModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Schedule Follow-up</h3>
                    <button type="button" id="closeFollowupModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="followupForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                        <select name="patient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Loading patients...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Follow-up Date *</label>
                        <input type="date" name="followup_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Department/Clinic</label>
                        <select name="department" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="opd">OPD</option>
                            <option value="cardiology">Cardiology</option>
                            <option value="surgery">Surgery</option>
                            <option value="pediatrics">Pediatrics</option>
                            <option value="obgyne">OB-GYN</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Attending Physician</label>
                        <input type="text" name="physician" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Follow-up instructions..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelFollowup" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-calendar-plus mr-2"></i>Schedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            var sections = document.querySelectorAll('.discharge-section');
            function showSection(hash) {
                if (!hash) hash = 'dashboard';
                sections.forEach(function (s) {
                    s.id === hash ? s.classList.remove('hidden') : s.classList.add('hidden');
                });
            }
            function getHash() {
                try { return (window.location.hash || '').replace(/^#/, '') || 'dashboard'; } catch (e) { return 'dashboard'; }
            }
            showSection(getHash());
            window.addEventListener('hashchange', function () { showSection(getHash()); });
        })();

        // Modal functionality
        (function () {
            var dischargePlanModal = document.getElementById('dischargePlanModal');
            var followupModal = document.getElementById('followupModal');
            
            // Load admitted patients for discharge management
            function loadAdmittedPatients(selectElement) {
                fetch(API_BASE_URL + '/patients/list.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok && data.patients) {
                            selectElement.innerHTML = '<option value="">Select Patient</option>';
                            // Filter for patients who might be ready for discharge
                            const admittedPatients = data.patients.filter(patient => 
                                patient.progress_status && 
                                !patient.progress_status.includes('Completed') &&
                                (patient.department || patient.initial_location)
                            );
                            admittedPatients.forEach(patient => {
                                const option = document.createElement('option');
                                option.value = patient.id;
                                option.textContent = `${patient.full_name} - ${patient.patient_code} (${patient.department || patient.initial_location || 'Ward'})`;
                                selectElement.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading patients:', error);
                        selectElement.innerHTML = '<option value="">Error loading patients</option>';
                    });
            }
            
            // Discharge Plan Modal
            document.getElementById('btnNewPlan').addEventListener('click', function () {
                const patientSelect = dischargePlanModal.querySelector('select[name="patient_id"]');
                loadAdmittedPatients(patientSelect);
                dischargePlanModal.classList.remove('hidden');
            });
            
            document.getElementById('closePlanModal').addEventListener('click', function () {
                dischargePlanModal.classList.add('hidden');
            });
            
            document.getElementById('cancelPlan').addEventListener('click', function () {
                dischargePlanModal.classList.add('hidden');
            });
            
            // Follow-up Modal
            document.getElementById('btnScheduleFollowup').addEventListener('click', function () {
                const patientSelect = followupModal.querySelector('select[name="patient_id"]');
                loadAdmittedPatients(patientSelect);
                followupModal.classList.remove('hidden');
            });
            
            document.getElementById('closeFollowupModal').addEventListener('click', function () {
                followupModal.classList.add('hidden');
            });
            
            document.getElementById('cancelFollowup').addEventListener('click', function () {
                followupModal.classList.add('hidden');
            });
            
            // Form submissions
            document.getElementById('dischargePlanForm').addEventListener('submit', function (e) {
                e.preventDefault();
                alert('Discharge planning functionality will be implemented with backend API.');
                dischargePlanModal.classList.add('hidden');
            });
            
            document.getElementById('followupForm').addEventListener('submit', function (e) {
                e.preventDefault();
                alert('Follow-up scheduling functionality will be implemented with backend API.');
                followupModal.classList.add('hidden');
            });
            
            // Close modals on outside click
            window.addEventListener('click', function (e) {
                if (e.target === dischargePlanModal) {
                    dischargePlanModal.classList.add('hidden');
                }
                if (e.target === followupModal) {
                    followupModal.classList.add('hidden');
                }
            });
        })();
    </script>
</body>
</html>
