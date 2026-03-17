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
            
            if ($password === $db_password) {
                $_SESSION['admin'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Access Denied: Invalid Security Token.";
            }
        } else {
            $error = "Admin Identity Not Verified.";
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
    <title>InfinityMedia | Entry Node</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #1a1a1a;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(rgba(75, 0, 130, 0.9), rgba(26, 26, 26, 0.9)), url('https://images.unsplash.com/photo-1514525253361-b83f859b73c0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 1rem 3rem rgba(0,0,0,0.5);
            width: 100%;
            max-width: 450px;
            border: 1px solid rgba(255, 215, 0, 0.2);
            color: #fff;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 215, 0, 0.3);
            color: #fff;
            padding: 12px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.15);
            border-color: #ffd700;
            color: #fff;
            box-shadow: none;
        }
        .btn-entry {
            background: #ffd700;
            color: #1a1a1a;
            font-weight: 800;
            padding: 12px;
            border-radius: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.3s;
        }
        .btn-entry:hover {
            background: #fff;
            transform: scale(1.02);
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <div class="mb-4">
        <i class="fas fa-infinite fa-4x text-gold"></i>
    </div>
    <h2 class="fw-bold text-gold mb-1">INFINITY MEDIA</h2>
    <p class="opacity-75 small mb-5">PREMIUM CONTENT COMMAND CENTER</p>

    <?php if($error): ?>
        <div class="alert alert-danger py-2 small bg-danger bg-opacity-25 border-danger text-white"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post" class="text-start">
        <div class="mb-3">
            <label class="form-label small fw-bold text-gold">ADMIN IDENTIFIER</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-5">
            <label class="form-label small fw-bold text-gold">SECURITY TOKEN</label>
            <input type="password" name="password" class="form-control" placeholder="admin123" required>
        </div>
        <button type="submit" class="btn btn-entry w-100 shadow-lg">Infiltrate Node</button>
    </form>
</div>

</body>
</html>
