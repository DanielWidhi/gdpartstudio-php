<?php
// Pastikan variabel $path tersedia
if (!isset($path)) { $path = '.'; }

// Logic ambil data user
if (isset($_SESSION['admin_id']) && isset($conn)) {
    $h_id = $_SESSION['admin_id'];
    $h_query = mysqli_query($conn, "SELECT name, email, avatar FROM admins WHERE id = $h_id");
    
    if ($h_query && mysqli_num_rows($h_query) > 0) {
        $h_data = mysqli_fetch_assoc($h_query);
        $h_name = $h_data['name'];
        $h_email = $h_data['email'];
        // Path Avatar disesuaikan dengan $path
        $h_avatar = !empty($h_data['avatar']) ? $path . "/../../" . $h_data['avatar'] : $path . "/../../assets/images/user-placeholder.jpg";
    } else {
        $h_name = "Admin";
        $h_email = "User";
        $h_avatar = $path . "/../../assets/images/user-placeholder.jpg";
    }
}
?>

<header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0 relative z-40">
    
    <!-- Breadcrumb -->
    <div class="flex items-center gap-2 text-sm text-[#4c669a]">
        <?php if(isset($pageTitle)): ?>
            <span class="font-medium text-[#0d121b]"><?= $pageTitle ?></span>
        <?php else: ?>
            <span class="font-medium text-[#0d121b]">Dashboard</span>
        <?php endif; ?>
    </div>

    <!-- Widgets (Weather & Clock) -->
    <div class="hidden md:flex items-center gap-3 ml-auto mr-6">
        <!-- Widget Cuaca -->
        <div id="weather-widget" class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-full border border-blue-100 hidden">
            <span id="weather-icon" class="material-symbols-outlined text-blue-600 text-[18px]">cloud</span>
            <span id="weather-temp" class="text-sm font-bold text-[#0d121b] tabular-nums">--°C</span>
            <span id="weather-city" class="text-[10px] font-medium text-blue-600 border-l border-blue-200 pl-2 uppercase max-w-[80px] truncate">...</span>
        </div>

        <!-- Widget Jam -->
        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-full border border-gray-100">
            <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
            <span class="text-sm font-bold text-[#0d121b] tabular-nums" id="global-clock">00:00:00</span>
            <span class="text-[10px] font-medium text-[#4c669a] border-l border-gray-300 pl-2 uppercase">WIB</span>
        </div>
    </div>

    <!-- Profile -->
    <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-[#0d121b]"><?= $h_name ?? 'Admin' ?></p>
            <p class="text-xs text-[#4c669a]"><?= $h_email ?? '' ?></p>
        </div>
        
        <!-- Avatar Link ke Profile -->
        <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300 relative group cursor-pointer" 
             style="background-image: url('<?= $h_avatar ?? '' ?>');"
             onclick="window.location.href='<?= $path ?>/profile/profile.php'">
        </div>
    </div>
</header>

<!-- INCLUDE JS JAM & CUACA DISINI (Sama seperti sebelumnya, tidak perlu diubah JS-nya) -->
<script>
    // ... (Script Jam & Cuaca biarkan tetap ada) ...
    // Pastikan kode JS yang sebelumnya saya berikan tetap ada di file ini
    // untuk menjalankan jam dan cuaca.
    function updateGlobalClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
        const clockEl = document.getElementById('global-clock');
        if (clockEl) clockEl.textContent = timeString;
    }
    setInterval(updateGlobalClock, 1000);
    updateGlobalClock();
    // ... (Script cuaca lanjutkan disini) ...
</script>