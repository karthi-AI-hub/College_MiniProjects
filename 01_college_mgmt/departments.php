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

// Fetch Departments
$departments = $conn->query("SELECT * FROM departments ORDER BY dept_name ASC");
?>

<h2 class="mt-4">Manage Departments</h2>
<hr>

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Add Department Form -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header bg-primary text-white">Add New Department</div>
            <div class="card-body">
                <form action="" method="post">
                    <div class="mb-3">
                        <label class="form-label">Department Name</label>
                        <input type="text" name="dept_name" class="form-control" placeholder="e.g. Computer Science" required>
                    </div>
                    <button type="submit" name="add_dept" class="btn btn-success w-100">Add Department</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Department List -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-secondary text-white">Department List</div>
            <div class="card-body">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Department Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($departments->num_rows > 0): ?>
                            <?php while($row = $departments->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                                    <td>
                                        <a href="delete_department.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Are you sure? This will delete all students in this department!');">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center">No departments found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
