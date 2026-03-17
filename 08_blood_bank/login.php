<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Credentials required for node entry.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $db_password);
            $stmt->fetch();
            
            if ($password === $db_password) {
                $_SESSION['admin'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Unauthorized access attempt.";
            }
        } else {
            $error = "Admin node not identified.";
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
    <title>Login | BloodLife</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #dc3545;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(220, 53, 69, 0.9), rgba(220, 53, 69, 0.9)), url('https://images.unsplash.com/photo-1579154235602-4c070f3f27a4?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
        }
        .login-card {
            background: #fff;
            padding: 2.5rem;
            border-radius: 12px;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.2);
            width: 100%;
            max-width: 400px;
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <i class="fas fa-heartbeat fa-4x text-danger mb-4"></i>
    <h2 class="fw-bold mb-1">BloodLife</h2>
    <p class="text-muted mb-4">Centralized Donor Command</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post" class="text-start">
        <div class="mb-3">
            <label class="form-label fw-bold">Admin Identifier</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Access Token</label>
            <input type="password" name="password" class="form-control" placeholder="admin123" required>
        </div>
        <button type="submit" class="btn btn-danger w-100 btn-lg fw-bold">INITIATE SESSION</button>
    </form>
</div>

</body>
</html>
