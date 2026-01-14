<!-- SIDEBAR -->
<aside class="w-64 bg-white border-r border-[#cfd7e7] flex flex-col h-full shrink-0 z-20 hidden md:flex">
    <!-- Logo Area -->
    <div class="p-6 flex items-center gap-3">
        <div class="h-8 w-auto flex items-center justify-center">
            <img src="../../assets/images/Logo2b.png" alt="Logo" class="h-full w-auto object-contain rounded p-1">
        </div>
        <h1 class="text-[#0d121b] text-base font-bold tracking-tight">GDPARTSTUDIO</h1>
    </div>

    <!-- Navigation Menu (Menggunakan Array PHP) -->
    <nav class="flex flex-col gap-1 px-3 mt-2 flex-1">
        
        <?php 
        // Definisi Daftar Menu
        $menus = [
            [
                'id'    => 'dashboard',
                'label' => 'Dashboard',
                'icon'  => 'dashboard',
                'link'  => 'admindashboard.php'
            ],
            [
                'id'    => 'weather',
                'label' => 'Weather',
                'icon'  => 'cloud',
                'link'  => 'weather.php'
            ],
            [
                'id'    => 'portfolio',
                'label' => 'Portfolio',
                'icon'  => 'inventory_2',
                'link'  => 'admin_portfolio.php'
            ],
            [
                'id'    => 'services',
                'label' => 'Services',
                'icon'  => 'handshake',
                'link'  => 'admin_services.php'
            ],
            [
                'id'    => 'admins',
                'label' => 'Daftar Admin',
                'icon'  => 'group',
                'link'  => 'manage_admins.php'
            ],
            [
                'id'    => 'invoices',
                'label' => 'Nota',
                'icon'  => 'receipt_long',
                'link'  => 'manage_invoices.php'
            ],
            [
                'id'    => 'settings',
                'label' => 'Settings (Log)',
                'icon'  => 'settings',
                'link'  => 'activity_log.php'
            ]
        ];

        // Looping Menu
        foreach ($menus as $menu) {
            // Cek apakah menu ini yang sedang aktif
            $isActive = (isset($currentPage) && $currentPage == $menu['id']);

            // Tentukan Class CSS berdasarkan status aktif
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
            
            <!-- Cetak HTML Menu -->
            <a href="<?= $menu['link'] ?>" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
                <span class="material-symbols-outlined <?= $classIcon ?>"><?= $menu['icon'] ?></span>
                <span class="text-sm <?= $classText ?>"><?= $menu['label'] ?></span>
            </a>

        <?php } ?>

    </nav>

    <!-- Logout Area -->
    <div class="p-3 mt-auto border-t border-[#cfd7e7]">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#fee2e2] group transition-colors" href="../logout.php">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-red-600">logout</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-red-600">Logout</span>
        </a>
    </div>
</aside>