<?php
session_start();
include '../../db.php';

// 1. CEK LOGIN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. HITUNG DATA DASHBOARD

// A. Total Proyek
$q_proj = mysqli_query($conn, "SELECT COUNT(*) as total FROM projects");
$total_projects = mysqli_fetch_assoc($q_proj)['total'];

// B. Total Layanan
$q_serv = mysqli_query($conn, "SELECT COUNT(*) as total FROM services");
$total_services = mysqli_fetch_assoc($q_serv)['total'];

// C. Pesanan Baru (Invoice status 'Pending')
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM invoices WHERE status = 'Pending'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

// D. Estimasi Pendapatan (Sum Grand Total Invoice yang statusnya 'Lunas')
$q_income = mysqli_query($conn, "SELECT SUM(grand_total) as total FROM invoices WHERE status = 'Lunas'");
$d_income = mysqli_fetch_assoc($q_income);
$raw_income = $d_income['total'] ?? 0;

// Format Rupiah Singkat (Jt / M)
function formatShortRp($n) {
    if ($n >= 1000000000) return 'Rp ' . round($n / 1000000000, 1) . 'M';
    if ($n >= 1000000) return 'Rp ' . round($n / 1000000, 1) . 'jt';
    return 'Rp ' . number_format($n, 0, ',', '.');
}

$formatted_income = formatShortRp($raw_income);

