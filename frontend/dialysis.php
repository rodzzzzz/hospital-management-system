<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dialysis Unit - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
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
                <nav class="flex-1 overflow-y-auto p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="index.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-home w-6 text-center"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="patients.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-injured w-6 text-center"></i>
                                <span>Patients</span>
                            </a>
                        </li>
                        <li>
                            <a href="out-patient-department.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-hospital-user w-6 text-center"></i>
                                <span>OPD</span>
                            </a>
                        </li>
                        <li>
                            <a href="dialysis.php" class="flex items-center gap-x-4 px-4 py-2.5 bg-blue-600 text-white rounded-lg shadow-md">
                                <i class="fas fa-sync-alt w-6 text-center"></i>
                                <span>Dialysis</span>
                            </a>
                        </li>
                        <li>
                            <a href="pharmacy.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-pills w-6 text-center"></i>
                                <span>Pharmacy</span>
                            </a>
                        </li>
                        <li>
                            <a href="kitchen.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-utensils w-6 text-center"></i>
                                <span>Kitchen</span>
                            </a>
                        </li>

                        <li>
                            <a href="inventory.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-boxes w-6 text-center"></i>
                                <span>Inventory</span>
                            </a>
                        </li>
                        <li>
                            <a href="payroll.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-money-check-alt w-6 text-center"></i>
                                <span>Payroll</span>
                            </a>
                        </li>
                        <li>
                            <a href="chat.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-comments w-6 text-center"></i>
                                <span>Chat Messages</span>
                            </a>
                        </li>
                        <li>
                            <a href="employees.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-friends w-6 text-center"></i>
                                <span>Employees</span>
                            </a>
                        </li>
                        <li>
                            <a href="cashier.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-cash-register w-6 text-center"></i>
                                <span>Cashier</span>
                            </a>
                        </li>
                        <li>
                            <a href="laboratory.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-flask w-6 text-center"></i>
                                <span>Laboratory</span>
                            </a>
                        </li>

                        <li>
                            <a href="philhealth-claims.php" class="flex items-center gap-x-4 px-4 py-2.5 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
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
        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Dialysis Unit Dashboard</h1>
                <button onclick="toggleModal('scheduleSessionModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Schedule Session
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-users"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Today's Sessions</h2>
                            <p id="statsSessionsToday" class="text-2xl font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-check-circle"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Available Machines</h2>
                            <p id="statsAvailableMachines" class="text-2xl font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-clock"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Avg. Treatment Time</h2>
                            <p id="statsAvgTreatment" class="text-2xl font-semibold text-gray-800">-</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Today's Dialysis Schedule</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Machine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="sessionsTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Machine Status</h3>
                        <div id="machineStatusGrid" class="grid grid-cols-2 gap-4"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hourly Patient Load</h3>
                    <canvas id="patientLoadChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Machine Utilization</h3>
                    <canvas id="machineUtilizationChart" class="w-full h-64"></canvas>
                </div>
            </div>
        </main>
    </div>

    <!-- View Session Details Modal -->
    <div id="viewSessionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Session Details</h3>
                    <button onclick="toggleModal('viewSessionModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="sessionDetailsContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex justify-end space-x-4">
                <button type="button" onclick="toggleModal('viewSessionModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                    Close
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-print mr-2"></i>
                    Print Report
                </button>
            </div>
        </div>
    </div>

    <!-- Schedule Session Modal -->
    <div id="scheduleSessionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Schedule Dialysis Session</h3>
                    <button onclick="toggleModal('scheduleSessionModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="scheduleSessionForm" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label for="patientSelect" class="block text-sm font-medium text-gray-700">Patient</label>
                        <select id="patientSelect" name="patient_id" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" required></select>
                    </div>
                    <div>
                        <label for="machineSelect" class="block text-sm font-medium text-gray-700">Assigned Machine</label>
                        <select id="machineSelect" name="machine_id" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" required></select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="sessionDate" class="block text-sm font-medium text-gray-700">Date</label>
                            <input id="sessionDate" name="date" type="date" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="timeSlot" class="block text-sm font-medium text-gray-700">Time Slot</label>
                            <select id="timeSlot" name="time_slot" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" required>
                                <option value="morning">Morning (8AM - 12PM)</option>
                                <option value="afternoon">Afternoon (1PM - 5PM)</option>
                                <option value="evening">Evening (6PM - 10PM)</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea id="notes" name="notes" placeholder="Any specific instructions..." rows="3" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal('scheduleSessionModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Schedule
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        const API_BASE = API_BASE_URL + '/dialysis';

        let patientLoadChart;
        let machineUtilizationChart;

        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.classList.toggle('hidden');
            }
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text == null ? '' : String(text);
            return div.innerHTML;
        }

        async function apiGet(path) {
            const res = await fetch(`${API_BASE}/${path}`, { cache: 'no-store' });
            const data = await res.json();
            if (!res.ok || !data.ok) {
                throw new Error(data.error || `Request failed: ${path}`);
            }
            return data;
        }

        async function apiPost(path, formData) {
            const res = await fetch(`${API_BASE}/${path}`, { method: 'POST', body: formData });
            const data = await res.json();
            if (!res.ok || !data.ok) {
                throw new Error(data.error || `Request failed: ${path}`);
            }
            return data;
        }

        function statusMeta(status) {
            if (status === 'scheduled') return { text: 'Scheduled', cls: 'bg-gray-200 text-gray-800' };
            if (status === 'in_progress') return { text: 'In Progress', cls: 'bg-blue-100 text-blue-800' };
            if (status === 'completed') return { text: 'Completed', cls: 'bg-green-100 text-green-800' };
            return { text: status, cls: 'bg-gray-100 text-gray-700' };
        }

        function machineMeta(status) {
            if (status === 'available') return { text: 'Available', cls: 'bg-green-50 border border-green-200 text-green-800' };
            if (status === 'in_use') return { text: 'In Use', cls: 'bg-blue-50 border border-blue-200 text-blue-800' };
            if (status === 'maintenance') return { text: 'Maintenance', cls: 'bg-yellow-50 border border-yellow-200 text-yellow-800' };
            return { text: status, cls: 'bg-gray-50 border border-gray-200 text-gray-800' };
        }

        function formatTimeRange(startStr, endStr) {
            const start = new Date(String(startStr).replace(' ', 'T'));
            const end = new Date(String(endStr).replace(' ', 'T'));
            const fmt = new Intl.DateTimeFormat(undefined, { hour: '2-digit', minute: '2-digit' });
            return `${fmt.format(start)} - ${fmt.format(end)}`;
        }

        function todayISO() {
            const d = new Date();
            const pad = (n) => String(n).padStart(2, '0');
            return `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
        }

        function ensureCharts() {
            const patientLoadCtx = document.getElementById('patientLoadChart').getContext('2d');
            const machineUtilizationCtx = document.getElementById('machineUtilizationChart').getContext('2d');

            patientLoadChart = new Chart(patientLoadCtx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Patients',
                        data: [],
                        backgroundColor: 'rgba(13, 110, 253, 0.35)',
                        borderColor: 'rgba(13, 110, 253, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            machineUtilizationChart = new Chart(machineUtilizationCtx, {
                type: 'doughnut',
                data: {
                    labels: ['In Use', 'Available', 'Maintenance'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: ['rgba(13, 110, 253, 0.85)', 'rgba(25, 135, 84, 0.85)', 'rgba(255, 193, 7, 0.85)'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });
        }

        async function loadStats() {
            const data = await apiGet('stats.php');
            const stats = data.stats;
            document.getElementById('statsSessionsToday').textContent = stats.sessions_today;
            document.getElementById('statsAvailableMachines').textContent = `${stats.available_machines} / ${stats.total_machines}`;
            document.getElementById('statsAvgTreatment').textContent = stats.avg_treatment_hours == null ? '-' : `${stats.avg_treatment_hours} hrs`;
        }

        async function loadMachines() {
            const data = await apiGet('machines.php');
            const grid = document.getElementById('machineStatusGrid');
            grid.innerHTML = '';

            for (const machine of data.machines) {
                const meta = machineMeta(machine.status);
                const tile = document.createElement('div');
                tile.className = `p-3 rounded-lg text-center ${meta.cls}`;
                tile.innerHTML = `
                    <p class="font-semibold">${escapeHtml(machine.machine_code)}</p>
                    <p class="text-xs">${escapeHtml(meta.text)}</p>
                `;
                grid.appendChild(tile);
            }

            const machineSelect = document.getElementById('machineSelect');
            const currentValue = machineSelect.value;
            machineSelect.innerHTML = '';

            for (const machine of data.machines) {
                const opt = document.createElement('option');
                opt.value = machine.id;
                opt.textContent = `${machine.machine_code} (${machineMeta(machine.status).text})`;
                machineSelect.appendChild(opt);
            }

            if (currentValue) {
                machineSelect.value = currentValue;
            }
        }

        async function loadPatients() {
            const data = await apiGet('patients.php');
            const patientSelect = document.getElementById('patientSelect');
            const currentValue = patientSelect.value;
            patientSelect.innerHTML = '';

            for (const patient of data.patients) {
                const opt = document.createElement('option');
                opt.value = patient.id;
                opt.textContent = `${patient.full_name} (${patient.patient_code})`;
                patientSelect.appendChild(opt);
            }

            if (currentValue) {
                patientSelect.value = currentValue;
            }
        }

        async function loadSessions(date) {
            const tbody = document.getElementById('sessionsTbody');
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';

            const data = await apiGet(`sessions.php?date=${encodeURIComponent(date)}`);
            if (!data.sessions || data.sessions.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No sessions for this date.</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            for (const s of data.sessions) {
                const meta = statusMeta(s.status);
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>
                        <div class="text-sm font-medium text-gray-900">${escapeHtml(s.full_name)}</div>
                        <div class="text-sm text-gray-500">ID: ${escapeHtml(s.patient_code)}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-700">${escapeHtml(s.machine_code)}</td>
                    <td class="px-6 py-4 text-sm text-gray-700">${escapeHtml(formatTimeRange(s.start_time, s.end_time))}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full ${meta.cls}">${escapeHtml(meta.text)}</span>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <button type="button" class="p-1 text-blue-600 hover:text-blue-800" data-session-id="${escapeHtml(s.id)}">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            }
        }

        async function loadAnalytics(date) {
            const data = await apiGet(`analytics.php?date=${encodeURIComponent(date)}`);

            patientLoadChart.data.labels = data.patient_load.labels;
            patientLoadChart.data.datasets[0].data = data.patient_load.data;
            patientLoadChart.update();

            machineUtilizationChart.data.labels = data.machine_utilization.labels;
            machineUtilizationChart.data.datasets[0].data = data.machine_utilization.data;
            machineUtilizationChart.update();
        }

        async function viewSessionDetails(sessionId) {
            const data = await apiGet(`session.php?id=${encodeURIComponent(sessionId)}`);
            const s = data.session;
            const meta = statusMeta(s.status);

            document.getElementById('sessionDetailsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Patient Information</h4>
                        <p><strong>Name:</strong> ${escapeHtml(s.full_name)}</p>
                        <p><strong>Patient ID:</strong> ${escapeHtml(s.patient_code)}</p>
                    </div>
                    <div class="space-y-3 bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-md font-semibold text-gray-800 border-b pb-2">Session Details</h4>
                        <p><strong>Machine:</strong> ${escapeHtml(s.machine_code)}</p>
                        <p><strong>Time:</strong> ${escapeHtml(formatTimeRange(s.start_time, s.end_time))}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 text-xs rounded-full ${meta.cls}">${escapeHtml(meta.text)}</span></p>
                    </div>
                    <div class="col-span-2 border-t pt-4">
                        <h4 class="text-md font-semibold text-gray-800">Nurse's Notes</h4>
                        <p class="mt-2 text-gray-600">${escapeHtml(s.notes || '')}</p>
                    </div>
                </div>
            `;

            toggleModal('viewSessionModal');
        }

        async function loadAll() {
            const date = todayISO();
            await Promise.all([
                loadStats(),
                loadMachines(),
                loadPatients(),
                loadSessions(date),
                loadAnalytics(date),
            ]);
        }

        document.addEventListener('DOMContentLoaded', async function() {
            document.getElementById('sessionDate').value = todayISO();
            ensureCharts();

            document.getElementById('sessionsTbody').addEventListener('click', async function(e) {
                const btn = e.target.closest('button[data-session-id]');
                if (!btn) return;
                const sessionId = btn.getAttribute('data-session-id');
                try {
                    await viewSessionDetails(sessionId);
                } catch (err) {
                    Toastify({
                        text: err.message,
                        duration: 3500,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#dc3545',
                    }).showToast();
                }
            });

            document.getElementById('scheduleSessionForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                try {
                    const formData = new FormData(this);
                    await apiPost('schedule.php', formData);

                    Toastify({
                        text: 'Session scheduled successfully!',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#198754',
                    }).showToast();

                    toggleModal('scheduleSessionModal');

                    this.reset();
                    document.getElementById('sessionDate').value = todayISO();
                    await loadAll();
                } catch (err) {
                    Toastify({
                        text: err.message,
                        duration: 3500,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#dc3545',
                    }).showToast();
                }
            });

            try {
                await loadAll();
            } catch (err) {
                Toastify({
                    text: err.message,
                    duration: 5000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#dc3545',
                }).showToast();
            }
        });
    </script>
</body>
</html>
