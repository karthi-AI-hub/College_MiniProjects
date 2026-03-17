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
    <title>Login | College Management</title>
    <!-- Google Fonts: Inter & Outfit -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body id="login-wrapper">

<div class="login-card-flat">
    <div class="text-center mb-5">
        <h1 class="outfit fw-800 text-dark mb-2" style="font-size: 1.75rem;">College Management</h1>
        <p class="text-muted small text-uppercase fw-600 tracking-wider">Administrative Access</p>
    </div>
    
    <?php if($error): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger py-2 small mb-4 text-center rounded-3"><?php echo $error; ?></div>
    <?php endif; ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="mb-4">
            <label class="form-label text-muted small fw-600">USERNAME</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-4">
            <label class="form-label text-muted small fw-600">PASSWORD</label>
            <div class="input-group">
                <input type="password" name="password" id="password" class="form-control" required>
                <button class="btn btn-light toggle-password" type="button" data-target="password" aria-label="Show password">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
        </div>
        <div class="d-grid mt-5">
            <button type="submit" class="btn btn-primary">Sign In to Dashboard</button>
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
