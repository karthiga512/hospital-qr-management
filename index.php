<?php
/**
 * Smart QR Based OP & Prescription Management System
 * Main Landing Page
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart OP System - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card fade-in">
                        <div class="card-header text-center">
                            <h2>🏥 Smart QR Based OP & Prescription Management System</h2>
                        </div>
                        <div class="card-body">
                            <p class="text-center text-muted mb-4">
                                A comprehensive solution for managing patient visits, prescriptions, and medical records using QR technology.
                            </p>
                            
                            <div class="row justify-content-center mt-4">
                                <div class="col-md-8">
                                    <div class="card shadow-sm border-0 bg-light">
                                        <div class="card-body text-center p-4">
                                            <div class="mb-3" style="font-size: 3.5rem;">🔐</div>
                                            <h4 class="mb-3">System Login</h4>
                                            <p class="text-muted mb-4">Select your role to access the portal</p>
                                            
                                            <div class="form-group mb-4">
                                                <select id="loginRole" class="form-select form-select-lg text-center shadow-sm" style="cursor: pointer; padding-left: 0.5rem;" onchange="updateButtonText()">
                                                    <option value="">-- Choose Your Portal --</option>
                                                    <option value="patient/index.php">👤 Patient Portal</option>
                                                    <option value="doctor/index.php">⚕️ Doctor Portal</option>
                                                    <option value="pharmacy/index.php">💊 Pharmacy Portal</option>
                                                    <option value="admin/index.php">⚙️ Admin Portal</option>
                                                    <option value="register.php">👩‍💼 Receptionist (Register Patient)</option>
                                                </select>
                                            </div>
                                            
                                            <button id="proceedBtn" onclick="proceedToLogin()" class="btn btn-primary btn-lg w-100 shadow-sm">
                                                Proceed to Login
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateButtonText() {
            var roleUrl = document.getElementById('loginRole').value;
            var btn = document.getElementById('proceedBtn');
            if (roleUrl === 'register.php') {
                btn.innerText = 'Proceed to Register';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-success');
            } else {
                btn.innerText = 'Proceed to Login';
                btn.classList.add('btn-primary');
                btn.classList.remove('btn-success');
            }
        }

        function proceedToLogin() {
            var roleUrl = document.getElementById('loginRole').value;
            if (roleUrl) {
                window.location.href = roleUrl;
            } else {
                alert('Please select a portal to proceed.');
            }
        }
    </script>
</body>
</html>
