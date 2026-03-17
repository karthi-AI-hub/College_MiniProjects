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

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Livestock Registry</h1>
            <p class="text-muted small mb-0">Manage and filter the livestock inventory.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="add.php" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus me-1"></i> Add Entry
            </a>
            <?php if($filter_status == 'Sick'): ?>
                <button onclick="window.print()" class="btn btn-light btn-sm px-3 border"><i class="fas fa-print me-1"></i> Sick Report</button>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> Animal deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'auth'): ?>
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-lock me-2"></i> Please sign in to delete records.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Filter Bar -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="" method="get" class="row g-3 align-items-center no-print filter-bar">
                <div class="col-md-5">
                    <label class="form-label small fw-600 text-muted mb-1">CATEGORY</label>
                    <select name="category" class="form-select filter-control" onchange="this.form.submit()">
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
                <div class="col-md-5">
                    <label class="form-label small fw-600 text-muted mb-1">HEALTH STATUS</label>
                    <select name="filter" class="form-select filter-control" onchange="this.form.submit()">
                        <option value="">All Health Statuses</option>
                        <option value="Healthy" <?php echo ($filter_status == 'Healthy') ? 'selected' : ''; ?>>Healthy</option>
                        <option value="Sick" <?php echo ($filter_status == 'Sick') ? 'selected' : ''; ?>>Sick</option>
                        <option value="Under Observation" <?php echo ($filter_status == 'Under Observation') ? 'selected' : ''; ?>>Under Observation</option>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <label class="form-label small fw-600 text-muted mb-1">ACTION</label>
                    <a href="view_animals.php" class="btn btn-light border text-muted fw-600 filter-control">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <?php if($filter_status == 'Sick'): ?>
        <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small mb-4 tracking-widest text-uppercase fw-600 no-print">
            <i class="fas fa-exclamation-triangle me-2"></i> Veterinary Review Required
        </div>
    <?php endif; ?>

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tag ID</th>
                        <th>Category / Breed</th>
                        <th>Age / Weight</th>
                        <th>Health Status</th>
                        <th>Last Checkup</th>
                        <th class="pe-4 no-print text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 fw-600"><?php echo htmlspecialchars($row['tag_id']); ?></td>
                                <td>
                                    <div class="fw-600"><?php echo htmlspecialchars($row['category_name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['breed']); ?></small>
                                </td>
                                <td>
                                    <div><?php echo htmlspecialchars($row['age']); ?></div>
                                    <small class="text-muted tracking-widest"><?php echo htmlspecialchars($row['weight']); ?> KG</small>
                                </td>
                                <td>
                                    <?php 
                                    $status_state = 'bg-light text-dark';
                                    if($row['health_status'] == 'Healthy') $status_state = 'bg-success bg-opacity-10 text-success';
                                    elseif($row['health_status'] == 'Sick') $status_state = 'bg-danger bg-opacity-10 text-danger';
                                    elseif($row['health_status'] == 'Under Observation') $status_state = 'bg-warning bg-opacity-10 text-warning';
                                    ?>
                                    <span class="badge-zen <?php echo $status_state; ?>"><?php echo htmlspecialchars($row['health_status']); ?></span>
                                </td>
                                <td class="text-muted"><?php echo htmlspecialchars($row['last_checkup_date']); ?></td>
                                <td class="pe-4 no-print text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border" title="Edit Entry"><i class="fas fa-edit text-primary"></i></a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border" title="Delete Entry" onclick="return confirm('Are you sure you want to delete this record?')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No livestock records match your search criteria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- End container-fluid -->

<?php
include 'includes/footer.php';
?>
