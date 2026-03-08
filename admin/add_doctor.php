<?php
/**
 * Add New Doctor
 */
require_once '../config.php';
require_login('admin', 'index.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

$name = sanitize($_POST['name']);
$username = sanitize($_POST['username']);
$password = $_POST['password']; // Plain text password

// Check if username already exists using prepared statement
$check_stmt = $conn->prepare("SELECT * FROM doctors WHERE username = ?");
$check_stmt->bind_param("s", $username);
$check_stmt->execute();
$check_result = $check_stmt->get_result();

if ($check_result->num_rows > 0) {
    $check_stmt->close();
    die("Username already exists!");
}
$check_stmt->close();

// Insert new doctor using prepared statement
$insert_stmt = $conn->prepare("INSERT INTO doctors (name, username, password) VALUES (?, ?, ?)");
$insert_stmt->bind_param("sss", $name, $username, $password);

if ($insert_stmt->execute()) {
    $insert_stmt->close();
    header("Location: dashboard.php?success=doctor_added");
}
else {
    die("Error: " . $conn->error);
}

mysqli_close($conn);
?>
