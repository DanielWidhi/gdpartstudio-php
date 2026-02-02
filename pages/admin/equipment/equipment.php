<?php
session_start();
include '../../db.php';
include 'log_helper.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. PROSES UPDATE STATUS (Via Modal)
if (isset($_POST['update_status'])) {
    $id = intval($_POST['equipment_id']);
    $new_status = $_POST['status'];
    $assigned_to = mysqli_real_escape_string($conn, $_POST['assigned_to']);
    $location_task = mysqli_real_escape_string($conn, $_POST['location_task']);
    $notes = mysqli_real_escape_string($conn, $_POST['notes']);
    
    // Update data
    $query = "UPDATE equipments SET status='$new_status', assigned_to='$assigned_to', rack_location='$location_task' WHERE id=$id";
    if (mysqli_query($conn, $query)) {
        if (function_exists('writeLog')) {
            $item_name = $_POST['equipment_name_log']; 
            writeLog($conn, $_SESSION['admin_id'], 'Update', $item_name, "Ubah status ke: $new_status");
        }
        echo "<script>window.location='equipment.php';</script>";
    }
}

// 3. PROSES DELETE
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $q_cek = mysqli_query($conn, "SELECT name, image_url FROM equipments WHERE id=$id");
    $d_cek = mysqli_fetch_assoc($q_cek);
    
    if (!empty($d_cek['image_url']) && file_exists("../../" . $d_cek['image_url'])) {
        unlink("../../" . $d_cek['image_url']);
    }

    if (mysqli_query($conn, "DELETE FROM equipments WHERE id=$id")) {
        if (function_exists('writeLog')) {
            writeLog($conn, $_SESSION['admin_id'], 'Delete', $d_cek['name'], 'Menghapus alat');
        }
    }
    header("Location: equipment.php");
    exit;
}

// 4. QUERY DATA
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if($search) { $whereClause = "WHERE name LIKE '%$search%' OR serial_number LIKE '%$search%'"; }

// Statistik
$stats = [];
$cats = ['Kamera', 'Lensa', 'Drone', 'Lighting'];
foreach ($cats as $c) {
    $q = mysqli_query($conn, "SELECT COUNT(*) as total FROM equipments WHERE category='$c'");
    $stats[$c] = mysqli_fetch_assoc($q)['total'];
}

// List Alat
$query = "SELECT * FROM equipments $whereClause ORDER BY id DESC";
$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);

// Notifikasi Servis
$today = date('Y-m-d');
$q_alert = mysqli_query($conn, "SELECT * FROM equipments WHERE next_service_date <= '$today' AND next_service_date IS NOT NULL AND status != 'Rusak' LIMIT 1");
$alert_service = mysqli_fetch_assoc($q_alert);

// Helper Warna Sesuai Desain HTML
function getStatusClass($status) {
    switch ($status) {
        case 'Di Studio': return 'text-emerald-600 bg-emerald-50'; // Hijau
        case 'Di Lapangan': return 'text-blue-600 bg-blue-50'; // Biru
        case 'Maintenance': return 'text-amber-600 bg-amber-50'; // Kuning
        case 'Rusak': return 'text-red-600 bg-red-50'; // Merah
        default: return 'text-gray-600 bg-gray-50';
    }
}

