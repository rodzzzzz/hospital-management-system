<?php
require_once __DIR__ . '/auth.php';

$authUser = auth_current_user();

if (!$authUser || !auth_user_has_module($authUser, 'DOCTOR')) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <?php include __DIR__ . '/includes/websocket-client.php'; ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <!-- Header -->
        <header class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <img src="logo.png" alt="Logo" class="h-8 w-auto mr-3">
                        <h1 class="text-2xl font-semibold text-gray-900">Doctor Portal</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <button class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-bell text-gray-500"></i>
                        </button>
                        <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                    </div>
                </div>
            </div>
        </header>

        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Inner Navigation Tabs -->
            <div class="px-6 pt-4 bg-white border-b border-gray-200">
                <nav class="flex space-x-1" id="doctorInnerNav">
                    <button onclick="switchDoctorTab('queue')" id="tabBtnQueue" class="px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 border-blue-600 text-blue-600 bg-blue-50 transition-colors">
                        <i class="fas fa-users mr-2"></i>Queue
                    </button>
                    <button onclick="switchDoctorTab('findings')" id="tabBtnFindings" class="px-5 py-3 text-sm font-semibold rounded-t-lg border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors">
                        <i class="fas fa-notes-medical mr-2"></i>Patient's Findings
                    </button>
                </nav>
            </div>

            <!-- Queue Tab Content -->
            <div id="doctorTabQueue" class="p-6 space-y-6">
                <!-- Doctor Queue Section -->
                <section id="doctorQueueSection" class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-600"></i>
                            Doctor's Queue
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <button id="doctorCallNextBtn" onclick="callNextPatient()" class="p-4 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-bell mr-2"></i> Call Next Patient
                            </button>
                            <button onclick="qecOpenReportModal()" class="p-4 bg-red-600 text-white rounded-lg text-lg font-semibold hover:bg-red-700 transition-colors flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Report Wrong Station
                            </button>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                            <h4 class="text-lg font-semibold text-gray-800">Currently Serving</h4>
                        </div>
                        <div id="doctorCurrentlyServing" class="text-center py-3">
                            <div class="text-gray-500">No patient being served</div>
                        </div>
                        <div id="doctorStationSelection" class="mt-4 hidden flex gap-2 justify-end">
                            <button onclick="callNextAndMarkUnavailable()" class="p-4 bg-orange-600 text-white rounded-lg text-lg font-semibold hover:bg-orange-700 transition-colors flex items-center">
                                <i class="fas fa-user-slash mr-2"></i> Mark Unavailable
                            </button>
                            <button onclick="openDoctorSendPatientModal()" class="p-4 bg-green-600 text-white rounded-lg text-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send to Next Station
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                            <i class="fas fa-list-ol mr-2 text-blue-600"></i>
                            Waiting Queue
                        </h4>
                        <div id="doctorQueueList" class="space-y-2">
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users-slash text-4xl mb-2"></i>
                                <p>No patients in queue</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                            <i class="fas fa-user-clock mr-2 text-orange-600"></i>
                            Unavailable Patients
                        </h4>
                        <div id="doctorUnavailablePatientsList" class="space-y-2">
                            <div class="text-center py-6 text-gray-400">
                                <i class="fas fa-check-circle text-3xl mb-2"></i>
                                <p>No unavailable patients</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button onclick="openDisplayScreen()" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center">
                            <i class="fas fa-tv mr-2"></i>
                            Open Display Screen
                        </button>
                    </div>
                </section>

                <!-- Send Patient Modal -->
                <div id="doctorSendPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[60]">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
                        <div class="p-8 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                            <h3 class="text-2xl font-bold text-gray-900">Send Patient to Next Station</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="closeDoctorSendPatientModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-8 flex-1 overflow-y-auto">
                            <div class="mb-6">
                                <label class="block text-lg font-semibold text-gray-700 mb-4">Select Destination Station:</label>
                                <div id="doctorStationList" class="space-y-4"></div>
                            </div>
                        </div>
                        <div class="p-8 bg-gray-50 border-t flex justify-end gap-4 flex-shrink-0">
                            <button type="button" class="px-8 py-4 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold transition-colors" onclick="closeDoctorSendPatientModal()">
                                Cancel
                            </button>
                            <button type="button" id="doctorConfirmSendBtn" class="px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                <i class="fas fa-paper-plane mr-3"></i>
                                Send Patient
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Patient's Findings Tab Content -->
            <div id="doctorTabFindings" class="p-6 space-y-6 hidden">
                <!-- Patient's Findings Section -->
                <section class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900"><i class="fas fa-notes-medical mr-2 text-blue-600"></i>Patient's Findings</h3>
                            <p class="text-sm text-gray-600 mt-1">View lab tests, nurse assessments, and x-ray results for each patient.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input id="findingsPatientSearch" type="text" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search patient name / code">
                            <button id="findingsRefreshBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Refresh</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Lab Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Assessments</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="findingsPatientsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </section>
            </div>

            <!-- OLD Patients Section (hidden, kept for backward compat) -->
            <div class="hidden">
                <section id="doctorPatientsSection" class="bg-white rounded-lg shadow-sm overflow-hidden hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Patients</h3>
                            <p class="text-sm text-gray-600 mt-1">View patient information and current type/status.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <input id="doctorPatientSearch" type="text" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Search name / code">
                            <button id="doctorRefreshPatients" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Refresh</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location / Dept</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Updated</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="doctorPatientsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div id="doctorLabDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Lab Request Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('doctorLabDetailsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="doctorLabDetailsContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('doctorLabDetailsModal')">Close</button>
            </div>
        </div>
    </div>

    <div id="doctorPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Patient Information</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('doctorPatientModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="doctorPatientContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('doctorPatientModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Combined Patient Findings Modal -->
    <div id="findingsAllModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-40 transition-all duration-300 ease-in-out">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-[95vw] mx-2 h-[95vh] flex flex-col transform transition-all duration-500 ease-out scale-95 opacity-0" id="findingsModalContainer">
            <!-- Header with Close All Button -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between flex-shrink-0 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-xl font-bold text-gray-900"><i class="fas fa-notes-medical mr-2 text-blue-600"></i>Patient's Medical Findings</h2>
                <button type="button" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-200 flex items-center" onclick="closeAllFindings()">
                    <i class="fas fa-times mr-2"></i>Close All
                </button>
            </div>
            
            <!-- Three Modal Content Areas -->
            <div class="flex-1 flex gap-4 p-4 overflow-hidden">
                <!-- Lab Tests Modal -->
                <div class="flex-1 bg-white rounded-lg shadow-sm flex flex-col transform transition-all duration-300 hover:shadow-md" id="labModalCard">
                    <div class="p-4 flex items-center justify-between flex-shrink-0 bg-cyan-50">
                        <h3 class="text-lg font-semibold text-gray-900"><i class="fas fa-flask mr-2 text-cyan-600"></i>Lab Test Results</h3>
                        <button type="button" class="px-3 py-1 text-sm bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors duration-200" onclick="expandModal('lab')">
                            <i class="fas fa-expand mr-1"></i>View All
                        </button>
                    </div>
                    <div id="findingsLabContent" class="p-4 flex-1 overflow-y-auto text-sm"></div>
                </div>
                
                <!-- Nurse Assessment Modal -->
                <div class="flex-1 bg-white rounded-lg shadow-sm flex flex-col transform transition-all duration-300 hover:shadow-md" id="nurseModalCard">
                    <div class="p-4 flex items-center justify-between flex-shrink-0 bg-red-50">
                        <h3 class="text-lg font-semibold text-gray-900"><i class="fas fa-user-nurse mr-2 text-red-600"></i>Nurse Assessment</h3>
                        <button type="button" class="px-3 py-1 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-200" onclick="expandModal('nurse')">
                            <i class="fas fa-expand mr-1"></i>View All
                        </button>
                    </div>
                    <div id="findingsNurseContent" class="p-4 flex-1 overflow-y-auto text-sm"></div>
                </div>
                
                <!-- X-ray Results Modal -->
                <div class="flex-1 bg-white rounded-lg shadow-sm flex flex-col transform transition-all duration-300 hover:shadow-md" id="xrayModalCard">
                    <div class="p-4 flex items-center justify-between flex-shrink-0 bg-indigo-50">
                        <h3 class="text-lg font-semibold text-gray-900"><i class="fas fa-x-ray mr-2 text-indigo-600"></i>X-ray Results</h3>
                        <button type="button" class="px-3 py-1 text-sm bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-200" onclick="expandModal('xray')">
                            <i class="fas fa-expand mr-1"></i>View All
                        </button>
                    </div>
                    <div id="findingsXrayContent" class="p-4 flex-1 overflow-y-auto text-sm"></div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Expanded Modal for Individual View -->
    <div id="expandedModal" class="fixed inset-0 hidden items-center justify-center z-50 transition-all duration-300 ease-in-out backdrop-blur-sm bg-black/20">
        <div class="bg-white rounded-lg shadow-2xl w-full max-w-7xl mx-2 h-[95vh] flex flex-col transform transition-all duration-500 ease-out scale-95 opacity-0" id="expandedModalContainer">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                <h3 id="expandedModalTitle" class="text-xl font-bold text-gray-900"></h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 transition-colors duration-200" onclick="closeExpandedModal()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <div id="expandedModalContent" class="p-6 flex-1 overflow-y-auto"></div>
            <div class="p-4 bg-gray-50 border-t flex justify-end flex-shrink-0">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200" onclick="closeExpandedModal()">Close</button>
            </div>
        </div>
    </div>

    <script>
        function toggleModal(id) {
            // Legacy function - kept for backward compatibility with existing modals
            const el = document.getElementById(id);
            if (!el) return;
            if (el.classList.contains('hidden')) {
                el.classList.remove('hidden');
                el.classList.add('flex');
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
            }
        }
        
        // Close modals when clicking outside
        document.addEventListener('click', function(e) {
            const allModal = document.getElementById('findingsAllModal');
            const expandedModal = document.getElementById('expandedModal');
            
            if (e.target === allModal) {
                closeAllFindings();
            }
            if (e.target === expandedModal) {
                closeExpandedModal();
            }
        });
        
        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const allModal = document.getElementById('findingsAllModal');
                const expandedModal = document.getElementById('expandedModal');
                
                if (!expandedModal.classList.contains('hidden')) {
                    closeExpandedModal();
                } else if (!allModal.classList.contains('hidden')) {
                    closeAllFindings();
                }
            }
        });

        function escapeHtml(s) {
            return (s ?? '').toString()
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function triageLabel(n) {
            const x = Number(n);
            if (x === 1) return '1 - Resuscitation';
            if (x === 2) return '2 - Emergent';
            if (x === 3) return '3 - Urgent';
            if (x === 4) return '4 - Less Urgent';
            if (x === 5) return '5 - Non-Urgent';
            return '';
        }

        function statusChip(status) {
            const s = (status ?? '').toString();
            if (s === 'pending_approval') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Pending Approval' };
            if (s === 'approved') return { cls: 'bg-blue-100 text-blue-800', label: 'Approved' };
            if (s === 'rejected') return { cls: 'bg-red-100 text-red-800', label: 'Rejected' };
            if (s === 'collected') return { cls: 'bg-purple-100 text-purple-800', label: 'Collected' };
            if (s === 'in_progress') return { cls: 'bg-indigo-100 text-indigo-800', label: 'In Progress' };
            if (s === 'completed') return { cls: 'bg-green-100 text-green-800', label: 'Completed' };
            if (s === 'cancelled') return { cls: 'bg-gray-100 text-gray-800', label: 'Cancelled' };
            return { cls: 'bg-gray-100 text-gray-800', label: s };
        }

        function patientTypeChip(type) {
            const t = (type ?? '').toString().toLowerCase();
            if (t === 'er') return { label: 'ER', cls: 'bg-red-100 text-red-800' };
            if (t === 'inpatient') return { label: 'In-Patient', cls: 'bg-purple-100 text-purple-800' };
            if (t === 'dialysis') return { label: 'Dialysis', cls: 'bg-blue-100 text-blue-800' };
            if (t === 'opd') return { label: 'OPD', cls: 'bg-green-100 text-green-800' };
            return { label: 'Registered', cls: 'bg-gray-100 text-gray-800' };
        }

        function setDoctorView(view) {
            const lab = document.getElementById('doctorLabRequestsSection');
            const patients = document.getElementById('doctorPatientsSection');
            if (!lab || !patients) return;

            const isLab = view === 'lab-requests';
            lab.classList.toggle('hidden', !isLab);
            patients.classList.toggle('hidden', isLab);

            if (isLab) {
                loadDoctorLabRequests();
            } else {
                loadDoctorPatients();
            }
        }

        async function loadDoctorLabRequests() {
            const tbody = document.getElementById('doctorLabTbody');
            if (!tbody) return;

            const status = (document.getElementById('doctorLabStatus')?.value ?? '').toString().trim();
            const url = API_BASE_URL + '/lab/list_requests.php?mode=doctor' + (status ? ('&status=' + encodeURIComponent(status)) : '');

            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            tbody.innerHTML = rows.map(r => {
                const chip = statusChip(r.status);
                const reqNo = escapeHtml(r.request_no || ('#' + String(r.id)));
                const patient = escapeHtml(r.full_name || '');
                const code = escapeHtml(r.patient_code || '');
                const tests = escapeHtml(r.tests || '');
                const triage = escapeHtml(triageLabel(r.triage_level));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${reqNo}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleString())}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${patient}</div>
                            <div class="text-sm text-gray-500">ID: ${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-800">${triage}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-800">${tests}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(r.chief_complaint || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openDoctorLabDetails(${Number(r.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openDoctorLabDetails(id) {
            const content = document.getElementById('doctorLabDetailsContent');
            if (!content) return;

            const res = await fetch(API_BASE_URL + '/lab/get_request.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                toggleModal('doctorLabDetailsModal');
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];
            const chip = statusChip(r.status);

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Patient</h4>
                        <p><strong>Name:</strong> ${escapeHtml(r.full_name || '')}</p>
                        <p><strong>Patient ID:</strong> ${escapeHtml(r.patient_code || '')}</p>
                        <p><strong>Request No:</strong> ${escapeHtml(r.request_no || '')}</p>
                    </div>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Request</h4>
                        <p><strong>Source:</strong> ${escapeHtml(r.source_unit || '')}</p>
                        <p><strong>Triage:</strong> ${escapeHtml(triageLabel(r.triage_level))}</p>
                        <p><strong>Priority:</strong> ${escapeHtml((r.priority || '').toString().toUpperCase())}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${chip.label}</span></p>
                        <p><strong>Approved By:</strong> ${escapeHtml(r.approved_by || '')}</p>
                    </div>
                    <div class="col-span-1 md:col-span-2 space-y-3">
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Clinical</div>
                            <div class="p-4">
                                <p><strong>Chief Complaint:</strong> ${escapeHtml(r.chief_complaint || '')}</p>
                                <p><strong>Requested By:</strong> ${escapeHtml(r.requested_by || '')}</p>
                                <p><strong>Notes:</strong> ${escapeHtml(r.notes || '')}</p>
                            </div>
                        </div>
                        <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                            <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Requested Tests</div>
                            <div class="p-4">
                                <ul class="space-y-2">
                                    ${items.map(it => `
                                        <li class="flex items-center justify-between">
                                            <div class="text-sm text-gray-800">${escapeHtml(it.test_name || '')}</div>
                                            <div class="text-xs text-gray-500">${escapeHtml(it.specimen || '')}</div>
                                        </li>
                                    `).join('')}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            toggleModal('doctorLabDetailsModal');
        }

        async function loadDoctorPatients() {
            const tbody = document.getElementById('doctorPatientsTbody');
            if (!tbody) return;

            const q = (document.getElementById('doctorPatientSearch')?.value ?? '').toString().trim();
            const url = API_BASE_URL + '/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');

            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients : [];
            tbody.innerHTML = rows.map(p => {
                const fullName = escapeHtml(p.full_name || '');
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                const loc = escapeHtml(p.initial_location || '');
                const dept = escapeHtml(p.department || '');
                const chip = patientTypeChip(p.patient_type);
                const updated = escapeHtml((p.updated_at || '').toString());

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">${fullName}</div>
                            <div class="text-xs text-gray-500">${escapeHtml(p.sex || '')}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">${code}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">${loc}</div>
                            <div class="text-xs text-gray-500">${dept}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${escapeHtml(chip.label)}</span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">${updated}</td>
                        <td class="px-6 py-4 text-right">
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openDoctorPatient(${Number(p.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openDoctorPatient(id) {
            const content = document.getElementById('doctorPatientContent');
            if (!content) return;

            const res = await fetch(API_BASE_URL + '/patients/get.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load patient.</div>';
                toggleModal('doctorPatientModal');
                return;
            }

            const p = json.patient || {};
            const chip = patientTypeChip(p.patient_type);

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Basic</h4>
                        <p><strong>Name:</strong> ${escapeHtml(p.full_name || '')}</p>
                        <p><strong>Patient Code:</strong> ${escapeHtml(p.patient_code || '')}</p>
                        <p><strong>Sex:</strong> ${escapeHtml(p.sex || '')}</p>
                        <p><strong>DOB:</strong> ${escapeHtml(p.dob || '')}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${escapeHtml(chip.label)}</span></p>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Contact</h4>
                        <p><strong>Contact:</strong> ${escapeHtml(p.contact || '')}</p>
                        <p><strong>Email:</strong> ${escapeHtml(p.email || '')}</p>
                        <p><strong>Civil Status:</strong> ${escapeHtml(p.civil_status || '')}</p>
                        <p><strong>PhilHealth PIN:</strong> ${escapeHtml(p.philhealth_pin || '')}</p>
                    </div>
                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Location</div>
                        <div class="p-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <div class="text-xs text-gray-500">Initial Location</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(p.initial_location || '')}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Department</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(p.department || '')}</div>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500">Diagnosis</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(p.diagnosis || '')}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            toggleModal('doctorPatientModal');
        }

        function applyViewFromHash() {
            const h = (window.location.hash || '').toString().replace(/^#/, '').toLowerCase();
            if (h === 'patients') {
                setDoctorView('patients');
            } else {
                setDoctorView('lab-requests');
            }
        }

        const doctorRefreshLab = document.getElementById('doctorRefreshLab');
        if (doctorRefreshLab) {
            doctorRefreshLab.addEventListener('click', loadDoctorLabRequests);
        }
        
        const doctorLabStatus = document.getElementById('doctorLabStatus');
        if (doctorLabStatus) {
            doctorLabStatus.addEventListener('change', loadDoctorLabRequests);
        }

        let ptTimer = null;
        const doctorPatientSearch = document.getElementById('doctorPatientSearch');
        if (doctorPatientSearch) {
            doctorPatientSearch.addEventListener('input', () => {
                if (ptTimer) window.clearTimeout(ptTimer);
                ptTimer = window.setTimeout(loadDoctorPatients, 250);
            });
        }
        
        const doctorRefreshPatients = document.getElementById('doctorRefreshPatients');
        if (doctorRefreshPatients) {
            doctorRefreshPatients.addEventListener('click', loadDoctorPatients);
        }

        window.addEventListener('hashchange', applyViewFromHash);
        applyViewFromHash();

        // Doctor Queue Management Functions
        let currentDoctorQueueData = null;

        async function loadDoctorQueue() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/display/2'); // Doctor station ID is 2
                currentDoctorQueueData = await response.json();
                updateDoctorQueueDisplay();
            } catch (error) {
                console.error('Error loading Doctor queue:', error);
            }
        }

        function getQueueEntryId(row) {
            if (!row) return 0;
            const v = row.queue_id ?? row.queue_entry_id ?? row.id;
            return Number(v || 0);
        }

        function updateDoctorQueueDisplay() {
            if (!currentDoctorQueueData) return;

            const currentlyServingDiv = document.getElementById('doctorCurrentlyServing');
            if (currentDoctorQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-lg border border-green-300 flex items-center gap-4">
                        <div class="relative h-16 w-16">
                            <div class="absolute h-12 w-12 left-[calc(50%-1.5rem)] top-[calc(50%-1.5rem)] bg-green-500 rounded animate-ping"></div>
                            <div class="relative h-full w-full bg-green-500 text-white text-2xl rounded-md flex flex-col items-center justify-center font-bold">
                                ${currentDoctorQueueData.currently_serving.queue_number}
                            </div>
                        </div>
                        <div class="flex flex-col items-start text-left">
                            <div class="text-2xl font-bold text-green-700 line-clamp-1">${currentDoctorQueueData.currently_serving.full_name}</div>
                            <div class="text-sm text-gray-600">${currentDoctorQueueData.currently_serving.patient_code || ''}</div>
                        </div>
                    </div>
                `;
                document.getElementById('doctorStationSelection').classList.remove('hidden');
                loadDoctorStationOptions();
            } else {
                currentlyServingDiv.innerHTML = `
                    <div class="text-gray-500">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p>No patient being served</p>
                    </div>
                `;
                document.getElementById('doctorStationSelection').classList.add('hidden');
            }

            const queueListDiv = document.getElementById('doctorQueueList');
            if (currentDoctorQueueData.next_patients && currentDoctorQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentDoctorQueueData.next_patients.map((patient) => `
                    <div class="flex justify-between items-center p-2 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-md flex items-center justify-center font-bold">
                                ${patient.queue_number}
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-800 line-clamp-1">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                queueListDiv.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-users-slash text-4xl mb-2"></i>
                        <p>No patients in queue</p>
                    </div>
                `;
            }

            const unavailableDiv = document.getElementById('doctorUnavailablePatientsList');
            if (currentDoctorQueueData.unavailable_patients && currentDoctorQueueData.unavailable_patients.length > 0) {
                unavailableDiv.innerHTML = currentDoctorQueueData.unavailable_patients.map(patient => `
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded-lg border border-orange-200 cursor-pointer hover:bg-orange-100 transition-colors" onclick="recallDoctorUnavailablePatient(${getQueueEntryId(patient)})">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-600 text-white rounded-md flex items-center justify-center font-bold">
                                ${patient.queue_number}
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-800 line-clamp-1">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                unavailableDiv.innerHTML = `
                    <div class="text-center py-6 text-gray-400">
                        <i class="fas fa-check-circle text-3xl mb-2"></i>
                        <p>No unavailable patients</p>
                    </div>
                `;
            }
        }

        function openDoctorSendPatientModal() {
            if (!currentDoctorQueueData?.currently_serving) {
                Toastify({
                    text: 'Please call a patient first before sending to next station',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#F59E0B',
                }).showToast();
                return;
            }

            const modal = document.getElementById('doctorSendPatientModal');
            modal.classList.remove('hidden');
            loadDoctorStationsForModal();
        }

        function closeDoctorSendPatientModal() {
            const modal = document.getElementById('doctorSendPatientModal');
            modal.classList.add('hidden');
            document.getElementById('doctorConfirmSendBtn').disabled = true;
            document.querySelectorAll('.doctor-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });
        }

        function loadDoctorStationsForModal() {
            const stationList = document.getElementById('doctorStationList');
            stationList.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i> <p class="mt-2 text-lg">Loading stations...</p></div>';

            const dischargeOption = document.createElement('div');
            dischargeOption.className = 'doctor-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200 transform hover:scale-[1.02]';
            dischargeOption.onclick = () => selectDoctorStation('discharge', dischargeOption);
            dischargeOption.innerHTML = `
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-xl flex items-center justify-center mr-6">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xl font-bold text-gray-800">Complete and Discharge</div>
                        <div class="text-base text-gray-600 mt-1">Patient consultation complete</div>
                    </div>
                </div>
            `;

            fetch(API_BASE_URL + '/queue/stations')
                .then(response => response.json())
                .then(data => {
                    stationList.innerHTML = '';

                    data.stations.forEach(station => {
                        if (station.id !== 2) {
                            const stationOption = document.createElement('div');
                            stationOption.className = 'doctor-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.02]';
                            stationOption.onclick = () => selectDoctorStation(station.id, stationOption);

                            let icon = 'fa-arrow-right';
                            let iconColor = 'bg-blue-600';
                            const stationName = (station.station_display_name || '').toLowerCase();
                            if (stationName.includes('doctor') || stationName.includes('consultation')) {
                                icon = 'fa-user-md';
                                iconColor = 'bg-purple-600';
                            } else if (stationName.includes('pharmacy')) {
                                icon = 'fa-pills';
                                iconColor = 'bg-pink-600';
                            } else if (stationName.includes('cashier') || stationName.includes('payment') || stationName.includes('billing')) {
                                icon = 'fa-cash-register';
                                iconColor = 'bg-yellow-600';
                            } else if (stationName.includes('lab') || stationName.includes('laboratory')) {
                                icon = 'fa-flask';
                                iconColor = 'bg-cyan-600';
                            } else if (stationName.includes('x-ray') || stationName.includes('radiology')) {
                                icon = 'fa-x-ray';
                                iconColor = 'bg-indigo-600';
                            } else if (stationName.includes('nurse') || stationName.includes('triage')) {
                                icon = 'fa-user-nurse';
                                iconColor = 'bg-red-600';
                            }

                            stationOption.innerHTML = `
                                <div class="flex items-center">
                                    <div class="w-16 h-16 ${iconColor} text-white rounded-xl flex items-center justify-center mr-6">
                                        <i class="fas ${icon} text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xl font-bold text-gray-800">${station.station_display_name}</div>
                                        <div class="text-base text-gray-600 mt-1">Move to ${station.station_display_name}</div>
                                    </div>
                                </div>
                            `;
                            stationList.appendChild(stationOption);
                        }
                    });

                    stationList.appendChild(dischargeOption);
                })
                .catch(() => {
                    stationList.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p class="text-lg">Failed to load stations</p></div>';
                });
        }

        function selectDoctorStation(stationId, element) {
            document.querySelectorAll('.doctor-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });

            if (stationId === 'discharge') {
                element.classList.add('ring-4', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            } else {
                element.classList.add('ring-4', 'ring-blue-500', 'bg-blue-50', 'shadow-lg');
            }

            document.getElementById('doctorConfirmSendBtn').disabled = false;
            document.getElementById('doctorConfirmSendBtn').onclick = () => sendDoctorPatientToStation(stationId);
        }

        async function sendDoctorPatientToStation(stationId) {
            if (!currentDoctorQueueData?.currently_serving) return;

            try {
                let body = { queue_id: currentDoctorQueueData.currently_serving.id };
                if (stationId !== 'discharge') {
                    body.target_station_id = parseInt(stationId, 10);
                }

                const response = await fetch(API_BASE_URL + '/queue/complete-service', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });

                const result = await response.json();
                const isSuccess = response.ok && (result.ok === true || result.success === true);
                if (isSuccess) {
                    Toastify({
                        text: stationId === 'discharge' ? 'Patient discharged successfully' : 'Patient sent to next station',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();

                    closeDoctorSendPatientModal();
                    loadDoctorQueue();
                } else {
                    throw new Error(result.error || result.message || 'Failed to send patient');
                }
            } catch (error) {
                console.error('Error sending patient to station:', error);
                Toastify({
                    text: error.message || 'Failed to send patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        function loadDoctorStationOptions() {
            // Compatibility function. Station selection is handled by modal.
        }

        async function callNextPatient() {
            try {
                if (currentDoctorQueueData?.currently_serving) {
                    Toastify({
                        text: 'Please complete the current patient service before calling the next patient',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                    return;
                }

                const response = await fetch(API_BASE_URL + '/queue/call-next', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ station_id: 2 })
                });
                
                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Next patient called successfully',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadDoctorQueue();
                } else {
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.toLowerCase().includes('no active transaction')) {
                        message = 'No more patients in the waiting queue';
                    }
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error calling next patient:', error);
                Toastify({
                    text: 'Error calling next patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function recallDoctorUnavailablePatient(queueId) {
            try {
                const response = await fetch(API_BASE_URL + '/queue/recall-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId
                    })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Patient recalled successfully',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadDoctorQueue();
                } else {
                    Toastify({
                        text: result.message || 'Unable to recall patient',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#EF4444',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error recalling unavailable patient:', error);
                Toastify({
                    text: 'Failed to recall patient from unavailable list',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function callNextAndMarkUnavailable() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        station_id: 2,
                        notes: 'Patient not available for service'
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Next patient called and previous patient marked as unavailable',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadDoctorQueue();
                } else {
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.toLowerCase().includes('no active transaction')) {
                        message = 'No more patients in the waiting queue';
                    }
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error calling next and marking unavailable:', error);
                Toastify({
                    text: 'Error calling next patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function completeService() {
            Toastify({
                text: 'Please use the "Send Patient" button instead',
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#F59E0B',
            }).showToast();
        }

        function openDisplayScreen() {
            window.open('doctor-display.php', '_blank');
        }

        // Initial load
        loadDoctorQueue();

        // Subscribe to WebSocket for real-time queue updates
        HospitalWS.subscribe('queue-2');
        HospitalWS.subscribe('global');
        HospitalWS.on('queue_update', function() { loadDoctorQueue(); });
        HospitalWS.on('fallback_poll', function() { loadDoctorQueue(); });

        // ===================== Tab Switching =====================
        function switchDoctorTab(tab) {
            const queueTab = document.getElementById('doctorTabQueue');
            const findingsTab = document.getElementById('doctorTabFindings');
            const btnQueue = document.getElementById('tabBtnQueue');
            const btnFindings = document.getElementById('tabBtnFindings');

            const activeClasses = ['border-blue-600', 'text-blue-600', 'bg-blue-50'];
            const inactiveClasses = ['border-transparent', 'text-gray-500'];

            if (tab === 'findings') {
                queueTab.classList.add('hidden');
                findingsTab.classList.remove('hidden');
                btnQueue.classList.remove(...activeClasses);
                btnQueue.classList.add(...inactiveClasses);
                btnFindings.classList.remove(...inactiveClasses);
                btnFindings.classList.add(...activeClasses);
                loadFindingsPatients();
            } else {
                findingsTab.classList.add('hidden');
                queueTab.classList.remove('hidden');
                btnFindings.classList.remove(...activeClasses);
                btnFindings.classList.add(...inactiveClasses);
                btnQueue.classList.remove(...inactiveClasses);
                btnQueue.classList.add(...activeClasses);
            }
        }

        // ===================== Findings Patient List =====================
        async function loadFindingsPatients() {
            const tbody = document.getElementById('findingsPatientsTbody');
            if (!tbody) return;

            const q = (document.getElementById('findingsPatientSearch')?.value ?? '').toString().trim();
            const url = API_BASE_URL + '/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');

            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400"><i class="fas fa-spinner fa-spin text-2xl"></i><p class="mt-2">Loading patients...</p></td></tr>';

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-400">Failed to load patients</td></tr>';
                    return;
                }

                const rows = Array.isArray(json.patients) ? json.patients : [];
                if (rows.length === 0) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-400">No patients found</td></tr>';
                    return;
                }

                tbody.innerHTML = rows.map(p => {
                    const chip = patientTypeChip(p.patient_type);
                    const labInfo = p.lab_status ? `<span class="px-2 py-0.5 text-xs rounded-full ${statusChip(p.lab_status).cls}">${statusChip(p.lab_status).label}</span>` : '<span class="text-xs text-gray-400">None</span>';
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">${escapeHtml(p.full_name || '')}</div>
                                <div class="text-xs text-gray-500">${escapeHtml(p.sex || '')}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">${escapeHtml(p.patient_code || ('P-' + String(p.id)))}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full ${chip.cls}">${escapeHtml(chip.label)}</span>
                            </td>
                            <td class="px-6 py-4">${labInfo}</td>
                            <td class="px-6 py-4">
                                <span class="text-xs text-gray-500">${escapeHtml(p.progress_status || '')}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openPatientFindings(${Number(p.id)}, '${escapeHtml(p.full_name || '')}')">
                                    <i class="fas fa-eye mr-1"></i>View
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-red-400">Error loading patients</td></tr>';
            }
        }

        let findingsSearchTimer = null;
        const findingsSearchInput = document.getElementById('findingsPatientSearch');
        if (findingsSearchInput) {
            findingsSearchInput.addEventListener('input', () => {
                if (findingsSearchTimer) clearTimeout(findingsSearchTimer);
                findingsSearchTimer = setTimeout(loadFindingsPatients, 300);
            });
        }
        
        const findingsRefreshBtn = document.getElementById('findingsRefreshBtn');
        if (findingsRefreshBtn) {
            findingsRefreshBtn.addEventListener('click', loadFindingsPatients);
        }

        // ===================== Open Combined Modal =====================
        let currentPatientId = null;
        let currentPatientName = null;
        
        async function openPatientFindings(patientId, patientName) {
            console.log('Opening findings for patient:', patientId, patientName);
            
            // Show modal with animation
            const modal = document.getElementById('findingsAllModal');
            const container = document.getElementById('findingsModalContainer');
            
            if (!modal || !container) {
                console.error('Modal elements not found');
                return;
            }
            
            // Set current patient after validation
            currentPatientId = patientId;
            currentPatientName = patientName;
            
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger animation after a brief delay
            setTimeout(() => {
                container.classList.remove('scale-95', 'opacity-0');
                container.classList.add('scale-100', 'opacity-100');
            }, 10);
            
            // Load all data
            try {
                await loadFindingsLab(patientId);
                await loadFindingsNurse(patientId);
                await loadFindingsXray(patientName); // Display-only, no actual loading
            } catch (error) {
                console.error('Error loading findings:', error);
            }
        }
        
        function closeAllFindings() {
            const modal = document.getElementById('findingsAllModal');
            const container = document.getElementById('findingsModalContainer');
            const expandedModal = document.getElementById('expandedModal');
            
            // Animate out
            container.classList.add('scale-95', 'opacity-0');
            container.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                expandedModal.classList.add('hidden');
                expandedModal.classList.remove('flex');
            }, 300);
        }
        
        function expandModal(type) {
            const expandedModal = document.getElementById('expandedModal');
            const expandedContainer = document.getElementById('expandedModalContainer');
            const expandedTitle = document.getElementById('expandedModalTitle');
            const expandedContent = document.getElementById('expandedModalContent');
            
            // Get the content from the appropriate modal
            let title = '';
            let content = '';
            
            if (type === 'lab') {
                title = '<i class="fas fa-flask mr-2 text-cyan-600"></i>Lab Test Results - Detailed View';
                content = document.getElementById('findingsLabContent').innerHTML;
            } else if (type === 'nurse') {
                title = '<i class="fas fa-user-nurse mr-2 text-red-600"></i>Nurse Assessment - Detailed View';
                content = document.getElementById('findingsNurseContent').innerHTML;
            } else if (type === 'xray') {
                title = '<i class="fas fa-x-ray mr-2 text-indigo-600"></i>X-ray Results - Detailed View';
                content = document.getElementById('findingsXrayContent').innerHTML;
            }
            
            // Set the content
            expandedTitle.innerHTML = title;
            expandedContent.innerHTML = content;
            
            // Show the expanded modal
            expandedModal.classList.remove('hidden');
            expandedModal.classList.add('flex');
            
            // Trigger animation after a brief delay
            setTimeout(() => {
                expandedContainer.classList.remove('scale-95', 'opacity-0');
                expandedContainer.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeExpandedModal() {
            const expandedModal = document.getElementById('expandedModal');
            const expandedContainer = document.getElementById('expandedModalContainer');
            
            expandedContainer.classList.add('scale-95', 'opacity-0');
            expandedContainer.classList.remove('scale-100', 'opacity-100');
            
            setTimeout(() => {
                expandedModal.classList.add('hidden');
                expandedModal.classList.remove('flex');
            }, 300);
        }

        // ===================== Lab Tests Modal =====================
        async function loadFindingsLab(patientId) {
            const content = document.getElementById('findingsLabContent');
            const viewAllBtn = document.querySelector('#labModalCard button[onclick="expandModal(\'lab\')"]');
            
            content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-cyan-500"></i><p class="mt-2 text-gray-500">Loading lab results...</p></div>';
            viewAllBtn.style.display = 'none'; // Hide initially

            try {
                const res = await fetch(API_BASE_URL + '/lab/list_requests.php?patient_id=' + patientId, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok || !Array.isArray(json.requests) || json.requests.length === 0) {
                    content.innerHTML = '<div class="text-center py-8 text-gray-400"><i class="fas fa-flask text-3xl mb-2"></i><p>No lab test records found for this patient.</p></div>';
                    viewAllBtn.style.display = 'none'; // Keep hidden when no data
                    return;
                }

                content.innerHTML = json.requests.map(r => {
                    const chip = statusChip(r.status);
                    return `
                        <div class="mb-3 border border-gray-200 rounded-lg overflow-hidden hover:shadow-sm transition-shadow duration-200">
                            <div class="px-3 py-2 bg-gray-50 border-b flex items-center justify-between">
                                <div>
                                    <span class="text-xs font-semibold text-gray-800">${escapeHtml(r.request_no || '#' + r.id)}</span>
                                    <span class="ml-2 text-xs text-gray-500">${escapeHtml(new Date(r.created_at).toLocaleDateString())}</span>
                                </div>
                                <span class="px-2 py-0.5 text-xs rounded-full ${chip.cls}">${chip.label}</span>
                            </div>
                            <div class="p-3 space-y-1">
                                <p class="text-xs"><strong>Tests:</strong> ${escapeHtml(r.tests || 'N/A')}</p>
                                <p class="text-xs"><strong>Complaint:</strong> ${escapeHtml((r.chief_complaint || 'N/A').substring(0, 50))}${(r.chief_complaint || '').length > 50 ? '...' : ''}</p>
                                <p class="text-xs"><strong>Triage:</strong> ${escapeHtml(triageLabel(r.triage_level) || 'N/A')}</p>
                                ${r.released_by ? `<p class="text-xs text-green-600"><i class="fas fa-check-circle mr-1"></i>Released by ${escapeHtml(r.released_by)}</p>` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
                viewAllBtn.style.display = 'inline-flex'; // Show when data is available
            } catch (e) {
                content.innerHTML = '<div class="text-center py-8 text-red-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p>Failed to load lab results.</p></div>';
                viewAllBtn.style.display = 'none'; // Hide on error
            }
        }

        // ===================== Nurse Assessment Modal =====================
        async function loadFindingsNurse(patientId) {
            const content = document.getElementById('findingsNurseContent');
            const viewAllBtn = document.querySelector('#nurseModalCard button[onclick="expandModal(\'nurse\')"]');
            
            content.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-2xl text-red-400"></i><p class="mt-2 text-gray-500">Loading nurse assessments...</p></div>';
            viewAllBtn.style.display = 'none'; // Hide initially

            try {
                const res = await fetch(API_BASE_URL + '/opd_assessment/list.php?patient_id=' + patientId, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                
                console.log('Nurse assessment response:', json); // Debug log

                if (!res.ok || !json || !json.ok) {
                    content.innerHTML = '<div class="text-center py-8 text-red-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p>Failed to load nurse assessments. Status: ' + res.status + '</p></div>';
                    viewAllBtn.style.display = 'none';
                    return;
                }
                
                if (!Array.isArray(json.assessments) || json.assessments.length === 0) {
                    content.innerHTML = '<div class="text-center py-8 text-gray-400"><i class="fas fa-user-nurse text-3xl mb-2"></i><p>No nurse assessment records found for this patient.</p></div>';
                    viewAllBtn.style.display = 'none'; // Keep hidden when no data
                    return;
                }

                content.innerHTML = json.assessments.map(a => {
                    let vitals = null;
                    let assessment = null;
                    try { vitals = a.vitals_json ? JSON.parse(a.vitals_json) : null; } catch(e) {}
                    try { assessment = a.assessment_json ? JSON.parse(a.assessment_json) : null; } catch(e) {}
                    
                    const pmh = (assessment && assessment.pmh && typeof assessment.pmh === 'object') ? assessment.pmh : {};
                    const hpi = (assessment && assessment.hpi && typeof assessment.hpi === 'object') ? assessment.hpi : {};
                    const social = (assessment && assessment.social && typeof assessment.social === 'object') ? assessment.social : {};

                    // Render vitals in grid format like OPD
                    const renderVitals = (v) => {
                        if (!v || typeof v !== 'object') return '<div class="text-gray-500">No vitals recorded.</div>';
                        const bpSys = (v.bp_systolic ?? '').toString();
                        const bpDia = (v.bp_diastolic ?? '').toString();
                        const bp = (bpSys && bpDia) ? (bpSys + '/' + bpDia) : (bpSys || bpDia ? (bpSys + '/' + bpDia) : '');
                        const items = [
                            ['BP', bp],
                            ['HR', v.hr],
                            ['RR', v.rr],
                            ['Temp', v.temp],
                            ['SpO₂', v.spo2],
                            ['Weight', v.weight],
                            ['Height', v.height],
                        ].filter(x => x[1] !== null && x[1] !== undefined && String(x[1]).trim() !== '');
                        if (!items.length) return '<div class="text-gray-500">No vitals recorded.</div>';
                        return '<div class="grid grid-cols-3 gap-4">' + items.map(([k, val]) => {
                            return `<div><span class="font-semibold text-gray-700">${escapeHtml(k)}:</span> <span class="text-gray-900">${escapeHtml(String(val))}</span></div>`;
                        }).join('') + '</div>';
                    };

                    return `
                        <div class="mb-6 bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="px-6 py-4 bg-gray-50 border-b flex items-center justify-between">
                                <div>
                                    <span class="font-semibold text-gray-800">Assessment #${a.id}</span>
                                    <span class="ml-3 text-gray-500">${escapeHtml(new Date(a.created_at).toLocaleString())}</span>
                                </div>
                                ${a.triage_level ? `<span class="px-3 py-1 rounded-full bg-orange-100 text-orange-800">${escapeHtml(triageLabel(a.triage_level))}</span>` : ''}
                            </div>
                            <div class="p-6 space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="font-semibold text-gray-800 mb-3">Patient</div>
                                        <div class="text-gray-900">${escapeHtml(a.full_name || '')}</div>
                                        <div class="text-gray-500">${escapeHtml(a.patient_code || '')}</div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <div class="font-semibold text-gray-800 mb-3">Assessment</div>
                                        <div class="text-gray-500"><span class="font-semibold text-gray-700">When:</span> ${escapeHtml(new Date(a.created_at).toLocaleString())}</div>
                                        <div class="text-gray-500"><span class="font-semibold text-gray-700">Nurse:</span> ${escapeHtml(a.nurse_name || '-')}</div>
                                    </div>
                                </div>
                                
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="section-title text-gray-700 mb-3 pb-2 border-b">Vitals</div>
                                    ${renderVitals(vitals)}
                                </div>
                                
                                ${hpi && (hpi.start || hpi.duration || hpi.severity || hpi.associated) ? `
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="section-title text-gray-700 mb-3 pb-2 border-b">History of Present Illness</div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        ${hpi.start ? `<div><span class="font-semibold text-gray-700">Start:</span> <span class="text-gray-900">${escapeHtml(hpi.start)}</span></div>` : ''}
                                        ${hpi.duration ? `<div><span class="font-semibold text-gray-700">Duration/Frequency:</span> <span class="text-gray-900">${escapeHtml(hpi.duration)}</span></div>` : ''}
                                        ${hpi.severity ? `<div><span class="font-semibold text-gray-700">Severity:</span> <span class="text-gray-900">${escapeHtml(hpi.severity)}</span></div>` : ''}
                                        ${hpi.associated ? `<div><span class="font-semibold text-gray-700">Associated Symptoms:</span> <span class="text-gray-900">${escapeHtml(hpi.associated)}</span></div>` : ''}
                                    </div>
                                    ${hpi.factors ? `<div class="mt-3"><span class="font-semibold text-gray-700">Aggravating/Relieving:</span> <span class="text-gray-900">${escapeHtml(hpi.factors)}</span></div>` : ''}
                                </div>
                                ` : ''}
                                
                                ${pmh && (pmh.diabetes || pmh.hypertension || pmh.asthma || pmh.heart_disease || pmh.other) ? `
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="section-title text-gray-700 mb-3 pb-2 border-b">Past Medical History</div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                        <div><span class="font-semibold text-gray-700">Diabetes:</span> <span class="text-gray-900">${pmh.diabetes ? 'Yes' : 'No'}</span></div>
                                        <div><span class="font-semibold text-gray-700">Hypertension:</span> <span class="text-gray-900">${pmh.hypertension ? 'Yes' : 'No'}</span></div>
                                        <div><span class="font-semibold text-gray-700">Asthma:</span> <span class="text-gray-900">${pmh.asthma ? 'Yes' : 'No'}</span></div>
                                        <div><span class="font-semibold text-gray-700">Heart Disease:</span> <span class="text-gray-900">${pmh.heart_disease ? 'Yes' : 'No'}</span></div>
                                    </div>
                                    ${pmh.other ? `<div class="mt-3"><span class="font-semibold text-gray-700">Other:</span> <span class="text-gray-900">${escapeHtml(pmh.other)}</span></div>` : ''}
                                </div>
                                ` : ''}
                                
                                ${a.notes ? `
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="section-title text-gray-700 mb-3 pb-2 border-b">Notes</div>
                                    <div class="text-gray-900">${escapeHtml(a.notes)}</div>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                    `;
                }).join('');
                viewAllBtn.style.display = 'inline-flex'; // Show when data is available
            } catch (e) {
                content.innerHTML = '<div class="text-center py-8 text-red-400"><i class="fas fa-exclamation-triangle text-2xl mb-2"></i><p>Failed to load nurse assessments.</p></div>';
                viewAllBtn.style.display = 'none'; // Hide on error
            }
        }

        // ===================== X-ray Results Modal (Display Only) =====================
        async function loadFindingsXray(patientName) {
            const content = document.getElementById('findingsXrayContent');
            const viewAllBtn = document.querySelector('#xrayModalCard button[onclick="expandModal(\'xray\')"]');
            
            // X-ray section is display-only, no API calls
            viewAllBtn.style.display = 'none'; // Always hidden for display-only
            
            content.innerHTML = `
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-x-ray text-3xl mb-3 text-indigo-300"></i>
                    <p class="text-sm font-medium text-gray-500 mb-2">X-ray Results</p>
                    <p class="text-xs text-gray-400">Display only - No functional data</p>
                    <div class="mt-4 bg-indigo-50 rounded-lg p-3 text-left max-w-sm mx-auto">
                        <div class="text-xs text-indigo-700">
                            <p class="font-semibold mb-1">Sample X-ray Record</p>
                            <p><strong>Exam:</strong> Chest X-ray</p>
                            <p><strong>Status:</strong> For display purposes</p>
                            <p><strong>Note:</strong> This section is non-functional</p>
                        </div>
                    </div>
                </div>
            `;
        }
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInUp {
                from {
                    transform: translateY(30px);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            
            @keyframes fadeIn {
                from { opacity: 0; }
                to { opacity: 1; }
            }
            
            .animate-slide-in {
                animation: slideInUp 0.3s ease-out;
            }
            
            .animate-fade-in {
                animation: fadeIn 0.2s ease-out;
            }
            
            #labModalCard:hover, #nurseModalCard:hover, #xrayModalCard:hover {
                transform: translateY(-2px);
            }
            
        /* Remove maximized styles since we're using separate expanded modal */
            
            /* Expanded Modal - Large Font Styles - Make ALL text consistent */
            #expandedModal * {
                font-size: 1.125rem !important;
            }
            
            #expandedModal h3 {
                font-size: 1.75rem !important;
            }
            
            #expandedModal .font-semibold {
                font-weight: 600 !important;
            }
            
            #expandedModal .section-title {
                font-weight: 500 !important;
                color: #374151 !important;
            }
            
            #expandedModal .p-4 {
                padding: 2rem !important;
            }
            
            #expandedModal .p-6 {
                padding: 2.5rem !important;
            }
            
            #expandedModal .gap-2 {
                gap: 1rem !important;
            }
            
            #expandedModal .gap-4 {
                gap: 1.5rem !important;
            }
            
            #expandedModal .mb-2 {
                margin-bottom: 1rem !important;
            }
            
            #expandedModal .mb-4 {
                margin-bottom: 2rem !important;
            }
            
            #expandedModal .space-y-4 > * {
                margin-top: 1.5rem !important;
                margin-bottom: 1.5rem !important;
            }
            
            #expandedModal .grid-cols-2 {
                grid-template-columns: repeat(3, minmax(0, 1fr)) !important;
            }
            
            #expandedModal button {
                font-size: 1.125rem !important;
                padding: 0.75rem 1.5rem !important;
            }
            
            #expandedModal i.fas {
                font-size: 1.5rem !important;
            }
            
            #expandedModal .rounded-lg {
                border-radius: 1rem !important;
            }
            
            #expandedModal .font-semibold {
                font-weight: 700 !important;
            }
            
            #expandedModal .text-gray-500 {
                color: #6b7280 !important;
            }
        
        
        `;
        document.head.appendChild(style);
    </script>
    <?php include __DIR__ . '/includes/queue-error-correction.php'; ?>
    <script>window.qecStationId = 2; window.qecRefreshQueue = function() { loadDoctorQueue(); };</script>
    <?php include __DIR__ . '/includes/queue-error-correction-js.php'; ?>
</body>

</html>