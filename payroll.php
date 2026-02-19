<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll Management - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <?php
    $sampleEmployees = [
        [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'department' => 'Medical Staff',
            'position' => 'Senior Doctor',
            'base_salary' => 12000,
            'ot_hours' => 12,
            'ot_pay' => 1500,
            'status' => 'Paid',
        ],
        [
            'name' => 'Maria Santos',
            'email' => 'maria.santos@example.com',
            'department' => 'Nursing',
            'position' => 'Staff Nurse',
            'base_salary' => 3800,
            'ot_hours' => 18,
            'ot_pay' => 650,
            'status' => 'Pending',
        ],
        [
            'name' => 'Anne Reyes',
            'email' => 'anne.reyes@example.com',
            'department' => 'Administration',
            'position' => 'HR Assistant',
            'base_salary' => 2600,
            'ot_hours' => 6,
            'ot_pay' => 120,
            'status' => 'Paid',
        ],
        [
            'name' => 'Paul Kim',
            'email' => 'paul.kim@example.com',
            'department' => 'Support Staff',
            'position' => 'Maintenance',
            'base_salary' => 1900,
            'ot_hours' => 10,
            'ot_pay' => 220,
            'status' => 'On Hold',
        ],
    ];

    $samplePayRuns = [
        [
            'period' => '2026-01-16 to 2026-01-31',
            'department' => 'All Departments',
            'employees' => 328,
            'gross' => 247580,
            'status' => 'Posted',
            'generated_by' => 'System Admin',
        ],
        [
            'period' => '2026-01-01 to 2026-01-15',
            'department' => 'Nursing',
            'employees' => 104,
            'gross' => 81240,
            'status' => 'Draft',
            'generated_by' => 'HR Manager',
        ],
        [
            'period' => '2025-12-16 to 2025-12-31',
            'department' => 'Medical Staff',
            'employees' => 68,
            'gross' => 96500,
            'status' => 'Paid',
            'generated_by' => 'Payroll Officer',
        ],
    ];

    $sampleApprovals = [
        [
            'type' => 'Overtime',
            'request' => 'OT 4 hours - Maria Santos',
            'department' => 'Nursing',
            'submitted' => '2026-02-01',
            'status' => 'Pending',
        ],
        [
            'type' => 'Adjustment',
            'request' => 'Manual time correction - Anne Reyes',
            'department' => 'Administration',
            'submitted' => '2026-01-30',
            'status' => 'Pending',
        ],
        [
            'type' => 'Payroll Posting',
            'request' => 'Post Payroll Run (2026-01-16 to 2026-01-31)',
            'department' => 'All Departments',
            'submitted' => '2026-01-31',
            'status' => 'Approved',
        ],
    ];

    $sampleReports = [
        [
            'name' => 'Payroll Summary',
            'description' => 'Monthly payroll overview',
            'last_generated' => '2026-01-31',
        ],
        [
            'name' => 'Tax Report',
            'description' => 'Tax deductions summary',
            'last_generated' => '2026-01-31',
        ],
        [
            'name' => 'Department Costs',
            'description' => 'Costs by department',
            'last_generated' => '2026-01-31',
        ],
        [
            'name' => 'Benefits Report',
            'description' => 'Employee benefits analysis',
            'last_generated' => '2026-01-15',
        ],
    ];

    function money_fmt($n) {
        return 'PHP ' . number_format((float)$n, 2);
    }

    function status_badge_class($status) {
        $s = strtolower(trim((string)$status));
        if ($s === 'paid' || $s === 'posted' || $s === 'approved') return 'bg-green-100 text-green-800';
        if ($s === 'pending' || $s === 'draft') return 'bg-yellow-100 text-yellow-800';
        if ($s === 'on hold' || $s === 'rejected') return 'bg-red-100 text-red-800';
        return 'bg-gray-100 text-gray-800';
    }
    ?>
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <!-- Sidebar -->
        <?php if (false): ?>
        <aside class="fixed inset-y-0 left-0 bg-white shadow-xl w-64">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="flex items-center justify-center h-20 border-b border-gray-200">
                    <img src="resources/logo.png" alt="Logo" class="h-10">
                    <span class="ml-3 text-xl font-bold text-gray-800">Hospital</span>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 p-4">
                    <ul class="space-y-1">
                        <li>
                            <a href="index.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-home w-6 text-center"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="patients.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-injured w-6 text-center"></i>
                                <span>Patients</span>
                            </a>
                        </li>
                        <li>
                            <a href="out-patient-department.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-hospital-user w-6 text-center"></i>
                                <span>OPD</span>
                            </a>
                        </li>
                        <li>
                            <a href="dialysis.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-sync-alt w-6 text-center"></i>
                                <span>Dialysis</span>
                            </a>
                        </li>
                        <li>
                            <a href="cashier.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-cash-register w-6 text-center"></i>
                                <span>Cashier</span>
                            </a>
                        </li>
                        <li>
                            <a href="pharmacy.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-pills w-6 text-center"></i>
                                <span>Pharmacy</span>
                            </a>
                        </li>
                        <li>
                            <a href="laboratory.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-flask w-6 text-center"></i>
                                <span>Laboratory</span>
                            </a>
                        </li>
                        <li>
                            <a href="inventory.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-boxes w-6 text-center"></i>
                                <span>Inventory</span>
                            </a>
                        </li>
                        <li>
                            <a href="kitchen.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-utensils w-6 text-center"></i>
                                <span>Kitchen</span>
                            </a>
                        </li>
                        <li>
                            <a href="payroll.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
                                <i class="fas fa-money-check-alt w-6 text-center"></i>
                                <span>Payroll</span>
                            </a>
                        </li>
                        <li>
                            <a href="chat.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-comments w-6 text-center"></i>
                                <span>Chat Messages</span>
                            </a>
                        </li>
                        <li>
                            <a href="employees.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-user-friends w-6 text-center"></i>
                                <span>Employees</span>
                            </a>
                        </li>
                        <li>
                            <a href="philhealth-claims.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
                                <i class="fas fa-file-medical-alt w-6 text-center"></i>
                                <span>PhilHealth Claims</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- User Profile -->
                <div class="mt-auto p-4 border-t border-gray-200">
                    <div class="flex items-center gap-x-4">
                        <img src="resources/doctor.jpg" alt="User" class="w-10 h-10 rounded-full object-cover">
                        <div>
                            <p class="font-semibold text-gray-800"><?php echo htmlspecialchars((string)($authUser['full_name'] ?? ($authUser['username'] ?? 'User')), ENT_QUOTES); ?></p>
                            <p class="text-sm text-gray-500">User</p>
                        </div>
                        <button class="ml-auto text-gray-500 hover:text-red-600" title="Logout">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </aside>

        <?php endif; ?>

        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 p-8">
            <div id="dashboard"></div>
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Payroll Management</h1>
                <div class="flex space-x-4">
                    <button onclick="toggleModal('generatePayrollModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Generate Payroll
                    </button>
                    <button onclick="toggleModal('addEmployeeModal')" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center">
                        <i class="fas fa-user-plus mr-2"></i>
                        Add Employee
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Payroll -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-peso-sign"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Total Payroll</h2>
                                <p class="text-2xl font-semibold text-gray-800">PHP 247,580</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-arrow-up"></i> 3.2%
                                </span>
                                <span class="ml-2 text-sm text-gray-600">vs last month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Employees -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Total Employees</h2>
                                <p class="text-2xl font-semibold text-gray-800">328</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-plus"></i> 5
                                </span>
                                <span class="ml-2 text-sm text-gray-600">new this month</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Overtime Hours -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Overtime Hours</h2>
                                <p class="text-2xl font-semibold text-gray-800">842</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-red-600 text-sm font-medium">
                                    <i class="fas fa-arrow-up"></i> 12%
                                </span>
                                <span class="ml-2 text-sm text-gray-600">increase</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Approvals -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Pending Approvals</h2>
                                <p class="text-2xl font-semibold text-gray-800">15</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-orange-600 text-sm font-medium">
                                    <i class="fas fa-exclamation-circle"></i>
                                </span>
                                <span class="ml-2 text-sm text-gray-600">requires attention</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 mb-6">
                <button onclick="toggleModal('taxCalculatorModal')" class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-indigo-100 text-indigo-600 mr-4">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Tax Calculator</h4>
                        <p class="text-xs text-gray-500">Calculate employee taxes</p>
                    </div>
                </button>
                
                <button onclick="toggleModal('benefitsModal')" class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-emerald-100 text-emerald-600 mr-4">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Benefits</h4>
                        <p class="text-xs text-gray-500">Manage employee benefits</p>
                    </div>
                </button>

                <button onclick="toggleModal('attendanceModal')" class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-amber-100 text-amber-600 mr-4">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Attendance</h4>
                        <p class="text-xs text-gray-500">Track attendance & leaves</p>
                    </div>
                </button>

                <button onclick="toggleModal('reportsModal')" class="flex items-center p-4 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-rose-100 text-rose-600 mr-4">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-900">Reports</h4>
                        <p class="text-xs text-gray-500">Generate payroll reports</p>
                    </div>
                </button>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Salary Distribution -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Salary Distribution</h3>
                    <canvas id="salaryDistributionChart" class="w-full h-64"></canvas>
                </div>

                <!-- Department Cost Analysis -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Department Costs</h3>
                    <canvas id="departmentCostChart" class="w-full h-64"></canvas>
                </div>

                <!-- Overtime Trends -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Overtime Trends</h3>
                    <canvas id="overtimeTrendChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <div id="employees" class="pt-2"></div>
            <!-- Employee Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Employee Payroll</h3>
                        <div class="flex space-x-4">
                            <div class="relative">
                                <input type="text" placeholder="Search employees..." 
                                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option>All Departments</option>
                                <option>Medical Staff</option>
                                <option>Nursing</option>
                                <option>Administration</option>
                                <option>Support Staff</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Position</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Base Salary</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overtime</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($sampleEmployees as $empIndex => $emp): ?>
                                <?php
                                $total = (float)$emp['base_salary'] + (float)$emp['ot_pay'];
                                $dep = (string)$emp['department'];
                                $depBadge = 'bg-blue-100 text-blue-800';
                                if (strtolower($dep) === 'nursing') $depBadge = 'bg-green-100 text-green-800';
                                if (strtolower($dep) === 'administration') $depBadge = 'bg-yellow-100 text-yellow-800';
                                if (strtolower($dep) === 'support staff') $depBadge = 'bg-purple-100 text-purple-800';
                                ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=<?php echo urlencode((string)$emp['name']); ?>" alt="">
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars((string)$emp['name'], ENT_QUOTES); ?></div>
                                                <div class="text-sm text-gray-500"><?php echo htmlspecialchars((string)$emp['email'], ENT_QUOTES); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo $depBadge; ?>"><?php echo htmlspecialchars((string)$emp['department'], ENT_QUOTES); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo htmlspecialchars((string)$emp['position'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo money_fmt($emp['base_salary']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900"><?php echo money_fmt($emp['ot_pay']); ?></div>
                                        <div class="text-xs text-gray-500"><?php echo htmlspecialchars((string)$emp['ot_hours'], ENT_QUOTES); ?> hours</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo money_fmt($total); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo status_badge_class($emp['status']); ?>"><?php echo htmlspecialchars((string)$emp['status'], ENT_QUOTES); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button type="button" onclick="return openPayrollSummary(event, <?php echo (int)$empIndex; ?>)" class="p-1 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-100 rounded" title="View">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" onclick="openPayrollSummary(event, <?php echo (int)$empIndex; ?>); printPayrollSummary(); return false;" class="p-1 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-100 rounded" title="Print">
                                                <i class="fas fa-print"></i>
                                            </button>
                                            <button type="button" class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing 1 to 10 of 328 entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">Previous</button>
                            <button class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700">1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">2</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">3</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="payroll-run" class="pt-8"></div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Payroll Runs</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pay Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Employees</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gross</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Generated By</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($samplePayRuns as $run): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars((string)$run['period'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$run['department'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm text-gray-900"><?php echo number_format((int)$run['employees']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-medium text-gray-900"><?php echo money_fmt($run['gross']); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo status_badge_class($run['status']); ?>"><?php echo htmlspecialchars((string)$run['status'], ENT_QUOTES); ?></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$run['generated_by'], ENT_QUOTES); ?></div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="approvals" class="pt-8"></div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Approvals</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($sampleApprovals as $a): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars((string)$a['type'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$a['request'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$a['department'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$a['submitted'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs rounded-full <?php echo status_badge_class($a['status']); ?>"><?php echo htmlspecialchars((string)$a['status'], ENT_QUOTES); ?></span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="taxes" class="pt-8"></div>
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Taxes & Contributions (Sample)</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">Withholding Tax</div>
                        <div class="text-lg font-semibold text-gray-900">PHP 18,420.00</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">PhilHealth</div>
                        <div class="text-lg font-semibold text-gray-900">PHP 6,820.00</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">SSS</div>
                        <div class="text-lg font-semibold text-gray-900">PHP 9,300.00</div>
                    </div>
                    <div class="p-4 bg-emerald-50 rounded-lg border border-emerald-100">
                        <div class="text-xs text-emerald-700">Total Contributions</div>
                        <div class="text-lg font-semibold text-emerald-900">PHP 34,540.00</div>
                    </div>
                </div>
            </div>

            <div id="reports" class="pt-8"></div>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mt-6">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Reports</h3>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Generated</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($sampleReports as $r): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars((string)$r['name'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$r['description'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-700"><?php echo htmlspecialchars((string)$r['last_generated'], ENT_QUOTES); ?></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">Generate</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div id="settings" class="pt-8"></div>
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Settings (Sample)</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">Pay Period</div>
                        <div class="text-sm font-semibold text-gray-900">Semi-monthly (1-15, 16-31)</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">Default OT Rate</div>
                        <div class="text-sm font-semibold text-gray-900">1.25x</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">Approval Mode</div>
                        <div class="text-sm font-semibold text-gray-900">Manager Approval Required</div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Generate Payroll Modal -->
    <div id="generatePayrollModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Generate Payroll</h3>
                    <button onclick="toggleModal('generatePayrollModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="generatePayrollForm" class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Pay Period</label>
                        <div class="grid grid-cols-2 gap-4 mt-1">
                            <input type="date" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <input type="date" class="px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <select class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>All Departments</option>
                            <option>Medical Staff</option>
                            <option>Nursing</option>
                            <option>Administration</option>
                            <option>Support Staff</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>Direct Deposit</option>
                            <option>Check</option>
                            <option>Cash</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Additional Notes</label>
                        <textarea class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500" rows="3"></textarea>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal('generatePayrollModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Generate Payroll
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="payrollSummaryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Employee Payroll Summary</h3>
                    <button onclick="toggleModal('payrollSummaryModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div id="payrollSummaryContent" class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">Employee</div>
                        <div id="ps_name" class="text-lg font-semibold text-gray-900">-</div>
                        <div id="ps_email" class="text-sm text-gray-600">-</div>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="text-xs text-gray-500">Department / Position</div>
                        <div id="ps_department" class="text-lg font-semibold text-gray-900">-</div>
                        <div id="ps_position" class="text-sm text-gray-600">-</div>
                    </div>
                </div>

                <div class="mt-3 flex flex-wrap items-center justify-between gap-2">
                    <div class="text-sm text-gray-600">
                        Pay Period: <span class="font-semibold text-gray-900">2026-01-16 to 2026-01-31</span>
                    </div>
                    <div>
                        <span id="ps_status" class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">-</span>
                    </div>
                </div>

                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">Base Salary</td>
                                <td id="ps_base_salary" class="px-4 py-2 text-sm text-gray-900 text-right">-</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">Overtime (<span id="ps_ot_hours">-</span> hours)</td>
                                <td id="ps_ot_pay" class="px-4 py-2 text-sm text-gray-900 text-right">-</td>
                            </tr>
                            <tr class="bg-gray-50">
                                <td class="px-4 py-2 text-sm font-semibold text-gray-900">Total</td>
                                <td id="ps_total" class="px-4 py-2 text-sm font-semibold text-gray-900 text-right">-</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" onclick="printPayrollSummary()" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                        Print
                    </button>
                    <button type="button" onclick="toggleModal('payrollSummaryModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Employee Modal -->
    <div id="addEmployeeModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Add New Employee</h3>
                    <button onclick="toggleModal('addEmployeeModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="addEmployeeForm" class="p-6">
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Department</label>
                            <select class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                                <option>Medical Staff</option>
                                <option>Nursing</option>
                                <option>Administration</option>
                                <option>Support Staff</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Position</label>
                            <input type="text" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Base Salary</label>
                            <input type="number" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal('addEmployeeModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Add Employee
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        const payrollEmployees = <?php echo json_encode($sampleEmployees, JSON_UNESCAPED_UNICODE); ?>;

        function phpMoney(n) {
            const v = Number(n || 0);
            return 'PHP ' + v.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function openPayrollSummary(e, empIndex) {
            if (e && typeof e.preventDefault === 'function') e.preventDefault();
            const emp = payrollEmployees?.[empIndex];
            if (!emp) return;

            const total = Number(emp.base_salary || 0) + Number(emp.ot_pay || 0);

            document.getElementById('ps_name').textContent = emp.name || '-';
            document.getElementById('ps_email').textContent = emp.email || '-';
            document.getElementById('ps_department').textContent = emp.department || '-';
            document.getElementById('ps_position').textContent = emp.position || '-';
            document.getElementById('ps_base_salary').textContent = phpMoney(emp.base_salary);
            document.getElementById('ps_ot_hours').textContent = String(emp.ot_hours ?? '-');
            document.getElementById('ps_ot_pay').textContent = phpMoney(emp.ot_pay);
            document.getElementById('ps_total').textContent = phpMoney(total);

            const statusEl = document.getElementById('ps_status');
            if (statusEl) {
                const raw = String(emp.status || '').trim();
                statusEl.textContent = raw || '-';
                const s = raw.toLowerCase();
                statusEl.className = 'px-2 py-1 text-xs rounded-full ' +
                    (s === 'paid' || s === 'posted' || s === 'approved'
                        ? 'bg-green-100 text-green-800'
                        : (s === 'pending' || s === 'draft'
                            ? 'bg-yellow-100 text-yellow-800'
                            : (s === 'on hold' || s === 'rejected'
                                ? 'bg-red-100 text-red-800'
                                : 'bg-gray-100 text-gray-800')));
            }

            const modal = document.getElementById('payrollSummaryModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }

            return false;
        }

        function printPayrollSummary() {
            const content = document.getElementById('payrollSummaryContent');
            if (!content) return;

            const w = window.open('', '_blank');
            if (!w) return;

            w.document.open();
            w.document.write(
                '<!DOCTYPE html><html><head>' +
                '<meta charset="UTF-8">' +
                '<meta name="viewport" content="width=device-width, initial-scale=1.0">' +
                '<title>Payroll Summary</title>' +
                '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">' +
                '<script src="https://cdn.tailwindcss.com"><\/script>' +
                '</head><body class="bg-white p-6">' +
                content.innerHTML +
                '</body></html>'
            );
            w.document.close();
            w.focus();
            w.print();
            w.close();
        }

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Salary Distribution Chart
            const salaryCtx = document.getElementById('salaryDistributionChart').getContext('2d');
            new Chart(salaryCtx, {
                type: 'bar',
                data: {
                    labels: ['PHP 30-40k', 'PHP 40-50k', 'PHP 50-60k', 'PHP 60-70k', 'PHP 70-80k', 'PHP 80k+'],
                    datasets: [{
                        label: 'Employees',
                        data: [45, 82, 96, 68, 24, 13],
                        backgroundColor: '#60A5FA80',
                        borderColor: '#60A5FA',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // Department Cost Chart
            const deptCtx = document.getElementById('departmentCostChart').getContext('2d');
            new Chart(deptCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Medical Staff', 'Nursing', 'Administration', 'Support Staff'],
                    datasets: [{
                        data: [45, 25, 20, 10],
                        backgroundColor: [
                            '#60A5FA',
                            '#34D399',
                            '#F59E0B',
                            '#EC4899'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Overtime Trend Chart
            const overtimeCtx = document.getElementById('overtimeTrendChart').getContext('2d');
            new Chart(overtimeCtx, {
                type: 'line',
                data: {
                    labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [{
                        label: 'Hours',
                        data: [650, 730, 805, 860, 842, 780],
                        borderColor: '#60A5FA',
                        backgroundColor: '#60A5FA20',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                display: true,
                                drawBorder: false
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });

        // Modal toggle function
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                } else {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }
        }

        // Form Submissions
        document.getElementById('generatePayrollForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Payroll generated successfully!', 'success');
            toggleModal('generatePayrollModal');
            this.reset();
        });

        document.getElementById('addEmployeeForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Employee added successfully!', 'success');
            toggleModal('addEmployeeModal');
            this.reset();
        });

        // Notification Function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed bottom-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500 z-[60]`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
        // Tax Calculator Function
        function calculateTax() {
            const salary = parseFloat(document.getElementById('grossSalary').value);
            const allowances = parseFloat(document.getElementById('allowances').value);
            const deductions = parseFloat(document.getElementById('deductions').value);

            const taxableIncome = salary + allowances - deductions;
            let tax = 0;

            // Simple progressive tax calculation
            if (taxableIncome <= 50000) {
                tax = taxableIncome * 0.10;
            } else if (taxableIncome <= 100000) {
                tax = 5000 + (taxableIncome - 50000) * 0.15;
            } else {
                tax = 12500 + (taxableIncome - 100000) * 0.25;
            }

            document.getElementById('taxResult').textContent = `PHP ${tax.toFixed(2)}`;
            document.getElementById('netIncomeResult').textContent = `PHP ${(taxableIncome - tax).toFixed(2)}`;
        }

        // Attendance Calendar
        function initializeCalendar() {
            const calendar = document.getElementById('attendanceCalendar');
            const currentDate = new Date();
            const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
            
            let calendarHTML = '';
            for (let i = 1; i <= daysInMonth; i++) {
                const status = Math.random() > 0.1 ? 'present' : 'absent';
                calendarHTML += `
                    <div class="p-2 border rounded-lg text-center cursor-pointer hover:bg-gray-50 ${
                        status === 'present' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700'
                    }">
                        <div class="text-sm font-medium">${i}</div>
                        <div class="text-xs">${status}</div>
                    </div>
                `;
            }
            calendar.innerHTML = calendarHTML;
        }
    </script>

    <!-- Tax Calculator Modal -->
    <div id="taxCalculatorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Tax Calculator</h3>
                    <button onclick="toggleModal('taxCalculatorModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Gross Salary</label>
                        <input type="number" id="grossSalary" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Allowances</label>
                        <input type="number" id="allowances" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Deductions</label>
                        <input type="number" id="deductions" class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button onclick="calculateTax()" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Calculate
                    </button>
                    <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm font-medium text-gray-600">Estimated Tax:</span>
                            <span id="taxResult" class="text-sm font-semibold text-gray-900">PHP 0.00</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-600">Net Income:</span>
                            <span id="netIncomeResult" class="text-sm font-semibold text-gray-900">PHP 0.00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Modal -->
    <div id="benefitsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Employee Benefits</h3>
                    <button onclick="toggleModal('benefitsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-6">
                    <!-- Health Insurance -->
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-heartbeat text-blue-600 text-xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Health Insurance</h4>
                                <p class="text-xs text-gray-500">Comprehensive medical coverage</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                        </label>
                    </div>

                    <!-- Life Insurance -->
                    <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-600 text-xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Life Insurance</h4>
                                <p class="text-xs text-gray-500">Term life coverage</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        </label>
                    </div>

                    <!-- Retirement Plan -->
                    <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-piggy-bank text-purple-600 text-xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Retirement Plan</h4>
                                <p class="text-xs text-gray-500">401(k) with matching</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer" checked>
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <!-- Dental Coverage -->
                    <div class="flex items-center justify-between p-4 bg-yellow-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-tooth text-yellow-600 text-xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Dental Coverage</h4>
                                <p class="text-xs text-gray-500">Dental and vision benefits</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" class="sr-only peer">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-yellow-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-yellow-600"></div>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Modal -->
    <div id="attendanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Attendance Tracking</h3>
                    <button onclick="toggleModal('attendanceModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="mb-6 flex justify-between items-center">
                    <div class="flex space-x-2">
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            <i class="fas fa-calendar-alt mr-2"></i> This Month
                        </button>
                        <button class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                            <i class="fas fa-download mr-2"></i> Export
                        </button>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Present</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Absent</span>
                        </div>
                    </div>
                </div>
                <div id="attendanceCalendar" class="grid grid-cols-7 gap-2" onload="initializeCalendar()">
                    <!-- Calendar will be populated by JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Reports Modal -->
    <div id="reportsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Payroll Reports</h3>
                    <button onclick="toggleModal('reportsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-2 gap-4">
                    <!-- Payroll Summary Report -->
                    <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-file-invoice-dollar text-blue-600 text-2xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Payroll Summary</h4>
                                <p class="text-xs text-gray-500">Monthly payroll overview</p>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Generate</button>
                    </div>

                    <!-- Tax Report -->
                    <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-receipt text-green-600 text-2xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Tax Report</h4>
                                <p class="text-xs text-gray-500">Tax deductions summary</p>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Generate</button>
                    </div>

                    <!-- Department Cost Report -->
                    <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-chart-pie text-purple-600 text-2xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Department Costs</h4>
                                <p class="text-xs text-gray-500">Costs by department</p>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">Generate</button>
                    </div>

                    <!-- Benefits Report -->
                    <div class="p-4 border border-gray-200 rounded-lg hover:shadow-md transition-shadow cursor-pointer">
                        <div class="flex items-center mb-4">
                            <i class="fas fa-heart text-red-600 text-2xl"></i>
                            <div class="ml-4">
                                <h4 class="text-sm font-medium text-gray-900">Benefits Report</h4>
                                <p class="text-xs text-gray-500">Employee benefits analysis</p>
                            </div>
                        </div>
                        <button class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Generate</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

