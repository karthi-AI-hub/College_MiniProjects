<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Storefront View: Show all media as an attractive grid
$query = "SELECT i.*, t.type_name 
          FROM inventory i 
          JOIN media_type t ON i.type_id = t.id 
          ORDER BY i.created_at DESC";
$res = $conn->query($query);

?>

<div class="bg-purple p-5 rounded-5 mb-5 text-center text-white shadow-lg banner-mesh">
    <h1 class="display-3 fw-bold text-gold">Infinity<span class="text-white">Media</span></h1>
    <p class="h4 opacity-75 mb-4">The ultimate destination for premium digital books and music.</p>
    <div class="d-flex justify-content-center gap-3">
        <a href="#store-grid" class="btn btn-gold btn-lg px-5 py-3 fw-bold">EXPLORE COLLECTION</a>
        <a href="view_catalog.php" class="btn btn-outline-light btn-lg px-4 py-3">ADMIN ACCESS</a>
    </div>
</div>

<div id="store-grid" class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-purple"><i class="fas fa-shopping-bag me-3"></i>Trending Collections</h2>
        <span class="text-muted small">Global Release Inventory</span>
    </div>

    <div class="row">
        <?php while($item = $res->fetch_assoc()): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card border-0 shadow-sm h-100 p-0 rounded-4 overflow-hidden storefront-card">
                    <div class="media-type-icon" style="height: 180px; background: #e0e0e0; color: #4b0082;">
                        <?php 
                        $icon = 'fa-file-alt';
                        if ($item['type_id'] == 1 || $item['type_id'] == 4) $icon = 'fa-book-open';
                        if ($item['type_id'] == 2 || $item['type_id'] == 3) $icon = 'fa-music';
                        ?>
                        <i class="fas <?php echo $icon; ?> fa-2x"></i>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-2">
                             <span class="badge bg-purple px-2 py-1 small"><?php echo $item['type_name']; ?></span>
                             <span class="text-muted small"><?php echo $item['genre']; ?></span>
                        </div>
                        <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($item['title']); ?></h5>
                        <p class="text-muted small mb-3">By <span class="text-purple fw-bold"><?php echo htmlspecialchars($item['creator']); ?></span></p>
                        <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                            <span class="h4 mb-0 fw-bold text-purple">$<?php echo number_format($item['price'], 2); ?></span>
                            <button class="btn btn-gold btn-sm px-3 fw-bold" onclick="alert('Inducting asset into your digital library...')">BUY NOW</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<style>
.banner-mesh {
    background: linear-gradient(135deg, #4b0082 0%, #1a1a1a 100%),
                radial-gradient(circle at top right, rgba(255,215,0,0.1), transparent);
    border: 2px solid var(--royal-gold);
}
.storefront-card {
    transition: all 0.4s ease;
}
.storefront-card:hover {
    transform: scale(1.03);
    box-shadow: 0 1rem 3rem rgba(75, 0, 130, 0.15) !important;
}
</style>

<?php
include 'includes/footer.php';
?>
