<?php
session_start();
include '../../db.php';
include 'log_helper.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

$admin_id = $_SESSION['admin_id'];
$query_get = mysqli_query($conn, "SELECT * FROM admins WHERE id = $admin_id");
$admin = mysqli_fetch_assoc($query_get);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi Email
    if ($email != $admin['email']) {
        $cek = mysqli_query($conn, "SELECT id FROM admins WHERE email = '$email'");
        if (mysqli_num_rows($cek) > 0) {
            echo "<script>alert('Email sudah digunakan!'); window.location='edit_profile.php';</script>";
            exit;
        }
    }

    // --- LOGIKA UPDATE FOTO ---
    $avatar_query = "";
    if (!empty($_FILES["avatar"]["name"])) {
        $target_dir = "../../assets/images/admin/";
        if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }

        $file_extension = pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION);
        $new_filename = time() . "_" . uniqid() . "." . $file_extension;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $target_file)) {
            // Hapus foto lama
            if (!empty($admin['avatar']) && file_exists("../../" . $admin['avatar'])) {
                unlink("../../" . $admin['avatar']);
            }
            $db_avatar_path = "assets/images/admin/" . $new_filename;
            $avatar_query = ", avatar='$db_avatar_path'";
            
            // Update Session Avatar (Jika Anda menyimpannya di session)
            // $_SESSION['admin_avatar'] = $db_avatar_path; 
        }
    }

    $sql = ""; 
    // Logic Password
    if (!empty($new_password)) {
        if (password_verify($current_password, $admin['password'])) {
            if ($new_password === $confirm_password) {
                $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
                $sql = "UPDATE admins SET name='$name', email='$email', password='$password_hash' $avatar_query WHERE id=$admin_id";
            } else {
                echo "<script>alert('Konfirmasi password tidak cocok!'); window.location='edit_profile.php';</script>"; exit;
            }
        } else {
            echo "<script>alert('Password lama salah!'); window.location='edit_profile.php';</script>"; exit;
        }
    } else {
        $sql = "UPDATE admins SET name='$name', email='$email' $avatar_query WHERE id=$admin_id";
    }

    if (mysqli_query($conn, $sql)) {
        if (function_exists('writeLog')) {
            writeLog($conn, $admin_id, 'Update', 'Profil Sendiri', 'Update profil/foto');
        }
        $_SESSION['admin_name'] = $name;
        $_SESSION['admin_email'] = $email;
        echo "<script>alert('Profil Berhasil Diperbarui!'); window.location='profile.php';</script>";
    } else {
        echo "<script>alert('Gagal update database.');</script>";
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Edit Profile - GDPARTSTUDIO</title>
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
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
</head>
<body class="bg-background-light text-[#0d121b] flex h-screen overflow-hidden">

    <?php $currentPage = 'settings'; include '../../assets/components/admin/sidebar.php'; ?>
    <?php include '../../assets/components/admin/mobile_header.php'; ?>

    <main class="flex-1 flex flex-col h-full overflow-hidden relative md:ml-0 mt-14 md:mt-0">
        <?php 
            $pageTitle = "Pengaturan > Edit Profil"; 
            include '../../assets/components/admin/header.php'; 
        ?>

        <div class="flex-1 overflow-y-auto bg-background-light p-4 md:p-8">
            <div class="max-w-[800px] mx-auto flex flex-col gap-8 pb-12">
                
                <div>
                    <h2 class="text-[#0d121b] text-[28px] font-bold">Edit Profile Admin</h2>
                    <p class="text-[#4c669a] text-sm mt-1">Perbarui informasi profil Anda.</p>
                </div>

                <form method="POST" action="" enctype="multipart/form-data" class="space-y-8">
                    
                    <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm">
                        <div class="p-6 border-b border-[#cfd7e7]">
                            <h3 class="text-base font-bold text-[#0d121b]">Informasi Pribadi</h3>
                        </div>
                        <div class="p-6 space-y-6">
                            
                            <!-- Foto Profil -->
                            <div class="flex items-center gap-6">
                                <div class="relative group cursor-pointer" onclick="document.getElementById('avatarInput').click()">
                                    <?php 
                                        $avatar = !empty($admin['avatar']) ? "../../" . $admin['avatar'] : "../../assets/images/user-placeholder.jpg";
                                    ?>
                                    <div class="w-24 h-24 rounded-full bg-gray-200 bg-cover bg-center border-4 border-white shadow-md overflow-hidden">
                                        <img id="avatarPreview" src="<?= $avatar ?>" class="w-full h-full object-cover">
                                    </div>
                                    <button type="button" class="absolute bottom-0 right-0 bg-primary text-white p-1.5 rounded-full shadow-lg hover:bg-primary-hover">
                                        <span class="material-symbols-outlined text-[18px]">photo_camera</span>
                                    </button>
                                    <input type="file" name="avatar" id="avatarInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                                </div>
                                <div>
                                    <h4 class="text-sm font-semibold text-[#0d121b]">Foto Profil</h4>
                                    <p class="text-xs text-[#4c669a] mt-1 mb-3">Klik kamera untuk mengganti foto.</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold">Nama Lengkap</label>
                                    <input name="name" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm" value="<?= $admin['name'] ?>" type="text" required/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold">Alamat Email</label>
                                    <input name="email" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm" value="<?= $admin['email'] ?>" type="email" required/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Ganti Password Card (Sama seperti sebelumnya) -->
                    <div class="bg-white rounded-xl border border-[#cfd7e7] shadow-sm">
                        <div class="p-6 border-b border-[#cfd7e7]">
                            <h3 class="text-base font-bold text-[#0d121b]">Ganti Password</h3>
                            <p class="text-xs text-[#4c669a]">Kosongkan jika tidak ingin mengubah.</p>
                        </div>
                        <div class="p-6 space-y-6">
                            <div class="flex flex-col gap-2 max-w-md">
                                <label class="text-sm font-semibold">Password Saat Ini</label>
                                <input name="current_password" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm" type="password"/>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold">Password Baru</label>
                                    <input name="new_password" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm" type="password"/>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <label class="text-sm font-semibold">Konfirmasi Password Baru</label>
                                    <input name="confirm_password" class="w-full px-4 py-2.5 rounded-lg border-[#cfd7e7] text-sm" type="password"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pb-10">
                        <a href="profile.php" class="px-6 py-2.5 rounded-lg border border-[#cfd7e7] text-sm font-bold text-[#4c669a] hover:bg-gray-100">Batal</a>
                        <button type="submit" class="px-6 py-2.5 rounded-lg bg-primary text-white text-sm font-bold hover:bg-primary-hover shadow-md">Perbarui Profil</button>
                    </div>

                </form>
            </div>
        </div>
    </main>

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>
</html>