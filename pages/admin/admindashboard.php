<?php
session_start();
include '../../db.php';

// 1. CEK LOGIN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. HITUNG DATA DASHBOARD
$q_proj = mysqli_query($conn, "SELECT COUNT(*) as total FROM projects");
$total_projects = mysqli_fetch_assoc($q_proj)['total'];

$q_serv = mysqli_query($conn, "SELECT COUNT(*) as total FROM services");
$total_services = mysqli_fetch_assoc($q_serv)['total'];

$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM invoices WHERE status = 'Pending'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

$q_income = mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE status = 'Lunas'");
$d_income = mysqli_fetch_assoc($q_income);
$raw_income = $d_income['total'] ?? 0;

function formatShortRp($n) {
    if ($n >= 1000000000) return 'Rp ' . round($n / 1000000000, 1) . 'M';
    if ($n >= 1000000) return 'Rp ' . round($n / 1000000, 1) . 'jt';
    return 'Rp ' . number_format($n, 0, ',', '.');
}
$formatted_income = formatShortRp($raw_income);

$q_recent = mysqli_query($conn, "SELECT * FROM projects ORDER BY created_at DESC LIMIT 5");
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GDPARTSTUDIO - Dashboard</title>
    
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
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
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- SIDEBAR -->
    <?php $currentPage = 'dashboard'; include '../../assets/components/admin/sidebar.php'; ?>
    
    <!-- MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER -->
        <?php $pageTitle = "Dashboard Ringkasan"; include '../../assets/components/admin/header.php'; ?>

        <!-- CONTENT -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1400px] mx-auto">
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <!-- LEFT COLUMN -->
                    <div class="flex-1 flex flex-col gap-6">
                        
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Dashboard Ringkasan</h2>
                                <p class="text-[#4c669a] text-sm font-normal mt-1">Selamat datang kembali! Berikut ringkasan performa hari ini.</p>
                            </div>
                        </div>

                        <!-- Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg material-symbols-outlined">photo_library</span>
                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Aktif</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Total Proyek</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $total_projects ?></h3>
                            </div>
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-purple-50 text-purple-600 rounded-lg material-symbols-outlined">handshake</span>
                                    <span class="text-xs font-semibold text-gray-400">Stable</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Total Layanan</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $total_services ?></h3>
                            </div>
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-orange-50 text-orange-600 rounded-lg material-symbols-outlined">notifications_active</span>
                                    <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Baru</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Invoice Pending</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $total_pending ?></h3>
                            </div>
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-green-50 text-green-600 rounded-lg material-symbols-outlined">payments</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Total Pendapatan (Lunas)</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $formatted_income ?></h3>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-[#cfd7e7] flex items-center justify-between">
                                <h4 class="text-base font-bold text-[#0d121b]">Proyek Terbaru</h4>
                                <a href="admin_portfolio.php" class="text-xs font-medium text-[#64748b] hover:text-[#0d121b]">Lihat Semua</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                            <th class="px-6 py-3 text-xs font-semibold text-[#64748b] uppercase">Project Name</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-[#64748b] uppercase">Category</th>
                                            <th class="px-6 py-3 text-xs font-semibold text-[#64748b] uppercase text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#e2e8f0]">
                                        <?php if(mysqli_num_rows($q_recent) > 0): ?>
                                            <?php while($row = mysqli_fetch_assoc($q_recent)): ?>
                                            <tr class="hover:bg-[#f8fafc]">
                                                <td class="px-6 py-4 text-sm font-medium text-[#0d121b]"><?= $row['title'] ?></td>
                                                <td class="px-6 py-4 text-sm text-[#64748b]"><?= $row['category_display'] ?></td>
                                                <td class="px-6 py-4 text-center">
                                                    <?php 
                                                        $statusClass = $row['status'] == 'Published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';
                                                    ?>
                                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold <?= $statusClass ?>">
                                                        <?= $row['status'] ?>
                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada proyek.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN (Calendar) -->
                    <div class="w-full lg:w-[360px] flex flex-col gap-6 shrink-0">
                        
                        <!-- CALENDAR WIDGET -->
                        <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm p-6">
                            
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-sm font-bold text-[#0d121b]">Kalender Indonesia</h4>
                                <div class="flex items-center gap-1 bg-gray-50 rounded-lg p-0.5 border border-[#e2e8f0]">
                                    <button id="prevMonth" class="p-1 hover:bg-white hover:shadow-sm rounded transition text-[#64748b]">
                                        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                                    </button>
                                    <span id="currentMonthYear" class="text-xs font-medium text-primary px-2 min-w-[90px] text-center select-none">...</span>
                                    <button id="nextMonth" class="p-1 hover:bg-white hover:shadow-sm rounded transition text-[#64748b]">
                                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                                    </button>
                                </div>
                            </div>

                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-[10px] font-bold text-red-500 text-center">Min</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Sen</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Sel</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Rab</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Kam</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Jum</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Sab</div>
                            </div>

                            <div id="calendar-grid" class="grid grid-cols-7 gap-1 mb-4 min-h-[180px]">
                                <!-- Calendar days will appear here -->
                            </div>
                            
                            <div class="space-y-3 mt-4 pt-4 border-t border-gray-100 max-h-[200px] overflow-y-auto custom-scrollbar" id="holiday-list">
                                <p class="text-xs text-center text-gray-400 py-2">Memuat hari libur...</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- SCRIPT CALENDAR FIX -->
    <script>
    // --- 1. JAM DIGITAL ---
    function updateGlobalClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false }).replace(/\./g, ':');
        const clockEl = document.getElementById('clock');
        if(clockEl) clockEl.textContent = timeString;
    }
    setInterval(updateGlobalClock, 1000);
    updateGlobalClock();

    // --- 2. CONFIG KALENDER ---
    let currDate = new Date();
    let currYear = currDate.getFullYear();
    let currMonth = currDate.getMonth();
    
    // Simpan data event di memori
    let cachedEvents = {}; 

    const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

    // --- 3. LOGIKA RENDER (TAMPILKAN TANGGAL) ---
    function renderCalendar() {
        const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay();
        const lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate();
        const lastDayOfLastMonth = new Date(currYear, currMonth, 0).getDate();
        
        let daysHTML = "";
        const calendarGrid = document.getElementById("calendar-grid");
        const currentMonthYear = document.getElementById("currentMonthYear");
        const holidayListEl = document.getElementById("holiday-list");

        // Set Judul Bulan & Tahun
        currentMonthYear.textContent = `${monthNames[currMonth]} ${currYear}`;

        // Ambil Data Event untuk Tahun ini (Jika ada)
        let eventsData = cachedEvents[currYear] || [];
        
        // Filter event bulan ini untuk List di bawah
        let eventsToShow = []; 

        // RENDER TANGGAL BULAN LALU (Abu-abu)
        for (let i = firstDayOfMonth; i > 0; i--) {
            daysHTML += `<div class="text-[11px] text-gray-200 text-center py-2">${lastDayOfLastMonth - i + 1}</div>`;
        }

        // RENDER TANGGAL BULAN INI
        for (let i = 1; i <= lastDateOfMonth; i++) {
            let dateKey = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
            let isTodayClass = "";
            let textClass = "text-[#0d121b]";
            let borderClass = "";
            let eventTooltip = "";

            // Cek Hari Ini
            const today = new Date();
            if (i === today.getDate() && currMonth === today.getMonth() && currYear === today.getFullYear()) {
                isTodayClass = "bg-primary text-white font-bold rounded-lg shadow-sm"; 
                textClass = "text-white";
            } else {
                isTodayClass = "hover:bg-gray-50 rounded-md";
            }

            // Cek Minggu
            if (new Date(currYear, currMonth, i).getDay() === 0) {
                if(!isTodayClass.includes("bg-primary")) textClass = "text-red-500 font-semibold";
            }

            // Cek Event (Jika Data Sudah Ada)
            if (eventsData.length > 0) {
                let dayEvents = eventsData.filter(e => e.date === dateKey);
                
                if (dayEvents.length > 0) {
                    // Logic Warna Prioritas
                    let hasNational = dayEvents.find(e => e.type === 'national');
                    let hasBali = dayEvents.find(e => e.type === 'bali');
                    
                    if (hasNational) {
                        if(!isTodayClass.includes("bg-primary")) {
                            isTodayClass = "bg-red-50 rounded-md";
                            textClass = "text-red-600 font-bold";
                            borderClass = "border border-red-200";
                        }
                    } else if (hasBali) {
                        if(!isTodayClass.includes("bg-primary")) {
                            isTodayClass = "bg-yellow-50 rounded-md";
                            textClass = "text-yellow-700 font-bold";
                            borderClass = "border border-yellow-200";
                        }
                    }

                    // Masukkan ke list bawah
                    dayEvents.forEach(e => {
                        if (!eventsToShow.some(ex => ex.name === e.name)) {
                            eventsToShow.push(e);
                        }
                        eventTooltip += e.name + "\n";
                    });
                }
            }

            daysHTML += `<div class="text-[11px] text-center py-2 cursor-pointer ${isTodayClass} ${textClass} ${borderClass}" title="${eventTooltip}">${i}</div>`;
        }

        calendarGrid.innerHTML = daysHTML;

        // RENDER LIST EVENT DI BAWAH
        if (eventsToShow.length > 0) {
            let listHTML = `<h5 class="text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-2">Agenda Bulan Ini</h5>`;
            // Urutkan tanggal
            eventsToShow.sort((a, b) => new Date(a.date) - new Date(b.date));
            
            eventsToShow.forEach(h => {
                const d = new Date(h.date);
                const dayNum = d.getDate();
                let dotColor = h.type === 'national' ? "bg-red-500" : (h.type === 'bali' ? "bg-yellow-500" : "bg-blue-400");
                let label = h.type === 'national' ? "Libur Nasional" : (h.type === 'bali' ? "Hari Raya Bali" : "Event");

                listHTML += `
                <div class="flex items-start gap-3 mb-2">
                    <div class="w-1.5 h-1.5 rounded-full ${dotColor} mt-1.5 shrink-0"></div>
                    <div>
                        <p class="text-[11px] font-bold text-[#0d121b]">${dayNum} ${monthNames[d.getMonth()]}: ${h.name}</p>
                        <p class="text-[10px] text-[#64748b]">${label}</p>
                    </div>
                </div>`;
            });
            holidayListEl.innerHTML = listHTML;
        } else {
            // Jika data belum load atau memang tidak ada libur
            if(cachedEvents[currYear]) {
                holidayListEl.innerHTML = `<p class="text-[11px] text-gray-400 italic text-center py-2">Tidak ada hari penting bulan ini.</p>`;
            } else {
                holidayListEl.innerHTML = `<div class="flex justify-center py-2"><span class="animate-spin h-4 w-4 border-2 border-primary border-t-transparent rounded-full"></span></div><p class="text-[10px] text-center text-gray-400">Sedang memuat data...</p>`;
            }
        }
    }

    // --- 4. LOGIKA DATA & API ---
    
    // A. Hitung Galungan & Kuningan (Matematis)
    function getBalineseEvents(year) {
        let events = [];
        const anchorGalungan = new Date('2024-02-28').getTime();
        const dayMs = 86400000;
        const cycleMs = 210 * dayMs;
        let checkDate = anchorGalungan - (5 * cycleMs); 

        for (let i = 0; i < 15; i++) {
            let gDate = new Date(checkDate);
            if (gDate.getFullYear() === year) {
                events.push({ date: gDate.toISOString().split('T')[0], name: "Galungan", type: "bali" });
                let kDate = new Date(checkDate + (10 * dayMs));
                if (kDate.getFullYear() === year) events.push({ date: kDate.toISOString().split('T')[0], name: "Kuningan", type: "bali" });
                let pDate = new Date(checkDate - (1 * dayMs));
                if (pDate.getFullYear() === year) events.push({ date: pDate.toISOString().split('T')[0], name: "Penampahan Galungan", type: "bali-minor" });
            }
            checkDate += cycleMs;
        }
        
        // Nyepi Manual Mapping
        const nyepiDates = { 2024: "2024-03-11", 2025: "2025-03-29", 2026: "2026-03-19" };
        if (nyepiDates[year]) events.push({ date: nyepiDates[year], name: "Hari Raya Nyepi", type: "national" });

        return events;
    }

    // B. Ambil Data API (Async)
    async function loadEvents(year) {
        if (cachedEvents[year]) return; // Jika sudah ada, stop

        let yearEvents = [];
        
        // 1. Data Bali (Lokal Calculation - Instan)
        yearEvents = [...getBalineseEvents(year)];
        
        // Render dulu biar user lihat tanggal & data bali sambil nunggu API
        cachedEvents[year] = yearEvents; 
        renderCalendar();

        // 2. Data Nasional (Fetch API)
        try {
            const response = await fetch(`https://dayoffapi.vercel.app/api?year=${year}`);
            if (response.ok) {
                const data = await response.json();
                data.forEach(item => {
                    yearEvents.push({
                        date: item.tanggal,
                        name: item.keterangan,
                        type: item.is_cuti ? "cuti" : "national"
                    });
                });
                // Update Cache & Render Ulang setelah API selesai
                cachedEvents[year] = yearEvents;
                renderCalendar();
            }
        } catch (e) { console.error("Gagal API Nasional"); }
    }

    // --- 5. INITIALIZATION ---
    
    // Event Listeners
    document.getElementById('prevMonth').addEventListener('click', () => {
        currMonth--;
        if (currMonth < 0) { currMonth = 11; currYear--; loadEvents(currYear); }
        else { renderCalendar(); }
    });

    document.getElementById('nextMonth').addEventListener('click', () => {
        currMonth++;
        if (currMonth > 11) { currMonth = 0; currYear++; loadEvents(currYear); }
        else { renderCalendar(); }
    });

    // 1. Render Tanggal Dulu (Biar gak blank)
    renderCalendar();
    
    // 2. Load Data di Background
    loadEvents(currYear);

</script>

</body>
</html>