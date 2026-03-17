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

// 3. Recently Added (Last 3)
// Using JOIN to get type_name for the "Recently Added" display
$recent_media = $conn->query("SELECT i.*, t.type_name 
                             FROM inventory i 
                             JOIN media_type t ON i.type_id = t.id 
                             ORDER BY i.created_at DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="h3 mb-1 text-purple fw-bold">Executive Showcase</h1>
        <p class="text-muted small mb-0">High-fidelity media distribution metrics.</p>
    </div>
    <a href="add_item.php" class="btn btn-gold shadow-sm px-4">
        <i class="fas fa-plus-circle me-2"></i>Curate Discovery
    </a>
</div>

<div class="row">
    <!-- Stat Widgets -->
    <div class="col-md-4 mb-4">
        <div class="card stat-widget h-100 p-4 shadow-lg">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Stock Portfolio</div>
                    <div class="stat-value"><?php echo $total_items; ?></div>
                    <div class="small opacity-75 mt-2"><i class="fas fa-layer-group me-1"></i> Unique Volumes</div>
                </div>
                <i class="fas fa-boxes fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stat-widget h-100 p-4 shadow-lg" style="background: linear-gradient(135deg, #1a1a1a 0%, #343a40 100%); border: 1px solid var(--royal-gold);">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Dominant Genre</div>
                    <div class="stat-value"><?php echo htmlspecialchars($top_genre); ?></div>
                    <div class="small text-gold mt-2"><i class="fas fa-fire me-1"></i> Market Leader</div>
                </div>
                <i class="fas fa-music fa-3x opacity-25 text-gold"></i>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card stat-widget h-100 p-4 shadow-lg">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="stat-label">Platform Status</div>
                    <div class="stat-value">Live</div>
                    <div class="small opacity-75 mt-2"><i class="fas fa-signal me-1"></i> All Nodes Active</div>
                </div>
                <i class="fas fa-satellite-dish fa-3x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recently Added Feed -->
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="m-0 fw-bold text-purple"><i class="fas fa-history me-2"></i>LATEST CURATIONS</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Resource Title</th>
                                <th>Creator</th>
                                <th>Format</th>
                                <th>Industry</th>
                                <th class="text-end pe-4">Price Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($m = $recent_media->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 fw-bold">
                                        <i class="fas <?php echo ($m['type_id'] == 1 || $m['type_id'] == 4) ? 'fa-book-open' : 'fa-music'; ?> me-2 text-gold"></i>
                                        <?php echo htmlspecialchars($m['title']); ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($m['creator']); ?></td>
                                    <td><span class="badge bg-purple px-3"><?php echo $m['type_name']; ?></span></td>
                                    <td><span class="genre-tag"><?php echo $m['genre']; ?></span></td>
                                    <td class="text-end pe-4 fw-bold text-dark">$<?php echo number_format($m['price'], 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
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
