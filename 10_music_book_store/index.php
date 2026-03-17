<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Media Statistics
// 1. Total Items
$total_res = $conn->query("SELECT COUNT(*) as total FROM inventory");
$total_items = $total_res->fetch_assoc()['total'];

// 2. Top Genre (Calculation Logic)
$genre_res = $conn->query("SELECT genre, COUNT(*) as count FROM inventory GROUP BY genre ORDER BY count DESC LIMIT 1");
$top_genre = ($genre_res->num_rows > 0) ? $genre_res->fetch_assoc()['genre'] : 'None';

$avg_price_res = $conn->query("SELECT AVG(price) as avg_price FROM inventory");
$avg_price = $avg_price_res->fetch_assoc()['avg_price'] ?? 0;

$recent_items_res = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$recent_items = $recent_items_res->fetch_assoc()['total'] ?? 0;

$top_genre_count = 0;
if ($top_genre !== 'None') {
    $top_genre_count_res = $conn->query("SELECT COUNT(*) as total FROM inventory WHERE genre = '" . $conn->real_escape_string($top_genre) . "'");
    $top_genre_count = $top_genre_count_res->fetch_assoc()['total'] ?? 0;
}
$top_genre_share = ($total_items > 0) ? round(($top_genre_count / $total_items) * 100, 1) : 0;

// 3. Recently Added (Last 3)
// Using JOIN to get type_name for the "Recently Added" display
$recent_media = $conn->query("SELECT i.*, t.type_name 
                             FROM inventory i 
                             JOIN media_type t ON i.type_id = t.id 
                             ORDER BY i.created_at DESC LIMIT 5");

$genre_mix = $conn->query("SELECT genre, COUNT(*) as total FROM inventory GROUP BY genre ORDER BY total DESC");
$genre_labels = [];
$genre_counts = [];
while ($row = $genre_mix->fetch_assoc()) {
    $genre_labels[] = $row['genre'];
    $genre_counts[] = (int)$row['total'];
}

?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="h3 mb-1 fw-bold">Portfolio Intelligence</h1>
        <p class="text-muted small mb-0">High-fidelity media distribution metrics</p>
    </div>
    <a href="add_item.php" class="btn btn-gold shadow-sm"><i class="fas fa-plus me-2"></i>Curate Discovery</a>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-gold">Genre Mix</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="genreChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0 text-gold">Insight Highlights</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Avg Price</div>
                            <div class="h4 mb-0 fw-bold text-warning">$<?php echo number_format($avg_price, 2); ?></div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">New (30 Days)</div>
                            <div class="h4 mb-0 fw-bold text-success"><?php echo $recent_items; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Top Genre Share</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($top_genre); ?></div>
                            <div class="text-muted small"><?php echo $top_genre_share; ?>% of catalog</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <!-- Stock Portfolio -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon me-3">
                        <i class="fas fa-boxes text-gold"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-bold">Stock Portfolio</span>
                </div>
                <h2 class="stats-number mb-0"><?php echo $total_items; ?> <span class="fs-6 fw-normal text-muted">Volumes</span></h2>
            </div>
        </div>
    </div>

    <!-- Dominant Genre -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-warning">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon me-3 bg-warning bg-opacity-10 text-warning">
                        <i class="fas fa-fire"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-bold">Dominant Genre</span>
                </div>
                <h2 class="stats-number mb-0 text-warning"><?php echo htmlspecialchars($top_genre); ?></h2>
            </div>
        </div>
    </div>

    <!-- Platform Status -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-info">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon me-3 bg-info bg-opacity-10 text-info">
                        <i class="fas fa-satellite-dish"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-bold">Platform Status</span>
                </div>
                <h2 class="stats-number mb-0 text-info">ACTIVE <span class="fs-6 fw-normal text-muted">LIVE</span></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Latest Curations -->
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0 text-gold">Latest Curations</h5>
                <a href="view_catalog.php" class="btn btn-sm btn-outline-light border-0">Full Catalog</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Resource Identity</th>
                                <th>Curator</th>
                                <th>Format</th>
                                <th>Industry</th>
                                <th class="text-end pe-4">Credit Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_media->num_rows > 0): ?>
                                <?php while($m = $recent_media->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-gold bg-opacity-10 rounded-circle p-2 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fas <?php echo ($m['type_id'] == 1 || $m['type_id'] == 4) ? 'fa-book-open' : 'fa-music'; ?> text-gold"></i>
                                                </div>
                                                <span class="fw-bold"><?php echo htmlspecialchars($m['title']); ?></span>
                                            </div>
                                        </td>
                                        <td><span class="text-muted small"><?php echo htmlspecialchars($m['creator']); ?></span></td>
                                        <td>
                                            <span class="badge bg-gold bg-opacity-10 text-gold border border-gold border-opacity-25 px-3 py-2 fw-bold">
                                                <?php echo $m['type_name']; ?>
                                            </span>
                                        </td>
                                        <td><span class="genre-tag"><?php echo $m['genre']; ?></span></td>
                                        <td class="text-end pe-4 fw-bold text-warning">$<?php echo number_format($m['price'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="5" class="text-center py-5 text-muted">No curated content found in index.</td></tr>
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
    const genreChart = document.getElementById('genreChart');
    if (genreChart) {
        new Chart(genreChart, {
            type: 'doughnut',
            data: {
                labels: <?php echo json_encode($genre_labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($genre_counts); ?>,
                    backgroundColor: ['#f59e0b', '#f97316', '#38bdf8', '#22c55e', '#a855f7', '#ef4444', '#0ea5e9'],
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
