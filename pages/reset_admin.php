<?php
// pages/reset_admin.php

// Hubungkan ke database (naik satu level ke root)
include '../db.php';

// Password baru yang ingin diset
$password_baru = "admin123";

// Enkripsi password menggunakan algoritma server Anda saat ini
$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
$email = "admin@gdpartstudio.com";

// Update database
$query = "UPDATE admins SET password = '$password_hash' WHERE email = '$email'";

if (mysqli_query($conn, $query)) {
    echo "<h1>BERHASIL! ✅</h1>";
    echo "<p>Password untuk <b>$email</b> berhasil di-reset.</p>";
    echo "<p>Password baru: <b>$password_baru</b></p>";
    echo "<p>Hash baru di database: $password_hash</p>";
    echo "<br><a href='login.php'>Klik disini untuk Login</a>";
} else {
    echo "<h1>GAGAL ❌</h1>";
    echo "Error: " . mysqli_error($conn);
}
?>