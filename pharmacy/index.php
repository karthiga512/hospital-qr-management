<?php
/**
 * Pharmacy Login Page
 */
require_once '../config.php';

// If already logged in, redirect to dashboard
if (is_logged_in('pharmacy')) {
    header("Location: dashboard.php");
    exit();
}

// Process login
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitize($_POST['username']);
    $password = $_POST['password'];

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM pharmacy WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $pharmacy = $result->fetch_assoc();
        $_SESSION['pharmacy_logged_in'] = true;
        $_SESSION['pharmacy_id'] = $pharmacy['id'];
        $_SESSION['pharmacy_name'] = $pharmacy['name'];
        header("Location: dashboard.php");
        exit();
    }
    else {
        $error = "Invalid username or password!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pharmacy Login - Smart OP System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div class="main-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-5 login-container">
                    <div class="card fade-in">
                        <div class="card-header text-center">
                            <h3>💊 Pharmacy Login</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($error): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php
endif; ?>

                            <form action="" method="POST">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input type="text" class="form-control" id="username" 
                                           name="username" required autofocus>
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="password" 
                                           name="password" required>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-success">Login</button>
                                    <a href="../index.php" class="btn btn-outline-secondary">Back to Home</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
