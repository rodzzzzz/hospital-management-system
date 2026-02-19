<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xray - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    <script>
        let xrayTypeChart = null;
        let xrayTimeChart = null;
        let xrayTatChart = null;

        async function ensureXrayInstalled() {
            try {
                await fetch('api/xray/install.php', { headers: { 'Accept': 'application/json' } });
            } catch (e) {
            }
        }

        async function loadXrayStats() {
            const res = await fetch('api/xray/stats.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json.stats || null;
        }

        async function loadXrayAnalytics() {
            const res = await fetch('api/xray/analytics.php', { headers: { 'Accept': 'application/json' } });
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
            const url = 'api/xray/list.php' + (params.toString() ? ('?' + params.toString()) : '');
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
            const res = await fetch('api/xray/scheduling.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return null;
            return json;
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
        });
    </script>
</body>

</html>
