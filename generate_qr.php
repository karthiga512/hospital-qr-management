<?php
/**
 * Generate QR Code for Patient
 * Processes registration and creates QR code
 */
require_once 'config.php';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit();
}

// Sanitize and validate input
$name = sanitize($_POST['name']);
$dob = sanitize($_POST['dob']);
$phone = sanitize($_POST['phone']);

// Validate inputs
if (empty($name) || empty($dob) || empty($phone)) {
    die("All fields are required!");
}

// Validate phone number
if (!preg_match('/^[0-9]{10}$/', $phone)) {
    die("Invalid phone number format!");
}

// Check if patient already exists
$check_sql = "SELECT * FROM patients WHERE phone = '$phone'";
$check_result = mysqli_query($conn, $check_sql);

if (mysqli_num_rows($check_result) > 0) {
    $existing_patient = mysqli_fetch_assoc($check_result);
    $patient_id = $existing_patient['id'];
    $qr_path = $existing_patient['qr_code'];
    $is_existing = true;
}
else {
    // Insert patient into database
    $sql = "INSERT INTO patients (name, dob, phone) VALUES ('$name', '$dob', '$phone')";

    if (!mysqli_query($conn, $sql)) {
        die("Error: " . mysqli_error($conn));
    }

    // Get the patient ID
    $patient_id = mysqli_insert_id($conn);

    // Create qrcodes directory if it doesn't exist
    $qr_dir = 'qrcodes/';
    if (!file_exists($qr_dir)) {
        mkdir($qr_dir, 0777, true);
    }

    // Generate QR code using external API (fallback method)
    // QR contains: Patient ID
    $qr_data = "PATIENT_ID:" . $patient_id;
    $qr_filename = 'patient_' . $patient_id . '.png';
    $qr_path = $qr_dir . $qr_filename;

    // Method 1: Using Google Chart API (Simple and fast)
    $qr_api_url = 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($qr_data);

    // Download QR code image
    $qr_image = file_get_contents($qr_api_url);
    file_put_contents($qr_path, $qr_image);

    // Update patient record with QR code path
    $update_sql = "UPDATE patients SET qr_code = '$qr_path' WHERE id = $patient_id";
    mysqli_query($conn, $update_sql);

    $is_existing = false;
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card fade-in">
                        <div class="card-header text-center">
                            <h3>✅ <?php echo $is_existing ? 'Patient Already Registered' : 'Registration Successful'; ?></h3>
                        </div>
                        <div class="card-body">
                            <?php if ($is_existing): ?>
                                <div class="alert alert-warning">
                                    <strong>Notice:</strong> A patient with this phone number already exists in our system.
                                </div>
                            <?php
else: ?>
                                <div class="alert alert-success">
                                    <strong>Success!</strong> Patient registered successfully.
                                </div>
                            <?php
endif; ?>

                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h5>Patient Details:</h5>
                                    <table class="table table-bordered">
                                        <tr>
                                            <th>Patient ID:</th>
                                            <td><strong><?php echo $patient_id; ?></strong></td>
                                        </tr>
                                        <tr>
                                            <th>Name:</th>
                                            <td><?php echo $name; ?></td>
                                        </tr>
                                        <tr>
                                            <th>Date of Birth:</th>
                                            <td><?php echo date('d-M-Y', strtotime($dob)); ?></td>
                                        </tr>
                                        <tr>
                                            <th>Phone:</th>
                                            <td><?php echo $phone; ?></td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <div class="qr-container">
                                        <h5 class="mb-3">Your QR Code</h5>
                                        <img src="<?php echo $qr_path; ?>" alt="Patient QR Code" class="img-fluid">
                                        <p class="mt-3 text-muted">
                                            <small>Save or print this QR code for future visits</small>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="alert alert-info">
                                <strong>📱 Important:</strong> Please save this QR code or Patient ID. 
                                You'll need it for doctor consultations and pharmacy visits.
                            </div>

                            <div class="d-grid gap-2">
                                <button onclick="window.print()" class="btn btn-primary">
                                    🖨️ Print QR Code
                                </button>
                                <a href="register.php" class="btn btn-success">
                                    Register Another Patient
                                </a>
                                <a href="index.php" class="btn btn-outline-secondary">
                                    Back to Home
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .qr-container, .qr-container * {
                visibility: visible;
            }
            .qr-container {
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
            }
        }
    </style>
</body>
</html>
