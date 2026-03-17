<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Blood Bank Statistics
// 1. Total Donors
$total_donors_res = $conn->query("SELECT COUNT(*) as total FROM donors");
$total_donors = $total_donors_res->fetch_assoc()['total'];

// 2. Recent Registrations (Last 30 days)
$recent_res = $conn->query("SELECT COUNT(*) as total FROM donors WHERE registered_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$recent_count = $recent_res->fetch_assoc()['total'];

// 3. Donors by Group (O+ and O-) for emergency highlight
$o_plus_res = $conn->query("SELECT COUNT(*) as total FROM donors WHERE blood_group = 'O+'");
$o_plus = $o_plus_res->fetch_assoc()['total'];

$o_minus_res = $conn->query("SELECT COUNT(*) as total FROM donors WHERE blood_group = 'O-'");
$o_minus = $o_minus_res->fetch_assoc()['total'];

$o_plus_share = ($total_donors > 0) ? round(($o_plus / $total_donors) * 100, 1) : 0;
$o_minus_share = ($total_donors > 0) ? round(($o_minus / $total_donors) * 100, 1) : 0;

$top_city_res = $conn->query("SELECT city, COUNT(*) as total FROM donors GROUP BY city ORDER BY total DESC LIMIT 1");
$top_city = ($top_city_res && $top_city_res->num_rows > 0) ? $top_city_res->fetch_assoc() : ['city' => 'N/A', 'total' => 0];

$blood_group_res = $conn->query("SELECT blood_group, COUNT(*) as total FROM donors GROUP BY blood_group");
$blood_group_labels = [];
$blood_group_counts = [];
while ($row = $blood_group_res->fetch_assoc()) {
    $blood_group_labels[] = $row['blood_group'];
    $blood_group_counts[] = (int)$row['total'];
}

// Quick Table: Recent Donors
$recent_donors = $conn->query("SELECT * FROM donors ORDER BY registered_at DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Emergency Control</h1>
    <a href="add_donor.php" class="btn btn-danger shadow-sm"><i class="fas fa-plus me-2"></i>Provision Donor</a>
</div>

<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Blood Group Mix</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="bloodGroupChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-muted small text-uppercase fw-bold mb-2">O+ Share</div>
                <h3 class="fw-bold text-danger mb-1"><?php echo $o_plus_share; ?>%</h3>
                <div class="small text-muted"><?php echo $o_plus; ?> donors</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-muted small text-uppercase fw-bold mb-2">O- Share</div>
                <h3 class="fw-bold text-info mb-1"><?php echo $o_minus_share; ?>%</h3>
                <div class="small text-muted"><?php echo $o_minus; ?> donors</div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="text-muted small text-uppercase fw-bold mb-2">Top Locality</div>
                <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($top_city['city']); ?></h4>
                <div class="small text-muted"><?php echo (int)$top_city['total']; ?> donors</div>
            </div>
        </div>
    </div>
</div>

<div class="card mb-5 bg-danger bg-opacity-10 border-danger border-opacity-25">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4"><i class="fas fa-search me-2"></i>Universal Search Registry</h5>
        <form action="view_donors.php" method="get" class="row g-3">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control py-3" placeholder="Locality Search (e.g. London)...">
            </div>
            <div class="col-md-4">
                <select name="blood_group" class="form-select py-3">
                    <option value="">All Blood Groups</option>
                    <?php foreach(['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-'] as $group): ?>
                        <option value="<?php echo $group; ?>"><?php echo $group; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-danger w-100 py-3 fw-bold">SCAN DONORS</button>
            </div>
        </form>
    </div>
</div>

<div class="row mb-5">
    <!-- Active Donors Card -->
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <p class="text-muted small text-uppercase fw-bold mb-2">Network Size</p>
                <div class="d-flex align-items-end justify-content-between">
                    <h2 class="stats-number mb-0"><?php echo $total_donors; ?></h2>
                    <i class="fas fa-users text-danger opacity-50 fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Intake Card -->
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <p class="text-muted small text-uppercase fw-bold mb-2">30-Day Intake</p>
                <div class="d-flex align-items-end justify-content-between">
                    <h2 class="stats-number mb-0 text-warning"><?php echo $recent_count; ?></h2>
                    <i class="fas fa-user-plus text-warning opacity-50 fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- O+ Inventory Card -->
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body p-4 border-start border-4 border-success">
                <p class="text-muted small text-uppercase fw-bold mb-2">O+ Inventory</p>
                <div class="d-flex align-items-end justify-content-between">
                    <h2 class="stats-number mb-0 text-success"><?php echo $o_plus; ?></h2>
                    <i class="fas fa-tint text-success opacity-50 fs-2"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- O- Inventory Card -->
    <div class="col-md-3 mb-4">
        <div class="card h-100">
            <div class="card-body p-4 border-start border-4 border-info">
                <p class="text-muted small text-uppercase fw-bold mb-2">Universal (O-)</p>
                <div class="d-flex align-items-end justify-content-between">
                    <h2 class="stats-number mb-0 text-info"><?php echo $o_minus; ?></h2>
                    <i class="fas fa-biohazard text-info opacity-50 fs-2"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Recent Donor Intake</h5>
                <a href="view_donors.php" class="btn btn-sm btn-outline-light border-0">Full Registry</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Entity Identity</th>
                                <th>Biological Group</th>
                                <th>Communication</th>
                                <th>Locality</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_donors->num_rows > 0): ?>
                                <?php while($d = $recent_donors->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-danger bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas fa-user-md text-danger"></i>
                                                </div>
                                                <span class="fw-bold"><?php echo htmlspecialchars($d['name']); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 fw-bold">
                                                TYPE: <?php echo $d['blood_group']; ?>
                                            </span>
                                        </td>
                                        <td><span class="text-muted small"><?php echo htmlspecialchars($d['phone']); ?></span></td>
                                        <td><span class="small"><?php echo htmlspecialchars($d['city']); ?></span></td>
                                        <td class="text-end pe-4">
                                            <a href="edit_donor.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-outline-light border-0"><i class="fas fa-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">No recent biological assets identified.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const bloodGroupChart = document.getElementById('bloodGroupChart');
    if (bloodGroupChart) {
        new Chart(bloodGroupChart, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($blood_group_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($blood_group_counts); ?>,
                    backgroundColor: ['#ef4444', '#f97316', '#facc15', '#22c55e', '#0ea5e9', '#6366f1', '#a855f7', '#14b8a6'],
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
