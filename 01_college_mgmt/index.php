<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch stats
$student_count_query = "SELECT COUNT(*) as total FROM students";
$dept_count_query = "SELECT COUNT(*) as total FROM departments";

$student_result = $conn->query($student_count_query);
$dept_result = $conn->query($dept_count_query);

$total_students = $student_result->fetch_assoc()['total'];
$total_depts = $dept_result->fetch_assoc()['total'];
?>

<h1 class="mt-4">Dashboard</h1>
<p>Welcome to the College Management System</p>

<div class="row mt-4">
    <!-- Total Students Card -->
    <div class="col-md-6 mb-4">
        <div class="card text-white bg-primary h-100">
            <div class="card-header">Students</div>
            <div class="card-body">
                <h5 class="card-title display-4"><?php echo $total_students; ?></h5>
                <p class="card-text">Total Registered Students</p>
                <a href="view_students.php" class="btn btn-light btn-sm">View Details</a>
            </div>
        </div>
    </div>

    <!-- Total Departments Card -->
    <div class="col-md-6 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-header">Departments</div>
            <div class="card-body">
                <h5 class="card-title display-4"><?php echo $total_depts; ?></h5>
                <p class="card-text">Active Departments</p>
                <a href="departments.php" class="btn btn-light btn-sm">Manage Departments</a>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
