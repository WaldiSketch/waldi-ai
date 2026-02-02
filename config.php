<?php
/**
 * File Konfigurasi Database
 * Website AI Management System
 */

// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'website_ai');

// Koneksi ke Database
try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Cek koneksi
    if ($conn->connect_error) {
        die("Koneksi database gagal: " . $conn->connect_error);
    }
    
    // Set charset UTF8
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    die("Error koneksi database: " . $e->getMessage());
}

/**
 * Fungsi untuk membersihkan input dari user
 * Mencegah SQL Injection dan XSS
 */
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

/**
 * Fungsi untuk redirect
 */
function redirect($url) {
    header("Location: " . $url);
    exit();
}

// Memulai session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
