<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .stat-card {
            transition: all 0.3s ease;
        }
        .stat-card:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
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
                            <a href="pharmacy.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
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
            <div class="bg-white p-6 flex items-center justify-between shadow-sm">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-semibold">Pharmacy Management</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <input type="text" placeholder="Search medicines..." 
                            class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <div class="flex items-center space-x-2">
                        <?php
                            $pharmacyDisplayNameRaw = (string)($authUser['full_name'] ?? ($authUser['username'] ?? 'User'));
                            $pharmacyDisplayName = htmlspecialchars($pharmacyDisplayNameRaw, ENT_QUOTES);

                            $pharmacyRoleLabelRaw = 'User';
                            $authRolesForPharmacy = $authUser['roles'] ?? [];
                            if (is_array($authRolesForPharmacy)) {
                                foreach ($authRolesForPharmacy as $r) {
                                    $module = strtolower((string)($r['module'] ?? ''));
                                    if ($module === 'pharmacy') {
                                        $role = (string)($r['role'] ?? '');
                                        $pharmacyRoleLabelRaw = $role !== '' ? ucfirst($role) : 'Pharmacy';
                                        break;
                                    }
                                }
                            }
                            $pharmacyRoleLabel = htmlspecialchars($pharmacyRoleLabelRaw, ENT_QUOTES);
                        ?>
                        <img src="resources/doctor.jpg" alt="<?php echo $pharmacyDisplayName; ?>" class="w-10 h-10 rounded-full">
                        <div>
                            <div class="font-medium"><?php echo $pharmacyDisplayName; ?></div>
                            <div class="text-sm text-gray-500"><?php echo $pharmacyRoleLabel; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pharmacyDashboardView" class="p-6 space-y-6">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-4 gap-6">
                    <!-- Total Products -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm stat-card">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-green-100 p-3 rounded-xl">
                                <i class="fas fa-box text-green-600 text-xl"></i>
                            </div>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div>
                            <h3 id="pharmacyStatTotalMedicines" class="text-3xl font-bold">0</h3>
                            <p class="text-gray-500 text-sm mt-1">Total Medicines</p>
                        </div>
                    </div>

                    <!-- Antibiotics -->
                    <div class="bg-teal-700 text-white rounded-2xl p-6 shadow-sm stat-card">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-teal-600 bg-opacity-30 p-3 rounded-xl">
                                <i class="fas fa-capsules text-white text-xl"></i>
                            </div>
                            <div class="flex items-center bg-teal-600 bg-opacity-30 px-2 py-1 rounded-lg text-sm"></div>
                        </div>
                        <div>
                            <h3 id="pharmacyStatTotalResits" class="text-3xl font-bold">0</h3>
                            <p class="text-teal-100 text-sm mt-1">Patient Receipts</p>
                        </div>
                    </div>

                    <!-- Low Stock -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm stat-card">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-yellow-100 p-3 rounded-xl">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                            </div>
                            <div class="flex items-center text-yellow-600 bg-yellow-100 px-2 py-1 rounded-lg text-sm"></div>
                        </div>
                        <div>
                            <h3 id="pharmacyStatLowStock" class="text-3xl font-bold">0</h3>
                            <p class="text-gray-500 text-sm mt-1">Low Stock</p>
                        </div>
                    </div>

                    <!-- Out of Stock -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm stat-card">
                        <div class="flex justify-between items-start mb-4">
                            <div class="bg-red-100 p-3 rounded-xl">
                                <i class="fas fa-times-circle text-red-600 text-xl"></i>
                            </div>
                            <div class="flex items-center text-red-600 bg-red-100 px-2 py-1 rounded-lg text-sm"></div>
                        </div>
                        <div>
                            <h3 id="pharmacyStatOutOfStock" class="text-3xl font-bold">0</h3>
                            <p class="text-gray-500 text-sm mt-1">Out of Stock</p>
                        </div>
                    </div>
                </div>

                <!-- Pharmacy Queue Section -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
                        <h3 class="text-2xl font-bold text-gray-900 flex items-center">
                            <i class="fas fa-users mr-2 text-blue-600"></i>
                            Pharmacy Queue
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <button id="pharmacyCallNextBtn" onclick="callNextPatient()" class="p-4 bg-blue-600 text-white rounded-lg text-lg font-semibold hover:bg-blue-700 transition-colors flex items-center">
                                <i class="fas fa-bell mr-2"></i> Call Next Patient
                            </button>
                            <button onclick="qecOpenReportModal()" class="p-4 bg-red-600 text-white rounded-lg text-lg font-semibold hover:bg-red-700 transition-colors flex items-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i> Report Wrong Station
                            </button>
                        </div>
                    </div>

                    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 rounded-xl border-2 border-green-200">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse mr-2"></div>
                            <h4 class="text-lg font-semibold text-gray-800">Currently Serving</h4>
                        </div>
                        <div id="pharmacyCurrentlyServing" class="text-center py-3">
                            <div class="text-gray-500">No patient being served</div>
                        </div>
                        <div id="pharmacyStationSelection" class="mt-4 hidden flex gap-2 justify-end">
                            <button onclick="callNextAndMarkUnavailable()" class="p-4 bg-orange-600 text-white rounded-lg text-lg font-semibold hover:bg-orange-700 transition-colors flex items-center">
                                <i class="fas fa-user-slash mr-2"></i> Mark Unavailable
                            </button>
                            <button onclick="openPharmacySendPatientModal()" class="p-4 bg-green-600 text-white rounded-lg text-lg font-semibold hover:bg-green-700 transition-colors flex items-center">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Send to Next Station
                            </button>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                            <i class="fas fa-list-ol mr-2 text-blue-600"></i>
                            Waiting Queue
                        </h4>
                        <div id="pharmacyQueueList" class="space-y-2">
                            <div class="text-center py-8 text-gray-400">
                                <i class="fas fa-users-slash text-4xl mb-2"></i>
                                <p>No patients in queue</p>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 p-4 flex items-center">
                            <i class="fas fa-user-clock mr-2 text-orange-600"></i>
                            Unavailable Patients
                        </h4>
                        <div id="pharmacyUnavailablePatientsList" class="space-y-2">
                            <div class="text-center py-6 text-gray-400">
                                <i class="fas fa-check-circle text-3xl mb-2"></i>
                                <p>No unavailable patients</p>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-200">
                        <button onclick="openDisplayScreen()" class="w-full px-4 py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition-colors flex items-center justify-center">
                            <i class="fas fa-tv mr-2"></i>
                            Open Display Screen
                        </button>
                    </div>
                </div>

                <!-- Send Patient Modal -->
                <div id="pharmacySendPatientModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-[60]">
                    <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl max-h-[90vh] mx-4 flex flex-col">
                        <div class="p-8 border-b border-gray-100 flex items-center justify-between flex-shrink-0">
                            <h3 class="text-2xl font-bold text-gray-900">Send Patient to Next Station</h3>
                            <button type="button" class="text-gray-400 hover:text-gray-600 text-2xl p-2" onclick="closePharmacySendPatientModal()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <div class="p-8 flex-1 overflow-y-auto">
                            <div class="mb-6">
                                <label class="block text-lg font-semibold text-gray-700 mb-4">Select Destination Station:</label>
                                <div id="pharmacyStationList" class="space-y-4"></div>
                            </div>
                        </div>
                        <div class="p-8 bg-gray-50 border-t flex justify-end gap-4 flex-shrink-0">
                            <button type="button" class="px-8 py-4 border-2 border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100 text-lg font-semibold transition-colors" onclick="closePharmacySendPatientModal()">
                                Cancel
                            </button>
                            <button type="button" id="pharmacyConfirmSendBtn" class="px-8 py-4 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-lg font-semibold transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>
                                <i class="fas fa-paper-plane mr-3"></i>
                                Send Patient
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Product List Section -->
                <div class="bg-white rounded-2xl shadow-sm">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-4">
                                <h2 class="text-lg font-semibold">Medicines</h2>
                                <span class="text-sm text-gray-500">Total <span id="pharmacyDashboardMedicinesTotal">0</span> medicines</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <button id="addProductBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-blue-700">
                                    <i class="fas fa-plus"></i>
                                    <span>Add New Medicine</span>
                                </button>
                                <button class="border px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-50">
                                    <i class="fas fa-filter text-gray-500"></i>
                                    <span>Filters</span>
                                </button>
                                <button class="border px-4 py-2 rounded-lg flex items-center space-x-2 hover:bg-gray-50">
                                    <i class="fas fa-download text-gray-500"></i>
                                    <span>Export</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full">
                            <thead>
                                <tr class="bg-gray-50">
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody id="pharmacyDashboardMedicinesTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="pharmacyDashboardMedicinesEmpty" class="hidden p-6 text-sm text-gray-500">No medicines found.</div>
                </div>
                <!-- Charts Section -->
                <div class="grid grid-cols-2 gap-6 mb-6 hidden">
                    <!-- Sales Trend Line Chart -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-semibold">Sales Analytics</h3>
                                <p class="text-sm text-gray-500">Monthly revenue overview</p>
                            </div>
                            <select class="border rounded-lg px-3 py-1 text-sm" onchange="updateSalesChart(this.value)">
                                <option value="6">Last 6 months</option>
                                <option value="12">Last 12 months</option>
                            </select>
                        </div>
                        <div class="h-80">
                            <canvas id="salesTrendChart"></canvas>
                        </div>
                    </div>

                    <!-- Inventory Bar Chart -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-semibold">Inventory Levels</h3>
                                <p class="text-sm text-gray-500">Stock levels by category</p>
                            </div>
                            <button class="text-blue-600 hover:text-blue-700 text-sm">
                                View Details
                            </button>
                        </div>
                        <div class="h-80">
                            <canvas id="inventoryChart"></canvas>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6 hidden">
                    <!-- Category Distribution Pie Chart -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-semibold">Category Distribution</h3>
                                <p class="text-sm text-gray-500">Products by category</p>
                            </div>
                            <div class="flex space-x-2">
                                <button class="text-sm text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-redo"></i>
                                </button>
                                <button class="text-sm text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>

                    <!-- Regional Distribution Map -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-semibold">Regional Distribution</h3>
                                <p class="text-sm text-gray-500">Sales by region</p>
                            </div>
                            <div class="flex space-x-2">
                                <select class="border rounded-lg px-3 py-1 text-sm" onchange="updateRegionMap(this.value)">
                                    <option value="sales">Sales Volume</option>
                                    <option value="revenue">Revenue</option>
                                </select>
                                <button onclick="toggleHeatmap()" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                                    Toggle Heatmap
                                </button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="regionMap"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Additional Visualizations -->
                <div class="grid grid-cols-2 gap-6 mb-6 hidden">
                    <!-- Product Performance Radar -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-semibold">Product Performance Matrix</h3>
                                <p class="text-sm text-gray-500">Multi-dimensional analysis</p>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="updateRadarMetrics('sales')" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                                    Sales
                                </button>
                                <button onclick="updateRadarMetrics('profit')" class="px-3 py-1 text-sm border rounded-lg hover:bg-gray-50">
                                    Profit
                                </button>
                            </div>
                        </div>
                        <div class="h-80">
                            <canvas id="performanceRadar"></canvas>
                        </div>
                    </div>

                    <!-- Price vs Demand Analysis -->
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <div>
                                <h3 class="font-semibold">Price vs Demand Analysis</h3>
                                <p class="text-sm text-gray-500">Interactive scatter plot</p>
                            </div>
                            <select id="categoryFilter" class="border rounded-lg px-3 py-1 text-sm" onchange="updateScatterPlot(this.value)">
                                <option value="all">All Categories</option>
                                <option value="antibiotics">Antibiotics</option>
                                <option value="painRelievers">Pain Relievers</option>
                                <option value="vitamins">Vitamins</option>
                            </select>
                        </div>
                        <div class="h-80">
                            <canvas id="priceAnalysis"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Stock Movement Timeline -->
                <div class="bg-white p-6 rounded-2xl shadow-sm mb-6 hidden">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h3 class="font-semibold">Stock Movement Timeline</h3>
                            <p class="text-sm text-gray-500">Interactive timeline visualization</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="showIncoming" checked class="form-checkbox h-4 w-4 text-blue-600">
                                <label class="text-sm">Incoming</label>
                            </div>
                            <div class="flex items-center space-x-2">
                                <input type="checkbox" id="showOutgoing" checked class="form-checkbox h-4 w-4 text-red-600">
                                <label class="text-sm">Outgoing</label>
                            </div>
                            <select class="border rounded-lg px-3 py-1 text-sm" onchange="updateTimelineRange(this.value)">
                                <option value="24h">Last 24 Hours</option>
                                <option value="7d">Last 7 Days</option>
                                <option value="30d">Last 30 Days</option>
                            </select>
                        </div>
                    </div>
                    <div class="h-64">
                        <canvas id="stockTimeline"></canvas>
                    </div>
                </div>

                <!-- Category Stats -->
                <div class="grid grid-cols-4 gap-6">
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Pain Relievers</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-2xl font-bold">95</span>
                            <span class="text-red-500 text-sm">-2.65%</span>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Vitamins & Supplements</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-2xl font-bold">75</span>
                            <span class="text-green-500 text-sm">+1.2%</span>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Cardiovascular</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-2xl font-bold">80</span>
                            <span class="text-green-500 text-sm">+3.8%</span>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl shadow-sm">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="font-semibold">Respiratory Medicines</h3>
                            <button class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-2xl font-bold">55</span>
                            <span class="text-green-500 text-sm">+2.8%</span>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pharmacyPatientResitView" class="hidden p-6 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h2 class="text-lg font-semibold">Patient Receipt</h2>
                        <p class="text-sm text-gray-500 mt-1">Input the receipt prescribed by the doctor for the patient.</p>
                    </div>
                    <div class="p-6">
                        <div class="flex flex-col gap-6">
                            <div>
                                <div class="flex items-center justify-between gap-3 mb-4">
                                    <div class="relative flex-1 max-w-md">
                                        <input id="pharmacyResitPatientSearch" type="text" placeholder="Search patient by name or ID..." class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    </div>
                                    <button id="pharmacyResitPatientRefresh" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Refresh</button>
                                </div>

                                <div class="overflow-x-auto border border-gray-100 rounded-xl">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sex</th>
                                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">DOB</th>
                                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="pharmacyResitPatientsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                                    </table>
                                </div>

                                <div id="pharmacyResitPatientsEmpty" class="hidden text-sm text-gray-500 mt-4">No patients found.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="pharmacyConsultNotesView" class="hidden p-6 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold">Consultation Notes</h2>
                                <p class="text-sm text-gray-500 mt-1">Submitted consultation notes for Pharmacy.</p>
                            </div>
                            <button id="pharmacyConsultNotesRefresh" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Refresh</button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Source</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Provider</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Note Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pharmacyConsultNotesTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="pharmacyConsultNotesEmpty" class="hidden p-6 text-sm text-gray-500">No submitted notes yet.</div>
                </div>
            </div>

            <div id="pharmacyPatientResitInfoView" class="hidden p-6 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold">Patient Receipt Info</h2>
                                <p class="text-sm text-gray-500 mt-1">Submitted receipt forms for patients.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input id="pharmacyResitInfoSearch" type="text" placeholder="Search receipts..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-72">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <button id="pharmacyResitInfoRefresh" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Refresh</button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient ID</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Prescribed By</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Submitted At</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Items</th>
                                    <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                                </tr>
                            </thead>
                            <tbody id="pharmacyResitInfoTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="pharmacyResitInfoEmpty" class="hidden p-6 text-sm text-gray-500">No submitted receipts yet.</div>
                </div>
            </div>

            <div id="pharmacyMedicinesView" class="hidden p-6 space-y-6">
                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between gap-4">
                            <div>
                                <h2 class="text-lg font-semibold">Medicines</h2>
                                <p class="text-sm text-gray-500 mt-1">List of available medicines.</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="relative">
                                    <input id="pharmacyMedicinesSearch" type="text" placeholder="Search medicines..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-72">
                                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                </div>
                                <button id="pharmacyMedicinesRefresh" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50">Refresh</button>
                                <button id="pharmacyMedicinesAddBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add Medicine</button>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody id="pharmacyMedicinesTbody" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                    <div id="pharmacyMedicinesEmpty" class="hidden p-6 text-sm text-gray-500">No medicines found.</div>
                </div>
            </div>
        </main>
    </div>

    <div id="pharmacyResitModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-5xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Receipt</h3>
                    <div class="mt-1 text-xs text-gray-500">Complete the receipt prescribed by the doctor.</div>
                </div>
                <button id="pharmacyResitModalClose" type="button" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <div id="pharmacyResitConsultMedsPanel" class="mb-4">
                    <div class="text-xs font-semibold text-gray-700">Medications Prescribed</div>
                    <textarea id="pharmacyResitConsultMeds" readonly rows="2" class="mt-2 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" placeholder="Select a patient to load..."></textarea>
                    <div id="pharmacyResitConsultMedsMeta" class="mt-1 text-[11px] text-gray-500"></div>
                </div>
                <div class="flex items-center justify-between">
                    <div class="text-xs font-semibold text-gray-700">Medicines</div>
                    <button id="pharmacyResitAddItem" type="button" class="px-3 py-1.5 text-xs bg-blue-600 text-white rounded-lg hover:bg-blue-700">Add Item</button>
                </div>
                <div id="pharmacyResitItems" class="mt-3 space-y-3"></div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex justify-end gap-3">
                <button id="pharmacyResitCancel" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Cancel</button>
                <button id="pharmacyResitSubmit" type="button" class="px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800">Submit</button>
            </div>
        </div>
    </div>

    <div id="pharmacyConsultNoteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-lg w-full max-w-5xl mx-4">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Consultation Note</h3>
                    <div id="pharmacyConsultNoteModalMeta" class="mt-1 text-xs text-gray-500"></div>
                </div>
                <button id="pharmacyConsultNoteModalClose" type="button" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 max-h-[75vh] overflow-y-auto">
                <div id="pharmacyConsultNoteModalBody" class="text-sm text-gray-800"></div>
            </div>
            <div class="p-6 bg-gray-50 border-t flex justify-end">
                <button id="pharmacyConsultNoteModalClose2" type="button" class="px-4 py-2 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-100">Close</button>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="fixed inset-0 bg-gray-900 bg-opacity-60 backdrop-blur-sm hidden items-start justify-center z-50 overflow-y-auto py-8">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-4xl mx-4 my-auto">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-semibold" id="modalTitle">Add New Medicine</h3>
                <button onclick="toggleProductModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="productForm" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Medicine Name</label>
                        <input type="text" name="productName" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter medicine name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Category</label>
                        <select name="category" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                            <option value="">Select category</option>
                            <option value="antibiotics">Antibiotics</option>
                            <option value="painRelievers">Pain Relievers</option>
                            <option value="vitamins">Vitamins & Supplements</option>
                            <option value="cardiovascular">Cardiovascular</option>
                            <option value="respiratory">Respiratory Medicines</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Quantity</label>
                        <input type="number" name="quantity" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter quantity">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Price</label>
                        <input type="number" name="price" step="0.01" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter price">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Expiry Date</label>
                        <input type="date" name="expiryDate" required 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-2">Manufacturer</label>
                        <input type="text" name="manufacturer" 
                            class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                            placeholder="Enter manufacturer name">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-600 mb-2">Description</label>
                    <textarea name="description" rows="3"
                        class="w-full px-4 py-3 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all outline-none"
                        placeholder="Enter medicine description"></textarea>
                </div>

                <div class="border-t border-gray-100 pt-6">
                    <div class="flex justify-end space-x-4">
                        <button id="autoFillMedicineBtn" type="button"
                            class="px-6 py-3 border border-gray-200 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                            Auto Fill
                        </button>
                        <button type="button" onclick="toggleProductModal()"
                            class="px-6 py-3 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all">
                            Save Medicine
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function escapeHtml(text) {
            const s = (text ?? '').toString();
            return s
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function debounce(fn, delay) {
            let t = null;
            return function () {
                const args = arguments;
                if (t) clearTimeout(t);
                t = setTimeout(function () { fn.apply(null, args); }, delay);
            };
        }

        function parseConsultNoteText(noteText) {
            const text = (noteText ?? '').toString();
            if (!text.trim()) return null;

            const lines = text.split(/\r?\n/);
            const t = (s) => (s ?? '').toString().trim();

            const out = {
                patientName: '',
                visitDate: '',
                dobAgeGender: '',
                hpiStart: '',
                hpiDuration: '',
                hpiSeverity: '',
                hpiFactors: '',
                hpiAssociated: '',
                pmhList: '',
                pmhOther: '',
                surgicalHistory: '',
                currentMedications: '',
                allergiesNone: false,
                allergiesOther: '',
                familyHistory: '',
                socialSmoking: '',
                socialAlcohol: '',
                socialOccupation: '',
                additionalNotes: '',
                patientSignature: '',
                patientSignatureDate: '',
                soapChiefComplaint: '',
                soapBp: '',
                soapPulse: '',
                soapTemp: '',
                soapExam: '',
                soapPrimaryDx: '',
                soapDifferentialDx: '',
                soapInvestigations: '',
                soapMedications: '',
                soapAdvice: '',
                soapFollowUp: '',
                soapDoctorSignature: '',
            };

            const takeUntil = (startIdx, stopFn) => {
                const buf = [];
                let i = startIdx;
                for (; i < lines.length; i++) {
                    const s = t(lines[i]);
                    if (stopFn(s)) break;
                    buf.push(lines[i]);
                }
                return { text: buf.join('\n').trim(), next: i };
            };

            for (let i = 0; i < lines.length; i++) {
                const s = t(lines[i]);
                let m;

                if ((m = s.match(/^Patient Name:\s*(.*)$/i))) { out.patientName = t(m[1]); continue; }
                if ((m = s.match(/^Date:\s*(.*)$/i))) { out.visitDate = t(m[1]); continue; }
                if ((m = s.match(/^Age\/Gender:\s*(.*)$/i))) { out.dobAgeGender = t(m[1]); continue; }

                if ((m = s.match(/^When did the problem start\?\s*(.*)$/i))) { out.hpiStart = t(m[1]); continue; }
                if ((m = s.match(/^Duration\/Frequency:\s*(.*)$/i))) { out.hpiDuration = t(m[1]); continue; }
                if ((m = s.match(/^Severity \(mild\/moderate\/severe\):\s*(.*)$/i))) { out.hpiSeverity = t(m[1]); continue; }
                if ((m = s.match(/^Aggravating\/Relieving factors:\s*(.*)$/i))) { out.hpiFactors = t(m[1]); continue; }
                if ((m = s.match(/^Associated Symptoms:\s*(.*)$/i))) { out.hpiAssociated = t(m[1]); continue; }

                if (s === 'Past Medical History') {
                    for (let j = i + 1; j < lines.length; j++) {
                        const nx = t(lines[j]);
                        if (!nx) continue;
                        if (/^Other:\s*/i.test(nx)) break;
                        if (nx === 'Surgical History (if any)' || nx === 'Current Medications' || nx === 'Allergies') break;
                        out.pmhList = nx;
                        break;
                    }
                    continue;
                }
                if ((m = s.match(/^Other:\s*(.*)$/i))) { out.pmhOther = t(m[1]); continue; }

                if (s === 'Surgical History (if any)') {
                    const block = takeUntil(i + 1, (x) => x === 'Current Medications');
                    out.surgicalHistory = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Current Medications') {
                    const block = takeUntil(i + 1, (x) => x === 'Allergies');
                    out.currentMedications = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Allergies') {
                    let next = '';
                    for (let j = i + 1; j < lines.length; j++) {
                        const nx = t(lines[j]);
                        if (!nx) continue;
                        next = nx;
                        break;
                    }
                    if (/^none$/i.test(next)) out.allergiesNone = true;
                    const mm = next.match(/^Drugs\/Food\/Other:\s*(.*)$/i);
                    if (mm) out.allergiesOther = t(mm[1]);
                    continue;
                }
                if (s === 'Family History (relevant conditions)') {
                    const block = takeUntil(i + 1, (x) => x === 'Social History');
                    out.familyHistory = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Smoking:\s*(.*)$/i))) { out.socialSmoking = t(m[1]); continue; }
                if ((m = s.match(/^Alcohol:\s*(.*)$/i))) { out.socialAlcohol = t(m[1]); continue; }
                if ((m = s.match(/^Occupation:\s*(.*)$/i))) { out.socialOccupation = t(m[1]); continue; }

                if (s === 'Additional Notes') {
                    const block = takeUntil(i + 1, (x) => /^Patient Signature:/i.test(x));
                    out.additionalNotes = block.text;
                    i = block.next - 1;
                    continue;
                }
                if ((m = s.match(/^Patient Signature:\s*(.*?)\s*Date:\s*(.*)$/i))) {
                    out.patientSignature = t(m[1]);
                    out.patientSignatureDate = t(m[2]);
                    continue;
                }

                if ((m = s.match(/^Chief Complaint:\s*(.*)$/i))) { out.soapChiefComplaint = t(m[1]); continue; }
                if ((m = s.match(/^Vital Signs:\s*BP:\s*(.*?)\s*Pulse:\s*(.*?)\s*Temp:\s*(.*)$/i))) {
                    out.soapBp = t(m[1]);
                    out.soapPulse = t(m[2]);
                    out.soapTemp = t(m[3]);
                    continue;
                }

                if (s === 'Physical Examination Findings:') {
                    const block = takeUntil(i + 1, (x) => x === 'A – Assessment');
                    out.soapExam = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Primary Diagnosis:\s*(.*)$/i))) { out.soapPrimaryDx = t(m[1]); continue; }
                if ((m = s.match(/^Differential Diagnosis \(if any\):\s*(.*)$/i))) { out.soapDifferentialDx = t(m[1]); continue; }

                if (s === 'Investigations Ordered:') {
                    const block = takeUntil(i + 1, (x) => x === 'Medications Prescribed:');
                    out.soapInvestigations = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Medications Prescribed:') {
                    const block = takeUntil(i + 1, (x) => x === 'Treatment/Advice:');
                    out.soapMedications = block.text;
                    i = block.next - 1;
                    continue;
                }
                if (s === 'Treatment/Advice:') {
                    const block = takeUntil(i + 1, (x) => /^Follow-up:/i.test(x));
                    out.soapAdvice = block.text;
                    i = block.next - 1;
                    continue;
                }

                if ((m = s.match(/^Follow-up:\s*(.*)$/i))) { out.soapFollowUp = t(m[1]); continue; }
                if ((m = s.match(/^Doctor’s Name & Signature:\s*(.*)$/i))) { out.soapDoctorSignature = t(m[1]); continue; }
            }

            const ok = !!out.patientName || !!out.soapChiefComplaint || !!out.visitDate;
            return ok ? out : null;
        }

        function renderConsultNoteFormReadOnly(d) {
            const esc = (s) => escapeHtml((s ?? '').toString());
            const norm = (s) => (s ?? '').toString().trim().toLowerCase();

            const pmh = (d.pmhList || '').toString().toLowerCase();
            const pmhDiabetes = pmh.includes('diabetes');
            const pmhHypertension = pmh.includes('hypertension');
            const pmhAsthma = pmh.includes('asthma');
            const pmhHeartDisease = pmh.includes('heart disease');

            const smokingYes = norm(d.socialSmoking) === 'yes';
            const smokingNo = norm(d.socialSmoking) === 'no';
            const alcoholYes = norm(d.socialAlcohol) === 'yes';
            const alcoholNo = norm(d.socialAlcohol) === 'no';

            return `
                <div class="space-y-4 w-full">
                    <div>
                        <div class="text-sm font-semibold text-gray-800">Doctor Consultation Form (Read-only)</div>
                        <div class="mt-1 text-xs text-gray-500">${esc(d.noteWhen || '')}${((d.noteWhen || '') && (d.noteDoctor || '')) ? ' • ' : ''}${esc(d.noteDoctor || '')}</div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Patient Information</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-gray-600">Full Name</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.patientName)}" />
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Date of Birth / Age / Gender</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.dobAgeGender)}" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Appointment Details</div>
                        <div class="mt-2">
                            <label class="block text-[11px] text-gray-600">Date of Visit</label>
                            <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.visitDate)}" />
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">History of Present Illness</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-gray-600">When did the problem start?</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.hpiStart)}" />
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Duration/Frequency</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.hpiDuration)}" />
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Severity (mild/moderate/severe)</label>
                                <select disabled class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">
                                    <option value="" ${!d.hpiSeverity ? 'selected' : ''}>Select</option>
                                    <option value="mild" ${norm(d.hpiSeverity) === 'mild' ? 'selected' : ''}>mild</option>
                                    <option value="moderate" ${norm(d.hpiSeverity) === 'moderate' ? 'selected' : ''}>moderate</option>
                                    <option value="severe" ${norm(d.hpiSeverity) === 'severe' ? 'selected' : ''}>severe</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Associated Symptoms</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.hpiAssociated)}" />
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="block text-[11px] text-gray-600">Aggravating/Relieving factors</label>
                            <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.hpiFactors)}</textarea>
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Past Medical History</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-2">
                            <label class="flex items-center gap-2 text-sm text-gray-800"><input disabled type="checkbox" class="h-4 w-4" ${pmhDiabetes ? 'checked' : ''}> Diabetes</label>
                            <label class="flex items-center gap-2 text-sm text-gray-800"><input disabled type="checkbox" class="h-4 w-4" ${pmhHypertension ? 'checked' : ''}> Hypertension</label>
                            <label class="flex items-center gap-2 text-sm text-gray-800"><input disabled type="checkbox" class="h-4 w-4" ${pmhAsthma ? 'checked' : ''}> Asthma</label>
                            <label class="flex items-center gap-2 text-sm text-gray-800"><input disabled type="checkbox" class="h-4 w-4" ${pmhHeartDisease ? 'checked' : ''}> Heart Disease</label>
                        </div>
                        <div class="mt-3">
                            <label class="block text-[11px] text-gray-600">Other</label>
                            <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.pmhOther)}" />
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Surgical History (if any)</div>
                        <textarea readonly rows="3" class="mt-2 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.surgicalHistory)}</textarea>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Current Medications</div>
                        <textarea readonly rows="3" class="mt-2 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.currentMedications)}</textarea>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Allergies</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3 items-end">
                            <label class="flex items-center gap-2 text-sm text-gray-800"><input disabled type="checkbox" class="h-4 w-4" ${d.allergiesNone ? 'checked' : ''}> None</label>
                            <div>
                                <label class="block text-[11px] text-gray-600">Drugs/Food/Other</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.allergiesOther)}" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Family History (relevant conditions)</div>
                        <textarea readonly rows="3" class="mt-2 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.familyHistory)}</textarea>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Social History</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <div class="text-[11px] text-gray-600">Smoking</div>
                                <div class="mt-1 flex items-center gap-4 text-sm text-gray-800">
                                    <label class="flex items-center gap-2"><input disabled type="radio" class="h-4 w-4" ${smokingYes ? 'checked' : ''}> Yes</label>
                                    <label class="flex items-center gap-2"><input disabled type="radio" class="h-4 w-4" ${smokingNo ? 'checked' : ''}> No</label>
                                </div>
                            </div>
                            <div>
                                <div class="text-[11px] text-gray-600">Alcohol</div>
                                <div class="mt-1 flex items-center gap-4 text-sm text-gray-800">
                                    <label class="flex items-center gap-2"><input disabled type="radio" class="h-4 w-4" ${alcoholYes ? 'checked' : ''}> Yes</label>
                                    <label class="flex items-center gap-2"><input disabled type="radio" class="h-4 w-4" ${alcoholNo ? 'checked' : ''}> No</label>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <label class="block text-[11px] text-gray-600">Occupation</label>
                            <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.socialOccupation)}" />
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Additional Notes</div>
                        <textarea readonly rows="3" class="mt-2 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.additionalNotes)}</textarea>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">Patient Signature</div>
                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[11px] text-gray-600">Signature</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.patientSignature)}" />
                            </div>
                            <div>
                                <label class="block text-[11px] text-gray-600">Date</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.patientSignatureDate)}" />
                            </div>
                        </div>
                    </div>

                    <div class="p-4 border border-gray-200 rounded-lg">
                        <div class="text-xs font-semibold text-gray-700">🩺 Doctor Consultation Note (SOAP Format)</div>

                        <div class="mt-3">
                            <div class="text-xs font-semibold text-gray-700">S – Subjective</div>
                            <div class="mt-2">
                                <label class="block text-[11px] text-gray-600">Chief Complaint</label>
                                <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapChiefComplaint)}" />
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">O – Objective</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-3 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">BP</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapBp)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Pulse</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapPulse)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Temp</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapTemp)}" />
                                </div>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Physical Examination Findings</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapExam)}</textarea>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">A – Assessment</div>
                            <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">Primary Diagnosis</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapPrimaryDx)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Differential Diagnosis (if any)</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapDifferentialDx)}" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <div class="text-xs font-semibold text-gray-700">P – Plan</div>
                            <div class="mt-2">
                                <label class="block text-[11px] text-gray-600">Investigations Ordered</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapInvestigations)}</textarea>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Medications Prescribed</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapMedications)}</textarea>
                            </div>
                            <div class="mt-3">
                                <label class="block text-[11px] text-gray-600">Treatment/Advice</label>
                                <textarea readonly rows="3" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700">${esc(d.soapAdvice)}</textarea>
                            </div>
                            <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] text-gray-600">Follow-up</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapFollowUp)}" />
                                </div>
                                <div>
                                    <label class="block text-[11px] text-gray-600">Doctor’s Name & Signature</label>
                                    <input readonly type="text" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg bg-gray-50 text-gray-700" value="${esc(d.soapDoctorSignature)}" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        let pharmacyResitPatientsLoaded = false;
        let pharmacyResitSelectedPatient = null;
        let pharmacyConsultNoteSeq = 0;
        let pharmacyResitConsultMedsSeq = 0;

        function toggleResitModal(forceOpen) {
            const el = document.getElementById('pharmacyResitModal');
            if (!el) return;
            const open = !el.classList.contains('hidden');
            const wantOpen = (forceOpen === undefined) ? !open : !!forceOpen;
            if (wantOpen) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                try { document.body.style.overflow = 'hidden'; } catch (e) {}
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                try { document.body.style.overflow = ''; } catch (e) {}
            }
        }

        function resetResitForm() {
            pharmacyResitSelectedPatient = null;
            setResitConsultMeds('', '');
            const itemsEl = document.getElementById('pharmacyResitItems');
            if (itemsEl) itemsEl.innerHTML = '';
            const modal = document.getElementById('pharmacyResitModal');
            if (modal) {
                delete modal.dataset.editResitId;
                delete modal.dataset.editPatientId;
                const titleEl = modal.querySelector('h3');
                if (titleEl) titleEl.textContent = 'Receipt';
            }
        }

        async function selectResitPatient(p) {
            pharmacyResitSelectedPatient = p;
            const modal = document.getElementById('pharmacyResitModal');
            if (modal) {
                delete modal.dataset.editResitId;
                modal.dataset.editPatientId = String(p && p.id ? p.id : '');
                const titleEl = modal.querySelector('h3');
                if (titleEl) titleEl.textContent = 'Receipt';
            }

            toggleResitModal(true);

            const pid = Number(p && p.id ? p.id : 0);
            if (pid > 0) {
                loadResitConsultMeds(pid);
            } else {
                setResitConsultMeds('No notes yet', '');
            }

            const itemsEl = document.getElementById('pharmacyResitItems');
            if (itemsEl) {
                itemsEl.innerHTML = ([{}]).map(renderResitItemRow).join('');
                itemsEl.querySelectorAll('[data-action="remove"]').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const row = btn.closest('[data-resit-row="1"]');
                        if (row) row.remove();
                    });
                });
                itemsEl.querySelectorAll('[data-resit-row="1"]').forEach(row => {
                    attachResitMedicineAutocomplete(row);
                });
            }
        }

        function setResitConsultMeds(text, meta) {
            const el = document.getElementById('pharmacyResitConsultMeds');
            const metaEl = document.getElementById('pharmacyResitConsultMedsMeta');
            if (el) el.value = (text || '').toString();
            if (metaEl) metaEl.textContent = (meta || '').toString();
        }

        async function loadResitConsultMeds(patientId) {
            const mySeq = ++pharmacyResitConsultMedsSeq;
            setResitConsultMeds('Loading...', '');
            try {
                const res = await fetch('api/pharmacy/latest_consult_note.php?patient_id=' + encodeURIComponent(String(patientId)), { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (mySeq !== pharmacyResitConsultMedsSeq) return;

                if (!res.ok || !json || !json.ok || !json.note) {
                    setResitConsultMeds('No notes yet', '');
                    return;
                }

                const n = json.note;
                const noteText = (n.note_text || '').toString();
                if (!noteText.trim()) {
                    setResitConsultMeds('No notes yet', '');
                    return;
                }

                const parsed = parseConsultNoteText(noteText);
                const meds = (parsed && parsed.soapMedications) ? String(parsed.soapMedications) : '';
                const textOut = meds.trim() ? meds : noteText;

                const parts = [];
                if (n.source) parts.push(String(n.source));
                if (n.provider_name) parts.push(String(n.provider_name));
                if (n.created_at) parts.push(String(n.created_at));
                setResitConsultMeds(textOut, parts.join(' · '));
            } catch (e) {
                if (mySeq !== pharmacyResitConsultMedsSeq) return;
                setResitConsultMeds('No notes yet', '');
            }
        }

        async function loadResitPatients(query) {
            const tbody = document.getElementById('pharmacyResitPatientsTbody');
            const empty = document.getElementById('pharmacyResitPatientsEmpty');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-sm text-gray-500">Loading...</td></tr>';
            if (empty) empty.classList.add('hidden');

            const q = (query || '').toString().trim();
            const url = 'api/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');

            try {
                const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-sm text-red-600">Unable to load patients.</td></tr>';
                    return;
                }

                const rows = Array.isArray(json.patients) ? json.patients : [];
                if (!rows.length) {
                    tbody.innerHTML = '';
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                if (empty) empty.classList.add('hidden');
                tbody.innerHTML = rows.map(p => {
                    const id = Number(p.id);
                    const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                    const name = escapeHtml(p.full_name || '');
                    const sex = escapeHtml(p.sex || '');
                    const dob = escapeHtml(p.dob || '');
                    const payload = escapeHtml(JSON.stringify(p));
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">${code}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${sex}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${dob}</td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" class="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800" data-action="select" data-patient="${payload}">Input Receipt</button>
                            </td>
                        </tr>
                    `;
                }).join('');

                tbody.querySelectorAll('button[data-action="select"]').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const raw = (btn.getAttribute('data-patient') || '').toString();
                        let p = null;
                        try { p = JSON.parse(raw); } catch (e) { p = null; }
                        if (!p) return;
                        selectResitPatient(p);
                    });
                });

                pharmacyResitPatientsLoaded = true;
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-4 py-6 text-sm text-red-600">Unable to load patients.</td></tr>';
            }
        }

        async function loadPharmacyResitInfo() {
            const tbody = document.getElementById('pharmacyResitInfoTbody');
            const empty = document.getElementById('pharmacyResitInfoEmpty');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-sm text-gray-500">Loading...</td></tr>';
            if (empty) empty.classList.add('hidden');

            try {
                const res = await fetch('api/pharmacy/list_resits_all.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    throw new Error((json && json.error) ? json.error : 'Unable to load receipts');
                }
                const rows = Array.isArray(json.resits) ? json.resits : [];
                if (!rows.length) {
                    tbody.innerHTML = '';
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                tbody.innerHTML = rows.map(r => {
                    const id = Number(r.id || 0);
                    const code = escapeHtml(r.patient_code || '');
                    const name = escapeHtml(r.full_name || '');
                    const prescribedBy = escapeHtml(r.prescribed_by || '');
                    const submittedAt = escapeHtml(r.submitted_at || '');
                    const items = Array.isArray(r.items) ? r.items : [];
                    const itemsSummary = items.map(it => `${escapeHtml(it.name || '')} (${escapeHtml(it.qty || '')})`).join(', ');
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">${code}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${prescribedBy}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${submittedAt}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate" title="${itemsSummary}">${itemsSummary}</td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" data-action="edit-resit" data-id="${id}">Edit</button>
                            </td>
                        </tr>
                    `;
                }).join('');

                tbody.querySelectorAll('button[data-action="edit-resit"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const idRaw = (btn.getAttribute('data-id') || '').toString();
                        if (!idRaw || !/^[0-9]+$/.test(idRaw)) return;
                        const id = Number(idRaw);
                        btn.disabled = true;
                        const old = btn.textContent;
                        btn.textContent = 'Loading...';
                        try {
                            const res = await fetch('api/pharmacy/get_resit.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                            const json = await res.json().catch(() => null);
                            if (!res.ok || !json || !json.ok || !json.resit) {
                                throw new Error((json && json.error) ? json.error : 'Unable to load receipt');
                            }
                            const r = json.resit;
                            openEditResitModal(r);
                        } catch (e) {
                            const msg = (e && e.message) ? String(e.message) : 'Unable to load receipt';
                            alert(msg);
                        } finally {
                            btn.disabled = false;
                            btn.textContent = old;
                        }
                    });
                });
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-sm text-red-600">Unable to load receipts.</td></tr>';
            }
        }

        function filterResits(rows, query) {
            const q = (query || '').toString().trim().toLowerCase();
            if (!q) return rows;
            return rows.filter(r => {
                const items = Array.isArray(r.items) ? r.items : [];
                const itemsText = items.map(it => `${(it.name || '').toString()} ${(it.qty || '').toString()} ${(it.sig || '').toString()}`.trim()).join(' | ');
                const hay = [
                    (r.patient_code || '').toString(),
                    (r.full_name || '').toString(),
                    (r.prescribed_by || '').toString(),
                    (r.submitted_at || '').toString(),
                    itemsText,
                ].join(' ').toLowerCase();
                return hay.includes(q);
            });
        }

        async function loadPharmacyResitInfoWithSearch(query) {
            const tbody = document.getElementById('pharmacyResitInfoTbody');
            const empty = document.getElementById('pharmacyResitInfoEmpty');
            if (!tbody) return;
            try {
                const res = await fetch('api/pharmacy/list_resits_all.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    throw new Error((json && json.error) ? json.error : 'Unable to load receipts');
                }
                const all = Array.isArray(json.resits) ? json.resits : [];
                const rows = filterResits(all, query);
                if (!rows.length) {
                    tbody.innerHTML = '';
                    if (empty) empty.classList.remove('hidden');
                    return;
                }
                if (empty) empty.classList.add('hidden');
                tbody.innerHTML = rows.map(r => {
                    const id = Number(r.id || 0);
                    const code = escapeHtml(r.patient_code || '');
                    const name = escapeHtml(r.full_name || '');
                    const prescribedBy = escapeHtml(r.prescribed_by || '');
                    const submittedAt = escapeHtml(r.submitted_at || '');
                    const items = Array.isArray(r.items) ? r.items : [];
                    const itemsSummary = items.map(it => `${escapeHtml(it.name || '')} (${escapeHtml(it.qty || '')})`).join(', ');
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">${code}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${prescribedBy}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${submittedAt}</td>
                            <td class="px-4 py-3 text-sm text-gray-700 max-w-xs truncate" title="${itemsSummary}">${itemsSummary}</td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" data-action="edit-resit" data-id="${id}">Edit</button>
                            </td>
                        </tr>
                    `;
                }).join('');

                tbody.querySelectorAll('button[data-action="edit-resit"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const idRaw = (btn.getAttribute('data-id') || '').toString();
                        if (!idRaw || !/^[0-9]+$/.test(idRaw)) return;
                        const id = Number(idRaw);
                        btn.disabled = true;
                        const old = btn.textContent;
                        btn.textContent = 'Loading...';
                        try {
                            const res2 = await fetch('api/pharmacy/get_resit.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                            const json2 = await res2.json().catch(() => null);
                            if (!res2.ok || !json2 || !json2.ok || !json2.resit) {
                                throw new Error((json2 && json2.error) ? json2.error : 'Unable to load receipt');
                            }
                            openEditResitModal(json2.resit);
                        } catch (e) {
                            const msg = (e && e.message) ? String(e.message) : 'Unable to load receipt';
                            alert(msg);
                        } finally {
                            btn.disabled = false;
                            btn.textContent = old;
                        }
                    });
                });
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-sm text-red-600">Unable to load receipts.</td></tr>';
            }
        }

        function openEditResitModal(resit) {
            // Populate modal header with patient info
            const modal = document.getElementById('pharmacyResitModal');
            if (!modal) return;

            // Update title to indicate edit mode
            const titleEl = modal.querySelector('h3');
            if (titleEl) titleEl.textContent = 'Edit Receipt';

            // Show patient info in consult meds panel
            const metaParts = [];
            if (resit.patient_code || resit.full_name) metaParts.push(`${(resit.patient_code || '').toString()} ${(resit.full_name || '').toString()}`.trim());
            if (resit.prescribed_by) metaParts.push((resit.prescribed_by || '').toString());
            if (resit.submitted_at) metaParts.push((resit.submitted_at || '').toString());
            setResitConsultMeds('Editing submitted receipt', metaParts.join(' · '));

            // Populate editable items
            const itemsContainer = document.getElementById('pharmacyResitItems');
            if (itemsContainer && Array.isArray(resit.items)) {
                itemsContainer.innerHTML = resit.items.map(renderResitItemRow).join('');
                // Attach autocomplete and remove handlers
                itemsContainer.querySelectorAll('[data-action="remove"]').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const row = btn.closest('[data-resit-row="1"]');
                        if (row) row.remove();
                    });
                });
                itemsContainer.querySelectorAll('[data-resit-row="1"]').forEach(row => {
                    attachResitMedicineAutocomplete(row);
                });
            }

            // Store editing context
            modal.dataset.editResitId = String(resit.id || '');
            modal.dataset.editPatientId = String(resit.patient_id || '');

            // Show modal
            toggleResitModal(true);
        }

        function toggleReceiptModal(forceOpen) {
            const el = document.getElementById('pharmacyReceiptModal');
            if (!el) return;
            const open = !el.classList.contains('hidden');
            const wantOpen = (forceOpen === undefined) ? !open : !!forceOpen;
            if (wantOpen) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                try { document.body.style.overflow = 'hidden'; } catch (e) {}
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                try { document.body.style.overflow = ''; } catch (e) {}
                // Reset title and edit context
                const titleEl = el.querySelector('h3');
                if (titleEl) titleEl.textContent = 'Receipt';
                delete el.dataset.editReceiptId;
                delete el.dataset.editPatientId;
            }
        }

        function toggleConsultNoteModal(forceOpen) {
            const el = document.getElementById('pharmacyConsultNoteModal');
            if (!el) return;
            const open = !el.classList.contains('hidden');
            const wantOpen = (forceOpen === undefined) ? !open : !!forceOpen;
            if (wantOpen) {
                el.classList.remove('hidden');
                el.classList.add('flex');
                try { document.body.style.overflow = 'hidden'; } catch (e) {}
            } else {
                el.classList.add('hidden');
                el.classList.remove('flex');
                try { document.body.style.overflow = ''; } catch (e) {}
            }
        }

        async function loadPharmacyConsultNotes() {
            const tbody = document.getElementById('pharmacyConsultNotesTbody');
            const empty = document.getElementById('pharmacyConsultNotesEmpty');
            if (!tbody) return;
            tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-6 text-sm text-gray-500">Loading...</td></tr>';
            if (empty) empty.classList.add('hidden');

            try {
                const res = await fetch('api/pharmacy/list_consult_notes.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) {
                    throw new Error((json && json.error) ? json.error : 'Unable to load notes');
                }
                const rows = Array.isArray(json.notes) ? json.notes : [];
                if (!rows.length) {
                    tbody.innerHTML = '';
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                tbody.innerHTML = rows.map(r => {
                    const id = Number(r.id || 0);
                    const code = escapeHtml(r.patient_code || '');
                    const name = escapeHtml(r.full_name || '');
                    const src = escapeHtml(r.source_module || '');
                    const provider = escapeHtml(r.provider_name || '');
                    const noteAt = escapeHtml(r.note_created_at || '');
                    const submittedAt = escapeHtml(r.submitted_at || '');
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm text-gray-700">${code}</td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${src}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${provider}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${noteAt}</td>
                            <td class="px-4 py-3 text-sm text-gray-700">${submittedAt}</td>
                            <td class="px-4 py-3 text-right">
                                <button type="button" class="px-3 py-1.5 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" data-action="view-consult" data-id="${id}">View</button>
                            </td>
                        </tr>
                    `;
                }).join('');

                tbody.querySelectorAll('button[data-action="view-consult"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const idRaw = (btn.getAttribute('data-id') || '').toString();
                        if (!idRaw || !/^[0-9]+$/.test(idRaw)) return;
                        const id = Number(idRaw);
                        btn.disabled = true;
                        const old = btn.textContent;
                        btn.textContent = 'Loading...';
                        try {
                            const res = await fetch('api/pharmacy/get_consult_note.php?id=' + encodeURIComponent(String(id)), { headers: { 'Accept': 'application/json' } });
                            const json = await res.json().catch(() => null);
                            if (!res.ok || !json || !json.ok || !json.note) {
                                throw new Error((json && json.error) ? json.error : 'Unable to load note');
                            }
                            const n = json.note;
                            const metaEl = document.getElementById('pharmacyConsultNoteModalMeta');
                            const bodyEl = document.getElementById('pharmacyConsultNoteModalBody');
                            const metaParts = [];
                            if (n.patient_code || n.full_name) metaParts.push(`${(n.patient_code || '').toString()} ${(n.full_name || '').toString()}`.trim());
                            if (n.source_module) metaParts.push((n.source_module || '').toString());
                            if (n.provider_name) metaParts.push((n.provider_name || '').toString());
                            if (n.note_created_at) metaParts.push((n.note_created_at || '').toString());
                            if (metaEl) metaEl.textContent = metaParts.filter(Boolean).join(' • ');

                            const noteText = (n.note_text || '').toString();
                            let noteWhen = '';
                            try {
                                if (n.note_created_at) noteWhen = new Date(n.note_created_at).toLocaleString();
                            } catch (e) {
                                noteWhen = (n.note_created_at || '').toString();
                            }
                            const noteDoctor = (n.provider_name || '').toString();
                            const parsed = parseConsultNoteText(noteText);
                            if (bodyEl) {
                                if (parsed) bodyEl.innerHTML = renderConsultNoteFormReadOnly({ ...parsed, noteWhen, noteDoctor });
                                else bodyEl.innerHTML = '<pre class="whitespace-pre-wrap">' + escapeHtml(noteText) + '</pre>';
                            }
                            toggleConsultNoteModal(true);
                        } catch (e) {
                            alert(e && e.message ? e.message : 'Unable to load note');
                        } finally {
                            btn.disabled = false;
                            btn.textContent = old;
                        }
                    });
                });
            } catch (e) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-6 text-sm text-red-600">Unable to load notes.</td></tr>';
            }
        }

        function resetReceiptForm() {
            pharmacyReceiptSelectedPatient = null;
            setReceiptConsultMeds('', '');
            const itemsEl = document.getElementById('pharmacyReceiptItems');
            if (itemsEl) itemsEl.innerHTML = '';
        }

        function collectResitForm() {
            const doctor = '';
            const notes = '';
            const itemsRoot = document.getElementById('pharmacyResitItems');
            const rows = itemsRoot ? Array.from(itemsRoot.querySelectorAll('[data-resit-row="1"]')) : [];
            const items = rows.map(r => {
                const nameEl = r.querySelector('[data-field="name"]');
                const name = (nameEl?.value || '').toString();
                const medicine_id = (nameEl && nameEl.dataset && nameEl.dataset.medicineId) ? nameEl.dataset.medicineId : '';
                const qty = (r.querySelector('[data-field="qty"]')?.value || '').toString();
                const sig = (r.querySelector('[data-field="sig"]')?.value || '').toString();
                return { name, medicine_id, qty, sig };
            }).filter(x => (x.name || '').trim() !== '' || (x.qty || '').trim() !== '' || (x.sig || '').trim() !== '');
            return { doctor, notes, items };
        }

        function renderResitItemRow(item) {
            const name = escapeHtml(item?.name || '');
            const medicineId = (item && item.medicine_id !== undefined && item.medicine_id !== null && String(item.medicine_id).trim() !== '')
                ? escapeHtml(String(item.medicine_id))
                : '';
            const qty = escapeHtml(item?.qty || '');
            const sig = escapeHtml(item?.sig || '');

            return `
                <div data-resit-row="1" class="bg-white border border-gray-200 rounded-xl p-3">
                    <div class="grid grid-cols-12 gap-2 items-start">
                        <div class="col-span-7 relative">
                            <div class="text-[11px] font-semibold text-gray-600">Medicine</div>
                            <input data-field="name" type="text" value="${name}" ${medicineId ? `data-medicine-id="${medicineId}"` : ''} autocomplete="off" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <div data-medicine-suggest="1" class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-auto hidden z-20"></div>
                        </div>
                        <div class="col-span-3">
                            <div class="text-[11px] font-semibold text-gray-600">Qty</div>
                            <input data-field="qty" type="text" value="${qty}" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <button type="button" data-action="remove" class="mt-5 px-2 py-2 text-gray-500 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="col-span-12">
                            <div class="text-[11px] font-semibold text-gray-600">Instructions</div>
                            <input data-field="sig" type="text" value="${sig}" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>
            `;
        }

        function attachResitMedicineAutocomplete(rowEl) {
            if (!rowEl) return;
            const input = rowEl.querySelector('input[data-field="name"]');
            const box = rowEl.querySelector('[data-medicine-suggest="1"]');
            if (!input || !box) return;
            if (input.dataset.autocompleteBound === '1') return;
            input.dataset.autocompleteBound = '1';

            let seq = 0;
            let debounceT = null;

            function hideBoxSoon() {
                setTimeout(function () {
                    box.classList.add('hidden');
                    box.innerHTML = '';
                }, 150);
            }

            function renderMessage(msg) {
                box.innerHTML = `<div class="px-3 py-2 text-sm text-gray-500">${escapeHtml(msg)}</div>`;
                box.classList.remove('hidden');
            }

            function renderList(meds) {
                const inStock = meds.filter(m => Number(m.quantity || 0) > 0);
                if (!meds.length) {
                    renderMessage('No result');
                    return;
                }
                if (!inStock.length) {
                    renderMessage('Out of stock');
                    return;
                }

                box.innerHTML = meds.map(m => {
                    const id = Number(m.id || 0);
                    const name = escapeHtml(m.name || '');
                    const cat = escapeHtml(m.category || '');
                    const qty = Number(m.quantity || 0);
                    const disabled = qty <= 0;
                    return `
                        <button type="button" data-med-select="1" data-med-id="${id}" data-med-name="${name}" ${disabled ? 'disabled' : ''} class="w-full text-left px-3 py-2 hover:bg-gray-50 ${disabled ? 'opacity-50 cursor-not-allowed' : ''}">
                            <div class="text-sm font-medium text-gray-900">${name}</div>
                            <div class="text-xs text-gray-500">${cat ? cat + ' · ' : ''}Qty: ${qty}</div>
                        </button>
                    `;
                }).join('');
                box.classList.remove('hidden');

                box.querySelectorAll('button[data-med-select="1"]:not([disabled])').forEach(btn => {
                    btn.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                    });
                    btn.addEventListener('click', function () {
                        const medName = (btn.getAttribute('data-med-name') || '').toString();
                        const medId = (btn.getAttribute('data-med-id') || '').toString();
                        input.value = medName;
                        input.dataset.medicineId = medId;
                        box.classList.add('hidden');
                        box.innerHTML = '';
                        input.focus();
                    });
                });
            }

            async function doFetch() {
                const q = input.value.toString().trim();
                const mySeq = ++seq;
                if (!q) {
                    box.classList.add('hidden');
                    box.innerHTML = '';
                    return;
                }
                renderMessage('Searching...');
                try {
                    const res = await fetch('api/pharmacy/list_medicines.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (mySeq !== seq) return;
                    if (!res.ok || !json || !json.ok) {
                        renderMessage((json && json.error) ? json.error : 'Unable to load medicines');
                        return;
                    }
                    const meds = Array.isArray(json.medicines) ? json.medicines : [];
                    renderList(meds);
                } catch (e) {
                    if (mySeq !== seq) return;
                    renderMessage('Unable to load medicines');
                }
            }

            input.addEventListener('input', function () {
                input.dataset.medicineId = '';
                if (debounceT) clearTimeout(debounceT);
                debounceT = setTimeout(doFetch, 250);
            });

            input.addEventListener('blur', hideBoxSoon);
            input.addEventListener('focus', function () {
                if (input.value.toString().trim() !== '') {
                    if (debounceT) clearTimeout(debounceT);
                    debounceT = setTimeout(doFetch, 50);
                }
            });
        }

        function calcAgeFromDob(dob) {
            if (!dob || typeof dob !== 'string') return '';
            const m = dob.match(/^(\d{4})-(\d{2})-(\d{2})/);
            if (!m) return '';
            const y = parseInt(m[1], 10);
            const mo = parseInt(m[2], 10) - 1;
            const d = parseInt(m[3], 10);
            const birth = new Date(y, mo, d);
            if (isNaN(birth.getTime())) return '';
            const today = new Date();
            let age = today.getFullYear() - birth.getFullYear();
            const mDiff = today.getMonth() - birth.getMonth();
            if (mDiff < 0 || (mDiff === 0 && today.getDate() < birth.getDate())) age--;
            if (age < 0 || age > 150) return '';
            return String(age);
        }

        function setConsultPatientInfo(p) {
            const consultPatientNameEl = document.getElementById('pharmacyConsultPatientName');
            const consultPatientDobAgeEl = document.getElementById('pharmacyConsultPatientDobAge');
            const consultPatientGenderEl = document.getElementById('pharmacyConsultPatientGender');

            const fullName = (p && (p.full_name || p.name || p.patient_name)) ? String(p.full_name || p.name || p.patient_name) : '';
            const dob = (p && (p.dob || p.birth_date || p.date_of_birth)) ? String(p.dob || p.birth_date || p.date_of_birth) : '';
            const sex = (p && (p.sex || p.gender)) ? String(p.sex || p.gender) : '';

            const age = calcAgeFromDob(dob);
            const dobAge = dob ? (age ? (dob + ' / ' + age) : dob) : (age ? age : '');

            if (consultPatientNameEl) consultPatientNameEl.value = fullName;
            if (consultPatientDobAgeEl) consultPatientDobAgeEl.value = dobAge;
            if (consultPatientGenderEl) consultPatientGenderEl.value = sex;
        }

        async function loadLatestConsultNote(patientId) {
            const mySeq = ++pharmacyConsultNoteSeq;
            const consultNoteEl = document.getElementById('pharmacyConsultNoteText');
            const consultMetaEl = document.getElementById('pharmacyConsultNoteMeta');
            const consultSourceEl = document.getElementById('pharmacyConsultNoteSource');
            const consultVisitDateEl = document.getElementById('pharmacyConsultVisitDate');
            if (consultNoteEl) consultNoteEl.value = 'Loading...';
            if (consultMetaEl) consultMetaEl.textContent = '';

            const srcSel = consultSourceEl ? (consultSourceEl.value || '').toString().trim().toUpperCase() : '';
            const srcParam = (srcSel === 'ER' || srcSel === 'OPD') ? ('&source=' + encodeURIComponent(srcSel)) : '';

            try {
                const res = await fetch('api/pharmacy/latest_consult_note.php?patient_id=' + encodeURIComponent(String(patientId)) + srcParam, { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (mySeq !== pharmacyConsultNoteSeq) return;
                if (!res.ok || !json || !json.ok) {
                    if (consultNoteEl) consultNoteEl.value = 'No notes yet';
                    if (consultMetaEl) consultMetaEl.textContent = '';
                    return;
                }
                const n = json.note || null;
                if (!n || !n.note_text) {
                    if (consultNoteEl) consultNoteEl.value = 'No notes yet';
                    if (consultMetaEl) consultMetaEl.textContent = '';
                    return;
                }
                const src = (n.source || '').toString();
                const by = (n.provider_name || '').toString();
                const at = (n.created_at || '').toString();
                if (consultNoteEl) consultNoteEl.value = (n.note_text || '').toString();
                if (consultVisitDateEl && !consultVisitDateEl.value) consultVisitDateEl.value = at;
                if (consultMetaEl) {
                    const parts = [];
                    if (src) parts.push(src);
                    if (by) parts.push(by);
                    if (at) parts.push(at);
                    consultMetaEl.textContent = parts.join(' · ');
                }
            } catch (e) {
                if (mySeq !== pharmacyConsultNoteSeq) return;
                if (consultNoteEl) consultNoteEl.value = 'No notes yet';
                if (consultMetaEl) consultMetaEl.textContent = '';
            }
        }

        function collectReceiptForm() {
            const doctor = '';
            const notes = '';
            const itemsRoot = document.getElementById('pharmacyReceiptItems');
            const rows = itemsRoot ? Array.from(itemsRoot.querySelectorAll('[data-receipt-row="1"]')) : [];
            const items = rows.map(r => {
                const nameEl = r.querySelector('[data-field="name"]');
                const name = (nameEl?.value || '').toString();
                const medicine_id = (nameEl && nameEl.dataset && nameEl.dataset.medicineId) ? nameEl.dataset.medicineId : '';
                const qty = (r.querySelector('[data-field="qty"]')?.value || '').toString();
                const sig = (r.querySelector('[data-field="sig"]')?.value || '').toString();
                return { name, medicine_id, qty, sig };
            }).filter(x => (x.name || '').trim() !== '' || (x.qty || '').trim() !== '' || (x.sig || '').trim() !== '');
            return { doctor, notes, items };
        }

        function renderReceiptItemRow(item) {
            const name = escapeHtml(item?.name || '');
            const medicineId = (item && item.medicine_id !== undefined && item.medicine_id !== null && String(item.medicine_id).trim() !== '')
                ? escapeHtml(String(item.medicine_id))
                : '';
            const qty = escapeHtml(item?.qty || '');
            const sig = escapeHtml(item?.sig || '');

            return `
                <div data-receipt-row="1" class="bg-white border border-gray-200 rounded-xl p-3">
                    <div class="grid grid-cols-12 gap-2 items-start">
                        <div class="col-span-7 relative">
                            <div class="text-[11px] font-semibold text-gray-600">Medicine</div>
                            <input data-field="name" type="text" value="${name}" ${medicineId ? `data-medicine-id="${medicineId}"` : ''} autocomplete="off" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            <div data-medicine-suggest="1" class="absolute left-0 right-0 mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-56 overflow-auto hidden z-20"></div>
                        </div>
                        <div class="col-span-3">
                            <div class="text-[11px] font-semibold text-gray-600">Qty</div>
                            <input data-field="qty" type="text" value="${qty}" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                        <div class="col-span-2 flex justify-end">
                            <button type="button" data-action="remove" class="mt-5 px-2 py-2 text-gray-500 hover:text-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <div class="col-span-12">
                            <div class="text-[11px] font-semibold text-gray-600">Instructions</div>
                            <input data-field="sig" type="text" value="${sig}" class="mt-1 w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>
                    </div>
                </div>
            `;
        }

        function attachReceiptMedicineAutocomplete(rowEl) {
            if (!rowEl) return;
            const input = rowEl.querySelector('input[data-field="name"]');
            const box = rowEl.querySelector('[data-medicine-suggest="1"]');
            if (!input || !box) return;
            if (input.dataset.autocompleteBound === '1') return;
            input.dataset.autocompleteBound = '1';

            let seq = 0;
            let debounceT = null;

            function hideBoxSoon() {
                setTimeout(function () {
                    box.classList.add('hidden');
                    box.innerHTML = '';
                }, 150);
            }

            function renderMessage(msg) {
                box.innerHTML = `<div class="px-3 py-2 text-sm text-gray-500">${escapeHtml(msg)}</div>`;
                box.classList.remove('hidden');
            }

            function renderList(meds) {
                const inStock = meds.filter(m => Number(m.quantity || 0) > 0);
                if (!meds.length) {
                    renderMessage('No result');
                    return;
                }
                if (!inStock.length) {
                    renderMessage('Out of stock');
                    return;
                }

                box.innerHTML = meds.map(m => {
                    const id = Number(m.id || 0);
                    const name = escapeHtml(m.name || '');
                    const cat = escapeHtml(m.category || '');
                    const qty = Number(m.quantity || 0);
                    const disabled = qty <= 0;
                    return `
                        <button type="button" data-med-select="1" data-med-id="${id}" data-med-name="${name}" ${disabled ? 'disabled' : ''} class="w-full text-left px-3 py-2 hover:bg-gray-50 ${disabled ? 'opacity-50 cursor-not-allowed' : ''}">
                            <div class="text-sm font-medium text-gray-900">${name}</div>
                            <div class="text-xs text-gray-500">${cat ? cat + ' · ' : ''}Qty: ${qty}</div>
                        </button>
                    `;
                }).join('');
                box.classList.remove('hidden');

                box.querySelectorAll('button[data-med-select="1"]:not([disabled])').forEach(btn => {
                    btn.addEventListener('mousedown', function (e) {
                        e.preventDefault();
                    });
                    btn.addEventListener('click', function () {
                        const medName = (btn.getAttribute('data-med-name') || '').toString();
                        const medId = (btn.getAttribute('data-med-id') || '').toString();
                        input.value = medName;
                        input.dataset.medicineId = medId;
                        box.classList.add('hidden');
                        box.innerHTML = '';
                        input.focus();
                    });
                });
            }

            async function doFetch() {
                const q = (input.value || '').toString().trim();
                const mySeq = ++seq;
                if (!q) {
                    box.classList.add('hidden');
                    box.innerHTML = '';
                    return;
                }

                box.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">Searching...</div>';
                box.classList.remove('hidden');

                try {
                    const res = await fetch('api/pharmacy/list_medicines.php?q=' + encodeURIComponent(q), { headers: { 'Accept': 'application/json' } });
                    const json = await res.json().catch(() => null);
                    if (mySeq !== seq) return;
                    if (!res.ok || !json || !json.ok) {
                        renderMessage('No result');
                        return;
                    }
                    const meds = Array.isArray(json.medicines) ? json.medicines : [];
                    renderList(meds);
                } catch (e) {
                    if (mySeq !== seq) return;
                    renderMessage('No result');
                }
            }

            input.addEventListener('input', function () {
                try { delete input.dataset.medicineId; } catch (e0) { }
                if (debounceT) clearTimeout(debounceT);
                debounceT = setTimeout(doFetch, 250);
            });
            input.addEventListener('focus', function () {
                if ((input.value || '').toString().trim() !== '') {
                    doFetch();
                }
            });
            input.addEventListener('blur', hideBoxSoon);
        }

        function ensureReceiptFormVisible() {
            toggleReceiptModal(true);
        }

        async function selectReceiptPatient(p) {
            pharmacyReceiptSelectedPatient = p;
            ensureReceiptFormVisible();

            const pid = Number(p && p.id ? p.id : 0);
            if (pid > 0) {
                loadReceiptConsultMeds(pid);
            } else {
                setReceiptConsultMeds('No notes yet', '');
            }

            const itemsEl = document.getElementById('pharmacyReceiptItems');
            if (itemsEl) {
                itemsEl.innerHTML = ([{}]).map(renderReceiptItemRow).join('');
                itemsEl.querySelectorAll('[data-action="remove"]').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const row = btn.closest('[data-receipt-row="1"]');
                        if (row) row.remove();
                    });
                });

                itemsEl.querySelectorAll('[data-receipt-row="1"]').forEach(row => {
                    attachReceiptMedicineAutocomplete(row);
                });
            }
        }

        async function loadReceiptPatients(query) {
            const tbody = document.getElementById('pharmacyReceiptPatientsTbody');
            const empty = document.getElementById('pharmacyReceiptPatientsEmpty');
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-sm text-gray-500">Loading...</td></tr>';

            const q = (query || '').toString().trim();
            const url = 'api/patients/list.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');

            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-6 text-sm text-red-600">Unable to load patients.</td></tr>';
                if (empty) empty.classList.add('hidden');
                return;
            }

            const rows = Array.isArray(json.patients) ? json.patients : [];
            if (!rows.length) {
                tbody.innerHTML = '';
                if (empty) empty.classList.remove('hidden');
                return;
            }

            if (empty) empty.classList.add('hidden');
            tbody.innerHTML = rows.map(p => {
                const id = Number(p.id);
                const code = escapeHtml(p.patient_code || ('P-' + String(p.id)));
                const name = escapeHtml(p.full_name || '');
                const sex = escapeHtml(p.sex || '');
                const dob = escapeHtml(p.dob || '');
                const dept = escapeHtml(p.department || '');
                const payload = escapeHtml(JSON.stringify(p));
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm text-gray-700">${code}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">${name}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${sex}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${dob}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">${dept}</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="px-3 py-1.5 text-sm bg-gray-900 text-white rounded-lg hover:bg-gray-800" data-action="select" data-patient="${payload}">Input Receipt</button>
                        </td>
                    </tr>
                `;
            }).join('');

            tbody.querySelectorAll('button[data-action="select"]').forEach(btn => {
                btn.addEventListener('click', function () {
                    const raw = (btn.getAttribute('data-patient') || '').toString();
                    let p = null;
                    try { p = JSON.parse(raw); } catch (e) { p = null; }
                    if (!p) return;
                    selectResitPatient(p);
                });
            });

            pharmacyResitPatientsLoaded = true;
        }

        function fmtMoney(v) {
            if (v === null || v === undefined || v === '') return '';
            const n = Number(v);
            if (!Number.isFinite(n)) return String(v);
            return n.toFixed(2);
        }

        async function loadPharmacyStats() {
            const totalEl = document.getElementById('pharmacyStatTotalMedicines');
            const lowEl = document.getElementById('pharmacyStatLowStock');
            const outEl = document.getElementById('pharmacyStatOutOfStock');
            const resitEl = document.getElementById('pharmacyStatTotalResits');

            try {
                const res = await fetch('api/pharmacy/stats.php', { headers: { 'Accept': 'application/json' } });
                const json = await res.json().catch(() => null);
                if (!res.ok || !json || !json.ok) return;
                const s = json.stats || {};
                if (totalEl) totalEl.textContent = String(s.total_medicines ?? 0);
                if (lowEl) lowEl.textContent = String(s.low_stock ?? 0);
                if (outEl) outEl.textContent = String(s.out_of_stock ?? 0);
                if (resitEl) resitEl.textContent = String(s.total_resits ?? 0);
            } catch (e) {
            }
        }

        function renderMedicineStatusPill(status) {
            if (status === 'Out of Stock') {
                return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Out of Stock</span>';
            }
            if (status === 'Low Stock') {
                return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Low Stock</span>';
            }
            return '<span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">In Stock</span>';
        }

        async function loadPharmacyMedicines(query, tbodyId, emptyId, totalId) {
            const tbody = document.getElementById(tbodyId);
            const empty = document.getElementById(emptyId);
            const total = totalId ? document.getElementById(totalId) : null;
            if (!tbody) return;

            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-6 text-sm text-gray-500">Loading...</td></tr>';
            if (empty) empty.classList.add('hidden');

            const q = (query || '').toString().trim();
            const url = 'api/pharmacy/list_medicines.php' + (q ? ('?q=' + encodeURIComponent(q)) : '');
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-6 text-sm text-red-600">Unable to load medicines.</td></tr>';
                return;
            }

            const rows = Array.isArray(json.medicines) ? json.medicines : [];
            if (total) total.textContent = String(rows.length);

            if (!rows.length) {
                tbody.innerHTML = '';
                if (empty) empty.classList.remove('hidden');
                return;
            }

            tbody.innerHTML = rows.map(m => {
                const name = escapeHtml(m.name || '');
                const cat = escapeHtml(m.category || '');
                const qty = Number(m.quantity || 0);
                const price = fmtMoney(m.price);
                const expiry = escapeHtml(m.expiry_date || '');
                const minQty = Number(m.min_quantity || 0);
                const status = qty <= 0 ? 'Out of Stock' : (minQty > 0 && qty <= minQty ? 'Low Stock' : 'In Stock');
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">${name}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 text-xs rounded-full bg-gray-100 text-gray-700">${cat || '-'}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${qty}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${price ? ('₱' + price) : ''}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${expiry || ''}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            ${renderMedicineStatusPill(status)}
                        </td>
                    </tr>
                `;
            }).join('');
        }

        function setPharmacyViewFromHash() {
            let h = '';
            try { h = (window.location.hash || '').toString().replace(/^#/, ''); } catch (e0) { h = ''; }
            if (!h) h = 'dashboard';
            if (h === 'resit-form') h = 'patient-resit';

            const dash = document.getElementById('pharmacyDashboardView');
            const resit = document.getElementById('pharmacyPatientResitView');
            const resitInfo = document.getElementById('pharmacyPatientResitInfoView');
            const consult = document.getElementById('pharmacyConsultNotesView');
            const meds = document.getElementById('pharmacyMedicinesView');
            if (!dash || !resit || !resitInfo || !meds || !consult) return;

            if (h === 'patient-resit') {
                dash.classList.add('hidden');
                resit.classList.remove('hidden');
                resitInfo.classList.add('hidden');
                consult.classList.add('hidden');
                meds.classList.add('hidden');

                if (!pharmacyResitPatientsLoaded) {
                    loadResitPatients('');
                }
            } else if (h === 'patient-resit-info') {
                dash.classList.add('hidden');
                resit.classList.add('hidden');
                resitInfo.classList.remove('hidden');
                consult.classList.add('hidden');
                meds.classList.add('hidden');

                loadPharmacyResitInfo();
            } else if (h === 'consultation-notes') {
                dash.classList.add('hidden');
                resit.classList.add('hidden');
                resitInfo.classList.add('hidden');
                consult.classList.remove('hidden');
                meds.classList.add('hidden');

                loadPharmacyConsultNotes();
            } else if (h === 'medicines') {
                dash.classList.add('hidden');
                resit.classList.add('hidden');
                resitInfo.classList.add('hidden');
                consult.classList.add('hidden');
                meds.classList.remove('hidden');

                loadPharmacyStats();
                loadPharmacyMedicines((document.getElementById('pharmacyMedicinesSearch')?.value || '').toString(), 'pharmacyMedicinesTbody', 'pharmacyMedicinesEmpty');
            } else {
                dash.classList.remove('hidden');
                resit.classList.add('hidden');
                resitInfo.classList.add('hidden');
                consult.classList.add('hidden');
                meds.classList.add('hidden');

                loadPharmacyStats();
                loadPharmacyMedicines('', 'pharmacyDashboardMedicinesTbody', 'pharmacyDashboardMedicinesEmpty', 'pharmacyDashboardMedicinesTotal');
            }
        }

        // Modal functionality
        function toggleProductModal() {
            const modal = document.getElementById('productModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = '';

                const form = document.getElementById('productForm');
                if (form) form.reset();
            }
        }

        // Initialize form handlers
        document.addEventListener('DOMContentLoaded', function() {
            setPharmacyViewFromHash();
            window.addEventListener('hashchange', setPharmacyViewFromHash);

            const patientSearch = document.getElementById('pharmacyResitPatientSearch');
            const patientRefresh = document.getElementById('pharmacyResitPatientRefresh');
            const addItem = document.getElementById('pharmacyResitAddItem');
            const submitBtn = document.getElementById('pharmacyResitSubmit');
            const cancelBtn = document.getElementById('pharmacyResitCancel');
            const resitModalCloseBtn = document.getElementById('pharmacyResitModalClose');

            const consultNotesRefreshBtn = document.getElementById('pharmacyConsultNotesRefresh');
            const consultNoteModalCloseBtn = document.getElementById('pharmacyConsultNoteModalClose');
            const consultNoteModalCloseBtn2 = document.getElementById('pharmacyConsultNoteModalClose2');

            const resitInfoRefreshBtn = document.getElementById('pharmacyResitInfoRefresh');
            const resitInfoSearch = document.getElementById('pharmacyResitInfoSearch');

            const doSearch = debounce(function () {
                loadResitPatients((patientSearch && patientSearch.value) ? patientSearch.value : '');
            }, 250);

            if (patientSearch) {
                patientSearch.addEventListener('input', doSearch);
            }

            if (patientRefresh) {
                patientRefresh.addEventListener('click', function () {
                    if (patientSearch) patientSearch.value = '';
                    loadResitPatients('');
                });
            }

            if (addItem) {
                addItem.addEventListener('click', function () {
                    if (!pharmacyResitSelectedPatient) return;
                    const itemsEl = document.getElementById('pharmacyResitItems');
                    if (!itemsEl) return;
                    const wrap = document.createElement('div');
                    wrap.innerHTML = renderResitItemRow({});
                    const node = wrap.firstElementChild;
                    if (node) {
                        const remove = node.querySelector('[data-action="remove"]');
                        if (remove) {
                            remove.addEventListener('click', function () {
                                const row = remove.closest('[data-resit-row="1"]');
                                if (row) row.remove();
                            });
                        }
                        attachResitMedicineAutocomplete(node);
                        itemsEl.appendChild(node);
                    }
                });
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', function () {
                    resetResitForm();
                    toggleResitModal(false);
                });
            }

            if (resitModalCloseBtn) {
                resitModalCloseBtn.addEventListener('click', function () {
                    resetResitForm();
                    toggleResitModal(false);
                });
            }

            if (consultNotesRefreshBtn) {
                consultNotesRefreshBtn.addEventListener('click', function () {
                    loadPharmacyConsultNotes();
                });
            }

            if (resitInfoRefreshBtn) {
                resitInfoRefreshBtn.addEventListener('click', function () {
                    const q = resitInfoSearch ? resitInfoSearch.value : '';
                    loadPharmacyResitInfoWithSearch(q);
                });
            }

            if (resitInfoSearch) {
                const doResitInfoSearch = debounce(function () {
                    loadPharmacyResitInfoWithSearch(resitInfoSearch.value || '');
                }, 250);
                resitInfoSearch.addEventListener('input', doResitInfoSearch);
            }

            if (consultNoteModalCloseBtn) {
                consultNoteModalCloseBtn.addEventListener('click', function () {
                    toggleConsultNoteModal(false);
                });
            }

            if (consultNoteModalCloseBtn2) {
                consultNoteModalCloseBtn2.addEventListener('click', function () {
                    toggleConsultNoteModal(false);
                });
            }

            if (submitBtn) {
                submitBtn.addEventListener('click', function () {
                    const modal = document.getElementById('pharmacyResitModal');
                    const editResitId = modal ? (modal.dataset.editResitId || '') : '';
                    const isEdit = editResitId !== '';

                    if (!isEdit && !pharmacyResitSelectedPatient) {
                        alert('Select a patient first');
                        return;
                    }
                    const draft = collectResitForm();
                    if (!draft.items || !draft.items.length) {
                        alert('Please add at least one medicine item');
                        return;
                    }
                    submitBtn.disabled = true;
                    submitBtn.textContent = isEdit ? 'Updating...' : 'Submitting...';

                    const url = isEdit ? 'api/pharmacy/update_resit.php' : 'api/pharmacy/save_resit.php';
                    const payload = isEdit ? {
                        resit_id: Number(editResitId),
                        prescribed_by: (draft.doctor || '').toString().trim(),
                        notes: (draft.notes || '').toString().trim(),
                        items: (draft.items || []).map(it => ({
                            name: (it.name || '').toString(),
                            medicine_id: (it.medicine_id || '').toString(),
                            qty: (it.qty || '').toString(),
                            sig: (it.sig || '').toString(),
                        }))
                    } : {
                        patient_id: Number(pharmacyResitSelectedPatient.id),
                        prescribed_by: (draft.doctor || '').toString().trim(),
                        notes: (draft.notes || '').toString().trim(),
                        items: (draft.items || []).map(it => ({
                            name: (it.name || '').toString(),
                            medicine_id: (it.medicine_id || '').toString(),
                            qty: (it.qty || '').toString(),
                            sig: (it.sig || '').toString(),
                        }))
                    };

                    fetch(url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify(payload)
                    })
                        .then(res => res.json().catch(() => null).then(json => ({ res, json })))
                        .then(({ res, json }) => {
                            if (!res.ok || !json || !json.ok) {
                                const msg = (json && json.error) ? json.error : (isEdit ? 'Unable to update receipt' : 'Unable to save receipt');
                                alert(msg);
                                return;
                            }
                            alert(isEdit ? 'Receipt updated successfully' : 'Receipt saved successfully');
                            if (isEdit) {
                                // Refresh list and close modal
                                loadPharmacyResitInfo();
                                toggleResitModal(false);
                            } else {
                                selectResitPatient(pharmacyResitSelectedPatient);
                            }
                        })
                        .catch(() => {
                            alert(isEdit ? 'Unable to update receipt' : 'Unable to save receipt');
                        })
                        .finally(() => {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Submit';
                        });
                });
            }

            const addProductBtn = document.getElementById('addProductBtn');
            const productForm = document.getElementById('productForm');
            const autoFillMedicineBtn = document.getElementById('autoFillMedicineBtn');

            const medicinesSearch = document.getElementById('pharmacyMedicinesSearch');
            const medicinesRefresh = document.getElementById('pharmacyMedicinesRefresh');
            const medicinesAddBtn = document.getElementById('pharmacyMedicinesAddBtn');

            if (addProductBtn) addProductBtn.addEventListener('click', toggleProductModal);

            const doMedicineSearch = debounce(function () {
                loadPharmacyMedicines((medicinesSearch && medicinesSearch.value) ? medicinesSearch.value : '', 'pharmacyMedicinesTbody', 'pharmacyMedicinesEmpty');
            }, 250);

            if (medicinesSearch) {
                medicinesSearch.addEventListener('input', doMedicineSearch);
            }

            if (medicinesRefresh) {
                medicinesRefresh.addEventListener('click', function () {
                    if (medicinesSearch) medicinesSearch.value = '';
                    loadPharmacyMedicines('', 'pharmacyMedicinesTbody', 'pharmacyMedicinesEmpty');
                });
            }

            if (medicinesAddBtn) {
                medicinesAddBtn.addEventListener('click', toggleProductModal);
            }

            if (autoFillMedicineBtn && productForm) {
                let lastAutoFillMedicineName = '';
                autoFillMedicineBtn.addEventListener('click', async function () {
                    autoFillMedicineBtn.disabled = true;
                    const oldText = autoFillMedicineBtn.textContent;
                    autoFillMedicineBtn.textContent = 'Auto Filling...';

                    try {
                        const seed = String(Date.now()) + '-' + Math.random().toString(16).slice(2);
                        const res = await fetch('api/pharmacy/ai_autofill_medicine.php', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                            body: JSON.stringify({
                                seed,
                                avoid_names: lastAutoFillMedicineName ? [lastAutoFillMedicineName] : [],
                            })
                        });
                        const json = await res.json().catch(() => null);
                        if (!res.ok || !json || !json.ok || !json.medicine) {
                            alert((json && json.error) ? json.error : 'Failed to autofill');
                            return;
                        }

                        const m = json.medicine;
                        const setVal = (name, v) => {
                            const el = productForm.elements[name];
                            if (!el) return;
                            el.value = (v ?? '').toString();
                        };

                        setVal('productName', m.name);
                        setVal('category', m.category);
                        setVal('quantity', m.quantity);
                        setVal('price', m.price);
                        setVal('expiryDate', m.expiry_date);
                        setVal('manufacturer', m.manufacturer);
                        setVal('description', m.description);

                        lastAutoFillMedicineName = (m.name || '').toString();
                    } finally {
                        autoFillMedicineBtn.disabled = false;
                        autoFillMedicineBtn.textContent = oldText;
                    }
                });
            }

            if (!productForm) return;
            productForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Collect form data
                const formData = new FormData(this);
                const product = Object.fromEntries(formData.entries());

                fetch('api/pharmacy/save_medicine.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                    body: JSON.stringify({
                        name: (product.productName || '').toString().trim(),
                        category: (product.category || '').toString().trim(),
                        quantity: Number(product.quantity || 0),
                        min_quantity: 0,
                        price: (product.price || '').toString(),
                        expiry_date: (product.expiryDate || '').toString(),
                        manufacturer: (product.manufacturer || '').toString().trim(),
                        description: (product.description || '').toString().trim(),
                    })
                })
                    .then(res => res.json().catch(() => null).then(json => ({ res, json })))
                    .then(({ res, json }) => {
                        if (!res.ok || !json || !json.ok) {
                            const msg = (json && json.error) ? json.error : 'Unable to save medicine';
                            alert(msg);
                            return;
                        }

                        alert('Medicine saved successfully!');

                        loadPharmacyStats();
                        loadPharmacyMedicines('', 'pharmacyDashboardMedicinesTbody', 'pharmacyDashboardMedicinesEmpty', 'pharmacyDashboardMedicinesTotal');
                        loadPharmacyMedicines((medicinesSearch && medicinesSearch.value) ? medicinesSearch.value : '', 'pharmacyMedicinesTbody', 'pharmacyMedicinesEmpty');

                        toggleProductModal();
                        this.reset();
                    })
                    .catch(() => {
                        alert('Unable to save medicine');
                    });
            });
        });

        // Initialize charts
        document.addEventListener('DOMContentLoaded', function() {
            return;
            // Sales Trend Line Chart
            const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
            const salesData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [35000, 42000, 38000, 45000, 39000, 48000],
                    borderColor: 'rgb(99, 102, 241)',
                    backgroundColor: 'rgba(99, 102, 241, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            };
            new Chart(salesCtx, {
                type: 'line',
                data: salesData,
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
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });

            // Inventory Bar Chart
            const inventoryCtx = document.getElementById('inventoryChart').getContext('2d');
            const inventoryData = {
                labels: ['Antibiotics', 'Pain Relievers', 'Vitamins', 'Cardiovascular', 'Respiratory'],
                datasets: [{
                    label: 'Current Stock',
                    data: [120, 95, 75, 80, 55],
                    backgroundColor: [
                        'rgba(99, 102, 241, 0.8)',
                        'rgba(14, 165, 233, 0.8)',
                        'rgba(34, 197, 94, 0.8)',
                        'rgba(249, 115, 22, 0.8)',
                        'rgba(236, 72, 153, 0.8)'
                    ]
                }]
            };
            new Chart(inventoryCtx, {
                type: 'bar',
                data: inventoryData,
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

            // Category Distribution Pie Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            const categoryData = {
                labels: ['Antibiotics', 'Pain Relievers', 'Vitamins', 'Cardiovascular', 'Respiratory', 'Others'],
                datasets: [{
                    data: [25, 20, 15, 15, 12, 13],
                    backgroundColor: [
                        'rgb(99, 102, 241)',
                        'rgb(14, 165, 233)',
                        'rgb(34, 197, 94)',
                        'rgb(249, 115, 22)',
                        'rgb(236, 72, 153)',
                        'rgb(148, 163, 184)'
                    ]
                }]
            };
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: categoryData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right'
                        }
                    }
                }
            });

            // Regional Map Chart with Heatmap Toggle
            const mapCtx = document.getElementById('regionMap').getContext('2d');
            let isHeatmapActive = false;
            const mapChart = new Chart(mapCtx, {
                type: 'bubble',
                data: {
                    datasets: [{
                        label: 'Sales by Region',
                        data: [
                            { x: 20, y: 30, r: 15, region: 'North', value: 15000 },
                            { x: 40, y: 10, r: 10, region: 'South', value: 10000 },
                            { x: 15, y: 50, r: 20, region: 'East', value: 20000 },
                            { x: 60, y: 40, r: 25, region: 'West', value: 25000 }
                        ],
                        backgroundColor: 'rgba(99, 102, 241, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { display: false },
                        y: { display: false }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.raw.region}: $${context.raw.value.toLocaleString()}`;
                                }
                            }
                        }
                    }
                }
            });

            // Performance Radar Chart
            const radarCtx = document.getElementById('performanceRadar').getContext('2d');
            const radarChart = new Chart(radarCtx, {
                type: 'radar',
                data: {
                    labels: ['Sales', 'Stock Level', 'Profit Margin', 'Growth Rate', 'Customer Demand'],
                    datasets: [{
                        label: 'Antibiotics',
                        data: [85, 70, 90, 65, 75],
                        borderColor: 'rgba(99, 102, 241, 1)',
                        backgroundColor: 'rgba(99, 102, 241, 0.2)'
                    }, {
                        label: 'Pain Relievers',
                        data: [75, 85, 65, 80, 90],
                        borderColor: 'rgba(14, 165, 233, 1)',
                        backgroundColor: 'rgba(14, 165, 233, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    elements: {
                        line: {
                            borderWidth: 2
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Price Analysis Scatter Plot
            const scatterCtx = document.getElementById('priceAnalysis').getContext('2d');
            const scatterChart = new Chart(scatterCtx, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Products',
                        data: generateScatterData(),
                        backgroundColor: 'rgba(99, 102, 241, 0.6)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Price (₱)'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Units Sold'
                            }
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Price: ₱${context.raw.x}, Sold: ${context.raw.y} units`;
                                }
                            }
                        }
                    }
                }
            });

            // Stock Timeline
            const timelineCtx = document.getElementById('stockTimeline').getContext('2d');
            const timelineChart = new Chart(timelineCtx, {
                type: 'line',
                data: {
                    labels: generateTimeLabels(24),
                    datasets: [{
                        label: 'Incoming Stock',
                        data: generateTimelineData(24, 'incoming'),
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        fill: true
                    }, {
                        label: 'Outgoing Stock',
                        data: generateTimelineData(24, 'outgoing'),
                        borderColor: 'rgba(239, 68, 68, 1)',
                        backgroundColor: 'rgba(239, 68, 68, 0.1)',
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        });

        // Helper Functions
        function generateScatterData() {
            const data = [];
            for (let i = 0; i < 50; i++) {
                data.push({
                    x: Math.random() * 100 + 10,
                    y: Math.random() * 200 + 50
                });
            }
            return data;
        }

        function generateTimeLabels(hours) {
            const labels = [];
            for (let i = hours - 1; i >= 0; i--) {
                labels.push(`${i}h ago`);
            }
            return labels;
        }

        function generateTimelineData(points, type) {
            const data = [];
            let baseValue = type === 'incoming' ? 100 : 80;
            for (let i = 0; i < points; i++) {
                data.push(baseValue + Math.random() * 30 - 15);
            }
            return data;
        }

        // Interactive Functions
        function toggleHeatmap() {
            isHeatmapActive = !isHeatmapActive;
            const chart = Chart.getChart('regionMap');
            chart.data.datasets[0].backgroundColor = isHeatmapActive ? 
                generateHeatmapColors(chart.data.datasets[0].data) :
                'rgba(99, 102, 241, 0.6)';
            chart.update();
        }

        function generateHeatmapColors(data) {
            return data.map(d => {
                const value = d.value;
                const maxValue = Math.max(...data.map(d => d.value));
                const intensity = value / maxValue;
                return `rgba(255, ${Math.round(intensity * 100)}, 0, 0.6)`;
            });
        }

        function updateRadarMetrics(type) {
            const chart = Chart.getChart('performanceRadar');
            if (type === 'sales') {
                chart.data.datasets[0].data = [85, 70, 90, 65, 75];
                chart.data.datasets[1].data = [75, 85, 65, 80, 90];
            } else {
                chart.data.datasets[0].data = [90, 65, 85, 70, 80];
                chart.data.datasets[1].data = [70, 80, 75, 85, 65];
            }
            chart.update();
        }

        function updateScatterPlot(category) {
            const chart = Chart.getChart('priceAnalysis');
            chart.data.datasets[0].data = generateScatterData();
            chart.update();
        }

        function updateTimelineRange(range) {
            const chart = Chart.getChart('stockTimeline');
            const hours = range === '24h' ? 24 : range === '7d' ? 168 : 720;
            chart.data.labels = generateTimeLabels(hours);
            chart.data.datasets[0].data = generateTimelineData(hours, 'incoming');
            chart.data.datasets[1].data = generateTimelineData(hours, 'outgoing');
            chart.update();
        }

        document.getElementById('showIncoming').addEventListener('change', function(e) {
            const chart = Chart.getChart('stockTimeline');
            chart.data.datasets[0].hidden = !e.target.checked;
            chart.update();
        });

        document.getElementById('showOutgoing').addEventListener('change', function(e) {
            const chart = Chart.getChart('stockTimeline');
            chart.data.datasets[1].hidden = !e.target.checked;
            chart.update();
        });

        // Pharmacy Queue Management Functions
        let currentPharmacyQueueData = null;

        async function loadPharmacyQueue() {
            try {
                const response = await fetch('api/queue/display/3'); // Pharmacy station ID is 3
                currentPharmacyQueueData = await response.json();
                updatePharmacyQueueDisplay();
            } catch (error) {
                console.error('Error loading Pharmacy queue:', error);
            }
        }

        function getQueueEntryId(row) {
            if (!row) return 0;
            const v = row.queue_id ?? row.queue_entry_id ?? row.id;
            return Number(v || 0);
        }

        function updatePharmacyQueueDisplay() {
            if (!currentPharmacyQueueData) return;

            const currentlyServingDiv = document.getElementById('pharmacyCurrentlyServing');
            if (currentPharmacyQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="bg-white p-4 rounded-lg border border-green-300 flex items-center gap-4">
                        <div class="relative h-16 w-16">
                            <div class="absolute h-12 w-12 left-[calc(50%-1.5rem)] top-[calc(50%-1.5rem)] bg-green-500 rounded animate-ping"></div>
                            <div class="relative h-full w-full bg-green-500 text-white text-2xl rounded-md flex flex-col items-center justify-center font-bold">
                                ${currentPharmacyQueueData.currently_serving.queue_number}
                            </div>
                        </div>
                        <div class="flex flex-col items-start text-left">
                            <div class="text-2xl font-bold text-green-700 line-clamp-1">${currentPharmacyQueueData.currently_serving.full_name}</div>
                            <div class="text-sm text-gray-600">${currentPharmacyQueueData.currently_serving.patient_code || ''}</div>
                        </div>
                    </div>
                `;

                document.getElementById('pharmacyStationSelection').classList.remove('hidden');
                loadPharmacyStationOptions();
            } else {
                currentlyServingDiv.innerHTML = `
                    <div class="text-gray-500">
                        <i class="fas fa-user-slash text-3xl mb-2"></i>
                        <p>No patient being served</p>
                    </div>
                `;
                document.getElementById('pharmacyStationSelection').classList.add('hidden');
            }

            const queueListDiv = document.getElementById('pharmacyQueueList');
            if (currentPharmacyQueueData.next_patients && currentPharmacyQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentPharmacyQueueData.next_patients.map((patient) => `
                    <div class="flex justify-between items-center p-2 bg-blue-50 rounded-lg border border-blue-200 hover:bg-blue-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-blue-600 text-white rounded-md flex items-center justify-center font-bold">
                                ${patient.queue_number}
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-800 line-clamp-1">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                queueListDiv.innerHTML = `
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-users-slash text-4xl mb-2"></i>
                        <p>No patients in queue</p>
                    </div>
                `;
            }

            const unavailableDiv = document.getElementById('pharmacyUnavailablePatientsList');
            if (currentPharmacyQueueData.unavailable_patients && currentPharmacyQueueData.unavailable_patients.length > 0) {
                unavailableDiv.innerHTML = currentPharmacyQueueData.unavailable_patients.map(patient => `
                    <div class="flex justify-between items-center p-2 bg-orange-50 rounded-lg border border-orange-200 cursor-pointer hover:bg-orange-100 transition-colors" onclick="recallPharmacyUnavailablePatient(${getQueueEntryId(patient)})">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-orange-600 text-white rounded-md flex items-center justify-center font-bold">
                                ${patient.queue_number}
                            </div>
                            <div>
                                <div class="text-xl font-semibold text-gray-800 line-clamp-1">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code || ''}</div>
                            </div>
                        </div>
                    </div>
                `).join('');
            } else {
                unavailableDiv.innerHTML = `
                    <div class="text-center py-6 text-gray-400">
                        <i class="fas fa-check-circle text-3xl mb-2"></i>
                        <p>No unavailable patients</p>
                    </div>
                `;
            }
        }

        function openPharmacySendPatientModal() {
            if (!currentPharmacyQueueData?.currently_serving) {
                Toastify({
                    text: 'Please call a patient first before sending to next station',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#F59E0B',
                }).showToast();
                return;
            }

            const modal = document.getElementById('pharmacySendPatientModal');
            modal.classList.remove('hidden');
            loadPharmacyStationsForModal();
        }

        function closePharmacySendPatientModal() {
            const modal = document.getElementById('pharmacySendPatientModal');
            modal.classList.add('hidden');
            document.getElementById('pharmacyConfirmSendBtn').disabled = true;
            document.querySelectorAll('.pharmacy-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });
        }

        function loadPharmacyStationsForModal() {
            const stationList = document.getElementById('pharmacyStationList');
            stationList.innerHTML = '<div class="text-center py-8"><i class="fas fa-spinner fa-spin text-blue-500 text-3xl"></i> <p class="mt-2 text-lg">Loading stations...</p></div>';

            const dischargeOption = document.createElement('div');
            dischargeOption.className = 'pharmacy-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-green-400 hover:bg-green-50 transition-all duration-200 transform hover:scale-[1.02]';
            dischargeOption.onclick = () => selectPharmacyStation('discharge', dischargeOption);
            dischargeOption.innerHTML = `
                <div class="flex items-center">
                    <div class="w-16 h-16 bg-green-600 text-white rounded-xl flex items-center justify-center mr-6">
                        <i class="fas fa-check text-2xl"></i>
                    </div>
                    <div class="flex-1">
                        <div class="text-xl font-bold text-gray-800">Complete and Discharge</div>
                        <div class="text-base text-gray-600 mt-1">Patient consultation complete</div>
                    </div>
                </div>
            `;

            fetch('api/queue/stations')
                .then(response => response.json())
                .then(data => {
                    stationList.innerHTML = '';

                    data.stations.forEach(station => {
                        if (station.id !== 3) {
                            const stationOption = document.createElement('div');
                            stationOption.className = 'pharmacy-station-option p-6 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-all duration-200 transform hover:scale-[1.02]';
                            stationOption.onclick = () => selectPharmacyStation(station.id, stationOption);

                            let icon = 'fa-arrow-right';
                            let iconColor = 'bg-blue-600';
                            const stationName = (station.station_display_name || '').toLowerCase();
                            if (stationName.includes('doctor') || stationName.includes('consultation')) {
                                icon = 'fa-user-md';
                                iconColor = 'bg-purple-600';
                            } else if (stationName.includes('pharmacy')) {
                                icon = 'fa-pills';
                                iconColor = 'bg-pink-600';
                            } else if (stationName.includes('cashier') || stationName.includes('payment') || stationName.includes('billing')) {
                                icon = 'fa-cash-register';
                                iconColor = 'bg-yellow-600';
                            } else if (stationName.includes('lab') || stationName.includes('laboratory')) {
                                icon = 'fa-flask';
                                iconColor = 'bg-cyan-600';
                            } else if (stationName.includes('x-ray') || stationName.includes('radiology')) {
                                icon = 'fa-x-ray';
                                iconColor = 'bg-indigo-600';
                            } else if (stationName.includes('nurse') || stationName.includes('triage')) {
                                icon = 'fa-user-nurse';
                                iconColor = 'bg-red-600';
                            }

                            stationOption.innerHTML = `
                                <div class="flex items-center">
                                    <div class="w-16 h-16 ${iconColor} text-white rounded-xl flex items-center justify-center mr-6">
                                        <i class="fas ${icon} text-2xl"></i>
                                    </div>
                                    <div class="flex-1">
                                        <div class="text-xl font-bold text-gray-800">${station.station_display_name}</div>
                                        <div class="text-base text-gray-600 mt-1">Move to ${station.station_display_name}</div>
                                    </div>
                                </div>
                            `;
                            stationList.appendChild(stationOption);
                        }
                    });

                    stationList.appendChild(dischargeOption);
                })
                .catch(() => {
                    stationList.innerHTML = '<div class="text-center py-8 text-red-500"><i class="fas fa-exclamation-triangle text-3xl mb-2"></i><p class="text-lg">Failed to load stations</p></div>';
                });
        }

        function selectPharmacyStation(stationId, element) {
            document.querySelectorAll('.pharmacy-station-option').forEach(btn => {
                btn.classList.remove('ring-4', 'ring-blue-500', 'bg-blue-50', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            });

            if (stationId === 'discharge') {
                element.classList.add('ring-4', 'ring-green-500', 'bg-green-50', 'shadow-lg');
            } else {
                element.classList.add('ring-4', 'ring-blue-500', 'bg-blue-50', 'shadow-lg');
            }

            document.getElementById('pharmacyConfirmSendBtn').disabled = false;
            document.getElementById('pharmacyConfirmSendBtn').onclick = () => sendPharmacyPatientToStation(stationId);
        }

        async function sendPharmacyPatientToStation(stationId) {
            if (!currentPharmacyQueueData?.currently_serving) return;

            try {
                let body = {
                    queue_id: currentPharmacyQueueData.currently_serving.id
                };

                if (stationId !== 'discharge') {
                    body.target_station_id = parseInt(stationId, 10);
                }

                const response = await fetch('api/queue/complete-service', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });

                const result = await response.json();
                const isSuccess = response.ok && (result.ok === true || result.success === true);

                if (isSuccess) {
                    Toastify({
                        text: stationId === 'discharge' ? 'Patient discharged successfully' : 'Patient sent to next station',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();

                    closePharmacySendPatientModal();
                    loadPharmacyQueue();
                } else {
                    const errorMessage = result.error || result.message || 'Failed to send patient';
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error sending patient to station:', error);
                Toastify({
                    text: error.message || 'Failed to send patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        function loadPharmacyStationOptions() {
            // Compatibility function. Station selection is handled by modal.
        }

        async function callNextPatient() {
            try {
                if (currentPharmacyQueueData?.currently_serving) {
                    Toastify({
                        text: 'Please complete the current patient service before calling the next patient',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                    return;
                }

                const response = await fetch('api/queue/call-next', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ station_id: 3 })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Next patient called successfully',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadPharmacyQueue();
                } else {
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.toLowerCase().includes('no active transaction')) {
                        message = 'No more patients in the waiting queue';
                    }
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error calling next patient:', error);
                Toastify({
                    text: 'Error calling next patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function recallPharmacyUnavailablePatient(queueId) {
            try {
                const response = await fetch('api/queue/recall-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId,
                        notes: 'Recalled from unavailable list'
                    })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Patient recalled successfully',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadPharmacyQueue();
                } else {
                    Toastify({
                        text: result.message || 'Unable to recall patient',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#EF4444',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error recalling unavailable patient:', error);
                Toastify({
                    text: 'Failed to recall patient from unavailable list',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function callNextAndMarkUnavailable() {
            try {
                const response = await fetch('api/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        station_id: 3,
                        notes: 'Patient not available for service'
                    })
                });

                const result = await response.json();
                if (result.success) {
                    Toastify({
                        text: 'Next patient called and previous patient marked as unavailable',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#10B981',
                    }).showToast();
                    loadPharmacyQueue();
                } else {
                    let message = result.message || 'No more patients in the waiting queue';
                    if (message === 'There is no active transaction' || message.toLowerCase().includes('no active transaction')) {
                        message = 'No more patients in the waiting queue';
                    }
                    Toastify({
                        text: message,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        backgroundColor: '#F59E0B',
                    }).showToast();
                }
            } catch (error) {
                console.error('Error calling next and marking unavailable:', error);
                Toastify({
                    text: 'Error calling next patient',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    backgroundColor: '#EF4444',
                }).showToast();
            }
        }

        async function completeService() {
            Toastify({
                text: 'Please use the "Send Patient" button instead',
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#F59E0B',
            }).showToast();
        }

        function openDisplayScreen() {
            window.open('pharmacy-display.php', '_blank');
        }

        // Auto-refresh queue every 10 seconds
        setInterval(loadPharmacyQueue, 10000);
        
        // Initial load
        loadPharmacyQueue();
    </script>
    <?php include __DIR__ . '/includes/queue-error-correction.php'; ?>
    <script>window.qecStationId = 3; window.qecRefreshQueue = function() { loadPharmacyQueue(); };</script>
    <?php include __DIR__ . '/includes/queue-error-correction-js.php'; ?>
</body>
</html>

