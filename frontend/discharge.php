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

            <!-- Discharge Planning Section (unified) -->
            <section id="planning" class="discharge-section mb-8 hidden">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Discharge Planning</h2>
                            <p class="text-sm text-gray-600 mt-1">Plan and coordinate patient discharge from wards.</p>
                        </div>
                        <div class="flex gap-2">
                            <button type="button" id="btnNewPlan" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-plus mr-2"></i>New Plan
                            </button>
                            <button type="button" id="btnScheduleFollowup" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                <i class="fas fa-calendar-plus mr-2"></i>Schedule Follow-up
                            </button>
                        </div>
                    </div>

                    <!-- Inner Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="-mb-px flex space-x-6">
                            <button type="button" class="planning-tab active py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600" data-panel="tab-orders">
                                <i class="fas fa-file-medical mr-1"></i>Discharge Orders
                            </button>
                            <button type="button" class="planning-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-panel="tab-instructions">
                                <i class="fas fa-file-lines mr-1"></i>Patient Instructions
                            </button>
                            <button type="button" class="planning-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-panel="tab-followup">
                                <i class="fas fa-calendar-check mr-1"></i>Follow-up Schedule
                            </button>
                            <button type="button" class="planning-tab py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-panel="tab-clearance">
                                <i class="fas fa-circle-check mr-1"></i>Final Clearance
                            </button>
                        </nav>
                    </div>

                    <!-- Discharge Orders Panel -->
                    <div id="tab-orders" class="planning-panel">
                        <div class="text-center py-12 text-gray-400">
                            <i class="fas fa-file-medical text-4xl mb-3"></i>
                            <p class="text-sm">No discharge orders pending.</p>
                        </div>
                    </div>

                    <!-- Patient Instructions Panel -->
                    <div id="tab-instructions" class="planning-panel hidden">
                        <div class="text-center py-12 text-gray-400">
                            <i class="fas fa-file-lines text-4xl mb-3"></i>
                            <p class="text-sm">No patient instructions generated yet.</p>
                        </div>
                    </div>

                    <!-- Follow-up Schedule Panel -->
                    <div id="tab-followup" class="planning-panel hidden">
                        <div class="text-center py-12 text-gray-400">
                            <i class="fas fa-calendar-check text-4xl mb-3"></i>
                            <p class="text-sm">No follow-up appointments scheduled.</p>
                        </div>
                    </div>

                    <!-- Final Clearance Panel -->
                    <div id="tab-clearance" class="planning-panel hidden">
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

        // Planning inner tab switching
        (function () {
            document.querySelectorAll('.planning-tab').forEach(function (tab) {
                tab.addEventListener('click', function () {
                    document.querySelectorAll('.planning-tab').forEach(function (t) {
                        t.classList.remove('active', 'border-blue-500', 'text-blue-600');
                        t.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.add('active', 'border-blue-500', 'text-blue-600');
                    this.classList.remove('border-transparent', 'text-gray-500');

                    var panelId = this.getAttribute('data-panel');
                    document.querySelectorAll('.planning-panel').forEach(function (p) {
                        p.id === panelId ? p.classList.remove('hidden') : p.classList.add('hidden');
                    });
                });
            });
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
                const form = e.target;
                const submitBtn = form.querySelector('[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Creating...';

                const patientId = parseInt(form.querySelector('[name="patient_id"]')?.value || '0');
                if (!patientId) {
                    alert('Please select a patient.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Create Plan';
                    return;
                }

                // Resolve admission_id first
                fetch(API_BASE_URL + '/admissions/list.php?status=admitted')
                    .then(r => r.json())
                    .then(admData => {
                        if (!admData.ok) throw new Error('Could not load admissions');
                        const adm = admData.admissions.find(a => String(a.patient_id) === String(patientId));
                        if (!adm) throw new Error('No active admission found for selected patient');

                        const data = {
                            admission_id:            adm.id,
                            patient_id:              patientId,
                            expected_discharge_date: form.querySelector('[name="discharge_date"]')?.value || null,
                            discharge_destination:   form.querySelector('[name="destination"]')?.value || 'home',
                            discharge_notes:         form.querySelector('[name="notes"]')?.value || '',
                            planned_by:              '',
                        };

                        return fetch(API_BASE_URL + '/discharge/plan_create.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data),
                        });
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.ok) {
                            alert('Discharge plan created! Plan No: ' + res.plan_no);
                            dischargePlanModal.classList.add('hidden');
                            form.reset();
                        } else {
                            alert('Error: ' + (res.error || 'Failed to create plan'));
                        }
                    })
                    .catch(err => alert('Error: ' + err.message))
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Create Plan';
                    });
            });
            
            document.getElementById('followupForm').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = e.target;
                const submitBtn = form.querySelector('[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Scheduling...';

                const patientId = parseInt(form.querySelector('[name="patient_id"]')?.value || '0');
                if (!patientId) {
                    alert('Please select a patient.');
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Schedule';
                    return;
                }

                // Resolve discharge_plan_id
                fetch(API_BASE_URL + '/discharge/plan_list.php?patient_id=' + patientId)
                    .then(r => r.json())
                    .then(planData => {
                        const plan = (planData.plans || [])[0];
                        if (!plan) throw new Error('No discharge plan found. Create a discharge plan first.');

                        const data = {
                            discharge_plan_id: plan.id,
                            patient_id:        patientId,
                            followup_date:     form.querySelector('[name="followup_date"]')?.value || '',
                            department:        form.querySelector('[name="department"]')?.value || '',
                            physician:         form.querySelector('[name="physician"]')?.value || '',
                            notes:             form.querySelector('[name="notes"]')?.value || '',
                        };

                        return fetch(API_BASE_URL + '/discharge/followup_create.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json' },
                            body: JSON.stringify(data),
                        });
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.ok) {
                            alert('Follow-up appointment scheduled successfully!');
                            followupModal.classList.add('hidden');
                            form.reset();
                        } else {
                            alert('Error: ' + (res.error || 'Failed to schedule follow-up'));
                        }
                    })
                    .catch(err => alert('Error: ' + err.message))
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = 'Schedule';
                    });
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
