<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Designations
$designations = $conn->query("SELECT * FROM designations ORDER BY designation_name ASC");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM employees WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $employee = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$employee) {
        die("Employee record not found.");
    }
} else {
    header("Location: view_employees.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $designation_id = $_POST['designation_id'];
    $email = trim($_POST['email']);
    $salary = $_POST['salary'];
    $join_date = $_POST['join_date'];

    if (empty($name) || empty($salary) || empty($join_date)) {
        $error = "Name, Salary, and Join Date are mandatory.";
    } else {
        $stmt = $conn->prepare("UPDATE employees SET name=?, designation_id=?, email=?, salary=?, join_date=? WHERE id=?");
        $stmt->bind_param("sisdsi", $name, $designation_id, $email, $salary, $join_date, $id);
        
        if ($stmt->execute()) {
            $message = "Employee record updated successfully (Professional Update).";
            // Refresh data
            $stmt_refresh = $conn->prepare("SELECT * FROM employees WHERE id = ?");
            $stmt_refresh->bind_param("i", $id);
            $stmt_refresh->execute();
            $employee = $stmt_refresh->get_result()->fetch_assoc();
            $stmt_refresh->close();
        } else {
            $error = "Error updating record: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Update Employee Profile</h1>
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
    <div class="card-header bg-white py-3">
        <h6 class="m-0 font-weight-bold text-purple">Employee ID: <?php echo htmlspecialchars($employee['emp_id']); ?></h6>
    </div>
    <div class="card-body pt-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold text-gray-700">Full Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($employee['name']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Designation (Promotion/Transfer) <span class="text-danger">*</span></label>
                    <select name="designation_id" class="form-select" required>
                        <?php 
                        $designations->data_seek(0);
                        while($d = $designations->fetch_assoc()) {
                            $selected = ($employee['designation_id'] == $d['id']) ? 'selected' : '';
                            echo "<option value='{$d['id']}' $selected>{$d['designation_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Email Address</label>
                    <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($employee['email']); ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Monthly Salary ($) <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-purple text-white border-purple">$</span>
                        <input type="number" step="0.01" name="salary" class="form-control" value="<?php echo $employee['salary']; ?>" required>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold text-gray-700">Joining Date <span class="text-danger">*</span></label>
                    <input type="date" name="join_date" class="form-control" value="<?php echo $employee['join_date']; ?>" required>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top text-end">
                <button type="submit" class="btn btn-purple px-5 shadow">Update Record</button>
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
