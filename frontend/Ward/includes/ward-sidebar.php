<?php
// Get current page
$currentPage = basename($_SERVER['PHP_SELF']);

// Ward navigation items
$wardNavItems = [
    [
        'href' => 'index.php',
        'label' => 'Dashboard',
        'icon' => 'fas fa-chart-line',
    ],
    [
        'href' => 'pediatrics-ward.php',
        'label' => 'Pediatrics Ward',
        'icon' => 'fas fa-child',
    ],
    [
        'href' => 'obgyn-ward.php',
        'label' => 'OB-GYN Ward',
        'icon' => 'fas fa-venus',
    ],
    [
        'href' => 'medical-ward.php',
        'label' => 'Medical Ward',
        'icon' => 'fas fa-heart-pulse',
    ],
    [
        'href' => 'ward-census.php',
        'label' => 'Ward Census',
        'icon' => 'fas fa-list-check',
    ],
    [
        'href' => 'nurses-notes.php',
        'label' => "Nurse's Notes",
        'icon' => 'fas fa-notes-medical',
    ],
];
?>

<!-- Ward Sidebar -->
<aside class="fixed left-0 top-0 h-screen w-64 bg-white shadow-lg z-40">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-900">Ward Management</h2>
            <p class="text-sm text-gray-500 mt-1">Patient Care System</p>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 overflow-y-auto p-4">
            <ul class="space-y-2">
                <?php foreach ($wardNavItems as $item): ?>
                    <?php
                    $isActive = ($currentPage === $item['href']);
                    $linkClass = 'flex items-center gap-x-3 px-4 py-3 rounded-lg transition-colors duration-200 ' . 
                                ($isActive ? 'bg-blue-600 text-white shadow-md' : 'text-gray-700 hover:bg-gray-100');
                    ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($item['href'], ENT_QUOTES); ?>" class="<?php echo $linkClass; ?>">
                            <i class="<?php echo htmlspecialchars($item['icon'], ENT_QUOTES); ?> w-5 text-center"></i>
                            <span class="font-medium"><?php echo htmlspecialchars($item['label'], ENT_QUOTES); ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>

        <!-- Footer -->
        <div class="p-4 border-t">
            <a href="../dashboard.php" class="flex items-center gap-x-3 px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors duration-200">
                <i class="fas fa-arrow-left w-5 text-center"></i>
                <span class="font-medium">Back to Main</span>
            </a>
        </div>
    </div>
</aside>
