<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR - Payroll</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media print {
            body * { visibility: hidden; }
            #payslipModal, #payslipModal * { visibility: visible; }
            #payslipModal { position: fixed; inset: 0; background: white !important; }
            #payslipCard { box-shadow: none !important; border: none !important; }
            #payslipActions { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50">
<?php
require_once __DIR__ . '/api/_db.php';
require_once __DIR__ . '/api/hr/_tables.php';

try {
    $pdo = db();
    ensure_hr_tables($pdo);

    $countEmployees = (int)$pdo->query('SELECT COUNT(*) FROM hr_employees')->fetchColumn();
    if ($countEmployees === 0) {
        $pdo->exec("INSERT INTO hr_departments (name, status) VALUES
            ('Human Resources','active'),
            ('Nursing','active'),
            ('Laboratory','active')");

        $deptHr = (int)$pdo->query("SELECT id FROM hr_departments WHERE name='Human Resources' LIMIT 1")->fetchColumn();
        $deptNursing = (int)$pdo->query("SELECT id FROM hr_departments WHERE name='Nursing' LIMIT 1")->fetchColumn();
        $deptLab = (int)$pdo->query("SELECT id FROM hr_departments WHERE name='Laboratory' LIMIT 1")->fetchColumn();

        $stmtPos = $pdo->prepare('INSERT INTO hr_positions (department_id, name, status) VALUES (:department_id, :name, :status)');
        $stmtPos->execute(['department_id' => $deptHr ?: null, 'name' => 'HR Assistant', 'status' => 'active']);
        $stmtPos->execute(['department_id' => $deptNursing ?: null, 'name' => 'Staff Nurse', 'status' => 'active']);
        $stmtPos->execute(['department_id' => $deptLab ?: null, 'name' => 'Med Tech', 'status' => 'active']);

        $posHr = (int)$pdo->query("SELECT id FROM hr_positions WHERE name='HR Assistant' LIMIT 1")->fetchColumn();
        $posNurse = (int)$pdo->query("SELECT id FROM hr_positions WHERE name='Staff Nurse' LIMIT 1")->fetchColumn();
        $posLab = (int)$pdo->query("SELECT id FROM hr_positions WHERE name='Med Tech' LIMIT 1")->fetchColumn();

        $stmtEmp = $pdo->prepare('INSERT INTO hr_employees (employee_code, full_name, phone, email, department_id, position_id, status) VALUES (:employee_code, :full_name, :phone, :email, :department_id, :position_id, :status)');
        $stmtEmp->execute([
            'employee_code' => 'EMP-0001',
            'full_name' => 'Maria Santos',
            'phone' => '0917-000-0001',
            'email' => 'maria.santos@example.com',
            'department_id' => $deptHr ?: null,
            'position_id' => $posHr ?: null,
            'status' => 'active',
        ]);
        $emp1 = (int)$pdo->lastInsertId();

        $stmtEmp->execute([
            'employee_code' => 'EMP-0002',
            'full_name' => 'John Dela Cruz',
            'phone' => '0917-000-0002',
            'email' => 'john.delacruz@example.com',
            'department_id' => $deptNursing ?: null,
            'position_id' => $posNurse ?: null,
            'status' => 'active',
        ]);
        $emp2 = (int)$pdo->lastInsertId();

        $stmtEmp->execute([
            'employee_code' => 'EMP-0003',
            'full_name' => 'Anne Reyes',
            'phone' => '0917-000-0003',
            'email' => 'anne.reyes@example.com',
            'department_id' => $deptLab ?: null,
            'position_id' => $posLab ?: null,
            'status' => 'active',
        ]);
        $emp3 = (int)$pdo->lastInsertId();

        $stmtSched = $pdo->prepare('INSERT INTO hr_schedules (employee_id, shift_date, start_time, end_time, notes) VALUES (:employee_id, :shift_date, :start_time, :end_time, :notes)');
        $today = new DateTimeImmutable('now');

        for ($i = 0; $i < 10; $i++) {
            $d = $today->sub(new DateInterval('P' . $i . 'D'))->format('Y-m-d');

            $stmtSched->execute([
                'employee_id' => $emp1,
                'shift_date' => $d,
                'start_time' => '08:00:00',
                'end_time' => '17:00:00',
                'notes' => $i % 4 === 0 ? 'On-site' : null,
            ]);

            if ($i % 2 === 0) {
                $stmtSched->execute([
                    'employee_id' => $emp2,
                    'shift_date' => $d,
                    'start_time' => '07:00:00',
                    'end_time' => '19:00:00',
                    'notes' => $i % 3 === 0 ? 'Overtime' : null,
                ]);
            }

            if ($i % 3 === 0) {
                $stmtSched->execute([
                    'employee_id' => $emp3,
                    'shift_date' => $d,
                    'start_time' => '09:00:00',
                    'end_time' => '18:00:00',
                    'notes' => null,
                ]);
            }
        }
    }
} catch (Throwable $e) {
}
?>

<div class="min-h-screen">
    <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

    <main class="ml-16 lg:ml-80 p-8">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Payroll</h1>
                <p class="text-sm text-gray-600 mt-1">Compute payroll from scheduled shifts and apply deductions.</p>
            </div>
            <div class="flex items-center gap-2">
                <button id="btnRefresh" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-rotate-right mr-2"></i>Refresh
                </button>
                <button id="btnPayslip" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                    <i class="fas fa-receipt mr-2"></i>Generate Payslip
                </button>
                <button id="btnPrint" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-print mr-2"></i>Print
                </button>
                <button id="btnSave" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">
                    <i class="fas fa-floppy-disk mr-2"></i>Save
                </button>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Employee</label>
                    <input id="employeeSearch" list="employeeList" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="Search employee...">
                    <datalist id="employeeList"></datalist>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Department</label>
                    <select id="department" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                        <option value="">All Departments</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">From</label>
                    <input id="from" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                </div>
                <div>
                    <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">To</label>
                    <input id="to" type="date" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200">
                </div>
            </div>

            <div class="mt-4 flex flex-wrap items-center gap-2">
                <button id="btnCalculate" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white shadow-sm">
                    <i class="fas fa-calculator mr-2"></i>Calculate
                </button>
                <button id="btnAddDeduction" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                    <i class="fas fa-minus-circle mr-2"></i>Add Deduction
                </button>
                <div class="px-3 py-2 rounded-lg bg-gray-50 border border-gray-100 text-sm text-gray-700">
                    <span class="text-xs text-gray-500 mr-2">Rate</span>
                    <span class="font-semibold" id="rateLabel">Auto</span>
                </div>
                <div class="ml-auto text-xs text-gray-500" id="status"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            <div class="lg:col-span-2 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <div class="text-sm font-semibold text-gray-900">Shifts in Period</div>
                    <div class="text-xs text-gray-500" id="shiftCount"></div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Date</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Time</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Employee</th>
                                <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Department</th>
                                <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Hours</th>
                                <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="shiftsTbody" class="divide-y divide-gray-100"></tbody>
                    </table>
                </div>
                <div id="shiftsEmpty" class="hidden p-8 text-center text-sm text-gray-600">No shifts found for the selected period.</div>
                <div id="shiftsLoading" class="hidden p-8 text-center text-sm text-gray-600">Loading...</div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                    <div class="text-sm font-semibold text-gray-900">Summary</div>
                </div>
                <div class="p-4 space-y-3">
                    <div class="grid grid-cols-2 gap-3">
                        <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="text-xs text-gray-500">Total Hours</div>
                            <div class="text-lg font-semibold text-gray-900" id="sumHours">0.00</div>
                        </div>
                        <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="text-xs text-gray-500">Gross Pay</div>
                            <div class="text-lg font-semibold text-gray-900" id="sumGross">0.00</div>
                        </div>
                        <div class="p-3 rounded-lg bg-gray-50 border border-gray-100">
                            <div class="text-xs text-gray-500">Total Deductions</div>
                            <div class="text-lg font-semibold text-gray-900" id="sumDed">0.00</div>
                        </div>
                        <div class="p-3 rounded-lg bg-emerald-50 border border-emerald-100">
                            <div class="text-xs text-emerald-700">Net Pay</div>
                            <div class="text-lg font-semibold text-emerald-900" id="sumNet">0.00</div>
                        </div>
                    </div>

                    <div class="mt-2">
                        <div class="flex items-center justify-between">
                            <div class="text-sm font-semibold text-gray-900">Deductions</div>
                        </div>
                        <div class="mt-2 overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-3 py-2">Type</th>
                                        <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-3 py-2">Amount</th>
                                        <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-3 py-2">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="deductionsTbody" class="divide-y divide-gray-100"></tbody>
                            </table>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">Sample deductions are editable and affect the net pay.</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="modal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
            <div class="w-full max-w-lg bg-white rounded-xl shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="font-semibold text-gray-900">Add Deduction</div>
                    <button id="btnCloseModal" class="text-gray-500 hover:text-gray-700"><i class="fas fa-times"></i></button>
                </div>
                <div class="p-6">
                    <div id="modalError" class="hidden mb-4 text-sm text-red-700 bg-red-50 border border-red-100 rounded-lg p-3"></div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Deduction Name</label>
                            <input id="dedName" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="e.g., Late, SSS, PhilHealth">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold tracking-wider text-gray-500 uppercase mb-1">Amount</label>
                            <input id="dedAmount" type="number" step="0.01" class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-200" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-end gap-2 bg-gray-50">
                    <button id="btnCancel" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">Cancel</button>
                    <button id="btnAdd" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow-sm">Add</button>
                </div>
            </div>
        </div>

        <div id="payslipModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center p-4 z-50">
            <div class="w-full max-w-3xl bg-white rounded-xl shadow-xl overflow-hidden">
                <div id="payslipActions" class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <div class="font-semibold text-gray-900">Payslip</div>
                    <div class="flex items-center gap-2">
                        <button id="btnPayslipPrint" class="px-4 py-2 rounded-lg bg-white border border-gray-200 hover:bg-gray-50 text-gray-700">
                            <i class="fas fa-print mr-2"></i>Print
                        </button>
                        <button id="btnPayslipClose" class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white shadow-sm">
                            <i class="fas fa-xmark mr-2"></i>Close
                        </button>
                    </div>
                </div>
                <div class="p-6" id="payslipBody">
                    <div id="payslipCard" class="border border-gray-100 rounded-lg p-6">
                        <div class="text-sm text-gray-600">Select an employee and click Generate Payslip.</div>
                    </div>
                </div>
            </div>
        </div>

    </main>
</div>

<script>
    let employeesCache = [];
    let departmentsCache = [];
    let schedulesCache = [];
    let selectedEmployeeId = '';
    let deductions = [
        { name: 'PhilHealth', amount: 150 },
        { name: 'Late / Absence', amount: 50 }
    ];

    function $(id) { return document.getElementById(id); }

    function phpMoney(n) {
        const v = Number(n || 0);
        return 'PHP ' + v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function escapeHtml(s) {
        return String(s ?? '')
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');
    }

    function toISODate(d) {
        return d.toISOString().slice(0, 10);
    }

    function setStatus(msg) {
        $('status').textContent = msg || '';
    }

    function setLoading(isLoading) {
        $('shiftsLoading').classList.toggle('hidden', !isLoading);
        $('shiftsTbody').classList.toggle('hidden', isLoading);
    }

    function setEmpty(isEmpty) {
        $('shiftsEmpty').classList.toggle('hidden', !isEmpty);
    }

    function parseTimeToMinutes(t) {
        const parts = String(t || '').split(':');
        if (parts.length < 2) return null;
        const h = parseInt(parts[0], 10);
        const m = parseInt(parts[1], 10);
        if (!Number.isFinite(h) || !Number.isFinite(m)) return null;
        return (h * 60) + m;
    }

    function calcHours(startTime, endTime) {
        const s = parseTimeToMinutes(startTime);
        const e = parseTimeToMinutes(endTime);
        if (s === null || e === null) return 0;
        let diff = e - s;
        if (diff < 0) diff += 24 * 60;
        return Math.max(0, diff / 60);
    }

    function getRateForScheduleRow(s) {
        const position = String(s && s.position_name ? s.position_name : '').toLowerCase();
        if (position.includes('nurse')) return 150;
        if (position.includes('med tech')) return 130;
        if (position.includes('hr')) return 120;
        return 120;
    }

    function updateRateLabel() {
        const emp = String(selectedEmployeeId || '').trim();
        if (!emp) {
            $('rateLabel').textContent = 'Auto';
            return;
        }
        const match = employeesCache.find(e => String(e.id) === String(emp));
        const labelPos = match && match.position_name ? String(match.position_name) : '';
        const rate = (schedulesCache.length > 0) ? getRateForScheduleRow(schedulesCache[0]) : 120;
        $('rateLabel').textContent = (labelPos ? (labelPos + ' - ') : '') + rate.toFixed(2) + '/hr';
    }

    function employeeLabel(e) {
        const parts = [e && e.full_name ? String(e.full_name) : ''];
        if (e && e.employee_code) parts.push('(' + String(e.employee_code) + ')');
        return parts.filter(Boolean).join(' ');
    }

    function fillEmployees() {
        const list = $('employeeList');
        list.innerHTML = '';
        employeesCache.forEach(e => {
            const opt = document.createElement('option');
            opt.value = employeeLabel(e);
            opt.setAttribute('data-id', String(e.id));
            list.appendChild(opt);
        });
    }

    function fillDepartments() {
        const select = $('department');
        const prev = select.value;
        select.innerHTML = '<option value="">All Departments</option>';
        departmentsCache.forEach(d => {
            const opt = document.createElement('option');
            opt.value = String(d.id);
            opt.textContent = String(d.name || '');
            select.appendChild(opt);
        });
        if (prev) select.value = prev;
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

    async function loadDepartments() {
        const res = await fetch('api/hr/departments/list.php?status=active', { headers: { 'Accept': 'application/json' } });
        const json = await res.json().catch(() => null);
        if (!res.ok || !json || !json.ok) return [];
        return Array.isArray(json.departments) ? json.departments : [];
    }

    function buildSchedulesUrl() {
        const params = new URLSearchParams();
        const emp = String(selectedEmployeeId || '').trim();
        const from = $('from').value.trim();
        const to = $('to').value.trim();

        if (emp) params.set('employee_id', emp);
        if (from) params.set('date_from', from);
        if (to) params.set('date_to', to);

        const qs = params.toString();
        return 'api/hr/schedules/list.php' + (qs ? ('?' + qs) : '');
    }

    async function loadSchedules() {
        setLoading(true);
        setEmpty(false);
        setStatus('Loading shifts...');

        try {
            const res = await fetch(buildSchedulesUrl(), { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                schedulesCache = [];
                renderShifts([]);
                setStatus('Unable to load shifts.');
                return;
            }

            schedulesCache = Array.isArray(json.schedules) ? json.schedules : [];

            const deptId = $('department').value.trim();
            if (deptId) {
                const dept = departmentsCache.find(d => String(d.id) === String(deptId));
                const deptName = dept && dept.name ? String(dept.name) : '';
                if (deptName) {
                    schedulesCache = schedulesCache.filter(s => String(s.department_name || '') === deptName);
                }
            }

            renderShifts(schedulesCache);
            setStatus('');
        } catch (e) {
            schedulesCache = [];
            renderShifts([]);
            setStatus('Unable to load shifts.');
        } finally {
            setLoading(false);
        }
    }

    function renderShifts(list) {
        const tbody = $('shiftsTbody');
        tbody.innerHTML = '';

        $('shiftCount').textContent = (Array.isArray(list) ? list.length : 0) + ' shift(s)';

        if (!Array.isArray(list) || list.length === 0) {
            setEmpty(true);
            return;
        }
        setEmpty(false);

        list.forEach(s => {
            const hours = calcHours(s.start_time, s.end_time);
            const rate = getRateForScheduleRow(s);
            const amount = hours * rate;
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-4 py-3 text-sm text-gray-900 font-medium">${escapeHtml(s.shift_date || '')}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml((s.start_time || '') + ' - ' + (s.end_time || ''))}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${escapeHtml(s.full_name || '')}</td>
                <td class="px-4 py-3 text-sm text-gray-700">${escapeHtml(s.department_name || '')}</td>
                <td class="px-4 py-3 text-sm text-gray-900 text-right">${hours.toFixed(2)}</td>
                <td class="px-4 py-3 text-sm text-gray-900 text-right">${amount.toFixed(2)}</td>
            `;
            tbody.appendChild(tr);
        });
    }

    function renderDeductions() {
        const tbody = $('deductionsTbody');
        tbody.innerHTML = '';

        deductions.forEach((d, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-3 py-2 text-sm text-gray-900">${escapeHtml(d.name)}</td>
                <td class="px-3 py-2 text-sm text-gray-900 text-right">${phpMoney(d.amount)}</td>
                <td class="px-3 py-2 text-right">
                    <button class="btnRemove px-3 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white" data-idx="${idx}">
                        <i class="fas fa-trash mr-1"></i>Remove
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        });
    }

    function calculate() {
        let totalHours = 0;
        let gross = 0;
        schedulesCache.forEach(s => {
            const h = calcHours(s.start_time, s.end_time);
            const r = getRateForScheduleRow(s);
            totalHours += h;
            gross += (h * r);
        });
        const totalDed = deductions.reduce((sum, d) => sum + (Number(d.amount || 0) || 0), 0);
        const net = gross - totalDed;

        $('sumHours').textContent = totalHours.toFixed(2);
        $('sumGross').textContent = phpMoney(gross);
        $('sumDed').textContent = phpMoney(totalDed);
        $('sumNet').textContent = phpMoney(net);

        setStatus('Calculated.');
        updateRateLabel();
    }

    function requireSelectedEmployee() {
        const emp = String(selectedEmployeeId || '').trim();
        if (!emp) {
            alert('Select an employee first to generate a payslip.');
            return null;
        }
        return emp;
    }

    function generatePayslip() {
        const empId = requireSelectedEmployee();
        if (!empId) return;

        const emp = employeesCache.find(e => String(e.id) === String(empId));
        const from = $('from').value.trim();
        const to = $('to').value.trim();

        const rows = schedulesCache.slice().reverse().map(s => {
            const hours = calcHours(s.start_time, s.end_time);
            const rate = getRateForScheduleRow(s);
            const amount = hours * rate;
            return { ...s, hours, rate, amount };
        });

        const totalHours = rows.reduce((sum, r) => sum + r.hours, 0);
        const gross = rows.reduce((sum, r) => sum + r.amount, 0);
        const totalDed = deductions.reduce((sum, d) => sum + (Number(d.amount || 0) || 0), 0);
        const net = gross - totalDed;

        const dayKeys = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];
        const dayTotals = { Mon: 0, Tue: 0, Wed: 0, Thu: 0, Fri: 0, Sat: 0, Sun: 0 };
        const dailySalary = 600;
        const dailyHours = 8;
        const hourlyFromDaily = dailySalary / dailyHours;
        let otAmount = 0;
        let otHours = 0;
        const otMultiplier = 1.25;

        const workedDates = new Set();

        rows.forEach(r => {
            const shiftDate = (r && r.shift_date) ? String(r.shift_date) : '';
            let d = null;
            try { d = shiftDate ? new Date(shiftDate + 'T00:00:00') : null; } catch (e0) { d = null; }
            if (!d || Number.isNaN(d.getTime())) return;

            const jsDay = d.getDay();
            const key = jsDay === 0 ? 'Sun' : (jsDay === 1 ? 'Mon' : (jsDay === 2 ? 'Tue' : (jsDay === 3 ? 'Wed' : (jsDay === 4 ? 'Thu' : (jsDay === 5 ? 'Fri' : 'Sat')))));

            if (shiftDate && !workedDates.has(shiftDate)) {
                workedDates.add(shiftDate);
                dayTotals[key] += dailySalary;
            }

            const extraHrs = Math.max(0, (Number(r.hours || 0) || 0) - dailyHours);
            otHours += extraHrs;
        });

        otAmount = otHours * hourlyFromDaily * otMultiplier;

        const allowAmount = 300;
        const holidayAmount = 0;
        const bonusAmount = 500;
        const basicSalary = dayKeys.reduce((sum, k) => sum + (Number(dayTotals[k]) || 0), 0);
        const grossSalary = basicSalary + allowAmount + holidayAmount + bonusAmount;

        const dedHtml = deductions.map(d => `
            <tr>
                <td style="padding:6px 8px; border-bottom:1px solid #eee;">${escapeHtml(d.name)}</td>
                <td style="padding:6px 8px; border-bottom:1px solid #eee; text-align:right;">${phpMoney(d.amount)}</td>
            </tr>
        `).join('');

        const salaryCells = dayKeys.map(k => {
            const val = Number(dayTotals[k] || 0);
            return `<td style="padding:4px 4px; border:1px solid #eee; text-align:center; font-weight:600; font-size:11px;">${phpMoney(val)}</td>`;
        }).join('');

        const card = `
            <div class="flex items-start justify-between gap-4">
                <div>
                    <div class="text-xl font-bold text-gray-900">Payslip</div>
                    <div class="text-sm text-gray-600 mt-1">Period: ${escapeHtml(from)} to ${escapeHtml(to)}</div>
                </div>
                <div class="text-xs text-gray-500">Generated: ${escapeHtml(new Date().toLocaleString())}</div>
            </div>
            <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="rounded-lg border border-gray-100 bg-gray-50 p-4">
                    <div class="font-semibold text-gray-900">${escapeHtml(emp && emp.full_name ? emp.full_name : '')}</div>
                    <div class="text-sm text-gray-600">Employee Code: ${escapeHtml(emp && emp.employee_code ? emp.employee_code : '')}</div>
                    <div class="text-sm text-gray-600">Department: ${escapeHtml(emp && emp.department_name ? emp.department_name : '')}</div>
                    <div class="text-sm text-gray-600">Position: ${escapeHtml(emp && emp.position_name ? emp.position_name : '')}</div>
                </div>
                <div class="rounded-lg border border-gray-100 bg-white p-4">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <div class="text-xs text-gray-500">Total Hours</div>
                            <div class="text-lg font-semibold text-gray-900">${totalHours.toFixed(2)}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">Gross Pay</div>
                            <div class="text-lg font-semibold text-gray-900">${phpMoney(gross)}</div>
                        </div>
                        <div>
                            <div class="text-xs text-gray-500">Total Deductions</div>
                            <div class="text-lg font-semibold text-gray-900">${phpMoney(totalDed)}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-emerald-700">Net Pay</div>
                            <div class="text-lg font-semibold text-emerald-900">${phpMoney(net)}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-sm font-semibold text-gray-900">Salary</div>
            <div class="mt-2 border border-gray-100 rounded-lg overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-0">
                    <div class="lg:col-span-2 p-4">
                        <div style="font-size:12px; font-weight:700; color:#111827; margin-bottom:6px;">SALARY</div>
                        <div style="overflow-x:hidden;">
                            <table style="width:100%; border-collapse:collapse; table-layout:fixed;">
                                <thead>
                                    <tr>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">MON</th>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">TUE</th>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">WED</th>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">THU</th>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">FRI</th>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">SAT</th>
                                        <th style="padding:4px 4px; border:1px solid #eee; background:#f9fafb; text-align:center; font-weight:700; font-size:11px;">SUN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        ${salaryCells}
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-top:10px; font-size:12px;">
                            <div style="font-weight:700; color:#111827;">BASIC SALARY</div>
                            <div style="font-weight:700; color:#111827;">${phpMoney(basicSalary)}</div>
                        </div>
                    </div>
                    <div class="p-4 border-t lg:border-t-0 lg:border-l border-gray-100 bg-white">
                        <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0;">
                            <div style="font-weight:700; color:#111827;">OT</div>
                            <div style="font-weight:700; color:#111827;">${phpMoney(otAmount)}</div>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0;">
                            <div style="font-weight:700; color:#111827;">ALLOW</div>
                            <div style="font-weight:700; color:#111827;">${phpMoney(allowAmount)}</div>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0;">
                            <div style="font-weight:700; color:#111827;">HOLIDAY</div>
                            <div style="font-weight:700; color:#111827;">${phpMoney(holidayAmount)}</div>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:12px; padding:4px 0;">
                            <div style="font-weight:700; color:#111827;">BONUS</div>
                            <div style="font-weight:700; color:#111827;">${phpMoney(bonusAmount)}</div>
                        </div>
                        <div style="display:flex; justify-content:space-between; font-size:12px; padding:8px 0; border-top:1px solid #eee; margin-top:6px;">
                            <div style="font-weight:800; color:#111827;">GROSS SALARY</div>
                            <div style="font-weight:800; color:#111827;">${phpMoney(grossSalary)}</div>
                        </div>
                        <div style="margin-top:8px; font-size:11px; color:#6b7280;">OT hours: ${otHours.toFixed(2)} (computed as hours beyond 8/day)</div>
                    </div>
                </div>
            </div>

            <div class="mt-6 text-sm font-semibold text-gray-900">Deductions</div>
            <div class="mt-2 overflow-x-auto border border-gray-100 rounded-lg">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="text-left text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Type</th>
                            <th class="text-right text-xs font-semibold tracking-wider text-gray-500 uppercase px-4 py-3">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        ${dedHtml || '<tr><td colspan="2" class="px-4 py-6 text-sm text-gray-600 text-center">No deductions.</td></tr>'}
                    </tbody>
                </table>
            </div>
        `;

        $('payslipCard').innerHTML = card;
        $('payslipModal').classList.remove('hidden');
        $('payslipModal').classList.add('flex');
    }

    function closePayslipModal() {
        $('payslipModal').classList.add('hidden');
        $('payslipModal').classList.remove('flex');
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

    function openModal() {
        $('modal').classList.remove('hidden');
        $('modal').classList.add('flex');
        showModalError('');
        $('dedName').value = '';
        $('dedAmount').value = '';
    }

    function closeModal() {
        $('modal').classList.add('hidden');
        $('modal').classList.remove('flex');
    }

    function addDeduction() {
        showModalError('');
        const name = $('dedName').value.trim();
        const amount = parseFloat($('dedAmount').value);

        if (!name) {
            showModalError('Deduction name is required.');
            return;
        }
        if (!Number.isFinite(amount) || amount < 0) {
            showModalError('Amount must be a valid number (0 or greater).');
            return;
        }

        deductions.push({ name, amount });
        renderDeductions();
        calculate();
        closeModal();
    }

    function attachEvents() {
        $('btnRefresh').addEventListener('click', async () => {
            employeesCache = await loadEmployees();
            departmentsCache = await loadDepartments();
            fillEmployees();
            fillDepartments();
            await loadSchedules();
            calculate();
        });

        $('btnCalculate').addEventListener('click', calculate);

        $('employeeSearch').addEventListener('input', async () => {
            const q = $('employeeSearch').value.trim();
            const match = employeesCache.find(e => employeeLabel(e) === q);
            selectedEmployeeId = match ? String(match.id) : '';
            await loadSchedules();
            calculate();
        });

        $('department').addEventListener('change', async () => {
            await loadSchedules();
            calculate();
        });
        $('from').addEventListener('change', async () => {
            await loadSchedules();
            calculate();
        });
        $('to').addEventListener('change', async () => {
            await loadSchedules();
            calculate();
        });

        $('btnAddDeduction').addEventListener('click', openModal);
        $('btnCloseModal').addEventListener('click', closeModal);
        $('btnCancel').addEventListener('click', closeModal);
        $('btnAdd').addEventListener('click', addDeduction);

        $('deductionsTbody').addEventListener('click', (e) => {
            const btn = e.target.closest ? e.target.closest('.btnRemove') : null;
            if (!btn) return;
            const idx = parseInt(btn.getAttribute('data-idx') || '-1', 10);
            if (idx < 0 || idx >= deductions.length) return;
            deductions.splice(idx, 1);
            renderDeductions();
            calculate();
        });

        $('modal').addEventListener('click', (e) => { if (e.target === $('modal')) closeModal(); });

        $('btnPayslip').addEventListener('click', generatePayslip);
        $('btnPayslipPrint').addEventListener('click', () => window.print());
        $('btnPayslipClose').addEventListener('click', closePayslipModal);
        $('payslipModal').addEventListener('click', (e) => { if (e.target === $('payslipModal')) closePayslipModal(); });
        $('btnPrint').addEventListener('click', () => window.print());
        $('btnSave').addEventListener('click', () => alert('Saved (demo). You can connect this to a payroll table later.'));
    }

    (async function init() {
        attachEvents();
        await ensureTables();

        const now = new Date();
        const from = new Date(now);
        from.setDate(from.getDate() - 7);
        $('from').value = toISODate(from);
        $('to').value = toISODate(now);

        employeesCache = await loadEmployees();
        departmentsCache = await loadDepartments();
        fillEmployees();
        fillDepartments();
        await loadSchedules();
        renderDeductions();
        calculate();
    })();
</script>

</body>
</html>
