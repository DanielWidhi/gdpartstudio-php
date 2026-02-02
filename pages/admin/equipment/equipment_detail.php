<?php
session_start();
include '../../db.php';
include 'log_helper.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Ambil ID
if (!isset($_GET['id'])) {
    header("Location: equipment.php");
    exit;
}
$id = intval($_GET['id']);

// 3. Query Data Alat
$query = mysqli_query($conn, "SELECT * FROM equipments WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Alat tidak ditemukan!'); window.location='equipment.php';</script>";
    exit;
}

// Helper Warna
function getStatusColor($status) {
    switch ($status) {
        case 'Di Studio': return 'text-emerald-600';
        case 'Di Lapangan': return 'text-blue-600';
        case 'Maintenance': return 'text-amber-600';
        default: return 'text-red-600';
    }
}
function getStatusDot($status) {
    switch ($status) {
        case 'Di Studio': return 'bg-emerald-600';
        case 'Di Lapangan': return 'bg-blue-600';
        case 'Maintenance': return 'bg-amber-600';
        default: return 'bg-red-600';
    }
}

// Hitung Sisa Waktu Servis
$nextService = strtotime($data['next_service_date']);
$today = time();
$daysLeft = ceil(($nextService - $today) / 86400);

if ($daysLeft < 0) {
    $serviceText = "Terlewat " . abs($daysLeft) . " Hari Lalu";
    $serviceColor = "text-red-600 font-bold";
} else {
    $serviceText = $daysLeft . " Hari Lagi";
    $serviceColor = "text-amber-600";
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Detail Alat - GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: { primary: "#135bec", "primary-hover": "#0f4bc4", "background-light": "#f8f9fc" },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
    <style> body { font-family: 'Inter', sans-serif; } .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0; } </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <?php $currentPage = 'equipment'; include '../../assets/components/admin/sidebar.php'; ?>
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm">
                <a href="equipment.php" class="text-[#4c669a] hover:text-primary transition-colors">Equipment</a>
                <span class="material-symbols-outlined text-[16px] text-[#9ca3af]">chevron_right</span>
                <span class="font-semibold text-[#0d121b]">Detail Alat</span>
            </div>
            <!-- Header User Area -->
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?? 'Admin' ?></p>
                    <p class="text-xs text-[#4c669a]"><?= $_SESSION['admin_email'] ?? '' ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-8">
                
                <!-- Title & Action -->
                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <button class="p-2 hover:bg-gray-200 rounded-lg transition-colors text-[#4c669a]" onclick="window.location.href='equipment.php'">
                            <span class="material-symbols-outlined">arrow_back</span>
                        </button>
                        <div>
                            <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight"><?= $data['name'] ?></h2>
                            <div class="flex items-center gap-3 mt-1">
                                <span class="px-2.5 py-0.5 bg-blue-50 text-blue-700 text-xs font-bold rounded-full border border-blue-100 uppercase"><?= $data['category'] ?></span>
                                <span class="text-[#4c669a] text-sm">Ditambahkan pada <?= date('d M Y', strtotime($data['created_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <a href="edit_equipment.php?id=<?= $data['id'] ?>" class="bg-primary hover:bg-primary-hover text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-md shadow-primary/20 transition-all flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">edit</span> Edit Alat
                    </a>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- FOTO ALAT -->
                    <div class="lg:col-span-5">
                        <div class="bg-white border border-[#cfd7e7] rounded-2xl overflow-hidden shadow-sm aspect-square flex items-center justify-center p-8 relative">
                            <?php if (!empty($data['image_url']) && file_exists("../../" . $data['image_url'])): ?>
                                <img src="../../<?= $data['image_url'] ?>" class="w-full h-full object-contain">
                            <?php else: ?>
                                <div class="flex flex-col items-center text-gray-400">
                                    <span class="material-symbols-outlined text-6xl">image_not_supported</span>
                                    <span class="text-sm mt-2">Tidak ada foto</span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- DETAIL TEKNIS -->
                    <div class="lg:col-span-7 flex flex-col gap-6">
                        <div class="bg-white border border-[#cfd7e7] rounded-2xl p-6 shadow-sm">
                            <h3 class="text-sm font-bold text-[#0d121b] mb-6 uppercase tracking-wider">Informasi Teknis</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                                <div>
                                    <p class="text-[11px] font-bold text-[#64748b] uppercase mb-1">Serial Number</p>
                                    <p class="text-sm font-mono font-semibold text-[#0d121b]"><?= $data['serial_number'] ?: '-' ?></p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-[#64748b] uppercase mb-1">Status Saat Ini</p>
                                    <span class="inline-flex items-center gap-1.5 text-sm font-semibold <?= getStatusColor($data['status']) ?>">
                                        <span class="w-2 h-2 rounded-full <?= getStatusDot($data['status']) ?>"></span> <?= $data['status'] ?>
                                    </span>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-[#64748b] uppercase mb-1">Kondisi Fisik</p>
                                    <span class="px-2 py-0.5 text-[10px] font-bold bg-green-50 text-green-700 rounded-full border border-green-100"><?= strtoupper($data['condition_status']) ?></span>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-[#64748b] uppercase mb-1">Lokasi Penyimpanan</p>
                                    <p class="text-sm font-medium text-[#0d121b]"><?= $data['rack_location'] ?: '-' ?></p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-[#64748b] uppercase mb-1">Terakhir Servis</p>
                                    <p class="text-sm font-medium text-[#0d121b]"><?= $data['last_service_date'] ? date('d M Y', strtotime($data['last_service_date'])) : '-' ?></p>
                                </div>
                                <div>
                                    <p class="text-[11px] font-bold text-[#64748b] uppercase mb-1">Jadwal Servis Berikutnya</p>
                                    <p class="text-sm font-medium <?= $serviceColor ?>">
                                        <?= date('d M Y', strtotime($data['next_service_date'])) ?> (<?= $serviceText ?>)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- INFO PEMINJAM (Hanya jika status Di Lapangan / Maintenance) -->
                        <?php if ($data['status'] == 'Di Lapangan' || $data['status'] == 'Maintenance'): ?>
                        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-6">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center text-primary shadow-sm shrink-0">
                                    <span class="material-symbols-outlined">info</span>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-[#0d121b]">Informasi Aktif</h4>
                                    <p class="text-xs text-[#4c669a] mt-1">
                                        Sedang digunakan/dipegang oleh <strong><?= $data['assigned_to'] ?></strong>.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                </div>

                <!-- RIWAYAT (DUMMY/STATIC - Karena belum ada tabel riwayat) -->
                <div class="bg-white border border-[#cfd7e7] rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-[#e2e8f0] flex items-center justify-between">
                        <h3 class="text-sm font-bold text-[#0d121b]">Riwayat Peminjaman (Contoh)</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase">Peminjam</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase">Tanggal Pinjam</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]">
                                <!-- Data Dummy -->
                                <tr class="hover:bg-[#f8fafc]">
                                    <td class="px-6 py-4 text-sm font-semibold text-[#0d121b]">Budi Santoso</td>
                                    <td class="px-6 py-4 text-xs text-[#0d121b]">05 Mar 2024</td>
                                    <td class="px-6 py-4"><span class="px-2 py-0.5 text-[10px] font-bold bg-gray-50 text-gray-700 rounded-full border border-gray-100">RETURNED</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>