<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Default Date: Today
$today = date('Y-m-d');
$attendance_date = isset($_GET['attendance_date']) ? $_GET['attendance_date'] : $today;
$invalid_date = $attendance_date !== $today;
if ($invalid_date) {
    $attendance_date = $today;
}

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
$attendance_locked = !empty($existing_attendance);
?>

<div class="executive-header mt-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Attendance Sheet</h1>
            <p class="text-muted small mb-0">Record attendance for <strong><?php echo date('d M Y', strtotime($attendance_date)); ?></strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="index.php" class="btn btn-light btn-sm px-3 border">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <!-- Date Picker Bar -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="" method="get" class="row g-3 align-items-center no-print">
                <div class="col-auto">
                    <label class="form-label small fw-700 text-muted mb-0 text-uppercase tracking-widest">Selected Date</label>
                </div>
                <div class="col-md-3">
                    <input type="date" name="attendance_date" class="form-control" value="<?php echo $attendance_date; ?>" min="<?php echo $today; ?>" max="<?php echo $today; ?>" readonly required>
                </div>
                <div class="col-auto ms-auto">
                    <div class="small text-muted fw-600">Attendance Calendar</div>
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'saved'): ?>
        <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success small mb-4 py-2">
            <i class="fas fa-check-double me-2"></i> Data synchronization complete for <?php echo date('d M Y', strtotime($attendance_date)); ?>.
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'auth'): ?>
        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning small mb-4 py-2">
            <i class="fas fa-lock me-2"></i> Please sign in to save attendance.
        </div>
    <?php endif; ?>

    <?php if($invalid_date || (isset($_GET['msg']) && $_GET['msg'] == 'date')): ?>
        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 text-warning small mb-4 py-2">
            <i class="fas fa-calendar-day me-2"></i> Attendance can only be marked for today.
        </div>
    <?php endif; ?>

    <?php if($attendance_locked || (isset($_GET['msg']) && $_GET['msg'] == 'locked')): ?>
        <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info small mb-4 py-2">
            <i class="fas fa-shield-alt me-2"></i> Attendance for today is already locked. Review history for changes.
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'error'): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small mb-4 py-2 d-flex flex-wrap align-items-center justify-content-between gap-2">
            <span><i class="fas fa-triangle-exclamation me-2"></i> We couldn't save the attendance. Please try again.</span>
            <a href="mark_attendance.php" class="btn btn-sm btn-outline-danger">Try Again</a>
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm overflow-hidden">
        <div class="card-header bg-white py-3 border-bottom">
            <h5 class="card-title mb-0 outfit fw-600">Attendance Log</h5>
        </div>
        <div class="card-body p-0">
            <form action="save_attendance.php" method="post">
                <input type="hidden" name="attendance_date" value="<?php echo $attendance_date; ?>">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Roll Number</th>
                                <th>Student Name</th>
                                <th>Class / Section</th>
                                <th class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($students->num_rows > 0): ?>
                                <?php while($student = $students->fetch_assoc()): ?>
                                    <?php 
                                        $s_id = $student['id'];
                                        $status = isset($existing_attendance[$s_id]) ? $existing_attendance[$s_id] : 'Present'; 
                                    ?>
                                    <tr>
                                        <td class="ps-4"><code class="text-muted"><?php echo htmlspecialchars($student['roll_no']); ?></code></td>
                                        <td class="fw-600"><?php echo htmlspecialchars($student['name']); ?></td>
                                        <td><span class="badge-zen bg-light text-dark"><?php echo htmlspecialchars($student['class_section']); ?></span></td>
                                        <td class="text-center pe-4">
                                            <div class="btn-group attendance-toggle" role="group">
                                                <input type="radio" class="btn-check" name="attendance[<?php echo $s_id; ?>]" id="present_<?php echo $s_id; ?>" value="Present" <?php echo ($status == 'Present') ? 'checked' : ''; ?> <?php echo $attendance_locked ? 'disabled' : ''; ?>>
                                                <label class="btn btn-outline-success btn-sm px-3 fw-600" for="present_<?php echo $s_id; ?>">P</label>

                                                <input type="radio" class="btn-check" name="attendance[<?php echo $s_id; ?>]" id="absent_<?php echo $s_id; ?>" value="Absent" <?php echo ($status == 'Absent') ? 'checked' : ''; ?> <?php echo $attendance_locked ? 'disabled' : ''; ?>>
                                                <label class="btn btn-outline-danger btn-sm px-3 fw-600" for="absent_<?php echo $s_id; ?>">A</label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">No student data detected in this sector.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-4 bg-light border-top text-end">
                    <button type="submit" class="btn btn-primary px-5 py-2 text-uppercase fw-700 tracking-widest" style="font-size: 0.75rem;" <?php echo $attendance_locked ? 'disabled' : ''; ?>>
                        <i class="fas fa-database me-2"></i> Save Attendance
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
