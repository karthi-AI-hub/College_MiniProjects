<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $veh = $result->fetch_assoc();
    $stmt->close();

    if (!$veh) {
        die("Vehicle unit not found in database.");
    }
} else {
    header("Location: view_vehicles.php");
    exit();
}

// Fetch Routes
$routes = $conn->query("SELECT id, route_name FROM routes ORDER BY route_name ASC");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $vehicle_no = strtoupper(trim($_POST['vehicle_no']));
    $model = trim($_POST['model']);
    $driver_name = trim($_POST['driver_name']);
    $route_id = $_POST['route_id'];
    $capacity = $_POST['capacity'];
    $status = $_POST['status'];

    if (empty($vehicle_no) || empty($model) || empty($driver_name)) {
        $error = "Essential technical fields cannot be empty.";
    } else {
        // Check Duplicate (excluding self)
        $check = $conn->prepare("SELECT id FROM vehicles WHERE vehicle_no = ? AND id != ?");
        $check->bind_param("si", $vehicle_no, $id);
        $check->execute();
        $check->store_result();
        
        if ($check->num_rows > 0) {
            $error = "Registration number $vehicle_no is locked by another fleet unit.";
        } else {
            $stmt = $conn->prepare("UPDATE vehicles SET vehicle_no=?, model=?, driver_name=?, route_id=?, capacity=?, status=? WHERE id=?");
            $stmt->bind_param("ssisssi", $vehicle_no, $model, $driver_name, $route_id, $capacity, $status, $id);
            
            if ($stmt->execute()) {
                $message = "Unit $vehicle_no specifications updated successfully.";
                // Refresh data
                $veh['vehicle_no'] = $vehicle_no;
                $veh['model'] = $model;
                $veh['driver_name'] = $driver_name;
                $veh['route_id'] = $route_id;
                $veh['capacity'] = $capacity;
                $veh['status'] = $status;
            } else {
                $error = "Error updating unit: " . $conn->error;
            }
            $stmt->close();
        }
        $check->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Modify Vehicle: <span class="text-yellow"><?php echo $veh['vehicle_no']; ?></span></h1>
    <a href="view_vehicles.php" class="btn btn-outline-dark shadow-sm"><i class="fas fa-arrow-left me-2"></i>Fleet Registry</a>
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
                    <label class="form-label fw-bold">Vehicle Registration No</label>
                    <input type="text" name="vehicle_no" class="form-control form-control-lg text-uppercase fw-bold text-yellow bg-dark" value="<?php echo htmlspecialchars($veh['vehicle_no']); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Vehicle Model</label>
                    <input type="text" name="model" class="form-control" value="<?php echo htmlspecialchars($veh['model']); ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Driver Name</label>
                    <input type="text" name="driver_name" class="form-control" value="<?php echo htmlspecialchars($veh['driver_name']); ?>" required>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Current Route</label>
                    <select name="route_id" class="form-select">
                        <option value="">Unassigned / Maintenance</option>
                        <?php 
                        $routes->data_seek(0);
                        while($r = $routes->fetch_assoc()): ?>
                            <option value="<?php echo $r['id']; ?>" <?php echo ($veh['route_id'] == $r['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($r['route_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Capacity</label>
                    <input type="number" name="capacity" class="form-control" value="<?php echo $veh['capacity']; ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Operational Status</label>
                    <select name="status" class="form-select fw-bold">
                        <option value="Active" <?php echo ($veh['status'] == 'Active') ? 'selected' : ''; ?>>Active / Operational</option>
                        <option value="Maintenance" <?php echo ($veh['status'] == 'Maintenance') ? 'selected' : ''; ?>>In Maintenance</option>
                        <option value="Out of Service" <?php echo ($veh['status'] == 'Out of Service') ? 'selected' : ''; ?>>Out of Service</option>
                    </select>
                </div>
            </div>

            <div class="mt-4 pt-3 border-top">
                <button type="submit" class="btn btn-yellow btn-lg px-5 shadow-sm">Commit Changes</button>
            </div>
        </form>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
