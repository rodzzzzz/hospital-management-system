<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR - Departments & Positions</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Departments &amp; Positions</h1>
                    <p class="text-sm text-gray-600 mt-1">Maintain departments, positions, and roles.</p>
                </div>
                <div class="flex items-center gap-2">
                    <button id="btnRefresh" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                        <i class="fas fa-rotate-right mr-2"></i>Refresh
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <section class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Departments</div>
                            <div class="text-xs text-gray-600 mt-1">Create and manage department list.</div>
                        </div>
                        <button id="btnAddDept" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                            <i class="fas fa-plus mr-2"></i>Add
                        </button>
                    </div>

                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Search</label>
                                <input id="deptQ" type="text" placeholder="Search department" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                                <select id="deptStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Name</th>
                                    <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Status</th>
                                    <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="deptTbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                        <div id="deptEmpty" class="hidden p-8 text-center text-sm text-gray-600">No departments found.</div>
                    </div>
                </section>

                <section class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Positions</div>
                            <div class="text-xs text-gray-600 mt-1">Create positions and optionally link them to departments.</div>
                        </div>
                        <button id="btnAddPos" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                            <i class="fas fa-plus mr-2"></i>Add
                        </button>
                    </div>

                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Department</label>
                                <select id="posDept" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">All Departments</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Search</label>
                                <input id="posQ" type="text" placeholder="Search position" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                                <select id="posStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                                    <option value="">All</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 overflow-x-auto">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Department</th>
                                    <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Name</th>
                                    <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Status</th>
                                    <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="posTbody" class="divide-y divide-gray-100"></tbody>
                        </table>
                        <div id="posEmpty" class="hidden p-8 text-center text-sm text-gray-600">No positions found.</div>
                    </div>
                </section>
            </div>
        </main>
    </div>

    <div id="deptModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="font-semibold text-gray-900" id="deptModalTitle">Add Department</div>
                <button id="btnCloseDeptModal" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6">
                <div id="deptModalError" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Name</label>
                        <input id="editDeptName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                        <select id="editDeptStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50">
                <button id="btnDeptCancel" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">Cancel</button>
                <button id="btnDeptSave" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">Save</button>
            </div>
        </div>
    </div>

    <div id="posModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="font-semibold text-gray-900" id="posModalTitle">Add Position</div>
                <button id="btnClosePosModal" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
            </div>
            <div class="p-6">
                <div id="posModalError" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Department (optional)</label>
                        <select id="editPosDept" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">None</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Name</label>
                        <input id="editPosName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                        <select id="editPosStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50">
                <button id="btnPosCancel" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">Cancel</button>
                <button id="btnPosSave" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">Save</button>
            </div>
        </div>
    </div>

    <script>
        let deptCache = [];
        let posCache = [];
        let editingDeptId = null;
        let editingPosId = null;

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

        function showError(el, msg) {
            if (!msg) {
                el.textContent = '';
                el.classList.add('hidden');
                return;
            }
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function badge(status) {
            const s = String(status || '').toLowerCase();
            return s === 'inactive'
                ? '<span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 text-gray-700">Inactive</span>'
                : '<span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-emerald-100 text-emerald-800">Active</span>';
        }

        function fillDeptSelect(selectEl, includeAllLabel) {
            const prev = selectEl.value;
            selectEl.innerHTML = '';

            if (includeAllLabel) {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = includeAllLabel;
                selectEl.appendChild(opt);
            } else {
                const opt = document.createElement('option');
                opt.value = '';
                opt.textContent = 'None';
                selectEl.appendChild(opt);
            }

            deptCache.forEach(d => {
                const opt = document.createElement('option');
                opt.value = String(d.id);
                opt.textContent = String(d.name);
                selectEl.appendChild(opt);
            });

            if (prev) selectEl.value = prev;
        }

        function setEmpty(el, isEmpty) {
            el.classList.toggle('hidden', !isEmpty);
        }

        function buildDeptListUrl() {
            const q = $('deptQ').value.trim();
            const status = $('deptStatus').value.trim();
            const params = new URLSearchParams();
            if (q) params.set('q', q);
            if (status) params.set('status', status);
            const qs = params.toString();
            return API_BASE_URL + '/hr/departments/list.php' + (qs ? ('?' + qs) : '');
        }

        function buildPosListUrl() {
            const dept = $('posDept').value.trim();
            const q = $('posQ').value.trim();
            const status = $('posStatus').value.trim();
            const params = new URLSearchParams();
            if (dept) params.set('department_id', dept);
            if (q) params.set('q', q);
            if (status) params.set('status', status);
            const qs = params.toString();
            return API_BASE_URL + '/hr/positions/list.php' + (qs ? ('?' + qs) : '');
        }

        async function loadDepartments() {
            const res = await fetch(buildDeptListUrl(), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return [];
            return Array.isArray(json.departments) ? json.departments : [];
        }

        async function loadPositions() {
            const res = await fetch(buildPosListUrl(), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) return [];
            return Array.isArray(json.positions) ? json.positions : [];
        }

        function renderDepartments(list) {
            const tbody = $('deptTbody');
            tbody.innerHTML = '';

            if (!Array.isArray(list) || list.length === 0) {
                setEmpty($('deptEmpty'), true);
                return;
            }
            setEmpty($('deptEmpty'), false);

            list.forEach(d => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(d.name || '')}</td>
                    <td class="px-4 py-3 text-sm">${badge(d.status)}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <button class="btnEditDept px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700" data-id="${d.id}">
                            <i class="fas fa-pen mr-1"></i>Edit
                        </button>
                        <button class="btnDeleteDept px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white ml-2" data-id="${d.id}">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderPositions(list) {
            const tbody = $('posTbody');
            tbody.innerHTML = '';

            if (!Array.isArray(list) || list.length === 0) {
                setEmpty($('posEmpty'), true);
                return;
            }
            setEmpty($('posEmpty'), false);

            list.forEach(p => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(p.department_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(p.name || '')}</td>
                    <td class="px-4 py-3 text-sm">${badge(p.status)}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        <button class="btnEditPos px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700" data-id="${p.id}">
                            <i class="fas fa-pen mr-1"></i>Edit
                        </button>
                        <button class="btnDeletePos px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white ml-2" data-id="${p.id}">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function openDeptModal(mode, dept) {
            $('deptModal').classList.remove('hidden');
            $('deptModal').classList.add('flex');
            showError($('deptModalError'), '');

            if (mode === 'edit' && dept) {
                editingDeptId = Number(dept.id);
                $('deptModalTitle').textContent = 'Edit Department';
                $('editDeptName').value = dept.name || '';
                $('editDeptStatus').value = (dept.status || 'active').toLowerCase();
            } else {
                editingDeptId = null;
                $('deptModalTitle').textContent = 'Add Department';
                $('editDeptName').value = '';
                $('editDeptStatus').value = 'active';
            }
        }

        function closeDeptModal() {
            $('deptModal').classList.add('hidden');
            $('deptModal').classList.remove('flex');
            editingDeptId = null;
        }

        function openPosModal(mode, pos) {
            $('posModal').classList.remove('hidden');
            $('posModal').classList.add('flex');
            showError($('posModalError'), '');

            fillDeptSelect($('editPosDept'), null);

            if (mode === 'edit' && pos) {
                editingPosId = Number(pos.id);
                $('posModalTitle').textContent = 'Edit Position';
                $('editPosDept').value = pos.department_id ? String(pos.department_id) : '';
                $('editPosName').value = pos.name || '';
                $('editPosStatus').value = (pos.status || 'active').toLowerCase();
            } else {
                editingPosId = null;
                $('posModalTitle').textContent = 'Add Position';
                $('editPosDept').value = '';
                $('editPosName').value = '';
                $('editPosStatus').value = 'active';
            }
        }

        function closePosModal() {
            $('posModal').classList.add('hidden');
            $('posModal').classList.remove('flex');
            editingPosId = null;
        }

        async function saveDept() {
            showError($('deptModalError'), '');
            const payload = {
                name: $('editDeptName').value.trim(),
                status: $('editDeptStatus').value.trim() || 'active',
            };
            if (!payload.name) {
                showError($('deptModalError'), 'Name is required.');
                return;
            }

            const isEdit = (editingDeptId !== null);
            if (isEdit) payload.department_id = editingDeptId;
            const endpoint = isEdit ? API_BASE_URL + '/hr/departments/update.php' : API_BASE_URL + '/hr/departments/create.php';

            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showError($('deptModalError'), (json && json.error) ? String(json.error) : 'Unable to save department.');
                return;
            }

            closeDeptModal();
            await refreshAll();
        }

        async function deleteDept(id) {
            if (!confirm('Delete this department?')) return;
            const res = await fetch(API_BASE_URL + '/hr/departments/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ department_id: id })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? String(json.error) : 'Unable to delete department.');
                return;
            }
            await refreshAll();
        }

        async function savePos() {
            showError($('posModalError'), '');
            const payload = {
                department_id: $('editPosDept').value.trim(),
                name: $('editPosName').value.trim(),
                status: $('editPosStatus').value.trim() || 'active',
            };
            if (!payload.name) {
                showError($('posModalError'), 'Name is required.');
                return;
            }
            if (payload.department_id === '') payload.department_id = null;

            const isEdit = (editingPosId !== null);
            if (isEdit) payload.position_id = editingPosId;
            const endpoint = isEdit ? API_BASE_URL + '/hr/positions/update.php' : API_BASE_URL + '/hr/positions/create.php';

            const res = await fetch(endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload)
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                showError($('posModalError'), (json && json.error) ? String(json.error) : 'Unable to save position.');
                return;
            }

            closePosModal();
            await refreshAll();
        }

        async function deletePos(id) {
            if (!confirm('Delete this position?')) return;
            const res = await fetch(API_BASE_URL + '/hr/positions/delete.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify({ position_id: id })
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? String(json.error) : 'Unable to delete position.');
                return;
            }
            await refreshAll();
        }

        async function refreshAll() {
            deptCache = await loadDepartments();
            renderDepartments(deptCache);

            fillDeptSelect($('posDept'), 'All Departments');
            fillDeptSelect($('editPosDept'), null);

            posCache = await loadPositions();
            renderPositions(posCache);
        }

        function attachEvents() {
            $('btnRefresh').addEventListener('click', refreshAll);

            $('btnAddDept').addEventListener('click', () => openDeptModal('add'));
            $('btnCloseDeptModal').addEventListener('click', closeDeptModal);
            $('btnDeptCancel').addEventListener('click', closeDeptModal);
            $('btnDeptSave').addEventListener('click', saveDept);
            $('deptModal').addEventListener('click', (e) => { if (e.target === $('deptModal')) closeDeptModal(); });

            $('btnAddPos').addEventListener('click', () => openPosModal('add'));
            $('btnClosePosModal').addEventListener('click', closePosModal);
            $('btnPosCancel').addEventListener('click', closePosModal);
            $('btnPosSave').addEventListener('click', savePos);
            $('posModal').addEventListener('click', (e) => { if (e.target === $('posModal')) closePosModal(); });

            const schedule = (fn) => {
                let t = null;
                return () => { if (t) clearTimeout(t); t = setTimeout(fn, 250); };
            };

            $('deptQ').addEventListener('input', schedule(refreshAll));
            $('deptStatus').addEventListener('change', refreshAll);

            $('posDept').addEventListener('change', refreshAll);
            $('posQ').addEventListener('input', schedule(refreshAll));
            $('posStatus').addEventListener('change', refreshAll);

            $('deptTbody').addEventListener('click', (e) => {
                const editBtn = e.target.closest ? e.target.closest('.btnEditDept') : null;
                const delBtn = e.target.closest ? e.target.closest('.btnDeleteDept') : null;

                if (editBtn) {
                    const id = parseInt(editBtn.getAttribute('data-id') || '0', 10);
                    const dept = deptCache.find(x => Number(x.id) === id);
                    if (dept) openDeptModal('edit', dept);
                    return;
                }

                if (delBtn) {
                    const id = parseInt(delBtn.getAttribute('data-id') || '0', 10);
                    if (id > 0) deleteDept(id);
                }
            });

            $('posTbody').addEventListener('click', (e) => {
                const editBtn = e.target.closest ? e.target.closest('.btnEditPos') : null;
                const delBtn = e.target.closest ? e.target.closest('.btnDeletePos') : null;

                if (editBtn) {
                    const id = parseInt(editBtn.getAttribute('data-id') || '0', 10);
                    const pos = posCache.find(x => Number(x.id) === id);
                    if (pos) openPosModal('edit', pos);
                    return;
                }

                if (delBtn) {
                    const id = parseInt(delBtn.getAttribute('data-id') || '0', 10);
                    if (id > 0) deletePos(id);
                }
            });
        }

        (async function init() {
            attachEvents();
            await ensureTables();
            await refreshAll();
        })();
    </script>
</body>
</html>
