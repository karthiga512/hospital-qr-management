<?php
/**
 * Admin Dashboard
 * View statistics, manage doctors and patients
 */
require_once '../config.php';
require_login('admin', 'index.php');

// Get statistics
$total_patients_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM patients");
$total_patients = mysqli_fetch_assoc($total_patients_query)['count'];

$total_visits_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM visits");
$total_visits = mysqli_fetch_assoc($total_visits_query)['count'];

$total_doctors_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM doctors");
$total_doctors = mysqli_fetch_assoc($total_doctors_query)['count'];

$pending_medicines_query = mysqli_query($conn, "SELECT COUNT(*) as count FROM visits WHERE medicine_status = 'pending'");
$pending_medicines = mysqli_fetch_assoc($pending_medicines_query)['count'];

// Get all patients
$patients_query = mysqli_query($conn, "SELECT * FROM patients ORDER BY created_at DESC");
$patients = [];
while ($row = mysqli_fetch_assoc($patients_query)) {
    $patients[] = $row;
}

// Get all doctors
$doctors_query = mysqli_query($conn, "SELECT * FROM doctors ORDER BY created_at DESC");
$doctors = [];
while ($row = mysqli_fetch_assoc($doctors_query)) {
    $doctors[] = $row;
}

// Get all pharmacy users
$pharmacy_query = mysqli_query($conn, "SELECT * FROM pharmacy ORDER BY created_at DESC");
$pharmacy_users = [];
while ($row = mysqli_fetch_assoc($pharmacy_query)) {
    $pharmacy_users[] = $row;
}

// Handle delete patient (using prepared statement)
if (isset($_GET['delete_patient'])) {
    $patient_id = intval($_GET['delete_patient']);
    $stmt = $conn->prepare("DELETE FROM patients WHERE id = ?");
    $stmt->bind_param("i", $patient_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?deleted=patient");
    exit();
}

// Handle delete doctor (using prepared statement)
if (isset($_GET['delete_doctor'])) {
    $doctor_id = intval($_GET['delete_doctor']);
    $stmt = $conn->prepare("DELETE FROM doctors WHERE id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?deleted=doctor");
    exit();
}

// Handle delete pharmacy user (using prepared statement)
if (isset($_GET['delete_pharmacy'])) {
    $pharmacy_id = intval($_GET['delete_pharmacy']);
    $stmt = $conn->prepare("DELETE FROM pharmacy WHERE id = ?");
    $stmt->bind_param("i", $pharmacy_id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php?deleted=pharmacy");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">⚙️ Admin Dashboard</a>
            <div class="d-flex">
                <span class="navbar-text me-3">Welcome, <strong>Administrator</strong></span>
                <a href="../index.php" class="btn btn-sm btn-secondary me-2">🏠 Home</a>
                <a href="logout.php" class="btn btn-sm btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <?php if (isset($_GET['deleted'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <strong>Success!</strong> <?php echo ucfirst($_GET['deleted']); ?> deleted successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php
endif; ?>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <h3><?php echo $total_patients; ?></h3>
                    <p>Total Patients</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <h3><?php echo $total_visits; ?></h3>
                    <p>Total Visits</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <h3><?php echo $total_doctors; ?></h3>
                    <p>Total Doctors</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <h3><?php echo $pending_medicines; ?></h3>
                    <p>Pending Prescriptions</p>
                </div>
            </div>
        </div>

        <!-- Manage Doctors -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>👨‍⚕️ Manage Doctors</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                    + Add Doctor
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($doctors as $doctor): ?>
                                <tr>
                                    <td><?php echo $doctor['id']; ?></td>
                                    <td><?php echo $doctor['name']; ?></td>
                                    <td><?php echo $doctor['username']; ?></td>
                                    <td><?php echo date('d-M-Y', strtotime($doctor['created_at'])); ?></td>
                                    <td>
                                        <a href="?delete_doctor=<?php echo $doctor['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this doctor?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Manage Pharmacy Users -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5>💊 Manage Pharmacy Users</h5>
                <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#addPharmacyModal">
                    + Add Pharmacy User
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pharmacy_users as $pharmacy): ?>
                                <tr>
                                    <td><?php echo $pharmacy['id']; ?></td>
                                    <td><?php echo $pharmacy['name']; ?></td>
                                    <td><?php echo $pharmacy['username']; ?></td>
                                    <td><?php echo date('d-M-Y', strtotime($pharmacy['created_at'])); ?></td>
                                    <td>
                                        <a href="?delete_pharmacy=<?php echo $pharmacy['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure you want to delete this pharmacy user?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Manage Patients -->
        <div class="card mb-4">
            <div class="card-header">
                <h5>👥 Manage Patients</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>DOB</th>
                                <th>Phone</th>
                                <th>Registered On</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $patient): ?>
                                <tr>
                                    <td><?php echo $patient['id']; ?></td>
                                    <td><?php echo $patient['name']; ?></td>
                                    <td><?php echo date('d-M-Y', strtotime($patient['dob'])); ?></td>
                                    <td><?php echo $patient['phone']; ?></td>
                                    <td><?php echo date('d-M-Y', strtotime($patient['created_at'])); ?></td>
                                    <td>
                                        <a href="?delete_patient=<?php echo $patient['id']; ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Are you sure? This will delete all visit records too!')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php
endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Pharmacy User Modal -->
    <div class="modal fade" id="addPharmacyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_pharmacy.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Pharmacy User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Staff Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Add Pharmacy User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="add_doctor.php" method="POST">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Doctor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Doctor Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Doctor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
