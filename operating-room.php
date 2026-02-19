<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Operating Room - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/double-sidebar.php'; ?>

        <main class="ml-16 lg:ml-80 p-8">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Operating Room</h1>
            </div>

            <section id="orDashboardSection">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600"><i class="fas fa-calendar-check"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Today's Surgeries</h2>
                                <p class="text-2xl font-semibold text-gray-800">8</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600"><i class="fas fa-door-open"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Theatres Available</h2>
                                <p class="text-2xl font-semibold text-gray-800">2</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-yellow-100 text-yellow-600"><i class="fas fa-procedures"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">In Progress</h2>
                                <p class="text-2xl font-semibold text-gray-800">3</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600"><i class="fas fa-clock"></i></div>
                            <div class="ml-4">
                                <h2 class="text-sm font-medium text-gray-600">Avg Turnover</h2>
                                <p class="text-2xl font-semibold text-gray-800">22m</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Upcoming Surgery Schedule</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Patient</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Procedure</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Surgeon</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Theatre</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-700">08:00</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">Maria Santos</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Appendectomy</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Dr. Lee</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">OR-2</td>
                                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Ready</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-700">09:30</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">John Dela Cruz</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Hernia Repair</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Dr. Jacobs</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">OR-1</td>
                                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Pre-op</span></td>
                                    </tr>
                                    <tr>
                                        <td class="px-6 py-4 text-sm text-gray-700">11:00</td>
                                        <td class="px-6 py-4 text-sm text-gray-900 font-medium">Emily White</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Cholecystectomy</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">Dr. Carter</td>
                                        <td class="px-6 py-4 text-sm text-gray-700">OR-3</td>
                                        <td class="px-6 py-4 text-sm"><span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Scheduled</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-gray-100">
                            <h3 class="text-lg font-semibold text-gray-900">Theatre Status</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">OR-1</p>
                                    <p class="text-xs text-gray-500">Cleaning / Turnover</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Turnover</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">OR-2</p>
                                    <p class="text-xs text-gray-500">Ready for next case</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Available</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">OR-3</p>
                                    <p class="text-xs text-gray-500">Case in progress</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">In Use</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">OR-4</p>
                                    <p class="text-xs text-gray-500">Ready for next case</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Available</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="orScheduleSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Surgery Schedule</h3>
                        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm">Add Case</button>
                    </div>
                    <div class="p-6">
                        <div class="text-sm text-gray-600">Sample schedule view (presentation only).</div>
                        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">OR-1</div>
                                        <div class="text-xs text-gray-500">09:30 - Hernia Repair</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Pre-op</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">OR-3</div>
                                        <div class="text-xs text-gray-500">11:00 - Cholecystectomy</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Scheduled</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="orCasesSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Active Cases</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            <div class="p-4 rounded-lg border border-gray-100 bg-white flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">OR-3 • Laparoscopy</div>
                                    <div class="text-xs text-gray-500">Patient: David Chen • Surgeon: Dr. Carter</div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">In Use</span>
                            </div>
                            <div class="p-4 rounded-lg border border-gray-100 bg-white flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">OR-2 • Appendectomy</div>
                                    <div class="text-xs text-gray-500">Patient: Maria Santos • Surgeon: Dr. Lee</div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">In Use</span>
                            </div>
                            <div class="p-4 rounded-lg border border-gray-100 bg-white flex items-center justify-between">
                                <div>
                                    <div class="font-semibold text-gray-900">OR-1 • Orthopedic Repair</div>
                                    <div class="text-xs text-gray-500">Patient: Sarah Lim • Surgeon: Dr. Jacobs</div>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Turnover Soon</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section id="orTheatresSection" class="hidden">
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900">Theatre Status</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">OR-1</div>
                                        <div class="text-xs text-gray-500">Cleaning / Turnover</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Turnover</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">OR-2</div>
                                        <div class="text-xs text-gray-500">Ready</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Available</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">OR-3</div>
                                        <div class="text-xs text-gray-500">Case in progress</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-red-100 text-red-700">In Use</span>
                                </div>
                            </div>
                            <div class="p-4 rounded-lg bg-gray-50 border border-gray-100">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="font-semibold text-gray-900">OR-4</div>
                                        <div class="text-xs text-gray-500">Ready</div>
                                    </div>
                                    <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-700">Available</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function setOrView(view) {
            var secDashboard = document.getElementById('orDashboardSection');
            var secSchedule = document.getElementById('orScheduleSection');
            var secCases = document.getElementById('orCasesSection');
            var secTheatres = document.getElementById('orTheatresSection');

            var sections = [
                { el: secDashboard, key: 'dashboard' },
                { el: secSchedule, key: 'schedule' },
                { el: secCases, key: 'cases' },
                { el: secTheatres, key: 'theatres' },
            ];

            sections.forEach(function (s) {
                if (!s.el) return;
                s.el.classList.toggle('hidden', view !== s.key);
            });
        }

        function applyOrViewFromHash() {
            var h = (window.location.hash || '').toLowerCase();
            if (h === '#schedule') setOrView('schedule');
            else if (h === '#cases') setOrView('cases');
            else if (h === '#theatres') setOrView('theatres');
            else setOrView('dashboard');
        }

        window.addEventListener('hashchange', applyOrViewFromHash);
        document.addEventListener('DOMContentLoaded', applyOrViewFromHash);
    </script>
</body>

</html>
