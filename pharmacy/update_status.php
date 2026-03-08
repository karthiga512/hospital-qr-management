<?php
/**
 * Update Medicine Status
 * Mark medicines as given/dispensed
 */
require_once '../config.php';
require_login('pharmacy', 'index.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: dashboard.php");
    exit();
}

$visit_id = intval($_POST['visit_id']);
$patient_id = intval($_POST['patient_id']);

// Update medicine status
$sql = "UPDATE visits SET medicine_status = 'given' WHERE visit_id = $visit_id";

if (mysqli_query($conn, $sql)) {
    header("Location: dashboard.php?patient_id=$patient_id&medicine_given=1");
}
else {
    die("Error: " . mysqli_error($conn));
}

mysqli_close($conn);
?>
