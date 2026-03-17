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
                $error = "Unauthorized: Invalid Librarian Key.";
            }
        } else {
            $error = "Librarian not found in registry.";
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
    <title>Login | ScholarLib</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #0a2342;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(10, 35, 66, 0.8), rgba(10, 35, 66, 0.8)), url('https://images.unsplash.com/photo-1507842217343-583bb7270b66?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
        .login-card {
            background: #fffcf2;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.4);
            width: 100%;
            max-width: 450px;
            border-bottom: 8px solid #0a2342;
        }
        .login-card h2 {
            font-family: 'Playfair Display', serif;
            color: #0a2342;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="text-center mb-4">
        <i class="fas fa-university fa-3x text-navy mb-3"></i>
        <h2>ScholarLib</h2>
        <p class="text-muted">Digital Inventory Admin</p>
    </div>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 small"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-3">
            <label class="form-label fw-bold">Librarian Username</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold">Secret Key</label>
            <input type="password" name="password" class="form-control" placeholder="admin123" required>
        </div>
        <button type="submit" class="btn btn-navy w-100 btn-lg shadow">Authorize Portal</button>
    </form>
</div>

</body>
</html>
