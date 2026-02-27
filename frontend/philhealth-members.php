<?php
require_once __DIR__ . '/auth.php';
auth_session_start();
?><!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PhilHealth Members</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <main class="flex-1 overflow-auto relative p-6">
            <div class="mx-auto w-full max-w-[1400px]">
                <div class="bg-white rounded-2xl shadow-lg mb-6">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex flex-col md:flex-row justify-between md:items-center gap-4">
                            <h3 class="text-lg font-semibold">PhilHealth Members</h3>
                            <div class="flex items-center space-x-2">
                                <input id="membersSearchInput" type="text" placeholder="Search PhilHealth Member..." class="w-full md:w-auto px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <button class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Filter" type="button">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Member</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIN</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="philhealthMembersTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="memberFormsModal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/50"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl overflow-hidden">
                <div class="p-5 border-b border-gray-200 flex items-center justify-between gap-3">
                    <div>
                        <div class="text-base font-semibold text-gray-900" id="memberFormsModalTitle">Member Forms</div>
                        <div class="text-xs text-gray-500" id="memberFormsModalSubtitle"></div>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="button" id="toggleEditMemberFormBtn" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Edit</button>
                        <button type="button" id="updateMemberFormBtn" class="px-3 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed" disabled>Update</button>
                        <button type="button" id="closeMemberFormsModalBtn" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Close</button>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <div class="flex items-center gap-2">
                            <button type="button" id="prevMemberFormBtn" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Previous</button>
                            <button type="button" id="nextMemberFormBtn" class="px-3 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Next</button>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-xs px-2 py-1 rounded-full border border-gray-200 text-gray-700" id="memberFormsMode">VIEW</div>
                            <div class="text-sm font-semibold text-gray-800" id="memberFormsStep">CF1</div>
                        </div>
                    </div>

                    <div id="memberFormsLoading" class="hidden">
                        <div class="flex items-center gap-3 text-gray-700">
                            <div class="w-8 h-8 rounded-full border-4 border-gray-200 border-t-blue-600 animate-spin"></div>
                            <div class="text-sm">Loading forms...</div>
                        </div>
                    </div>

                    <div class="relative border border-gray-200 rounded-xl overflow-hidden" style="height: 70vh;">
                        <iframe id="memberFormsFrame" class="w-full h-full" src="about:blank" title="PhilHealth Forms"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const tbody = document.getElementById('philhealthMembersTbody');
            const searchInput = document.getElementById('membersSearchInput');
            if (!tbody) return;

            const escapeHtml = (s) => {
                return (s ?? '').toString()
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            };

            const formatDate = (s) => {
                const d = new Date(s);
                if (!Number.isFinite(d.getTime())) return (s ?? '').toString();
                return d.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: '2-digit' });
            };

            let membersCache = [];

            function render(list) {
                tbody.innerHTML = (list || []).map(m => {
                    const name = escapeHtml(m.full_name || '');
                    const dob = m.dob ? ('DOB: ' + escapeHtml(m.dob)) : '';
                    const pin = escapeHtml(m.philhealth_pin || '');
                    const updated = escapeHtml(formatDate(m.updated_at || ''));
                    const patientId = (m.patient_id ?? '').toString();

                    const claimId = (m.latest_claim_id ?? '').toString();
                    const statusRaw = (m.latest_claim_status ?? '').toString().trim().toLowerCase();
                    const statusLabel = statusRaw ? statusRaw.charAt(0).toUpperCase() + statusRaw.slice(1) : 'Registered';
                    const statusClass = (function () {
                        if (statusRaw === 'approved') return 'bg-green-100 text-green-800';
                        if (statusRaw === 'pending') return 'bg-yellow-100 text-yellow-800';
                        if (statusRaw === 'rejected') return 'bg-red-100 text-red-800';
                        return 'bg-green-100 text-green-800';
                    })();

                    const showApprove = statusRaw === 'pending' && claimId !== '';
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${name}</div>
                                <div class="text-sm text-gray-500">${dob}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${pin}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">${escapeHtml(statusLabel)}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${updated}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="relative inline-block text-left">
                                    <button type="button" class="ph-actions-trigger inline-flex items-center justify-center w-9 h-9 rounded-full border border-gray-300 bg-white shadow-sm hover:bg-gray-50" aria-haspopup="true" aria-expanded="false" data-patient-id="${escapeHtml(patientId)}">
                                        <i class="fas fa-ellipsis-v text-gray-700"></i>
                                    </button>
                                    <div class="ph-actions-menu hidden absolute right-0 mt-2 w-64 origin-top-right rounded-xl bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none z-10 overflow-hidden">
                                        <div class="py-2">
                                            <button type="button" class="ph-action-item flex w-full items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" data-action="view-forms" data-patient-id="${escapeHtml(patientId)}">
                                                <i class="fas fa-pen w-4 text-gray-500"></i>
                                                <span>View/Edit CF1-CF4</span>
                                            </button>
                                            ${showApprove ? `<button type="button" class="ph-action-item flex w-full items-center gap-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" data-action="approve" data-claim-id="${escapeHtml(claimId)}"><i class=\"fas fa-check w-4 text-gray-500\"></i><span>Approve</span></button>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    `
                }).join('');
            }

            async function approveClaim(claimId) {
                const cid = Number(claimId);
                if (!Number.isFinite(cid) || cid <= 0) return;
                if (!confirm('Approve this pending claim?')) return;
                try {
                    const res = await fetch(API_BASE_URL + '/philhealth/approve_claim.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ claim_id: cid }),
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'Failed to approve claim');
                    }
                    await loadMembers();
                } catch (e) {
                    alert(e && e.message ? e.message : 'Failed to approve claim');
                }
            }

            function closeAllActionMenus() {
                const menus = document.querySelectorAll('.ph-actions-menu');
                menus.forEach(m => {
                    m.classList.add('hidden');
                    m.style.position = '';
                    m.style.top = '';
                    m.style.left = '';
                });
                const triggers = document.querySelectorAll('.ph-actions-trigger');
                triggers.forEach(t => t.setAttribute('aria-expanded', 'false'));
            }

            function positionActionMenu(trigger, menu) {
                if (!trigger || !menu) return;
                const rect = trigger.getBoundingClientRect();

                menu.style.position = 'fixed';
                menu.style.top = '0px';
                menu.style.left = '0px';

                const gutter = 8;
                const menuW = menu.offsetWidth || 260;
                const menuH = menu.offsetHeight || 0;

                let left = rect.right - menuW;
                if (left < gutter) left = gutter;
                if (left + menuW > window.innerWidth - gutter) left = window.innerWidth - gutter - menuW;

                let top = rect.bottom + 8;
                if (menuH > 0 && top + menuH > window.innerHeight - gutter) {
                    const above = rect.top - 8 - menuH;
                    if (above >= gutter) top = above;
                }
                if (top < gutter) top = gutter;
                if (menuH > 0 && top + menuH > window.innerHeight - gutter) top = window.innerHeight - gutter - menuH;

                menu.style.left = Math.round(left) + 'px';
                menu.style.top = Math.round(top) + 'px';
            }

            document.addEventListener('click', function (e) {
                const target = e.target;
                const trigger = target && target.closest ? target.closest('.ph-actions-trigger') : null;
                const item = target && target.closest ? target.closest('.ph-action-item') : null;

                if (trigger) {
                    const wrap = trigger.parentElement;
                    const menu = wrap ? wrap.querySelector('.ph-actions-menu') : null;
                    const open = menu && !menu.classList.contains('hidden');
                    closeAllActionMenus();
                    if (menu && !open) {
                        menu.classList.remove('hidden');
                        positionActionMenu(trigger, menu);
                        trigger.setAttribute('aria-expanded', 'true');
                    }
                    e.preventDefault();
                    return;
                }

                if (item) {
                    const action = (item.getAttribute('data-action') || '').toString();
                    if (action === 'view-forms') {
                        openMemberFormsModal(item.getAttribute('data-patient-id'));
                    } else if (action === 'approve') {
                        approveClaim(item.getAttribute('data-claim-id'));
                    }
                    closeAllActionMenus();
                    e.preventDefault();
                    return;
                }

                closeAllActionMenus();
            });

            const modal = document.getElementById('memberFormsModal');
            const modalTitle = document.getElementById('memberFormsModalTitle');
            const modalSubtitle = document.getElementById('memberFormsModalSubtitle');
            const modalStep = document.getElementById('memberFormsStep');
            const modalLoading = document.getElementById('memberFormsLoading');
            const modalFrame = document.getElementById('memberFormsFrame');
            const closeBtn = document.getElementById('closeMemberFormsModalBtn');
            const prevBtn = document.getElementById('prevMemberFormBtn');
            const nextBtn = document.getElementById('nextMemberFormBtn');
            const toggleEditBtn = document.getElementById('toggleEditMemberFormBtn');
            const updateBtn = document.getElementById('updateMemberFormBtn');
            const modeChip = document.getElementById('memberFormsMode');

            const steps = ['cf1', 'cf2', 'cf3', 'cf4'];
            let activeStepIndex = 0;
            let activeClaimJson = null;
            let activePatientId = null;
            let isEdit = false;

            function showModal() {
                if (!modal) return;
                modal.classList.remove('hidden');
            }

            function hideModal() {
                if (!modal) return;
                modal.classList.add('hidden');
            }

            function setLoading(on) {
                if (modalLoading) modalLoading.classList.toggle('hidden', !on);
                if (modalFrame) modalFrame.classList.toggle('hidden', !!on);
            }

            function updateNavButtons() {
                if (prevBtn) prevBtn.disabled = activeStepIndex <= 0;
                if (nextBtn) nextBtn.disabled = activeStepIndex >= steps.length - 1;
            }

            function updateModeUi() {
                if (modeChip) modeChip.textContent = isEdit ? 'EDIT' : 'VIEW';
                if (toggleEditBtn) toggleEditBtn.textContent = isEdit ? 'View' : 'Edit';
                if (updateBtn) updateBtn.disabled = !isEdit;
            }

            function renderActiveStep() {
                const stepKey = steps[activeStepIndex] || 'cf1';
                if (modalStep) modalStep.textContent = stepKey.toUpperCase();
                updateNavButtons();

                if (!activePatientId) return;
                const map = {
                    cf1: 'philhealth-cf1.php',
                    cf2: 'philhealth-cf2.php',
                    cf3: 'philhealth-cf3.php',
                    cf4: 'philhealth-cf4.php',
                };
                const page = map[stepKey] || 'philhealth-cf1.php';
                const url = page + '?patient_id=' + encodeURIComponent(String(activePatientId)) + '&mode=' + (isEdit ? 'edit' : 'view') + '&embed=1';
                if (modalFrame) modalFrame.src = url;
            }

            window.openMemberFormsModal = async function (patientId) {
                const pid = Number(patientId);
                if (!Number.isFinite(pid) || pid <= 0) return;

                activeStepIndex = 0;
                activeClaimJson = null;
                activePatientId = pid;
                isEdit = false;
                updateModeUi();
                if (modalTitle) modalTitle.textContent = 'Member Forms';
                if (modalSubtitle) modalSubtitle.textContent = 'Patient ID: ' + String(pid);
                if (modalFrame) modalFrame.src = 'about:blank';
                setLoading(true);
                showModal();

                try {
                    const res = await fetch(API_BASE_URL + '/philhealth/member_claim.php?patient_id=' + encodeURIComponent(String(pid)), {
                        headers: { 'Accept': 'application/json' },
                    });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'Failed to load member claim');
                    }
                    activeClaimJson = json;
                    const basic = (json.basic && typeof json.basic === 'object') ? json.basic : null;
                    if (modalTitle && basic && basic.patientName) modalTitle.textContent = (basic.patientName || '').toString();
                    if (modalSubtitle && basic && basic.philhealthId) modalSubtitle.textContent = 'PIN: ' + (basic.philhealthId || '').toString();

                    const safeWrite = (key, value) => {
                        try { sessionStorage.setItem(key, JSON.stringify(value ?? null)); } catch (e2) { }
                    };

                    safeWrite('philhealthPatientId', String(pid));

                    const forms = (json.forms && typeof json.forms === 'object') ? json.forms : {};
                    if (forms.cf1) safeWrite('philhealthCf1Draft', forms.cf1);
                    if (forms.cf2) safeWrite('philhealthCf2Draft', forms.cf2);
                    if (forms.cf3) safeWrite('philhealthCf3Draft', forms.cf3);
                    if (forms.cf4) safeWrite('philhealthCf4Draft', forms.cf4);

                    setLoading(false);
                    renderActiveStep();
                } catch (e) {
                    setLoading(false);
                    alert(e && e.message ? e.message : 'Failed to load member claim');
                    hideModal();
                }
            };

            if (toggleEditBtn) toggleEditBtn.addEventListener('click', function () {
                isEdit = !isEdit;
                updateModeUi();
                renderActiveStep();
            });

            if (updateBtn) updateBtn.addEventListener('click', function () {
                if (!isEdit) return;
                if (!modalFrame || !modalFrame.contentWindow) return;
                try {
                    modalFrame.contentWindow.postMessage({ type: 'PHILHEALTH_MEMBER_FORMS_SAVE' }, '*');
                } catch (e) {
                }
            });

            if (closeBtn) closeBtn.addEventListener('click', () => {
                if (modalFrame) modalFrame.src = 'about:blank';
                hideModal();
            });
            if (modal) {
                modal.addEventListener('click', function (e) {
                    const target = e.target;
                    if (target && target === modal) hideModal();
                    if (target && target.classList && target.classList.contains('bg-black/50')) hideModal();
                });
            }
            if (prevBtn) {
                prevBtn.addEventListener('click', function () {
                    if (activeStepIndex <= 0) return;
                    activeStepIndex -= 1;
                    renderActiveStep();
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', function () {
                    if (activeStepIndex >= steps.length - 1) return;
                    activeStepIndex += 1;
                    renderActiveStep();
                });
            }

            async function loadMembers() {
                try {
                    const res = await fetch(API_BASE_URL + '/philhealth/dashboard.php', { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (!res.ok || !json || !json.ok) {
                        throw new Error((json && json.error) ? json.error : 'Failed to load members');
                    }
                    const members = Array.isArray(json.members) ? json.members : [];
                    membersCache = members;
                    render(membersCache);
                } catch (e) {
                    tbody.innerHTML = '';
                    alert(e && e.message ? e.message : 'Failed to load members');
                }
            }

            function applyFilter() {
                const q = (searchInput && searchInput.value ? searchInput.value : '').toString().trim().toLowerCase();
                if (!q) {
                    render(membersCache);
                    return;
                }
                const filtered = membersCache.filter(m => {
                    const name = (m.full_name || '').toString().toLowerCase();
                    const pin = (m.philhealth_pin || '').toString().toLowerCase();
                    return name.includes(q) || pin.includes(q);
                });
                render(filtered);
            }

            if (searchInput) {
                searchInput.addEventListener('input', applyFilter);
            }

            loadMembers();
        })();
    </script>
</body>
</html>
