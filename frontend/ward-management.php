<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Management - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ward Management</h1>
                    <p class="text-sm text-gray-600 mt-1">Oversee ward patients, nurses' notes, and ward census.</p>
                </div>
            </div>

            <!-- Dashboard Section -->
            <section id="dashboard" class="ward-section mb-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Pediatrics</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statPedia">—</p>
                            </div>
                            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-child text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">OB-GYN</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statObgyne">—</p>
                            </div>
                            <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-venus text-pink-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Surgical</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statSurgical">—</p>
                            </div>
                            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-scalpel text-red-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">Medical</p>
                                <p class="text-2xl font-bold text-gray-900 mt-1" id="statMedical">—</p>
                            </div>
                            <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                                <i class="fas fa-heart-pulse text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-4">Ward Overview</h2>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-hospital text-4xl mb-3"></i>
                        <p class="text-sm">Ward overview data will appear here.</p>
                    </div>
                </div>
            </section>

            <!-- Pediatrics Ward Section -->
            <section id="pedia" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Pediatrics Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Pediatrics.</p>
                        </div>
                        <button type="button" id="btnRefreshPedia" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-child text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in Pediatrics Ward.</p>
                    </div>
                </div>
            </section>

            <!-- OB-GYN Ward Section -->
            <section id="obgyne" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">OB-GYN Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Obstetrics & Gynecology.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-venus text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in OB-GYN Ward.</p>
                    </div>
                </div>
            </section>

            <!-- Surgical Ward Section -->
            <section id="surgical" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Surgical Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Surgery.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-scalpel text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in Surgical Ward.</p>
                    </div>
                </div>
            </section>

            <!-- Medical Ward Section -->
            <section id="medical" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Medical Ward</h2>
                            <p class="text-sm text-gray-600 mt-1">Patients admitted under Internal Medicine.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-rotate mr-2"></i>Refresh
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-heart-pulse text-4xl mb-3"></i>
                        <p class="text-sm">No patients currently in Medical Ward.</p>
                    </div>
                </div>
            </section>

            <!-- Ward Census Section -->
            <section id="census" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Ward Census</h2>
                            <p class="text-sm text-gray-600 mt-1">Daily patient census across all wards.</p>
                        </div>
                        <button type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition-colors">
                            <i class="fas fa-file-export mr-2"></i>Export
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-list-check text-4xl mb-3"></i>
                        <p class="text-sm">Census data will appear here.</p>
                    </div>
                </div>
            </section>

            <!-- Nurse's Notes Section -->
            <section id="nurses-notes" class="ward-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Nurse's Notes</h2>
                            <p class="text-sm text-gray-600 mt-1">Nursing notes and shift reports for admitted patients.</p>
                        </div>
                        <button type="button" id="btnAddNote" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>Add Note
                        </button>
                    </div>
                    <div class="text-center py-12 text-gray-400">
                        <i class="fas fa-notes-medical text-4xl mb-3"></i>
                        <p class="text-sm">No nursing notes recorded yet.</p>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Add Nurse Note Modal -->
    <div id="addNoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
                <div class="flex items-center justify-between p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Add Nurse's Note</h3>
                    <button type="button" id="closeNoteModal" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <form id="addNoteForm" class="p-6">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Patient *</label>
                        <select name="patient_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Loading patients...</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note Type</label>
                        <select name="note_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="assessment">Assessment</option>
                            <option value="medication">Medication</option>
                            <option value="vital_signs">Vital Signs</option>
                            <option value="general">General Note</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Note *</label>
                        <textarea name="note_content" rows="4" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter nursing note..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelNote" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
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
        (function () {
            var sections = document.querySelectorAll('.ward-section');
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
            var addNoteModal = document.getElementById('addNoteModal');
            
            // Load admitted patients for ward management
            function loadAdmittedPatients(selectElement) {
                fetch(API_BASE_URL + '/patients/list.php')
                    .then(response => response.json())
                    .then(data => {
                        if (data.ok && data.patients) {
                            selectElement.innerHTML = '<option value="">Select Patient</option>';
                            // Filter for patients who might be admitted (have recent activity)
                            const recentPatients = data.patients.filter(patient => 
                                patient.progress_status && 
                                !patient.progress_status.includes('Completed')
                            );
                            recentPatients.forEach(patient => {
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
            
            // Add Note Modal
            document.getElementById('btnAddNote').addEventListener('click', function () {
                const patientSelect = addNoteModal.querySelector('select[name="patient_id"]');
                loadAdmittedPatients(patientSelect);
                addNoteModal.classList.remove('hidden');
            });
            
            document.getElementById('closeNoteModal').addEventListener('click', function () {
                addNoteModal.classList.add('hidden');
            });
            
            document.getElementById('cancelNote').addEventListener('click', function () {
                addNoteModal.classList.add('hidden');
            });
            
            // Form submission
            document.getElementById('addNoteForm').addEventListener('submit', function (e) {
                e.preventDefault();
                alert('Nurse note functionality will be implemented with backend API.');
                addNoteModal.classList.add('hidden');
            });
            
            // Refresh buttons
            document.getElementById('btnRefreshPedia').addEventListener('click', function () {
                alert('Ward refresh functionality will be implemented with backend API.');
            });
            
            // Close modal on outside click
            window.addEventListener('click', function (e) {
                if (e.target === addNoteModal) {
                    addNoteModal.classList.add('hidden');
                }
            });
        })();
    </script>
</body>
</html>
