<?php
session_start();
include '../../db.php';

// 1. Cek Login
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

// 2. Ambil Data Admin Saat Ini
$admin_id = $_SESSION['admin_id'];
$query = mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id");
$admin = mysqli_fetch_assoc($query);

// 3. Proses Update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi Email Unik (Jika diganti)
    if($email != $admin['email']){
        $cek = mysqli_query($conn, "SELECT id FROM admins WHERE email = '$email'");
        if(mysqli_num_rows($cek) > 0){
            echo "<script>alert('Email sudah digunakan!'); window.location='edit_profile.php';</script>";
            exit;
        }
    }

    // Logic Ganti Password
    if (!empty($new_password)) {
        // Cek password lama
        if (password_verify($current_password, $admin['password'])) {
            if ($new_password === $confirm_password) {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE admins SET name='$name', email='$email', password='$password_hash' WHERE id=$admin_id";
            } else {
                echo "<script>alert('Konfirmasi password baru tidak cocok!'); window.location='edit_profile.php';</script>";
                exit;
            }
        } else {
            echo "<script>alert('Password saat ini salah!'); window.location='edit_profile.php';</script>";
            exit;
        }
    } else {
        // Update tanpa ganti password
        $sql = "UPDATE admins SET name='$name', email='$email' WHERE id=$admin_id";
    }

    if (mysqli_query($conn, $sql)) {
        // Update Session
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_email'] = $email;
        echo "<script>alert('Profil Berhasil Diperbarui!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Gagal update profil.');</script>";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Edit Profile Admin - GDPARTSTUDIO</title>
    
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
        .material-symbols-outlined { font-size: 20px; font-variation-settings: 'FILL' 0; }
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
        
        <!-- Header -->
        <header class="h-16 bg-white border-b border-[#cfd7e7] flex items-center justify-between px-8 shrink-0">
            <div class="flex items-center gap-2 text-sm text-[#4c669a]">
                <a href="profile.php" class="hover:text-primary">Profil Admin</a>
                <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                <span class="font-medium text-[#0d121b]">Edit Profile</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-semibold text-[#0d121b]"><?= $admin['name'] ?></p>
                    <p class="text-xs text-[#4c669a]"><?= $admin['email'] ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gray-200 bg-cover bg-center border border-gray-300" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
            </div>
        </header>

        <!-- Content -->
        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[800px] mx-auto flex flex-col gap-8 pb-12">
                
                <!-- Title -->
                <div>
                    <h2 class="text-[#0d121b] text-[28px] font-bold leading-tight tracking-tight">Edit Profile Admin</h2>
                    <p class="text-[#4c669a] text-sm font-normal mt-1">Perbarui informasi profil dan pengaturan akun Anda.</p>
                </div>

                <form method="POST" action="" class="space-y-8">
                    
                    <!-- Informasi Pribadi Card -->
                    <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm">
                        <div class="p-6 border-b border-[#cfd7e7]">
                            <h3 class="text-base font-bold text-[#0d121b]">Informasi Pribadi</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            
                            <!-- Foto Profil (UI Only) -->
                            <div class="flex items-center gap-6">
                                <div class="relative group">
                                    <div class="w-24 h-24 rounded-full bg-gray-200 bg-cover bg-center border-4 border-white shadow-md" style="background-image: url('../../assets/images/user-placeholder.jpg');"></div>
                                    <button type="button" class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full shadow-lg hover:bg-primary-hover transition-colors">
                                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                                    </button>
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-[#0d121b]">Foto Profil</h4>
                                    <p class="text-xs text-[#4c669a] mt-1 mb-3">JPG, GIF, atau PNG. Maks 2MB.</p>
                                    <div class="flex gap-2">
                                        <button type="button" class="text-xs font-semibold px-4 py-2 bg-gray-100 text-[#0d121b] rounded-lg hover:bg-gray-200 transition-colors">Ganti Foto</button>
                                    </div>
                                </div>
                            </div>

                            <!-- Input Nama & Email -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold text-[#0d121b]">Nama Lengkap</label>
                                    <input name="name" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary transition-shadow" value="<?= $admin['name'] ?>" type="text" required/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold text-[#0d121b]">Alamat Email</label>
                                    <input name="email" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary transition-shadow" value="<?= $admin['email'] ?>" type="email" required/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold text-[#0d121b]">Nomor Telepon</label>
                                    <input class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary transition-shadow" placeholder="+62 ..." type="tel"/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold text-[#0d121b]">Role</label>
                                    <input class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] bg-gray-50 text-sm text-[#64748b] cursor-not-allowed" disabled value="Super Admin" type="text"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ganti Password Card -->
                    <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm">
                        <div class="p-6 border-b border-[#cfd7e7]">
                            <h3 class="text-base font-bold text-[#0d121b]">Ganti Password</h3>
                            <p class="text-xs text-[#4c669a] mt-1">Kosongkan jika tidak ingin mengubah password.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex flex-col gap-2 max-w-md">
                                <label class="text-sm font-semibold text-[#0d121b]">Password Saat Ini</label>
                                <div class="relative">
                                    <input name="current_password" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary" placeholder="••••••••" type="password" id="current_pass"/>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold text-[#0d121b]">Password Baru</label>
                                    <input name="new_password" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary" placeholder="Masukkan password baru" type="password"/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold text-[#0d121b]">Konfirmasi Password Baru</label>
                                    <input name="confirm_password" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm focus:ring-primary focus:border-primary" placeholder="Ulangi password baru" type="password"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pb-10">
                        <a href="profile.php" class="px-6 py-2.5 rounded-lg border border-[#cfd7e7] text-sm font-bold text-[#4c669a] hover:bg-gray-100 transition-colors">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:bg-primary-hover shadow-md shadow-primary/20 transition-all">Perbarui Profil</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

</body>
</html>