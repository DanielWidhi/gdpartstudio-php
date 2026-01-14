<?php
// Pastikan koneksi database ($conn) sudah tersedia
if (isset($_SESSION['admin_id'])) {
    $h_id = $_SESSION['admin_id'];
    if (isset($conn)) {
        $h_query = mysqli_query($conn, "SELECT name, email, avatar FROM admins WHERE id = $h_id");
        
        if ($h_query && mysqli_num_rows($h_query) > 0) {
            $h_data = mysqli_fetch_assoc($h_query);
            $h_name = $h_data['name'];
            $h_email = $h_data['email'];
            $h_avatar = !empty($h_data['avatar']) ? "../../" . $h_data['avatar'] : "../../assets/images/user-placeholder.jpg";
        } else {
            $h_name = "Admin";
            $h_email = "User";
            $h_avatar = "../../assets/images/user-placeholder.jpg";
        }
    }
}
?>

<!-- HEADER COMPONENT -->
<header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
    
    <!-- 1. Breadcrumb (Kiri) -->
    <div class="flex items-center gap-2 text-sm text-[#4c669a]">
        <?php if(isset($pageTitle)): ?>
            <span class="font-medium text-[#0d121b]"><?= $pageTitle ?></span>
        <?php else: ?>
            <span class="font-medium text-[#0d121b]">Dashboard</span>
        <?php endif; ?>
    </div>

    <!-- Area Widget (Tengah/Kanan) -->
    <div class="hidden md:flex items-center gap-3 ml-auto mr-6">
        
        <!-- 2a. Widget Cuaca -->
        <div id="weather-widget" class="flex items-center gap-2 px-3 py-1.5 bg-blue-50 rounded-full border border-blue-100 hidden">
            <!-- Icon Cuaca -->
            <span id="weather-icon" class="material-symbols-outlined text-blue-600 text-[18px]">cloud</span>
            <!-- Suhu -->
            <span id="weather-temp" class="text-sm font-bold text-[#0d121b] tabular-nums">--°C</span>
            <!-- Lokasi (Kota) -->
            <span id="weather-city" class="text-[10px] font-medium text-blue-600 border-l border-blue-200 pl-2 uppercase max-w-[80px] truncate">...</span>
        </div>

        <!-- 2b. Widget Jam -->
        <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-50 rounded-full border border-gray-100">
            <span class="material-symbols-outlined text-primary text-[18px]">schedule</span>
            <span class="text-sm font-bold text-[#0d121b] tabular-nums" id="global-clock">00:00:00</span>
            <span class="text-[10px] font-medium text-[#4c669a] border-l border-gray-300 pl-2 uppercase">WIB</span>
        </div>

    </div>

    <!-- 3. User Profile (Kanan) -->
    <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-[#0d121b]"><?= $h_name ?? 'Admin' ?></p>
            <p class="text-xs text-[#4c669a]"><?= $h_email ?? 'email@domain.com' ?></p>
        </div>
        
        <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300 relative group cursor-pointer" 
             style="background-image: url('<?= $h_avatar ?? '' ?>');"
             onclick="window.location.href='../../pages/admin/users/profile.php'">
             
             <div class="absolute top-full right-0 mt-2 w-32 bg-white text-xs text-gray-700 shadow-lg rounded p-2 hidden group-hover:block border text-center z-50">
                 Edit Profil
             </div>
        </div>
    </div>

</header>

<!-- JAVASCRIPT: JAM & CUACA -->
<script>
    // --- 1. Script Jam ---
    function updateGlobalClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
        const clockEl = document.getElementById('global-clock');
        if (clockEl) clockEl.textContent = timeString;
    }
    setInterval(updateGlobalClock, 1000);
    updateGlobalClock();

    // --- 2. Script Cuaca (Dengan Fallback Bali) ---
    async function fetchWeatherData(lat, lon, isDefault = false) {
        try {
            // A. Ambil Data Cuaca
            const weatherRes = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${lat}&longitude=${lon}&current_weather=true`);
            const weatherData = await weatherRes.json();
            
            const temp = Math.round(weatherData.current_weather.temperature);
            const code = weatherData.current_weather.weathercode;
            
            // Mapping Icon
            let icon = 'wb_sunny'; 
            if (code > 3) icon = 'cloud'; 
            if (code > 45) icon = 'foggy'; 
            if (code > 50) icon = 'rainy'; 
            if (code > 80) icon = 'thunderstorm'; 

            // Update UI
            document.getElementById('weather-temp').innerText = `${temp}°C`;
            document.getElementById('weather-icon').innerText = icon;

            // B. Ambil Nama Kota
            // Jika pakai default (Bali), set teks manual biar cepat
            if (isDefault) {
                document.getElementById('weather-city').innerText = "BALI";
            } else {
                const cityRes = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                const cityData = await cityRes.json();
                let city = cityData.address.city || cityData.address.town || cityData.address.county || "Lokasi";
                city = city.replace('Kota ', '').replace('Kabupaten ', '');
                document.getElementById('weather-city').innerText = city;
            }

            // Tampilkan Widget (Hapus class hidden)
            document.getElementById('weather-widget').classList.remove('hidden');

        } catch (error) {
            console.error("Gagal memuat cuaca:", error);
        }
    }

    function initWeather() {
        // Koordinat Default (Bali)
        const defaultLat = -8.4095;
        const defaultLon = 115.1889;

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                // SUKSES: Pakai lokasi user (Jika HTTPS/Localhost dan diizinkan)
                (position) => {
                    fetchWeatherData(position.coords.latitude, position.coords.longitude);
                }, 
                // ERROR/DITOLAK: Pakai Default (Bali)
                (error) => {
                    console.warn("Izin lokasi ditolak/error, menggunakan default (Bali).");
                    fetchWeatherData(defaultLat, defaultLon, true);
                }
            );
        } else {
            // BROWSER TIDAK SUPPORT: Pakai Default (Bali)
            fetchWeatherData(defaultLat, defaultLon, true);
        }
    }

    // Jalankan saat load
    document.addEventListener("DOMContentLoaded", initWeather);
</script>