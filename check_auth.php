<?php
/**
 * File untuk mengecek autentikasi admin
 * File ini harus di-include di setiap halaman admin
 */

require_once '../config.php';

// Cek apakah admin sudah login
if (!isset($_SESSION['admin_id']) || empty($_SESSION['admin_id'])) {
    // Jika belum login, redirect ke halaman login
    redirect('../login.php');
    exit();
}

// Cek timeout session (opsional - 2 jam)
$timeout_duration = 7200; // 2 jam dalam detik
if (isset($_SESSION['login_time']) && (time() - $_SESSION['login_time']) > $timeout_duration) {
    // Session timeout
    session_unset();
    session_destroy();
    redirect('../login.php?timeout=1');
    exit();
}

// Update waktu aktivitas terakhir
$_SESSION['login_time'] = time();
?>