function getConditionClass($cond) {
    switch ($cond) {
        case 'Excellent': return 'bg-green-50 text-green-700 border-green-100';
        case 'Good': return 'bg-blue-50 text-blue-700 border-blue-100';
        case 'Fair': return 'bg-amber-50 text-amber-700 border-amber-100';
        case 'Poor': return 'bg-red-50 text-red-700 border-red-100';
        default: return 'bg-gray-50 text-gray-700 border-gray-100';
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>GDPARTSTUDIO - Manajemen Inventaris Alat</title>
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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; font-size: 24px; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        
        /* Animasi Notifikasi */
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        .animate-slide-in { animation: slideIn 0.5s ease-out forwards; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark text-[#0d121b] flex h-screen overflow-hidden">

    <!-- SIDEBAR COMPONENT -->
    <?php $currentPage = 'equipment'; include '../../assets/components/admin/sidebar.php'; ?>

    <!-- MOBILE HEADER COMPONENT -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER COMPONENT (Ganti isi text via variable) -->
        <?php 
            $pageTitle = "Inventory > Equipment Management"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <!-- NOTIFIKASI SERVIS (Floating) -->
        <?php if ($alert_service): ?>
        <div id="serviceAlert" class="absolute top-20 right-8 w-80 bg-white border-l-4 border-red-500 rounded-xl shadow-2xl p-4 flex gap-4 z-50 animate-slide-in">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <span class="material-symbols-outlined text-red-600 text-[20px]">notification_important</span>
            </div>
            <div class="flex-1">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-[#0d121b]">Jadwal Servis Tiba</h4>
                    <span class="text-[10px] text-[#64748b]">Baru saja</span>
                </div>
                <p class="text-xs text-[#4c669a] mt-1 leading-relaxed">
                    Alat <span class="font-bold text-[#0d121b]"><?= $alert_service['name'] ?></span> memerlukan perawatan rutin segera.
                </p>
                <div class="mt-3 flex gap-2">
                    <button onclick="openUpdateModal(<?= $alert_service['id'] ?>, '<?= addslashes($alert_service['name']) ?>', '<?= $alert_service['serial_number'] ?>', '<?= $alert_service['status'] ?>')" class="text-[11px] font-bold text-primary hover:underline">Update Status</button>
                    <button onclick="document.getElementById('serviceAlert').style.display='none'" class="text-[11px] font-bold text-[#64748b] hover:text-[#0d121b]">Tutup</button>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- CONTENT -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1400px] mx-auto flex flex-col gap-6">
                
                <!-- Title & Button -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Manajemen Inventaris Alat</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Kelola dan pantau seluruh perlengkapan fotografi dan videografi GDPARTSTUDIO.</p>
                    </div>
                    <a href="create_equipment.php" class="bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition-all flex items-center gap-2 shadow-md">
                        <span class="material-symbols-outlined text-[20px]">add</span>
                        Tambah Alat Baru
                    </a>
                </div>

                <!-- Cards Summary -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-5 flex flex-col hover:border-primary/40 transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                <span class="material-symbols-outlined fill">photo_camera</span>
                            </div>
                            <span class="text-xs font-bold text-[#9ca3af]"><?= $stats['Kamera'] ?> Units</span>
                        </div>
                        <h3 class="text-base font-bold text-[#0d121b]">Kamera</h3>
                        <p class="text-xs text-[#4c669a] mt-1">DSLR, Mirrorless, Cinema</p>
                    </div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-5 flex flex-col hover:border-primary/40 transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                                <span class="material-symbols-outlined fill">camera_roll</span>
                            </div>
                            <span class="text-xs font-bold text-[#9ca3af]"><?= $stats['Lensa'] ?> Units</span>
                        </div>
                        <h3 class="text-base font-bold text-[#0d121b]">Lensa</h3>
                        <p class="text-xs text-[#4c669a] mt-1">Prime, Zoom, Anamorphic</p>
                    </div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-5 flex flex-col hover:border-primary/40 transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-purple-50 text-purple-600 rounded-lg">
                                <span class="material-symbols-outlined fill">helicopter</span>
                            </div>
                            <span class="text-xs font-bold text-[#9ca3af]"><?= $stats['Drone'] ?> Units</span>
                        </div>
                        <h3 class="text-base font-bold text-[#0d121b]">Drone</h3>
                        <p class="text-xs text-[#4c669a] mt-1">FPV, Aerial Photography</p>
                    </div>
                    <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm p-5 flex flex-col hover:border-primary/40 transition-all cursor-pointer">
                        <div class="flex items-center justify-between mb-3">
                            <div class="p-2 bg-yellow-50 text-yellow-600 rounded-lg">
                                <span class="material-symbols-outlined fill">lightbulb</span>
                            </div>
                            <span class="text-xs font-bold text-[#9ca3af]"><?= $stats['Lighting'] ?> Units</span>
                        </div>
                        <h3 class="text-base font-bold text-[#0d121b]">Lighting</h3>
                        <p class="text-xs text-[#4c669a] mt-1">LED, Strobes, Modifiers</p>
                    </div>
                </div>

                <!-- TABLE -->
                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                    
                    <div class="px-6 py-4 border-b border-[#e2e8f0] flex flex-col sm:flex-row sm:items-center justify-between bg-white sticky top-0 z-10 gap-4">
                        <div class="flex items-center gap-4 w-full sm:w-auto">
                            <h3 class="text-sm font-bold text-[#0d121b]">Daftar Alat</h3>
                            <form class="relative w-full sm:w-64" method="GET">
                                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-[18px]">search</span>
                                <input name="search" value="<?= $search ?>" class="pl-9 pr-4 py-1.5 border border-[#cfd7e7] rounded-lg text-xs w-full focus:ring-1 focus:ring-primary focus:border-primary outline-none" placeholder="Cari nama alat atau SN..." />
                            </form>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 border border-[#cfd7e7] rounded-lg text-xs font-medium hover:bg-gray-50 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">filter_list</span> Filter
                            </button>
                            <button class="px-3 py-1.5 border border-[#cfd7e7] rounded-lg text-xs font-medium hover:bg-gray-50 flex items-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">download</span> Export
                            </button>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Nama Alat</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Serial Number</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Kondisi</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase tracking-wider">Jadwal Servis</th>
                                    <th class="px-6 py-4 text-[11px] font-bold text-[#64748b] uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]">
                                <?php if($total_rows > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                    <tr class="hover:bg-[#f8fafc] transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center text-gray-500 overflow-hidden">
                                                    <?php if(!empty($row['image_url'])): ?>
                                                        <img src="../../<?= $row['image_url'] ?>" class="w-full h-full object-cover">
                                                    <?php else: ?>
                                                        <!-- Icon Default sesuai Kategori -->
                                                        <?php 
                                                            $icon = 'inventory_2';
                                                            if($row['category'] == 'Kamera') $icon = 'photo_camera';
                                                            elseif($row['category'] == 'Lensa') $icon = 'camera_roll';
                                                            elseif($row['category'] == 'Drone') $icon = 'helicopter';
                                                            elseif($row['category'] == 'Lighting') $icon = 'lightbulb';
                                                        ?>
                                                        <span class="material-symbols-outlined"><?= $icon ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <div>
                                                    <a href="equipment_detail.php?id=<?= $row['id'] ?>" class="text-sm font-semibold text-[#0d121b] hover:text-primary hover:underline">
                                                        <?= $row['name'] ?>
                                                    </a>
                                                    <p class="text-[10px] text-[#64748b]"><?= $row['category'] ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-xs font-mono text-[#0d121b]"><?= $row['serial_number'] ?: '-' ?></td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-0.5">
                                                <span class="inline-flex items-center gap-1.5 text-xs font-medium <?= getStatusClass($row['status']) ?> px-2 py-0.5 rounded-full border border-transparent">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span> <?= $row['status'] ?>
                                                </span>
                                                <span class="text-[10px] text-[#64748b]">
                                                    <?= $row['status'] == 'Di Lapangan' ? "Dipinjam: " . $row['assigned_to'] : $row['rack_location'] ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 text-[10px] font-bold rounded-full border <?= getConditionClass($row['condition_status']) ?>">
                                                <?= strtoupper($row['condition_status']) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="text-xs text-[#0d121b]">Terakhir: <?= $row['last_service_date'] ? date('d M Y', strtotime($row['last_service_date'])) : '-' ?></span>
                                                <?php 
                                                    $nextDate = $row['next_service_date'];
                                                    $isOverdue = $nextDate && strtotime($nextDate) <= strtotime($today);
                                                    $nextClass = $isOverdue ? 'text-red-600 font-bold' : 'text-[#64748b]';
                                                    $nextText = $isOverdue ? "TERLEWAT: " . date('d M Y', strtotime($nextDate)) : "Next: " . ($nextDate ? date('d M Y', strtotime($nextDate)) : '-');
                                                ?>
                                                <span class="text-[10px] <?= $nextClass ?>"><?= $nextText ?></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button onclick="openUpdateModal(<?= $row['id'] ?>, '<?= addslashes($row['name']) ?>', '<?= $row['serial_number'] ?>', '<?= $row['status'] ?>')" class="p-1.5 text-[#64748b] hover:text-primary hover:bg-primary/5 rounded-md transition-all" title="Update Status">
                                                    <span class="material-symbols-outlined text-[20px]">sync_alt</span>
                                                </button>
                                                <a href="equipment.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Hapus alat ini?')" class="p-1.5 text-[#64748b] hover:text-red-600 hover:bg-red-50 rounded-md transition-all" title="Hapus">
                                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data alat.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination (Static) -->
                    <div class="px-6 py-4 border-t border-[#e2e8f0] flex items-center justify-between bg-white">
                        <span class="text-xs text-[#64748b]">Menampilkan <?= $total_rows ?> alat</span>
                        <div class="flex items-center gap-2">
                            <button class="p-1 text-gray-400 hover:text-primary disabled:opacity-50" disabled="">
                                <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                            </button>
                            <div class="flex items-center gap-1">
                                <button class="w-7 h-7 flex items-center justify-center text-xs font-bold bg-primary text-white rounded">1</button>
                            </div>
                            <button class="p-1 text-gray-400 hover:text-primary">
                                <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- MODAL UPDATE STATUS -->
    <div id="updateModal" class="hidden fixed inset-0 z-50 bg-[#0d121b]/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up">
            <div class="px-6 py-5 border-b border-[#e2e8f0] flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-[#0d121b]">Update Status Alat</h3>
                    <p class="text-xs text-[#64748b] mt-0.5" id="modalItemName">Loading...</p>
                </div>
                <button onclick="closeModal()" class="text-[#64748b] hover:text-[#0d121b] transition-colors">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <div class="p-6">
                <form method="POST" action="">
                    <input type="hidden" name="equipment_id" id="modalItemId">
                    <input type="hidden" name="equipment_name_log" id="modalItemNameLog">
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-[#64748b] uppercase tracking-wider mb-3">Pilih Status Baru</label>
                        <div class="grid grid-cols-3 gap-3">
                            <label class="relative cursor-pointer group">
                                <input class="peer sr-only" name="status" type="radio" value="Di Studio" onchange="toggleFields()">
                                <div class="px-3 py-4 border border-[#cfd7e7] rounded-xl flex flex-col items-center gap-2 hover:border-emerald-500/50 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition-all">
                                    <span class="material-symbols-outlined text-emerald-600">home</span>
                                    <span class="text-xs font-semibold text-[#0d121b]">Di Studio</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input class="peer sr-only" name="status" type="radio" value="Di Lapangan" onchange="toggleFields()">
                                <div class="px-3 py-4 border border-[#cfd7e7] rounded-xl flex flex-col items-center gap-2 hover:border-blue-500/50 peer-checked:border-blue-50 peer-checked:bg-blue-50 transition-all">
                                    <span class="material-symbols-outlined text-blue-600">work</span>
                                    <span class="text-xs font-semibold text-[#0d121b]">Di Lapangan</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer group">
                                <input class="peer sr-only" name="status" type="radio" value="Maintenance" onchange="toggleFields()">
                                <div class="px-3 py-4 border border-[#cfd7e7] rounded-xl flex flex-col items-center gap-2 hover:border-amber-500/50 peer-checked:border-amber-500 peer-checked:bg-amber-50 transition-all">
                                    <span class="material-symbols-outlined text-amber-600">build</span>
                                    <span class="text-xs font-semibold text-[#0d121b]">Servis</span>
                                </div>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div class="space-y-1.5" id="field-person">
                            <label class="text-xs font-semibold text-[#0d121b]">Nama Peminjam / Teknisi</label>
                            <input name="assigned_to" id="inputAssignedTo" class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none"/>
                        </div>
                        <div class="space-y-1.5" id="field-location">
                            <label class="text-xs font-semibold text-[#0d121b]">Lokasi (Rak / Lokasi Tugas)</label>
                            <input name="location_task" id="inputLocation" class="w-full px-3 py-2 border border-[#cfd7e7] rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none"/>
                        </div>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-[#0d121b]">Catatan (Opsional)</label>
                        <textarea name="notes" class="w-full px-4 py-2 border border-[#cfd7e7] rounded-lg text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none min-h-[80px]" placeholder="Kondisi alat saat ini..."></textarea>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button type="button" onclick="closeModal()" class="px-5 py-2 text-sm font-semibold text-[#64748b] hover:text-[#0d121b] transition-colors">Batal</button>
                        <button type="submit" name="update_status" class="bg-primary hover:bg-primary-hover text-white px-6 py-2 rounded-lg text-sm font-bold transition-all shadow-md">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openUpdateModal(id, name, sn, currentStatus) {
            document.getElementById('modalItemId').value = id;
            document.getElementById('modalItemName').innerText = name + ' (' + sn + ')';
            document.getElementById('modalItemNameLog').value = name;
            let radios = document.getElementsByName('status');
            for (let r of radios) { if (r.value === currentStatus) r.checked = true; }
            document.getElementById('updateModal').classList.remove('hidden');
            toggleFields();
        }
        function closeModal() { document.getElementById('updateModal').classList.add('hidden'); }
        function toggleFields() {
            const status = document.querySelector('input[name="status"]:checked').value;
            const fieldPerson = document.getElementById('field-person');
            const fieldLoc = document.getElementById('field-location');
            if (status === 'Di Studio') {
                fieldPerson.classList.add('hidden');
                fieldLoc.classList.remove('hidden');
            } else {
                fieldPerson.classList.remove('hidden');
                fieldLoc.classList.remove('hidden');
            }
        }
    </script>
</body>
</html>