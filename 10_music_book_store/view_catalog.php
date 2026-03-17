<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Search and Filter Logic
$search = isset($_GET['search']) ? $_GET['search'] : '';
$type_id = isset($_GET['type_id']) ? $_GET['type_id'] : '';

$conditions = [];
if ($search) {
    $conditions[] = "(i.title LIKE '%" . $conn->real_escape_string($search) . "%' OR i.creator LIKE '%" . $conn->real_escape_string($search) . "%')";
}
if ($type_id) {
    $conditions[] = "i.type_id = " . (int)$type_id;
}

$where_sql = "";
if (count($conditions) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $conditions);
}

// JOIN Query Explanation (Viva Highlight):
/* 
   We use JOIN inventory (i) with media_type (t) ON type_id.
   This allows us to fetch the human-readable 'type_name' (e.g., E-Book)
   instead of just the numeric ID, which is essential for normalized databases.
*/
$query = "SELECT i.*, t.type_name 
          FROM inventory i 
          JOIN media_type t ON i.type_id = t.id 
          $where_sql 
          ORDER BY i.title ASC";
$res = $conn->query($query);

// Fetch Types for Filter
$types = $conn->query("SELECT * FROM media_type ORDER BY type_name ASC");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-purple fw-bold">Master Catalog Registry</h1>
    <a href="add.php" class="btn btn-gold shadow-sm fw-bold">
        <i class="fas fa-plus me-1"></i>New Media Entry
    </a>
</div>

<!-- Search & Filter Card -->
<div class="card border-0 shadow-sm rounded-4 mb-5">
    <div class="card-body p-4">
        <form action="" method="get" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-bold text-muted">Direct Search</label>
                <input type="text" name="search" class="form-control" placeholder="Title or Artist/Author..." value="<?php echo htmlspecialchars($search); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Resource Type</label>
                <select name="type_id" class="form-select">
                    <option value="">All Formats</option>
                    <?php while($t = $types->fetch_assoc()): ?>
                        <option value="<?php echo $t['id']; ?>" <?php echo ($type_id == $t['id']) ? 'selected' : ''; ?>>
                            <?php echo $t['type_name']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-purple text-white w-100 fw-bold">Refresh Results</button>
                <a href="view_catalog.php" class="btn btn-light border w-50">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Card-Based Catalog UI -->
<div class="row">
    <?php if ($res->num_rows > 0): ?>
        <?php while($item = $res->fetch_assoc()): ?>
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="media-card h-100">
                    <div class="media-type-icon">
                        <?php 
                        // Logic to show icon based on media type
                        $icon = 'fa-file-alt';
                        if ($item['type_id'] == 1 || $item['type_id'] == 4) $icon = 'fa-book-open';
                        if ($item['type_id'] == 2 || $item['type_id'] == 3) $icon = 'fa-music';
                        ?>
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <span class="genre-tag"><?php echo htmlspecialchars($item['genre']); ?></span>
                            <span class="fw-bold text-purple small">$<?php echo number_format($item['price'], 2); ?></span>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 line-clamp-1"><?php echo htmlspecialchars($item['title']); ?></h6>
                        <p class="text-muted small mb-3"><?php echo htmlspecialchars($item['creator']); ?> | <?php echo $item['release_year']; ?></p>
                        
                        <div class="d-flex justify-content-between border-top pt-3">
                            <span class="badge bg-purple bg-opacity-10 text-purple fw-normal py-2"><?php echo $item['type_name']; ?></span>
                            <div class="btn-group">
                                <a href="edit.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-purple"><i class="fas fa-edit"></i></a>
                                <a href="delete.php?id=<?php echo $item['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Decommission this asset?')"><i class="fas fa-trash-alt"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="col-12 text-center py-5">
            <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">No media assets identified in current catalog node.</h5>
        </div>
    <?php endif; ?>
</div>

<style>
.bg-purple { background-color: #4b0082 !important; }
.text-purple { color: #4b0082 !important; }
.btn-purple { background-color: #4b0082; border-color: #4b0082; }
.btn-outline-purple { color: #4b0082; border-color: #4b0082; }
.btn-outline-purple:hover { background-color: #4b0082; color: #fff; }
.line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
</style>

<?php
include 'includes/footer.php';
?>
