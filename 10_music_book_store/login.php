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
    <title>Login | InfinityMedia</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            background: var(--dark-bg);
            background-image: linear-gradient(rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.9)), url('https://images.unsplash.com/photo-1514525253361-b83f859b73c0?ixlib=rb-1.2.1&auto=format&fit=crop&w=1350&q=80');
            background-size: cover;
            background-position: center;
        }
        .login-card {
            width: 100%;
            max-width: 450px;
            padding: 50px;
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(15px);
            border: 1px solid var(--border-color);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 3rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .login-subtitle {
            color: var(--text-muted);
            text-align: center;
            margin-bottom: 2.5rem;
            font-size: 0.9rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 14px 18px;
            border-radius: 12px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-color);
            color: #fff;
            box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);
        }
        .form-label {
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1 class="login-title">InfinityMedia</h1>
    <p class="login-subtitle">Store Login</p>
    
    <?php if($error): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger py-2 small mb-4"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="" method="post">
        <div class="mb-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" placeholder="admin" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" placeholder="admin123" required>
                <button class="btn btn-light toggle-password" type="button" data-target="password" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="d-grid mt-5">
            <button type="submit" class="btn btn-gold py-3 fw-bold">Sign In <i class="fas fa-key ms-2"></i></button>
        </div>
    </form>
</div>

<script>
    document.querySelectorAll('.toggle-password').forEach(function(button) {
        button.addEventListener('click', function() {
            var target = document.getElementById(button.dataset.target);
            if (!target) return;
            var isPassword = target.type === 'password';
            target.type = isPassword ? 'text' : 'password';
            var icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('fa-eye', !isPassword);
                icon.classList.toggle('fa-eye-slash', isPassword);
            }
        });
    });
</script>

</body>
</html>
