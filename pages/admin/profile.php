<?php
session_start();
include '../../db.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Ambil Data Admin dari Database
$admin_id = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id");
$admin = mysqli_fetch_assoc($query);

// Format Tanggal
$join_date = date('d F Y', strtotime($admin['created_at']));
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Profil Admin - GDPARTSTUDIO</title>
    
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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; font-size: 20px; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- 1. INCLUDE SIDEBAR -->
    <?php 
        $currentPage = 'settings'; 
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. INCLUDE MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- HEADER -->
        <?php 
            $pageTitle = "Pengaturan > Profil Admin"; // Judul untuk breadcrumb
            include '../../assets/components/admin/header.php'; 
        ?>
        <!-- CONTENT -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-12 lg:p-20">
            <div class="max-w-[800px] mx-auto">
                <div class="bg-white rounded-3xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                    
                    <!-- Cover Photo -->
                    <div class="relative h-32 bg-gradient-to-r from-blue-500 to-primary"></div>
                    
                    <div class="px-8 pb-12">
                        <!-- Profile Avatar Area -->
                        <div class="flex flex-col md:flex-row justify-between items-end -mt-16 mb-12 gap-6">
                            <div class="relative">
                                <?php 
                                    $avatar = !empty($admin['avatar']) ? "../../" . $admin['avatar'] : "../../assets/images/user-placeholder.jpg";
                                ?>
                                <!-- Gunakan tag IMG untuk lebih fleksibel -->
                                <img src="<?= $avatar ?>" class="w-32 h-32 md:w-40 md:h-40 rounded-full border-4 border-white shadow-md object-cover bg-white">
                                <div class="absolute bottom-2 right-2 w-6 h-6 bg-green-500 border-2 border-white rounded-full"></div>
                            </div>
                            <div class="flex-1 flex flex-col md:flex-row md:justify-end">
                                <!-- Tombol ini bisa diarahkan ke halaman edit profil jika ada -->
                                <a href="edit_profile.php" class="flex items-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary-hover text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-primary/20">
                                    <span class="material-symbols-outlined text-lg">edit</span>
                                    Edit Profil
                                    </a>
                            </div>
                        </div>

                        <!-- Info Section -->
                        <div class="space-y-10">
                            <div>
                                <h2 class="text-[#0d121b] text-3xl font-bold tracking-tight mb-2">Profil Admin</h2>
                                <p class="text-[#4c669a] text-sm">Kelola informasi pribadi dan pengaturan akun Anda untuk mengontrol dashboard GDPARTSTUDIO.</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                                <div class="flex flex-col gap-1.5 border-b border-gray-50 pb-4">
                                    <span class="text-[11px] font-bold text-[#4c669a] uppercase tracking-wider">Nama Lengkap</span>
                                    <p class="text-base font-semibold text-[#0d121b]"><?= $admin['name'] ?></p>
                                </div>
                                <div class="flex flex-col gap-1.5 border-b border-gray-50 pb-4">
                                    <span class="text-[11px] font-bold text-[#4c669a] uppercase tracking-wider">Alamat Email</span>
                                    <p class="text-base font-semibold text-[#0d121b]"><?= $admin['email'] ?></p>
                                </div>
                                <div class="flex flex-col gap-1.5 border-b border-gray-50 pb-4">
                                    <span class="text-[11px] font-bold text-[#4c669a] uppercase tracking-wider">Peran</span>
                                    <div class="flex items-center gap-2">
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700">SUPER ADMIN</span>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5 border-b border-gray-50 pb-4">
                                    <span class="text-[11px] font-bold text-[#4c669a] uppercase tracking-wider">Tanggal Bergabung</span>
                                    <p class="text-base font-semibold text-[#0d121b]"><?= $join_date ?></p>
                                </div>
                                <div class="flex flex-col gap-1.5 border-b border-gray-50 pb-4">
                                    <span class="text-[11px] font-bold text-[#4c669a] uppercase tracking-wider">Status Akun</span>
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                        <p class="text-base font-semibold text-[#0d121b]">Aktif</p>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-1.5 border-b border-gray-50 pb-4">
                                    <span class="text-[11px] font-bold text-[#4c669a] uppercase tracking-wider">Terakhir Login</span>
                                    <p class="text-base font-semibold text-[#0d121b]">Hari ini, <?= date('H:i') ?> WIB</p>
                                </div>
                            </div>

                            <!-- Security Banner -->
                            <div class="mt-8 p-6 bg-blue-50 rounded-2xl border border-blue-100 flex items-start gap-4">
                                <span class="material-symbols-outlined text-blue-600 mt-1">verified_user</span>
                                <div>
                                    <h4 class="text-sm font-bold text-blue-900">Keamanan Akun</h4>
                                    <p class="text-xs text-blue-700 mt-1 leading-relaxed">Autentikasi dua faktor diaktifkan. Akun Anda terlindungi dengan standar keamanan terbaru dari GDPARTSTUDIO.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center text-center">
                    <p class="text-[11px] text-[#4c669a] font-medium uppercase tracking-widest">GDPARTSTUDIO Management System • Version 2.4.0</p>
                </div>
            </div>
        </div>
    </main>

</body>
</html>