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
$issue_rate = ($total_books > 0) ? round(($issued_books / $total_books) * 100, 1) : 0;

$recent_books_res = $conn->query("SELECT COUNT(*) as total FROM books WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
$recent_books = $recent_books_res->fetch_assoc()['total'] ?? 0;

$top_book_res = $conn->query("SELECT b.title, COUNT(*) as total FROM transactions t JOIN books b ON t.book_id = b.id GROUP BY t.book_id ORDER BY total DESC LIMIT 1");
$top_book = ($top_book_res && $top_book_res->num_rows > 0) ? $top_book_res->fetch_assoc() : ['title' => 'N/A', 'total' => 0];

// 4. Recent Transactions
$recent_trans = $conn->query("SELECT t.*, b.title 
                             FROM transactions t 
                             JOIN books b ON t.book_id = b.id 
                             ORDER BY t.issue_date DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 fw-bold">Collection Overview</h1>
    <a href="issue_book.php" class="btn btn-navy shadow-sm"><i class="fas fa-plus me-2"></i>New Transaction</a>
</div>

<div class="row mb-4">
    <div class="col-lg-6 mb-4 mb-lg-0">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Book Availability</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="libraryChart" height="220"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Insight Highlights</h5>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Issue Rate</div>
                            <div class="h4 mb-0 fw-bold text-danger"><?php echo $issue_rate; ?>%</div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">New (30 Days)</div>
                            <div class="h4 mb-0 fw-bold text-success"><?php echo $recent_books; ?></div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="p-3 border rounded-3 h-100">
                            <div class="text-muted small text-uppercase fw-600">Most Issued</div>
                            <div class="fw-bold"><?php echo htmlspecialchars($top_book['title']); ?></div>
                            <div class="text-muted small"><?php echo (int)$top_book['total']; ?> issues</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <!-- Total Volumes Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-navy bg-opacity-10 p-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-book-open text-navy fs-3"></i>
                </div>
                <div class="stats-number mb-2"><?php echo $total_books; ?></div>
                <p class="text-muted small text-uppercase fw-bold mb-0">Total Volumes</p>
            </div>
        </div>
    </div>

    <!-- Active Loans Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-danger bg-opacity-10 p-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-user-tag text-danger fs-3"></i>
                </div>
                <div class="stats-number text-danger mb-2"><?php echo $issued_books; ?></div>
                <p class="text-muted small text-uppercase fw-bold mb-0">Active Loans</p>
            </div>
        </div>
    </div>

    <!-- Available Card -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4 text-center">
                <div class="rounded-circle bg-success bg-opacity-10 p-3 mx-auto mb-3" style="width: 70px; height: 70px;">
                    <i class="fas fa-check-circle text-success fs-3"></i>
                </div>
                <div class="stats-number text-success mb-2"><?php echo $avail_books; ?></div>
                <p class="text-muted small text-uppercase fw-bold mb-0">Available Books</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Outstanding Issues</h5>
                <a href="transactions.php" class="btn btn-sm btn-outline-light border-0">Audit Logs</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Resource Details</th>
                                <th>Student Affiliate</th>
                                <th>Checkout Date</th>
                                <th class="text-end pe-4">Current Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_trans->num_rows > 0): ?>
                                <?php while($row = $recent_trans->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="bg-navy bg-opacity-10 p-2 rounded me-3">
                                                    <i class="fas fa-book text-navy"></i>
                                                </div>
                                                <span class="fw-bold"><?php echo htmlspecialchars($row['title']); ?></span>
                                            </div>
                                        </td>
                                        <td><span class="text-muted"><?php echo htmlspecialchars($row['student_name']); ?></span></td>
                                        <td><span class="small"><?php echo date('d M Y', strtotime($row['issue_date'])); ?></span></td>
                                        <td class="text-end pe-4">
                                            <?php if ($row['status'] == 'Active'): ?>
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 small">PENDING RETURN</span>
                                            <?php else: ?>
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 small">ARCHIVED</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">No outstanding collection movements discovered.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const libraryChart = document.getElementById('libraryChart');
    if (libraryChart) {
        new Chart(libraryChart, {
            type: 'doughnut',
            data: {
                labels: ['Available', 'Issued'],
                datasets: [{
                    data: [<?php echo (int)$avail_books; ?>, <?php echo (int)$issued_books; ?>],
                    backgroundColor: ['#22c55e', '#ef4444'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
</script>

<?php
include 'includes/footer.php';
?>
