<?php
// pages/admin/log_helper.php

// Mencegah fungsi dideklarasikan ganda
if (!function_exists('writeLog')) {
    
    function writeLog($conn, $admin_id, $action, $target, $detail) {
        
        // 1. Cek Koneksi
        if (!$conn) {
            die("Log Error: Koneksi Database Terputus.");
        }

        // 2. Deteksi IP & User Agent
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = 'Unknown';
        $device  = 'Unknown';

        // Deteksi OS
        if (preg_match('/linux/i', $u_agent)) { $device = 'Linux'; }
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) { $device = 'macOS'; }
        elseif (preg_match('/windows|win32/i', $u_agent)) { $device = 'Windows'; }
        elseif (preg_match('/android/i', $u_agent)) { $device = 'Android'; }
        elseif (preg_match('/iphone/i', $u_agent)) { $device = 'iPhone'; }

        // Deteksi Browser
        if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) { $browser = 'Internet Explorer'; }
        elseif (preg_match('/Firefox/i', $u_agent)) { $browser = 'Firefox'; }
        elseif (preg_match('/Chrome/i', $u_agent)) { $browser = 'Chrome'; }
        elseif (preg_match('/Safari/i', $u_agent)) { $browser = 'Safari'; }
        elseif (preg_match('/Opera/i', $u_agent) || preg_match('/OPR/i', $u_agent)) { $browser = 'Opera'; }

        // 3. Sanitasi Data (PENTING AGAR TIDAK ERROR SYNTAX)
        $admin_id_safe = intval($admin_id); // Pastikan angka
        $action_safe   = mysqli_real_escape_string($conn, $action);
        $target_safe   = mysqli_real_escape_string($conn, $target);
        $detail_safe   = mysqli_real_escape_string($conn, $detail);
        $device_safe   = mysqli_real_escape_string($conn, $device);
        $browser_safe  = mysqli_real_escape_string($conn, $browser);
        $ip_safe       = mysqli_real_escape_string($conn, $ip_address);

        // 4. Query Insert (Menggunakan backtick ` untuk nama kolom)
        $sql = "INSERT INTO `activity_logs` 
                (`admin_id`, `action_type`, `target_name`, `detail`, `device`, `browser`, `ip_address`) 
                VALUES 
                ('$admin_id_safe', '$action_safe', '$target_safe', '$detail_safe', '$device_safe', '$browser_safe', '$ip_safe')";
        
        // 5. Eksekusi
        if (!mysqli_query($conn, $sql)) {
            // Tampilkan error detail jika gagal (untuk debugging)
            die("GAGAL MENYIMPAN LOG: " . mysqli_error($conn) . "<br>Query: " . $sql);
        }
    }

}
?>