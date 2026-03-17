<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch stats
$total_animals_query = "SELECT COUNT(*) as total FROM livestock";
$sick_animals_query = "SELECT COUNT(*) as sick FROM livestock WHERE health_status = 'Sick'";
$recent_animals_query = "SELECT COUNT(*) as recent FROM livestock WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";

$total_result = $conn->query($total_animals_query);
$sick_result = $conn->query($sick_animals_query);
$recent_result = $conn->query($recent_animals_query);

$total_animals = $total_result->fetch_assoc()['total'];
$sick_stats = $sick_result->fetch_assoc()['sick'];
$recent_stats = $recent_result->fetch_assoc()['recent'];
?>

<h1 class="mt-4 text-success"><i class="fas fa-tractor"></i> Farm Dashboard</h1>
<p class="text-muted">Overview of your livestock health and inventory.</p>

<div class="row mt-4">
    <!-- Total Animals Card -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title display-4 fw-bold"><?php echo $total_animals; ?></h5>
                        <p class="card-text">Total Animals</p>
                    </div>
                    <i class="fas fa-paw fa-4x opacity-50"></i>
                </div>
                <a href="view_animals.php" class="btn btn-light btn-sm mt-3 text-success fw-bold">View Inventory</a>
            </div>
        </div>
    </div>

    <!-- Sick Animals Card -->
    <div class="col-md-4 mb-4">
        <div class="card text-white bg-danger h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title display-4 fw-bold"><?php echo $sick_stats; ?></h5>
                        <p class="card-text">Sick Animals</p>
                    </div>
                    <i class="fas fa-notes-medical fa-4x opacity-50"></i>
                </div>
                <a href="view_animals.php?filter=Sick" class="btn btn-light btn-sm mt-3 text-danger fw-bold">View Sick List</a>
            </div>
        </div>
    </div>

    <!-- Recent Additions Card -->
    <div class="col-md-4 mb-4">
        <div class="card text-dark bg-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title display-4 fw-bold"><?php echo $recent_stats; ?></h5>
                        <p class="card-text">Added This Week</p>
                    </div>
                    <i class="fas fa-clock fa-4x opacity-50"></i>
                </div>
                <!-- <a href="#" class="btn btn-light btn-sm mt-3 text-warning fw-bold">View Recent</a> -->
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
