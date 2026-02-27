<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xray - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Xray</h1>
                    <p class="text-sm text-gray-600 mt-1">Radiology workload snapshot and turnaround insights.</p>
                </div>
            </div>

            <section id="overview" class="xray-section">
                <div class="mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Overview</h2>
                    <p class="text-sm text-gray-600 mt-1">Daily performance snapshot and key charts.</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-sky-100 text-sky-700"><i class="fas fa-file-medical"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Orders Today</h2>
                                <p id="xrayOrdersToday" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-amber-100 text-amber-700"><i class="fas fa-hourglass-half"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Pending</h2>
                                <p id="xrayPending" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-emerald-100 text-emerald-700"><i class="fas fa-check-circle"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Reported Today</h2>
                                <p id="xrayReported" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-fuchsia-100 text-fuchsia-700"><i class="fas fa-stopwatch"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Avg TAT (mins)</h2>
                                <p id="xrayAvgTat" class="text-2xl font-semibold text-gray-800">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-600"></i>
                            X-Ray Queue
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <button id="xrayCallNextBtn" onclick="callXrayNextPatient()" class="p-4 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
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
                        <div id="xrayCurrentlyServing" class="text-center py-3">
                            <div class="text-gray-500">No patient being served</div>
                        </div>
                        <div id="xrayStationSelection" class="mt-4 hidden flex gap-2 justify-end">
                            <button onclick="callXrayNextAndMarkUnavailable()" class="p-4 bg-orange-600 text-white rounded-lg text-lg font-semibold hover:bg-orange-700 transition-colors flex items-center">
                                <i class="fas fa-user-slash mr-2"></i> Mark Unavailable
                            </button>
                            <button onclick="openXraySendPatientModal()" class="p-4 bg-green-600 text-white rounded-lg text-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
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
                        <div id="xrayQueueList" class="space-y-2">
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
                        <div id="xrayUnavailablePatientsList" class="space-y-2">
                            <div class="text-center py-6 text-gray-400">
                                <i class="fas fa-check-circle text-3xl mb-2"></i>
                                <p>No unavailable patients</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button onclick="openXrayDisplayScreen()" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center">
                            <i class="fas fa-tv mr-2"></i>
                            Open Display Screen
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Exams by Type (Today)</h3>
                            <span class="text-xs text-gray-500">count</span>
                        </div>
                        <canvas id="xrayByTypeChart" class="w-full h-64"></canvas>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Orders by Time Window (Today)</h3>
                            <span class="text-xs text-gray-500">count</span>
                        </div>
                        <canvas id="xrayByTimeChart" class="w-full h-64"></canvas>
                    </div>
                </div>

                <div class="grid grid-cols-1 mt-6">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Turnaround Trend (7 days)</h3>
                            <span class="text-xs text-gray-500">minutes</span>
                        </div>
                        <canvas id="xrayTurnaroundChart" class="w-full h-64"></canvas>
                    </div>
                </div>
            </section>

            <section id="scheduling" class="xray-section mt-10 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Scheduling</h3>
                        <p class="text-sm text-gray-600 mt-1">Assigned slots, modality availability, and queue.</p>
                    </div>

                    <div class="p-6 border-b border-gray-100">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="text-xs text-gray-500">Queue Total</div>
                                <div id="xraySchedQueueTotal" class="text-2xl font-semibold text-gray-900 mt-1">-</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="text-xs text-gray-500">In Progress</div>
                                <div id="xraySchedInProgress" class="text-2xl font-semibold text-gray-900 mt-1">-</div>
                            </div>
                            <div class="rounded-lg border border-gray-200 p-4">
                                <div class="text-xs text-gray-500">Scheduled</div>
                                <div id="xraySchedScheduled" class="text-2xl font-semibold text-gray-900 mt-1">-</div>
                            </div>
                        </div>

                        <div id="xrayModalityGrid" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4"></div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Scheduled</th>
                                </tr>
                            </thead>
                            <tbody id="xraySchedulingTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="worklist" class="xray-section mt-10 hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Worklist</h3>
                            <p class="text-sm text-gray-600 mt-1">Latest imaging requests and their current status.</p>
                        </div>
                        <div class="flex items-center gap-2">
                            <select id="xrayWorklistStatus" class="px-3 py-2 border border-gray-200 rounded-lg text-sm">
                                <option value="">All</option>
                                <option value="requested">Requested</option>
                                <option value="scheduled">Scheduled</option>
                                <option value="in_progress">In progress</option>
                                <option value="completed">Completed</option>
                                <option value="reported">Reported</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            <input id="xrayWorklistSearch" type="text" placeholder="Search patient / exam" class="px-3 py-2 border border-gray-200 rounded-lg text-sm" />
                            <button id="xrayWorklistRefresh" type="button" class="px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-sm">Refresh</button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Exam</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ordered</th>
                                </tr>
                            </thead>
                            <tbody id="xrayWorklistTbody" class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="results-release" class="xray-section mt-10 hidden">
                <?php
                $includeXrayResultsReleaseModal = false;
                include __DIR__ . '/includes/xray-results-release.php';
                ?>
            </section>

        </main>
    </div>
    <?php
    $includeXrayResultsReleaseCard = false;
    $includeXrayResultsReleaseModal = true;
    include __DIR__ . '/includes/xray-results-release.php';
    ?>

    <?php include __DIR__ . '/includes/xray-results-release-js.php'; ?>

    <div id="xraySendPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[60]">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
            <div class="p-8 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                <h3 class="text-2xl font-bold text-gray-900">Send Patient to Next Station</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="closeXraySendPatientModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-8 flex-1 overflow-y-auto">
                <div class="mb-6">
                    <label class="block text-lg font-semibold text-gray-700 mb-4">Select Destination Station:</label>
                    <div id="xrayStationList" class="space-y-4"></div>
                </div>
            </div>
            <div class="p-8 bg-gray-50 border-t flex justify-end gap-4 flex-shrink-0">
                <button type="button" class="px-8 py-4 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold transition-colors" onclick="closeXraySendPatientModal()">
                    Cancel
                </button>
                <button type="button" id="xrayConfirmSendBtn" class="px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-paper-plane mr-3"></i>
                    Send Patient
                </button>
            </div>
        </div>
    </div>

    <script>
        let xrayTypeChart = null;
        let xrayTimeChart = null;
        let xrayTatChart = null;
        let currentXrayQueueData = null;

        async function ensureXrayInstalled() {
            try {
                await fetch(API_BASE_URL + '/xray/install.php', { headers: { 'Accept': 'application/json' } });
            } catch (e) {
            }
        }

        async function loadXrayStats() {
            const res = await fetch(API_BASE_URL + '/xray/stats.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json.stats || null;
        }

        async function loadXrayAnalytics() {
            const res = await fetch(API_BASE_URL + '/xray/analytics.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json;
        }

        async function loadXrayWorklist() {
            const status = (document.getElementById('xrayWorklistStatus')?.value || '').toString().trim();
            const q = (document.getElementById('xrayWorklistSearch')?.value || '').toString().trim();
            const params = new URLSearchParams();
            if (status) params.set('status', status);
            if (q) params.set('q', q);
            const url = API_BASE_URL + '/xray/list.php' + (params.toString() ? ('?' + params.toString()) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return Array.isArray(json.orders) ? json.orders : [];
        }

        function escapeHtml(s) {
            return String(s ?? '')
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function fmtDateTime(s) {
            const d = new Date(String(s || '').replace(' ', 'T'));
            if (Number.isNaN(d.getTime())) return '';
            return d.toLocaleString([], { year: 'numeric', month: 'short', day: '2-digit', hour: 'numeric', minute: '2-digit' });
        }

        function statusChip(status) {
            const s = String(status || '').toLowerCase();
            if (s === 'requested') return { cls: 'bg-indigo-100 text-indigo-800', label: 'Requested' };
            if (s === 'scheduled') return { cls: 'bg-purple-100 text-purple-800', label: 'Scheduled' };
            if (s === 'in_progress') return { cls: 'bg-blue-100 text-blue-800', label: 'In Progress' };
            if (s === 'completed') return { cls: 'bg-emerald-100 text-emerald-800', label: 'Completed' };
            if (s === 'reported') return { cls: 'bg-green-100 text-green-800', label: 'Reported' };
            if (s === 'cancelled') return { cls: 'bg-red-100 text-red-800', label: 'Cancelled' };
            return { cls: 'bg-gray-100 text-gray-800', label: s || '-' };
        }

        function priorityChip(priority) {
            const p = String(priority || '').toLowerCase();
            if (p === 'stat') return { cls: 'bg-red-100 text-red-800', label: 'STAT' };
            if (p === 'urgent') return { cls: 'bg-amber-100 text-amber-800', label: 'Urgent' };
            return { cls: 'bg-slate-100 text-slate-800', label: p ? p[0].toUpperCase() + p.slice(1) : '-' };
        }

        async function loadXrayScheduling() {
            const res = await fetch(API_BASE_URL + '/xray/scheduling.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json;
        }

        async function loadXrayQueue() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/display/5');
                currentXrayQueueData = await response.json();
                updateXrayQueueDisplay();
            } catch (error) {
                console.error('Error loading X-Ray queue:', error);
            }
        }

        function getXrayQueueEntryId(row) {
            if (!row) return 0;
            const v = row.queue_id ?? row.queue_entry_id ?? row.id;
            return Number(v || 0);
        }

        function updateXrayQueueDisplay() {
            if (!currentXrayQueueData) return;

            const currentlyServingDiv = document.getElementById('xrayCurrentlyServing');
            if (currentXrayQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-lg border border-green-300 flex items-center gap-4">
                        <div class="relative h-16 w-16">
                            <div class="absolute h-12 w-12 left-[calc(50%-1.5rem)] top-[calc(50%-1.5rem)] bg-green-500 rounded animate-ping"></div>
                            <div class="relative h-full w-full bg-green-500 text-white text-2xl rounded-md flex flex-col items-center justify-center font-bold">
                                ${currentXrayQueueData.currently_serving.queue_number}
                            </div>
                        </div>
                        <div class="flex flex-col items-start text-left">
                            <div class="text-2xl font-bold text-green-700 line-clamp-1">${currentXrayQueueData.currently_serving.full_name}</div>
                            <div class="text-sm text-gray-600">${currentXrayQueueData.currently_serving.patient_code || ''}</div>
                        </div>
                    </div>
                `;

                document.getElementById('xrayStationSelection').classList.remove('hidden');
                loadXrayStationOptions();
            } else {
                currentlyServingDiv.innerHTML = `
                    <div class="text-gray-500">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p>No patient being served</p>
                    </div>
                `;
                document.getElementById('xrayStationSelection').classList.add('hidden');
            }

            const queueListDiv = document.getElementById('xrayQueueList');
            if (currentXrayQueueData.next_patients && currentXrayQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentXrayQueueData.next_patients.map((patient) => `
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

            const unavailableDiv = document.getElementById('xrayUnavailablePatientsList');
            if (currentXrayQueueData.unavailable_patients && currentXrayQueueData.unavailable_patients.length > 0) {
                unavailableDiv.innerHTML = currentXrayQueueData.unavailable_patients.map(patient => `
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded-lg border border-orange-200 cursor-pointer hover:bg-orange-100 transition-colors" onclick="recallXrayUnavailablePatient(${getXrayQueueEntryId(patient)})">
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

        function openXraySendPatientModal() {
            if (!currentXrayQueueData?.currently_serving) {
                Toastify({
                    text: 'Please call a patient first before sending to next station',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#F59E0B',
                }).showToast();
                return;
            }

            const modal = document.getElementById('xraySendPatientModal');
            modal.classList.remove('hidden');
            loadXrayStationsForModal();
        }

        function closeXraySendPatientModal() {
            const modal = document.getElementById('xraySendPatientModal');
            modal.classList.add('hidden');
            document.getElementById('xrayConfirmSendBtn').disabled = true;
            document.querySelectorAll('.xray-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });
        }

        function loadXrayStationsForModal() {
            const stationList = document.getElementById('xrayStationList');
            stationList.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i> <p class="mt-2 text-lg">Loading stations...</p></div>';

            const dischargeOption = document.createElement('div');
            dischargeOption.className = 'xray-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200 transform hover:scale-[1.02]';
            dischargeOption.onclick = () => selectXrayStation('discharge', dischargeOption);
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
                        if (station.id !== 5) {
                            const stationOption = document.createElement('div');
                            stationOption.className = 'xray-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.02]';
                            stationOption.onclick = () => selectXrayStation(station.id, stationOption);

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

        function selectXrayStation(stationId, element) {
            document.querySelectorAll('.xray-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });

            if (stationId === 'discharge') {
                element.classList.add('ring-4', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            } else {
                element.classList.add('ring-4', 'ring-blue-500', 'bg-blue-50', 'shadow-lg');
            }

            document.getElementById('xrayConfirmSendBtn').disabled = false;
            document.getElementById('xrayConfirmSendBtn').onclick = () => sendXrayPatientToStation(stationId);
        }

        async function sendXrayPatientToStation(stationId) {
            if (!currentXrayQueueData?.currently_serving) return;

            try {
                const body = {
                    queue_id: currentXrayQueueData.currently_serving.id
                };

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

                    closeXraySendPatientModal();
                    loadXrayQueue();
                } else {
                    const errorMessage = result.error || result.message || 'Failed to send patient';
                    throw new Error(errorMessage);
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

        function loadXrayStationOptions() {
            // Compatibility function. Station selection is handled by modal.
        }

        async function callXrayNextPatient() {
            try {
                if (currentXrayQueueData?.currently_serving) {
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
                    body: JSON.stringify({ station_id: 5 })
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
                    loadXrayQueue();
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

        async function recallXrayUnavailablePatient(queueId) {
            try {
                const response = await fetch(API_BASE_URL + '/queue/recall-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId,
                        notes: 'Recalled from unavailable list'
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
                    loadXrayQueue();
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

        async function callXrayNextAndMarkUnavailable() {
            try {
                const response = await fetch(API_BASE_URL + '/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        station_id: 5,
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
                    loadXrayQueue();
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

        function openXrayDisplayScreen() {
            window.open('xray-display.php', '_blank');
        }

        function setHtml(id, html) {
            const el = document.getElementById(id);
            if (!el) return;
            el.innerHTML = html;
        }

        const xraySections = ['overview', 'scheduling', 'worklist', 'results-release'];
        const xrayLoaded = {
            overview: false,
            scheduling: false,
            worklist: false,
            'results-release': false,
        };

        function getXraySectionFromHash() {
            let h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) return 'overview';
            if (xraySections.indexOf(h) !== -1) return h;
            return 'overview';
        }

        function setXrayActiveSection(active) {
            xraySections.forEach(function (id) {
                const el = document.getElementById(id);
                if (!el) return;
                if (id === active) {
                    el.classList.remove('hidden');
                } else {
                    el.classList.add('hidden');
                }
            });
        }

        async function ensureXraySectionLoaded(section) {
            if (section === 'overview') {
                if (!xrayLoaded.overview) {
                    const stats = await loadXrayStats();
                    if (stats) {
                        setText('xrayOrdersToday', stats.orders_today);
                        setText('xrayPending', stats.pending_orders);
                        setText('xrayReported', stats.reported_today);
                        setText('xrayAvgTat', stats.avg_turnaround_mins === null ? '-' : stats.avg_turnaround_mins);
                    }

                    const analytics = await loadXrayAnalytics();
                    renderXrayCharts(analytics);
                    xrayLoaded.overview = true;
                } else {
                    window.setTimeout(function () {
                        if (xrayTypeChart && typeof xrayTypeChart.resize === 'function') xrayTypeChart.resize();
                        if (xrayTimeChart && typeof xrayTimeChart.resize === 'function') xrayTimeChart.resize();
                        if (xrayTatChart && typeof xrayTatChart.resize === 'function') xrayTatChart.resize();
                    }, 0);
                }
                return;
            }

            if (section === 'scheduling') {
                if (xrayLoaded.scheduling) return;
                await renderXrayScheduling();
                xrayLoaded.scheduling = true;
                return;
            }

            if (section === 'worklist') {
                if (xrayLoaded.worklist) return;
                await renderXrayWorklist();
                xrayLoaded.worklist = true;
                return;
            }

            if (section === 'results-release') {
                if (xrayLoaded['results-release']) return;
                if (window.xrayResultsRelease && typeof window.xrayResultsRelease.render === 'function') {
                    await window.xrayResultsRelease.render();
                }
                xrayLoaded['results-release'] = true;
                return;
            }
        }

        async function renderXrayWorklist() {
            const tbody = document.getElementById('xrayWorklistTbody');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';

            const rows = await loadXrayWorklist();
            if (!rows) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-600">Unable to load worklist.</td></tr>';
                return;
            }
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No orders found.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(function (o) {
                const st = statusChip(o.status);
                const pr = priorityChip(o.priority);
                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(o.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(o.exam_type) + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + pr.cls + '">' + escapeHtml(pr.label) + '</span></td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + st.cls + '">' + escapeHtml(st.label) + '</span></td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(o.ordered_at)) + '</td>' +
                    '</tr>'
                );
            }).join('');
        }

        async function renderXrayScheduling() {
            const tbody = document.getElementById('xraySchedulingTbody');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">Loading...</td></tr>';
            const json = await loadXrayScheduling();
            if (!json) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-red-600">Unable to load scheduling.</td></tr>';
                return;
            }

            const summary = json.summary || {};
            setText('xraySchedQueueTotal', summary.queue_total);
            setText('xraySchedInProgress', summary.in_progress);
            setText('xraySchedScheduled', summary.scheduled);

            const grid = document.getElementById('xrayModalityGrid');
            if (grid) {
                const avail = Array.isArray(json.availability) ? json.availability : [];
                grid.innerHTML = avail.map(function (m) {
                    const st = String(m.status || '').toLowerCase();
                    const cls = (st === 'busy') ? 'border-amber-200 bg-amber-50' : 'border-emerald-200 bg-emerald-50';
                    const dot = (st === 'busy') ? 'bg-amber-500' : 'bg-emerald-500';
                    const label = (st === 'busy') ? 'Busy' : 'Available';
                    const nextTxt = (st === 'busy') ? (String(m.next_slot_mins || 0) + ' mins') : 'Now';
                    return (
                        '<div class="rounded-lg border ' + cls + ' p-4">' +
                            '<div class="flex items-center justify-between">' +
                                '<div class="text-sm font-semibold text-gray-900">' + escapeHtml(m.modality) + '</div>' +
                                '<div class="flex items-center gap-2 text-xs text-gray-700"><span class="w-2 h-2 rounded-full ' + dot + '"></span>' + escapeHtml(label) + '</div>' +
                            '</div>' +
                            '<div class="mt-3 grid grid-cols-2 gap-3">' +
                                '<div class="text-xs text-gray-600"><div class="text-gray-500">Next Slot</div><div class="mt-1 font-medium text-gray-900">' + escapeHtml(nextTxt) + '</div></div>' +
                                '<div class="text-xs text-gray-600"><div class="text-gray-500">Queue</div><div class="mt-1 font-medium text-gray-900">' + escapeHtml(m.queue) + '</div></div>' +
                            '</div>' +
                        '</div>'
                    );
                }).join('');
            }

            const rows = Array.isArray(json.queue) ? json.queue : [];
            if (rows.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">No queued studies.</td></tr>';
                return;
            }

            tbody.innerHTML = rows.map(function (o) {
                const st = statusChip(o.status);
                const pr = priorityChip(o.priority);
                return (
                    '<tr class="hover:bg-gray-50">' +
                        '<td class="px-6 py-4 text-sm text-gray-900 font-medium">' + escapeHtml(o.patient_name) + '</td>' +
                        '<td class="px-6 py-4 text-sm text-gray-700">' + escapeHtml(o.exam_type) + '</td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + pr.cls + '">' + escapeHtml(pr.label) + '</span></td>' +
                        '<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full ' + st.cls + '">' + escapeHtml(st.label) + '</span></td>' +
                        '<td class="px-6 py-4 text-sm text-gray-600">' + escapeHtml(fmtDateTime(o.scheduled_at || o.ordered_at)) + '</td>' +
                    '</tr>'
                );
            }).join('');
        }

        async function showXrayResult(id) {
            if (window.xrayResultsRelease && typeof window.xrayResultsRelease.showResult === 'function') {
                await window.xrayResultsRelease.showResult(id);
            }
        }

        function setText(id, val) {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = (val === null || val === undefined || val === '') ? '-' : String(val);
        }

        function renderXrayCharts(analytics) {
            if (!analytics) return;

            const byType = analytics.exams_by_type || { labels: [], data: [] };
            const byTime = analytics.orders_by_time || { labels: [], data: [] };
            const tat = analytics.turnaround_trend || { labels: [], data: [] };

            const ctxType = document.getElementById('xrayByTypeChart').getContext('2d');
            if (xrayTypeChart) xrayTypeChart.destroy();
            xrayTypeChart = new Chart(ctxType, {
                type: 'bar',
                data: {
                    labels: byType.labels || [],
                    datasets: [{
                        label: 'Orders',
                        data: byType.data || [],
                        backgroundColor: '#38BDF880',
                        borderColor: '#0284C7',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            const ctxTime = document.getElementById('xrayByTimeChart').getContext('2d');
            if (xrayTimeChart) xrayTimeChart.destroy();
            xrayTimeChart = new Chart(ctxTime, {
                type: 'line',
                data: {
                    labels: byTime.labels || [],
                    datasets: [{
                        label: 'Orders',
                        data: byTime.data || [],
                        borderColor: '#F59E0B',
                        backgroundColor: '#FCD34D',
                        tension: 0.35,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            const ctxTat = document.getElementById('xrayTurnaroundChart').getContext('2d');
            if (xrayTatChart) xrayTatChart.destroy();
            xrayTatChart = new Chart(ctxTat, {
                type: 'line',
                data: {
                    labels: tat.labels || [],
                    datasets: [{
                        label: 'Avg Minutes',
                        data: tat.data || [],
                        borderColor: '#A855F7',
                        backgroundColor: '#D8B4FE',
                        tension: 0.35,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true, title: { display: true, text: 'Minutes' } } }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', async function () {
            await ensureXrayInstalled();

            async function handle() {
                const active = getXraySectionFromHash();
                setXrayActiveSection(active);
                await ensureXraySectionLoaded(active);
            }

            await handle();
            window.addEventListener('hashchange', handle);

            const btn = document.getElementById('xrayWorklistRefresh');
            if (btn) btn.addEventListener('click', renderXrayWorklist);
            const sel = document.getElementById('xrayWorklistStatus');
            if (sel) sel.addEventListener('change', renderXrayWorklist);
            const q = document.getElementById('xrayWorklistSearch');
            if (q) q.addEventListener('input', function () {
                window.clearTimeout(window.__xrayWorklistTimer);
                window.__xrayWorklistTimer = window.setTimeout(renderXrayWorklist, 300);
            });

            if (window.xrayResultsRelease && typeof window.xrayResultsRelease.bindModalOnce === 'function') {
                window.xrayResultsRelease.bindModalOnce();
            }

            loadXrayQueue();
            window.setInterval(loadXrayQueue, 10000);
        });
    </script>
    <?php include __DIR__ . '/includes/queue-error-correction.php'; ?>
    <script>window.qecStationId = 5; window.qecRefreshQueue = function() { loadXrayQueue(); };</script>
    <?php include __DIR__ . '/includes/queue-error-correction-js.php'; ?>
</body>

</html>
