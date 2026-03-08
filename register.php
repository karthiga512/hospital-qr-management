<?php
/**
 * Patient Registration Page
 * Allows new patients to register in the system
 */
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card fade-in">
                        <div class="card-header text-center">
                            <h3>👩‍💼 Receptionist - Register Patient</h3>
                        </div>
                        <div class="card-body">
                            <form action="generate_qr.php" method="POST" id="registrationForm">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" id="name" name="name" required 
                                           pattern="[A-Za-z\s]+" title="Only letters and spaces allowed"
                                           placeholder="Enter patient's full name">
                                </div>

                                <div class="mb-3">
                                    <label for="dob" class="form-label">Date of Birth *</label>
                                    <input type="date" class="form-control" id="dob" name="dob" required 
                                           max="<?php echo date('Y-m-d'); ?>">
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" required 
                                           pattern="[0-9]{10}" title="Enter 10 digit phone number"
                                           placeholder="Enter 10 digit phone number">
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        Register & Generate QR Code
                                    </button>
                                    <a href="index.php" class="btn btn-outline-secondary">
                                        Back to Home
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Form validation
        document.getElementById('registrationForm').addEventListener('submit', function(e) {
            const phone = document.getElementById('phone').value;
            if (phone.length !== 10 || !/^\d+$/.test(phone)) {
                e.preventDefault();
                alert('Please enter a valid 10-digit phone number');
                return false;
            }
        });
    </script>
</body>
</html>
