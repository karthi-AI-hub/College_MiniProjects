<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Get Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'dept_name';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

// Build Search Clause
$search_sql = "";
if ($search) {
    $s = $conn->real_escape_string($search);
    $search_sql = "WHERE dept_name LIKE '%$s%'";
}

// Logic for Sorting
$allowed_sort = ['id', 'dept_name'];
$sort_by = in_array($sort, $allowed_sort) ? $sort : 'dept_name';

// Fetch Departments
$departments = $conn->query("SELECT * FROM departments $search_sql ORDER BY $sort_by $order");

// Total Count for Metric
$total_depts = $conn->query("SELECT COUNT(*) as total FROM departments")->fetch_assoc()['total'];
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Academic Infrastructure</h1>
            <p class="text-muted small mb-0">Manage and organize departments.</p>
        </div>
        <div class="d-flex align-items-center gap-3">
            <a href="add_department.php" class="btn btn-primary btn-sm px-3 d-none d-md-flex align-items-center gap-2">
                <i class="fas fa-plus"></i> New Department
            </a>
            <div class="stats-icon-flat bg-primary bg-opacity-10 text-primary">
                <i class="fas fa-sitemap"></i>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid">

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-check-circle me-2"></i> Department deleted successfully.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(isset($_GET['msg']) && $_GET['msg'] == 'auth'): ?>
    <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
        <i class="fas fa-lock me-2"></i> Please sign in to delete records.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<div class="row">
    <!-- Department List -->
    <div class="col-12">
        <!-- Search & Sort Bar -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body p-3">
                <form action="" method="get" class="row g-3 align-items-center no-print filter-bar">
                    <div class="col-md-5">
                        <div class="input-group filter-input-group">
                            <span class="input-group-text bg-white border-end-0 text-muted filter-control"><i class="fas fa-search"></i></span>
                            <input type="text" name="search" class="form-control border-start-0 ps-0 filter-control" placeholder="Find department..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-2">
                            <select name="sort" class="form-select filter-control" onchange="this.form.submit()">
                                <option value="dept_name" <?php echo ($sort == 'dept_name') ? 'selected' : ''; ?>>Sort by Name</option>
                                <option value="id" <?php echo ($sort == 'id') ? 'selected' : ''; ?>>Sort by ID</option>
                            </select>
                            <select name="order" class="form-select w-auto filter-control" onchange="this.form.submit()">
                                <option value="asc" <?php echo ($order == 'ASC') ? 'selected' : ''; ?>>ASC</option>
                                <option value="desc" <?php echo ($order == 'DESC') ? 'selected' : ''; ?>>DESC</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <a href="view_departments.php" class="btn btn-light border w-100 text-muted filter-control">Clear</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4" style="width: 80px;">ID</th>
                            <th>Department Name</th>
                            <th class="pe-4 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($departments->num_rows > 0): ?>
                            <?php while($row = $departments->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-muted small">#<?php echo $row['id']; ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="stats-icon-flat bg-light text-muted me-3" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                <i class="fas fa-building"></i>
                                            </div>
                                            <span class="fw-600 text-dark"><?php echo htmlspecialchars($row['dept_name']); ?></span>
                                        </div>
                                    </td>
                                    <td class="pe-4 text-center">
                                        <a href="delete_department.php?id=<?php echo $row['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger border-0"
                                           title="Remove Department"
                                           onclick="return confirm('Are you sure? This will delete all students in this department!');">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr><td colspan="3" class="text-center py-5 text-muted">No departments found matching your search.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div> <!-- End container-fluid -->
<?php
include 'includes/footer.php';
?>
