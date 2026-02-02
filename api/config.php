<?php
/**
 * File Konfigurasi Database - Optimized for Vercel & TiDB Cloud
 */

// 1. Initialize MySQLi
$conn = mysqli_init();

// 2. Mandatory: Enable SSL for TiDB Cloud Serverless
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);

// 3. Database Credentials from Vercel Environment Variables
// Ensure these keys match exactly what you typed in Vercel Settings
$host = getenv('TIDB_HOST');     // e.g., gateway01.us-west-2.prod.aws.tidbcloud.com
$user = getenv('TIDB_USER');     // e.g., xxxxxxx.root
$pass = getenv('TIDB_PASSWORD'); // Your generated TiDB password
$db   = getenv('TIDB_DATABASE'); // website_ai
$port = 4000;                    // TiDB default port

// 4. Establish Connection (Forces TCP/IP via Port 4000)
try {
    $success = mysqli_real_connect(
        $conn, 
        $host, 
        $user, 
        $pass, 
        $db, 
        $port, 
        NULL, 
        MYSQLI_CLIENT_SSL
    );

    if (!$success) {
        throw new Exception("Koneksi gagal: " . mysqli_connect_error());
    }

    // Set charset to support modern characters
    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    die("Error koneksi database: " . $e->getMessage());
}

/**
 * Utility Functions
 */
function clean_input($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

function redirect($url) {
    header("Location: " . $url);
    exit();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>