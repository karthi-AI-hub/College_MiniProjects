<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch all vehicles with route info
$query = "SELECT v.*, r.route_name 
          FROM vehicles v 
          LEFT JOIN routes r ON v.route_id = r.id 
          ORDER BY v.status ASC, v.vehicle_no ASC";
$res = $conn->query($query);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Fleet Registry</h1>
    <a href="add.php" class="btn btn-yellow shadow-sm"><i class="fas fa-bus-alt me-2"></i>Add vehicle</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-yellow">
                    <tr>
                        <th class="ps-4">Vehicle Number</th>
                        <th>Model</th>
                        <th>Driver</th>
                        <th>Route Name</th>
                        <th>Capacity</th>
                        <th>Current Status</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($veh = $res->fetch_assoc()): ?>
                            <tr class="<?php echo ($veh['status'] == 'Out of Service') ? 'table-light' : ''; ?>">
                                <td class="ps-4 fw-bold text-dark text-uppercase fs-5"><?php echo htmlspecialchars($veh['vehicle_no']); ?></td>
                                <td><?php echo htmlspecialchars($veh['model']); ?></td>
                                <td><?php echo htmlspecialchars($veh['driver_name']); ?></td>
                                <td><i class="fas fa-route text-muted me-1"></i> <?php echo htmlspecialchars($veh['route_name'] ?? 'NULL/Unassigned'); ?></td>
                                <td><?php echo $veh['capacity']; ?> Seats</td>
                                <td>
                                    <?php 
                                    $badge = 'bg-success';
                                    if ($veh['status'] == 'Maintenance') $badge = 'bg-yellow text-dark';
                                    if ($veh['status'] == 'Out of Service') $badge = 'bg-danger';
                                    ?>
                                    <span class="badge <?php echo $badge; ?> p-2 px-3"><?php echo strtoupper($veh['status']); ?></span>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="edit.php?id=<?php echo $veh['id']; ?>" class="btn btn-sm btn-light" title="Modify"><i class="fas fa-tools"></i></a>
                                        <a href="delete.php?id=<?php echo $veh['id']; ?>" class="btn btn-sm btn-light text-danger" title="Decommission" onclick="return confirm('Archive this vehicle permanently?')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">Fleet is empty. Start provisioning vehicles.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
