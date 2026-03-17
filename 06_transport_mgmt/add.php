<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

// Fetch Routes for Dropdown
$routes = $conn->query("SELECT id, route_name FROM routes ORDER BY route_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_no = strtoupper(trim($_POST['vehicle_no']));
    $model = trim($_POST['model']);
    $driver_name = trim($_POST['driver_name']);
    $route_id = $_POST['route_id'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    if (empty($vehicle_no) || empty($model) || empty($driver_name) || empty($capacity)) {
        $error = "Detailed vehicle technical data required.";
    } else {
        // Check Duplicate Vehicle No
        $check = $conn->prepare("SELECT id FROM vehicles WHERE vehicle_no = ?");
        $check->bind_param("s", $vehicle_no);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Vehicle registration number [$vehicle_no] already exists in fleet.";
        } else {
            $stmt = $conn->prepare("INSERT INTO vehicles (vehicle_no, model, driver_name, route_id, capacity, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiss", $vehicle_no, $model, $driver_name, $route_id, $capacity, $status);
            
            if ($stmt->execute()) {
                $message = "Vehicle $vehicle_no provisioned successfully.";
            } else {
                $error = "Critical System Error: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Provision New Vehicle</h1>
    <a href="view_vehicles.php" class="btn btn-outline-dark shadow-sm"><i class="fas fa-arrow-left me-2"></i>Back to Fleet</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="fas fa-check-circle me-2"></i> <?php echo $message; ?>
    </div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4">
        <i class="fas fa-exclamation-circle me-2"></i> <?php echo $error; ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Vehicle Registration No <span class="text-danger">*</span></label>
                    <input type="text" name="vehicle_no" class="form-control form-control-lg text-uppercase fw-bold" placeholder="e.g. TN-01-AB-1234" required>
                    <small class="text-muted">Display format: AA-00-AA-0000</small>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Vehicle Model <span class="text-danger">*</span></label>
                    <input type="text" name="model" class="form-control" placeholder="e.g. Tata Marcopolo" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Assigned Driver <span class="text-danger">*</span></label>
                    <input type="text" name="driver_name" class="form-control" placeholder="Full Name" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Assigned Route <span class="text-danger">*</span></label>
                    <select name="route_id" class="form-select" required>
                        <option value="">Select Operational Route</option>
                        <?php while($r = $routes->fetch_assoc()): ?>
                            <option value="<?php echo $r['id']; ?>"><?php echo htmlspecialchars($r['route_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Seating Capacity <span class="text-danger">*</span></label>
                    <input type="number" name="capacity" class="form-control" placeholder="e.g. 50" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Initial Status</label>
                    <select name="status" class="form-select">
                        <option value="Active">Active / Operational</option>
                        <option value="Maintenance">Maintenance / Service</option>
                        <option value="Out of Service">Out of Service</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top d-flex gap-2">
                <button type="submit" class="btn btn-yellow btn-lg px-5">Provision Unit</button>
                <button type="reset" class="btn btn-light btn-lg px-4 border">Reset Form</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
