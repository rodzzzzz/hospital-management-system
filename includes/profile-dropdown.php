<?php
$currentProfilePage = basename($_SERVER['PHP_SELF'] ?? '');
$isHrProfilePage = (isset($hrPages) && is_array($hrPages) && in_array($currentProfilePage, $hrPages, true));
$logoutTarget = $isHrProfilePage ? 'hr-login.php' : 'login.php';

$profileDropdownTheme = isset($profileDropdownTheme) ? (string)$profileDropdownTheme : 'light';
$isDark = strtolower($profileDropdownTheme) === 'dark';

$btnClass = $isDark
    ? 'flex items-center space-x-3 rounded-lg px-2 py-1 hover:bg-white/10 text-white'
    : 'flex items-center space-x-3 rounded-lg px-2 py-1 hover:bg-gray-100';
$chevClass = $isDark ? 'text-white/70' : 'text-gray-400';
?>
<div class="relative profile-dropdown" data-logout-target="<?php echo htmlspecialchars($logoutTarget, ENT_QUOTES); ?>">
    <button type="button" class="profile-menu-btn <?php echo htmlspecialchars($btnClass, ENT_QUOTES); ?>">
        <img src="resources/doctor.jpg" alt="Profile" class="w-8 h-8 rounded-full">
        <span class="font-medium"><?php echo htmlspecialchars((string)($authUser['full_name'] ?? ($authUser['username'] ?? 'User')), ENT_QUOTES); ?></span>
        <i class="fas fa-chevron-down <?php echo htmlspecialchars($chevClass, ENT_QUOTES); ?> text-xs"></i>
    </button>
    <div class="profile-menu hidden absolute right-0 mt-2 w-56 bg-white border border-gray-100 rounded-xl shadow-lg overflow-hidden z-50">
        <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-user text-gray-400"></i>
            <span>Profile</span>
        </a>
        <a href="change-password.php" class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-key text-gray-400"></i>
            <span>Change Password</span>
        </a>
        <div class="h-px bg-gray-100"></div>
        <button type="button" class="profile-logout w-full text-left flex items-center gap-3 px-4 py-3 text-sm text-red-700 hover:bg-red-50">
            <i class="fas fa-right-from-bracket text-red-400"></i>
            <span>Logout</span>
        </button>
    </div>
</div>
<script>
    (function () {
        if (window.__profileDropdownInit) return;
        window.__profileDropdownInit = true;

        function closeAll() {
            document.querySelectorAll('.profile-dropdown .profile-menu').forEach(function (m) {
                m.classList.add('hidden');
            });
        }

        document.addEventListener('click', function (e) {
            const btn = e.target && e.target.closest ? e.target.closest('.profile-dropdown .profile-menu-btn') : null;
            if (btn) {
                e.preventDefault();
                e.stopPropagation();

                const root = btn.closest('.profile-dropdown');
                if (!root) return;

                const menu = root.querySelector('.profile-menu');
                if (!menu) return;

                const wasHidden = menu.classList.contains('hidden');
                closeAll();
                if (wasHidden) menu.classList.remove('hidden');
                return;
            }

            const logoutBtn = e.target && e.target.closest ? e.target.closest('.profile-dropdown .profile-logout') : null;
            if (logoutBtn) {
                e.preventDefault();

                const root = logoutBtn.closest('.profile-dropdown');
                const target = root ? (root.getAttribute('data-logout-target') || 'login.php') : 'login.php';

                fetch('api/auth/logout.php', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' }
                }).catch(function () { }).finally(function () {
                    window.location.href = target;
                });
                return;
            }

            closeAll();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAll();
        });
    })();
</script>
