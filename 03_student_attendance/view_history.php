<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Default Date: Today
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('m');
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'daily';

// Fetch History for Selected Date
$history_query = "SELECT s.name, s.roll_no, s.class_section, ar.status 
                  FROM students s 
                  LEFT JOIN attendance_records ar ON s.id = ar.student_id AND ar.attendance_date = '$selected_date'
                  ORDER BY s.roll_no ASC";
$history_result = $conn->query($history_query);

// Fetch Monthly Percentage Stats
// Calculate percentage of present days for each student in the selected month
$percentage_query = "SELECT s.name, s.roll_no, 
                     COUNT(CASE WHEN ar.status = 'Present' THEN 1 END) as present_days,
                     COUNT(ar.id) as total_attendance_days
                     FROM students s
                     LEFT JOIN attendance_records ar ON s.id = ar.student_id 
                     AND MONTH(ar.attendance_date) = '$selected_month' 
                     AND YEAR(ar.attendance_date) = '$selected_year'
                     GROUP BY s.id
                     ORDER BY s.roll_no ASC";
$percentage_result = $conn->query($percentage_query);
?>

<h1 class="mt-4 text-warning"><i class="fas fa-history"></i> Attendance History</h1>

<ul class="nav nav-tabs mt-4" id="myTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link text-dark fw-bold <?php echo ($active_tab === 'daily') ? 'active' : ''; ?>" id="daily-tab" data-bs-toggle="tab" data-bs-target="#daily" type="button" role="tab">Daily View</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link text-dark fw-bold <?php echo ($active_tab === 'monthly') ? 'active' : ''; ?>" id="monthly-tab" data-bs-toggle="tab" data-bs-target="#monthly" type="button" role="tab">Monthly Percentage</button>
    </li>
</ul>

<div class="tab-content" id="myTabContent">
    
    <!-- Daily View Tab -->
    <div class="tab-pane fade p-3 bg-white border border-top-0 rounded-bottom <?php echo ($active_tab === 'daily') ? 'show active' : ''; ?>" id="daily" role="tabpanel">
        <form action="" method="get" class="row g-3 align-items-center mb-4">
            <input type="hidden" name="tab" value="daily">
            <div class="col-auto">
                <label for="date" class="col-form-label fw-bold">Select Date:</label>
            </div>
            <div class="col-auto">
                <input type="date" id="date" name="date" class="form-control" value="<?php echo $selected_date; ?>">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-warning">View</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($history_result->num_rows > 0): ?>
                        <?php while($row = $history_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['class_section']); ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Present'): ?>
                                        <span class="badge bg-success">Present</span>
                                    <?php elseif ($row['status'] == 'Absent'): ?>
                                        <span class="badge bg-danger">Absent</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Not Marked</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">No records found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Monthly Stats Tab -->
    <div class="tab-pane fade p-3 bg-white border border-top-0 rounded-bottom <?php echo ($active_tab === 'monthly') ? 'show active' : ''; ?>" id="monthly" role="tabpanel">
        <form action="" method="get" class="row g-3 align-items-center mb-4">
            <input type="hidden" name="tab" value="monthly">
            <input type="hidden" name="date" value="<?php echo $selected_date; ?>"> <!-- Keep daily date selected -->
            <div class="col-auto">
                <label class="col-form-label fw-bold">Select Month/Year:</label>
            </div>
            <div class="col-auto">
                <select name="month" class="form-select">
                    <?php
                    for ($m=1; $m<=12; $m++) {
                        $month = date('F', mktime(0,0,0,$m, 1, date('Y')));
                        $selected = ($m == $selected_month) ? 'selected' : '';
                        echo "<option value='$m' $selected>$month</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-auto">
                <select name="year" class="form-select">
                    <?php
                    $current_year = date('Y');
                    for ($y=$current_year; $y>=$current_year-5; $y--) {
                        $selected = ($y == $selected_year) ? 'selected' : '';
                        echo "<option value='$y' $selected>$y</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-info text-white">Filter Stats</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="bg-warning text-dark">
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Total Working Days</th>
                        <th>Days Present</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($percentage_result->num_rows > 0): ?>
                        <?php while($row = $percentage_result->fetch_assoc()): ?>
                            <?php 
                                $total = $row['total_attendance_days'];
                                $present = $row['present_days'];
                                $percentage = ($total > 0) ? round(($present / $total) * 100, 2) : 0;
                                $progress_color = ($percentage < 75) ? 'bg-danger' : 'bg-success';
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo $total; ?></td>
                                <td><?php echo $present; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="me-2 fw-bold"><?php echo $percentage; ?>%</span>
                                        <div class="progress flex-grow-1" style="height: 10px;">
                                            <div class="progress-bar <?php echo $progress_color; ?>" role="progressbar" style="width: <?php echo $percentage; ?>%;" aria-valuenow="<?php echo $percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center">No attendance data for this month.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
