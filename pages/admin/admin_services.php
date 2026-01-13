<?php
session_start();
include '../../db.php';
include 'log_helper.php';

// Log
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // Ambil nama service dulu
    $q = mysqli_query($conn, "SELECT name FROM services WHERE id=$id");
    $d = mysqli_fetch_assoc($q);
    $srv_name = $d['name'] ?? 'Unknown Service';

    mysqli_query($conn, "DELETE FROM services WHERE id=$id");
    
    // --- LOG ---
    writeLog($conn, $_SESSION['admin_id'], 'Delete', $srv_name, 'Menghapus layanan');
    // -----------

    header("Location: admin_services.php");
    exit;
}

// 1. CEK LOGIN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. LOGIKA DELETE
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // Ambil info gambar untuk dihapus (Opsional, jika gambar disimpan lokal)
    $q = mysqli_query($conn, "SELECT image_url FROM services WHERE id=$id");
    $data = mysqli_fetch_assoc($q);
    
    // Hapus file fisik jika ada di folder uploads (Cek path apakah lokal atau URL eksternal)
    if ($data && strpos($data['image_url'], 'assets/uploads/') !== false) {
        if (file_exists("../../" . $data['image_url'])) {
            unlink("../../" . $data['image_url']);
        }
    }

    mysqli_query($conn, "DELETE FROM services WHERE id=$id");
    header("Location: admin_services.php");
    exit;
}

// 3. LOGIKA SEARCH
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if($search) {
    $whereClause = "WHERE name LIKE '%$search%' OR sku LIKE '%$search%' OR type LIKE '%$search%'";
}

// Query Data
$query = "SELECT * FROM services $whereClause ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Services Management - GDPARTSTUDIO</title>
    
    <!-- Fonts & Icons -->
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
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
        
        /* Custom Colors untuk Badge Dot */
        .dot-purple-500 { background-color: #a855f7; }
        .dot-blue-500 { background-color: #3b82f6; }
        .dot-orange-500 { background-color: #f97316; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- 1. INCLUDE SIDEBAR (Modular) -->
    <?php 
        $currentPage = 'services'; // Penanda halaman aktif
        // Pastikan path ini benar sesuai struktur folder Anda
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. INCLUDE MOBILE HEADER (Modular) -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- TOP HEADER -->
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-end px-8 shrink-0">
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?? 'Admin User' ?></p>
                    <p class="text-xs text-[#4c669a]"><?= $_SESSION['admin_email'] ?? 'admin@gdpartstudio.com' ?></p>
                </div>
                <!-- Avatar -->
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <!-- CONTENT SCROLL AREA -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6">
                
                <!-- Page Heading -->
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Services Management</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Manage photography and videography packages, prices, and service details.</p>
                    </div>
                </div>

                <!-- Toolbar: Search & Add -->
                <div class="flex flex-col sm:flex-row gap-4 items-center justify-between bg-white p-4 rounded-xl border border-[#cfd7e7] shadow-sm">
                    <!-- Search Form -->
                    <form method="GET" class="w-full sm:w-96 relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-[#9ca3af]">
                            <span class="material-symbols-outlined">search</span>
                        </div>
                        <input type="text" name="search" value="<?= $search ?>" class="block w-full pl-10 pr-3 py-2.5 border-none rounded-lg bg-[#f3f4f6] text-sm placeholder-[#9ca3af] focus:outline-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all" placeholder="Search by service name or category..."/>
                    </form>
                    
                    <!-- Add Button -->
                    <a href="create_service.php" class="w-full sm:w-auto flex items-center justify-center gap-2 bg-primary hover:bg-primary-hover text-white px-5 py-2.5 rounded-lg transition-colors shadow-sm font-bold text-sm">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
                        Add New Service
                    </a>
                </div>

                <!-- Data Table -->
                <div class="bg-white border border-[#cfd7e7] rounded-xl shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-[#f8fafc] border-b border-[#e2e8f0]">
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase w-20">Image</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase min-w-[200px]">Service Name</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase">Type</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase">Base Price</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase text-center">Status</th>
                                    <th class="px-6 py-4 text-xs font-semibold text-[#64748b] uppercase text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#e2e8f0]">
                                <?php if($total_rows > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="group hover:bg-[#f8fafc] transition-colors">
                                            
                                            <!-- Image -->
                                            <td class="px-6 py-4 align-middle">
                                                <div class="h-12 w-16 bg-gray-100 rounded-md overflow-hidden bg-cover bg-center border border-gray-200" 
                                                     style="background-image: url('<?= $row['image_url'] ?>');">
                                                </div>
                                            </td>
                                            
                                            <!-- Name & SKU -->
                                            <td class="px-6 py-4 align-middle">
                                                <p class="text-[#0d121b] text-sm font-medium"><?= $row['name'] ?></p>
                                                <p class="text-[#64748b] text-xs">SKU: <?= $row['sku'] ?></p>
                                            </td>
                                            
                                            <!-- Type Badge -->
                                            <td class="px-6 py-4 align-middle">
                                                <?php 
                                                    $type = $row['type'];
                                                    $badge = 'bg-gray-50 text-gray-700 border-gray-100 dot-gray-500'; // Default
                                                    
                                                    if($type == 'Photography') {
                                                        $badge = 'bg-purple-50 text-purple-700 border-purple-100 dot-purple-500';
                                                    } elseif($type == 'Videography') {
                                                        $badge = 'bg-blue-50 text-blue-700 border-blue-100 dot-blue-500';
                                                    } elseif($type == 'Documentary') {
                                                        $badge = 'bg-orange-50 text-orange-700 border-orange-100 dot-orange-500';
                                                    }
                                                ?>
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium border <?= $badge ?>">
                                                    <span class="w-1.5 h-1.5 rounded-full <?= explode(' ', $badge)[3] ?>"></span>
                                                    <?= $type ?>
                                                </span>
                                            </td>
                                            
                                            <!-- Price -->
                                            <td class="px-6 py-4 align-middle">
                                                <p class="text-[#0d121b] text-sm font-medium">IDR <?= number_format($row['price'], 0, ',', '.') ?></p>
                                                <p class="text-[#64748b] text-xs"><?= $row['price_unit'] ?></p>
                                            </td>
                                            
                                            <!-- Status -->
                                            <td class="px-6 py-4 align-middle text-center">
                                                <?php 
                                                    $statusClass = $row['status'] == 'Active' 
                                                        ? 'bg-green-100 text-green-700 border-green-200' 
                                                        : 'bg-gray-100 text-gray-600 border-gray-200';
                                                ?>
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                                    <?= $row['status'] ?>
                                                </span>
                                            </td>
                                            
                                            <!-- Actions -->
                                            <td class="px-6 py-4 align-middle text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="edit_service.php?id=<?= $row['id'] ?>" class="p-1.5 rounded-md text-[#64748b] hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit">
                                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                                    </a>
                                                    <a href="admin_services.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus layanan ini?')" class="p-1.5 rounded-md text-[#64748b] hover:text-red-600 hover:bg-red-50 transition-colors" title="Delete">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-10 text-center text-[#64748b]">Belum ada layanan. Silakan tambah data baru.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Simple Pagination (Static for now) -->
                    <div class="px-6 py-4 bg-white border-t border-[#e2e8f0] flex items-center justify-between">
                        <span class="text-sm text-[#64748b]">Showing <?= $total_rows ?> entries</span>
                        <!-- Anda bisa menambahkan logika pagination PHP di sini jika data sudah banyak -->
                    </div>
                </div>

            </div>
        </div>
    </main>
</body>
</html>