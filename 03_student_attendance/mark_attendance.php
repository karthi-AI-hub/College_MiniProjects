<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Default Date: Today
$attendance_date = isset($_GET['attendance_date']) ? $_GET['attendance_date'] : date('Y-m-d');

// Fetch Students
$students = $conn->query("SELECT * FROM students ORDER BY roll_no ASC");

// Check if attendance already exists for this date to pre-fill
$existing_attendance = [];
$check_query = $conn->query("SELECT student_id, status FROM attendance_records WHERE attendance_date = '$attendance_date'");
if ($check_query->num_rows > 0) {
    while($row = $check_query->fetch_assoc()) {
        $existing_attendance[$row['student_id']] = $row['status'];
    }
}
?>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h2 class="text-warning text-dark-shadow"><i class="fas fa-check-square"></i> Mark Attendance</h2>
    <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
</div>

<!-- Date Picker Form -->
<form action="" method="get" class="mb-4 row g-3 align-items-center bg-light p-3 rounded shadow-sm border border-warning">
    <div class="col-auto">
        <label for="attendance_date" class="col-form-label fw-bold">Select Date:</label>
    </div>
    <div class="col-auto">
        <input type="date" id="attendance_date" name="attendance_date" class="form-control" value="<?php echo $attendance_date; ?>" required>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-warning text-dark fw-bold">Load Sheet</button>
    </div>
</form>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'saved'): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> Attendance for <?php echo date('d M Y', strtotime($attendance_date)); ?> has been saved.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-warning">
    <div class="card-header bg-warning text-dark fw-bold">
        Attendance Sheet - <?php echo date('l, d F Y', strtotime($attendance_date)); ?>
    </div>
    <div class="card-body">
        <form action="save_attendance.php" method="post">
            <input type="hidden" name="attendance_date" value="<?php echo $attendance_date; ?>">
            
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Roll No</th>
                            <th>Student Name</th>
                            <th>Class</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($students->num_rows > 0): ?>
                            <?php while($student = $students->fetch_assoc()): ?>
                                <?php 
                                    $s_id = $student['id'];
                                    $status = isset($existing_attendance[$s_id]) ? $existing_attendance[$s_id] : 'Present'; // Default to Present
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($student['roll_no']); ?></td>
                                    <td><?php echo htmlspecialchars($student['name']); ?></td>
                                    <td><?php echo htmlspecialchars($student['class_section']); ?></td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group" aria-label="Attendance Status">
                                            <input type="radio" class="btn-check" name="attendance[<?php echo $s_id; ?>]" id="present_<?php echo $s_id; ?>" value="Present" <?php echo ($status == 'Present') ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-success" for="present_<?php echo $s_id; ?>">Present</label>

                                            <input type="radio" class="btn-check" name="attendance[<?php echo $s_id; ?>]" id="absent_<?php echo $s_id; ?>" value="Absent" <?php echo ($status == 'Absent') ? 'checked' : ''; ?>>
                                            <label class="btn btn-outline-danger" for="absent_<?php echo $s_id; ?>">Absent</label>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="4" class="text-center">No students found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                <button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save me-2"></i> Save Attendance</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
