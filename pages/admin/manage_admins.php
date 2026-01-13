<?php
session_start();
include '../../db.php';

// 1. CEK LOGIN
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. LOGIKA TAMBAH ADMIN
if (isset($_POST['add_admin'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    // Password default: admin123 (hash)
    $password = password_hash('admin123', PASSWORD_DEFAULT);
    
    // Cek email duplikat
    $cek = mysqli_query($conn, "SELECT id FROM admins WHERE email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        $query = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password')";
        if(mysqli_query($conn, $query)){
            echo "<script>alert('Admin berhasil ditambahkan! Password default: admin123'); window.location='manage_admins.php';</script>";
        } else {
            echo "<script>alert('Gagal menambah admin.');</script>";
        }
    }
}

// 3. LOGIKA HAPUS ADMIN
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    
    // Cegah hapus diri sendiri
    if($id == $_SESSION['admin_id']){
        echo "<script>alert('Anda tidak bisa menghapus akun sendiri!'); window.location='manage_admins.php';</script>";
    } else {
        mysqli_query($conn, "DELETE FROM admins WHERE id=$id");
        header("Location: manage_admins.php");
        exit;
    }
}

// 4. LOGIKA SEARCH
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$whereClause = "";
if($search) {
    $whereClause = "WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
}

// Query Data
$query = "SELECT * FROM admins $whereClause ORDER BY id ASC";
$result = mysqli_query($conn, $query);
$total_rows = mysqli_num_rows($result);

// Helper function untuk inisial nama
function getInitials($name){
    $words = explode(" ", $name);
    $initials = "";
    foreach ($words as $w) {
        $initials .= mb_substr($w, 0, 1);
    }
    return strtoupper(substr($initials, 0, 2));
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Admin - GDPARTSTUDIO</title>
    
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
                        "status-active": "#ecfdf5",
                        "status-active-text": "#059669",
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

    <!-- SIDEBAR -->
    <?php 
        $currentPage = 'admins'; 
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER -->
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm text-[#4c669a]">
                <span>Manajemen</span>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-[#0d121b] font-medium">Daftar Admin</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?></p>
                    <p class="text-xs text-[#4c669a]"><?= $_SESSION['admin_email'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <!-- CONTENT -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[1200px] mx-auto flex flex-col gap-6 pb-12">
                
                <!-- Title & Toolbar -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-[#0d121b] text-[28px] font-bold leading-tight tracking-tight">Daftar Admin</h2>
                        <p class="text-[#4c669a] text-sm font-normal mt-1">Kelola semua personil yang memiliki akses ke dashboard GDPARTSTUDIO.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-[#94a3b8]">search</span>
                            <input name="search" value="<?= $search ?>" class="pl-10 pr-4 py-2.5 w-full md:w-64 rounded-lg border-[#cfd7e7] bg-white text-sm focus:ring-primary focus:border-primary placeholder:text-[#94a3b8]" placeholder="Cari admin..." type="text"/>
                        </form>
                        <a href="create_profile.php" class="flex items-center gap-2 bg-primary hover:bg-primary-hover text-white text-sm font-bold py-2.5 px-5 rounded-lg transition-all shadow-md whitespace-nowrap">
                            <span class="material-symbols-outlined text-[20px]">add</span>
                                Tambah Admin Baru
                        </a>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 border-b border-[#cfd7e7]">
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Foto Profil</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Nama Lengkap</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Alamat Email</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Peran (Role)</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-[#4c669a] uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#f1f3f7]">
                                <?php if($total_rows > 0): ?>
                                    <?php while($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-gray-50/50 transition-colors">
                                            <td class="px-6 py-4">
                                                <!-- Avatar Initials -->
                                                <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-[#4c669a] font-bold text-xs border border-gray-200">
                                                    <?= getInitials($row['name']) ?>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm font-semibold text-[#0d121b]"><?= $row['name'] ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-sm text-[#4c669a]"><?= $row['email'] ?></span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-700">Administrator</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-status-active text-status-active-text text-xs font-bold">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-status-active-text"></span>
                                                    Aktif
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-right">
    <div class="flex items-center justify-end gap-2">
        
        <!-- Tombol READ (Lihat Detail) -->
        <a href="profile.php?id=<?= $row['id'] ?>" class="p-2 text-[#4c669a] hover:bg-gray-100 rounded-lg transition-colors" title="Lihat Detail">
            <span class="material-symbols-outlined">visibility</span>
        </a>

        <!-- Tombol EDIT -->
        <a href="edit_profile.php?id=<?= $row['id'] ?>" class="p-2 text-[#4c669a] hover:bg-gray-100 rounded-lg transition-colors" title="Edit">
            <span class="material-symbols-outlined">edit</span>
        </a>

        <!-- Tombol DELETE (Logic lama tetap ada) -->
        <?php if($row['id'] != $_SESSION['admin_id']): ?>
            <a href="manage_admins.php?delete_id=<?= $row['id'] ?>" onclick="return confirm('Hapus admin ini?')" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                <span class="material-symbols-outlined">delete</span>
            </a>
        <?php else: ?>
            <span class="p-2 text-gray-300 cursor-not-allowed" title="Akun Sendiri">
                <span class="material-symbols-outlined">delete</span>
            </span>
        <?php endif; ?>

    </div>
</td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Belum ada data admin.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Footer -->
                    <div class="px-6 py-4 border-t border-[#cfd7e7] flex items-center justify-between bg-gray-50/50">
                        <p class="text-xs text-[#4c669a]">Menampilkan <?= $total_rows ?> dari <?= $total_rows ?> admin terdaftar</p>
                        <div class="flex items-center gap-1">
                            <button class="w-8 h-8 rounded border border-primary bg-primary text-white text-xs font-bold">1</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <!-- MODAL ADD ADMIN -->
    <div id="modalAddAdmin" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-[#0d121b]">Tambah Admin Baru</h3>
                <button onclick="document.getElementById('modalAddAdmin').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form method="POST" action="" class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input type="text" name="name" required class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary text-sm" placeholder="Contoh: Budi Santoso">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" name="email" required class="w-full rounded-lg border-gray-300 focus:ring-primary focus:border-primary text-sm" placeholder="email@domain.com">
                </div>
                <div class="p-3 bg-blue-50 text-blue-700 text-xs rounded-lg flex items-start gap-2">
                    <span class="material-symbols-outlined text-[16px] mt-0.5">info</span>
                    Password default untuk admin baru adalah <b>admin123</b>. Harap minta admin untuk segera mengganti password setelah login.
                </div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalAddAdmin').classList.add('hidden')" class="px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium">Batal</button>
                    <button type="submit" name="add_admin" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-hover text-sm font-bold">Simpan</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>