<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Operator credentials required.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $db_password);
            $stmt->fetch();
            
            if ($password === $db_password || password_verify($password, $db_password)) {
                $_SESSION['admin'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Unauthorized: Invalid Access Code.";
            }
        } else {
            $error = "Operator not registered in system.";
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
    <title>Login | FleetManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #212529;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(0,0,0,0.7), rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.5);
            width: 100%;
            max-width: 400px;
            border-top: 8px solid #ffc107;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <i class="fas fa-truck-pickup fa-3x text-yellow mb-2"></i>
        <h3 class="fw-bold">FleetManager</h3>
        <p class="text-muted">Transport Admin Login</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label fw-bold">Operator ID</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Access Code</label>
            <input type="password" name="password" class="form-control" placeholder="admin123" required>
        </div>
        <button type="submit" class="btn btn-yellow w-100 btn-lg shadow">Authorize Entry</button>
    </form>
</div>

</body>
</html>
