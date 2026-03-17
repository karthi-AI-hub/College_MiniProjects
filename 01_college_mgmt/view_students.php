<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Departments for Filter Dropdown
$departments_list = $conn->query("SELECT * FROM departments ORDER BY dept_name ASC");

// Get Parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$dept_filter = isset($_GET['dept_filter']) ? $_GET['dept_filter'] : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'roll_no';
$order = isset($_GET['order']) && $_GET['order'] == 'desc' ? 'DESC' : 'ASC';

// Build Where Clause
$where_clauses = [];
if ($search) {
    $s = $conn->real_escape_string($search);
    $where_clauses[] = "(students.name LIKE '%$s%' OR students.roll_no LIKE '%$s%')";
}
if ($dept_filter) {
    $df = (int)$dept_filter;
    $where_clauses[] = "students.dept_id = $df";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}

// Logic for Sorting
$allowed_sort = ['roll_no', 'name', 'dept_name'];
$sort_by = in_array($sort, $allowed_sort) ? $sort : 'roll_no';

$query = "SELECT students.*, departments.dept_name 
          FROM students 
          LEFT JOIN departments ON students.dept_id = departments.id 
          $where_sql 
          ORDER BY $sort_by $order";

$result = $conn->query($query);
?>

<div class="executive-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Student Directory</h1>
            <p class="text-muted small mb-0">Manage and filter student academic profiles.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="add.php" class="btn btn-primary btn-sm px-3">
                <i class="fas fa-plus me-1"></i> Add Student
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'deleted'): ?>
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> Student deleted successfully.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(isset($_GET['msg']) && $_GET['msg'] == 'auth'): ?>
        <div class="alert alert-warning alert-dismissible fade show border-0 shadow-sm" role="alert">
            <i class="fas fa-lock me-2"></i> Please sign in to delete records.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <!-- Filter & Search Bar -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body p-3">
            <form action="" method="get" class="row g-3 align-items-end no-print">
                <div class="col-md-4">
                    <label class="form-label small fw-600 text-muted mb-1">SEARCH</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 text-muted"><i class="fas fa-search small"></i></span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Name or Roll No..." value="<?php echo htmlspecialchars($search); ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-600 text-muted mb-1">DEPARTMENT</label>
                    <select name="dept_filter" class="form-select" onchange="this.form.submit()">
                        <option value="">All Departments</option>
                        <?php while($dept = $departments_list->fetch_assoc()): ?>
                            <option value="<?php echo $dept['id']; ?>" <?php echo ($dept_filter == $dept['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($dept['dept_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-600 text-muted mb-1">SORT BY</label>
                    <div class="d-flex gap-2">
                        <select name="sort" class="form-select flex-grow-1" onchange="this.form.submit()">
                            <option value="roll_no" <?php echo ($sort == 'roll_no') ? 'selected' : ''; ?>>Roll Number</option>
                            <option value="name" <?php echo ($sort == 'name') ? 'selected' : ''; ?>>Name</option>
                            <option value="dept_name" <?php echo ($sort == 'dept_name') ? 'selected' : ''; ?>>Department</option>
                        </select>
                        <select name="order" class="form-select w-auto" style="min-width: 90px;" onchange="this.form.submit()">
                            <option value="asc" <?php echo ($order == 'ASC') ? 'selected' : ''; ?>>ASC</option>
                            <option value="desc" <?php echo ($order == 'DESC') ? 'selected' : ''; ?>>DESC</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 d-grid">
                    <a href="view_students.php" class="btn btn-light border text-muted fw-600">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">Roll No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th class="pe-4 no-print text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 fw-600"><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center text-primary fw-700" style="width: 32px; height: 32px; font-size: 0.75rem;">
                                            <?php echo strtoupper(substr($row['name'], 0, 1)); ?>
                                        </div>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </div>
                                </td>
                                <td><span class="badge bg-light text-dark border"><?php echo htmlspecialchars($row['dept_name']); ?></span></td>
                                <td class="text-muted"><?php echo htmlspecialchars($row['email']); ?></td>
                                <td class="text-muted"><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td class="pe-4 no-print text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border" title="Edit Profile"><i class="fas fa-edit text-primary"></i></a>
                                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border" title="Delete Profile" onclick="return confirm('Are you sure you want to delete this student record?')">
                                            <i class="fas fa-trash text-danger"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center py-5 text-muted">No student profiles match your search criteria.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div> <!-- End container-fluid -->

<?php include 'includes/footer.php'; ?>
