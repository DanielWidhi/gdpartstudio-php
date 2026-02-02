<?php
if (!function_exists('writeLog')) {
    
    function writeLog($conn, $admin_id, $action, $target, $detail) {
        
        // Cek Koneksi
        if (!$conn) { die("Log Error: DB Connection Lost."); }

        // Deteksi IP & User Agent
        $ip_address = $_SERVER['REMOTE_ADDR'];
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        
        // Deteksi Device & Browser (Sederhana)
        $browser = 'Unknown'; $device = 'Unknown';
        if (preg_match('/linux/i', $u_agent)) $device = 'Linux';
        elseif (preg_match('/macintosh|mac os x/i', $u_agent)) $device = 'macOS';
        elseif (preg_match('/windows|win32/i', $u_agent)) $device = 'Windows';
        elseif (preg_match('/android/i', $u_agent)) $device = 'Android';
        elseif (preg_match('/iphone/i', $u_agent)) $device = 'iPhone';

        if (preg_match('/Chrome/i', $u_agent)) $browser = 'Chrome';
        elseif (preg_match('/Firefox/i', $u_agent)) $browser = 'Firefox';
        elseif (preg_match('/Safari/i', $u_agent)) $browser = 'Safari';

        // Sanitasi
        $action_safe   = mysqli_real_escape_string($conn, $action);
        $target_safe   = mysqli_real_escape_string($conn, $target);
        $detail_safe   = mysqli_real_escape_string($conn, $detail);
        $device_safe   = mysqli_real_escape_string($conn, $device);
        $browser_safe  = mysqli_real_escape_string($conn, $browser);
        $ip_safe       = mysqli_real_escape_string($conn, $ip_address);

        // LOGIKA BARU: Handle Admin ID
        if ($admin_id > 0) {
            $id_val = intval($admin_id);
        } else {
            $id_val = "NULL"; // Masukkan sebagai SQL NULL
        }

        // Query Insert
        $sql = "INSERT INTO `activity_logs` 
                (`admin_id`, `action_type`, `target_name`, `detail`, `device`, `browser`, `ip_address`) 
                VALUES 
                ($id_val, '$action_safe', '$target_safe', '$detail_safe', '$device_safe', '$browser_safe', '$ip_safe')";
        
        mysqli_query($conn, $sql);
    }
}
?>