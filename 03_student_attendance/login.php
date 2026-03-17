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
            
            // Verify Password (supports both plain text for initial setup and hash for security)
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
    <title>Login - Attendance System</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            background-color: #f9fafb;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }
        .login-card {
            width: 100%;
            max-width: 440px;
            padding: 3rem;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        .institutional-badge {
            font-family: 'Outfit', sans-serif;
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 2px;
            color: #4f46e5;
            text-transform: uppercase;
            display: block;
            margin-bottom: 0.5rem;
            text-align: center;
        }
        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: 2rem;
            text-align: center;
        }
        .form-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 0.5rem;
        }
        .form-control {
            padding: 0.8rem 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 0.95rem;
            color: #111827;
        }
        .form-control:focus {
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }
        .btn-auth {
            background-color: #4f46e5;
            color: white;
            font-weight: 700;
            padding: 1rem;
            border-radius: 8px;
            border: none;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.8rem;
            transition: all 0.2s;
            margin-top: 1rem;
        }
        .btn-auth:hover {
            background-color: #4338ca;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>

<div class="login-card">
    <span class="institutional-badge">Institutional Access</span>
    <h1 class="login-title">Attendance Node</h1>
    
    <?php if($error): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small mb-4 py-2">
            <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-4">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <button class="btn btn-light toggle-password" type="button" data-target="password" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <button type="submit" class="btn-auth">Initialize Session</button>
    </form>
    
    <div class="text-center mt-4">
        <p class="text-muted" style="font-size: 11px;">Restricted Administrative Access Only</p>
    </div>
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
