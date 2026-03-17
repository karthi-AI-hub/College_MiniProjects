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

// 5. Recent Vehicles
$recent_vehicles = $conn->query("SELECT v.*, r.route_name 
                                FROM vehicles v 
                                LEFT JOIN routes r ON v.route_id = r.id 
                                ORDER BY v.id DESC LIMIT 4");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Fleet Operations Dashboard</h1>
    <a href="add_vehicle.php" class="btn btn-yellow shadow-sm"><i class="fas fa-plus me-2"></i>Provision Vehicle</a>
</div>

<!-- Stats Indicators -->
<div class="row">
    <!-- Total Vehicles -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card card-fleet h-100 shadow-sm">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">Total Fleet Size</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $total_vehicles; ?> Units</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-truck fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Routes -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-left-info" style="border-left: 5px solid #0dcaf0;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Active Routes</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $total_routes; ?> Routes</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-map-marked-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maintenance -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-left-warning" style="border-left: 5px solid #ffc107;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">In Maintenance</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $maintenance_count; ?> Units</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tools fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Out of Service -->
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card h-100 shadow-sm border-left-danger" style="border-left: 5px solid #dc3545;">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col me-2">
                        <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Out of Service</div>
                        <div class="h4 mb-0 font-weight-bold text-gray-800"><?php echo $oos_count; ?> Units</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Fleet Overview Table -->
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-dark text-uppercase"><i class="fas fa-list me-2"></i>Recent Fleet Activities</h6>
                <a href="view_vehicles.php" class="btn btn-sm btn-outline-dark">View Entire Fleet</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-dark text-yellow">
                            <tr>
                                <th class="ps-4">Vehicle No</th>
                                <th>Driver</th>
                                <th>Assigned Route</th>
                                <th>Capacity</th>
                                <th>Status</th>
                                <th class="text-center pe-4">Quick View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_vehicles->num_rows > 0): ?>
                                <?php while($veh = $recent_vehicles->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-dark text-uppercase"><?php echo htmlspecialchars($veh['vehicle_no']); ?></td>
                                        <td><?php echo htmlspecialchars($veh['driver_name']); ?></td>
                                        <td><?php echo htmlspecialchars($veh['route_name'] ?? 'Unassigned'); ?></td>
                                        <td><?php echo $veh['capacity']; ?> Pax</td>
                                        <td>
                                            <?php 
                                            $badge = 'bg-success';
                                            if ($veh['status'] == 'Maintenance') $badge = 'bg-yellow text-dark';
                                            if ($veh['status'] == 'Out of Service') $badge = 'bg-danger';
                                            ?>
                                            <span class="badge <?php echo $badge; ?>"><?php echo $veh['status']; ?></span>
                                        </td>
                                        <td class="text-center pe-4">
                                            <a href="edit_vehicle.php?id=<?php echo $veh['id']; ?>" class="btn btn-sm btn-light border"><i class="fas fa-edit"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="6" class="text-center py-4 text-muted">No vehicles registered in fleet database.</td></tr>
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
