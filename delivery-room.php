<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Room - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Delivery Room</h1>
            </div>

            <section id="drDashboardSection">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-pink-100 text-pink-600"><i class="fas fa-baby"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Deliveries Today</h2>
                                <p class="text-2xl font-semibold text-gray-800">5</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-users"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Labor Queue</h2>
                                <p class="text-2xl font-semibold text-gray-800">3</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-user-md"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">OB On Duty</h2>
                                <p class="text-2xl font-semibold text-gray-800">2</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-bed"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Delivery Rooms Free</h2>
                                <p class="text-2xl font-semibold text-gray-800">1</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Labor Queue</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Age</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gestation</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Midwife</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">Ana Reyes</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">28</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">39w</td>
                                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Active Labor</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Nurse Mae</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">Liza Cruz</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">32</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">38w</td>
                                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Monitoring</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Nurse Joy</td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">Karen Lim</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">24</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">40w</td>
                                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Ready</span></td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Nurse Mae</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Newborn Care (Today)</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Baby A</p>
                                    <p class="text-xs text-gray-500">APGAR: 8/9 • Weight: 3.2kg</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Stable</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Baby B</p>
                                    <p class="text-xs text-gray-500">APGAR: 7/9 • Weight: 2.9kg</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Observe</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">Baby C</p>
                                    <p class="text-xs text-gray-500">APGAR: 8/10 • Weight: 3.4kg</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Stable</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="drLaborQueueSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Labor Queue</h3>
                        <button class="px-4 py-2 rounded-lg bg-pink-600 text-white text-sm">Add Patient</button>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-600">Sample labor queue view (presentation only).</div>
                        <div class="mt-4 space-y-3">
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">Ana Reyes</div>
                                        <div class="text-xs text-gray-500">39 weeks • Active labor</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Active</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">Liza Cruz</div>
                                        <div class="text-xs text-gray-500">38 weeks • Monitoring</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Monitoring</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="drDeliveriesSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Delivery Records</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date/Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mother</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Delivery Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">OB</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Outcome</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">2026-02-01 06:12</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Karen Lim</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Normal Spontaneous</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Dr. Santos</td>
                                    <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Stable</span></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">2026-02-01 04:45</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Ana Reyes</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Assisted</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Dr. Mendoza</td>
                                    <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Observe</span></td>
                                </tr>
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-700">2026-01-31 23:10</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 font-medium">Liza Cruz</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">C-Section</td>
                                    <td class="px-6 py-4 text-sm text-gray-700">Dr. Santos</td>
                                    <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Stable</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="drNewbornSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Newborn Care</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">Baby A</div>
                                        <div class="text-xs text-gray-500">APGAR: 8/9 • Weight: 3.2kg</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Stable</span>
                                </div>
                                <div class="mt-3 text-xs text-gray-600">Feeding started • Vitamin K given</div>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">Baby B</div>
                                        <div class="text-xs text-gray-500">APGAR: 7/9 • Weight: 2.9kg</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Observe</span>
                                </div>
                                <div class="mt-3 text-xs text-gray-600">Warmth monitoring • Glucose checks</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function setDrView(view) {
            var secDashboard = document.getElementById('drDashboardSection');
            var secLabor = document.getElementById('drLaborQueueSection');
            var secDeliveries = document.getElementById('drDeliveriesSection');
            var secNewborn = document.getElementById('drNewbornSection');

            var sections = [
                { el: secDashboard, key: 'dashboard' },
                { el: secLabor, key: 'labor-queue' },
                { el: secDeliveries, key: 'deliveries' },
                { el: secNewborn, key: 'newborn' },
            ];

            sections.forEach(function (s) {
                if (!s.el) return;
                s.el.classList.toggle('hidden', view !== s.key);
            });
        }

        function applyDrViewFromHash() {
            var h = (window.location.hash || '').toLowerCase();
            if (h === '#labor-queue') setDrView('labor-queue');
            else if (h === '#deliveries') setDrView('deliveries');
            else if (h === '#newborn') setDrView('newborn');
            else setDrView('dashboard');
        }

        window.addEventListener('hashchange', applyDrViewFromHash);
        document.addEventListener('DOMContentLoaded', applyDrViewFromHash);
    </script>
</body>

</html>
