<?php
session_start();
require_once '../config.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['patient_id'])) {
    header("Location: dashboard.php");
    exit();
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $patient_id = trim($_POST['patient_id']);
    $phone = trim($_POST['phone']);

    if (empty($patient_id) || empty($phone)) {
        $error = "Please enter both Patient ID and Phone Number.";
    } else {
        // Find patient in database
        $stmt = $conn->prepare("SELECT id, name, phone FROM patients WHERE id = ? AND phone = ?");
        $stmt->bind_param("is", $patient_id, $phone);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            
            // Set session variables
            $_SESSION['patient_id'] = $row['id'];
            $_SESSION['patient_name'] = $row['name'];
            
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Patient ID or Phone Number.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Portal Login - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card fade-in shadow">
                    <div class="card-header text-center bg-info text-white">
                        <h3 class="mb-0">👤 Patient Portal</h3>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="patient_id" class="form-label">Patient ID</label>
                                <input type="number" class="form-control" id="patient_id" name="patient_id" required 
                                       placeholder="Enter your Patient ID">
                            </div>
                            <div class="mb-4">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required 
                                       pattern="[0-9]{10}" placeholder="Enter 10-digit phone number">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-info text-white">Login</button>
                                <a href="../index.php" class="btn btn-outline-secondary">Back to Home</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
