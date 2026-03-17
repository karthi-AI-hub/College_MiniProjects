<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$where_clause = "";
if ($search) {
    $search = $conn->real_escape_string($search);
    $where_clause = "WHERE students.name LIKE '%$search%' OR students.roll_no LIKE '%$search%' OR departments.dept_name LIKE '%$search%'";
}

$query = "SELECT students.*, departments.dept_name 
          FROM students 
          LEFT JOIN departments ON students.dept_id = departments.id 
          $where_clause 
          ORDER BY students.created_at DESC";

$result = $conn->query($query);
?>

<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
    <h2>Student Directory</h2>
    <div>
        <a href="add.php" class="btn btn-primary d-print-none">
        <i class="fas fa-plus me-1"></i> Add Student
    </a>
        <button onclick="window.print()" class="btn btn-secondary btn-print"><i class="fas fa-print"></i> Print</button>
    </div>
</div>

<!-- Search Form -->
<form action="" method="get" class="mb-4 no-print">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search by Name, Roll No, or Department" value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-outline-secondary" type="submit">Search</button>
        <?php if($search): ?>
            <a href="view_students.php" class="btn btn-outline-danger">Clear</a>
        <?php endif; ?>
    </div>
</form>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Roll No</th>
                        <th>Name</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th class="no-print">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['roll_no']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['dept_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo htmlspecialchars($row['gender']); ?></td>
                                <td class="no-print">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="7" class="text-center">No students found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
