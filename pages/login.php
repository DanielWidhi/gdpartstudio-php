<?php
session_start();

// 1. INCLUDE KONEKSI DATABASE
// Naik satu level karena login.php ada di dalam folder 'pages'
if (file_exists('../db.php')) {
    include '../db.php';
} else {
    die("Error: File koneksi database (db.php) tidak ditemukan.");
}

// 2. INCLUDE HELPER LOG
// Helper ada di 'pages/admin/', sedangkan kita di 'pages/'
if (file_exists('admin/log_helper.php')) {
    include 'admin/log_helper.php';
} else {
    // Buat fungsi dummy agar tidak error jika file helper hilang
    if (!function_exists('writeLog')) { function writeLog($a,$b,$c,$d,$e){} }
}

// 3. CEK SESSION (Jika sudah login, lempar ke dashboard)
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: admin/admin_portfolio.php"); // Sesuaikan dengan file utama dashboard Anda
    exit;
}

$error = "";
$loginSuccess = false;

// 4. PROSES LOGIN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Cek Email di Database
    $query = "SELECT * FROM admins WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    // SKENARIO 1: Email Ditemukan
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        $admin_id = $row['id'];
        
        // Verifikasi Password
        if (password_verify($password, $row['password'])) {
            // --- A. LOGIN SUKSES ---
            session_regenerate_id(true); // Keamanan: Cegah Session Fixation
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            
            $loginSuccess = true; // Trigger JS untuk suara & redirect

            // Log Sukses
            if (function_exists('writeLog')) {
                writeLog($conn, $admin_id, 'Login', 'System', 'Login Berhasil');
            }

        } else {
            // --- B. PASSWORD SALAH ---
            $error = "Password yang Anda masukkan salah.";

            // Log Gagal (Password Salah)
            if (function_exists('writeLog')) {
                writeLog($conn, $admin_id, 'Login Failed', 'System', 'Gagal: Password Salah');
            }
        }
    } else {
        // SKENARIO 2: Email Tidak Dikenal (Orang Asing / Typo)
        $error = "Email tidak terdaftar dalam sistem.";

        // Log Gagal (Intruder)
        // Kita kirim ID = 0 (Pastikan DB activity_logs sudah diubah agar admin_id boleh NULL/0)
        if (function_exists('writeLog')) {
            writeLog($conn, 0, 'Login Failed', $email, 'Percobaan login: Email tidak terdaftar');
        }
    }
}
?>

<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Login Admin - GDPARTSTUDIO</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet"/>
    <!-- Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: { primary: "#135bec", "background-light": "#f6f6f8" },
                    fontFamily: { display: ["Plus Jakarta Sans", "sans-serif"] },
                },
            },
        }
    </script>
