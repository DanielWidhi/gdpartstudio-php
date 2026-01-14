<?php
session_start();
include '../../db.php';
include 'log_helper.php'; // Pastikan helper ini ada di folder yang sama

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Logika Delete & Log (DIGABUNG DISINI)
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // A. Ambil Data Dulu (Penting untuk Log & Hapus File)
    $q_cek = mysqli_query($conn, "SELECT invoice_number, file_pdf FROM invoices WHERE id=$id");
    $data = mysqli_fetch_assoc($q_cek);
    
    // Simpan info untuk log
    $inv_num = $data['invoice_number'] ?? 'Unknown Invoice';

    // B. Hapus File PDF Fisik jika ada (Agar server bersih)
    if (!empty($data['file_pdf'])) {
        $filePath = "../../assets/invoice/" . $data['file_pdf'];
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    // C. Hapus Data dari Database
    $query_delete = "DELETE FROM invoices WHERE id=$id";

    if (mysqli_query($conn, $query_delete)) {
        // D. Catat Log (Hanya jika delete berhasil)
        if (function_exists('writeLog')) {
            writeLog($conn, $_SESSION['admin_id'], 'Delete', $inv_num, 'Menghapus data invoice');
        }
    }

    // E. Redirect
    header("Location: manage_invoices.php");
    exit;
}

// 3. Logika Search
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if($search) {
    $whereClause = "WHERE invoice_number LIKE '%$search%' OR client_name LIKE '%$search%'";
}

// 4. Query Data Utama
$query = "SELECT * FROM invoices $whereClause ORDER BY invoice_date DESC";
$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Manajemen Nota - GDPARTSTUDIO</title>
    
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
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- 1. SIDEBAR -->
    <?php 
        $currentPage = 'invoices'; // Active menu
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER -->
        <?php 
            $pageTitle = "Manajemen > Nota"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <!-- CONTENT -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6 pb-12">
                
                <!-- Title & Toolbar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[32px] font-bold leading-tight tracking-tight">Manajemen Nota</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Kelola pembuatan nota, pembayaran, dan pengiriman invoice ke klien.</p>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Search Form -->
                        <form method="GET" class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#9ca3af]">search</span>
                            <input name="search" value="<?= $search ?>" class="pl-10 pr-4 py-2.5 w-full md:w-64 rounded-lg border-[#cfd7e7] bg-white text-sm focus:ring-primary focus:border-primary placeholder:text-[#94a3b8]" placeholder="Cari nama atau no. nota..." type="text"/>
                        </form>
                        
                        <!-- Add Button -->
                        <a href="create_invoice.php" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-all shadow-md whitespace-nowrap">
                            <span class="material-symbols-outlined text-[20px]">add_circle</span>
                            Buat Nota Baru
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-[#cfd7e7]">
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">No. Nota</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Nama Klien</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Total Harga</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider text-center">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#f1f3f7]">
                                <?php if($total_rows > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <span class="text-[#0d121b] text-sm font-medium"><?= $row['invoice_number'] ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-[#0d121b] text-sm font-medium"><?= $row['client_name'] ?></p>
                                                <p class="text-[#64748b] text-xs"><?= $row['client_email'] ?></p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-[#0d121b] text-sm"><?= date('d M Y', strtotime($row['invoice_date'])) ?></p>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-[#0d121b] text-sm font-semibold">Rp <?= number_format($row['grand_total'], 0, ',', '.') ?></span>
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                <?php
                                                    $statusClass = '';
                                                    if($row['status'] == 'Lunas') $statusClass = 'bg-green-100 text-green-700 border-green-200';
                                                    elseif($row['status'] == 'Pending') $statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                                    else $statusClass = 'bg-red-100 text-red-700 border-red-200';
                                                ?>
                                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium border <?= $statusClass ?>">
                                                    <?= $row['status'] ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex items-center justify-end gap-2">
                                                    <!-- Action Buttons -->
                                                    <a href="download_invoice.php?id=<?= $row['id'] ?>" target="_blank" class="p-1.5 rounded-md text-[#64748b] hover:text-primary hover:bg-blue-50 transition-colors" title="Lihat/Print PDF">
                                                        <span class="material-symbols-outlined text-[20px]">print</span>
                                                    </a>
                                                    <a href="update_invoice.php?id=<?= $row['id'] ?>" class="p-1.5 rounded-md text-[#64748b] hover:text-primary hover:bg-blue-50 transition-colors" title="Edit">
                                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                                    </a>
                                                    <a href="manage_invoices.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Hapus nota ini?')" class="p-1.5 rounded-md text-[#64748b] hover:text-red-600 hover:bg-red-50 transition-colors" title="Hapus">
                                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada nota yang dibuat.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-[#cfd7e7] flex items-center justify-between bg-gray-50/50">
                        <p class="text-xs text-[#4c669a]">Menampilkan <?= $total_rows ?> dari <?= $total_rows ?> nota</p>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>