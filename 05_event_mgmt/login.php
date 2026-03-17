<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Admin credentials required.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $db_password);
            $stmt->fetch();
            
            // Password verification
            if ($password === $db_password || password_verify($password, $db_password)) {
                $_SESSION['admin'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Security breach: Invalid Password.";
            }
        } else {
            $error = "Security breach: Admin not found.";
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
    <title>Login | Event Horizon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #212529;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: white;
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(233, 30, 99, 0.2);
            width: 100%;
            max-width: 450px;
            text-align: center;
        }
        .login-box h2 {
            font-weight: 700;
            color: #e91e63;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2><i class="fas fa-bolt me-2"></i>Event Horizon</h2>
    <p class="text-muted mb-4">Secure Admin Access</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-4 text-start">
            <label class="form-label fw-bold">Username</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label fw-bold">Password</label>
            <input type="password" name="password" class="form-control" placeholder="admin123" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-magenta btn-lg shadow">Unlock Portal</button>
        </div>
    </form>
</div>

</body>
</html>
