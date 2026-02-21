<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <header class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Doctor</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-500"></i>
                    </button>
                    <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                </div>
            </header>

            <div class="p-6 space-y-6">
                <section id="doctorLabRequestsSection" class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Lab Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Track lab request progress and view request forms.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <select id="doctorLabStatus" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">All statuses</option>
                                <option value="pending_approval" selected>Pending Approval</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="collected">Collected</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <button id="doctorRefreshLab" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Refresh</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Triage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="doctorLabTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </section>

                <!-- Doctor Queue Section -->
                <section id="doctorQueueSection" class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Doctor's Queue</h3>
                            <div class="flex space-x-2">
                                <button id="doctorCallNextBtn" onclick="callNextPatient()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                    <i class="fas fa-bell"></i> Call Next
                                </button>
                                <button onclick="callNextAndMarkUnavailable()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                    <i class="fas fa-user-slash"></i> Call Next & Mark Unavailable
                                </button>
                            </div>
                        </div>

                        <!-- Currently Serving -->
                        <div class="mb-4 p-3 bg-green-50 rounded-lg border border-green-200">
                            <div class="text-sm font-medium text-gray-600 mb-1">Currently Serving:</div>
                            <div id="doctorCurrentlyServing" class="text-lg font-semibold text-green-600">No patient being served</div>
                            <div id="doctorStationSelection" class="mt-3 hidden">
                                <div class="text-sm font-medium text-gray-600 mb-1">Next Destination:</div>
                                <select id="doctorDestinationStation" class="px-3 py-1 border border-gray-300 rounded text-sm">
                                    <option value="">Select destination...</option>
                                </select>
                                <button onclick="completeService()" class="ml-2 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                                    <i class="fas fa-check"></i> Complete
                                </button>
                            </div>
                        </div>

                        <!-- Queue List -->
                        <div class="space-y-2">
                            <div class="text-sm font-medium text-gray-600 mb-2">Waiting Patients:</div>
                            <ul id="doctorQueueList" class="space-y-2">
                                <li class="text-center text-gray-400 py-8">No patients in queue</li>
                            </ul>
                        </div>

                        <!-- Unavailable Patients -->
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="text-sm font-medium text-gray-600 mb-2">Unavailable Patients:</div>
                            <div id="doctorUnavailablePatientsList" class="space-y-2">
                                <div class="text-center text-gray-400 py-2">No unavailable patients</div>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <button onclick="openDisplayScreen()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                <i class="fas fa-tv"></i> Open Display Screen
                            </button>
                        </div>
                    </div>
                </section>

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

    <script>
        function toggleModal(id) {
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
            const url = 'api/lab/list_requests.php?mode=doctor' + (status ? ('&status=' + encodeURIComponent(status)) : '');

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

            const res = await fetch('api/lab/get_request.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
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
            const url = 'api/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');

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

            const res = await fetch('api/patients/get.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
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

        document.getElementById('doctorRefreshLab').addEventListener('click', loadDoctorLabRequests);
        document.getElementById('doctorLabStatus').addEventListener('change', loadDoctorLabRequests);

        let ptTimer = null;
        document.getElementById('doctorPatientSearch').addEventListener('input', () => {
            if (ptTimer) window.clearTimeout(ptTimer);
            ptTimer = window.setTimeout(loadDoctorPatients, 250);
        });
        document.getElementById('doctorRefreshPatients').addEventListener('click', loadDoctorPatients);

        window.addEventListener('hashchange', applyViewFromHash);
        applyViewFromHash();

        // Doctor Queue Management Functions
        let currentDoctorQueueData = null;

        async function loadDoctorQueue() {
            try {
                const response = await fetch('api/queue/display/2'); // Doctor station ID is 2
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

            const callNextBtn = document.getElementById('doctorCallNextBtn');
            if (callNextBtn) {
                const disabled = !!currentDoctorQueueData.currently_serving;
                callNextBtn.disabled = disabled;
                callNextBtn.classList.toggle('opacity-50', disabled);
                callNextBtn.classList.toggle('cursor-not-allowed', disabled);
            }

            // Update currently serving
            const currentlyServingDiv = document.getElementById('doctorCurrentlyServing');
            if (currentDoctorQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="font-semibold">${currentDoctorQueueData.currently_serving.full_name}</div>
                    <div class="text-sm text-gray-600">${currentDoctorQueueData.currently_serving.queue_number}</div>
                `;
                
                // Show station selection dropdown
                document.getElementById('doctorStationSelection').classList.remove('hidden');
                loadDoctorStationOptions();
            } else {
                currentlyServingDiv.innerHTML = '<span class="text-gray-400">No patient being served</span>';
                document.getElementById('doctorStationSelection').classList.add('hidden');
            }

            // Update queue list
            const queueListDiv = document.getElementById('doctorQueueList');
            if (currentDoctorQueueData.next_patients && currentDoctorQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentDoctorQueueData.next_patients.map((patient, index) => `
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-green-600">${patient.queue_number}</span>
                            <div>
                                <div class="font-medium">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Est. ${index * 20} min
                        </div>
                    </div>
                `).join('');
            } else {
                queueListDiv.innerHTML = '<div class="text-center text-gray-400 py-8">No patients in queue</div>';
            }

            // Update unavailable patients
            const unavailableDiv = document.getElementById('doctorUnavailablePatientsList');
            if (currentDoctorQueueData.unavailable_patients && currentDoctorQueueData.unavailable_patients.length > 0) {
                unavailableDiv.innerHTML = currentDoctorQueueData.unavailable_patients.map(patient => `
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded border border-orange-200 cursor-pointer hover:bg-orange-100" onclick="recallDoctorUnavailablePatient(${getQueueEntryId(patient)})">
                        <div class="flex items-center space-x-3">
                            <div>
                                <div class="font-medium">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code}</div>
                            </div>
                            <div class="text-sm text-orange-600">
                                ${patient.updated_at ? new Date(patient.updated_at).toLocaleTimeString() : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                unavailableDiv.innerHTML = '<div class="text-center text-gray-400 py-2">No unavailable patients</div>';
            }
        }

        function loadDoctorStationOptions() {
            const select = document.getElementById('doctorDestinationStation');
            select.innerHTML = '<option value="">Select destination...</option>';
            
            // Add discharge option
            const dischargeOption = document.createElement('option');
            dischargeOption.value = 'discharge';
            dischargeOption.textContent = 'Complete and Discharge';
            select.appendChild(dischargeOption);
            
            // Add other stations
            fetch('api/queue/stations')
                .then(response => response.json())
                .then(data => {
                    data.stations.forEach(station => {
                        if (station.id !== 2) { // Don't show current station
                            const option = document.createElement('option');
                            option.value = station.id;
                            option.textContent = station.station_display_name;
                            select.appendChild(option);
                        }
                    });
                });
        }

        async function callNextPatient() {
            try {
                const response = await fetch('api/queue/call-next', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ station_id: 2 })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Next patient called successfully');
                    loadDoctorQueue();
                } else {
                    alert(result.message || 'No patients in queue');
                }
            } catch (error) {
                console.error('Error calling next patient:', error);
                alert('Error calling next patient');
            }
        }

        async function recallDoctorUnavailablePatient(queueId) {
            try {
                const response = await fetch('api/queue/recall-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId
                    })
                });

                const result = await response.json();
                if (result.success) {
                    alert('Patient recalled successfully');
                    loadDoctorQueue();
                } else {
                    alert(result.message || 'Unable to recall patient');
                }
            } catch (error) {
                console.error('Error recalling unavailable patient:', error);
                alert('Error recalling patient');
            }
        }

        async function callNextAndMarkUnavailable() {
            try {
                const response = await fetch('api/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        station_id: 2
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    alert('Next patient called and previous marked unavailable');
                    loadDoctorQueue();
                } else {
                    alert(result.message || 'No patients in queue');
                }
            } catch (error) {
                console.error('Error calling next and marking unavailable:', error);
                alert('Error calling next patient');
            }
        }

        async function completeService() {
            if (!currentDoctorQueueData?.currently_serving) {
                alert('No patient currently being served');
                return;
            }

            const destinationSelect = document.getElementById('doctorDestinationStation');
            const selectedDestination = destinationSelect.value;
            
            if (!selectedDestination) {
                alert('Please select a destination station');
                return;
            }

            try {
                let endpoint = 'api/queue/complete-service';
                let body = { 
                    queue_id: currentDoctorQueueData.currently_serving.id
                };
                
                if (selectedDestination !== 'discharge') {
                    body.target_station_id = parseInt(selectedDestination);
                }
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                
                const result = await response.json();
                if (result.success) {
                    alert(selectedDestination === 'discharge' ? 'Patient discharged successfully' : 'Consultation completed successfully');
                    loadDoctorQueue();
                    
                    // Reset selection
                    destinationSelect.value = '';
                    document.getElementById('doctorStationSelection').classList.add('hidden');
                } else {
                    alert('Error completing consultation');
                }
            } catch (error) {
                console.error('Error completing service:', error);
                alert('Error completing consultation');
            }
        }

        function openDisplayScreen() {
            window.open('doctor-display.php', '_blank');
        }

        // Auto-refresh queue every 10 seconds
        setInterval(loadDoctorQueue, 10000);
        
        // Initial load
        loadDoctorQueue();
    </script>
</body>

</html>
