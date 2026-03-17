<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Designations
$designations = $conn->query("SELECT * FROM designations ORDER BY designation_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $emp_id = trim($_POST['emp_id']);
    $name = trim($_POST['name']);
    $designation_id = $_POST['designation_id'];
    $email = trim($_POST['email']);
    $salary = $_POST['salary'];
    $join_date = $_POST['join_date'];

    if (empty($emp_id) || empty($name) || empty($salary) || empty($join_date)) {
        $error = "All mandatory fields (*) must be filled.";
    } else {
        // Check for duplicate EMP ID
        $check = $conn->prepare("SELECT id FROM employees WHERE emp_id = ?");
        $check->bind_param("s", $emp_id);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Employee ID already exists. Please use a unique ID.";
        } else {
            $stmt = $conn->prepare("INSERT INTO employees (emp_id, name, designation_id, email, salary, join_date) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisds", $emp_id, $name, $designation_id, $email, $salary, $join_date);
            
            if ($stmt->execute()) {
                $message = "Employee recruited successfully!";
            } else {
                $error = "Error adding record: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Recruit New Employee</h1>
    <a href="view_employees.php" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Back to Directory</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $message; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $error; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow mb-4">
    <div class="card-body pt-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold text-gray-700">Employee ID <span class="text-danger">*</span></label>
                    <input type="text" name="emp_id" class="form-control" placeholder="EMP-1001" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label fw-bold text-gray-700">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Designation <span class="text-danger">*</span></label>
                    <select name="designation_id" class="form-select" required>
                        <option value="">Select Designation</option>
                        <?php 
                        $designations->data_seek(0);
                        while($d = $designations->fetch_assoc()) {
                            echo "<option value='{$d['id']}'>{$d['designation_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="john.doe@company.com">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Monthly Salary ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-purple text-white border-purple">$</span>
                        <input type="number" step="0.01" name="salary" class="form-control" placeholder="5000.00" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Joining Date <span class="text-danger">*</span></label>
                    <input type="date" name="join_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="reset" class="btn btn-light px-4 me-2">Reset Form</button>
                <button type="submit" class="btn btn-purple px-5 shadow">Save Record</button>
            </div>
        </form>
    </div>
</div>

<style>
.btn-purple { background-color: #6f42c1; border-color: #6f42c1; color: white; }
.btn-purple:hover { background-color: #59359a; border-color: #59359a; color: white; }
.border-purple { border-color: #6f42c1; }
</style>

<?php
include 'includes/footer.php';
?>
