<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$student = $stmt->get_result()->fetch_assoc();

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $roll_no = trim($_POST['roll_no']);
    $class_section = trim($_POST['class_section']);

    $stmt = $conn->prepare("UPDATE students SET name=?, roll_no=?, class_section=? WHERE id=?");
    $stmt->bind_param("sssi", $name, $roll_no, $class_section, $id);
    
    if ($stmt->execute()) {
        $message = "Student updated successfully.";
        $student['name'] = $name;
        $student['roll_no'] = $roll_no;
        $student['class_section'] = $class_section;
    } else {
        $error = "Error updating student.";
    }
}
?>

<div class="container-fluid">
    <h2 class="mt-4">Edit Student</h2>
    <hr>
    <?php if($message): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
    <?php if($error): ?><div class="alert alert-danger"><?php echo $error; ?></div><?php endif; ?>

    <div class="card shadow">
        <div class="card-body">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label">Full Name</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Roll Number</label>
                    <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Class & Section</label>
                    <input type="text" name="class_section" class="form-control" value="<?php echo htmlspecialchars($student['class_section']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Update Student</button>
                <a href="index.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
