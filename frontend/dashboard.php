<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="flex h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <!-- Sidebar -->
        <?php if (false): ?>
        <aside class="w-64 bg-blue-800 text-white p-6">
            <div class="mb-8">
                <img src="resources/logo.png" alt="Hospital Dashboard" class="h-8">
            </div>
            <nav class="space-y-2">
                <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg bg-blue-700">
                    <i class="fas fa-chart-line w-5 h-5"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                    <i class="fas fa-user-injured w-5 h-5"></i>
                    <span>Patients</span>
                </a>
                <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                    <i class="fas fa-calendar w-5 h-5"></i>
                    <span>Appointments</span>
                </a>
                <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                    <i class="fas fa-user-md w-5 h-5"></i>
                    <span>Doctors</span>
                </a>
                <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                    <i class="fas fa-pills w-5 h-5"></i>
                    <span>Medicine</span>
                </a>
                <a href="#" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg">
                    <i class="fas fa-cog w-5 h-5"></i>
                    <span>Settings</span>
                </a>
            </nav>
        </aside>

        <?php endif; ?>

        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <!-- Header -->
            <header class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Dashboard</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-500"></i>
                    </button>
                    <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <!-- Total Patients -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500">Total Patients</h3>
                            <span class="text-green-500 text-sm">+4.75%</span>
                        </div>
                        <p class="text-2xl font-semibold">1,287</p>
                    </div>
                    <!-- Appointments -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500">Appointments</h3>
                            <span class="text-green-500 text-sm">+2.5%</span>
                        </div>
                        <p class="text-2xl font-semibold">965</p>
                    </div>
                    <!-- Operations -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500">Operations</h3>
                            <span class="text-red-500 text-sm">-1.2%</span>
                        </div>
                        <p class="text-2xl font-semibold">128</p>
                    </div>
                    <!-- Revenue -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500">Revenue</h3>
                            <span class="text-green-500 text-sm">+8.4%</span>
                        </div>
                        <p class="text-2xl font-semibold">$315K</p>
                    </div>
                </div>

                <!-- Charts -->
                <div class="grid grid-cols-2 gap-6 mb-6">
                    <!-- Patient Overview Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Patient Overview</h3>
                        <canvas id="patientChart"></canvas>
                    </div>
                    <!-- Revenue Overview Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Revenue Overview</h3>
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>

                <!-- Appointments and Department Overview -->
                <div class="grid grid-cols-3 gap-6">
                    <!-- Upcoming Appointments -->
                    <div class="col-span-2 bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Upcoming Appointments</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Sarah Johnson</div>
                                                    <div class="text-sm text-gray-500">General Checkup</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sep 15, 2023</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10:00 AM</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full bg-gray-200"></div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">Michael Brown</div>
                                                    <div class="text-sm text-gray-500">Follow-up</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Sep 15, 2023</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">11:30 AM</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Department Overview -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="text-lg font-semibold mb-4">Department Overview</h3>
                        <canvas id="departmentChart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Chart.js configuration
        const patientCtx = document.getElementById('patientChart').getContext('2d');
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');

        // Patient Overview Chart
        new Chart(patientCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Patients',
                    data: [65, 78, 90, 85, 95, 100],
                    borderColor: 'rgb(59, 130, 246)',
                    tension: 0.3,
                    fill: false
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Revenue Overview Chart
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [30000, 35000, 32000, 38000, 40000, 42000],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
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
                        beginAtZero: true
                    }
                }
            }
        });

        // Department Overview Chart
        new Chart(departmentCtx, {
            type: 'doughnut',
            data: {
                labels: ['Emergency', 'Surgery', 'Pediatrics', 'Other'],
                datasets: [{
                    data: [40, 25, 20, 15],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(107, 114, 128, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>

    </div>
</body>

</html>
