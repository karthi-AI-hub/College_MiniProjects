<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Handle Add Department
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_dept'])) {
    $dept_name = trim($_POST['dept_name']);
    if (!empty($dept_name)) {
        // Check if exists
        $check = $conn->prepare("SELECT id FROM departments WHERE dept_name = ?");
        $check->bind_param("s", $dept_name);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Department already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO departments (dept_name) VALUES (?)");
            $stmt->bind_param("s", $dept_name);
            if ($stmt->execute()) {
                $message = "Department added successfully.";
            } else {
                $error = "Error adding department: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $error = "Department name cannot be empty.";
    }
}
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">New Department</h1>
            <p class="text-muted small mb-0">Add a department to your college.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="view_departments.php" class="btn btn-light btn-sm px-3 border">
                <i class="fas fa-arrow-left me-1"></i> Back to Departments
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="card-title mb-0 outfit fw-600">Register Department</h5>
            </div>
            <div class="card-body p-4">
                <form action="" method="post">
                    <div class="mb-4">
                        <label class="form-label small fw-600 text-muted mb-1">DEPARTMENT NAME</label>
                        <input type="text" name="dept_name" class="form-control" placeholder="e.g. Electrical Engineering" required>
                    </div>
                    <div class="mt-4 pt-4 border-top d-flex gap-2">
                        <button type="submit" name="add_dept" class="btn btn-primary flex-grow-1 fw-600 py-2">
                            <i class="fas fa-save me-2"></i> Create Department
                        </button>
                        <a href="view_departments.php" class="btn btn-light border px-4 fw-600 py-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</div> <!-- End container-fluid -->

<?php
include 'includes/footer.php';
?>
