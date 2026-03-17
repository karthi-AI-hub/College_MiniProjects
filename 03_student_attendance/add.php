<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $roll_no = trim($_POST['roll_no']);
    $class_section = trim($_POST['class_section']);

    if (empty($name) || empty($roll_no) || empty($class_section)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO students (name, roll_no, class_section) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $roll_no, $class_section);
        
        if ($stmt->execute()) {
            $message = "Student added successfully.";
        } else {
            $error = "Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="container-fluid">
    <h2 class="mt-4">Register New Student</h2>
    <hr>
    <?php if($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_no" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Class & Section</label>
                    <input type="text" name="class_section" class="form-control" placeholder="e.g. 10-A" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Student</button>
                <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