// E. Proyek Terbaru
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
    <script id="tailwind-config">
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

    <!-- 1. SIDEBAR -->
    <?php 
        $currentPage = 'dashboard'; 
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER -->
        <?php 
            $pageTitle = "Dashboard Ringkasan"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <!-- CONTENT -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1400px] mx-auto">
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <!-- LEFT COLUMN -->
                    <div class="flex-1 flex flex-col gap-6">
                        <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                            <div>
                                <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Dashboard Ringkasan</h2>
                                <p class="text-[#4c669a] text-sm font-normal mt-1">Selamat datang kembali <?= $_SESSION['admin_name']  ?>! Berikut ringkasan performa hari ini.</p>
                            </div>
                        </div>

                        <!-- Cards -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
                            <!-- Card 1: Total Proyek -->
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-blue-50 text-blue-600 rounded-lg material-symbols-outlined">photo_library</span>
                                    <span class="text-xs font-semibold text-green-600 bg-green-50 px-2 py-0.5 rounded-full">Aktif</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Total Proyek</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $total_projects ?></h3>
                            </div>

                            <!-- Card 2: Total Layanan -->
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-purple-50 text-purple-600 rounded-lg material-symbols-outlined">handshake</span>
                                    <span class="text-xs font-semibold text-gray-400">Stable</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Total Layanan</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $total_services ?></h3>
                            </div>

                            <!-- Card 3: Pesanan Baru (Invoice Pending) -->
                            <div class="bg-white p-5 rounded-xl border border-[#cfd7e7] shadow-sm flex flex-col gap-1">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="p-2 bg-orange-50 text-orange-600 rounded-lg material-symbols-outlined">notifications_active</span>
                                    <span class="text-xs font-semibold text-orange-600 bg-orange-50 px-2 py-0.5 rounded-full">Baru</span>
                                </div>
                                <p class="text-[#64748b] text-sm font-medium">Invoice Pending</p>
                                <h3 class="text-[#0d121b] text-2xl font-bold"><?= $total_pending ?></h3>
                            </div>

    <!-- Card 4: Pendapatan (Total Lunas) -->
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
                                                    <span class="px-2 py-1 rounded-full text-[10px] font-bold <?= $row['status'] == 'Published' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' ?>">
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
                        
                        <!-- DYNAMIC CALENDAR WIDGET -->
                        <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm p-6">
                            
                            <!-- Calendar Header (Month Navigation) -->
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

                            <!-- Calendar Grid Header -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div class="text-[10px] font-bold text-red-500 text-center">Min</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Sen</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Sel</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Rab</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Kam</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Jum</div>
                                <div class="text-[10px] font-bold text-[#64748b] text-center">Sab</div>
                            </div>

                            <!-- Calendar Days Container -->
                            <div id="calendar-grid" class="grid grid-cols-7 gap-1 mb-4">
                                <!-- Days will be injected here by JS -->
                            </div>
                            
                            <!-- Holiday List -->
                            <div class="space-y-3 mt-4 pt-4 border-t border-gray-100 max-h-[200px] overflow-y-auto custom-scrollbar" id="holiday-list">
                                <p class="text-xs text-center text-gray-400">Memuat hari libur...</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- SCRIPT CLOCK & CALENDAR -->
    <script>
        // --- JAM DIGITAL ---
        function updateClock() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }).replace(/\./g, ':');
            document.getElementById('clock').textContent = timeString;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // --- KALENDER GLOBAL DINAMIS ---
        let currDate = new Date();
        let currYear = currDate.getFullYear();
        let currMonth = currDate.getMonth();
        let globalHolidays = [];

        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        // Fetch Hari Libur dari API (Nager.Date)
        async function fetchHolidays(year) {
            try {
                // Menggunakan API publik nager.at untuk Indonesia (ID)
                const response = await fetch(`https://date.nager.at/api/v3/PublicHolidays/${year}/ID`);
                if (!response.ok) throw new Error('Gagal mengambil data libur');
                globalHolidays = await response.json();
                renderCalendar(); // Render ulang setelah data dapat
            } catch (error) {
                console.error("Error fetching holidays:", error);
                document.getElementById('holiday-list').innerHTML = '<p class="text-xs text-center text-red-400">Gagal memuat libur.</p>';
                renderCalendar(); // Tetap render kalender meski tanpa libur
            }
        }

        // Render Kalender
        function renderCalendar() {
            const firstDayOfMonth = new Date(currYear, currMonth, 1).getDay(); // 0 = Minggu
            const lastDateOfMonth = new Date(currYear, currMonth + 1, 0).getDate();
            const lastDayOfLastMonth = new Date(currYear, currMonth, 0).getDate();
            
            let days = "";
            const calendarGrid = document.getElementById("calendar-grid");
            const currentMonthYear = document.getElementById("currentMonthYear");
            const holidayListEl = document.getElementById("holiday-list");

            currentMonthYear.textContent = `${monthNames[currMonth]} ${currYear}`;

            // List libur bulan ini untuk ditampilkan di bawah
            let holidaysThisMonth = [];

            // Tanggal bulan lalu (abu-abu)
            for (let i = firstDayOfMonth; i > 0; i--) {
                days += `<div class="text-[11px] text-gray-300 text-center py-2">${lastDayOfLastMonth - i + 1}</div>`;
            }

            // Tanggal bulan ini
            for (let i = 1; i <= lastDateOfMonth; i++) {
                let isToday = i === new Date().getDate() && currMonth === new Date().getMonth() && currYear === new Date().getFullYear() ? "bg-primary/10 text-primary font-bold ring-2 ring-primary/20" : "text-[#0d121b] hover:bg-gray-50";
                
                // Cek Hari Libur
                let dateStr = `${currYear}-${String(currMonth + 1).padStart(2, '0')}-${String(i).padStart(2, '0')}`;
                let holiday = globalHolidays.find(h => h.date === dateStr);
                let dayOfWeek = new Date(currYear, currMonth, i).getDay();
                
                let textColorClass = "";
                // Jika Minggu (0), warnai merah
                if (dayOfWeek === 0) {
                    textColorClass = "text-red-500 font-semibold";
                }

                // Jika Libur Nasional
                if (holiday) {
                    isToday += " bg-red-50 border border-red-200"; // Highlight merah muda
                    textColorClass = "text-red-600 font-bold";
                    holidaysThisMonth.push(holiday);
                }

                days += `<div class="text-[11px] text-center py-2 rounded-md cursor-pointer ${isToday} ${textColorClass}" title="${holiday ? holiday.localName : ''}">${i}</div>`;
            }

            calendarGrid.innerHTML = days;

            // Render List Libur di Bawah Kalender
            let holidayHTML = `<h5 class="text-[10px] font-bold text-[#64748b] uppercase tracking-wider mb-2">Agenda Bulan Ini</h5>`;
            
            if (holidaysThisMonth.length > 0) {
                holidaysThisMonth.forEach(h => {
                    // Format tanggal indonesia: 17 Agustus
                    let d = new Date(h.date);
                    let day = d.getDate();
                    let month = monthNames[d.getMonth()];
                    
                    holidayHTML += `
                    <div class="flex items-start gap-3">
                        <div class="w-1.5 h-1.5 rounded-full bg-red-500 mt-1.5 shrink-0"></div>
                        <div>
                            <p class="text-[11px] font-bold text-[#0d121b]">${day} ${month}: ${h.localName}</p>
                            <p class="text-[10px] text-[#64748b]">Hari Libur Nasional</p>
                        </div>
                    </div>`;
                });
            } else {
                holidayHTML += `<p class="text-[11px] text-gray-400 italic">Tidak ada hari libur nasional bulan ini.</p>`;
            }
            holidayListEl.innerHTML = holidayHTML;
        }

        // Event Listeners Navigasi Bulan
        document.getElementById('prevMonth').addEventListener('click', () => {
            currMonth--;
            if (currMonth < 0) {
                currMonth = 11;
                currYear--;
                fetchHolidays(currYear); // Fetch ulang jika tahun berubah
            } else {
                renderCalendar();
            }
        });

        document.getElementById('nextMonth').addEventListener('click', () => {
            currMonth++;
            if (currMonth > 11) {
                currMonth = 0;
                currYear++;
                fetchHolidays(currYear); // Fetch ulang jika tahun berubah
            } else {
                renderCalendar();
            }
        });

        // Init Load
        fetchHolidays(currYear);

    </script>

</body>
</html>