<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR - User Accounts / Access</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">User Accounts / Access</h1>
                    <p class="text-sm text-gray-600 mt-1">Create accounts and assign module roles (ER Nurse, NP/PA, MedTech, etc.).</p>
                </div>
                <div class="flex items-center gap-2">
                    <?php if (isset($authUser) && is_array($authUser) && function_exists('auth_user_has_module') && auth_user_has_module($authUser, 'ADMIN')): ?>
                        <button id="btnSeed" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-seedling mr-2"></i>Seed Test Accounts
                        </button>
                    <?php endif; ?>
                    <button id="btnRefresh" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                        <i class="fas fa-rotate-right mr-2"></i>Refresh
                    </button>
                    <button id="btnAddUser" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                        <i class="fas fa-user-plus mr-2"></i>Add User
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Search</label>
                        <input id="filterQ" type="text" placeholder="Search name or username" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Module</label>
                        <select id="filterModule" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="">All Modules</option>
                            <option value="ER">ER</option>
                            <option value="OPD">OPD</option>
                            <option value="LAB">Laboratory</option>
                            <option value="ICU">ICU</option>
                            <option value="XRAY">Xray</option>
                            <option value="DOCTOR">Doctor</option>
                            <option value="PHARMACY">Pharmacy</option>
                            <option value="CASHIER">Cashier</option>
                            <option value="HR">HR</option>
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
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Name</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Username</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Status</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Roles</th>
                                <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="usersTbody" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>

                <div id="emptyState" class="hidden p-8 text-center text-sm text-gray-600">
                    No users found.
                </div>

                <div id="loadingState" class="hidden p-8 text-center text-sm text-gray-600">
                    Loading...
                </div>
            </div>
        </main>
    </div>

    <div id="userModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
        <div class="w-full max-w-5xl bg-white rounded-xl shadow-xl overflow-hidden max-h-[90vh] flex flex-col">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <div class="font-semibold text-gray-900" id="modalTitle">Add User</div>
                <button id="btnCloseModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto flex-1">
                <div id="modalError" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>

                <div id="modeToggle" class="mb-4">
                    <div class="inline-flex rounded-lg border border-gray-200 bg-white overflow-hidden">
                        <button type="button" id="btnModeCreate" class="px-3 py-2 text-sm font-semibold text-gray-700 bg-gray-50">Create Account</button>
                        <button type="button" id="btnModeAssign" class="px-3 py-2 text-sm font-semibold text-gray-700">Assign Registered User</button>
                    </div>
                </div>

                <div id="assignModeBlock" class="hidden">
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Find Registered (Unassigned) User</label>
                    <input id="findUserQ" type="text" placeholder="Search email" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    <div id="findUserResults" class="mt-2 border border-gray-200 rounded-lg overflow-hidden hidden"></div>
                    <div class="mt-1 text-xs text-gray-500">Pick a registered user to assign roles. (Email is the username.)</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Full Name</label>
                        <input id="editFullName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Email</label>
                        <input id="editUsername" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                    </div>

                    <div id="createModeBlock" class="md:col-span-2 hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Password</label>
                                <input id="editPassword" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Confirm Password</label>
                                <input id="editPassword2" type="password" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            </div>
                            <div class="md:col-span-2 text-xs text-gray-500">
                                Set an initial password for this account.
                            </div>
                        </div>
                    </div>

                    <div id="statusBlock">
                        <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Status</label>
                        <select id="editStatus" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>

                <div id="rolesBlock" class="mt-6">
                    <div class="text-xs font-semibold tracking-wider text-gray-500 uppercase mb-2">Roles</div>
                    <div id="rolesGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3"></div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50">
                <button id="btnCancel" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">Cancel</button>
                <button id="btnSave" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">Save</button>
            </div>
        </div>
    </div>

    <script>
        const IS_ADMIN = <?php echo (isset($authUser) && is_array($authUser) && function_exists('auth_user_has_module') && auth_user_has_module($authUser, 'ADMIN')) ? 'true' : 'false'; ?>;
        const MODULE_ROLES = {
            'ER': ['ER Nurse', 'NP/PA'],
            'LAB': ['MedTech', 'Lab Supervisor'],
            'ICU': ['ICU Nurse', 'ICU Staff'],
            'XRAY': ['Xray Tech', 'Radiologist'],
            'DOCTOR': ['Doctor', 'Operating Room Staff', 'Delivery Room Staff'],
            'CASHIER': ['Cashier', 'Billing'],
            'PHARMACY': ['Pharmacist', 'Pharmacy Assistant'],
            'OPD': ['OPD Nurse', 'OPD Clerk'],
            'HR': ['HR Staff', 'HR Admin'],
        };

        let usersCache = [];
        let editingUserId = null;
        let modalMode = 'create';

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
            $('usersTbody').classList.toggle('hidden', isLoading);
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

        function clearRoleSelections() {
            document.querySelectorAll('.roleCheck').forEach(chk => {
                chk.checked = false;
            });
        }

        function setModalMode(mode) {
            modalMode = mode;

            const btnCreate = $('btnModeCreate');
            const btnAssign = $('btnModeAssign');
            const assignBlock = $('assignModeBlock');
            const createBlock = $('createModeBlock');

            if (btnCreate && btnAssign) {
                btnCreate.classList.toggle('bg-gray-50', mode === 'create');
                btnAssign.classList.toggle('bg-gray-50', mode === 'assign');
            }

            if (assignBlock) assignBlock.classList.toggle('hidden', mode !== 'assign');
            if (createBlock) createBlock.classList.toggle('hidden', mode !== 'create');

            $('statusBlock').classList.toggle('hidden', mode === 'create');
            $('rolesBlock').classList.toggle('hidden', mode === 'create');

            if (mode === 'create') {
                editingUserId = null;
                $('modalTitle').textContent = 'Add User';

                $('findUserQ').value = '';
                $('findUserResults').innerHTML = '';
                $('findUserResults').classList.add('hidden');
                $('findUserQ').disabled = true;

                $('editFullName').readOnly = false;
                $('editUsername').readOnly = false;

                $('editStatus').value = 'active';
                clearRoleSelections();

                $('editPassword').value = '';
                $('editPassword2').value = '';
            }

            if (mode === 'assign') {
                editingUserId = null;
                $('modalTitle').textContent = 'Assign Roles';
                $('findUserQ').disabled = false;
                $('findUserQ').value = '';
                $('findUserResults').innerHTML = '';
                $('findUserResults').classList.add('hidden');

                $('editFullName').value = '';
                $('editUsername').value = '';
                $('editFullName').readOnly = true;
                $('editUsername').readOnly = true;

                $('editPassword').value = '';
                $('editPassword2').value = '';

                clearRoleSelections();
            }
        }

        function openModal(mode, user) {
            $('userModal').classList.remove('hidden');
            $('userModal').classList.add('flex');
            showModalError('');

            $('findUserQ').value = '';
            $('findUserResults').innerHTML = '';
            $('findUserResults').classList.add('hidden');

            $('editPassword').value = '';
            $('editPassword2').value = '';

            if (mode === 'edit' && user) {
                editingUserId = user.id;
                $('modalTitle').textContent = 'Edit User';
                $('editFullName').value = user.full_name || '';
                $('editUsername').value = user.username || '';
                $('editStatus').value = (user.status || 'active').toLowerCase();

                $('editFullName').readOnly = false;
                $('editUsername').readOnly = false;
                $('findUserQ').disabled = true;

                $('modeToggle').classList.add('hidden');
                $('assignModeBlock').classList.add('hidden');
                $('createModeBlock').classList.add('hidden');
                modalMode = 'edit';
            } else {
                editingUserId = null;
                $('modalTitle').textContent = 'Add User';
                $('editFullName').value = '';
                $('editUsername').value = '';
                $('editStatus').value = 'active';

                $('editFullName').readOnly = false;
                $('editUsername').readOnly = false;

                $('modeToggle').classList.remove('hidden');
                setModalMode('create');
            }

            const selected = new Set();
            if (user && Array.isArray(user.roles)) {
                user.roles.forEach(r => {
                    if (!r || !r.module || !r.role) return;
                    selected.add(String(r.module).toUpperCase() + '::' + String(r.role));
                });
            }

            const grid = $('rolesGrid');
            grid.innerHTML = '';

            Object.keys(MODULE_ROLES).forEach(module => {
                const roles = MODULE_ROLES[module] || [];
                const box = document.createElement('div');
                box.className = 'border border-gray-200 rounded-lg p-3 bg-white';

                const title = document.createElement('div');
                title.className = 'text-sm font-semibold text-gray-900 mb-2';
                title.textContent = module;
                box.appendChild(title);

                roles.forEach(role => {
                    const key = module + '::' + role;
                    const row = document.createElement('label');
                    row.className = 'flex items-center gap-2 text-sm text-gray-700 py-1 cursor-pointer';
                    row.innerHTML = `
                        <input type="checkbox" class="roleCheck" data-module="${escapeHtml(module)}" data-role="${escapeHtml(role)}" ${selected.has(key) ? 'checked' : ''}>
                        <span>${escapeHtml(role)}</span>
                    `;
                    box.appendChild(row);
                });

                grid.appendChild(box);
            });
        }

        function closeModal() {
            $('userModal').classList.add('hidden');
            $('userModal').classList.remove('flex');
            editingUserId = null;
            modalMode = 'create';

            $('findUserQ').value = '';
            $('findUserResults').innerHTML = '';
            $('findUserResults').classList.add('hidden');
            $('findUserQ').disabled = false;
            $('editFullName').readOnly = false;
            $('editUsername').readOnly = false;

            $('editPassword').value = '';
            $('editPassword2').value = '';
            $('modeToggle').classList.remove('hidden');
        }

        async function searchUnassignedUsers(q) {
            const resultsEl = $('findUserResults');
            if (!resultsEl) return;

            const needle = (q || '').toString().trim();
            if (!needle) {
                resultsEl.classList.add('hidden');
                resultsEl.innerHTML = '';
                return;
            }

            resultsEl.classList.remove('hidden');
            resultsEl.innerHTML = '<div class="px-3 py-2 text-sm text-gray-600 bg-white">Searching...</div>';

            const url = API_BASE_URL + '/users/list.php?unassigned=1&q=' + encodeURIComponent(needle);
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);

            if (!res.ok || !json || !json.ok) {
                resultsEl.innerHTML = '<div class="px-3 py-2 text-sm text-gray-600 bg-white">No results</div>';
                return;
            }

            const rows = Array.isArray(json.users) ? json.users.slice(0, 10) : [];
            if (rows.length === 0) {
                resultsEl.innerHTML = '<div class="px-3 py-2 text-sm text-gray-600 bg-white">No results</div>';
                return;
            }

            resultsEl.innerHTML = rows.map(u => {
                const name = escapeHtml(u.full_name || '');
                const email = escapeHtml(u.username || '');
                return `
                    <button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50" data-id="${Number(u.id)}" data-name="${name}" data-email="${email}">
                        <div class="text-sm font-semibold text-gray-900">${email}</div>
                        <div class="text-xs text-gray-500">${name}</div>
                    </button>
                `;
            }).join('');

            resultsEl.querySelectorAll('button[data-id]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = parseInt(btn.getAttribute('data-id') || '0', 10);
                    const name = btn.getAttribute('data-name') || '';
                    const email = btn.getAttribute('data-email') || '';
                    if (id > 0) {
                        setModalMode('assign');
                        editingUserId = id;
                        $('modalTitle').textContent = 'Assign Roles';
                        $('editFullName').value = name;
                        $('editUsername').value = email;
                        $('editFullName').readOnly = true;
                        $('editUsername').readOnly = true;
                        resultsEl.classList.add('hidden');
                        resultsEl.innerHTML = '';
                    }
                });
            });
        }

        function rolesToText(roles) {
            if (!Array.isArray(roles) || roles.length === 0) return '';
            return roles.map(r => `${r.module}: ${r.role}`).join(', ');
        }

        function renderUsers(users) {
            const tbody = $('usersTbody');
            tbody.innerHTML = '';

            if (!Array.isArray(users) || users.length === 0) {
                setEmpty(true);
                return;
            }

            setEmpty(false);

            users.forEach(u => {
                const tr = document.createElement('tr');

                const isAdminUser = Array.isArray(u.roles) && u.roles.some(r => {
                    const m = (r && r.module) ? String(r.module).toUpperCase() : '';
                    return m === 'ADMIN';
                });
                const canManageRow = !isAdminUser;

                const status = (u.status || '').toLowerCase();
                const statusBadge = status === 'inactive'
                    ? '<span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-gray-100 text-gray-700">Inactive</span>'
                    : '<span class="inline-flex items-center px-2 py-1 rounded-md text-xs bg-emerald-100 text-emerald-800">Active</span>';

                const actionsHtml = canManageRow ? `
                        <button class="btnEdit px-3 py-1.5 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700" data-id="${u.id}">
                            <i class="fas fa-pen mr-1"></i>Edit
                        </button>
                        <button class="btnDelete px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white ml-2" data-id="${u.id}">
                            <i class="fas fa-trash mr-1"></i>Delete
                        </button>
                ` : '';

                tr.innerHTML = `
                    <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(u.full_name || '')}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(u.username || '')}</td>
                    <td class="px-4 py-3 text-sm">${statusBadge}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(rolesToText(u.roles))}</td>
                    <td class="px-4 py-3 text-right text-sm">
                        ${actionsHtml}
                    </td>
                `;

                tbody.appendChild(tr);
            });
        }

        async function ensureTables() {
            try {
                await fetch(API_BASE_URL + '/users/install.php', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' }
                });
            } catch (e) {
            }
        }

        function buildListUrl() {
            const q = $('filterQ').value.trim();
            const module = $('filterModule').value.trim();
            const status = $('filterStatus').value.trim();

            const params = new URLSearchParams();
            if (q) params.set('q', q);
            if (module) params.set('module', module);
            if (status) params.set('status', status);
            const qs = params.toString();
            return API_BASE_URL + '/users/list.php' + (qs ? ('?' + qs) : '');
        }

        async function loadUsers() {
            setLoading(true);
            setEmpty(false);

            try {
                const url = buildListUrl();
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {
                    usersCache = [];
                    renderUsers([]);
                    setLoading(false);
                    return;
                }

                usersCache = Array.isArray(json.users) ? json.users : [];
                renderUsers(usersCache);
            } catch (e) {
                usersCache = [];
                renderUsers([]);
            } finally {
                setLoading(false);
            }
        }

        function getSelectedRoles() {
            const checks = Array.from(document.querySelectorAll('.roleCheck'));
            const roles = [];
            checks.forEach(chk => {
                if (!chk.checked) return;
                const module = String(chk.getAttribute('data-module') || '').toUpperCase();
                const role = String(chk.getAttribute('data-role') || '');
                if (!module || !role) return;
                roles.push({ module, role });
            });
            return roles;
        }

        async function saveUser() {
            showModalError('');

            const isEdit = (editingUserId !== null);

            if (!isEdit && modalMode === 'assign') {
                showModalError('Select a registered user to assign roles, or switch to Create Account.');
                return;
            }

            const payload = {
                full_name: $('editFullName').value.trim(),
                username: $('editUsername').value.trim(),
            };

            if (!payload.full_name || !payload.username) {
                showModalError('Full Name and Username are required.');
                return;
            }

            if (isEdit) payload.user_id = editingUserId;

            if (!isEdit && modalMode === 'create') {
                const p1 = $('editPassword').value;
                const p2 = $('editPassword2').value;
                if (!p1) {
                    showModalError('Password is required when creating a new account.');
                    return;
                }
                if (p1.length < 6) {
                    showModalError('Password must be at least 6 characters.');
                    return;
                }
                if (p1 !== p2) {
                    showModalError('Passwords do not match.');
                    return;
                }
                payload.password = p1;
            } else {
                payload.status = $('editStatus').value.trim();
                payload.roles = getSelectedRoles();
            }

            const endpoint = isEdit ? API_BASE_URL + '/users/update.php' : API_BASE_URL + '/users/create.php';

            try {
                const res = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify(payload)
                });
                const json = await res.json().catch(() => null);

                if (!res.ok || !json || !json.ok) {
                    const msg = (json && json.error) ? String(json.error) : 'Unable to save user.';
                    showModalError(msg);
                    return;
                }

                closeModal();
                await loadUsers();
            } catch (e) {
                showModalError('Unable to save user.');
            }
        }

        async function deleteUser(userId) {
            if (!confirm('Delete this user?')) return;

            try {
                const res = await fetch(API_BASE_URL + '/users/delete.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({ user_id: userId })
                });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    alert((json && json.error) ? String(json.error) : 'Unable to delete user.');
                    return;
                }
                await loadUsers();
            } catch (e) {
                alert('Unable to delete user.');
            }
        }

        function attachEvents() {
            $('btnAddUser').addEventListener('click', () => openModal('add'));
            $('btnCloseModal').addEventListener('click', closeModal);
            $('btnCancel').addEventListener('click', closeModal);
            $('btnSave').addEventListener('click', saveUser);
            $('btnRefresh').addEventListener('click', loadUsers);

            $('btnModeCreate').addEventListener('click', () => setModalMode('create'));
            $('btnModeAssign').addEventListener('click', () => setModalMode('assign'));

            const btnSeed = $('btnSeed');
            if (btnSeed) {
                btnSeed.addEventListener('click', async () => {
                    if (!confirm('Seed test accounts? This will (re)set their password to testacc123.')) return;
                    try {
                        const res = await fetch(API_BASE_URL + '/admin/seed_test_accounts.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({})
                        });
                        const json = await res.json().catch(() => null);
                        if (!res.ok || !json || !json.ok) {
                            alert((json && json.error) ? String(json.error) : 'Unable to seed accounts.');
                            return;
                        }
                        await loadUsers();
                    } catch (e) {
                        alert('Unable to seed accounts.');
                    }
                });
            }

            const scheduleSearch = (() => {
                let t = null;
                return () => {
                    if (t) clearTimeout(t);
                    t = setTimeout(() => searchUnassignedUsers($('findUserQ').value), 250);
                };
            })();
            $('findUserQ').addEventListener('input', scheduleSearch);

            $('userModal').addEventListener('click', (e) => {
                if (e.target === $('userModal')) closeModal();
            });

            const scheduleReload = (() => {
                let t = null;
                return () => {
                    if (t) clearTimeout(t);
                    t = setTimeout(loadUsers, 250);
                };
            })();

            $('filterQ').addEventListener('input', scheduleReload);
            $('filterModule').addEventListener('change', loadUsers);
            $('filterStatus').addEventListener('change', loadUsers);

            $('usersTbody').addEventListener('click', (e) => {
                const editBtn = e.target.closest ? e.target.closest('.btnEdit') : null;
                const delBtn = e.target.closest ? e.target.closest('.btnDelete') : null;

                if (editBtn) {
                    const id = parseInt(editBtn.getAttribute('data-id') || '0', 10);
                    const user = usersCache.find(u => Number(u.id) === id);
                    if (user) openModal('edit', user);
                    return;
                }

                if (delBtn) {
                    const id = parseInt(delBtn.getAttribute('data-id') || '0', 10);
                    if (id > 0) deleteUser(id);
                }
            });
        }

        document.addEventListener('DOMContentLoaded', async () => {
            await ensureTables();
            attachEvents();
            await loadUsers();
        });
    </script>
</body>
</html>
