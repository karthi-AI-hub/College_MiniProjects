<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_logout'])) {
	session_unset();
	session_destroy();
	header("Location: login.php");
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Confirm Logout</title>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Outfit:wght@700;800&display=swap" rel="stylesheet">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="assets/css/style.css">
</head>
<body id="login-wrapper">

<div class="login-card-flat text-center">
	<h1 class="fw-bold mb-2" style="font-size: 1.6rem;">Confirm Logout</h1>
	<p class="text-muted small mb-4">Are you sure you want to sign out?</p>
	<form method="post" class="d-grid gap-2">
		<button type="submit" name="confirm_logout" class="btn btn-primary">Yes, sign out</button>
		<a href="index.php" class="btn btn-light">Cancel</a>
	</form>
</div>

</body>
</html>
