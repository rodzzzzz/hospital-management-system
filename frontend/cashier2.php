<?php require_once __DIR__ . '/auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier & Billing - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>
        <!-- Main Content -->
        <main class="ml-16 lg:ml-80 p-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Cashier & Billing</h1>
                <div class="flex space-x-4">
                    <button onclick="toggleModal('createBillModal')" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Create New Bill
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-dollar-sign"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Today's Revenue</h2>
                            <p class="text-2xl font-semibold text-gray-800">$12,450</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-hourglass-half"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Pending Bills</h2>
                            <p class="text-2xl font-semibold text-gray-800">32</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-receipt"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Month-to-Date Revenue</h2>
                            <p class="text-2xl font-semibold text-gray-800">$186,300</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-times-circle"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Voided Transactions (Today)</h2>
                            <p class="text-2xl font-semibold text-gray-800">4</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Hourly Transaction Volume</h3>
                    <canvas id="hourlyVolumeChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Department</h3>
                    <canvas id="departmentRevenueChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Bill Status Overview</h3>
                    <canvas id="billStatusChart" class="w-full h-64"></canvas>
                </div>
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Revenue by Payment Method</h3>
                    <canvas id="paymentMethodChart" class="w-full h-64"></canvas>
                </div>
            </div>

            <!-- Recent Transactions Table -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 space-y-4">
                    <div class="flex flex-col md:flex-row justify-between md:items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Transactions</h3>
                        <div class="relative mt-2 md:mt-0">
                            <input type="text" placeholder="Search by patient or invoice..." 
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-sm font-medium text-gray-700">From:</label>
                            <input type="date" class="px-3 py-1 border border-gray-200 rounded-lg text-sm">
                            <label class="text-sm font-medium text-gray-700">To:</label>
                            <input type="date" class="px-3 py-1 border border-gray-200 rounded-lg text-sm">
                        </div>
                        <select class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm">
                            <option value="">All Statuses</option>
                            <option value="paid">Paid</option>
                            <option value="pending">Pending</option>
                            <option value="overdue">Overdue</option>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Sample Row 1 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#INV-2024-0715</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Maria Garcia</div>
                                    <div class="text-sm text-gray-500">maria.garcia@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$250.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 15, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                    <button class="p-1 text-gray-600 hover:text-gray-800"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <!-- Sample Row 2 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#INV-2024-0714</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Robert Smith</div>
                                    <div class="text-sm text-gray-500">robert.smith@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$120.50</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 15, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                    <button onclick="toggleModal('processPaymentModal')" class="p-1 text-green-600 hover:text-green-800"><i class="fas fa-credit-card"></i></button>
                                </td>
                            </tr>
                             <!-- Sample Row 3 -->
                             <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#INV-2024-0713</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Linda Johnson</div>
                                    <div class="text-sm text-gray-500">linda.j@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$85.75</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 14, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Overdue</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                    <button onclick="toggleModal('processPaymentModal')" class="p-1 text-green-600 hover:text-green-800"><i class="fas fa-credit-card"></i></button>
                                </td>
                            </tr>
                            <!-- Sample Row 4 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#INV-2024-0712</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">David Chen</div>
                                    <div class="text-sm text-gray-500">david.chen@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$310.20</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 14, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                    <button class="p-1 text-gray-600 hover:text-gray-800"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                            <!-- Sample Row 5 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#INV-2024-0711</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Emily White</div>
                                    <div class="text-sm text-gray-500">emily.w@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$55.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 13, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                    <button onclick="toggleModal('processPaymentModal')" class="p-1 text-green-600 hover:text-green-800"><i class="fas fa-credit-card"></i></button>
                                </td>
                            </tr>
                            <!-- Sample Row 6 -->
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#INV-2024-0710</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">Michael Brown</div>
                                    <div class="text-sm text-gray-500">michael.b@example.com</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">$425.00</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">July 12, 2024</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="p-1 text-blue-600 hover:text-blue-800"><i class="fas fa-eye"></i></button>
                                    <button class="p-1 text-gray-600 hover:text-gray-800"><i class="fas fa-print"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing 1 to 10 of 57 entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">Previous</button>
                            <button class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm">1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

        <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function() {
            // Hourly Transaction Volume Chart
            const hourlyVolumeCtx = document.getElementById('hourlyVolumeChart').getContext('2d');
            new Chart(hourlyVolumeCtx, {
                type: 'bar',
                data: {
                    labels: ['8-10am', '10-12pm', '12-2pm', '2-4pm', '4-6pm', '6-8pm'],
                    datasets: [{
                        label: 'Transactions',
                        data: [15, 28, 22, 35, 18, 9],
                        borderColor: '#3B82F6',
                        backgroundColor: '#60A5FA20',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: true } }
                }
            });

            // Revenue by Department Chart
            const departmentRevenueCtx = document.getElementById('departmentRevenueChart').getContext('2d');
            new Chart(departmentRevenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Pharmacy', 'Consultation', 'Lab Tests', 'Radiology', 'Surgery'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [45000, 32000, 28000, 21000, 56000],
                        backgroundColor: ['#34D399', '#60A5FA', '#F59E0B', '#A78BFA', '#EC4899'],
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: { x: { beginAtZero: true } }
                }
            });

            // Bill Status Chart
            const billStatusCtx = document.getElementById('billStatusChart').getContext('2d');
            new Chart(billStatusCtx, {
                type: 'pie',
                data: {
                    labels: ['Paid', 'Pending', 'Overdue'],
                    datasets: [{
                        data: [112, 32, 14],
                        backgroundColor: ['#34D399', '#FBBF24', '#F87171'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    },
                    cutout: '0%'
                }
            });

            // Payment Method Chart
            const paymentMethodCtx = document.getElementById('paymentMethodChart').getContext('2d');
            new Chart(paymentMethodCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Credit Card', 'Insurance'],
                    datasets: [{
                        data: [45, 35, 20],
                        backgroundColor: ['#34D399', '#60A5FA', '#F59E0B'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    cutout: '70%'
                }
            });

            // Add event listener for dynamic bill calculation
            document.getElementById('createBillForm').addEventListener('input', updateBillTotals);
        });

        // Modal toggle function
        function toggleModal(modalId) {
            const modal = document.getElementById(modalId);
            if (modal) {
                if (modalId === 'createBillModal' && !modal.classList.contains('hidden')) {
                    // Reset form when closing
                    resetBillForm();
                }
                // Only toggle the 'hidden' class for visibility
                // The 'flex' class should remain for layout
                modal.classList.toggle('hidden'); 
            }
        }

        // Notification Function
        function showNotification(message, type = 'success') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: 'right',
                backgroundColor: type === 'success' ? '#10B981' : '#EF4444',
            }).showToast();
        }

        // --- Create Bill Modal Functionality ---

        function addBillableItem() {
            const itemsContainer = document.getElementById('billableItems');
            const itemRow = document.createElement('div');
            itemRow.className = 'flex items-center gap-4';
            itemRow.innerHTML = `
                <input type="text" placeholder="Service or Item" class="bill-item-description flex-1 px-4 py-2 border border-gray-200 rounded-lg">
                <input type="number" placeholder="Qty" value="1" min="1" class="bill-item-qty w-20 px-4 py-2 border border-gray-200 rounded-lg">
                <input type="number" placeholder="Price" step="0.01" min="0" class="bill-item-price w-28 px-4 py-2 border border-gray-200 rounded-lg">
                <button type="button" onclick="removeBillableItem(this)" class="p-2 text-red-500 hover:text-red-700"><i class="fas fa-trash"></i></button>
            `;
            itemsContainer.appendChild(itemRow);
        }

        function removeBillableItem(button) {
            button.closest('.flex').remove();
            updateBillTotals();
        }

        function updateBillTotals() {
            const items = document.querySelectorAll('#billableItems .flex');
            let subtotal = 0;

            items.forEach(item => {
                const qty = parseFloat(item.querySelector('.bill-item-qty').value) || 0;
                const price = parseFloat(item.querySelector('.bill-item-price').value) || 0;
                subtotal += qty * price;
            });

            const discountPercent = parseFloat(document.getElementById('billDiscount').value) || 0;
            const discountAmount = subtotal * (discountPercent / 100);
            
            const subtotalAfterDiscount = subtotal - discountAmount;
            
            const taxRate = 0.12; // 12%
            const taxAmount = subtotalAfterDiscount * taxRate;

            const total = subtotalAfterDiscount + taxAmount;

            document.getElementById('billSubtotal').textContent = `$${subtotal.toFixed(2)}`;
            document.getElementById('billTax').textContent = `$${taxAmount.toFixed(2)}`;
            document.getElementById('billTotal').textContent = `$${total.toFixed(2)}`;
        }

        function resetBillForm() {
            const form = document.getElementById('createBillForm');
            form.reset();

            // Remove extra billable items, leave one
            const itemsContainer = document.getElementById('billableItems');
            while (itemsContainer.children.length > 1) {
                itemsContainer.removeChild(itemsContainer.lastChild);
            }

            // Reset totals
            updateBillTotals();
        }

        // Form Submissions
        document.getElementById('createBillForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Here you would typically gather the form data and send to a server
            // For now, we just show a notification and close
            showNotification('Bill created successfully!', 'success');
            toggleModal('createBillModal');
            // The reset is now handled by the toggleModal function when closing
        });

        document.getElementById('processPaymentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            showNotification('Payment processed successfully!', 'success');
            toggleModal('processPaymentModal');
            this.reset();
        });
    </script>
</body>
</html>


