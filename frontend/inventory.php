<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Equipment Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
        .chart-container {
            position: relative;
            width: 100%;
            height: 300px;
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
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
                            <a href="inventory.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
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
                            <a href="payroll.php" class="flex items-center gap-x-4 px-4 py-2 text-gray-500 hover:bg-blue-50 hover:text-blue-600 rounded-lg transition-colors duration-200">
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
        <main class="ml-16 lg:ml-80 flex-1 overflow-auto">
            <!-- Header -->
            <div class="bg-white p-6 flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Medical Equipment Inventory</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="addNewBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg flex items-center space-x-2 hover:bg-blue-700">
                        <i class="fas fa-plus"></i>
                        <span>Add New</span>
                    </button>
                    <button class="p-2 rounded-full hover:bg-gray-100">
                        <i class="fas fa-bell text-gray-500"></i>
                    </button>
                    <?php include __DIR__ . '/includes/profile-dropdown.php'; ?>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-blue-50 p-6 rounded-lg shadow border-l-4 border-blue-500">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-blue-700">Total Equipment</h3>
                            <span class="text-green-500 text-sm">+2.5%</span>
                        </div>
                        <p class="text-2xl font-semibold text-blue-900">4,818</p>
                    </div>
                    <div class="bg-green-50 p-6 rounded-lg shadow border-l-4 border-green-500">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-green-700">Active Usage</h3>
                            <span class="text-blue-500 text-sm">118,818</span>
                        </div>
                        <p class="text-2xl font-semibold text-green-900">98.78%</p>
                    </div>
                    <div class="bg-yellow-50 p-6 rounded-lg shadow border-l-4 border-yellow-500">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-yellow-700">Maintenance Due</h3>
                            <span class="text-yellow-500 text-sm">+5.2%</span>
                        </div>
                        <p class="text-2xl font-semibold">45</p>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-gray-500">Total Value</h3>
                            <span class="text-green-500 text-sm">+3.1%</span>
                        </div>
                        <p class="text-2xl font-semibold">$12.15M</p>
                    </div>
                </div>

                <!-- Equipment Usage Graph -->
                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold">Equipment Usage Analytics</h2>
                        <button class="text-blue-600 hover:text-blue-700 flex items-center space-x-2">
                            <span>Download Report</span>
                            <i class="fas fa-download"></i>
                        </button>
                    </div>
                    <div class="chart-container">
                        <canvas id="equipmentChart"></canvas>
                    </div>
                </div>

                <!-- Additional Charts Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Category Distribution Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">Equipment Categories</h2>
                            <div class="flex space-x-2">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-info-circle"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    <!-- Maintenance Status Chart -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">Maintenance Status</h2>
                            <div class="flex space-x-2">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="maintenanceChart"></canvas>
                        </div>
                    </div>

                    <!-- Equipment Value Trend -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">Equipment Value Trend</h2>
                            <div class="flex space-x-2">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-calendar"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="valueChart"></canvas>
                        </div>
                    </div>

                    <!-- Department Usage Distribution -->
                    <div class="bg-white p-6 rounded-lg shadow">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-semibold">Department Usage</h2>
                            <div class="flex space-x-2">
                                <button class="text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-filter"></i>
                                </button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="departmentUsageChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Equipment List -->
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold">Equipment Inventory</h2>
                            <div class="flex items-center space-x-4">
                                <button class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-filter"></i>
                                    <span class="ml-2">Filter</span>
                                </button>
                                <button class="text-gray-600 hover:text-gray-800">
                                    <i class="fas fa-cog"></i>
                                    <span class="ml-2">Settings</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usage Rate</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                                <i class="fas fa-heartbeat text-blue-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">ECG Machine</div>
                                                <div class="text-sm text-gray-500">ID: MED-2023-001</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Diagnostic
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Active
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 80%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">80% Usage</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                                <i class="fas fa-lungs text-green-600"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Ventilator</div>
                                                <div class="text-sm text-gray-500">ID: MED-2023-002</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Life Support
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Maintenance
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-blue-600 h-2.5 rounded-full" style="width: 60%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600">60% Usage</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-gray-600 hover:text-gray-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-600">
                                Showing 1 to 2 of 100 entries
                            </div>
                            <div class="flex items-center space-x-2">
                                <button class="px-3 py-1 border rounded-md hover:bg-gray-100">Previous</button>
                                <button class="px-3 py-1 border rounded-md bg-blue-600 text-white">1</button>
                                <button class="px-3 py-1 border rounded-md hover:bg-gray-100">2</button>
                                <button class="px-3 py-1 border rounded-md hover:bg-gray-100">3</button>
                                <button class="px-3 py-1 border rounded-md hover:bg-gray-100">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Equipment Usage Chart
        const ctx = document.getElementById('equipmentChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['1', '4', '8', '12', '16', '20', '24', '28', '30'],
                datasets: [{
                    label: 'Equipment Usage',
                    data: [500, 800, 600, 900, 800, 1000, 700, 800, 600],
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        // Category Distribution Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Diagnostic', 'Life Support', 'Monitoring', 'Surgery', 'Laboratory', 'Other'],
                datasets: [{
                    data: [30, 20, 15, 15, 12, 8],
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(168, 85, 247, 0.8)',
                        'rgba(107, 114, 128, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            }
        });

        // Maintenance Status Chart
        const maintenanceCtx = document.getElementById('maintenanceChart').getContext('2d');
        new Chart(maintenanceCtx, {
            type: 'bar',
            data: {
                labels: ['Up to Date', 'Due Soon', 'Overdue', 'In Progress', 'Under Repair'],
                datasets: [{
                    label: 'Equipment Count',
                    data: [150, 45, 15, 20, 10],
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.8)',
                        'rgba(245, 158, 11, 0.8)',
                        'rgba(239, 68, 68, 0.8)',
                        'rgba(59, 130, 246, 0.8)',
                        'rgba(107, 114, 128, 0.8)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
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

        // Equipment Value Trend
        const valueCtx = document.getElementById('valueChart').getContext('2d');
        new Chart(valueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Total Value (millions)',
                    data: [10.2, 10.8, 11.2, 11.5, 12.0, 12.15],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });

        // Department Usage Distribution
        const departmentUsageCtx = document.getElementById('departmentUsageChart').getContext('2d');
        new Chart(departmentUsageCtx, {
            type: 'polarArea',
            data: {
                labels: ['ICU', 'Surgery', 'Emergency', 'Cardiology', 'Radiology', 'Laboratory'],
                datasets: [{
                    data: [35, 25, 20, 15, 12, 8],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(168, 85, 247, 0.7)',
                        'rgba(107, 114, 128, 0.7)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12
                        }
                    }
                }
            }
        });
    </script>

    <!-- Add/Edit Equipment Modal -->
    <div id="equipmentModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-4xl mx-4 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent" id="modalTitle">Add New Equipment</h3>
                <button onclick="toggleModal('equipmentModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="equipmentForm" onsubmit="handleEquipmentSubmit(event)" class="space-y-6">
                <input type="hidden" id="equipmentId" value="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-600 mb-2">Equipment Name</label>
                        <input type="text" id="equipmentName" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none" 
                            required 
                            placeholder="Enter equipment name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Category</label>
                        <div class="relative">
                            <select id="equipmentCategory" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none appearance-none">
                                <option value="" disabled selected>Select category</option>
                                <option value="diagnostic">Diagnostic</option>
                                <option value="life-support">Life Support</option>
                                <option value="monitoring">Monitoring</option>
                                <option value="surgery">Surgery</option>
                                <option value="laboratory">Laboratory</option>
                            </select>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-500">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Status</label>
                        <div class="relative">
                            <select id="equipmentStatus" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none appearance-none">
                                <option value="" disabled selected>Select status</option>
                                <option value="active">Active</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="repair">Under Repair</option>
                                <option value="inactive">Inactive</option>
                            </select>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-500">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Department</label>
                        <div class="relative">
                            <select id="equipmentDepartment" 
                                class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none appearance-none">
                                <option value="" disabled selected>Select department</option>
                                <option value="icu">ICU</option>
                                <option value="surgery">Surgery</option>
                                <option value="emergency">Emergency</option>
                                <option value="cardiology">Cardiology</option>
                                <option value="radiology">Radiology</option>
                                <option value="laboratory">Laboratory</option>
                            </select>
                            <div class="absolute right-3 top-1/2 transform -translate-y-1/2 pointer-events-none text-gray-500">
                                <i class="fas fa-chevron-down"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Serial Number</label>
                        <input type="text" id="serialNumber" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none" 
                            placeholder="Enter serial number">
                    </div>
                </div>
                <div class="border-t border-gray-100 pt-6 mt-6">
                    <div class="flex justify-end space-x-4">
                        <button type="button" 
                            onclick="toggleModal('equipmentModal')" 
                            class="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 hover:border-gray-300 transition-all focus:outline-none focus:ring-2 focus:ring-gray-200">
                            Cancel
                        </button>
                        <button type="submit" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Save Equipment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Filter Modal -->
    <div id="filterModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold">Filter Equipment</h3>
                <button onclick="toggleModal('filterModal')" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="filterForm" onsubmit="handleFilterSubmit(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select id="filterCategory" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" multiple>
                            <option value="diagnostic">Diagnostic</option>
                            <option value="life-support">Life Support</option>
                            <option value="monitoring">Monitoring</option>
                            <option value="surgery">Surgery</option>
                            <option value="laboratory">Laboratory</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="filterStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" multiple>
                            <option value="active">Active</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="repair">Under Repair</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Department</label>
                        <select id="filterDepartment" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" multiple>
                            <option value="icu">ICU</option>
                            <option value="surgery">Surgery</option>
                            <option value="emergency">Emergency</option>
                            <option value="cardiology">Cardiology</option>
                            <option value="radiology">Radiology</option>
                            <option value="laboratory">Laboratory</option>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" onclick="resetFilters()" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                        Reset
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-sm">
            <div class="text-center">
                <i class="fas fa-exclamation-triangle text-5xl text-red-500 mb-4"></i>
                <h3 class="text-lg font-semibold mb-2">Delete Equipment</h3>
                <p class="text-gray-600 mb-6">Are you sure you want to delete this equipment? This action cannot be undone.</p>
            </div>
            <div class="flex justify-end space-x-3">
                <button onclick="toggleModal('deleteModal')" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">
                    Cancel
                </button>
                <button onclick="confirmDelete()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <script>
        // Wait for DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize event listeners
            initializeEventListeners();
        });

        function initializeEventListeners() {
            // Add New button
            document.getElementById('addNewBtn').addEventListener('click', function() {
                document.getElementById('modalTitle').textContent = 'Add New Equipment';
                document.getElementById('equipmentId').value = '';
                document.getElementById('equipmentForm').reset();
                toggleModal('equipmentModal');
            });

            // Edit buttons
            document.querySelectorAll('.edit-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    editEquipment(id);
                });
            });

            // Delete buttons
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    deleteEquipment(id);
                });
            });

            // Filter button
            document.querySelector('button[title="Filter"]')?.addEventListener('click', () => {
                toggleModal('filterModal');
            });

            // Download report button
            document.querySelector('button.text-blue-600')?.addEventListener('click', () => {
                downloadReport();
            });
        }

        function downloadReport() {
            // Here you would implement the actual report download logic
            console.log('Downloading report...');
            alert('Report download started!');
        }

        // Modal functionality
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Add New Equipment button
        document.querySelector('button[title="Add New"]').addEventListener('click', () => {
            document.getElementById('modalTitle').textContent = 'Add New Equipment';
            document.getElementById('equipmentId').value = '';
            document.getElementById('equipmentForm').reset();
            toggleModal('equipmentModal');
        });

        // Filter button
        document.querySelector('button[title="Filter"]').addEventListener('click', () => {
            toggleModal('filterModal');
        });

        // Edit button functionality
        function editEquipment(id) {
            document.getElementById('modalTitle').textContent = 'Edit Equipment';
            document.getElementById('equipmentId').value = id;
            // Here you would normally populate the form with the equipment data
            toggleModal('equipmentModal');
        }

        // Delete button functionality
        let equipmentToDelete = null;
        function deleteEquipment(id) {
            equipmentToDelete = id;
            toggleModal('deleteModal');
        }

        function confirmDelete() {
            if (equipmentToDelete) {
                // Here you would normally make an API call to delete the equipment
                console.log('Deleting equipment:', equipmentToDelete);
                toggleModal('deleteModal');
                equipmentToDelete = null;
            }
        }

        // Form submissions
        function handleEquipmentSubmit(event) {
            event.preventDefault();
            const formData = {
                id: document.getElementById('equipmentId').value,
                name: document.getElementById('equipmentName').value,
                category: document.getElementById('equipmentCategory').value,
                status: document.getElementById('equipmentStatus').value,
                department: document.getElementById('equipmentDepartment').value
            };
            console.log('Form data:', formData);
            toggleModal('equipmentModal');
        }

        function handleFilterSubmit(event) {
            event.preventDefault();
            const filterData = {
                categories: Array.from(document.getElementById('filterCategory').selectedOptions).map(opt => opt.value),
                statuses: Array.from(document.getElementById('filterStatus').selectedOptions).map(opt => opt.value),
                departments: Array.from(document.getElementById('filterDepartment').selectedOptions).map(opt => opt.value)
            };
            console.log('Filter data:', filterData);
            toggleModal('filterModal');
        }

        function resetFilters() {
            document.getElementById('filterForm').reset();
        }

        // Download report functionality
        document.querySelector('button.text-blue-600').addEventListener('click', () => {
            // Here you would normally generate and download the report
            console.log('Downloading report...');
            alert('Report download started!');
        });

        // Pagination functionality
        const paginationButtons = document.querySelectorAll('.pagination button');
        paginationButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Here you would normally handle pagination
                console.log('Page clicked:', button.textContent);
            });
        });
    </script>

    <script>
        // Initialize charts with animation options
        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            animation: {
                duration: 2000,
                easing: 'easeOutQuart',
                delay: 300
            }
        };

        // Line Chart
        const maintenanceChart = new Chart(document.getElementById('maintenanceChart'), {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Maintenance Requests',
                    data: [65, 59, 80, 81, 56, 55],
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: chartOptions
        });

        // Doughnut Chart
        const equipmentStatusChart = new Chart(document.getElementById('equipmentStatusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Maintenance', 'Retired', 'Under Repair'],
                datasets: [{
                    data: [300, 50, 100, 30],
                    backgroundColor: [
                        'rgb(34, 197, 94)',
                        'rgb(234, 179, 8)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 162, 158)'
                    ]
                }]
            },
            options: {
                ...chartOptions,
                animation: {
                    ...chartOptions.animation,
                    animateRotate: true,
                    animateScale: true
                }
            }
        });

        // Bar Chart
        const departmentChart = new Chart(document.getElementById('departmentChart'), {
            type: 'bar',
            data: {
                labels: ['ICU', 'Surgery', 'Emergency', 'Cardiology', 'Radiology'],
                datasets: [{
                    label: 'Equipment Count',
                    data: [120, 150, 180, 90, 100],
                    backgroundColor: 'rgba(59, 130, 246, 0.8)'
                }]
            },
            options: chartOptions
        });

        // Polar Area Chart
        const serviceLifeChart = new Chart(document.getElementById('serviceLifeChart'), {
            type: 'polarArea',
            data: {
                labels: ['0-2 Years', '2-5 Years', '5-8 Years', '8+ Years'],
                datasets: [{
                    data: [90, 120, 70, 40],
                    backgroundColor: [
                        'rgba(34, 197, 94, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(234, 179, 8, 0.7)',
                        'rgba(239, 68, 68, 0.7)'
                    ]
                }]
            },
            options: {
                ...chartOptions,
                animation: {
                    ...chartOptions.animation,
                    animateRotate: true,
                    animateScale: true
                }
            }
        });
    </script>
</body>
</html>

