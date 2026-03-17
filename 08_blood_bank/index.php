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

// Quick Table: Recent Donors
$recent_donors = $conn->query("SELECT * FROM donors ORDER BY registered_at DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Emergency Command Center</h1>
    <a href="add_donor.php" class="btn btn-danger shadow-sm fw-bold"><i class="fas fa-user-plus me-2"></i>Register New Donor</a>
</div>

<!-- Quick Search Logic -->
<div class="card shadow-sm border-0 mb-4 bg-danger text-white">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-search me-2"></i>Instant Blood Finder</h5>
        <form action="view_donors.php" method="get" class="row g-2">
            <div class="col-md-5">
                <input type="text" name="search" class="form-control form-control-lg" placeholder="Search by City (e.g. London)...">
            </div>
            <div class="col-md-4">
                <select name="blood_group" class="form-select form-select-lg">
                    <option value="">Any Blood Group</option>
                    <option value="A+">A+</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B-">B-</option>
                    <option value="O+">O+</option>
                    <option value="O-">O-</option>
                    <option value="AB+">AB+</option>
                    <option value="AB-">AB-</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-light btn-lg w-100 fw-bold text-danger">SEARCH DONORS</button>
            </div>
        </form>
        <small class="mt-2 d-block opacity-75">Uses SQL <code>LIKE</code> logic for broad location matching.</small>
    </div>
</div>

<!-- Stats Indicators -->
<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100 shadow-sm border-0">
            <div class="card-body">
                <div class="stats-title">Total Registered</div>
                <div class="stats-number"><?php echo $total_donors; ?></div>
                <div class="small text-muted"><i class="fas fa-users me-1"></i> Lifetime data</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100 shadow-sm border-0" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="stats-title">New (30 Days)</div>
                <div class="stats-number"><?php echo $recent_count; ?></div>
                <div class="small text-warning"><i class="fas fa-user-clock me-1"></i> Active recruitment</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100 shadow-sm border-0" style="border-left-color: #198754;">
            <div class="card-body">
                <div class="stats-title">O+ Available</div>
                <div class="stats-number"><?php echo $o_plus; ?></div>
                <div class="small text-success"><i class="fas fa-tint me-1"></i> Primary Stock</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100 shadow-sm border-0" style="border-left-color: #0d6efd;">
            <div class="card-body">
                <div class="stats-title">O- (Critical)</div>
                <div class="stats-number"><?php echo $o_minus; ?></div>
                <div class="small text-primary"><i class="fas fa-shield-alt me-1"></i> Universal Donor</div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Recent Activity Table -->
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-clipboard-list me-2"></i>RECENTLY ADDED DONORS</h6>
                <a href="view_donors.php" class="btn btn-sm btn-outline-danger">Full Registry</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Donor Name</th>
                                <th>Group</th>
                                <th>Contact (Emergency)</th>
                                <th>Location</th>
                                <th class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_donors->num_rows > 0): ?>
                                <?php while($d = $recent_donors->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo htmlspecialchars($d['name']); ?></td>
                                        <td><span class="badge bg-danger p-2"><?php echo $d['blood_group']; ?></span></td>
                                        <td class="emergency-phone"><?php echo htmlspecialchars($d['phone']); ?></td>
                                        <td><?php echo htmlspecialchars($d['city']); ?></td>
                                        <td class="text-center pe-4">
                                            <a href="edit_donor.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-light border"><i class="fas fa-pen"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-4 text-muted">No donor data in registry.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
