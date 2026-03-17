<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Sales History
$query = "SELECT * FROM bills ORDER BY bill_date DESC";
$res = $conn->query($query);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Invoice History Log</h1>
    <a href="create_bill.php" class="btn btn-teal shadow-sm fw-bold">
        <i class="fas fa-plus me-1"></i>New Transaction
    </a>
</div>

<?php if(isset($_GET['status']) && $_GET['status'] == 'success'): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4">
        <i class="fas fa-check-circle me-2"></i> Transaction #<?php echo $_GET['id']; ?> posted successfully! Stock pool updated.
    </div>
<?php endif; ?>

<div class="card card-retail border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-dark text-white">
                    <tr>
                        <th class="ps-4">Invoice #</th>
                        <th>Customer / Client</th>
                        <th>Sale Date</th>
                        <th class="text-end">Gross Total</th>
                        <th class="text-center pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($res->num_rows > 0): ?>
                        <?php while($b = $res->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4 text-teal fw-bold">INV-<?php echo str_pad($b['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                                <td class="small"><?php echo date('d M Y | H:i', strtotime($b['bill_date'])); ?></td>
                                <td class="text-end fw-bold text-success">$<?php echo number_format($b['total_amount'], 2); ?></td>
                                <td class="text-center pe-4">
                                    <button class="btn btn-sm btn-light border px-3" onclick="alert('Print functionality ready (PDF Preview)')">
                                        <i class="fas fa-print me-1"></i> Print
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="text-center py-5 text-muted">No transactions found in archive.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
