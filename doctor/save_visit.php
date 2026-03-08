<?php
/**
 * Save Visit Details
 * Backend script to save doctor's visit record
 */
require_once '../config.php';
require_login('doctor', 'index.php');

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

$patient_id = intval($_POST['patient_id']);
$doctor_id = $_SESSION['doctor_id'];
$problem = sanitize($_POST['problem']);
$diagnosis = sanitize($_POST['diagnosis']);
$prescription = sanitize($_POST['prescription']);
$treatment = sanitize($_POST['treatment']);

// Validate inputs
if (empty($problem) || empty($diagnosis) || empty($prescription)) {
    die("All required fields must be filled!");
}

// Insert visit into database
$sql = "INSERT INTO visits (patient_id, doctor_id, problem, diagnosis, prescription, treatment) 
        VALUES ($patient_id, $doctor_id, '$problem', '$diagnosis', '$prescription', '$treatment')";

if (mysqli_query($conn, $sql)) {
    // Update patient's last_visit timestamp
    $update_sql = "UPDATE patients SET last_visit = NOW() WHERE id = $patient_id";
    mysqli_query($conn, $update_sql);

    // Redirect back to patient page with success message
    header("Location: dashboard.php?patient_id=$patient_id&success=1");
}
else {
    die("Error: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
