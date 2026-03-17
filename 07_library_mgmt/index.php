<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Library Statistics
// 1. Total Books
$total_books_res = $conn->query("SELECT COUNT(*) as total FROM books");
$total_books = $total_books_res->fetch_assoc()['total'];

// 2. Books Issued
$issued_res = $conn->query("SELECT COUNT(*) as total FROM books WHERE status = 'Issued'");
$issued_books = $issued_res->fetch_assoc()['total'];

// 3. Books Available
$avail_books = $total_books - $issued_books;

// 4. Recent Transactions
$recent_trans = $conn->query("SELECT t.*, b.title 
                             FROM transactions t 
                             JOIN books b ON t.book_id = b.id 
                             ORDER BY t.issue_date DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-navy fw-bold" style="font-family: 'Playfair Display', serif;">Academic Dashboard</h1>
    <a href="issue_book.php" class="btn btn-navy shadow-sm"><i class="fas fa-plus me-2"></i>New Transaction</a>
</div>

<!-- Stats Indicators -->
<div class="row">
    <!-- Total Books -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0" style="border-top: 5px solid #0a2342 !important;">
            <div class="card-body text-center py-4">
                <i class="fas fa-book-open fa-3x text-navy mb-3"></i>
                <div class="text-uppercase text-muted small fw-bold mb-1">Total Volumes</div>
                <div class="stats-number text-navy"><?php echo $total_books; ?></div>
            </div>
        </div>
    </div>

    <!-- Books Issued -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0" style="border-top: 5px solid #dc3545 !important;">
            <div class="card-body text-center py-4">
                <i class="fas fa-user-tag fa-3x text-danger mb-3"></i>
                <div class="text-uppercase text-muted small fw-bold mb-1">Active Loans</div>
                <div class="stats-number text-danger"><?php echo $issued_books; ?></div>
            </div>
        </div>
    </div>

    <!-- Available -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 shadow-sm border-0" style="border-top: 5px solid #28a745 !important;">
            <div class="card-body text-center py-4">
                <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                <div class="text-uppercase text-muted small fw-bold mb-1">Available for Borrowing</div>
                <div class="stats-number text-success"><?php echo $avail_books; ?></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <!-- Recent Activity -->
    <div class="col-lg-12">
        <div class="card shadow-sm border-0">
            <div class="card-header py-3 bg-white border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-navy text-uppercase"><i class="fas fa-history me-2"></i>Current Outstanding Issues</h6>
                <a href="transactions.php" class="btn btn-sm btn-outline-navy">View All Records</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-navy">
                            <tr>
                                <th class="ps-4">Book Title</th>
                                <th>Student Name</th>
                                <th>Issue Date</th>
                                <th class="text-center pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_trans->num_rows > 0): ?>
                                <?php while($row = $recent_trans->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold"><?php echo htmlspecialchars($row['title']); ?></td>
                                        <td><?php echo htmlspecialchars($row['student_name']); ?></td>
                                        <td><?php echo date('d M Y', strtotime($row['issue_date'])); ?></td>
                                        <td class="text-center pe-4">
                                            <?php if ($row['status'] == 'Active'): ?>
                                                <span class="badge bg-danger">Pending Return</span>
                                            <?php else: ?>
                                                <span class="badge bg-success">Returned</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-4 text-muted">No recent transactions discovered.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
