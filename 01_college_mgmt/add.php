<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Departments for Dropdown
$departments = $conn->query("SELECT * FROM departments ORDER BY dept_name ASC");

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
        // Check if Roll No exists
        $check = $conn->prepare("SELECT id FROM students WHERE roll_no = ?");
        $check->bind_param("s", $roll_no);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Student with this Roll No already exists.";
        } else {
            $stmt = $conn->prepare("INSERT INTO students (name, roll_no, dept_id, email, phone, gender) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisss", $name, $roll_no, $dept_id, $email, $phone, $gender);
            
            if ($stmt->execute()) {
                $message = "Student added successfully.";
                // Clear fields to prevent duplicate submission on refresh or clean form for next entry
                $name = $roll_no = $email = $phone = "";
            } else {
                $error = "Error adding student: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<h2 class="mt-4">Add New Student</h2>
<hr>

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
                    <input type="text" name="name" class="form-control" value="<?php echo isset($name) ? htmlspecialchars($name) : ''; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Roll Number <span class="text-danger">*</span></label>
                    <input type="text" name="roll_no" class="form-control" value="<?php echo isset($roll_no) ? htmlspecialchars($roll_no) : ''; ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Department <span class="text-danger">*</span></label>
                    <select name="dept_id" class="form-select" required>
                        <option value="">Select Department</option>
                        <?php 
                        if ($departments->num_rows > 0) {
                            while($row = $departments->fetch_assoc()) {
                                $selected = (isset($dept_id) && $dept_id == $row['id']) ? 'selected' : '';
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
                        <option value="Male" <?php echo (isset($gender) && $gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo (isset($gender) && $gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                        <option value="Other" <?php echo (isset($gender) && $gender == 'Other') ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Save Student</button>
            <a href="view_students.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
