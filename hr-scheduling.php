<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR - Scheduling / Duty Roster</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Scheduling / Duty Roster</h1>
                    <p class="text-sm text-gray-600 mt-1">Assign shifts and manage coverage.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="btnRefresh" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                        <i class="fas fa-rotate-right mr-2"></i>Refresh
                    </button>
                    <button id="btnAdd" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                        <i class="fas fa-plus mr-2"></i>Add Shift
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Employee</label>
                        <select id="filterEmployee" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">All Employees</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">From</label>
                        <input id="filterFrom" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">To</label>
                        <input id="filterTo" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div class="flex items-end">
                        <button id="btnApply" class="w-full px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-filter mr-2"></i>Apply
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Date</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Time</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Employee</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Department</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Position</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Notes</th>
                                <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tbody" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
                <div id="emptyState" class="hidden p-8 text-center text-sm text-gray-600">No shifts found.</div>
                <div id="loadingState" class="hidden p-8 text-center text-sm text-gray-600">Loading...</div>
            </div>
        </main>
    </div>

    <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="w-full max-w-xl bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="font-semibold text-gray-900" id="modalTitle">Add Shift</div>
                <button id="btnCloseModal" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6">
                <div id="modalError" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Employee</label>
                        <select id="editEmployee" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200"></select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Date</label>
                        <input id="editDate" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Start Time</label>
                        <input id="editStart" type="time" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">End Time</label>
                        <input id="editEnd" type="time" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Notes</label>
                        <input id="editNotes" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Optional">
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50">
                <button id="btnCancel" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">Cancel</button>
                <button id="btnSave" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">Save</button>
            </div>
        </div>
    </div>

    <script>
        let schedulesCache = [];
        let employeesCache = [];

        function $(id) { return document.getElementById(id); }

        function escapeHtml(s) {
            return String(s ?? '')
                .replaceAll('&', '&amp;')
                .replaceAll('<', '&lt;')
                .replaceAll('>', '&gt;')
                .replaceAll('"', '&quot;')
                .replaceAll("'", '&#039;');
        }

        function setLoading(isLoading) {
            $('loadingState').classList.toggle('hidden', !isLoading);
            $('tbody').classList.toggle('hidden', isLoading);
        }

        function setEmpty(isEmpty) {
            $('emptyState').classList.toggle('hidden', !isEmpty);
        }

        function showModalError(msg) {
            const el = $('modalError');
            if (!msg) {
                el.textContent = '';
                el.classList.add('hidden');
                return;
            }
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        async function ensureTables() {
            try {
                await fetch('api/hr/install.php', { method: 'POST', headers: { 'Accept': 'application/json' } });
            } catch (e) {
            }
        }

        async function loadEmployees() {
            const res = await fetch('api/hr/employees/list.php?status=active', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return [];
            return Array.isArray(json.employees) ? json.employees : [];
        }

        function fillEmployeeSelect(selectEl, includeAllLabel) {
            const prev = selectEl.value;
            selectEl.innerHTML = '';

            const opt0 = document.createElement('option');
            opt0.value = '';
            opt0.textContent = includeAllLabel ? includeAllLabel : 'Select Employee';
            selectEl.appendChild(opt0);

            employeesCache.forEach(e => {
                const opt = document.createElement('option');
                opt.value = String(e.id);
                const labelParts = [e.full_name];
                if (e.employee_code) labelParts.push('(' + e.employee_code + ')');
                opt.textContent = labelParts.filter(Boolean).join(' ');
                selectEl.appendChild(opt);
            });

            if (prev) selectEl.value = prev;
        }

        function buildListUrl() {
            const params = new URLSearchParams();
            const emp = $('filterEmployee').value.trim();
            const from = $('filterFrom').value.trim();
            const to = $('filterTo').value.trim();

            if (emp) params.set('employee_id', emp);
            if (from) params.set('date_from', from);
            if (to) params.set('date_to', to);

            const qs = params.toString();
            return 'api/hr/schedules/list.php' + (qs ? ('?' + qs) : '');
        }

        function render(list) {
            const tbody = $('tbody');
            tbody.innerHTML = '';

            if (!Array.isArray(list) || list.length === 0) {
                setEmpty(true);
                return;
            }
            setEmpty(false);

            list.forEach(s => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(s.shift_date || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml((s.start_time || '') + ' - ' + (s.end_time || ''))}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(s.full_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(s.department_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(s.position_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(s.notes || '')}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <button class="btnDelete px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white" data-id="${s.id}">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        async function loadSchedules() {
            setLoading(true);
            setEmpty(false);
            try {
                const res = await fetch(buildListUrl(), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    schedulesCache = [];
                    render([]);
                    return;
                }
                schedulesCache = Array.isArray(json.schedules) ? json.schedules : [];
                render(schedulesCache);
            } catch (e) {
                schedulesCache = [];
                render([]);
            } finally {
                setLoading(false);
            }
        }

        function openModal() {
            $('modal').classList.remove('hidden');
            $('modal').classList.add('flex');
            showModalError('');
            $('modalTitle').textContent = 'Add Shift';

            fillEmployeeSelect($('editEmployee'), false);

            $('editEmployee').value = '';
            $('editDate').value = '';
            $('editStart').value = '';
            $('editEnd').value = '';
            $('editNotes').value = '';
        }

        function closeModal() {
            $('modal').classList.add('hidden');
            $('modal').classList.remove('flex');
        }

        async function save() {
            showModalError('');

            const payload = {
                employee_id: $('editEmployee').value.trim(),
                shift_date: $('editDate').value.trim(),
                start_time: $('editStart').value.trim(),
                end_time: $('editEnd').value.trim(),
                notes: $('editNotes').value.trim(),
            };

            if (!payload.employee_id || !payload.shift_date || !payload.start_time || !payload.end_time) {
                showModalError('Employee, Date, Start Time, and End Time are required.');
                return;
            }
            if (payload.notes === '') payload.notes = null;

            const res = await fetch('api/hr/schedules/create.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json().catch(() => null);

            if (!res.ok || !json || !json.ok) {
                showModalError((json && json.error) ? String(json.error) : 'Unable to save schedule.');
                return;
            }

            closeModal();
            await loadSchedules();
        }

        async function del(id) {
            if (!confirm('Delete this shift?')) return;
            const res = await fetch('api/hr/schedules/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ schedule_id: id })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? String(json.error) : 'Unable to delete schedule.');
                return;
            }
            await loadSchedules();
        }

        function attachEvents() {
            $('btnRefresh').addEventListener('click', loadSchedules);
            $('btnAdd').addEventListener('click', openModal);
            $('btnCloseModal').addEventListener('click', closeModal);
            $('btnCancel').addEventListener('click', closeModal);
            $('btnSave').addEventListener('click', save);
            $('btnApply').addEventListener('click', loadSchedules);

            $('modal').addEventListener('click', (e) => { if (e.target === $('modal')) closeModal(); });

            $('tbody').addEventListener('click', (e) => {
                const delBtn = e.target.closest ? e.target.closest('.btnDelete') : null;
                if (!delBtn) return;
                const id = parseInt(delBtn.getAttribute('data-id') || '0', 10);
                if (id > 0) del(id);
            });

            $('filterEmployee').addEventListener('change', loadSchedules);
        }

        (async function init() {
            attachEvents();
            await ensureTables();
            employeesCache = await loadEmployees();
            fillEmployeeSelect($('filterEmployee'), 'All Employees');
            fillEmployeeSelect($('editEmployee'), false);
            await loadSchedules();
        })();
    </script>
</body>
</html>
