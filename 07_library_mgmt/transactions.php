<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch all transactions
$query = "SELECT t.*, b.title, b.isbn 
          FROM transactions t 
          JOIN books b ON t.book_id = b.id 
          ORDER BY t.status ASC, t.issue_date DESC";
$res = $conn->query($query);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-navy fw-bold">Lending Transitions</h1>
    <a href="issue_book.php" class="btn btn-navy shadow-sm"><i class="fas fa-plus me-2"></i>New Issue</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="thead-navy">
                    <tr>
                        <th class="ps-4">Book Details</th>
                        <th>Student Name</th>
                        <th>Issue Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($row = $res->fetch_assoc()): ?>
                            <tr class="<?php echo ($row['status'] == 'Returned') ? 'text-muted' : ''; ?>">
                                <td class="ps-4">
                                    <div class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($row['isbn']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                <td><?php echo date('d M Y', strtotime($row['issue_date'])); ?></td>
                                <td><?php echo $row['return_date'] ? date('d M Y', strtotime($row['return_date'])) : '---'; ?></td>
                                <td>
                                    <?php if ($row['status'] == 'Active'): ?>
                                        <span class="badge bg-danger p-2 px-3">On Loan</span>
                                    <?php else: ?>
                                        <span class="badge bg-success p-2 px-3">Returned</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <?php if ($row['status'] == 'Active'): ?>
                                        <a href="return_book.php?id=<?php echo $row['id']; ?>&book_id=<?php echo $row['book_id']; ?>" 
                                           class="btn btn-sm btn-warning fw-bold shadow-sm"
                                           onclick="return confirm('Confirm book return and mark as available?')">
                                            <i class="fas fa-undo me-1"></i> Return
                                        </a>
                                    <?php else: ?>
                                        <i class="fas fa-check-double text-success"></i>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center py-5 text-muted">No lending records in database.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
