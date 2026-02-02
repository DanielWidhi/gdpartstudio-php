<?php
session_start();
include '../../db.php';

// Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Monitoring Cuaca Bali - GDPARTSTUDIO</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "primary-hover": "#0f4bc4",
                        "background-light": "#f8f9fc",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                },
            },
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .material-symbols-outlined { font-variation-settings: 'FILL' 1; }
        /* Animasi Loading */
        .animate-pulse-slow { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- 1. SIDEBAR -->
    <?php 
        $currentPage = 'weather'; // Penanda menu aktif
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER -->
        <?php 
            $pageTitle = "Monitoring > Bali Weather"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <!-- CONTENT SCROLL -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1400px] mx-auto flex flex-col gap-8">
                
                <!-- TITLE SECTION -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Monitoring Cuaca Bali</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Ringkasan visual area populer dan daftar detail cuaca seluruh wilayah Bali.</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                        </span>
                        <span class="text-xs font-medium text-green-600 uppercase tracking-wider">Update Real-time</span>
                    </div>
                </div>

                <!-- 4 CARDS (HIGHLIGHT) -->
                <!-- Data akan diisi oleh Javascript (ID: card-ubud, card-uluwatu, dll) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" id="highlight-cards">
                    <!-- Loading Skeleton (Default) -->
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-6 h-64 animate-pulse"></div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-6 h-64 animate-pulse"></div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-6 h-64 animate-pulse"></div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-6 h-64 animate-pulse"></div>
                </div>

                <!-- TABLE SECTION -->
                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-5 border-b border-[#e2e8f0] flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <h3 class="text-lg font-bold text-[#0d121b]">Daftar Cuaca Seluruh Wilayah</h3>
                        <div class="relative w-full sm:w-72">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#9ca3af] text-[20px]">search</span>
                            <input id="searchTable" onkeyup="filterTable()" class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-[#cfd7e7] rounded-lg text-sm focus:ring-primary focus:border-primary transition-all outline-none" placeholder="Cari kabupaten atau kota..." type="text"/>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse" id="weatherTable">
                            <thead>
                                <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                    <th class="px-6 py-4 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Wilayah / Kabupaten</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Kondisi</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-[#64748b] uppercase tracking-wider text-center">Suhu</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Kelembapan</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-[#64748b] uppercase tracking-wider">Kecepatan Angin</th>
                                    <th class="px-6 py-4 text-[10px] font-bold text-[#64748b] uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]" id="table-body">
                                <!-- Data will be injected here -->
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">Memuat data cuaca...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        // Data Lokasi Bali (Koordinat & Detail)
        const locations = [
            { name: "Ubud", region: "Gianyar", lat: -8.5069, lon: 115.2625, type: "highlight", color: "amber" },
            { name: "Uluwatu", region: "Badung", lat: -8.8149, lon: 115.0884, type: "highlight", color: "blue" },
            { name: "Canggu", region: "Badung", lat: -8.6478, lon: 115.1385, type: "highlight", color: "red" },
            { name: "Denpasar", region: "Kota Madya", lat: -8.6705, lon: 115.2126, type: "highlight", color: "orange" },
            
            // Data Tabel
            { name: "Gianyar", region: "Ubud, Sukawati, Tampaksiring", lat: -8.5433, lon: 115.3197, type: "list" },
            { name: "Tabanan", region: "Bedugul, Tanah Lot", lat: -8.5408, lon: 115.1223, type: "list" },
            { name: "Buleleng", region: "Singaraja, Lovina", lat: -8.1158, lon: 115.0901, type: "list" },
            { name: "Badung", region: "Kuta, Seminyak, Canggu", lat: -8.5833, lon: 115.1833, type: "list" },
            { name: "Karangasem", region: "Amed, Gunung Agung", lat: -8.4447, lon: 115.5946, type: "list" },
            { name: "Jembrana", region: "Negara, Gilimanuk", lat: -8.3582, lon: 114.6369, type: "list" },
            { name: "Bangli", region: "Kintamani", lat: -8.2974, lon: 115.3549, type: "list" },
            { name: "Klungkung", region: "Nusa Penida", lat: -8.5365, lon: 115.4055, type: "list" }
        ];

        // Fungsi Ambil Data dari API Open-Meteo
        async function fetchWeather() {
            const cardsContainer = document.getElementById('highlight-cards');
            const tableBody = document.getElementById('table-body');
            
            // Kosongkan loading skeleton
            cardsContainer.innerHTML = '';
            tableBody.innerHTML = '';

            for (const loc of locations) {
                try {
                    const res = await fetch(`https://api.open-meteo.com/v1/forecast?latitude=${loc.lat}&longitude=${loc.lon}&current_weather=true&hourly=relativehumidity_2m`);
                    const data = await res.json();
                    
                    const weather = data.current_weather;
                    // Ambil kelembapan (ambil jam sekarang karena API ini hourly)
                    const hourIndex = new Date().getHours();
                    const humidity = data.hourly.relativehumidity_2m[hourIndex];

                    // Mapping Kondisi Cuaca (WMO Code)
                    let condition = "Cerah";
                    let icon = "sunny";
                    let colorClass = "text-amber-500";
                    let badgeClass = "bg-amber-50 text-amber-700 border-amber-100";

                    if (weather.weathercode > 3) { condition = "Berawan"; icon = "cloud"; colorClass = "text-blue-400"; badgeClass = "bg-blue-50 text-blue-700 border-blue-100"; }
                    if (weather.weathercode > 45) { condition = "Kabut"; icon = "foggy"; colorClass = "text-gray-400"; badgeClass = "bg-gray-50 text-gray-700 border-gray-100"; }
                    if (weather.weathercode > 50) { condition = "Hujan"; icon = "rainy"; colorClass = "text-blue-600"; badgeClass = "bg-blue-100 text-blue-800 border-blue-200"; }
                    if (weather.weathercode > 80) { condition = "Badai"; icon = "thunderstorm"; colorClass = "text-purple-600"; badgeClass = "bg-purple-50 text-purple-700 border-purple-100"; }

                    // RENDER UI BERDASARKAN TIPE
                    if (loc.type === 'highlight') {
                        const borderLeft = loc.color === 'amber' ? 'border-l-amber-400' : 
                                           loc.color === 'blue' ? 'border-l-blue-400' :
                                           loc.color === 'red' ? 'border-l-red-400' : 'border-l-orange-400';

                        cardsContainer.innerHTML += `
                        <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-6 flex flex-col transition-all hover:shadow-md border-l-4 ${borderLeft}">
                            <div class="flex justify-between items-start mb-4">
                                <div><h3 class="text-lg font-bold text-[#0d121b]">${loc.name}</h3><p class="text-xs text-[#4c669a]">${loc.region}</p></div>
                                <span class="material-symbols-outlined text-[44px] ${colorClass}">${icon}</span>
                            </div>
                            <div class="flex items-center gap-2 mb-6">
                                <span class="text-4xl font-bold text-[#0d121b]">${Math.round(weather.temperature)}°C</span>
                                <span class="text-xs font-medium px-2.5 py-1 rounded-full border ${badgeClass}">${condition}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-bold text-[#9ca3af] uppercase tracking-wider">Kelembapan</span>
                                    <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px] text-[#4c669a]">humidity_percentage</span><span class="text-sm font-semibold text-[#0d121b]">${humidity}%</span></div>
                                </div>
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[10px] font-bold text-[#9ca3af] uppercase tracking-wider">Angin</span>
                                    <div class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[18px] text-[#4c669a]">air</span><span class="text-sm font-semibold text-[#0d121b]">${weather.windspeed} km/h</span></div>
                                </div>
                            </div>
                            <button class="mt-auto w-full bg-primary hover:bg-primary-hover text-white py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[18px]">send</span> Kirim Update
                            </button>
                        </div>`;
                    } else {
                        tableBody.innerHTML += `
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-semibold text-sm text-[#0d121b]">${loc.name}</div>
                                <div class="text-[10px] text-[#64748b]">${loc.region}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px] ${colorClass}">${icon}</span>
                                    <span class="text-sm font-medium">${condition}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center"><span class="text-sm font-bold">${Math.round(weather.temperature)}°C</span></td>
                            <td class="px-6 py-4 text-sm text-[#4c669a]">${humidity}%</td>
                            <td class="px-6 py-4 text-sm text-[#4c669a]">${weather.windspeed} km/h</td>
                            <td class="px-6 py-4 text-right">
                                <button class="text-primary hover:text-primary-hover font-bold text-xs flex items-center gap-1 ml-auto">
                                    Update Tim <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                </button>
                            </td>
                        </tr>`;
                    }

                } catch (error) {
                    console.error("Gagal load cuaca untuk " + loc.name);
                }
            }
        }

        // Fungsi Search Table
        function filterTable() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("searchTable");
            filter = input.value.toUpperCase();
            table = document.getElementById("weatherTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        // Init
        document.addEventListener("DOMContentLoaded", fetchWeather);
    </script>

</body>
</html>