<!-- SIDEBAR -->
<?php
// Default path jika tidak didefinisikan (untuk berjaga-jaga)
if (!isset($path)) { $path = '.'; }
?>

<aside class="w-64 bg-white border-r border-[#cfd7e7] flex flex-col h-full shrink-0 z-20 hidden md:flex">
    
    <!-- Logo Area -->
    <div class="p-6 flex items-center gap-3">
        <div class="h-8 w-auto flex items-center justify-center">
            <!-- Path logo disesuaikan relatif terhadap root admin -->
            <img src="<?= $path ?>/../../assets/images/Logo2b.png" alt="Logo" class="h-full w-auto object-contain rounded p-1">
        </div>
        <h1 class="text-[#0d121b] text-base font-bold tracking-tight">GDPARTSTUDIO</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex flex-col gap-1 px-3 mt-2 flex-1 overflow-y-auto no-scrollbar">
        
        <?php 
        // Definisi Daftar Menu dengan Struktur Folder Baru
        $menus = [
            [
                'id'    => 'dashboard',
                'label' => 'Dashboard',
                'icon'  => 'dashboard',
                'link'  => $path . '/admindashboard.php' // File di root admin
            ],
            [
                'id'    => 'equipment',
                'label' => 'Equipment',
                'icon'  => 'home_repair_service',
                'link'  => $path . '/equipment/equipment.php' // Masuk folder equipment
            ],
            [
                'id'    => 'portfolio',
                'label' => 'Portfolio',
                'icon'  => 'inventory_2',
                'link'  => $path . '/portfolio/admin_portfolio.php' // Masuk folder portfolio
            ],
            [
                'id'    => 'services',
                'label' => 'Services',
                'icon'  => 'handshake',
                'link'  => $path . '/services/admin_services.php' // Masuk folder services
            ],
            [
                'id'    => 'weather',
                'label' => 'Weather',
                'icon'  => 'cloud',
                'link'  => $path . '/weather/weather.php' // Masuk folder weather
            ],
            [
                'id'    => 'admins',
                'label' => 'Daftar Admin',
                'icon'  => 'group',
                'link'  => $path . '/profile/manage_admins.php' // Masuk folder profile
            ],
            [
                'id'    => 'invoices',
                'label' => 'Nota',
                'icon'  => 'receipt_long',
                'link'  => $path . '/invoice/manage_invoices.php' // Masuk folder invoice
            ],
            [
                'id'    => 'settings',
                'label' => 'Settings (Log)',
                'icon'  => 'settings',
                'link'  => $path . '/logs/activity_log.php' // Masuk folder logs
            ]
        ];

        if (!isset($currentPage)) { $currentPage = ''; }

        foreach ($menus as $menu) {
            $isActive = ($currentPage == $menu['id']);

            $classLink = $isActive 
                ? 'bg-primary/10 text-primary' 
                : 'hover:bg-[#f3f4f6] text-[#4c669a] group';
            
            $classIcon = $isActive 
                ? 'fill' 
                : 'group-hover:text-[#0d121b]';
            
            $classText = $isActive 
                ? 'font-bold' 
                : 'font-medium group-hover:text-[#0d121b]';
            ?>
            
            <a href="<?= $menu['link'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
                <span class="material-symbols-outlined <?= $classIcon ?>"><?= $menu['icon'] ?></span>
                <span class="text-sm <?= $classText ?>"><?= $menu['label'] ?></span>
            </a>

        <?php } ?>

    </nav>

    <!-- Logout Area -->
    <div class="p-3 mt-auto border-t border-[#cfd7e7]">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#fee2e2] group transition-colors" href="<?= $path ?>/../logout.php">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-red-600">logout</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-red-600">Logout</span>
        </a>
    </div>
</aside>