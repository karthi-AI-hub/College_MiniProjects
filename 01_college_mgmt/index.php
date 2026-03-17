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
$avg_students_per_dept = ($total_depts > 0) ? round($total_students / $total_depts, 1) : 0;

$recent_students_res = $conn->query("SELECT COUNT(*) as total FROM students WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$recent_students = $recent_students_res->fetch_assoc()['total'] ?? 0;

$top_dept_res = $conn->query("SELECT d.dept_name, COUNT(s.id) as total FROM departments d LEFT JOIN students s ON s.dept_id = d.id GROUP BY d.id ORDER BY total DESC LIMIT 1");
$top_dept_row = ($top_dept_res && $top_dept_res->num_rows > 0) ? $top_dept_res->fetch_assoc() : ['dept_name' => 'N/A', 'total' => 0];

$dept_mix_res = $conn->query("SELECT d.dept_name, COUNT(s.id) as total FROM departments d LEFT JOIN students s ON s.dept_id = d.id GROUP BY d.id ORDER BY d.dept_name ASC");
$dept_labels = [];
$dept_counts = [];
while ($row = $dept_mix_res->fetch_assoc()) {
    $dept_labels[] = $row['dept_name'];
    $dept_counts[] = (int)$row['total'];
}
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Institutional Overview</h1>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-600 mb-0">Department Mix</h6>
                    <span class="text-muted small">Student distribution</span>
                </div>
                <canvas id="deptMixChart" height="220"></canvas>
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
                            <div class="text-muted small text-uppercase fw-600">Avg Students/Dept</div>
                            <div class="h4 mb-0 fw-bold text-primary"><?php echo $avg_students_per_dept; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">New (30 Days)</div>
                            <div class="h4 mb-0 fw-bold text-success"><?php echo $recent_students; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Top Department</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($top_dept_row['dept_name']); ?></div>
                            <div class="text-muted small"><?php echo (int)$top_dept_row['total']; ?> students</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <!-- Student Stats -->
        <div class="col-lg-6 mb-4">
            <div class="card p-4 border-start border-primary border-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <span class="text-muted small fw-600 text-uppercase ls-1">Registrations</span>
                        <h5 class="card-title mt-1 mb-0">Total Students</h5>
                    </div>
                    <div class="stats-icon-flat bg-primary bg-opacity-10 text-primary">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                </div>
                <div class="stats-number"><?php echo $total_students; ?></div>
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <a href="view_students.php" class="btn btn-primary btn-sm flex-grow-1">Manage Registry</a>
                    <a href="add.php" class="btn btn-light btn-sm px-3 border"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>

        <!-- Department Stats -->
        <div class="col-lg-6 mb-4">
            <div class="card p-4 border-start border-info border-4 shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <span class="text-muted small fw-600 text-uppercase ls-1">Infrastructure</span>
                        <h5 class="card-title mt-1 mb-0">Total Departments</h5>
                    </div>
                    <div class="stats-icon-flat bg-info bg-opacity-10 text-info">
                        <i class="fas fa-sitemap"></i>
                    </div>
                </div>
                <div class="stats-number"><?php echo $total_depts; ?></div>
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    <a href="view_departments.php" class="btn btn-primary btn-sm flex-grow-1">Explore Departments</a>
                    <a href="add_department.php" class="btn btn-light btn-sm px-3 border"><i class="fas fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const deptMixChart = document.getElementById('deptMixChart');
    if (deptMixChart) {
        new Chart(deptMixChart, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($dept_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($dept_counts); ?>,
                    backgroundColor: ['#4f46e5', '#38bdf8', '#22c55e', '#f59e0b', '#ef4444', '#a855f7', '#0ea5e9', '#14b8a6'],
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
