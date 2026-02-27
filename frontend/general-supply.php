<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>General Supply Management - Hospital System</title>
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
        <main class="ml-16 lg:ml-80 p-8" id="main-content">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">General Supply Management</h1>
                <p class="text-gray-500">Manage and track hospital supplies</p>
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
                                <p class="text-2xl font-semibold text-gray-800">387</p>
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
                    <img src="https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=400&q=80" alt="Supplies"
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
                                <p class="text-2xl font-semibold text-gray-800">23</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-red-600 text-sm font-medium">
                                    <i class="fas fa-arrow-up"></i> 4%
                                </span>
                                <span class="ml-2 text-sm text-gray-600">requires attention</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=400&q=80" alt="Alert"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>

                <!-- Monthly Budget -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Monthly Budget</h2>
                                <p class="text-2xl font-semibold text-gray-800">$8,450</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-green-600 text-sm font-medium">
                                    <i class="fas fa-check"></i> On Track
                                </span>
                                <span class="ml-2 text-sm text-gray-600">75% utilized</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?w=400&q=80" alt="Budget"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>

                <!-- Orders Status -->
                <div class="bg-white rounded-lg shadow p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-shipping-fast"></i>
                            </div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Pending Orders</h2>
                                <p class="text-2xl font-semibold text-gray-800">12</p>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div class="flex items-center">
                                <span class="text-purple-600 text-sm font-medium">
                                    <i class="fas fa-clock"></i> Processing
                                </span>
                                <span class="ml-2 text-sm text-gray-600">5 urgent</span>
                            </div>
                        </div>
                    </div>
                    <img src="https://images.unsplash.com/photo-1566576912321-d58ddd7a6088?w=400&q=80" alt="Orders"
                        class="absolute top-0 right-0 w-20 h-full object-cover opacity-10">
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <button onclick="toggleModal('requestSuppliesModal')" 
                    class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all flex items-center space-x-3">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-cart-plus"></i>
                    </div>
                    <span class="font-medium text-gray-700">Request Supplies</span>
                </button>

                <button onclick="toggleModal('addItemModal')" 
                    class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all flex items-center space-x-3">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <i class="fas fa-plus"></i>
                    </div>
                    <span class="font-medium text-gray-700">Add New Item</span>
                </button>

                <button onclick="toggleModal('generateReportModal')" 
                    class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all flex items-center space-x-3">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="font-medium text-gray-700">Generate Report</span>
                </button>

                <button onclick="toggleModal('approveRequestsModal')" 
                    class="p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-all flex items-center space-x-3">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <span class="font-medium text-gray-700">Approve Requests</span>
                </button>
            </div>

            <!-- Supply Management Section -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h2 class="text-xl font-semibold text-gray-800">Supply Inventory</h2>
                        <div class="flex space-x-2">
                            <div class="relative">
                                <input type="text" placeholder="Search items..." 
                                    class="pl-10 pr-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select class="px-4 py-2 border rounded-lg focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <option>All Categories</option>
                                <option>Office Supplies</option>
                                <option>Cleaning Materials</option>
                                <option>Safety Equipment</option>
                                <option>Electronics</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gray-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Restock</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Office Paper -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 relative group">
                                            <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=100&q=80" 
                                                alt="Office Paper" class="w-full h-full rounded-lg object-cover shadow-sm">
                                            <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">A4 Paper</div>
                                            <div class="text-sm text-gray-500">500 sheets/ream</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                        Office Supplies
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">150 reams</div>
                                    <div class="text-xs text-green-600">Well Stocked</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">$4.50</div>
                                    <div class="text-xs text-gray-500">per ream</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Sep 10, 2025</div>
                                    <div class="text-xs text-gray-500">4 days ago</div>
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

                            <!-- Cleaning Supplies -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 relative group">
                                            <img src="https://images.unsplash.com/photo-1585842378054-ee2e52f94ba2?w=100&q=80" 
                                                alt="Cleaning Supplies" class="w-full h-full rounded-lg object-cover shadow-sm">
                                            <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Surface Cleaner</div>
                                            <div class="text-sm text-gray-500">5L container</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                        Cleaning Materials
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">28 units</div>
                                    <div class="text-xs text-yellow-600">Low Stock</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">$12.99</div>
                                    <div class="text-xs text-gray-500">per unit</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Aug 25, 2025</div>
                                    <div class="text-xs text-gray-500">20 days ago</div>
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

                            <!-- Safety Equipment -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center space-x-4">
                                        <div class="flex-shrink-0 w-14 h-14 relative group">
                                            <img src="https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=100&q=80" 
                                                alt="Safety Mask" class="w-full h-full rounded-lg object-cover shadow-sm">
                                            <div class="absolute inset-0 bg-blue-600 bg-opacity-0 group-hover:bg-opacity-10 rounded-lg transition-all duration-200"></div>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">Safety Masks</div>
                                            <div class="text-sm text-gray-500">Box of 50</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">
                                        Safety Equipment
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">75 boxes</div>
                                    <div class="text-xs text-green-600">Sufficient</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">$15.99</div>
                                    <div class="text-xs text-gray-500">per box</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">Sep 5, 2025</div>
                                    <div class="text-xs text-gray-500">9 days ago</div>
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
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Analytics Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Consumption Trend -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Consumption Trend</h3>
                    <canvas id="consumptionChart" class="w-full" height="200"></canvas>
                </div>

                <!-- Category Distribution -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Category Distribution</h3>
                    <canvas id="categoryChart" class="w-full" height="200"></canvas>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">Recent Activities</h3>
                    <button class="text-blue-600 hover:text-blue-800">View All</button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600 mr-4">
                            <i class="fas fa-box"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">New stock received: Office Supplies</p>
                            <p class="text-xs text-gray-500">2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600 mr-4">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Low stock alert: Cleaning Materials</p>
                            <p class="text-xs text-gray-500">5 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <div class="p-3 rounded-full bg-green-100 text-green-600 mr-4">
                            <i class="fas fa-check"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Supply request approved: Department A</p>
                            <p class="text-xs text-gray-500">1 day ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Request Supplies Modal -->
    <div id="requestSuppliesModal" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-2xl mx-4 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-medium text-gray-900">Request Supplies</h3>
                <button onclick="toggleModal('requestSuppliesModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="requestSuppliesForm" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option>Administration</option>
                            <option>Emergency Room</option>
                            <option>ICU</option>
                            <option>Laboratory</option>
                            <option>Radiology</option>
                        </select>
                    </div>
                    
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Requested Items</label>
                        <div id="requestedItems" class="space-y-3">
                            <div class="flex items-center gap-4">
                                <select class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                    <option>A4 Paper</option>
                                    <option>Surface Cleaner</option>
                                    <option>Safety Masks</option>
                                    <option>Hand Sanitizer</option>
                                </select>
                                <input type="number" placeholder="Quantity" min="1" 
                                    class="w-32 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                                <button type="button" onclick="removeRequestItem(this)" class="p-2 text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <button type="button" onclick="addRequestItem()" 
                            class="mt-2 text-blue-600 hover:text-blue-800 text-sm font-medium flex items-center">
                            <i class="fas fa-plus mr-1"></i> Add Another Item
                        </button>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority Level</label>
                        <div class="flex gap-4">
                            <label class="flex-1 flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="priority" value="low" class="mr-2">
                                <span class="text-sm font-medium">Low</span>
                            </label>
                            <label class="flex-1 flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="priority" value="medium" class="mr-2">
                                <span class="text-sm font-medium">Medium</span>
                            </label>
                            <label class="flex-1 flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="priority" value="high" class="mr-2">
                                <span class="text-sm font-medium">High</span>
                            </label>
                        </div>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Additional Notes</label>
                        <textarea rows="3" placeholder="Any special requirements or instructions" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200"></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('requestSuppliesModal')" 
                        class="px-6 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                        Submit Request
                    </button>
                </div>
            </form>
        </div>
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
                                    <option>Office Supplies</option>
                                    <option>Cleaning Materials</option>
                                    <option>Safety Equipment</option>
                                    <option>Electronics</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Stock Level</label>
                                <input type="number" class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" placeholder="Minimum quantity">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all" rows="3" placeholder="Item description and specifications"></textarea>
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

    <!-- Generate Report Modal -->
    <div id="generateReportModal" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-2xl mx-4 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-medium text-gray-900">Generate Report</h3>
                <button onclick="toggleModal('generateReportModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <form id="generateReportForm" class="space-y-6">
                <div class="grid grid-cols-2 gap-6">
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="reportType" value="inventory" class="mr-2">
                                <div>
                                    <span class="text-sm font-medium block">Inventory Status</span>
                                    <span class="text-xs text-gray-500">Current stock levels and values</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="reportType" value="consumption" class="mr-2">
                                <div>
                                    <span class="text-sm font-medium block">Consumption Analysis</span>
                                    <span class="text-xs text-gray-500">Usage patterns and trends</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="reportType" value="requests" class="mr-2">
                                <div>
                                    <span class="text-sm font-medium block">Request Summary</span>
                                    <span class="text-xs text-gray-500">Department-wise requests</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border rounded-lg cursor-pointer hover:bg-gray-50">
                                <input type="radio" name="reportType" value="costs" class="mr-2">
                                <div>
                                    <span class="text-sm font-medium block">Cost Analysis</span>
                                    <span class="text-xs text-gray-500">Expenditure and budgeting</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option>Last 7 Days</option>
                            <option>Last 30 Days</option>
                            <option>Last Quarter</option>
                            <option>Custom Range</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                        <select class="w-full px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition-all">
                            <option>PDF Report</option>
                            <option>Excel Spreadsheet</option>
                            <option>CSV File</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Include Sections</label>
                        <div class="grid grid-cols-2 gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded text-blue-600">
                                <span class="text-sm">Executive Summary</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded text-blue-600">
                                <span class="text-sm">Detailed Analysis</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded text-blue-600">
                                <span class="text-sm">Charts & Graphs</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="mr-2 rounded text-blue-600">
                                <span class="text-sm">Recommendations</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" onclick="toggleModal('generateReportModal')" 
                        class="px-6 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Approve Requests Modal -->
    <div id="approveRequestsModal" class="fixed inset-0 bg-black bg-opacity-30 backdrop-blur-sm hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-4xl mx-4 transform transition-all">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-2xl font-medium text-gray-900">Pending Requests</h3>
                <button onclick="toggleModal('approveRequestsModal')" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="mb-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                        <select class="px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <option>All Departments</option>
                            <option>Emergency Room</option>
                            <option>ICU</option>
                            <option>Laboratory</option>
                        </select>
                        <select class="px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                            <option>All Priorities</option>
                            <option>High</option>
                            <option>Medium</option>
                            <option>Low</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-sort-amount-down mr-1"></i> Sort
                        </button>
                        <button class="px-4 py-2 text-gray-600 hover:text-gray-800">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                    </div>
                </div>
            </div>

            <div class="overflow-y-auto max-h-96 mb-6">
                <!-- Request Item -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" class="rounded text-blue-600">
                            <div>
                                <h4 class="font-medium">Emergency Room</h4>
                                <span class="text-sm text-gray-500">Request #ER-2025-092</span>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-800">High Priority</span>
                        </div>
                        <span class="text-sm text-gray-500">Requested: 2 hours ago</span>
                    </div>
                    <div class="pl-8">
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center space-x-4">
                                <img src="https://images.unsplash.com/photo-1584515979956-d9f6e5d09982?w=50&q=80" 
                                    class="w-10 h-10 rounded-lg object-cover">
                                <div>
                                    <p class="font-medium">Safety Masks</p>
                                    <p class="text-sm text-gray-500">Box of 50</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">10 boxes</p>
                                <p class="text-sm text-gray-500">$159.90</p>
                            </div>
                        </div>
                        <!-- More items... -->
                    </div>
                </div>

                <!-- Another Request -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <input type="checkbox" class="rounded text-blue-600">
                            <div>
                                <h4 class="font-medium">Laboratory</h4>
                                <span class="text-sm text-gray-500">Request #LAB-2025-045</span>
                            </div>
                            <span class="px-3 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Medium Priority</span>
                        </div>
                        <span class="text-sm text-gray-500">Requested: 5 hours ago</span>
                    </div>
                    <div class="pl-8">
                        <div class="flex items-center justify-between py-2 border-b">
                            <div class="flex items-center space-x-4">
                                <img src="https://images.unsplash.com/photo-1589939705384-5185137a7f0f?w=50&q=80" 
                                    class="w-10 h-10 rounded-lg object-cover">
                                <div>
                                    <p class="font-medium">A4 Paper</p>
                                    <p class="text-sm text-gray-500">500 sheets/ream</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="font-medium">20 reams</p>
                                <p class="text-sm text-gray-500">$90.00</p>
                            </div>
                        </div>
                        <!-- More items... -->
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-between pt-4 border-t">
                <div class="flex items-center space-x-4">
                    <button class="px-4 py-2 text-red-600 hover:text-red-800">
                        <i class="fas fa-times-circle mr-1"></i> Reject Selected
                    </button>
                    <span class="text-sm text-gray-500">2 items selected</span>
                </div>
                <div class="flex space-x-3">
                    <button onclick="toggleModal('approveRequestsModal')" 
                        class="px-6 py-2.5 rounded-lg text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                        Cancel
                    </button>
                    <button type="button" 
                        class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all duration-200">
                        Approve Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Consumption Trend Chart
        const consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
        new Chart(consumptionCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Office Supplies',
                    data: [650, 590, 800, 810, 760, 850],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true
                }, {
                    label: 'Cleaning Materials',
                    data: [450, 480, 460, 520, 540, 580],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
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
                labels: ['Office Supplies', 'Cleaning Materials', 'Safety Equipment', 'Electronics', 'Others'],
                datasets: [{
                    data: [40, 25, 15, 12, 8],
                    backgroundColor: [
                        'rgb(59, 130, 246)',
                        'rgb(16, 185, 129)',
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

        // Add Request Item Function
        function addRequestItem() {
            const container = document.getElementById('requestedItems');
            const newItem = document.createElement('div');
            newItem.className = 'flex items-center gap-4';
            newItem.innerHTML = `
                <select class="flex-1 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                    <option>A4 Paper</option>
                    <option>Surface Cleaner</option>
                    <option>Safety Masks</option>
                    <option>Hand Sanitizer</option>
                </select>
                <input type="number" placeholder="Quantity" min="1" 
                    class="w-32 px-4 py-2 rounded-lg border border-gray-200 focus:border-blue-500 focus:ring-2 focus:ring-blue-200">
                <button type="button" onclick="removeRequestItem(this)" class="p-2 text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            `;
            container.appendChild(newItem);
        }

        // Remove Request Item Function
        function removeRequestItem(button) {
            button.closest('.flex').remove();
        }

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
        document.getElementById('requestSuppliesForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Supply request submitted successfully!', 'success');
            toggleModal('requestSuppliesModal');
            this.reset();
        });

        document.getElementById('addItemForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('New item added successfully!', 'success');
            toggleModal('addItemModal');
            this.reset();
            document.getElementById('previewImg').classList.add('hidden');
            document.getElementById('uploadIcon').classList.remove('hidden');
        });

        document.getElementById('generateReportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Report generated successfully!', 'success');
            toggleModal('generateReportModal');
            this.reset();
        });

        // Notification Function
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed bottom-4 right-4 ${type === 'success' ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-500`;
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

        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Supply Categories Distribution Chart
            const categoryCtx = document.getElementById('categoryChart').getContext('2d');
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Office Supplies', 'Cleaning Materials', 'Safety Equipment', 'Medical Supplies', 'Others'],
                    datasets: [{
                        data: [30, 25, 20, 15, 10],
                        backgroundColor: [
                            '#60A5FA', '#34D399', '#F59E0B', '#EC4899', '#8B5CF6'
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

            // Monthly Consumption Trends Chart
            const consumptionCtx = document.getElementById('consumptionChart').getContext('2d');
            new Chart(consumptionCtx, {
                type: 'line',
                data: {
                    labels: ['Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep'],
                    datasets: [{
                        label: 'Consumption',
                        data: [6500, 5900, 8000, 8100, 7800, 7400],
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

            // Supplier Performance Chart
            const supplierCtx = document.getElementById('supplierChart').getContext('2d');
            new Chart(supplierCtx, {
                type: 'bar',
                data: {
                    labels: ['ABC Corp', 'MedSupply', 'SafetyFirst', 'CleanPro', 'OfficeMart'],
                    datasets: [{
                        label: 'On-time Delivery',
                        data: [95, 88, 92, 85, 90],
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
                            max: 100,
                            grid: {
                                display: true,
                                drawBorder: false
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + '%';
                                }
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
    </script>
</body>
</html>

