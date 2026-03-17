<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Access credentials required.";
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
                $error = "Invalid credentials.";
            }
        } else {
            $error = "Account not found.";
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
    <title>Login | RetailPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #343a40;
            font-family: 'Inter', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow: hidden;
        }
        .login-container {
            display: flex;
            width: 850px;
            height: 500px;
            background: #fff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.5);
        }
        .login-info {
            flex: 1;
            background: #20c997;
            background: linear-gradient(135deg, #20c997 0%, #17a2b8 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #fff;
            padding: 40px;
            text-align: center;
        }
        .login-form-container {
            flex: 1.2;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .btn-login {
            background: #20c997;
            border: none;
            color: #fff;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background: #1ba87e;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-info">
        <i class="fas fa-shopping-cart fa-5x mb-4"></i>
        <h1 class="fw-bolder">RetailPro</h1>
        <p class="opacity-75">Next-Gen Billing & Inventory Command Center</p>
    </div>
    <div class="login-form-container">
        <h3 class="fw-bold mb-4">Sign In</h3>
        <?php if($error): ?>
            <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="" method="post">
            <div class="mb-3">
                <label class="form-label small fw-bold text-muted">ADMIN USERNAME</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-teal"></i></span>
                    <input type="text" name="username" class="form-control border-start-0" placeholder="admin" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold text-muted">SECURITY TOKEN</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-teal"></i></span>
                    <input type="password" name="password" class="form-control border-start-0" placeholder="admin123" required>
                </div>
            </div>
            <button type="submit" class="btn btn-login w-100 shadow-sm">INITIATE OPERATIONS</button>
        </form>
    </div>
</div>

</body>
</html>
