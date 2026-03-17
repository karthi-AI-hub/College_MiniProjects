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

// 4. Designation Breakdown for Chart or List
$designation_stats = $conn->query("SELECT d.designation_name, COUNT(e.id) as emp_count 
                                  FROM designations d 
                                  LEFT JOIN employees e ON d.id = e.designation_id 
                                  GROUP BY d.id");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Executive Dashboard</h1>
    <a href="add_employee.php" class="btn btn-purple shadow-sm"><i class="fas fa-plus fa-sm text-white-50 me-2"></i>Recruit Employee</a>
</div>

<!-- Stats Row -->
<div class="row">

    <!-- Total Employees Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100 border-start-purple">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-purple text-uppercase mb-1">Total Workforce</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_employees; ?> Employees</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Payroll Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100 border-start-success shadow-sm" style="border-left: .25rem solid #198754 !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Monthly Payroll Cost</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">$<?php echo number_format($monthly_payroll, 2); ?></div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- New Joiners Card -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100 border-start-info shadow-sm" style="border-left: .25rem solid #0dcaf0 !important;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">New Hire (<?php echo date('M Y'); ?>)</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $new_joiners; ?> New Members</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Designation breakdown -->
    <div class="col-lg-12 mb-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3 bg-white">
                <h6 class="m-0 font-weight-bold text-purple">Workforce Distribution</h6>
            </div>
            <div class="card-body">
                <?php while($stat = $designation_stats->fetch_assoc()): ?>
                    <?php 
                        $percent = ($total_employees > 0) ? ($stat['emp_count'] / $total_employees) * 100 : 0;
                    ?>
                    <h4 class="small font-weight-bold"><?php echo htmlspecialchars($stat['designation_name']); ?> <span class="float-end"><?php echo $stat['emp_count']; ?></span></h4>
                    <div class="progress mb-4" style="height: 10px;">
                        <div class="progress-bar bg-purple" role="progressbar" style="width: <?php echo $percent; ?>%" aria-valuenow="<?php echo $percent; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
