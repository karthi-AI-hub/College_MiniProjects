<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Default to Today's Date
$today = date('Y-m-d');

// Fetch Stats
$total_students_query = "SELECT COUNT(*) as total FROM students";
$present_query = "SELECT COUNT(*) as present FROM attendance_records WHERE attendance_date = '$today' AND status = 'Present'";
$absent_query = "SELECT COUNT(*) as absent FROM attendance_records WHERE attendance_date = '$today' AND status = 'Absent'";

$total_result = $conn->query($total_students_query);
$present_result = $conn->query($present_query);
$absent_result = $conn->query($absent_query);

$total_students = $total_result->fetch_assoc()['total'];
$present_count = $present_result->fetch_assoc()['present'];
$absent_count = $absent_result->fetch_assoc()['absent'];
?>

<h1 class="mt-4 text-warning" style="text-shadow: 1px 1px 2px rgba(0,0,0,0.1);"><i class="fas fa-chalkboard-teacher"></i> Dashboard</h1>
<p class="text-muted">Overview for Today (<?php echo date('d M Y'); ?>)</p>

<div class="row mt-4">
    <!-- Total Students Card -->
    <div class="col-md-4 mb-4">
        <div class="card bg-info text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title display-4 fw-bold"><?php echo $total_students; ?></h5>
                        <p class="card-text">Total Students</p>
                    </div>
                    <i class="fas fa-users fa-4x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Present Today Card -->
    <div class="col-md-4 mb-4">
        <div class="card bg-success text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title display-4 fw-bold"><?php echo $present_count; ?></h5>
                        <p class="card-text">Present Today</p>
                    </div>
                    <i class="fas fa-user-check fa-4x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Absent Today Card -->
    <div class="col-md-4 mb-4">
        <div class="card bg-danger text-white h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title display-4 fw-bold"><?php echo $absent_count; ?></h5>
                        <p class="card-text">Absent Today</p>
                    </div>
                    <i class="fas fa-user-times fa-4x opacity-50"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mt-5 mb-3">
    <h3 class="text-warning"><i class="fas fa-users"></i> Student Registry</h3>
    <a href="add.php" class="btn btn-warning shadow-sm fw-bold">
        <i class="fas fa-user-plus me-1"></i> Add Student
    </a>
</div>

<div class="card shadow border-0 rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="ps-4">ID</th>
                    <th>Name</th>
                    <th>Roll No</th>
                    <th>Class</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $students = $conn->query("SELECT * FROM students ORDER BY name ASC");
                while($row = $students->fetch_assoc()):
                ?>
                <tr>
                    <td class="ps-4"><?php echo $row['id']; ?></td>
                    <td class="fw-bold"><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                    <td><?php echo htmlspecialchars($row['class_section']); ?></td>
                    <td>
                        <div class="btn-group">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-warning"><i class="fas fa-edit"></i></a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Purge record?')"><i class="fas fa-trash"></i></a>
                        </div>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="d-grid gap-2 col-6 mx-auto mt-5 mb-5">
    <a href="mark_attendance.php" class="btn btn-warning btn-lg fw-bold shadow-sm">
        <i class="fas fa-clipboard-list me-2"></i> Take Attendance for Today
    </a>
</div>

<?php
include 'includes/footer.php';
?>

