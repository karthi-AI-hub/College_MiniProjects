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
$healthy_animals = max(0, $total_animals - $sick_stats);
$sick_rate = ($total_animals > 0) ? round(($sick_stats / $total_animals) * 100, 1) : 0;

$category_stats = $conn->query("SELECT categories.category_name, COUNT(livestock.id) as total
                               FROM categories
                               LEFT JOIN livestock ON livestock.category_id = categories.id
                               GROUP BY categories.id
                               ORDER BY total DESC");

$category_labels = [];
$category_counts = [];
while ($row = $category_stats->fetch_assoc()) {
    $category_labels[] = $row['category_name'];
    $category_counts[] = (int)$row['total'];
}

$top_category = $category_labels[0] ?? 'N/A';
$top_category_count = $category_counts[0] ?? 0;
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Livestock Dashboard</h1>
            <p class="text-muted small mb-0">Overview of livestock health and inventory.</p>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <!-- Total Animals -->
        <div class="col-lg-4 mb-4">
            <div class="card p-5 border-0 text-center">
                <div class="text-muted small text-uppercase fw-600 tracking-widest mb-3">Total Inventory</div>
                <div class="stats-number mb-4"><?php echo $total_animals; ?></div>
                <div class="d-flex gap-2">
                    <a href="view_animals.php" class="btn btn-primary flex-grow-1 py-3 text-uppercase fw-700 tracking-widest" style="font-size: 0.7rem;">Registry</a>
                </div>
            </div>
        </div>

        <!-- Sick Animals -->
        <div class="col-lg-4 mb-4">
            <div class="card p-5 border-0 text-center">
                <div class="text-muted small text-uppercase fw-600 tracking-widest mb-3">Health Alerts</div>
                <div class="stats-number mb-4 text-danger"><?php echo $sick_stats; ?></div>
                <div class="d-flex gap-2">
                    <a href="view_animals.php?filter=Sick" class="btn btn-outline-danger flex-grow-1 py-3 text-uppercase fw-700 tracking-widest" style="font-size: 0.7rem;">View Sick List</a>
                </div>
            </div>
        </div>

        <!-- Recent Additions -->
        <div class="col-lg-4 mb-4">
            <div class="card p-5 border-0 text-center">
                <div class="text-muted small text-uppercase fw-600 tracking-widest mb-3">New Registrations</div>
                <div class="stats-number mb-4"><?php echo $recent_stats; ?></div>
                <div class="mt-auto">
                    <span class="badge bg-light text-muted p-2 w-100 border">Active Tracking</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-600 mb-0">Animal Categories</h6>
                    <span class="text-muted small">Category Mix</span>
                </div>
                <canvas id="livestockCategoryChart" height="220"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-600 mb-0">Quick Insights</h6>
                    <span class="text-muted small">Snapshot</span>
                </div>
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Healthy Animals</div>
                            <div class="h4 mb-0 fw-bold text-success"><?php echo $healthy_animals; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Sick Rate</div>
                            <div class="h4 mb-0 fw-bold text-danger"><?php echo $sick_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Top Category</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($top_category); ?></div>
                            <div class="text-muted small"><?php echo $top_category_count; ?> animals</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">New (7 Days)</div>
                            <div class="h4 mb-0 fw-bold text-primary"><?php echo $recent_stats; ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const livestockCategoryChart = document.getElementById('livestockCategoryChart');
    if (livestockCategoryChart) {
        new Chart(livestockCategoryChart, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($category_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($category_counts); ?>,
                    backgroundColor: ['#4f46e5', '#38bdf8', '#22c55e', '#f59e0b', '#ef4444', '#a855f7', '#0ea5e9', '#14b8a6'],
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