</head>
<body class="bg-background-light font-display min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Audio Element (Pastikan file ada di assets/sounds/) -->
    <audio id="successSound" src="../assets/sounds/suc1.mp3" preload="auto"></audio>

    <div class="w-full max-w-[420px] bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden border border-gray-100 transition-all">
        
        <!-- Header Image -->
        <div class="relative h-32 bg-primary/5 flex items-center justify-center overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-40" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAb0GTy5B4zH7fH6D1zeSRs-VKmtWx9V4sg4eiMTAWT7AfuZQ1pdkoSn-d-bBZgLfLWlJLF3ciZ5FAbaWEIWG6bapFWs8k_J7T_m53aWxQG2-Zaqv6smz8hMz8XEaHcDJw4loZORm9D90IUoiRbPBNDO33hzk-bil0zlHvrz4DoibL2QOi_4EXsDWzV_9CSfE6yX2jkiqaMWv1i-4JnXIYSFjpoxWjWM4GhZ389N1l7s0jgSfFKbVaxtmimkFJCcq_rKZ6jmt3wNsY");'></div>
            <div class="absolute inset-0 bg-gradient-to-b from-transparent to-white/20"></div>
            <div class="relative z-10 flex flex-col items-center">
                <div class="h-14 w-14 bg-primary text-white rounded-xl flex items-center justify-center shadow-lg shadow-primary/30 mb-2 transform rotate-3">
                    <span class="material-symbols-outlined text-[32px]">camera_video</span>
                </div>
            </div>
        </div>

        <div class="p-8 pt-6">
            <div class="text-center mb-8">
                <h1 class="text-slate-900 text-2xl font-bold tracking-tight">Admin Portal</h1>
                <p class="text-slate-500 text-sm mt-1">Please sign in to manage your portfolio</p>
            </div>

            <!-- Error Alert -->
            <?php if($error): ?>
            <div class="mb-6 p-4 rounded-lg bg-red-50 border border-red-100 text-red-600 text-sm font-medium flex items-start gap-2 animate-pulse">
                <span class="material-symbols-outlined text-[20px] mt-0.5">error</span>
                <span><?= $error ?></span>
            </div>
            <?php endif; ?>

            <!-- Success Alert (Hidden by default until PHP sets flag) -->
            <?php if($loginSuccess): ?>
            <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-100 text-green-700 text-sm font-medium flex items-center gap-2 animate-bounce">
                <span class="material-symbols-outlined text-[20px]">check_circle</span>
                <span>Login Berhasil! Mengalihkan...</span>
            </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form class="flex flex-col gap-5" method="POST" action="">
                
                <div class="space-y-1.5">
                    <label class="text-slate-900 text-sm font-semibold" for="email">Email Address</label>
                    <div class="relative">
                        <input class="w-full rounded-lg border-gray-200 bg-gray-50 text-slate-900 focus:border-primary focus:ring-primary h-11 pl-10 pr-4 text-sm transition-all" 
                               id="email" name="email" type="email" placeholder="admin@gdpartstudio.com" required autocomplete="email"/>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none flex items-center">
                            <span class="material-symbols-outlined text-[20px]">mail</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <div class="flex justify-between items-center">
                        <label class="text-slate-900 text-sm font-semibold" for="password">Password</label>
                    </div>
                    <div class="relative group">
                        <input class="w-full rounded-lg border-gray-200 bg-gray-50 text-slate-900 focus:border-primary focus:ring-primary h-11 pl-10 pr-10 text-sm transition-all" 
                               id="password" name="password" type="password" placeholder="Enter password" required/>
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none flex items-center">
                            <span class="material-symbols-outlined text-[20px]">lock</span>
                        </div>
                        <button type="button" onclick="togglePassword()" class="absolute right-0 top-0 h-full px-3 text-gray-400 hover:text-slate-600 flex items-center justify-center outline-none transition-colors">
                            <span class="material-symbols-outlined text-[20px]" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="mt-2 w-full flex items-center justify-center rounded-lg h-12 bg-primary hover:bg-primary-hover text-white text-sm font-bold tracking-wide transition-all shadow-lg shadow-primary/20 hover:shadow-primary/40 transform active:scale-[0.98]">
                    Sign In
                </button>
            </form>
        </div>

        <div class="bg-gray-50 p-4 border-t border-gray-100 text-center">
            <p class="text-gray-400 text-xs font-medium">© <?= date('Y') ?> GDPARTSTUDIO. All rights reserved.</p>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Toggle Password Visibility
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            var eyeIcon = document.getElementById("eyeIcon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.innerText = "visibility_off";
            } else {
                passwordInput.type = "password";
                eyeIcon.innerText = "visibility";
            }
        }

        // Handle Success Login (Sound & Redirect)
        <?php if($loginSuccess): ?>
        window.onload = function() {
            var audio = document.getElementById("successSound");
            var dashboardUrl = "admin/admin_portfolio.php"; // Update jika nama file dashboard Anda berbeda

            // Coba putar audio
            var playPromise = audio.play();

            if (playPromise !== undefined) {
                playPromise.then(_ => {
                    // Audio berhasil diputar, tunggu sebentar lalu redirect
                    setTimeout(function() {
                        window.location.href = dashboardUrl;
                    }, 1500);
                }).catch(error => {
                    // Autoplay diblokir browser, langsung redirect
                    window.location.href = dashboardUrl;
                });
            } else {
                // Fallback
                setTimeout(function() {
                    window.location.href = dashboardUrl;
                }, 1000);
            }
        };
        <?php endif; ?>
    </script>

</body>
</html>