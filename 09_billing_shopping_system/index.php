<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch Store Statistics
// 1. Total Products
$total_prod_res = $conn->query("SELECT COUNT(*) as total FROM products");
$total_products = $total_prod_res->fetch_assoc()['total'];

// 2. Low Stock Alert (Quantity < 10)
$low_stock_res = $conn->query("SELECT COUNT(*) as total FROM products WHERE stock_quantity < 10");
$low_stock_count = $low_stock_res->fetch_assoc()['total'];

// 3. Total Revenue
$revenue_res = $conn->query("SELECT SUM(total_amount) as total FROM bills");
$total_revenue = $revenue_res->fetch_assoc()['total'] ?? 0;

// Fetch Recent Transaction List
$recent_bills = $conn->query("SELECT * FROM bills ORDER BY bill_date DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Store Command Center</h1>
    <a href="create_bill.php" class="btn btn-teal shadow-sm px-4 fw-bold">
        <i class="fas fa-plus me-2"></i>GENERATE NEW BILL
    </a>
</div>

<div class="row">
    <!-- Stat Cards -->
    <div class="col-md-4 mb-4">
        <div class="card card-retail h-100 p-3">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon me-3">
                    <i class="fas fa-boxes fa-lg"></i>
                </div>
                <div>
                    <div class="small fw-semibold text-muted">Active SKUs</div>
                    <div class="h3 fw-bold mb-0"><?php echo $total_products; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card card-retail h-100 p-3">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon me-3 bg-danger bg-opacity-10 text-danger">
                    <i class="fas fa-exclamation-triangle fa-lg"></i>
                </div>
                <div>
                    <div class="small fw-semibold text-muted">Stock Alerts</div>
                    <div class="h3 fw-bold mb-0 text-danger"><?php echo $low_stock_count; ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card card-retail h-100 p-3">
            <div class="card-body d-flex align-items-center">
                <div class="stats-icon me-3 bg-success bg-opacity-10 text-success">
                    <i class="fas fa-wallet fa-lg"></i>
                </div>
                <div>
                    <div class="small fw-semibold text-muted">Revenue Flow (Gross)</div>
                    <div class="h3 fw-bold mb-0 text-success">$<?php echo number_format($total_revenue, 2); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <!-- Recent Transactions -->
    <div class="col-lg-8 mb-4">
        <div class="card card-retail h-100">
            <div class="card-header bg-white border-0 py-3">
                <h6 class="m-0 fw-bold text-dark"><i class="fas fa-receipt me-2 text-teal"></i>Live Transaction Feed</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Invoice ID</th>
                                <th>Customer Name</th>
                                <th>Timestamp</th>
                                <th class="text-end pe-4">Total Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_bills->num_rows > 0): ?>
                                <?php while($b = $recent_bills->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4 fw-bold text-teal">#INV-<?php echo str_pad($b['id'], 5, '0', STR_PAD_LEFT); ?></td>
                                        <td><?php echo htmlspecialchars($b['customer_name']); ?></td>
                                        <td class="small"><?php echo date('d M, H:i', strtotime($b['bill_date'])); ?></td>
                                        <td class="text-end pe-4 fw-bold">$<?php echo number_format($b['total_amount'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">No sales recorded yet.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Retail Quick Tips -->
    <div class="col-lg-4 mb-4">
        <div class="card card-retail h-100 bg-teal text-white">
            <div class="card-body d-flex flex-column justify-content-center p-4">
                <h4 class="fw-bold mb-3"><i class="fas fa-lightbulb me-2"></i>Smart Billing Tips</h4>
                <ul class="list-unstyled opacity-90 small">
                    <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Keep items updated with accurate pricing.</li>
                    <li class="mb-3"><i class="fas fa-check-circle me-2"></i> Billing automatically decrements your inventory.</li>
                    <li><i class="fas fa-check-circle me-2"></i> Low stock alerts trigger red badges in the registry.</li>
                </ul>
                <hr class="bg-white">
                <p class="small mb-0">"Efficient retail starts with precise inventory data."</p>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>
