<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Statistics
// 1. Total Employees
$total_emp_res = $conn->query("SELECT COUNT(*) as total FROM employees");
$total_employees = $total_emp_res->fetch_assoc()['total'];

// 2. Monthly Payroll Total (SQL SUM function)
// Examiners love this: calculating the total salary payout using MySQL
$payroll_res = $conn->query("SELECT SUM(salary) as total_payroll FROM employees");
$monthly_payroll = $payroll_res->fetch_assoc()['total_payroll'] ?? 0;

// 3. New Joiners (This Month)
$this_month = date('Y-m');
$new_joiners_res = $conn->query("SELECT COUNT(*) as new_count FROM employees WHERE join_date LIKE '$this_month%'");
$new_joiners = $new_joiners_res->fetch_assoc()['new_count'];

$avg_salary = ($total_employees > 0) ? $monthly_payroll / $total_employees : 0;
$new_joiner_rate = ($total_employees > 0) ? round(($new_joiners / $total_employees) * 100, 1) : 0;

$top_designation_res = $conn->query("SELECT d.designation_name, COUNT(e.id) as emp_count
                                  FROM designations d
                                  LEFT JOIN employees e ON d.id = e.designation_id
                                  GROUP BY d.id
                                  ORDER BY emp_count DESC
                                  LIMIT 1");
$top_designation = ($top_designation_res && $top_designation_res->num_rows > 0) ? $top_designation_res->fetch_assoc() : ['designation_name' => 'N/A', 'emp_count' => 0];

// 4. Designation Breakdown for Chart or List
$designation_stats = $conn->query("SELECT d.designation_name, COUNT(e.id) as emp_count 
                                  FROM designations d 
                                  LEFT JOIN employees e ON d.id = e.designation_id 
                                  GROUP BY d.id");

$designation_rows = [];
while ($row = $designation_stats->fetch_assoc()) {
    $designation_rows[] = $row;
}

$designation_labels = array_map(function($row) {
    return $row['designation_name'];
}, $designation_rows);

$designation_counts = array_map(function($row) {
    return (int)$row['emp_count'];
}, $designation_rows);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Executive Dashboard</h1>
    <a href="add_employee.php" class="btn btn-purple"><i class="fas fa-plus fa-sm me-2"></i>Recruit Employee</a>
</div>

<div class="row">
    <!-- Total Workforce Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-purple bg-opacity-10 p-3 me-3">
                        <i class="fas fa-users text-purple fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">Total Workforce</h5>
                </div>
                <h2 class="display-4 fw-bold mb-3"><?php echo $total_employees; ?></h2>
                <p class="text-muted">Managed Employees</p>
            </div>
        </div>
    </div>

    <!-- Monthly Payroll Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">
                        <i class="fas fa-dollar-sign text-success fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">Monthly Payroll</h5>
                </div>
                <h2 class="display-4 fw-bold mb-3 text-success">$<?php echo number_format($monthly_payroll, 2); ?></h2>
                <p class="text-muted">Total Salary Payout</p>
            </div>
        </div>
    </div>

    <!-- New Hire Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-calendar-check text-info fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">New Hire</h5>
                </div>
                <h2 class="display-4 fw-bold mb-3 text-info"><?php echo $new_joiners; ?></h2>
                <p class="text-muted">Joined in <?php echo date('M Y'); ?></p>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Team Breakdown</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="employeeChart" height="240"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Insight Highlights</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Avg Salary</div>
                            <div class="h4 mb-0 fw-bold text-success">$<?php echo number_format($avg_salary, 2); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">New Hire Rate</div>
                            <div class="h4 mb-0 fw-bold text-info"><?php echo $new_joiner_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Top Designation</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($top_designation['designation_name']); ?></div>
                            <div class="text-muted small"><?php echo (int)$top_designation['emp_count']; ?> team members</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Workforce Distribution</h5>
            </div>
            <div class="card-body p-4">
                <?php foreach ($designation_rows as $stat): ?>
                    <?php
                        $percent = ($total_employees > 0) ? ($stat['emp_count'] / $total_employees) * 100 : 0;
                    ?>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted"><?php echo htmlspecialchars($stat['designation_name']); ?></span>
                            <span class="fw-bold"><?php echo $stat['emp_count']; ?></span>
                        </div>
                        <div class="progress" style="height: 8px; border-radius: 10px; background: rgba(255,255,255,0.05);">
                            <div class="progress-bar bg-purple" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const employeeChart = document.getElementById('employeeChart');
    if (employeeChart) {
        new Chart(employeeChart, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($designation_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($designation_counts); ?>,
                    backgroundColor: ['#4f46e5', '#22c55e', '#f59e0b', '#ef4444', '#38bdf8', '#a855f7'],
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
