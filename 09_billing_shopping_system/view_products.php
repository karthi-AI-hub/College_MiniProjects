<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Search and Category Filter
$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$conditions = [];
if ($search) {
    $conditions[] = "(product_name LIKE '%" . $conn->real_escape_string($search) . "%' OR category LIKE '%" . $conn->real_escape_string($search) . "%')";
}
if ($category) {
    $conditions[] = "category = '" . $conn->real_escape_string($category) . "'";
}

$where_sql = "";
if (count($conditions) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $conditions);
}

// Fetch all products
$query = "SELECT * FROM products $where_sql ORDER BY product_name ASC";
$res = $conn->query($query);

// Fetch categories for dropdown
$cat_res = $conn->query("SELECT DISTINCT category FROM products WHERE category IS NOT NULL AND category != ''");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Active Inventory Registry</h1>
    <a href="add.php" class="btn btn-teal shadow-sm fw-bold">
        <i class="fas fa-plus-circle me-1"></i>Add New Product
    </a>
</div>

<!-- Search & Filter Area -->
<div class="card card-retail border-0 mb-4">
    <div class="card-body">
        <form action="" method="get" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label small fw-bold text-muted">Quick Search</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fas fa-search text-teal opacity-50"></i></span>
                    <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Product name or SKU info..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold text-muted">Category Segment</label>
                <select name="category" class="form-select">
                    <option value="">All Segments</option>
                    <?php while($c = $cat_res->fetch_assoc()): ?>
                        <option value="<?php echo htmlspecialchars($c['category']); ?>" <?php echo ($category == $c['category']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($c['category']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-dark px-4 flex-grow-1">Apply Filters</button>
                <a href="view_products.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card card-retail border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4 py-3">Product Name</th>
                        <th class="py-3">Category</th>
                        <th class="py-3">Unit Price</th>
                        <th class="py-3">Stock Units</th>
                        <th class="text-center py-3">Status</th>
                        <th class="text-center py-3 pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($p = $res->fetch_assoc()): ?>
                            <tr class="<?php echo ($p['stock_quantity'] < 10) ? 'table-warning bg-opacity-10' : ''; ?>">
                                <td class="ps-4 fw-bold"><?php echo htmlspecialchars($p['product_name']); ?></td>
                                <td><span class="badge bg-light text-dark fw-normal border"><?php echo htmlspecialchars($p['category']); ?></span></td>
                                <td class="fw-semibold text-teal">$<?php echo number_format($p['price'], 2); ?></td>
                                <td class="<?php echo ($p['stock_quantity'] < 10) ? 'stock-low' : ''; ?>">
                                    <?php echo $p['stock_quantity']; ?> Units
                                </td>
                                <td class="text-center">
                                    <?php if ($p['stock_quantity'] < 10): ?>
                                        <span class="stock-badge-low"><i class="fas fa-arrow-down me-1"></i> LOW STOCK</span>
                                    <?php else: ?>
                                        <span class="badge bg-success bg-opacity-10 text-success fw-normal px-3 py-2 rounded-pill">Good Pool</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group shadow-sm">
                                        <a href="edit.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light border"><i class="fas fa-edit"></i></a>
                                        <a href="delete.php?id=<?php echo $p['id']; ?>" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Purge this item from SKU library?')"><i class="fas fa-trash-alt"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No products found in the database. Add some items to begin.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
