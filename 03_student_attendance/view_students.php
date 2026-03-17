<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$students = $conn->query("SELECT * FROM students ORDER BY name ASC");
?>

<div class="executive-header mt-4 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="outfit fw-700 h3 mb-1">Student Registry</h1>
            <p class="text-muted small mb-0">Manage roster details and student records.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="add.php" class="btn btn-primary px-4 fw-600">
                <i class="fas fa-user-plus me-2"></i> Add Student
            </a>
        </div>
    </div>
</div>

<div class="container-fluid">
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0 outfit fw-600">Registered Students</h5>
            <span class="text-muted small">Total: <?php echo $students->num_rows; ?></span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Student Name</th>
                        <th>Roll Number</th>
                        <th>Class / Section</th>
                        <th class="pe-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($students->num_rows > 0): ?>
                        <?php while($row = $students->fetch_assoc()): ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($row['name']); ?>&background=f3f4f6&color=4f46e5" width="28" height="28" class="rounded-circle me-3 border" alt="<?php echo htmlspecialchars($row['name']); ?>">
                                    <span class="fw-600"><?php echo htmlspecialchars($row['name']); ?></span>
                                </div>
                            </td>
                            <td><code class="small text-muted"><?php echo htmlspecialchars($row['roll_no']); ?></code></td>
                            <td><span class="badge-zen bg-light text-dark"><?php echo htmlspecialchars($row['class_section']); ?></span></td>
                            <td class="pe-4 text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border" title="Modify"><i class="fas fa-pen-nib text-primary"></i></a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border" title="Delete" onclick="return confirm('Delete this record?')">
                                        <i class="fas fa-trash-can text-danger"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center py-5 text-muted">No student data detected in this sector.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
