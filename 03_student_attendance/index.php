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
$attendance_rate = ($total_students > 0) ? round(($present_count / $total_students) * 100, 1) : 0;
$absence_rate = ($total_students > 0) ? round(($absent_count / $total_students) * 100, 1) : 0;
$recorded_count = $present_count + $absent_count;
$unmarked_count = max(0, $total_students - $recorded_count);
?>

<div class="executive-header mt-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Attendance Overview</h1>
            <p class="text-muted small mb-0">Daily institutional oversight for <strong><?php echo date('l, d F Y'); ?></strong>.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="mark_attendance.php" class="btn btn-primary px-4 fw-600">
                <i class="fas fa-clipboard-check me-2"></i> Mark Today's Attendance
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row g-4 mb-5">
        <!-- Total Students Card -->
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="small fw-700 text-muted tracking-widest text-uppercase mb-1">Total Students</div>
                        <h2 class="outfit fw-800 mb-0"><?php echo $total_students; ?></h2>
                    </div>
                    <div class="bg-indigo-50 p-2 rounded-3">
                        <i class="fas fa-users text-primary"></i>
                    </div>
                </div>
                <div class="small text-muted border-top pt-3">Total enrolled students</div>
            </div>
        </div>

        <!-- Present Today Card -->
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="small fw-700 text-muted tracking-widest text-uppercase mb-1">Present Today</div>
                        <h2 class="outfit fw-800 mb-0 text-success"><?php echo $present_count; ?></h2>
                    </div>
                    <div class="bg-success-50 p-2 rounded-3">
                        <i class="fas fa-user-check text-success"></i>
                    </div>
                </div>
                <div class="progress mb-2" style="height: 4px;">
                    <div class="progress-bar bg-success" style="width: <?php echo ($total_students > 0) ? ($present_count/$total_students)*100 : 0; ?>%"></div>
                </div>
                <div class="small text-muted">Attendance rate today</div>
            </div>
        </div>

        <!-- Absent Today Card -->
        <div class="col-md-4">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <div class="small fw-700 text-muted tracking-widest text-uppercase mb-1">Absent Today</div>
                        <h2 class="outfit fw-800 mb-0 text-danger"><?php echo $absent_count; ?></h2>
                    </div>
                    <div class="bg-danger-50 p-2 rounded-3">
                        <i class="fas fa-user-times text-danger"></i>
                    </div>
                </div>
                <div class="progress mb-2" style="height: 4px;">
                    <div class="progress-bar bg-danger" style="width: <?php echo ($total_students > 0) ? ($absent_count/$total_students)*100 : 0; ?>%"></div>
                </div>
                <div class="small text-muted">Absences today</div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-600 mb-0">Attendance Split</h6>
                    <span class="text-muted small">Today</span>
                </div>
                <canvas id="attendanceChart" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-600 mb-0">Quick Insights</h6>
                    <span class="text-muted small">Snapshot</span>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Attendance Rate</div>
                            <div class="h4 mb-0 fw-bold text-success"><?php echo $attendance_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Absence Rate</div>
                            <div class="h4 mb-0 fw-bold text-danger"><?php echo $absence_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Recorded Today</div>
                            <div class="h4 mb-0 fw-bold text-primary"><?php echo $recorded_count; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Unmarked</div>
                            <div class="h4 mb-0 fw-bold text-warning"><?php echo $unmarked_count; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-5">
        <div class="card-body p-4 d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <h5 class="card-title mb-1 outfit fw-600">Student Registry</h5>
                <p class="text-muted small mb-0">Manage full student records in a dedicated view.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="view_students.php" class="btn btn-primary px-4 fw-600">
                    <i class="fas fa-users me-2"></i> View Registry
                </a>
                <a href="add.php" class="btn btn-light border px-3 fw-600">
                    <i class="fas fa-plus me-1"></i> New Entry
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const attendanceChart = document.getElementById('attendanceChart');
    if (attendanceChart) {
        new Chart(attendanceChart, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Absent'],
                datasets: [{
                    data: [<?php echo (int)$present_count; ?>, <?php echo (int)$absent_count; ?>],
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
</script>

<?php
include 'includes/footer.php';
?>

