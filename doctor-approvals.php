<?php
header('Location: doctor.php#lab-requests', true, 302);
exit;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Approvals - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <header class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Doctor Approvals</h1>
                </div>
                <div class="flex items-center space-x-3">
                    <input id="doctorName" type="text" class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Doctor name" value="Dr. Smith">
                    <button id="refreshBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Refresh</button>
                </div>
            </header>

            <div class="p-6 space-y-6">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Pending ER Lab Requests</h3>
                            <p class="text-sm text-gray-600 mt-1">Approve or reject before Laboratory can process.</p>
                        </div>
                        <div class="text-sm text-gray-600" id="pendingCount">0 pending</div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Request</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Triage</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tests</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Priority</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="approvalsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="detailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Request Details</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('detailsModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div id="detailsContent" class="p-6 max-h-[70vh] overflow-y-auto"></div>
            <div class="p-6 bg-gray-50 border-t flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('detailsModal')">Close</button>
                </div>
                <div class="flex items-center gap-3">
                    <button type="button" id="rejectBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Reject</button>
                    <button type="button" id="approveBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Reject Request</h3>
                <button type="button" class="text-gray-400 hover:text-gray-600" onclick="toggleModal('rejectModal')">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <label class="block text-sm font-medium text-gray-700">Reason</label>
                <input id="rejectReason" type="text" class="mt-2 w-full px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500" placeholder="Enter reason">
            </div>
            <div class="p-6 bg-gray-50 border-t flex justify-end gap-3">
                <button type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100" onclick="toggleModal('rejectModal')">Cancel</button>
                <button type="button" id="confirmRejectBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Confirm Reject</button>
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

        function priorityChip(p) {
            const v = (p ?? '').toString().toLowerCase();
            if (v === 'stat') return { cls: 'bg-red-100 text-red-800', label: 'STAT' };
            if (v === 'urgent') return { cls: 'bg-yellow-100 text-yellow-800', label: 'Urgent' };
            return { cls: 'bg-gray-100 text-gray-800', label: 'Routine' };
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

        let currentRequestId = null;

        async function loadPending() {
            const tbody = document.getElementById('approvalsTbody');
            const countEl = document.getElementById('pendingCount');
            if (!tbody) return;

            const res = await fetch('api/lab/list_requests.php?mode=doctor&status=pending_approval', { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '';
                if (countEl) countEl.textContent = '0 pending';
                return;
            }

            const rows = Array.isArray(json.requests) ? json.requests : [];
            if (countEl) countEl.textContent = String(rows.length) + ' pending';

            tbody.innerHTML = rows.map(r => {
                const chip = priorityChip(r.priority);
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
                            <button class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" onclick="openDetails(${Number(r.id)})">View</button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        async function openDetails(id) {
            currentRequestId = id;
            const content = document.getElementById('detailsContent');
            if (!content) return;

            const res = await fetch('api/lab/get_request.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                content.innerHTML = '<div class="text-sm text-red-600">Unable to load details.</div>';
                toggleModal('detailsModal');
                return;
            }

            const r = json.request || {};
            const items = Array.isArray(json.items) ? json.items : [];

            const vitals = (() => {
                try {
                    return r.vitals_json ? JSON.parse(r.vitals_json) : null;
                } catch (_) {
                    return null;
                }
            })();

            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Patient</div>
                        <div class="text-sm text-gray-700 mt-2">${escapeHtml(r.full_name || '')}</div>
                        <div class="text-xs text-gray-500">${escapeHtml(r.patient_code || '')}</div>
                        <div class="text-xs text-gray-500 mt-2">Request: ${escapeHtml(r.request_no || '')}</div>
                    </div>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-sm font-semibold text-gray-800">Clinical</div>
                        <div class="text-sm text-gray-700 mt-2">Triage: ${escapeHtml(triageLabel(r.triage_level))}</div>
                        <div class="text-sm text-gray-700">Complaint: ${escapeHtml(r.chief_complaint || '')}</div>
                        <div class="text-sm text-gray-700">Priority: ${escapeHtml((r.priority || '').toString().toUpperCase())}</div>
                        <div class="text-sm text-gray-700">Requested by: ${escapeHtml(r.requested_by || '')}</div>
                    </div>
                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
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
                    <div class="md:col-span-2 bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <div class="px-4 py-3 bg-gray-50 border-b text-sm font-semibold text-gray-800">Vitals</div>
                        <div class="p-4 grid grid-cols-2 md:grid-cols-5 gap-3">
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-500">BP</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(vitals?.bp || '')}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-500">HR</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(vitals?.hr || '')}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-500">RR</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(vitals?.rr || '')}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-500">Temp</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(vitals?.temp || '')}</div>
                            </div>
                            <div class="border border-gray-200 rounded-lg p-3">
                                <div class="text-xs text-gray-500">SpO2</div>
                                <div class="text-sm font-semibold text-gray-800">${escapeHtml(vitals?.spo2 || '')}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            toggleModal('detailsModal');
        }

        async function act(action, reason = '') {
            if (!currentRequestId) return;
            const doctorName = (document.getElementById('doctorName')?.value ?? '').toString().trim();
            if (!doctorName) {
                alert('Doctor name is required');
                return;
            }

            const body = {
                request_id: currentRequestId,
                doctor_name: doctorName,
                action,
                reason
            };

            const res = await fetch('api/lab/approve.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                alert((json && json.error) ? json.error : 'Action failed');
                return;
            }

            toggleModal('detailsModal');
            await loadPending();
        }

        document.getElementById('approveBtn').addEventListener('click', () => act('approve'));
        document.getElementById('rejectBtn').addEventListener('click', () => {
            document.getElementById('rejectReason').value = '';
            toggleModal('rejectModal');
        });
        document.getElementById('confirmRejectBtn').addEventListener('click', async () => {
            const reason = (document.getElementById('rejectReason')?.value ?? '').toString().trim();
            if (!reason) {
                alert('Reason is required');
                return;
            }
            toggleModal('rejectModal');
            await act('reject', reason);
        });
        document.getElementById('refreshBtn').addEventListener('click', loadPending);

        loadPending();
    </script>
</body>

</html>
