<?php
$hostname = 'localhost';
$username = 'root';
$password = ''; // Kosongkan jika pakai XAMPP default
$dbname   = 'gdpartstudio';

$conn = mysqli_connect($hostname, $username, $password, $dbname);

if (!$conn) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
?>