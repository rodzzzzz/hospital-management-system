<!DOCTYPE html>
<html lang="en">

<head>
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
                    <button id="createBillBtn" onclick="toggleModal('createBillModal')"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center">
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        Create New Bill
                    </button>
                </div>
            </div>

            <div id="cashierDashboardView">

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-dollar-sign"></i>
                        </div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Today's Revenue</h2>
                            <p id="cashierStatTodayRevenue" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i
                                class="fas fa-hourglass-half"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Pending Bills</h2>
                            <p id="cashierStatPendingBills" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-receipt"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Month-to-Date Revenue</h2>
                            <p id="cashierStatMonthRevenue" class="text-2xl font-semibold text-gray-800">₱0.00</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-start">
                        <div class="p-3 rounded-full bg-red-100 text-red-600"><i class="fas fa-times-circle"></i></div>
                        <div class="ml-4">
                            <h2 class="text-sm font-medium text-gray-600">Voided Transactions (Today)</h2>
                            <p id="cashierStatVoidedToday" class="text-2xl font-semibold text-gray-800">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashier Queue Section -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center space-x-4">
                            <h2 class="text-lg font-semibold text-gray-900">Cashier Queue</h2>
                            <span class="text-sm text-gray-500">Payment processing queue</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <button id="cashierCallNextBtn" onclick="callNextPatient()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                <i class="fas fa-bell"></i> Call Next
                            </button>
                            <button onclick="callNextAndMarkUnavailable()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                <i class="fas fa-user-slash"></i> Call Next & Mark Unavailable
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Currently Serving -->
                <div class="p-6 border-b border-gray-100">
                    <div class="text-sm font-medium text-gray-600 mb-1">Currently Serving:</div>
                    <div id="cashierCurrentlyServing" class="text-lg font-semibold text-red-600">No patient being served</div>
                    <div id="cashierStationSelection" class="mt-3 hidden">
                        <div class="text-sm font-medium text-gray-600 mb-1">Next Destination:</div>
                        <select id="cashierDestinationStation" class="px-3 py-1 border border-gray-300 rounded text-sm">
                            <option value="">Select destination...</option>
                        </select>
                        <button onclick="completeService()" class="ml-2 px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 text-sm">
                            <i class="fas fa-check"></i> Complete
                        </button>
                    </div>
                </div>

                <!-- Queue List -->
                <div class="p-6">
                    <div class="text-sm font-medium text-gray-600 mb-2">Waiting Patients:</div>
                    <div id="cashierQueueList" class="space-y-2">
                        <div class="text-center text-gray-400 py-8">No patients in queue</div>
                    </div>
                </div>

                <!-- Unavailable Patients -->
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-sm font-medium text-gray-600 mb-2">Unavailable Patients:</div>
                    <div id="cashierUnavailablePatientsList" class="space-y-2">
                        <div class="text-center text-gray-400 py-2">No unavailable patients</div>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-200">
                    <button onclick="openDisplayScreen()" class="w-full px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i class="fas fa-tv"></i> Open Display Screen
                    </button>
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
                        </select>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Invoice ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cashierRecentInvoicesTbody" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing 0 to 0 of 0 entries
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 bg-gray-100" disabled>Previous</button>
                            <button class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm" disabled>1</button>
                            <button class="px-3 py-1 border border-gray-300 rounded-lg text-sm text-gray-600 bg-gray-100" disabled>Next</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>

        <div id="cashierPendingChargesView" class="hidden">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 space-y-4">
                    <div class="flex flex-col md:flex-row justify-between md:items-center">
                        <h3 class="text-lg font-semibold text-gray-900">Pending Charges</h3>
                        <div class="relative mt-2 md:mt-0">
                            <input id="cashierChargesSearch" type="text" placeholder="Search by patient code or name..."
                                class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Charge ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="cashierChargesTbody" class="bg-white divide-y divide-gray-200"></tbody>
                </table>
            </div>
        </div>

        <div id="cashierInvoicesView" class="hidden">
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-100 space-y-4">
                    <div class="flex flex-col md:flex-row justify-between md:items-center gap-3">
                        <div class="flex flex-col gap-3">
                            <h3 class="text-lg font-semibold text-gray-900">Payments</h3>
                        </div>
                        <div class="flex flex-col md:flex-row md:items-center gap-3 mt-2 md:mt-0">
                            <div id="cashierPaymentsControls" class="relative">
                                <input id="cashierPaymentsSearch" type="text" placeholder="Search by patient or method..."
                                    class="pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="cashierBillingPaymentsPanel">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Invoice ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cashierPaymentsTbody" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
            </div>
        </div>
        </main>
    </div>

    <div id="chargeDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Charge Details</h3>
                    <button onclick="toggleModal('chargeDetailsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="flex flex-col md:flex-row md:justify-between gap-2">
                        <div>
                            <div class="text-sm text-gray-600">Patient</div>
                            <div id="chargeDetailsPatient" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Charge ID</div>
                            <div id="chargeDetailsId" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Total</div>
                            <div id="chargeDetailsTotal" class="text-sm font-bold text-gray-900"></div>
                        </div>
                    </div>
                    <div id="chargeDetailsWarning" class="mt-3 hidden text-sm text-red-600"></div>
                    <div id="chargeDetailsSources" class="mt-3 flex flex-wrap gap-2"></div>
                </div>
                <div id="chargeDetailsLabSection" class="hidden space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="text-sm font-semibold text-gray-900">Lab Bill</div>
                        <div class="text-sm text-gray-700">Total: <span id="chargeDetailsLabTotal" class="font-medium">₱0.00</span></div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="chargeDetailsLabItems" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
                <div id="chargeDetailsPharmacySection" class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div id="chargeDetailsItemsTitle" class="text-sm font-semibold text-gray-900">Pharmacy Bill</div>
                        <div class="text-sm text-gray-700">Total: <span id="chargeDetailsPharmacyTotal" class="font-medium">₱0.00</span></div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Medicine</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody id="chargeDetailsItems" class="bg-white divide-y divide-gray-200"></tbody>
                        </table>
                    </div>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('chargeDetailsModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Close
                    </button>
                    <button id="chargeDetailsCreateInvoiceBtn" type="button" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Process Payment
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="invoiceDetailsModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-3xl relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Invoice Details</h3>
                    <button onclick="toggleModal('invoiceDetailsModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                        <div class="lg:col-span-2">
                            <div class="text-sm text-gray-600">Patient</div>
                            <div id="invoiceDetailsPatient" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Invoice ID</div>
                            <div id="invoiceDetailsId" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-600">Change</div>
                            <div id="invoiceDetailsBalance" class="text-sm font-bold text-gray-900"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Total</div>
                            <div id="invoiceDetailsTotal" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Paid</div>
                            <div id="invoiceDetailsPaid" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-600">Status</div>
                            <div id="invoiceDetailsStatus" class="text-sm font-medium text-gray-800"></div>
                        </div>
                        <div class="sm:col-span-2 lg:col-span-1">
                            <div class="text-sm text-gray-600">Received By</div>
                            <div id="invoiceDetailsReceivedBy" class="text-sm font-medium text-gray-800"></div>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unit Price</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="invoiceDetailsItems" class="bg-white divide-y divide-gray-200"></tbody>
                    </table>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('invoiceDetailsModal')" class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Close
                    </button>
                    <button id="invoiceDetailsPayBtn" type="button" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Process Payment
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Process Payment Modal -->
    <div id="processPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md relative z-50">
            <div class="p-6 border-b border-gray-100">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">Process Payment</h3>
                    <button onclick="toggleModal('processPaymentModal')" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <form id="processPaymentForm" class="p-6">
                <input type="hidden" id="payInvoiceId" value="">
                <input type="hidden" id="payChargeId" value="">
                <input type="hidden" id="payTotalDue" value="">
                <div class="space-y-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            <div>
                                <div class="text-sm text-gray-600">Patient</div>
                                <div id="payPatient" class="text-sm font-medium text-gray-800">-</div>
                            </div>
                            <div>
                                <div class="text-sm text-gray-600">Reference</div>
                                <div id="payInvoiceLabel" class="text-sm font-medium text-gray-800 sm:text-right">-</div>
                            </div>
                            <div class="sm:col-span-2 flex items-baseline justify-between pt-2 border-t border-gray-200">
                                <span class="text-sm font-semibold text-gray-900">Total Price</span>
                                <span id="payTotalPrice" class="text-lg font-bold text-red-600">₱0.00</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Payment Method</label>
                        <select
                            id="payMethod"
                            class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option>Cash</option>
                            <option>Credit Card</option>
                            <option>Insurance</option>
                            <option>Bank Transfer</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Amount Received</label>
                        <input id="payAmount" type="number" step="0.01"
                            class="mt-1 w-full px-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="text-right">
                        <span class="text-sm text-gray-600">Change: </span>
                        <span id="payChange" class="text-sm font-medium text-green-600">₱0.00</span>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="toggleModal('processPaymentModal')"
                        class="px-4 py-2 border border-gray-200 text-gray-600 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        Confirm Payment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Initialize Charts
        document.addEventListener('DOMContentLoaded', function () {
            // Hourly Transaction Volume Chart
            const hourlyVolumeCtx = document.getElementById('hourlyVolumeChart').getContext('2d');
            const hourlyVolumeChart = new Chart(hourlyVolumeCtx, {
                type: 'bar',
                data: {
                    labels: ['8-10am', '10-12pm', '12-2pm', '2-4pm', '4-6pm', '6-8pm'],
                    datasets: [{
                        label: 'Transactions',
                        data: [0, 0, 0, 0, 0, 0],
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
            const departmentRevenueChart = new Chart(departmentRevenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Pharmacy', 'Consultation', 'Lab Tests', 'Radiology', 'Other'],
                    datasets: [{
                        label: 'Revenue ($)',
                        data: [0, 0, 0, 0, 0],
                        backgroundColor: ['#34D399', '#60A5FA', '#F59E0B', '#A78BFA', '#9CA3AF'],
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
            const billStatusChart = new Chart(billStatusCtx, {
                type: 'pie',
                data: {
                    labels: ['Paid', 'Partial', 'Unpaid'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: ['#34D399', '#60A5FA', '#FBBF24'],
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
            const paymentMethodChart = new Chart(paymentMethodCtx, {
                type: 'doughnut',
                data: {
                    labels: ['cash', 'credit_card', 'insurance'],
                    datasets: [{
                        data: [0, 0, 0],
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
            const createBillFormEl = document.getElementById('createBillForm');
            if (createBillFormEl) {
                createBillFormEl.addEventListener('input', updateBillTotals);
            }

            window.cashierCharts = {
                hourlyVolumeChart,
                departmentRevenueChart,
                billStatusChart,
                paymentMethodChart,
            };

            const chargesSearch = document.getElementById('cashierChargesSearch');
            if (chargesSearch) {
                let t = null;
                chargesSearch.addEventListener('input', function () {
                    if (t) window.clearTimeout(t);
                    t = window.setTimeout(() => loadCharges(chargesSearch.value), 250);
                });
            }

            const paymentsSearch = document.getElementById('cashierPaymentsSearch');
            if (paymentsSearch) {
                let t = null;
                paymentsSearch.addEventListener('input', function () {
                    if (t) window.clearTimeout(t);
                    t = window.setTimeout(() => loadPayments(paymentsSearch.value), 250);
                });
            }

            window.addEventListener('hashchange', applyCashierViewFromHash);
            applyCashierViewFromHash();
        });

        function escapeHtml(str) {
            const s = (str ?? '').toString();
            return s
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#039;');
        }

        function money(v) {
            const n = Number(v ?? 0) || 0;
            try {
                return new Intl.NumberFormat('en-PH', {
                    style: 'currency',
                    currency: 'PHP',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(n);
            } catch (e) {
                return '₱' + n.toFixed(2);
            }
        }

        function renderEmptyRow(tbody, colCount, message) {
            if (!tbody) return;
            const msg = escapeHtml(message || 'No records');
            tbody.innerHTML = `
                <tr>
                    <td class="px-6 py-6 text-center text-sm text-gray-500" colspan="${String(colCount)}">${msg}</td>
                </tr>
            `;
        }

        async function apiGet(url) {
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                throw new Error((json && json.error) ? json.error : 'Request failed');
            }
            return json;
        }

        async function apiPost(url, payload) {
            const res = await fetch(url, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(payload || {}),
            });
            const json = await res.json().catch(() => null);
            if (!res.ok || !json || !json.ok) {
                throw new Error((json && json.error) ? json.error : 'Request failed');
            }
            return json;
        }

        function applyCashierViewFromHash() {
            const raw = (window.location.hash || '').toString().replace(/^#/, '').toLowerCase();
            const isPayments = (raw === 'payments');
            const view = (raw === 'pending-charges' || isPayments) ? (isPayments ? 'payments' : raw) : 'dashboard';

            try {
                window.scrollTo(0, 0);
            } catch (e0) {
            }

            const dash = document.getElementById('cashierDashboardView');
            const charges = document.getElementById('cashierPendingChargesView');
            const invoices = document.getElementById('cashierInvoicesView');

            if (dash) dash.classList.toggle('hidden', view !== 'dashboard');
            if (charges) charges.classList.toggle('hidden', view !== 'pending-charges');
            if (invoices) invoices.classList.toggle('hidden', view !== 'payments');

            const createBillBtn = document.getElementById('createBillBtn');
            if (createBillBtn) createBillBtn.classList.toggle('hidden', view !== 'dashboard');

            if (view === 'pending-charges') {
                const chargesSearch = document.getElementById('cashierChargesSearch');
                const qp = (new URLSearchParams(window.location.search).get('q') || '').toString();
                if (chargesSearch && qp && !(chargesSearch.value || '').toString().trim()) {
                    chargesSearch.value = qp;
                }
                const q = (chargesSearch?.value ?? '').toString();
                loadCharges(q);
            } else if (view === 'payments') {
                const q = (document.getElementById('cashierPaymentsSearch')?.value ?? '').toString();
                loadPayments(q);
            } else {
                loadRecentInvoices();
                loadDashboardStats();
            }
        }

        async function loadDashboardStats() {
            try {
                const json = await apiGet('api/cashier/stats.php');
                const cards = json.cards || {};

                const elToday = document.getElementById('cashierStatTodayRevenue');
                if (elToday) elToday.textContent = money(cards.today_revenue);
                const elPending = document.getElementById('cashierStatPendingBills');
                if (elPending) elPending.textContent = String(Number(cards.pending_charges ?? 0) || 0);
                const elMonth = document.getElementById('cashierStatMonthRevenue');
                if (elMonth) elMonth.textContent = money(cards.month_revenue);
                const elVoid = document.getElementById('cashierStatVoidedToday');
                if (elVoid) elVoid.textContent = String(Number(cards.voided_today ?? 0) || 0);

                const charts = json.charts || {};
                const c = window.cashierCharts || {};

                if (c.hourlyVolumeChart && charts.hourly_volume) {
                    c.hourlyVolumeChart.data.labels = Array.isArray(charts.hourly_volume.labels) ? charts.hourly_volume.labels : c.hourlyVolumeChart.data.labels;
                    c.hourlyVolumeChart.data.datasets[0].data = Array.isArray(charts.hourly_volume.series) ? charts.hourly_volume.series : c.hourlyVolumeChart.data.datasets[0].data;
                    c.hourlyVolumeChart.update();
                }

                if (c.departmentRevenueChart && charts.department_revenue) {
                    c.departmentRevenueChart.data.labels = Array.isArray(charts.department_revenue.labels) ? charts.department_revenue.labels : c.departmentRevenueChart.data.labels;
                    c.departmentRevenueChart.data.datasets[0].data = Array.isArray(charts.department_revenue.series) ? charts.department_revenue.series : c.departmentRevenueChart.data.datasets[0].data;
                    c.departmentRevenueChart.update();
                }

                if (c.billStatusChart && charts.bill_status) {
                    c.billStatusChart.data.labels = Array.isArray(charts.bill_status.labels) ? charts.bill_status.labels : c.billStatusChart.data.labels;
                    c.billStatusChart.data.datasets[0].data = Array.isArray(charts.bill_status.series) ? charts.bill_status.series : c.billStatusChart.data.datasets[0].data;
                    c.billStatusChart.update();
                }

                if (c.paymentMethodChart && charts.payment_method) {
                    c.paymentMethodChart.data.labels = Array.isArray(charts.payment_method.labels) ? charts.payment_method.labels : c.paymentMethodChart.data.labels;
                    c.paymentMethodChart.data.datasets[0].data = Array.isArray(charts.payment_method.series) ? charts.payment_method.series : c.paymentMethodChart.data.datasets[0].data;
                    c.paymentMethodChart.update();
                }
            } catch (e) {
            }
        }

        async function loadRecentInvoices() {
            const tbody = document.getElementById('cashierRecentInvoicesTbody');
            if (!tbody) return;
            try {
                const json = await apiGet('api/cashier/list_invoices.php');
                const rows = Array.isArray(json.invoices) ? json.invoices.slice(0, 10) : [];
                if (rows.length === 0) {
                    renderEmptyRow(tbody, 6, 'No invoices yet');
                    return;
                }
                tbody.innerHTML = rows.map(inv => {
                    const id = Number(inv.id || 0);
                    const status = (inv.status || '').toString();
                    const chip = status === 'paid'
                        ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>'
                        : (status === 'partial'
                            ? '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Partial</span>'
                            : '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Unpaid</span>');

                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#${escapeHtml(String(id))}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${escapeHtml(inv.full_name || '')}</div>
                                <div class="text-sm text-gray-500">${escapeHtml(inv.patient_code || '')}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${money(inv.total)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml((inv.created_at || '').toString())}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${chip}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="p-1 text-blue-600 hover:text-blue-800" type="button" onclick="openInvoiceDetails(${id})"><i class="fas fa-eye"></i></button>
                                <button class="p-1 text-green-600 hover:text-green-800" type="button" onclick="openPaymentForInvoice(${id})"><i class="fas fa-credit-card"></i></button>
                            </td>
                        </tr>
                    `;
                }).join('');
            } catch (e) {
                renderEmptyRow(tbody, 6, 'Failed to load invoices');
            }
        }

        async function loadCharges(q = '') {
            const tbody = document.getElementById('cashierChargesTbody');
            if (!tbody) return;
            try {
                const groupUrl = 'api/cashier/list_charges.php?status=pending_invoice&group=patient' + (q ? ('&q=' + encodeURIComponent(q)) : '');
                const allUrl = 'api/cashier/list_charges.php?status=pending_invoice' + (q ? ('&q=' + encodeURIComponent(q)) : '');

                const [groupJson, allJson] = await Promise.all([apiGet(groupUrl), apiGet(allUrl)]);
                const grouped = Array.isArray(groupJson.charges) ? groupJson.charges : [];
                const allRows = Array.isArray(allJson.charges) ? allJson.charges : [];

                const others = allRows.filter(r => {
                    const src = (r.source_module || '').toString();
                    return (src !== 'lab_request' && src !== 'pharmacy_resit');
                });

                const rows = grouped.concat(others);
                if (rows.length === 0) {
                    renderEmptyRow(tbody, 7, 'No pending charges');
                    return;
                }
                tbody.innerHTML = rows.map(c => {
                    const isGroup = (c.lab_charge_id !== undefined || c.pharmacy_charge_id !== undefined);

                    const statusText = (c.status || '').toString();
                    const statusChip = (statusText === 'pending_invoice')
                        ? '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending Charge</span>'
                        : '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">' + escapeHtml(statusText) + '</span>';

                    if (isGroup) {
                        const patientId = Number(c.patient_id || 0);
                        const labChargeId = (c.lab_charge_id === null || c.lab_charge_id === undefined || c.lab_charge_id === '') ? null : Number(c.lab_charge_id);
                        const pharmacyChargeId = (c.pharmacy_charge_id === null || c.pharmacy_charge_id === undefined || c.pharmacy_charge_id === '') ? null : Number(c.pharmacy_charge_id);

                        const chargeCell = `
                            ${labChargeId ? `<div class="text-sm text-gray-500">Lab #${escapeHtml(String(labChargeId))}</div>` : ''}
                            ${pharmacyChargeId ? `<div class="text-sm text-gray-500">Pharmacy #${escapeHtml(String(pharmacyChargeId))}</div>` : ''}
                        `;

                        const sourceCell = `
                            ${c.lab_source_id ? `<div class="text-sm text-gray-500">lab_request #${escapeHtml(String(c.lab_source_id))}</div>` : ''}
                            ${c.pharmacy_source_id ? `<div class="text-sm text-gray-500">pharmacy_resit #${escapeHtml(String(c.pharmacy_source_id))}</div>` : ''}
                        `;

                        return `
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">${chargeCell}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${escapeHtml(c.full_name || '')}</div>
                                    <div class="text-sm text-gray-500">${escapeHtml(c.patient_code || '')}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">${sourceCell}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${money(c.total)}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${statusChip}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml((c.created_at || '').toString())}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="px-3 py-1 text-blue-600 hover:text-blue-800" type="button" onclick="openPatientBills(${patientId}, ${labChargeId || 'null'}, ${pharmacyChargeId || 'null'})">View</button>
                                    ${(labChargeId ? `<button class="px-3 py-1 text-green-600 hover:text-green-800" type="button" onclick="openPaymentForCharge(${labChargeId})">Pay (Lab)</button>` : '')}
                                    ${(pharmacyChargeId ? `<button class="px-3 py-1 text-green-600 hover:text-green-800" type="button" onclick="openPaymentForCharge(${pharmacyChargeId})">Pay (Pharmacy)</button>` : '')}
                                </td>
                            </tr>
                        `;
                    }

                    const id = Number(c.id || 0);

                    const payBtn = `<button class="px-3 py-1 text-green-600 hover:text-green-800" type="button" onclick="openPaymentForCharge(${id})">Pay</button>`;

                    return `
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#${escapeHtml(String(id))}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">${escapeHtml(c.full_name || '')}</div>
                                    <div class="text-sm text-gray-500">${escapeHtml(c.patient_code || '')}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(c.source_module || '')} #${escapeHtml(String(c.source_id || ''))}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${money(c.total)}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${statusChip}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml((c.created_at || '').toString())}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button class="px-3 py-1 text-blue-600 hover:text-blue-800" type="button" onclick="openChargeDetails(${id})">View</button>
                                    ${payBtn}
                                </td>
                            </tr>
                        `;
                }).join('');
            } catch (e) {
                renderEmptyRow(tbody, 7, 'Failed to load charges');
                showNotification(e.message || 'Failed to load charges', 'error');
            }
        }

        async function openPatientBills(patientId, labChargeId, pharmacyChargeId) {
            try {
                const tasks = [];
                const out = { lab: null, pharmacy: null };
                if (labChargeId) {
                    tasks.push(apiGet('api/cashier/get_charge.php?charge_id=' + encodeURIComponent(String(labChargeId)))
                        .then(j => { out.lab = j.charge || null; }));
                }
                if (pharmacyChargeId) {
                    tasks.push(apiGet('api/cashier/get_charge.php?charge_id=' + encodeURIComponent(String(pharmacyChargeId)))
                        .then(j => { out.pharmacy = j.charge || null; }));
                }

                await Promise.all(tasks);
                const lab = out.lab;
                const pharmacy = out.pharmacy;
                if (!lab && !pharmacy) {
                    throw new Error('No bills found for patient');
                }

                const itemsTitle = document.getElementById('chargeDetailsItemsTitle');
                if (itemsTitle) itemsTitle.textContent = 'Pharmacy Bill';

                const patientCode = (lab?.patient_code || pharmacy?.patient_code || '').toString();
                const fullName = (lab?.full_name || pharmacy?.full_name || '').toString();
                document.getElementById('chargeDetailsPatient').textContent = (patientCode ? (patientCode + ' - ') : '') + fullName;

                const idParts = [];
                if (lab) idParts.push('Lab #' + String(lab.id || labChargeId));
                if (pharmacy) idParts.push('Pharmacy #' + String(pharmacy.id || pharmacyChargeId));
                document.getElementById('chargeDetailsId').textContent = idParts.join(' | ');

                const total = (Number(lab?.total || 0) || 0) + (Number(pharmacy?.total || 0) || 0);
                document.getElementById('chargeDetailsTotal').textContent = money(total);

                const labSection = document.getElementById('chargeDetailsLabSection');
                const pharmacySection = document.getElementById('chargeDetailsPharmacySection');
                if (labSection) labSection.classList.toggle('hidden', !lab);
                if (pharmacySection) pharmacySection.classList.toggle('hidden', !pharmacy);

                const labTotalEl = document.getElementById('chargeDetailsLabTotal');
                if (labTotalEl) labTotalEl.textContent = money(lab?.total || 0);
                const pharmacyTotalEl = document.getElementById('chargeDetailsPharmacyTotal');
                if (pharmacyTotalEl) pharmacyTotalEl.textContent = money(pharmacy?.total || 0);

                const warning = document.getElementById('chargeDetailsWarning');
                const hasMissingPrice = !!(pharmacy && pharmacy.has_missing_price);
                if (warning) {
                    warning.classList.toggle('hidden', !hasMissingPrice);
                    warning.textContent = hasMissingPrice ? 'Some medicines have missing price. Please set prices in Pharmacy → Medicines before invoicing.' : '';
                }

                const sources = document.getElementById('chargeDetailsSources');
                if (sources) {
                    sources.innerHTML = '';
                }

                const labItemsTbody = document.getElementById('chargeDetailsLabItems');
                if (labItemsTbody) {
                    const items = Array.isArray(lab?.items) ? lab.items : [];
                    labItemsTbody.innerHTML = items.map(it => {
                        const unit = (it.unit_price === null || it.unit_price === undefined || it.unit_price === '') ? '-' : money(it.unit_price);
                        return `
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">${escapeHtml(it.medicine_name || '')}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(String(it.qty || ''))}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(unit)}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">${money(it.subtotal)}</td>
                            </tr>
                        `;
                    }).join('');
                }

                const pharmItemsTbody = document.getElementById('chargeDetailsItems');
                if (pharmItemsTbody) {
                    const items = Array.isArray(pharmacy?.items) ? pharmacy.items : [];
                    pharmItemsTbody.innerHTML = items.map(it => {
                        const unit = (it.unit_price === null || it.unit_price === undefined || it.unit_price === '') ? '-' : money(it.unit_price);
                        return `
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">${escapeHtml(it.medicine_name || '')}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(String(it.qty || ''))}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(unit)}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">${money(it.subtotal)}</td>
                            </tr>
                        `;
                    }).join('');
                }

                const createBtn = document.getElementById('chargeDetailsCreateInvoiceBtn');
                if (createBtn) {
                    const targetChargeId = pharmacy ? (Number(pharmacy?.id || 0) || pharmacyChargeId) : (Number(lab?.id || 0) || labChargeId);
                    createBtn.textContent = 'Process Payment';
                    createBtn.disabled = !!hasMissingPrice || !targetChargeId;
                    createBtn.onclick = function () {
                        toggleModal('chargeDetailsModal');
                        openPaymentForCharge(targetChargeId);
                    };
                }

                toggleModal('chargeDetailsModal');
            } catch (e) {
                showNotification(e.message || 'Failed to load bills', 'error');
            }
        }

        async function loadPayments(q = '') {
            const tbody = document.getElementById('cashierPaymentsTbody');
            if (!tbody) return;
            try {
                const params = ['status=paid'];
                if (q) params.push('q=' + encodeURIComponent(q));
                const url = 'api/cashier/list_invoices.php' + (params.length ? ('?' + params.join('&')) : '');
                const json = await apiGet(url);
                const rows = Array.isArray(json.invoices) ? json.invoices : [];
                if (rows.length === 0) {
                    renderEmptyRow(tbody, 8, 'No payments');
                    return;
                }
                tbody.innerHTML = rows.map(inv => {
                    const id = Number(inv.id || 0);
                    const statusText = (inv.status || '').toString();
                    const lastChangeNum = Number(inv.last_change || 0) || 0;
                    const chip = statusText === 'paid'
                        ? '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>'
                        : (statusText === 'partial'
                            ? '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Partial</span>'
                            : '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Unpaid</span>');

                    const dt = (inv.last_paid_at || inv.created_at || '').toString();
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">#${escapeHtml(String(id))}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${escapeHtml(inv.full_name || '')}</div>
                                <div class="text-sm text-gray-500">${escapeHtml(inv.patient_code || '')}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${money(inv.total)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${money(inv.paid)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">${money(lastChangeNum)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${chip}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(dt)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="px-3 py-1 text-blue-600 hover:text-blue-800" type="button" onclick="openInvoiceDetails(${id})">View</button>
                            </td>
                        </tr>
                    `;
                }).join('');
            } catch (e) {
                renderEmptyRow(tbody, 8, 'Failed to load payments');
                showNotification(e.message || 'Failed to load payments', 'error');
            }
        }

        async function openChargeDetails(chargeId) {
            try {
                const json = await apiGet('api/cashier/get_charge.php?charge_id=' + encodeURIComponent(String(chargeId)));
                const c = json.charge || {};

                const labSection = document.getElementById('chargeDetailsLabSection');
                const pharmacySection = document.getElementById('chargeDetailsPharmacySection');

                const src = (c.source_module || '').toString();
                const isLab = (src === 'lab_request');

                const itemsTitle = document.getElementById('chargeDetailsItemsTitle');
                if (itemsTitle) {
                    itemsTitle.textContent = (src === 'pharmacy_resit') ? 'Pharmacy Bill' : 'Charge Items';
                }

                if (labSection) labSection.classList.toggle('hidden', !isLab);
                if (pharmacySection) pharmacySection.classList.remove('hidden');

                document.getElementById('chargeDetailsPatient').textContent = (c.patient_code ? (c.patient_code + ' - ') : '') + (c.full_name || '');
                document.getElementById('chargeDetailsId').textContent = '#' + String(c.id || chargeId);
                document.getElementById('chargeDetailsTotal').textContent = money(c.total);

                const pharmacyTotalEl = document.getElementById('chargeDetailsPharmacyTotal');
                if (pharmacyTotalEl) pharmacyTotalEl.textContent = money(c.total);

                const labTotalEl = document.getElementById('chargeDetailsLabTotal');
                if (labTotalEl) labTotalEl.textContent = money(c.total);

                const warning = document.getElementById('chargeDetailsWarning');
                if (warning) {
                    warning.classList.toggle('hidden', !c.has_missing_price);
                    warning.textContent = c.has_missing_price ? 'Some medicines have missing price. Please set prices in Pharmacy → Medicines before invoicing.' : '';
                }

                const sources = document.getElementById('chargeDetailsSources');
                if (sources) {
                    sources.innerHTML = '';
                }

                const itemsTbody = document.getElementById('chargeDetailsItems');
                if (itemsTbody) {
                    const items = Array.isArray(c.items) ? c.items : [];
                    itemsTbody.innerHTML = items.map(it => {
                        const unit = (it.unit_price === null || it.unit_price === undefined || it.unit_price === '') ? '-' : money(it.unit_price);
                        return `
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">${escapeHtml(it.medicine_name || '')}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(String(it.qty || ''))}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(unit)}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">${money(it.subtotal)}</td>
                            </tr>
                        `;
                    }).join('');
                }

                const labItemsTbody = document.getElementById('chargeDetailsLabItems');
                if (labItemsTbody) {
                    const items = Array.isArray(c.items) ? c.items : [];
                    labItemsTbody.innerHTML = items.map(it => {
                        const unit = (it.unit_price === null || it.unit_price === undefined || it.unit_price === '') ? '-' : money(it.unit_price);
                        return `
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">${escapeHtml(it.medicine_name || '')}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(String(it.qty || ''))}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(unit)}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">${money(it.subtotal)}</td>
                            </tr>
                        `;
                    }).join('');
                }

                const createBtn = document.getElementById('chargeDetailsCreateInvoiceBtn');
                if (createBtn) {
                    createBtn.textContent = 'Process Payment';
                    createBtn.disabled = !!c.has_missing_price;
                    createBtn.onclick = function () {
                        toggleModal('chargeDetailsModal');
                        openPaymentForCharge(chargeId);
                    };
                }

                toggleModal('chargeDetailsModal');
            } catch (e) {
                showNotification(e.message || 'Failed to load charge', 'error');
            }
        }

        async function openInvoiceDetails(invoiceId) {
            try {
                const json = await apiGet('api/cashier/get_invoice.php?invoice_id=' + encodeURIComponent(String(invoiceId)));
                const inv = json.invoice || {};
                document.getElementById('invoiceDetailsPatient').textContent = (inv.patient_code ? (inv.patient_code + ' - ') : '') + (inv.full_name || '');
                document.getElementById('invoiceDetailsId').textContent = '#' + String(inv.id || invoiceId);
                document.getElementById('invoiceDetailsTotal').textContent = money(inv.total);
                document.getElementById('invoiceDetailsPaid').textContent = money(inv.paid);
                document.getElementById('invoiceDetailsBalance').textContent = money((inv.last_change !== undefined ? inv.last_change : 0));
                document.getElementById('invoiceDetailsStatus').textContent = (inv.status || '').toString();
                const rb = document.getElementById('invoiceDetailsReceivedBy');
                if (rb) rb.textContent = (inv.last_received_by || '-').toString();

                const itemsTbody = document.getElementById('invoiceDetailsItems');
                if (itemsTbody) {
                    const items = Array.isArray(inv.items) ? inv.items : [];
                    itemsTbody.innerHTML = items.map(it => {
                        return `
                            <tr>
                                <td class="px-4 py-2 text-sm text-gray-900">${escapeHtml(it.description || '')}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${escapeHtml(String(it.qty || ''))}</td>
                                <td class="px-4 py-2 text-sm text-gray-700">${money(it.unit_price)}</td>
                                <td class="px-4 py-2 text-sm text-gray-900">${money(it.subtotal)}</td>
                            </tr>
                        `;
                    }).join('');
                }

                const payBtn = document.getElementById('invoiceDetailsPayBtn');
                if (payBtn) {
                    const bal = (Number(inv.balance || 0) || 0);
                    const st = (inv.status || '').toString().toLowerCase();
                    const isPaid = st === 'paid' || bal <= 0;
                    payBtn.disabled = isPaid;
                    payBtn.classList.toggle('hidden', isPaid);
                    payBtn.onclick = function () {
                        toggleModal('invoiceDetailsModal');
                        openPaymentForInvoice(invoiceId);
                    };
                }

                toggleModal('invoiceDetailsModal');
            } catch (e) {
                showNotification(e.message || 'Failed to load invoice', 'error');
            }
        }

        async function openPaymentForInvoice(invoiceId) {
            try {
                const json = await apiGet('api/cashier/get_invoice.php?invoice_id=' + encodeURIComponent(String(invoiceId)));
                const inv = json.invoice || {};
                const idEl = document.getElementById('payInvoiceId');
                if (idEl) idEl.value = String(inv.id || invoiceId);
                const chargeIdEl = document.getElementById('payChargeId');
                if (chargeIdEl) chargeIdEl.value = '';
                const patEl = document.getElementById('payPatient');
                if (patEl) patEl.textContent = (inv.patient_code ? (inv.patient_code + ' - ') : '') + (inv.full_name || '');
                const labelEl = document.getElementById('payInvoiceLabel');
                if (labelEl) labelEl.textContent = '#' + String(inv.id || invoiceId);
                const dueNum = (Number(inv.balance || 0) || 0);
                const dueEl = document.getElementById('payTotalPrice');
                if (dueEl) dueEl.textContent = money(dueNum);
                const dueHidden = document.getElementById('payTotalDue');
                if (dueHidden) dueHidden.value = String(dueNum);
                const amtEl = document.getElementById('payAmount');
                if (amtEl) amtEl.value = String(dueNum);
                updatePayChange();
                toggleModal('processPaymentModal');
            } catch (e) {
                showNotification(e.message || 'Failed to open payment', 'error');
            }
        }

        async function openPaymentForCharge(chargeId) {
            try {
                const json = await apiGet('api/cashier/get_charge.php?charge_id=' + encodeURIComponent(String(chargeId)));
                const c = json.charge || {};
                if (c.has_missing_price) {
                    showNotification('Some items have missing price. Please set prices before payment.', 'error');
                    return;
                }

                const invoiceIdEl = document.getElementById('payInvoiceId');
                if (invoiceIdEl) invoiceIdEl.value = '';
                const chargeIdEl = document.getElementById('payChargeId');
                if (chargeIdEl) chargeIdEl.value = String(c.id || chargeId);

                const patEl = document.getElementById('payPatient');
                if (patEl) patEl.textContent = (c.patient_code ? (c.patient_code + ' - ') : '') + (c.full_name || '');

                const labelEl = document.getElementById('payInvoiceLabel');
                if (labelEl) labelEl.textContent = 'Charge #' + String(c.id || chargeId);

                const dueNum = (Number(c.total || 0) || 0);
                const dueEl = document.getElementById('payTotalPrice');
                if (dueEl) dueEl.textContent = money(dueNum);
                const dueHidden = document.getElementById('payTotalDue');
                if (dueHidden) dueHidden.value = String(dueNum);

                const amtEl = document.getElementById('payAmount');
                if (amtEl) amtEl.value = String(dueNum);

                updatePayChange();
                toggleModal('processPaymentModal');
            } catch (e) {
                showNotification(e.message || 'Failed to open payment', 'error');
            }
        }

        function updatePayChange() {
            const dueRaw = (document.getElementById('payTotalDue')?.value ?? '').toString();
            const receivedRaw = (document.getElementById('payAmount')?.value ?? '').toString();
            const due = Number(dueRaw || 0);
            const received = Number(receivedRaw || 0);
            const change = (received > due) ? (received - due) : 0;
            const changeEl = document.getElementById('payChange');
            if (changeEl) changeEl.textContent = money(change);
        }

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

            document.getElementById('billSubtotal').textContent = money(subtotal);
            document.getElementById('billTax').textContent = money(taxAmount);
            document.getElementById('billTotal').textContent = money(total);
        }

        function resetBillForm() {
            const form = document.getElementById('createBillForm');
            if (!form) return;
            form.reset();

            // Remove extra billable items, leave one
            const itemsContainer = document.getElementById('billableItems');
            if (!itemsContainer) {
                updateBillTotals();
                return;
            }
            while (itemsContainer.children.length > 1) {
                itemsContainer.removeChild(itemsContainer.lastChild);
            }

            // Reset totals
            updateBillTotals();
        }

        // Form Submissions
        const createBillFormSubmitEl = document.getElementById('createBillForm');
        if (createBillFormSubmitEl) {
            createBillFormSubmitEl.addEventListener('submit', function (e) {
                e.preventDefault();
                // Here you would typically gather the form data and send to a server
                // For now, we just show a notification and close
                showNotification('Bill created successfully!', 'success');
                toggleModal('createBillModal');
                // The reset is now handled by the toggleModal function when closing
            });
        }

        const processPaymentFormEl = document.getElementById('processPaymentForm');
        if (processPaymentFormEl) {
            processPaymentFormEl.addEventListener('submit', function (e) {
                e.preventDefault();
                (async () => {
                    try {
                        const invoiceIdRaw = (document.getElementById('payInvoiceId')?.value ?? '').toString();
                        const chargeIdRaw = (document.getElementById('payChargeId')?.value ?? '').toString();
                        const amountRaw = (document.getElementById('payAmount')?.value ?? '').toString();
                        const methodRaw = (document.getElementById('payMethod')?.value ?? '').toString();
                        const invoiceId = Number(invoiceIdRaw || 0);
                        const chargeId = Number(chargeIdRaw || 0);
                        const amount = Number(amountRaw || 0);
                        if ((!invoiceId || invoiceId < 1) && (!chargeId || chargeId < 1)) {
                            showNotification('Invalid reference', 'error');
                            return;
                        }
                        if (!amount || amount <= 0) {
                            showNotification('Invalid amount', 'error');
                            return;
                        }

                        if (chargeId && chargeId > 0) {
                            await apiPost('api/cashier/pay_charge.php', {
                                charge_id: chargeId,
                                amount: amount,
                                method: (methodRaw || 'cash').toLowerCase().replace(/\s+/g, '_'),
                            });
                        } else {
                            await apiPost('api/cashier/add_payment.php', {
                                invoice_id: invoiceId,
                                amount: amount,
                                method: (methodRaw || 'cash').toLowerCase().replace(/\s+/g, '_'),
                            });
                        }

                        showNotification('Payment processed successfully!', 'success');
                        toggleModal('processPaymentModal');
                        this.reset();

                        if ((window.location.hash || '') === '#pending-charges') {
                            loadCharges((document.getElementById('cashierChargesSearch')?.value ?? '').toString());
                        }
                        loadPayments((document.getElementById('cashierPaymentsSearch')?.value ?? '').toString());
                        loadRecentInvoices();

                        if (chargeId && chargeId > 0) {
                            window.location.hash = '#payments';
                        }
                    } catch (err) {
                        showNotification(err.message || 'Failed to process payment', 'error');
                    }
                })();
            });
        }

        const payAmtInput = document.getElementById('payAmount');
        if (payAmtInput) {
            payAmtInput.addEventListener('input', updatePayChange);
            payAmtInput.addEventListener('change', updatePayChange);
        }

        // Cashier Queue Management Functions
        let currentCashierQueueData = null;

        async function loadCashierQueue() {
            try {
                const response = await fetch('/api/queue/display/4'); // Cashier station ID is 4
                currentCashierQueueData = await response.json();
                updateCashierQueueDisplay();
            } catch (error) {
                console.error('Error loading Cashier queue:', error);
            }
        }

        function getQueueEntryId(row) {
            if (!row) return 0;
            const v = row.queue_id ?? row.queue_entry_id ?? row.id;
            return Number(v || 0);
        }

        function updateCashierQueueDisplay() {
            if (!currentCashierQueueData) return;

            const callNextBtn = document.getElementById('cashierCallNextBtn');
            if (callNextBtn) {
                const disabled = !!currentCashierQueueData.currently_serving;
                callNextBtn.disabled = disabled;
                callNextBtn.classList.toggle('opacity-50', disabled);
                callNextBtn.classList.toggle('cursor-not-allowed', disabled);
            }

            // Update currently serving
            const currentlyServingDiv = document.getElementById('cashierCurrentlyServing');
            if (currentCashierQueueData.currently_serving) {
                currentlyServingDiv.innerHTML = `
                    <div class="font-semibold">${currentCashierQueueData.currently_serving.full_name}</div>
                    <div class="text-sm text-gray-600">${currentCashierQueueData.currently_serving.queue_number}</div>
                `;
                
                // Show station selection dropdown
                document.getElementById('cashierStationSelection').classList.remove('hidden');
                loadCashierStationOptions();
            } else {
                currentlyServingDiv.innerHTML = '<span class="text-gray-400">No patient being served</span>';
                document.getElementById('cashierStationSelection').classList.add('hidden');
            }

            // Update queue list
            const queueListDiv = document.getElementById('cashierQueueList');
            if (currentCashierQueueData.next_patients && currentCashierQueueData.next_patients.length > 0) {
                queueListDiv.innerHTML = currentCashierQueueData.next_patients.map((patient, index) => `
                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <span class="font-semibold text-red-600">${patient.queue_number}</span>
                            <div>
                                <div class="font-medium">${patient.full_name}</div>
                                <div class="text-sm text-gray-600">${patient.patient_code}</div>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500">
                            Est. ${index * 5} min
                        </div>
                    </div>
                `).join('');
            } else {
                queueListDiv.innerHTML = '<div class="text-center text-gray-400 py-8">No patients in queue</div>';
            }

            // Update unavailable patients
            const unavailableDiv = document.getElementById('cashierUnavailablePatientsList');
            if (unavailableDiv) {
                if (currentCashierQueueData.unavailable_patients && currentCashierQueueData.unavailable_patients.length > 0) {
                    unavailableDiv.innerHTML = currentCashierQueueData.unavailable_patients.map(patient => `
                        <div class="flex justify-between items-center p-2 bg-orange-50 rounded border border-orange-200 cursor-pointer hover:bg-orange-100" onclick="recallCashierUnavailablePatient(${getQueueEntryId(patient)})">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <div class="font-medium">${patient.full_name}</div>
                                    <div class="text-sm text-gray-600">${patient.patient_code}</div>
                                </div>
                                <div class="text-sm text-orange-600">
                                    ${patient.updated_at ? new Date(patient.updated_at).toLocaleTimeString() : ''}
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    unavailableDiv.innerHTML = '<div class="text-center text-gray-400 py-2">No unavailable patients</div>';
                }
            }
        }

        function loadCashierStationOptions() {
            const select = document.getElementById('cashierDestinationStation');
            select.innerHTML = '<option value="">Select destination...</option>';
            
            // Add discharge option
            const dischargeOption = document.createElement('option');
            dischargeOption.value = 'discharge';
            dischargeOption.textContent = 'Complete and Discharge';
            select.appendChild(dischargeOption);
            
            // Add other stations
            fetch('/api/queue/stations')
                .then(response => response.json())
                .then(data => {
                    data.stations.forEach(station => {
                        if (station.id !== 4) { // Don't show current station
                            const option = document.createElement('option');
                            option.value = station.id;
                            option.textContent = station.station_display_name;
                            select.appendChild(option);
                        }
                    });
                });
        }

        async function callNextPatient() {
            try {
                const response = await fetch('/api/queue/call-next', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ station_id: 4 })
                });
                
                const result = await response.json();
                if (result.success) {
                    showNotification('Next patient called successfully', 'success');
                    loadCashierQueue();
                } else {
                    showNotification(result.message || 'No patients in queue', 'warning');
                }
            } catch (error) {
                console.error('Error calling next patient:', error);
                showNotification('Error calling next patient', 'error');
            }
        }

        async function recallCashierUnavailablePatient(queueId) {
            try {
                const response = await fetch('/api/queue/recall-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        queue_id: queueId
                    })
                });

                const result = await response.json();
                if (result.success) {
                    showNotification('Patient recalled successfully', 'success');
                    loadCashierQueue();
                } else {
                    showNotification(result.message || 'Unable to recall patient', 'warning');
                }
            } catch (error) {
                console.error('Error recalling unavailable patient:', error);
                showNotification('Error recalling patient', 'error');
            }
        }

        async function callNextAndMarkUnavailable() {
            try {
                const response = await fetch('/api/queue/call-next-mark-unavailable', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        station_id: 4
                    })
                });
                
                const result = await response.json();
                if (result.success) {
                    showNotification('Next patient called and previous marked unavailable', 'success');
                    loadCashierQueue();
                } else {
                    showNotification(result.message || 'No patients in queue', 'warning');
                }
            } catch (error) {
                console.error('Error calling next and marking unavailable:', error);
                showNotification('Error calling next patient', 'error');
            }
        }

        async function completeService() {
            if (!currentCashierQueueData?.currently_serving) {
                showNotification('No patient currently being served', 'warning');
                return;
            }

            const destinationSelect = document.getElementById('cashierDestinationStation');
            const selectedDestination = destinationSelect.value;
            
            if (!selectedDestination) {
                showNotification('Please select a destination station', 'warning');
                return;
            }

            try {
                let endpoint = '/api/queue/complete-service';
                let body = { 
                    queue_id: currentCashierQueueData.currently_serving.id
                };
                
                if (selectedDestination !== 'discharge') {
                    body.target_station_id = parseInt(selectedDestination);
                }
                
                const response = await fetch(endpoint, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                
                const result = await response.json();
                if (result.success) {
                    showNotification(selectedDestination === 'discharge' ? 'Patient discharged successfully' : 'Payment completed successfully', 'success');
                    loadCashierQueue();
                    
                    // Reset selection
                    destinationSelect.value = '';
                    document.getElementById('cashierStationSelection').classList.add('hidden');
                } else {
                    showNotification('Error completing payment', 'error');
                }
            } catch (error) {
                console.error('Error completing service:', error);
                showNotification('Error completing payment', 'error');
            }
        }

        function openDisplayScreen() {
            window.open('cashier-display.php', '_blank');
        }

        // Auto-refresh queue every 10 seconds
        setInterval(loadCashierQueue, 10000);
        
        // Initial load
        loadCashierQueue();
</script>
</body>
</html>
