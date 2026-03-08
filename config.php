<?php
/**
 * Configuration File
 * Database connection and global settings
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smart_op');

// Base URL Configuration (Update this according to your setup)
define('BASE_URL', 'http://localhost/QR/');

// Create database connection
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to UTF-8
mysqli_set_charset($conn, "utf8mb4");

/**
 * Sanitize user input to prevent SQL injection
 * @param string $data
 * @return string
 */
function sanitize($data) {
    global $conn;
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = mysqli_real_escape_string($conn, $data);
    return $data;
}

/**
 * Check if user is logged in
 * @param string $role (doctor, admin, pharmacy)
 * @return bool
 */
function is_logged_in($role) {
    return isset($_SESSION[$role . '_logged_in']) && $_SESSION[$role . '_logged_in'] === true;
}

/**
 * Redirect to login if not authenticated
 * @param string $role
 * @param string $login_page
 */
function require_login($role, $login_page) {
    if (!is_logged_in($role)) {
        header("Location: " . $login_page);
        exit();
    }
}
?>
