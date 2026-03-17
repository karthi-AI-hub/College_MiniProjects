<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Filter & Search Logic
$where_clauses = [];
$params = [];
$types = "";

if (isset($_GET['designation_id']) && !empty($_GET['designation_id'])) {
    $where_clauses[] = "e.designation_id = ?";
    $params[] = $_GET['designation_id'];
    $types .= "i";
}

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $where_clauses[] = "(e.name LIKE ? OR e.emp_id LIKE ? OR e.email LIKE ?)";
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
    $types .= "sss";
}

$query = "SELECT e.*, d.designation_name 
          FROM employees e 
          JOIN designations d ON e.designation_id = d.id";

if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}

$query .= " ORDER BY e.id DESC";

$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$employees = $stmt->get_result();

// Fetch Designations for Filter
$designations = $conn->query("SELECT * FROM designations ORDER BY designation_name ASC");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800 fw-bold">Employee Directory</h1>
    <a href="add.php" class="btn btn-purple shadow-sm"><i class="fas fa-user-plus me-2"></i>New Hire</a>
</div>

<!-- Filters -->
<div class="card shadow mb-4">
    <div class="card-body">
        <form action="" method="get" class="row g-3">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by name, ID, or email..." value="<?php echo $_GET['search'] ?? ''; ?>">
            </div>
            <div class="col-md-3">
                <select name="designation_id" class="form-select">
                    <option value="">All Designations</option>
                    <?php while($d = $designations->fetch_assoc()): ?>
                        <option value="<?php echo $d['id']; ?>" <?php echo (isset($_GET['designation_id']) && $_GET['designation_id'] == $d['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($d['designation_name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-purple px-4">Apply Filters</button>
                <a href="view_employees.php" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Employee Table -->
<div class="card shadow mb-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead>
                    <tr>
                        <th class="ps-4">EMP ID</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Salary</th>
                        <th>Join Date</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($employees->num_rows > 0): ?>
                        <?php while($emp = $employees->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 font-weight-bold"><?php echo htmlspecialchars($emp['emp_id']); ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($emp['name']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($emp['email']); ?></div>
                                </td>
                                <td><span class="badge bg-purple-light text-purple"><?php echo htmlspecialchars($emp['designation_name']); ?></span></td>
                                <td>$<?php echo number_format($emp['salary'], 2); ?></td>
                                <td><?php echo date('d M Y', strtotime($emp['join_date'])); ?></td>
                                <td class="text-center pe-4">
                                    <div class="btn-group">
                                        <a href="edit.php?id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                        <a href="delete.php?id=<?php echo $emp['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Terminate this employee record?')"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-4 text-muted">No कर्मचारी (employees) found in directory.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.bg-purple-light { background-color: rgba(111, 66, 193, 0.1); }
.text-purple { color: #6f42c1; }
.btn-purple { background-color: #6f42c1; border-color: #6f42c1; color: white; }
.btn-purple:hover { background-color: #59359a; border-color: #59359a; color: white; }
</style>

<?php
include 'includes/footer.php';
?>
