<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Departments
$departments = $conn->query("SELECT * FROM departments ORDER BY dept_name ASC");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();

    if (!$student) {
        die("Student not found.");
    }
} else {
    header("Location: view_students.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $roll_no = trim($_POST['roll_no']);
    $dept_id = $_POST['dept_id'];
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];

    if (empty($name) || empty($roll_no) || empty($dept_id) || empty($gender)) {
        $error = "Name, Roll No, Department, and Gender are required.";
    } else {
        // Check if Roll No exists for other students
        $check = $conn->prepare("SELECT id FROM students WHERE roll_no = ? AND id != ?");
        $check->bind_param("si", $roll_no, $id);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Roll No already exists for another student.";
        } else {
            $stmt = $conn->prepare("UPDATE students SET name=?, roll_no=?, dept_id=?, email=?, phone=?, gender=? WHERE id=?");
            $stmt->bind_param("ssisssi", $name, $roll_no, $dept_id, $email, $phone, $gender, $id);
            
            if ($stmt->execute()) {
                $message = "Student updated successfully.";
                // Refresh data
                $stmt_refresh = $conn->prepare("SELECT * FROM students WHERE id = ?");
                $stmt_refresh->bind_param("i", $id);
                $stmt_refresh->execute();
                $result_refresh = $stmt_refresh->get_result();
                $student = $result_refresh->fetch_assoc();
                $stmt_refresh->close();
            } else {
                $error = "Error updating student: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Edit Student</h1>
            <p class="text-muted small mb-0">Modify academic profile: <span class="text-primary fw-600"><?php echo htmlspecialchars($student['name']); ?></span></p>
        </div>
        <a href="view_students.php" class="btn btn-light btn-sm px-3 border"><i class="fas fa-arrow-left me-1"></i> Back to Directory</a>
    </div>
</div>

<div class="container-fluid">

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <a href="view_students.php" class="btn btn-sm btn-success ms-2">View List</a>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Roll Number <span class="text-danger">*</span></label>
                    <input type="text" name="roll_no" class="form-control" value="<?php echo htmlspecialchars($student['roll_no']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <select name="dept_id" class="form-select" required>
                        <option value="">Select Department</option>
                        <?php 
                        if ($departments->num_rows > 0) {
                            $departments->data_seek(0); // Reset pointer
                            while($row = $departments->fetch_assoc()) {
                                $selected = ($student['dept_id'] == $row['id']) ? 'selected' : '';
                                echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['dept_name']) . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender <span class="text-danger">*</span></label>
                    <select name="gender" class="form-select" required>
                        <option value="">Select Gender</option>
                        <option value="Male" <?php echo ($student['gender'] == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($student['gender'] == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo ($student['gender'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($student['email']); ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($student['phone']); ?>">
                </div>
            </div>

            <div class="mt-4 pt-4 border-top">
                <button type="submit" class="btn btn-primary px-4 fw-600">
                    <i class="fas fa-save me-2"></i> Update Student Profile
                </button>
                <a href="view_students.php" class="btn btn-light border px-4 ms-2">Cancel</a>
            </div>
        </form>
    </div>
</div> <!-- End container-fluid -->
<?php
include 'includes/footer.php';
?>
