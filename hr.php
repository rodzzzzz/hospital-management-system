<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Human Resources - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Human Resources</h1>
                    <p class="text-sm text-gray-600 mt-1">Manage staff directory, access roles, scheduling, and HR records.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                <a href="hr-directory.php" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Staff Directory</div>
                            <div class="text-sm text-gray-600 mt-1">Browse and manage staff profiles.</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-700 flex items-center justify-center">
                            <i class="fas fa-address-book"></i>
                        </div>
                    </div>
                </a>

                <a href="hr-accounts.php" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">User Accounts / Access</div>
                            <div class="text-sm text-gray-600 mt-1">Create accounts and assign module roles.</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center">
                            <i class="fas fa-user-shield"></i>
                        </div>
                    </div>
                </a>

                <a href="hr-scheduling.php" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Scheduling / Duty Roster</div>
                            <div class="text-sm text-gray-600 mt-1">Assign shifts and manage coverage.</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-50 text-emerald-700 flex items-center justify-center">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                    </div>
                </a>

                <a href="hr-payroll.php" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Payroll</div>
                            <div class="text-sm text-gray-600 mt-1">Compute pay from shifts and manage deductions.</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                    </div>
                </a>

                <a href="hr-departments.php" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Departments &amp; Positions</div>
                            <div class="text-sm text-gray-600 mt-1">Maintain departments, positions, and roles.</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center">
                            <i class="fas fa-sitemap"></i>
                        </div>
                    </div>
                </a>

                <a href="hr-reports.php" class="block bg-white rounded-lg shadow-sm border border-gray-100 hover:border-blue-200 hover:shadow-md transition p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-semibold text-gray-900">Reports</div>
                            <div class="text-sm text-gray-600 mt-1">Staffing, schedules, and compliance reports.</div>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-100 p-6">
                <div class="text-sm font-semibold text-gray-900">Next</div>
                <div class="text-sm text-gray-600 mt-1">Tell me which HR section you want to build first (Directory, Accounts, Scheduling, Departments, Reports) and I’ll wire the pages and database.</div>
            </div>
        </main>
    </div>
</body>
</html>
