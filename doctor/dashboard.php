<?php
/**
 * Doctor Dashboard
 * Scan QR, view patient history, add visits
 */
require_once '../config.php';
require_login('doctor', 'index.php');

$doctor_id = $_SESSION['doctor_id'];
$doctor_name = $_SESSION['doctor_name'];

// Get patient details if patient_id is provided
$patient = null;
$visits = [];
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($patient_id > 0) {
    // Fetch patient details
    $sql = "SELECT * FROM patients WHERE id = $patient_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $patient = mysqli_fetch_assoc($result);

        // Fetch visit history
        $visit_sql = "SELECT v.*, d.name as doctor_name 
                      FROM visits v 
                      JOIN doctors d ON v.doctor_id = d.id 
                      WHERE v.patient_id = $patient_id 
                      ORDER BY v.visit_date DESC";
        $visit_result = mysqli_query($conn, $visit_sql);

        while ($row = mysqli_fetch_assoc($visit_result)) {
            $visits[] = $row;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">⚕️ Doctor Dashboard</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Welcome, <strong><?php echo $doctor_name; ?></strong></span>
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
                            <div id="qr-result" class="alert alert-info mt-3" style="display: none;"></div>
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
                                    <button type="submit" class="btn btn-primary w-100">Search Patient</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php
else: ?>
            <!-- Patient Details & History -->
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
                                    <th>DOB:</th>
                                    <td><?php echo date('d-M-Y', strtotime($patient['dob'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Age:</th>
                                    <td><?php
    $dob = new DateTime($patient['dob']);
    $now = new DateTime();
    echo $now->diff($dob)->y . ' years';
?></td>
                                </tr>
                                <tr>
                                    <th>Phone:</th>
                                    <td><?php echo $patient['phone']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total Visits:</th>
                                    <td><span class="badge bg-primary"><?php echo count($visits); ?></span></td>
                                </tr>
                            </table>
                            <a href="dashboard.php" class="btn btn-secondary btn-sm w-100">
                                ← Back to Scanner
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <!-- Add New Visit Form -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>📝 Add New Visit</h5>
                        </div>
                        <div class="card-body">
                            <form action="save_visit.php" method="POST">
                                <input type="hidden" name="patient_id" value="<?php echo $patient_id; ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Problem / Complaint *</label>
                                    <textarea class="form-control" name="problem" rows="2" 
                                              required placeholder="Patient's complaint"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Diagnosis *</label>
                                    <textarea class="form-control" name="diagnosis" rows="2" 
                                              required placeholder="Your diagnosis"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prescription / Medicine *</label>
                                    <textarea class="form-control" name="prescription" rows="3" 
                                              required placeholder="Medicine details, dosage, duration"></textarea>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Treatment Notes</label>
                                    <textarea class="form-control" name="treatment" rows="2" 
                                              placeholder="Additional treatment instructions (optional)"></textarea>
                                </div>

                                <button type="submit" class="btn btn-success w-100">
                                    💾 Save Visit
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Visit History -->
                    <div class="card">
                        <div class="card-header">
                            <h5>📋 Visit History</h5>
                        </div>
                        <div class="card-body">
                            <?php if (count($visits) === 0): ?>
                                <div class="alert alert-info">No previous visits recorded.</div>
                            <?php
    else: ?>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Doctor</th>
                                                <th>Problem</th>
                                                <th>Diagnosis</th>
                                                <th>Prescription</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($visits as $visit): ?>
                                                <tr>
                                                    <td><?php echo date('d-M-Y H:i', strtotime($visit['visit_date'])); ?></td>
                                                    <td><?php echo $visit['doctor_name']; ?></td>
                                                    <td><?php echo substr($visit['problem'], 0, 30) . '...'; ?></td>
                                                    <td><?php echo substr($visit['diagnosis'], 0, 30) . '...'; ?></td>
                                                    <td><?php echo substr($visit['prescription'], 0, 30) . '...'; ?></td>
                                                    <td>
                                                        <?php if ($visit['medicine_status'] === 'given'): ?>
                                                            <span class="badge bg-success">Given</span>
                                                        <?php
            else: ?>
                                                            <span class="badge bg-warning">Pending</span>
                                                        <?php
            endif; ?>
                                                    </td>
                                                </tr>
                                            <?php
        endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php
    endif; ?>
                        </div>
                    </div>
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
            // Stop scanning
            html5QrcodeScanner.clear();
            
            // Extract patient ID from QR code
            let patientId = decodedText;
            if (decodedText.includes('PATIENT_ID:')) {
                patientId = decodedText.split('PATIENT_ID:')[1];
            }
            
            // Redirect to patient page
            window.location.href = 'dashboard.php?patient_id=' + patientId;
        }

        function onScanFailure(error) {
            // Handle scan failure, usually not necessary to do anything
        }

        let html5QrcodeScanner = new Html5QrcodeScanner(
            "reader",
            { fps: 10, qrbox: {width: 250, height: 250} },
            /* verbose= */ false
        );
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        <?php
endif; ?>
    </script>
</body>
</html>
