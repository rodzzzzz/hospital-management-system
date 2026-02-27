<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admissions - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Admissions</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage patient admissions, registration, and bed assignments.</p>
                </div>
            </div>

            <!-- Dashboard Section -->
            <section id="dashboard" class="admissions-section mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Today's Admissions</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statTodayAdmissions">—</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bed-pulse text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pending Queue</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statPendingQueue">—</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-users-line text-yellow-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Available Beds</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statAvailableBeds">—</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bed text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pre-admissions</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statPreAdmissions">—</p>
                            </div>
                            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clipboard-check text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Recent Admissions</h2>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-bed-pulse text-4xl mb-3"></i>
                        <p class="text-sm">No recent admissions to display.</p>
                    </div>
                </div>
            </section>

            <!-- Patient Registration Section -->
            <section id="registration" class="admissions-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Patient Registration</h2>
                            <p class="text-sm text-gray-600 mt-1">Register a new patient for admission.</p>
                        </div>
                        <button type="button" id="btnNewPatient" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-user-plus mr-2"></i>New Patient
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-user-plus text-4xl mb-3"></i>
                        <p class="text-sm">Patient registration form coming soon.</p>
                    </div>
                </div>
            </section>

            <!-- Admission Queue Section -->
            <section id="queue" class="admissions-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Admission Queue</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients waiting for ward assignment.</p>
                        </div>
                        <button type="button" id="btnRefreshQueue" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-users-line text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in queue.</p>
                    </div>
                </div>
            </section>

            <!-- Pre-admission Section -->
            <section id="pre-admission" class="admissions-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Pre-admission</h2>
                            <p class="text-sm text-gray-600 mt-1">Scheduled admissions and pre-operative clearances.</p>
                        </div>
                        <button type="button" id="btnSchedulePreAdmission" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Schedule Pre-admission
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-clipboard-check text-4xl mb-3"></i>
                        <p class="text-sm">No pre-admissions scheduled.</p>
                    </div>
                </div>
            </section>

            <!-- Bed Assignment Section -->
            <section id="bed-assignment" class="admissions-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Bed Assignment</h2>
                            <p class="text-sm text-gray-600 mt-1">Assign available beds to admitted patients.</p>
                        </div>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-bed text-4xl mb-3"></i>
                        <p class="text-sm">Bed assignment interface coming soon.</p>
                    </div>
                </div>
            </section>

            <!-- Insurance Verification Section -->
            <section id="insurance" class="admissions-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Insurance Verification</h2>
                            <p class="text-sm text-gray-600 mt-1">Verify PhilHealth and private insurance coverage.</p>
                        </div>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-shield-halved text-4xl mb-3"></i>
                        <p class="text-sm">Insurance verification interface coming soon.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- New Patient Modal -->
    <div id="newPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">New Patient Registration</h3>
                    <button type="button" id="closeNewPatientModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="newPatientForm" class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                            <input type="text" name="first_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                            <input type="text" name="last_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth *</label>
                            <input type="date" name="birth_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gender *</label>
                            <select name="gender" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Number</label>
                            <input type="tel" name="contact_number" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Emergency Contact</label>
                            <input type="tel" name="emergency_contact" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                        <textarea name="address" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Chief Complaint</label>
                        <textarea name="chief_complaint" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Reason for admission..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelNewPatient" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-save mr-2"></i>Register Patient
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pre-admission Modal -->
    <div id="preAdmissionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Schedule Pre-admission</h3>
                    <button type="button" id="closePreAdmissionModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="preAdmissionForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                        <select name="patient_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Loading patients...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Scheduled Date *</label>
                        <input type="date" name="scheduled_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Procedure/Surgery</label>
                        <input type="text" name="procedure" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Attending Physician</label>
                        <input type="text" name="physician" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes</label>
                        <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelPreAdmission" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
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
            var sections = document.querySelectorAll('.admissions-section');

            function showSection(hash) {
                if (!hash) hash = 'dashboard';
                sections.forEach(function (s) {
                    if (s.id === hash) {
                        s.classList.remove('hidden');
                    } else {
                        s.classList.add('hidden');
                    }
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
            var newPatientModal = document.getElementById('newPatientModal');
            var preAdmissionModal = document.getElementById('preAdmissionModal');
            
            // Load patient data for dropdowns
            function loadPatients(selectElement) {
                fetch(API_BASE_URL + '/patients/list.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok && data.patients) {
                            selectElement.innerHTML = '<option value="">Select Patient</option>';
                            data.patients.forEach(patient => {
                                const option = document.createElement('option');
                                option.value = patient.id;
                                option.textContent = `${patient.full_name} - ${patient.patient_code}`;
                                selectElement.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading patients:', error);
                        selectElement.innerHTML = '<option value="">Error loading patients</option>';
                    });
            }
            
            // New Patient Modal
            document.getElementById('btnNewPatient').addEventListener('click', function () {
                newPatientModal.classList.remove('hidden');
            });
            
            document.getElementById('closeNewPatientModal').addEventListener('click', function () {
                newPatientModal.classList.add('hidden');
            });
            
            document.getElementById('cancelNewPatient').addEventListener('click', function () {
                newPatientModal.classList.add('hidden');
            });
            
            // Pre-admission Modal
            document.getElementById('btnSchedulePreAdmission').addEventListener('click', function () {
                const patientSelect = preAdmissionModal.querySelector('select[name="patient_name"]');
                loadPatients(patientSelect);
                preAdmissionModal.classList.remove('hidden');
            });
            
            document.getElementById('closePreAdmissionModal').addEventListener('click', function () {
                preAdmissionModal.classList.add('hidden');
            });
            
            document.getElementById('cancelPreAdmission').addEventListener('click', function () {
                preAdmissionModal.classList.add('hidden');
            });
            
            // Form submissions
            document.getElementById('newPatientForm').addEventListener('submit', function (e) {
                e.preventDefault();
                // TODO: Implement patient registration API call
                alert('Patient registration functionality will be implemented with backend API.');
                newPatientModal.classList.add('hidden');
            });
            
            document.getElementById('preAdmissionForm').addEventListener('submit', function (e) {
                e.preventDefault();
                // TODO: Implement pre-admission scheduling API call
                alert('Pre-admission scheduling functionality will be implemented with backend API.');
                preAdmissionModal.classList.add('hidden');
            });
            
            // Refresh Queue button
            document.getElementById('btnRefreshQueue').addEventListener('click', function () {
                // TODO: Implement queue refresh API call
                alert('Queue refresh functionality will be implemented with backend API.');
            });
            
            // Close modals on outside click
            window.addEventListener('click', function (e) {
                if (e.target === newPatientModal) {
                    newPatientModal.classList.add('hidden');
                }
                if (e.target === preAdmissionModal) {
                    preAdmissionModal.classList.add('hidden');
                }
            });
        })();
    </script>
</body>
</html>
