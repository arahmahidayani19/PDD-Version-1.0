<?php
// Mulai sesi
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    // Jika tidak login, arahkan ke halaman login
    header('Location: ../login/login.php');
    exit; // Pastikan tidak ada kode lain yang dijalankan setelah redirect
}
?>