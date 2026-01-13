<!-- SIDEBAR -->
<aside class="w-64 bg-white border-r border-[#cfd7e7] flex flex-col h-full shrink-0 z-20 hidden md:flex">
    <!-- Logo Area -->
    <div class="p-6 flex items-center gap-3">
        <!-- Logo Image -->
        <div class="h-8 w-auto flex items-center justify-center">
            <!-- Pastikan path logo sesuai dengan preferensi Anda -->
            <img src="../../assets/images/Logo2b.png" alt="Logo" class="h-full w-auto object-contain rounded p-1">
        </div>
        <h1 class="text-[#0d121b] text-base font-bold tracking-tight">GDPARTSTUDIO</h1>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex flex-col gap-1 px-3 mt-2 flex-1">
        
        <!-- Dashboard (Placeholder) -->
        <?php 
            $isActive = (isset($currentPage) && $currentPage == 'dashboard');
            $classLink = $isActive ? 'bg-primary/10 text-primary' : 'hover:bg-[#f3f4f6] text-[#4c669a] group';
            $classIcon = $isActive ? 'fill' : 'group-hover:text-[#0d121b]';
            $classText = $isActive ? 'font-bold' : 'font-medium group-hover:text-[#0d121b]';
        ?>
        <a href="admindashboard.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
            <span class="material-symbols-outlined <?= $classIcon ?>">dashboard</span>
            <span class="text-sm <?= $classText ?>">Dashboard</span>
        </a>

        <!-- Portfolio Link -->
        <?php 
            $isActive = (isset($currentPage) && $currentPage == 'portfolio');
            $classLink = $isActive ? 'bg-primary/10 text-primary' : 'hover:bg-[#f3f4f6] text-[#4c669a] group';
            $classIcon = $isActive ? 'fill' : 'group-hover:text-[#0d121b]';
            $classText = $isActive ? 'font-bold' : 'font-medium group-hover:text-[#0d121b]';
        ?>
        <a href="admin_portfolio.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
            <span class="material-symbols-outlined <?= $classIcon ?>">inventory_2</span>
            <span class="text-sm <?= $classText ?>">Portfolio</span>
        </a>

        <!-- Services Link -->
        <?php 
            $isActive = (isset($currentPage) && $currentPage == 'services');
            $classLink = $isActive ? 'bg-primary/10 text-primary' : 'hover:bg-[#f3f4f6] text-[#4c669a] group';
            $classIcon = $isActive ? 'fill' : 'group-hover:text-[#0d121b]';
            $classText = $isActive ? 'font-bold' : 'font-medium group-hover:text-[#0d121b]';
        ?>
        <a href="admin_services.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
            <span class="material-symbols-outlined <?= $classIcon ?>">handshake</span>
            <span class="text-sm <?= $classText ?>">Services</span>
        </a>

        <!-- Daftar Admin Link (BARU) -->
        <?php 
            $isActive = (isset($currentPage) && $currentPage == 'admins');
            $classLink = $isActive ? 'bg-primary/10 text-primary' : 'hover:bg-[#f3f4f6] text-[#4c669a] group';
            $classIcon = $isActive ? 'fill' : 'group-hover:text-[#0d121b]';
            $classText = $isActive ? 'font-bold' : 'font-medium group-hover:text-[#0d121b]';
        ?>
        <a href="manage_admins.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
            <span class="material-symbols-outlined <?= $classIcon ?>">group</span>
            <span class="text-sm <?= $classText ?>">Daftar Admin</span>
        </a>

        <!-- Invoice Link -->
        <?php 
            $isActive = (isset($currentPage) && $currentPage == 'invoices');
            $classLink = $isActive ? 'bg-primary/10 text-primary' : 'hover:bg-[#f3f4f6] text-[#4c669a] group';
            $classIcon = $isActive ? 'fill' : 'group-hover:text-[#0d121b]';
            $classText = $isActive ? 'font-bold' : 'font-medium group-hover:text-[#0d121b]';
        ?>
        <a href="manage_invoices.php" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-colors <?= $classLink ?>">
            <span class="material-symbols-outlined <?= $classIcon ?>">receipt_long</span>
            <span class="text-sm <?= $classText ?>">Nota</span>
        </a>

        <!-- Settings (Placeholder) -->
        <a href="#" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#f3f4f6] group transition-colors">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-[#0d121b]">settings</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-[#0d121b]">Settings</span>
        </a>

    </nav>

    <!-- Logout Area -->
    <div class="p-3 mt-auto border-t border-[#cfd7e7]">
        <a class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-[#fee2e2] group transition-colors" href="../logout.php">
            <span class="material-symbols-outlined text-[#4c669a] group-hover:text-red-600">logout</span>
            <span class="text-[#4c669a] text-sm font-medium group-hover:text-red-600">Logout</span>
        </a>
    </div>
</aside>