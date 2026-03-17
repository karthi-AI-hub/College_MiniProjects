<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$id = $_GET['id'] ?? null;
if (!$id) { header("Location: index.php"); exit(); }

$stmt = $conn->prepare("SELECT id, name, roll_no, class_section FROM students WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($student_id, $student_name, $student_roll, $student_section);
$stmt->fetch();
$student = $student_id ? [
    'id' => $student_id,
    'name' => $student_name,
    'roll_no' => $student_roll,
    'class_section' => $student_section
] : null;
$stmt->close();

if (!$student) {
    header("Location: index.php");
    exit();
}

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

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Modify Profile</h1>
            <p class="text-muted small mb-0">Update institutional record for civilian <strong><?php echo htmlspecialchars($student['roll_no']); ?></strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-light btn-sm px-3 border">
                <i class="fas fa-arrow-left me-1"></i> Registry
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php if($message): ?>
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success small mb-4 py-2">
            <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small mb-4 py-2">
            <i class="fas fa-exclamation-triangle me-2"></i> <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="card-title mb-0 outfit fw-600">Administrative Record Edit</h5>
                </div>
                <div class="card-body p-4">
                    <form action="" method="post">
                        <div class="row">
                            <div class="col-md-7 mb-4">
                                <label class="form-label small fw-700 text-muted mb-1">LEGAL IDENTITY NAME</label>
                                <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                            </div>
                            <div class="col-md-5 mb-4">
                                <label class="form-label small fw-700 text-muted mb-1">ROLL IDENTIFIER</label>
                                <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label class="form-label small fw-700 text-muted mb-1">SECTOR / CLASS ASSIGNMENT</label>
                            <input type="text" name="class_section" class="form-control" value="<?php echo htmlspecialchars($student['class_section']); ?>" required>
                        </div>
                        <div class="mt-4 pt-4 border-top d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1 fw-700 py-3 text-uppercase tracking-widest" style="font-size: 0.75rem;">
                                <i class="fas fa-sync me-2"></i> Update Archive
                             </button>
                            <a href="index.php" class="btn btn-light border px-4 fw-700 py-3 text-uppercase tracking-widest" style="font-size: 0.75rem;">Abort</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
