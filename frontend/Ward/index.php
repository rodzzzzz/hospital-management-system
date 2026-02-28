<?php require_once __DIR__ . '/../auth.php'; auth_session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/../config.js.php'; ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Management Dashboard - Hospital System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body class="bg-gray-50">
    <div class="min-h-screen">
        <?php include __DIR__ . '/includes/ward-sidebar.php'; ?>
        <main class="ml-64 p-8">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Ward Management Dashboard</h1>
                    <p class="text-sm text-gray-600 mt-1">Overview of all ward patients and statistics.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Pediatrics</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="statPedia">—</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-child text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">OB-GYN</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="statObgyne">—</p>
                        </div>
                        <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-venus text-pink-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Surgical</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="statSurgical">—</p>
                        </div>
                        <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-scalpel text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Medical</p>
                            <p class="text-2xl font-bold text-gray-900 mt-1" id="statMedical">—</p>
                        </div>
                        <div class="w-12 h-12 bg-green-50 rounded-xl flex items-center justify-center">
                            <i class="fas fa-heart-pulse text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">patients admitted</p>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Ward Overview</h2>
                <div class="text-center py-12 text-gray-400">
                    <i class="fas fa-hospital text-4xl mb-3"></i>
                    <p class="text-sm">Ward overview data will appear here.</p>
                </div>
            </div>
        </main>
    </div>

    <script>
        (function () {
            // Load ward statistics
            function loadWardStats() {
                // Placeholder for API integration
                document.getElementById('statPedia').textContent = '0';
                document.getElementById('statObgyne').textContent = '0';
                document.getElementById('statSurgical').textContent = '0';
                document.getElementById('statMedical').textContent = '0';
            }

            loadWardStats();
        })();
    </script>
</body>
</html>
