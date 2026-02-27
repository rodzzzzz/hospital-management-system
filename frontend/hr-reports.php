<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR - Reports</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Reports</h1>
                    <p class="text-sm text-gray-600 mt-1">Staffing, schedules, and compliance reports.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <section class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div class="flex items-start justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Staff Count by Status</div>
                            <div class="text-sm text-gray-600 mt-1">Active vs Inactive employees.</div>
                        </div>
                        <button id="btnRefresh" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-rotate-right mr-2"></i>Refresh
                        </button>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-4">
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                            <div class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Active Staff</div>
                            <div id="countActive" class="text-2xl font-bold text-gray-900 mt-2">-</div>
                        </div>
                        <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                            <div class="text-xs font-semibold tracking-wider text-gray-500 uppercase">Inactive Staff</div>
                            <div id="countInactive" class="text-2xl font-bold text-gray-900 mt-2">-</div>
                        </div>
                    </div>

                    <div class="mt-6 text-xs text-gray-500">Note: This report uses the current employee list and is meant as a quick overview.</div>
                </section>

                <section class="bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                    <div>
                        <div class="text-sm font-semibold text-gray-900">Upcoming Shifts</div>
                        <div class="text-sm text-gray-600 mt-1">Next 7 days.</div>
                    </div>

                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">From</label>
                            <input id="from" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">To</label>
                            <input id="to" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button id="btnLoadShifts" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                            <i class="fas fa-table mr-2"></i>Load
                        </button>
                    </div>

                    <div class="mt-4 border border-gray-100 rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Date</th>
                                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Time</th>
                                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Employee</th>
                                    </tr>
                                </thead>
                                <tbody id="shiftsTbody" class="divide-y divide-gray-100"></tbody>
                            </table>
                        </div>
                        <div id="shiftsEmpty" class="hidden p-6 text-center text-sm text-gray-600">No shifts found.</div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <script>
        function $(id) { return document.getElementById(id); }

        function escapeHtml(s) {
            return String(s ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        async function ensureTables() {
            try {
                await fetch(API_BASE_URL + '/hr/install.php', { method: 'POST', headers: { 'Accept': 'application/json' } });
            } catch (e) {
            }
        }

        async function loadCounts() {
            const res = await fetch(API_BASE_URL + '/hr/employees/list.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                $('countActive').textContent = '-';
                $('countInactive').textContent = '-';
                return;
            }

            const list = Array.isArray(json.employees) ? json.employees : [];
            let active = 0;
            let inactive = 0;
            list.forEach(e => {
                const s = String(e.status || '').toLowerCase();
                if (s === 'inactive') inactive++;
                else active++;
            });

            $('countActive').textContent = String(active);
            $('countInactive').textContent = String(inactive);
        }

        function setShiftsEmpty(isEmpty) {
            $('shiftsEmpty').classList.toggle('hidden', !isEmpty);
        }

        async function loadUpcomingShifts() {
            const from = $('from').value.trim();
            const to = $('to').value.trim();

            const params = new URLSearchParams();
            if (from) params.set('date_from', from);
            if (to) params.set('date_to', to);

            const url = API_BASE_URL + '/hr/schedules/list.php' + (params.toString() ? ('?' + params.toString()) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);

            const tbody = $('shiftsTbody');
            tbody.innerHTML = '';

            if (!res.ok || !json || !json.ok) {
                setShiftsEmpty(true);
                return;
            }

            const list = Array.isArray(json.schedules) ? json.schedules : [];
            if (list.length === 0) {
                setShiftsEmpty(true);
                return;
            }
            setShiftsEmpty(false);

            list.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(s.shift_date || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml((s.start_time || '') + ' - ' + (s.end_time || ''))}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(s.full_name || '')}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function toISODate(d) {
            return d.toISOString().slice(0, 10);
        }

        function attachEvents() {
            $('btnRefresh').addEventListener('click', loadCounts);
            $('btnLoadShifts').addEventListener('click', loadUpcomingShifts);
        }

        (async function init() {
            attachEvents();
            await ensureTables();

            const now = new Date();
            const from = new Date(now);
            const to = new Date(now);
            to.setDate(to.getDate() + 7);
            $('from').value = toISODate(from);
            $('to').value = toISODate(to);

            await loadCounts();
            await loadUpcomingShifts();
        })();
    </script>
</body>
</html>
