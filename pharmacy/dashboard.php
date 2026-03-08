<?php
/**
 * Pharmacy Dashboard
 * View latest prescription and mark medicines as given
 */
require_once '../config.php';
require_login('pharmacy', 'index.php');

// Get patient details if patient_id is provided
$patient = null;
$latest_visit = null;
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($patient_id > 0) {
    // Fetch patient details
    $sql = "SELECT * FROM patients WHERE id = $patient_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $patient = mysqli_fetch_assoc($result);

        // Fetch latest visit (prescription)
        $visit_sql = "SELECT v.*, d.name as doctor_name 
                      FROM visits v 
                      JOIN doctors d ON v.doctor_id = d.id 
                      WHERE v.patient_id = $patient_id 
                      ORDER BY v.visit_date DESC 
                      LIMIT 1";
        $visit_result = mysqli_query($conn, $visit_sql);

        if (mysqli_num_rows($visit_result) === 1) {
            $latest_visit = mysqli_fetch_assoc($visit_result);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Dashboard - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">💊 Pharmacy Dashboard</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Welcome, <strong>Pharmacy Staff</strong></span>
                <a href="../index.php" class="btn btn-sm btn-secondary me-2">🏠 Home</a>
                <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 dashboard-container">
        <?php if (!$patient): ?>
            <!-- Scanner Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>📷 Scan Patient QR Code</h5>
                        </div>
                        <div class="card-body">
                            <div id="reader"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Manual Entry Section -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>🔍 Or Enter Patient ID Manually</h5>
                        </div>
                        <div class="card-body">
                            <form action="" method="GET" class="row g-3">
                                <div class="col-md-8">
                                    <input type="number" class="form-control" name="patient_id" 
                                           placeholder="Enter Patient ID" required min="1">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="btn btn-success w-100">View Prescription</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
else: ?>
            <!-- Patient & Prescription Details -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>👤 Patient Details</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th>Patient ID:</th>
                                    <td><strong><?php echo $patient['id']; ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Name:</th>
                                    <td><?php echo $patient['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo $patient['phone']; ?></td>
                                </tr>
                            </table>
                            <a href="dashboard.php" class="btn btn-secondary btn-sm w-100">
                                ← Back to Scanner
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <?php if ($latest_visit): ?>
                        <!-- Latest Prescription -->
                        <div class="card mb-4">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5>📋 Latest Prescription</h5>
                                <?php if ($latest_visit['medicine_status'] === 'pending'): ?>
                                    <span class="badge bg-warning">Medicines Pending</span>
                                <?php
        else: ?>
                                    <span class="badge bg-success">Medicines Given</span>
                                <?php
        endif; ?>
                            </div>
                            <div class="card-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <strong>Visit Date:</strong>
                                        <p><?php echo date('d-M-Y H:i', strtotime($latest_visit['visit_date'])); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Doctor:</strong>
                                        <p><?php echo $latest_visit['doctor_name']; ?></p>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <strong>Problem:</strong>
                                    <p class="alert alert-light"><?php echo nl2br($latest_visit['problem']); ?></p>
                                </div>

                                <div class="mb-3">
                                    <strong>Diagnosis:</strong>
                                    <p class="alert alert-light"><?php echo nl2br($latest_visit['diagnosis']); ?></p>
                                </div>

                                <div class="mb-3">
                                    <strong>💊 Prescription:</strong>
                                    <div class="alert alert-success">
                                        <?php echo nl2br($latest_visit['prescription']); ?>
                                    </div>
                                </div>

                                <?php if (!empty($latest_visit['treatment'])): ?>
                                    <div class="mb-3">
                                        <strong>Treatment Notes:</strong>
                                        <p class="alert alert-light"><?php echo nl2br($latest_visit['treatment']); ?></p>
                                    </div>
                                <?php
        endif; ?>

                                <?php if ($latest_visit['medicine_status'] === 'pending'): ?>
                                    <form action="update_status.php" method="POST">
                                        <input type="hidden" name="visit_id" value="<?php echo $latest_visit['visit_id']; ?>">
                                        <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                                        <button type="submit" class="btn btn-success btn-lg w-100">
                                            ✅ Mark Medicines as Given
                                        </button>
                                    </form>
                                <?php
        else: ?>
                                    <div class="alert alert-success mb-0">
                                        ✅ Medicines have been dispensed for this visit.
                                    </div>
                                <?php
        endif; ?>
                            </div>
                        </div>
                    <?php
    else: ?>
                        <div class="alert alert-warning">
                            <strong>No Prescription Found!</strong><br>
                            This patient has no prescription records yet.
                        </div>
                    <?php
    endif; ?>
                </div>
            </div>
        <?php
endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        <?php if (!$patient): ?>
        // Initialize QR Scanner
        function onScanSuccess(decodedText, decodedResult) {
            html5QrcodeScanner.clear();
            
            let patientId = decodedText;
            if (decodedText.includes('PATIENT_ID:')) {
                patientId = decodedText.split('PATIENT_ID:')[1];
            }
            
            window.location.href = 'dashboard.php?patient_id=' + patientId;
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            false
        );
        html5QrcodeScanner.render(onScanSuccess);
        <?php
endif; ?>
    </script>
</body>
</html>
