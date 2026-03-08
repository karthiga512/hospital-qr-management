<?php
session_start();
require_once '../config.php';

// Check if user is logged in
if (!isset($_SESSION['patient_id'])) {
    header("Location: index.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];
$patient_name = $_SESSION['patient_name'];

// Fetch patient details
$stmt = $conn->prepare("SELECT * FROM patients WHERE id = ?");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$patient = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Fetch visits
$stmt = $conn->prepare("
    SELECT v.*, d.name as doctor_name 
    FROM visits v 
    JOIN doctors d ON v.doctor_id = d.id 
    WHERE v.patient_id = ? 
    ORDER BY v.visit_date DESC
");
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$visits = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        .timeline {
            position: relative;
            padding: 20px 0;
        }
        .timeline-item {
            position: relative;
            padding-left: 40px;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: -20px;
            width: 2px;
            background: #17a2b8;
        }
        .timeline-item:last-child::before {
            bottom: 0;
        }
        .timeline-badge {
            position: absolute;
            left: -6px;
            top: 0;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: #17a2b8;
            border: 2px solid #fff;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-info mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">🏥 Patient Portal</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3">Welcome, <?php echo htmlspecialchars($patient_name); ?></span>
                <a href="logout.php" class="btn btn-light btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row mb-4">
            <!-- Patient Profile Card -->
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100 border-info">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0">My Details</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Patient ID:</strong> #<?php echo str_pad($patient['id'], 5, '0', STR_PAD_LEFT); ?></p>
                        <p><strong>Name:</strong> <?php echo htmlspecialchars($patient['name']); ?></p>
                        <p><strong>DOB:</strong> <?php echo date('d M Y', strtotime($patient['dob'])); ?></p>
                        <p><strong>Phone:</strong> <?php echo htmlspecialchars($patient['phone']); ?></p>
                        <p><strong>Registered:</strong> <?php echo date('d M Y', strtotime($patient['created_at'])); ?></p>
                        <?php if($patient['last_visit']): ?>
                        <p class="mb-0"><strong>Last Visit:</strong> <?php echo date('d M Y h:i A', strtotime($patient['last_visit'])); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Medical History -->
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 text-info">Medical History & Prescriptions</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($visits->num_rows > 0): ?>
                            <div class="timeline">
                                <?php while ($visit = $visits->fetch_assoc()): ?>
                                    <div class="timeline-item">
                                        <div class="timeline-badge"></div>
                                        <div class="card border-0 bg-light">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                    <h6 class="text-info mb-0">
                                                        <?php echo date('d M Y, h:i A', strtotime($visit['visit_date'])); ?>
                                                    </h6>
                                                    <span class="badge bg-secondary">Dr. <?php echo htmlspecialchars($visit['doctor_name']); ?></span>
                                                </div>
                                                
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <strong class="text-muted">Problem:</strong>
                                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($visit['problem'])); ?></p>
                                                    </div>
                                                    
                                                    <div class="col-12">
                                                        <strong class="text-muted">Diagnosis:</strong>
                                                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($visit['diagnosis'])); ?></p>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="p-2 border rounded border-info bg-white h-100">
                                                            <strong class="text-info d-block mb-1">💊 Prescriptions (Tablets):</strong>
                                                            <?php echo nl2br(htmlspecialchars($visit['prescription'])); ?>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="p-2 border rounded bg-white h-100">
                                                            <strong class="d-block mb-1">Instructions/Treatment:</strong>
                                                            <?php echo nl2br(htmlspecialchars($visit['treatment'] ?? 'None')); ?>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="col-12 mt-2">
                                                        <span class="badge <?php echo $visit['medicine_status'] == 'given' ? 'bg-success' : 'bg-warning'; ?>">
                                                            Medicine Status: <?php echo ucfirst($visit['medicine_status']); ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info mb-0">
                                No past visits or medical history found.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
