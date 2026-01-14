<?php
session_start();
include '../../db.php';
include 'log_helper.php';

// 1. CEK LOGIN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. LOGIKA CREATE & DELETE (Tetap Sama seperti sebelumnya)
// ... (Saya persingkat agar fokus ke perubahan tampilan) ...

if (isset($_POST['add_admin'])) {
    // ... (Kode tambah admin tetap sama) ...
}

if (isset($_GET['delete_id'])) {
    // ... (Kode hapus admin tetap sama) ...
}

// 3. SEARCH & READ DATA
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if($search) {
    $whereClause = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

$query = "SELECT * FROM admins $whereClause ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);

// Helper Initials (Untuk fallback jika tidak ada foto)
function getInitials($name){
    $words = explode(" ", $name);
    $initials = "";
    foreach ($words as $w) { $initials .= mb_substr($w, 0, 1); }
    return strtoupper(substr($initials, 0, 2));
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Admin - GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#135bec", "primary-hover": "#0f4bc4", "background-light": "#f8f9fc" },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
    <style> body { font-family: 'Inter', sans-serif; } .material-symbols-outlined { font-size: 20px; } </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <?php 
        $currentPage = 'admins'; 
        include '../../assets/components/admin/sidebar.php'; 
    ?>
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- INCLUDE HEADER BARU -->
        <?php 
            $pageTitle = "Manajemen > Daftar Admin"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6 pb-12">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[28px] font-bold leading-tight tracking-tight">Daftar Admin</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Kelola personil admin GDPARTSTUDIO.</p>
                    </div>
                    <div class="flex items-center gap-3">
                         <!-- Tombol Tambah Admin (Sama seperti sebelumnya) -->
                         <a href="create_profile.php" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-all shadow-md whitespace-nowrap">
                            <span class="material-symbols-outlined text-[20px]">add</span> Tambah Admin Baru
                        </a>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-[#cfd7e7]">
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Foto Profil</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#f1f3f7]">
                                <?php if($total_rows > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <!-- LOGIKA TAMPIL FOTO / INISIAL -->
                                                <?php if(!empty($row['avatar']) && file_exists("../../" . $row['avatar'])): ?>
                                                    <!-- Tampilkan Foto -->
                                                    <div class="w-10 h-10 rounded-full bg-cover bg-center border border-gray-200" 
                                                         style="background-image: url('../../<?= $row['avatar'] ?>');"></div>
                                                <?php else: ?>
                                                    <!-- Tampilkan Inisial (Fallback) -->
                                                    <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-[#4c669a] font-bold text-xs border border-gray-200">
                                                        <?= getInitials($row['name']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            
                                            <td class="px-6 py-4">
                                                <span class="text-sm font-semibold text-[#0d121b]"><?= $row['name'] ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-[#4c669a]"><?= $row['email'] ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Aktif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <!-- Tombol Aksi (Read/Delete) -->
                                                <div class="flex items-center justify-end gap-2">
                                                    <a href="admin_detail.php?id=<?= $row['id'] ?>" class="p-2 text-[#4c669a] hover:bg-gray-100 rounded-lg" title="Lihat">
                                                        <span class="material-symbols-outlined">visibility</span>
                                                    </a>
                                                    <?php if($row['id'] != $_SESSION['admin_id']): ?>
                                                        <a href="manage_admins.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Hapus admin ini?')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg" title="Hapus">
                                                            <span class="material-symbols-outlined">delete</span>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>