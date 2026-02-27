<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Supply Management - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
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
                            <a href="kitchen.php" class="flex items-center gap-x-4 px-4 py-2 bg-blue-600 text-white rounded-lg shadow-md">
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
        <main class="ml-16 lg:ml-80 p-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Kitchen Supply Management</h1>
                <p class="text-gray-500">Manage and track kitchen supplies</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Items -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-box"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Total Items</h2>
                                <p class="text-2xl font-semibold text-gray-800">245</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-arrow-up"></i> 12%
                                </span>
                                <span class="ml-2 text-sm text-gray-600">from last month</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1590779033100-9f60a05a013d?w=400&q=80" alt="Kitchen Inventory"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>

                <!-- Low Stock Items -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Low Stock Items</h2>
                                <p class="text-2xl font-semibold text-gray-800">15</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-red-600 text-sm font-medium">
                                    <i class="fas fa-arrow-up"></i> 5%
                                </span>
                                <span class="ml-2 text-sm text-gray-600">requires attention</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1604382355076-af4b0eb60143?w=400&q=80" alt="Alert"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>

                <!-- Total Value -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Total Value</h2>
                                <p class="text-2xl font-semibold text-gray-800">$12,450</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-arrow-up"></i> 8%
                                </span>
                                <span class="ml-2 text-sm text-gray-600">from last month</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=400&q=80" alt="Finance"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>

                <!-- Equipment Status -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-tools"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Equipment Status</h2>
                                <p class="text-2xl font-semibold text-gray-800">98%</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-check"></i> Operational
                                </span>
                                <span class="ml-2 text-sm text-gray-600">all systems</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1581092160607-ee22621dd758?w=400&q=80" alt="Equipment"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>
            </div>

            <!-- Supply Management Section -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Kitchen Inventory</h2>
                        <button onclick="toggleModal('addItemModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            Add New Item
                        </button>
                    </div>
                </div>

                <!-- Inventory Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expiry Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Rice -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 relative group">
                                            <img src="resources/rice.jpg" alt="Rice" 
                                                class="w-full h-full rounded-lg object-cover shadow-sm">
                                            <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">#K001 Premium Rice</div>
                                            <div class="text-sm text-gray-500">25kg bag</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-amber-100 text-amber-800">
                                        Dry Goods
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">45 bags</div>
                                    <div class="text-xs text-green-600">Well Stocked</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">$35.00</div>
                                    <div class="text-xs text-red-600">+2.8%</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">15-03-2026</div>
                                    <div class="text-xs text-gray-500">180 days left</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        In Stock
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-1 text-red-600 hover:text-red-800 hover:bg-red-100 rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Cooking Oil -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 relative group">
                                            <img src="resources/oil.jpg" alt="Cooking Oil" 
                                                class="w-full h-full rounded-lg object-cover shadow-sm">
                                            <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">#K002 Cooking Oil</div>
                                            <div class="text-sm text-gray-500">20L container</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        Oils & Fats
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">12 containers</div>
                                    <div class="text-xs text-yellow-600">Low Stock</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">$45.00</div>
                                    <div class="text-xs text-green-600">-1.5%</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">10-12-2025</div>
                                    <div class="text-xs text-gray-500">90 days left</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Low Stock
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-1 text-red-600 hover:text-red-800 hover:bg-red-100 rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Equipment -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 relative group">
                                            <img src="resources/pot.jpg" alt="Stock Pot" 
                                                class="w-full h-full rounded-lg object-cover shadow-sm">
                                            <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">#K003 Stock Pot</div>
                                            <div class="text-sm text-gray-500">100L Stainless Steel</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-purple-100 text-purple-800">
                                        Equipment
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">5 units</div>
                                    <div class="text-xs text-green-600">Sufficient</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">$250.00</div>
                                    <div class="text-xs text-gray-500">Fixed Asset</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">N/A</div>
                                    <div class="text-xs text-gray-500">Durable Good</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Operational
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button class="p-1 text-blue-600 hover:text-blue-800 hover:bg-blue-100 rounded">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="p-1 text-red-600 hover:text-red-800 hover:bg-red-100 rounded">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                        <button class="p-1 text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Analytics Dashboard -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Stock Movement Analysis -->
                <div class="bg-white rounded-lg shadow p-6 lg:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Stock Movement Analysis</h3>
                        <select class="text-sm border rounded-md px-2 py-1">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last 90 Days</option>
                        </select>
                    </div>
                    <canvas id="stockMovementChart" class="w-full" height="200"></canvas>
                </div>

                <!-- Top Consumed Items -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top Consumed Items</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-full">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Rice</span>
                                    <span class="text-sm text-gray-600">85%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-full">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Cooking Oil</span>
                                    <span class="text-sm text-gray-600">70%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="w-full">
                                <div class="flex justify-between mb-1">
                                    <span class="text-sm font-medium">Vegetables</span>
                                    <span class="text-sm text-gray-600">65%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="bg-blue-600 h-2 rounded-full" style="width: 65%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Stock Value Trend -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Stock Value Trend</h3>
                    <canvas id="stockValueChart" class="w-full" height="200"></canvas>
                </div>

                <!-- Category Distribution -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Distribution</h3>
                    <canvas id="categoryChart" class="w-full" height="200"></canvas>
                </div>
            </div>

            <!-- Cost Analysis and Meal Planning -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Monthly Cost Analysis -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Cost Analysis</h3>
                    <canvas id="costAnalysisChart" class="w-full" height="200"></canvas>
                </div>

                <!-- Meal Planning Calendar -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Weekly Meal Planning</h3>
                        <button onclick="toggleModal('addMenuModal')" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                            <i class="fas fa-plus mr-1"></i> Add Menu
                        </button>
                    </div>
                    <div class="grid grid-cols-7 gap-2">
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Mon</div>
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Tue</div>
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Wed</div>
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Thu</div>
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Fri</div>
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Sat</div>
                        <div class="text-center p-2 text-sm font-medium text-gray-600">Sun</div>
                        
                        <!-- Sample Menu Items -->
                        <div class="p-2 border rounded bg-blue-50 text-xs">Rice & Curry</div>
                        <div class="p-2 border rounded bg-green-50 text-xs">Pasta Day</div>
                        <div class="p-2 border rounded bg-yellow-50 text-xs">Soup & Bread</div>
                        <div class="p-2 border rounded bg-purple-50 text-xs">Asian Menu</div>
                        <div class="p-2 border rounded bg-red-50 text-xs">Local Dishes</div>
                        <div class="p-2 border rounded bg-indigo-50 text-xs">Special Menu</div>
                        <div class="p-2 border rounded bg-pink-50 text-xs">Chef's Choice</div>
                    </div>
                </div>
            </div>

            <!-- Equipment Maintenance -->
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Equipment Maintenance Schedule</h3>
                    <button onclick="toggleModal('maintenanceModal')" class="px-3 py-1 text-sm bg-blue-600 text-white rounded hover:bg-blue-700">
                        <i class="fas fa-plus mr-1"></i> Schedule Maintenance
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Equipment</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Last Service</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Next Due</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                                            <img class="h-10 w-10 rounded-lg object-cover" 
                                                src="resources/stove.jpg" 
                                                alt="Industrial Stove">
                                        </div>
                                        <div>
                                            <div class="font-medium">Industrial Stove</div>
                                            <div class="text-xs text-gray-500">Kitchen Area 1</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">Aug 15, 2025</td>
                                <td class="px-4 py-3 text-sm">Oct 15, 2025</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Operational
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-wrench"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 mr-3">
                                            <img class="h-10 w-10 rounded-lg object-cover" 
                                                src="resources/freezer.jpg" 
                                                alt="Walk-in Freezer">
                                        </div>
                                        <div>
                                            <div class="font-medium">Walk-in Freezer</div>
                                            <div class="text-xs text-gray-500">Storage Area</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm">Sep 1, 2025</td>
                                <td class="px-4 py-3 text-sm">Sep 30, 2025</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                        Service Due Soon
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <button class="text-blue-600 hover:text-blue-800">
                                        <i class="fas fa-wrench"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <!-- Add New Item Modal -->
    <div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-2xl mx-4 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-medium text-gray-900">Add New Item</h3>
                <button onclick="toggleModal('addItemModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="addItemForm" class="space-y-6">
                <div class="flex gap-8">
                    <!-- Image Upload Section -->
                    <div class="w-1/3">
                        <div class="relative group cursor-pointer" onclick="document.getElementById('itemImage').click()">
                            <div id="imagePreview" class="w-full h-48 rounded-xl bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden">
                                <div id="uploadIcon" class="text-center p-4">
                                    <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-500">Click to upload image</p>
                                </div>
                                <img id="previewImg" src="" alt="" class="hidden w-full h-full object-cover">
                            </div>
                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-xl transition-all duration-300"></div>
                        </div>
                        <input type="file" id="itemImage" accept="image/*" class="hidden" onchange="previewImage(this)">
                    </div>

                    <!-- Form Fields -->
                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Item Name</label>
                                <input type="text" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Enter item name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option>Dry Goods</option>
                                    <option>Fresh Produce</option>
                                    <option>Meat & Poultry</option>
                                    <option>Equipment</option>
                                    <option>Others</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                <input type="number" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Enter quantity">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                                <div class="relative">
                                    <span class="absolute left-3 top-2 text-gray-500">$</span>
                                    <input type="number" step="0.01" class="w-full px-4 py-2 pl-8 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="0.00">
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                                <input type="date" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                            <textarea class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" rows="3" placeholder="Add any additional notes"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('addItemModal')" 
                        class="px-6 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                        Add Item
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Menu Modal -->
    <div id="addMenuModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-xl mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-900">Add Menu Item</h3>
                <button onclick="toggleModal('addMenuModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="addMenuForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Menu Name</label>
                    <input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Day of Week</label>
                    <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option>Monday</option>
                        <option>Tuesday</option>
                        <option>Wednesday</option>
                        <option>Thursday</option>
                        <option>Friday</option>
                        <option>Saturday</option>
                        <option>Sunday</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Menu Items</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center space-x-2">
                            <input type="text" placeholder="Item name" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <button type="button" class="p-2 text-blue-600 hover:text-blue-800">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Special Instructions</label>
                    <textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="toggleModal('addMenuModal')" class="px-4 py-2 border rounded-md text-gray-600 hover:bg-gray-50">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Add Menu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schedule Maintenance Modal -->
    <div id="maintenanceModal" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-2xl mx-4 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-medium text-gray-900">Schedule Maintenance</h3>
                <button onclick="toggleModal('maintenanceModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="maintenanceForm" class="space-y-6">
                <div class="flex gap-8">
                    <!-- Equipment Preview Section -->
                    <div class="w-1/3">
                        <div class="relative">
                            <div id="equipmentPreview" class="w-full h-48 rounded-xl bg-gray-100 overflow-hidden shadow-sm">
                                <img id="selectedEquipmentImage" src="https://images.unsplash.com/photo-1612788571571-e14fdb52c19b?w=400&q=80" 
                                    alt="Equipment" class="w-full h-full object-cover">
                            </div>
                            <div class="mt-4">
                                <div class="bg-blue-50 rounded-lg p-4">
                                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Equipment Status</h4>
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Last Service:</span>
                                            <span class="text-gray-900">Aug 15, 2025</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Condition:</span>
                                            <span class="text-green-600">Good</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600">Age:</span>
                                            <span class="text-gray-900">2.5 years</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Form Fields -->
                    <div class="flex-1 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Equipment</label>
                                <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                    onchange="updateEquipmentPreview(this.value)">
                                    <option value="stove">Industrial Stove</option>
                                    <option value="freezer">Walk-in Freezer</option>
                                    <option value="dishwasher">Dishwasher</option>
                                    <option value="mixer">Industrial Mixer</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Type</label>
                                <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option>Routine Check</option>
                                    <option>Repair</option>
                                    <option>Replacement</option>
                                    <option>Cleaning</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                                <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all"
                                    onchange="updatePriorityStyle(this)">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date</label>
                                <input type="date" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Time Slot</label>
                                <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                                    <option>Morning (8AM - 12PM)</option>
                                    <option>Afternoon (1PM - 5PM)</option>
                                    <option>Evening (6PM - 10PM)</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" 
                                rows="3" placeholder="Describe the maintenance requirements"></textarea>
                        </div>
                    </div>
                </div>

                <!-- Technician Assignment Section -->
                <div class="border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Assign Technician</label>
                    <div class="flex gap-2">
                        <div class="flex-shrink-0">
                            <img src="https://randomuser.me/api/portraits/men/1.jpg" alt="Technician" 
                                class="w-10 h-10 rounded-full">
                        </div>
                        <select class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option>John Doe (Available)</option>
                            <option>Jane Smith (Available)</option>
                            <option>Mike Johnson (Busy)</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('maintenanceModal')" 
                        class="px-6 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                        Schedule Maintenance
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Modal Functions
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

        // Image Preview Function
        function previewImage(input) {
            const preview = document.getElementById('previewImg');
            const uploadIcon = document.getElementById('uploadIcon');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    uploadIcon.classList.add('hidden');
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Form Submissions
        document.getElementById('addItemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            alert('Item added successfully!');
            toggleModal('addItemModal');
            
            // Reset form and image preview
            this.reset();
            document.getElementById('previewImg').classList.add('hidden');
            document.getElementById('uploadIcon').classList.remove('hidden');
        });

        document.getElementById('addMenuForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Add your form submission logic here
            alert('Menu added successfully!');
            toggleModal('addMenuModal');
        });

        // Equipment Preview Update Function
        function updateEquipmentPreview(equipment) {
            const imageElement = document.getElementById('selectedEquipmentImage');
            const images = {
                'stove': 'resources/stove.jpg',
                'freezer': 'resources/freezer.jpg',
                'dishwasher': 'resources/dishwasher.jpg',
                'mixer': 'resources/mixer.jpg',
            };
            imageElement.src = images[equipment] || images['stove'];
        }

        // Priority Style Update Function
        function updatePriorityStyle(select) {
            const priorityColors = {
                'low': 'bg-green-100 text-green-800',
                'medium': 'bg-yellow-100 text-yellow-800',
                'high': 'bg-orange-100 text-orange-800',
                'critical': 'bg-red-100 text-red-800'
            };
            select.className = `w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all ${priorityColors[select.value]}`;
        }

        document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            // Show success notification
            const notification = document.createElement('div');
            notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500';
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-check-circle"></i>
                    <span>Maintenance scheduled successfully!</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            // Remove notification after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);

            toggleModal('maintenanceModal');
            this.reset();
        });

        // Stock Value Trend Chart
        const stockValueCtx = document.getElementById('stockValueChart').getContext('2d');
        new Chart(stockValueCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Stock Value ($)',
                    data: [10000, 11200, 10800, 11500, 12450, 12000],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Stock Movement Analysis Chart
        const stockMovementCtx = document.getElementById('stockMovementChart').getContext('2d');
        new Chart(stockMovementCtx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Stock In',
                    data: [150, 220, 180, 200, 250, 190, 170],
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    fill: true
                }, {
                    label: 'Stock Out',
                    data: [120, 180, 160, 190, 220, 170, 150],
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Category Distribution Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: ['Dry Goods', 'Fresh Produce', 'Meat & Poultry', 'Equipment', 'Others'],
                datasets: [{
                    data: [35, 25, 20, 15, 5],
                    backgroundColor: [
                        'rgb(251, 191, 36)',
                        'rgb(34, 197, 94)',
                        'rgb(239, 68, 68)',
                        'rgb(168, 85, 247)',
                        'rgb(107, 114, 128)'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        // Monthly Cost Analysis Chart
        const costAnalysisCtx = document.getElementById('costAnalysisChart').getContext('2d');
        new Chart(costAnalysisCtx, {
            type: 'bar',
            data: {
                labels: ['Raw Materials', 'Equipment', 'Labor', 'Utilities', 'Maintenance'],
                datasets: [{
                    label: 'Budget',
                    data: [5000, 2000, 3000, 1000, 800],
                    backgroundColor: 'rgba(59, 130, 246, 0.5)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 1
                }, {
                    label: 'Actual',
                    data: [4800, 1900, 3200, 950, 700],
                    backgroundColor: 'rgba(16, 185, 129, 0.5)',
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 2,
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    </script>
</body>
</html>

