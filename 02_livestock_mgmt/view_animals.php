<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$filter_category = isset($_GET['category']) ? $_GET['category'] : '';
$filter_status = isset($_GET['filter']) ? $_GET['filter'] : ''; // Handles 'Sick' filter from dashboard

$where_clauses = [];
if ($filter_category) {
    $safe_category = $conn->real_escape_string($filter_category);
    $where_clauses[] = "categories.id = '$safe_category'";
}
if ($filter_status) {
    $safe_status = $conn->real_escape_string($filter_status);
    $where_clauses[] = "livestock.health_status = '$safe_status'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(' AND ', $where_clauses);
}

// JOIN Query to fetch livestock with category names
// We use LEFT JOIN to ensure we get animal data even if category is somehow missing (though constraints prevent this)
$query = "SELECT livestock.*, categories.category_name 
          FROM livestock 
          LEFT JOIN categories ON livestock.category_id = categories.id 
          $where_sql 
          ORDER BY livestock.created_at DESC";

$result = $conn->query($query);

// Fetch categories for filter dropdown
$categories = $conn->query("SELECT * FROM categories ORDER BY category_name ASC");
?>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h2 class="text-success"><i class="fas fa-list"></i> Livestock Inventory</h2>
    <div>
        <a href="add.php" class="btn btn-dark">
        <i class="fas fa-plus me-1"></i> Add Entry
    </a>
        <?php if($filter_status == 'Sick'): ?>
             <button onclick="window.print()" class="btn btn-danger btn-print"><i class="fas fa-print"></i> Print Sick Report</button>
        <?php endif; ?>
    </div>
</div>

<!-- Filter Form -->
<form action="" method="get" class="mb-4 no-print row g-3">
    <div class="col-auto">
        <select name="category" class="form-select">
            <option value="">All Categories</option>
            <?php 
            if ($categories->num_rows > 0) {
                $categories->data_seek(0);
                while($row = $categories->fetch_assoc()) {
                    $selected = ($filter_category == $row['id']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . htmlspecialchars($row['category_name']) . "</option>";
                }
            }
            ?>
        </select>
    </div>
    <div class="col-auto">
        <select name="filter" class="form-select">
            <option value="">All Health Statuses</option>
            <option value="Healthy" <?php echo ($filter_status == 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
            <option value="Sick" <?php echo ($filter_status == 'Sick') ? 'selected' : ''; ?>>Sick</option>
            <option value="Under Observation" <?php echo ($filter_status == 'Under Observation') ? 'selected' : ''; ?>>Under Observation</option>
        </select>
    </div>
    <div class="col-auto">
        <button type="submit" class="btn btn-outline-success">Filter</button>
        <a href="view_animals.php" class="btn btn-outline-secondary">Clear</a>
    </div>
</form>

<?php if($filter_status == 'Sick'): ?>
    <div class="alert alert-danger no-print">
        <strong><i class="fas fa-exclamation-triangle"></i> Attention:</strong> Creating list of sick animals for veterinary review.
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-success">
                    <tr>
                        <th>Tag ID</th>
                        <th>Category</th>
                        <th>Breed</th>
                        <th>Age / Weight</th>
                        <th>Health Status</th>
                        <th>Last Checkup</th>
                        <th class="no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr class="<?php echo ($row['health_status'] == 'Sick') ? 'table-danger' : ''; ?>">
                                <td class="fw-bold"><?php echo htmlspecialchars($row['tag_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['category_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['breed']); ?></td>
                                <td>
                                    <div><?php echo htmlspecialchars($row['age']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['weight']); ?> kg</small>
                                </td>
                                <td>
                                    <?php 
                                    $badge_class = 'bg-secondary';
                                    if($row['health_status'] == 'Healthy') $badge_class = 'badge-healthy';
                                    elseif($row['health_status'] == 'Sick') $badge_class = 'badge-sick';
                                    elseif($row['health_status'] == 'Under Observation') $badge_class = 'badge-observation';
                                    ?>
                                    <span class="badge <?php echo $badge_class; ?>"><?php echo htmlspecialchars($row['health_status']); ?></span>
                                </td>
                                <td><?php echo htmlspecialchars($row['last_checkup_date']); ?></td>
                                <td class="no-print">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-dark"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Purge this record?')">
                                        <i class="fas fa-trash-alt"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No livestock records found matching your criteria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
