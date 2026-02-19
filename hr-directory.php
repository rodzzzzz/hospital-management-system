<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR - Staff Directory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Staff Directory</h1>
                    <p class="text-sm text-gray-600 mt-1">Browse and manage staff profiles.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="btnRefresh" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                        <i class="fas fa-rotate-right mr-2"></i>Refresh
                    </button>
                    <button id="btnAddEmployee" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                        <i class="fas fa-user-plus mr-2"></i>Add Staff
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Search</label>
                        <input id="filterQ" type="text" placeholder="Search name or code" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Department</label>
                        <select id="filterDept" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Position</label>
                        <select id="filterPos" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">All Positions</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                        <select id="filterStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Code</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Name</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Department</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Position</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Phone</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Email</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Status</th>
                                <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeesTbody" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>

                <div id="emptyState" class="hidden p-8 text-center text-sm text-gray-600">No staff found.</div>
                <div id="loadingState" class="hidden p-8 text-center text-sm text-gray-600">Loading...</div>
            </div>
        </main>
    </div>

    <div id="employeeModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="font-semibold text-gray-900" id="modalTitle">Add Staff</div>
                <button id="btnCloseModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6">
                <div id="modalError" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Full Name</label>
                        <input id="editFullName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Employee Code</label>
                        <input id="editEmployeeCode" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Phone</label>
                        <input id="editPhone" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Email</label>
                        <input id="editEmail" type="email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Optional">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Department</label>
                        <select id="editDept" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">None</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Position</label>
                        <select id="editPos" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">None</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
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
        let employeesCache = [];
        let departmentsCache = [];
        let positionsCache = [];
        let editingEmployeeId = null;

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
            $('employeesTbody').classList.toggle('hidden', isLoading);
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

        async function loadDepartments() {
            const res = await fetch('api/hr/departments/list.php', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return [];
            return Array.isArray(json.departments) ? json.departments : [];
        }

        async function loadPositions(departmentId) {
            const params = new URLSearchParams();
            if (departmentId) params.set('department_id', String(departmentId));
            const url = 'api/hr/positions/list.php' + (params.toString() ? ('?' + params.toString()) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return [];
            return Array.isArray(json.positions) ? json.positions : [];
        }

        function fillSelect(selectEl, items, valueKey, labelKey, defaultLabel, includeEmpty) {
            const prev = selectEl.value;
            selectEl.innerHTML = '';
            if (includeEmpty) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = defaultLabel;
                selectEl.appendChild(opt);
            }
            items.forEach(it => {
                const opt = document.createElement('option');
                opt.value = String(it[valueKey] ?? '');
                opt.textContent = String(it[labelKey] ?? '');
                selectEl.appendChild(opt);
            });
            if (prev) selectEl.value = prev;
        }

        async function reloadTaxonomies() {
            departmentsCache = await loadDepartments();
            positionsCache = await loadPositions('');

            fillSelect($('filterDept'), departmentsCache, 'id', 'name', 'All Departments', true);
            fillSelect($('filterPos'), positionsCache, 'id', 'name', 'All Positions', true);

            fillSelect($('editDept'), departmentsCache, 'id', 'name', 'None', true);
            await reloadEditPositions();
        }

        async function reloadEditPositions() {
            const deptId = $('editDept').value;
            const list = await loadPositions(deptId);
            const prev = $('editPos').value;
            fillSelect($('editPos'), list, 'id', 'name', 'None', true);
            if (prev) $('editPos').value = prev;
        }

        function buildListUrl() {
            const q = $('filterQ').value.trim();
            const deptId = $('filterDept').value.trim();
            const posId = $('filterPos').value.trim();
            const status = $('filterStatus').value.trim();

            const params = new URLSearchParams();
            if (q) params.set('q', q);
            if (deptId) params.set('department_id', deptId);
            if (posId) params.set('position_id', posId);
            if (status) params.set('status', status);

            const qs = params.toString();
            return 'api/hr/employees/list.php' + (qs ? ('?' + qs) : '');
        }

        function renderEmployees(list) {
            const tbody = $('employeesTbody');
            tbody.innerHTML = '';

            if (!Array.isArray(list) || list.length === 0) {
                setEmpty(true);
                return;
            }
            setEmpty(false);

            list.forEach(e => {
                const tr = document.createElement('tr');
                const status = String(e.status || '').toLowerCase();
                const statusBadge = status === 'inactive'
                    ? '<span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 text-gray-700">Inactive</span>'
                    : '<span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-emerald-100 text-emerald-800">Active</span>';

                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(e.employee_code || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(e.full_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(e.department_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(e.position_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(e.phone || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(e.email || '')}</td>
                    <td class="px-4 py-3 text-sm">${statusBadge}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <button class="btnEdit px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700" data-id="${e.id}">
                            <i class="fas fa-pen mr-1"></i>Edit
                        </button>
                        <button class="btnDelete px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white ml-2" data-id="${e.id}">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </td>
                `;

                tbody.appendChild(tr);
            });
        }

        async function loadEmployees() {
            setLoading(true);
            setEmpty(false);

            try {
                const url = buildListUrl();
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {
                    employeesCache = [];
                    renderEmployees([]);
                    return;
                }

                employeesCache = Array.isArray(json.employees) ? json.employees : [];
                renderEmployees(employeesCache);
            } catch (e) {
                employeesCache = [];
                renderEmployees([]);
            } finally {
                setLoading(false);
            }
        }

        function openModal(mode, employee) {
            $('employeeModal').classList.remove('hidden');
            $('employeeModal').classList.add('flex');
            showModalError('');

            if (mode === 'edit' && employee) {
                editingEmployeeId = Number(employee.id);
                $('modalTitle').textContent = 'Edit Staff';
                $('editFullName').value = employee.full_name || '';
                $('editEmployeeCode').value = employee.employee_code || '';
                $('editPhone').value = employee.phone || '';
                $('editEmail').value = employee.email || '';
                $('editStatus').value = (employee.status || 'active').toLowerCase();
                $('editDept').value = employee.department_id ? String(employee.department_id) : '';
            } else {
                editingEmployeeId = null;
                $('modalTitle').textContent = 'Add Staff';
                $('editFullName').value = '';
                $('editEmployeeCode').value = '';
                $('editPhone').value = '';
                $('editEmail').value = '';
                $('editStatus').value = 'active';
                $('editDept').value = '';
                $('editPos').value = '';
            }

            reloadEditPositions().then(() => {
                if (mode === 'edit' && employee) {
                    $('editPos').value = employee.position_id ? String(employee.position_id) : '';
                }
            });
        }

        function closeModal() {
            $('employeeModal').classList.add('hidden');
            $('employeeModal').classList.remove('flex');
            editingEmployeeId = null;
        }

        async function saveEmployee() {
            showModalError('');

            const payload = {
                full_name: $('editFullName').value.trim(),
                employee_code: $('editEmployeeCode').value.trim(),
                phone: $('editPhone').value.trim(),
                email: $('editEmail').value.trim(),
                department_id: $('editDept').value.trim(),
                position_id: $('editPos').value.trim(),
                status: $('editStatus').value.trim(),
            };

            if (!payload.full_name) {
                showModalError('Full Name is required.');
                return;
            }

            if (payload.department_id === '') payload.department_id = null;
            if (payload.position_id === '') payload.position_id = null;
            if (payload.employee_code === '') payload.employee_code = null;
            if (payload.phone === '') payload.phone = null;
            if (payload.email === '') payload.email = null;

            const isEdit = (editingEmployeeId !== null);
            if (isEdit) payload.employee_id = editingEmployeeId;

            const endpoint = isEdit ? 'api/hr/employees/update.php' : 'api/hr/employees/create.php';

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {
                    const msg = (json && json.error) ? String(json.error) : 'Unable to save staff.';
                    showModalError(msg);
                    return;
                }

                closeModal();
                await loadEmployees();
            } catch (e) {
                showModalError('Unable to save staff.');
            }
        }

        async function deleteEmployee(id) {
            if (!confirm('Delete this staff record?')) return;

            try {
                const res = await fetch('api/hr/employees/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ employee_id: id })
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    alert((json && json.error) ? String(json.error) : 'Unable to delete staff.');
                    return;
                }
                await loadEmployees();
            } catch (e) {
                alert('Unable to delete staff.');
            }
        }

        function attachEvents() {
            $('btnAddEmployee').addEventListener('click', () => openModal('add'));
            $('btnCloseModal').addEventListener('click', closeModal);
            $('btnCancel').addEventListener('click', closeModal);
            $('btnSave').addEventListener('click', saveEmployee);
            $('btnRefresh').addEventListener('click', loadEmployees);

            $('employeeModal').addEventListener('click', (e) => {
                if (e.target === $('employeeModal')) closeModal();
            });

            $('editDept').addEventListener('change', async () => {
                $('editPos').value = '';
                await reloadEditPositions();
            });

            $('filterDept').addEventListener('change', async () => {
                const deptId = $('filterDept').value.trim();
                const list = await loadPositions(deptId);
                fillSelect($('filterPos'), list, 'id', 'name', 'All Positions', true);
                $('filterPos').value = '';
                await loadEmployees();
            });

            const scheduleReload = (() => {
                let t = null;
                return () => {
                    if (t) clearTimeout(t);
                    t = setTimeout(loadEmployees, 250);
                };
            })();

            $('filterQ').addEventListener('input', scheduleReload);
            $('filterPos').addEventListener('change', loadEmployees);
            $('filterStatus').addEventListener('change', loadEmployees);

            $('employeesTbody').addEventListener('click', (e) => {
                const editBtn = e.target.closest ? e.target.closest('.btnEdit') : null;
                const delBtn = e.target.closest ? e.target.closest('.btnDelete') : null;

                if (editBtn) {
                    const id = parseInt(editBtn.getAttribute('data-id') || '0', 10);
                    const emp = employeesCache.find(x => Number(x.id) === id);
                    if (emp) openModal('edit', emp);
                    return;
                }

                if (delBtn) {
                    const id = parseInt(delBtn.getAttribute('data-id') || '0', 10);
                    if (id > 0) deleteEmployee(id);
                }
            });
        }

        (async function init() {
            attachEvents();
            await ensureTables();
            await reloadTaxonomies();
            await loadEmployees();
        })();
    </script>
</body>
</html>
