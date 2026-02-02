<?php
session_start();
include '../../db.php';
include 'log_helper.php';

// Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password']; 
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Cek Email
    $cek = mysqli_query($conn, "SELECT id FROM admins WHERE email = '$email'");
    if(mysqli_num_rows($cek) > 0){
        echo "<script>alert('Email sudah terdaftar!'); window.location='create_profile.php';</script>";
        exit;
    }

    // --- LOGIKA UPLOAD FOTO ---
    $db_avatar_path = NULL; // Default null jika tidak ada foto

    if (!empty($_FILES["avatar"]["name"])) {
        $target_dir = "../../assets/images/admin/";
        
        // Buat folder jika belum ada
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $new_filename = time() . "_" . uniqid() . "." . $file_extension; // Nama file unik
        $target_file = $target_dir . $new_filename;

        // Upload
        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            $db_avatar_path = "assets/images/admin/" . $new_filename;
        }
    }

    // Insert Database
    $query = "INSERT INTO admins (name, email, password, avatar) VALUES ('$name', '$email', '$password_hash', '$db_avatar_path')";
    
    if(mysqli_query($conn, $query)){
        if (function_exists('writeLog')) {
            writeLog($conn, $_SESSION['admin_id'], 'Create', $name, 'Menambahkan admin baru');
        }
        echo "<script>alert('Admin Berhasil Ditambahkan!'); window.location='manage_admins.php';</script>";
    } else {
        echo "<script>alert('Gagal menambah admin: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Create Profile Admin - GDPARTSTUDIO</title>
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
                    colors: { primary: "#135bec", "primary-hover": "#0f4bc4", "background-light": "#f8f9fc" },
                    fontFamily: { display: ["Inter", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <?php $currentPage = 'settings'; include '../../assets/components/admin/sidebar.php'; ?>
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        <?php 
            $pageTitle = "Pengaturan > Tambah Admin"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[700px] mx-auto flex flex-col gap-8 pb-12">
                <div>
                    <h2 class="text-[#0d121b] text-[28px] font-bold leading-tight tracking-tight">Buat Profil Admin Baru</h2>
                    <p class="text-[#4c669a] text-sm font-normal mt-1">Tambahkan anggota tim baru.</p>
                </div>

                <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm overflow-hidden">
                    <!-- Form dengan Multipart -->
                    <form method="POST" action="" enctype="multipart/form-data" class="p-6 md:p-8 space-y-6">
                        
                        <!-- Upload Foto -->
                        <div class="flex flex-col items-center justify-center pb-6 border-b border-[#f1f3f7]">
                            <div class="relative group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                                <!-- Preview Image -->
                                <div class="w-24 h-24 rounded-full bg-gray-100 border-2 border-dashed border-[#cfd7e7] flex items-center justify-center overflow-hidden hover:bg-gray-50 transition">
                                    <img id="avatarPreview" src="" class="w-full h-full object-cover hidden">
                                    <span id="avatarIcon" class="material-symbols-outlined text-[#4c669a] text-[32px]">add_a_photo</span>
                                </div>
                                <button type="button" class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full shadow-lg hover:bg-primary-hover transition-colors">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </button>
                            </div>
                            <div class="mt-3 text-center">
                                <p class="text-sm font-semibold text-[#0d121b]">Foto Profil</p>
                                <p class="text-xs text-[#64748b]">JPG, PNG max 2MB</p>
                            </div>
                            <!-- Input File Tersembunyi -->
                            <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                        </div>

                        <!-- Inputs -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]">Nama Lengkap</label>
                            <input name="name" required class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary" placeholder="Masukkan nama lengkap" type="text"/>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]">Alamat Email</label>
                            <input name="email" required class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary" placeholder="contoh@gdpartstudio.com" type="email"/>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b]">Password</label>
                            <div class="relative">
                                <input name="password" required class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary" id="password" placeholder="••••••••" type="password"/>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 pt-6">
                            <button type="submit" class="flex-1 bg-primary hover:bg-primary-hover text-white text-sm font-bold py-3 px-6 rounded-lg transition-all shadow-md">Simpan Profil</button>
                            <a href="manage_admins.php" class="flex-1 bg-white hover:bg-gray-50 text-[#4c669a] border border-[#cfd7e7] text-sm font-bold py-3 px-6 rounded-lg transition-all text-center">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- JS Preview Gambar -->
    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                    document.getElementById('avatarPreview').classList.remove('hidden');
                    document.getElementById('avatarIcon').classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>