<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Fleet Statistics
// 1. Total Vehicles
$total_veh_res = $conn->query("SELECT COUNT(*) as total FROM vehicles");
$total_vehicles = $total_veh_res->fetch_assoc()['total'];

// 2. Active Routes
$route_res = $conn->query("SELECT COUNT(*) as total FROM routes");
$total_routes = $route_res->fetch_assoc()['total'];

// 3. Vehicles in Maintenance
$maint_res = $conn->query("SELECT COUNT(*) as total FROM vehicles WHERE status = 'Maintenance'");
$maintenance_count = $maint_res->fetch_assoc()['total'];

// 4. Out of Service
$oos_res = $conn->query("SELECT COUNT(*) as total FROM vehicles WHERE status = 'Out of Service'");
$oos_count = $oos_res->fetch_assoc()['total'];
$active_vehicles = max(0, $total_vehicles - $maintenance_count - $oos_count);
$active_rate = ($total_vehicles > 0) ? round(($active_vehicles / $total_vehicles) * 100, 1) : 0;
$maintenance_rate = ($total_vehicles > 0) ? round(($maintenance_count / $total_vehicles) * 100, 1) : 0;

$top_route_res = $conn->query("SELECT r.route_name, COUNT(v.id) as total FROM routes r LEFT JOIN vehicles v ON v.route_id = r.id GROUP BY r.id ORDER BY total DESC LIMIT 1");
$top_route = ($top_route_res && $top_route_res->num_rows > 0) ? $top_route_res->fetch_assoc() : ['route_name' => 'N/A', 'total' => 0];

// 5. Recent Vehicles
$recent_vehicles = $conn->query("SELECT v.*, r.route_name 
                                FROM vehicles v 
                                LEFT JOIN routes r ON v.route_id = r.id 
                                ORDER BY v.id DESC LIMIT 4");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Operations Hub</h1>
    <a href="add_vehicle.php" class="btn btn-yellow shadow-sm"><i class="fas fa-plus me-2"></i>Provision Vehicle</a>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Fleet Status</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="fleetStatusChart" height="220"></canvas>
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
                            <div class="text-muted small text-uppercase fw-600">Active Fleet</div>
                            <div class="h4 mb-0 fw-bold text-success"><?php echo $active_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Maintenance Rate</div>
                            <div class="h4 mb-0 fw-bold text-warning"><?php echo $maintenance_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Top Route</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($top_route['route_name']); ?></div>
                            <div class="text-muted small"><?php echo (int)$top_route['total']; ?> assigned vehicles</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <!-- Fleet Size Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-yellow bg-opacity-10 p-3 me-3">
                        <i class="fas fa-truck text-yellow fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">Fleet Units</h5>
                </div>
                <h2 class="display-6 fw-bold mb-1"><?php echo $total_vehicles; ?></h2>
                <p class="text-muted small mb-0">Total Registered</p>
            </div>
        </div>
    </div>

    <!-- Active Routes Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-info bg-opacity-10 p-3 me-3">
                        <i class="fas fa-map-marked-alt text-info fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">Active Routes</h5>
                </div>
                <h2 class="display-6 fw-bold mb-1"><?php echo $total_routes; ?></h2>
                <p class="text-muted small mb-0">Service Coverage</p>
            </div>
        </div>
    </div>

    <!-- Maintenance Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">
                        <i class="fas fa-tools text-warning fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">Maintenance</h5>
                </div>
                <h2 class="display-6 fw-bold mb-1 text-warning"><?php echo $maintenance_count; ?></h2>
                <p class="text-muted small mb-0">Units in Workshop</p>
            </div>
        </div>
    </div>

    <!-- Out of Service Card -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3 me-3">
                        <i class="fas fa-exclamation-triangle text-danger fs-3"></i>
                    </div>
                    <h5 class="card-title mb-0">Out of Service</h5>
                </div>
                <h2 class="display-6 fw-bold mb-1 text-danger"><?php echo $oos_count; ?></h2>
                <p class="text-muted small mb-0">Critical Attention</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Fleet Activities</h5>
                <a href="view_vehicles.php" class="btn btn-sm btn-outline-light border-0">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Vehicle No</th>
                                <th>Driver</th>
                                <th>Assigned Route</th>
                                <th>Status</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_vehicles->num_rows > 0): ?>
                                <?php while($veh = $recent_vehicles->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-yellow bg-opacity-10 p-2 rounded me-3">
                                                    <i class="fas fa-shuttle-van text-yellow"></i>
                                                </div>
                                                <span class="fw-bold"><?php echo htmlspecialchars($veh['vehicle_no']); ?></span>
                                            </div>
                                        </td>
                                        <td><?php echo htmlspecialchars($veh['driver_name']); ?></td>
                                        <td><span class="text-muted small"><?php echo htmlspecialchars($veh['route_name'] ?? 'Unassigned'); ?></span></td>
                                        <td>
                                            <?php 
                                            $color = 'success';
                                            if ($veh['status'] == 'Maintenance') $color = 'warning';
                                            if ($veh['status'] == 'Out of Service') $color = 'danger';
                                            ?>
                                            <span class="badge bg-<?php echo $color; ?> bg-opacity-10 text-<?php echo $color; ?> border border-<?php echo $color; ?> border-opacity-25 px-3 py-2">
                                                <?php echo $veh['status']; ?>
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="edit_vehicle.php?id=<?php echo $veh['id']; ?>" class="btn btn-sm btn-outline-light border-0"><i class="fas fa-chevron-right"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">No fleet activity recorded.</td></tr>
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
    const fleetStatusChart = document.getElementById('fleetStatusChart');
    if (fleetStatusChart) {
        new Chart(fleetStatusChart, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Maintenance', 'Out of Service'],
                datasets: [{
                    data: [<?php echo (int)$active_vehicles; ?>, <?php echo (int)$maintenance_count; ?>, <?php echo (int)$oos_count; ?>],
                    backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
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
