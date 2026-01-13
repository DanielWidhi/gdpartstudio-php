<?php
session_start();
include '../../db.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Proses Tambah Admin
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; // Password mentah
    
    // Hash Password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek Email Duplikat
    $cek = mysqli_query($conn, "SELECT id FROM admins WHERE email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        // Insert
        $query = "INSERT INTO admins (name, email, password) VALUES ('$name', '$email', '$password_hash')";
        
        if(mysqli_query($conn, $query)){
            echo "<script>alert('Admin Berhasil Ditambahkan!'); window.location='manage_admins.php';</script>";
        } else {
            echo "<script>alert('Gagal menambah admin: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Create Profile Admin - GDPARTSTUDIO</title>
    
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
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; font-size: 20px; }
        .material-symbols-outlined.fill { font-variation-settings: 'FILL' 1; }
    </style>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <!-- 1. INCLUDE SIDEBAR -->
    <?php 
        $currentPage = 'settings'; // Set active menu ke Settings (atau bisa buat menu baru 'users')
        include '../../assets/components/admin/sidebar.php'; 
    ?>

    <!-- 2. INCLUDE MOBILE HEADER -->
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm text-[#4c669a]">
                <a href="manage_admins.php" class="hover:text-primary">Settings</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="text-[#0d121b] font-medium">Create Profile Admin</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $_SESSION['admin_name'] ?></p>
                    <p class="text-xs text-[#4c669a]"><?= $_SESSION['admin_email'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[700px] mx-auto flex flex-col gap-8 pb-12">
                
                <!-- Title -->
                <div>
                    <h2 class="text-[#0d121b] text-[28px] font-bold leading-tight tracking-tight">Buat Profil Admin Baru</h2>
                    <p class="text-[#4c669a] text-sm font-normal mt-1">Tambahkan anggota tim baru untuk mengelola platform GDPARTSTUDIO.</p>
                </div>

                <!-- Form Card -->
                <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                    <form method="POST" action="" class="p-6 md:p-8 space-y-6">
                        
                        <!-- Foto Profil (UI Only) -->
                        <div class="flex flex-col items-center justify-center pb-6 border-b border-[#f1f3f7]">
                            <div class="relative group cursor-pointer">
                                <div class="w-24 h-24 rounded-full bg-gray-100 border-2 border-dashed border-[#cfd7e7] flex items-center justify-center overflow-hidden hover:bg-gray-50 transition">
                                    <span class="material-symbols-outlined text-[#4c669a] text-[32px]">add_a_photo</span>
                                </div>
                                <button type="button" class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full shadow-lg hover:bg-primary-hover transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <p class="text-sm font-semibold text-[#0d121b]">Foto Profil</p>
                                <p class="text-xs text-[#64748b]">JPG, PNG max 2MB</p>
                            </div>
                        </div>

                        <!-- Input Fields -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]" for="fullname">Nama Lengkap</label>
                            <input name="name" required class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] bg-white text-sm focus:ring-primary focus:border-primary placeholder:text-[#94a3b8]" id="fullname" placeholder="Masukkan nama lengkap" type="text"/>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]" for="email">Alamat Email</label>
                            <input name="email" required class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] bg-white text-sm focus:ring-primary focus:border-primary placeholder:text-[#94a3b8]" id="email" placeholder="contoh@gdpartstudio.com" type="email"/>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]" for="password">Password</label>
                            <div class="relative">
                                <input name="password" required class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] bg-white text-sm focus:ring-primary focus:border-primary placeholder:text-[#94a3b8]" id="password" placeholder="••••••••" type="password"/>
                                <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 -translate-y-1/2 text-[#64748b] hover:text-primary">
                                    <span class="material-symbols-outlined text-[20px]" id="eyeIcon">visibility</span>
                                </button>
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]" for="role">Peran (Role)</label>
                            <select class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] bg-white text-sm focus:ring-primary focus:border-primary text-[#0d121b]" id="role">
                                <option value="admin" selected>Administrator (Akses Penuh)</option>
                                <option value="editor">Editor (Hanya Portfolio &amp; Layanan)</option>
                            </select>
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center gap-3 pt-6">
                            <button type="submit" class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-bold py-3 px-6 rounded-lg transition-all shadow-md shadow-primary/10">
                                Simpan Profil
                            </button>
                            <a href="manage_admins.php" class="flex-1 bg-white hover:bg-gray-50 text-[#4c669a] border border-[#cfd7e7] text-sm font-bold py-3 px-6 rounded-lg transition-all text-center">
                                Batal
                            </a>
                        </div>

                    </form>
                    
                    <!-- Info Box -->
                    <div class="bg-blue-50/50 p-6 border-t border-[#cfd7e7] flex gap-3">
                        <span class="material-symbols-outlined text-blue-600 text-[20px]">info</span>
                        <p class="text-xs text-[#4c669a] leading-relaxed">
                            Admin yang baru dibuat dapat langsung login menggunakan email dan password yang Anda atur di atas.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <script>
        function togglePassword() {
            var input = document.getElementById("password");
            var icon = document.getElementById("eyeIcon");
            if (input.type === "password") {
                input.type = "text";
                icon.innerText = "visibility_off";
            } else {
                input.type = "password";
                icon.innerText = "visibility";
            }
        }
    </script>

</body>
</html>