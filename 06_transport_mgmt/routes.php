<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";

// Simple Route Add Logic (Modal-style form handling)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_route'])) {
    $name = trim($_POST['route_name']);
    $start = trim($_POST['start_point']);
    $end = trim($_POST['end_point']);
    $dist = $_POST['distance_km'];

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO routes (route_name, start_point, end_point, distance_km) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssd", $name, $start, $end, $dist);
        if ($stmt->execute()) {
            $message = "New route $name established.";
        }
        $stmt->close();
    }
}

// Fetch Routes
$res = $conn->query("SELECT * FROM routes ORDER BY route_name ASC");
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Route Directory</h1>
    <button type="button" class="btn btn-yellow shadow-sm" data-bs-toggle="modal" data-bs-target="#addRouteModal">
        <i class="fas fa-map-marked-alt me-2"></i>Map New Route
    </button>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm"><?php echo $message; ?></div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-yellow">
                    <tr>
                        <th class="ps-4">Route Designation</th>
                        <th>Path (Start <i class="fas fa-long-arrow-alt-right"></i> End)</th>
                        <th>Distance</th>
                        <th class="text-center pe-4">Units Assigned</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($row = $res->fetch_assoc()): ?>
                            <?php 
                            // Count vehicles on this route
                            $rid = $row['id'];
                            $count_res = $conn->query("SELECT COUNT(*) as vcount FROM vehicles WHERE route_id = $rid");
                            $vcount = $count_res->fetch_assoc()['vcount'];
                            ?>
                            <tr>
                                <td class="ps-4 fw-bold"><?php echo htmlspecialchars($row['route_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['start_point']); ?> <span class="text-muted">to</span> <?php echo htmlspecialchars($row['end_point']); ?></td>
                                <td><span class="badge bg-light text-dark border"><?php echo $row['distance_km']; ?> KM</span></td>
                                <td class="text-center pe-4">
                                    <span class="fw-bold fs-5 text-yellow bg-dark px-3 rounded-pill"><?php echo $vcount; ?></span>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No operational routes defined.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Route Modal -->
<div class="modal fade" id="addRouteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="" method="post">
                <div class="modal-header bg-dark text-yellow">
                    <h5 class="modal-title fw-bold">Establish New Route</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Route Name</label>
                        <input type="text" name="route_name" class="form-control" required placeholder="e.g. North Campus Express">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Starting Point</label>
                        <input type="text" name="start_point" class="form-control" required placeholder="Point A">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Destination</label>
                        <input type="text" name="end_point" class="form-control" required placeholder="Point B">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Distance (KM)</label>
                        <input type="number" step="0.1" name="distance_km" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_route" class="btn btn-yellow">Save Path</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
