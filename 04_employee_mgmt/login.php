<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = "Please enter both username and password.";
    } else {
        $stmt = $conn->prepare("SELECT id, password FROM admin WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($id, $db_password);
            $stmt->fetch();
            
            // Simple string check for demo/initial setup, password_verify for production
            if ($password === $db_password || password_verify($password, $db_password)) {
                $_SESSION['admin'] = $username;
                header("Location: index.php");
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid username.";
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
    <title>Login - Employee Management</title>
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
        }
        .login-card {
            width: 100%;
            max-width: 400px;
            padding: 40px;
            background: var(--card-bg);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.4);
        }
        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 2rem;
            font-weight: 800;
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: #fff;
            padding: 12px;
            border-radius: 10px;
        }
        .form-control:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--accent-color);
            color: #fff;
            box-shadow: none;
        }
        .form-label {
            color: var(--text-muted);
            font-weight: 500;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1 class="login-title">Employee Management</h1>
    <?php if($error): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-4">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required placeholder="Enter username">
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required placeholder="Enter password">
                <button class="btn btn-light toggle-password" type="button" data-target="password" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="d-grid mt-5">
            <button type="submit" class="btn btn-purple">Login <i class="fas fa-sign-in-alt ms-2"></i></button>
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
