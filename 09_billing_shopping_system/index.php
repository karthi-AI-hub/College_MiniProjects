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
$healthy_stock = max(0, $total_products - $low_stock_count);

$total_bills_res = $conn->query("SELECT COUNT(*) as total FROM bills");
$total_bills = $total_bills_res->fetch_assoc()['total'] ?? 0;
$avg_bill_value = ($total_bills > 0) ? $total_revenue / $total_bills : 0;

$week_revenue_res = $conn->query("SELECT SUM(total_amount) as total FROM bills WHERE bill_date >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
$week_revenue = $week_revenue_res->fetch_assoc()['total'] ?? 0;

$top_category_res = $conn->query("SELECT category, COUNT(*) as total FROM products GROUP BY category ORDER BY total DESC LIMIT 1");
$top_category = ($top_category_res && $top_category_res->num_rows > 0) ? $top_category_res->fetch_assoc() : ['category' => 'N/A', 'total' => 0];

// Fetch Recent Transaction List
$recent_bills = $conn->query("SELECT * FROM bills ORDER BY bill_date DESC LIMIT 5");

?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h1 class="h3 mb-1 fw-bold">Market Intelligence</h1>
        <p class="text-muted small mb-0">Operational overview of retail nodes</p>
    </div>
    <a href="create_bill.php" class="btn btn-teal shadow-sm"><i class="fas fa-plus me-2"></i>Generate Invoice</a>
</div>

<div class="row mb-4">
    <div class="col-lg-6">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0">Inventory Health</h5>
            </div>
            <div class="card-body p-4">
                <canvas id="inventoryChart" height="220"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
    <!-- Active SKUs -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon me-3">
                        <i class="fas fa-box"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-bold">Inventory Assets</span>
                </div>
                <h2 class="stats-number mb-0"><?php echo $total_products; ?> <span class="fs-6 fw-normal text-muted">SKUs</span></h2>
            </div>
        </div>
    </div>

    <!-- Stock Alerts -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-danger">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon me-3 bg-danger bg-opacity-10 text-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-bold">Critical Stock</span>
                </div>
                <h2 class="stats-number mb-0 text-danger"><?php echo $low_stock_count; ?> <span class="fs-6 fw-normal text-muted">Alerts</span></h2>
            </div>
        </div>
    </div>

    <!-- Revenue -->
    <div class="col-md-4 mb-4">
        <div class="card h-100 border-start border-4 border-success">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="stats-icon me-3 bg-success bg-opacity-10 text-success">
                        <i class="fas fa-wallet"></i>
                    </div>
                    <span class="text-muted small text-uppercase fw-bold">Gross Revenue</span>
                </div>
                <h2 class="stats-number mb-0 text-success">$<?php echo number_format($total_revenue, 2); ?></h2>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Live Transaction Feed -->
    <div class="col-lg-8 mb-4">
        <div class="card h-100">
            <div class="card-header bg-transparent border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Live Transaction Feed</h5>
                <a href="view_bills.php" class="btn btn-sm btn-outline-light border-0">Full History</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4">Invoice Hash</th>
                                <th>Customer Identity</th>
                                <th>Timestamp</th>
                                <th class="text-end pe-4">Credit Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_bills->num_rows > 0): ?>
                                <?php while($b = $recent_bills->fetch_assoc()): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="badge bg-teal bg-opacity-10 text-teal px-3 py-2 fw-bold">
                                                #INV-<?php echo str_pad($b['id'], 5, '0', STR_PAD_LEFT); ?>
                                            </span>
                                        </td>
                                        <td><span class="fw-bold"><?php echo htmlspecialchars($b['customer_name']); ?></span></td>
                                        <td><span class="text-muted small"><?php echo date('d M, H:i', strtotime($b['bill_date'])); ?></span></td>
                                        <td class="text-end pe-4 fw-bold text-success">$<?php echo number_format($b['total_amount'], 2); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">No operational logs found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Smart Retail Intelligence -->
    <div class="col-lg-4 mb-4">
        <div class="card h-100 bg-teal bg-opacity-10 border-teal border-opacity-25">
            <div class="card-body p-4 d-flex flex-column">
                <h5 class="fw-bold mb-4 text-teal"><i class="fas fa-microchip me-2"></i>Market Intel</h5>
                <div class="mb-4">
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-receipt text-teal mt-1 me-3"></i>
                        <div>
                            <p class="small text-uppercase fw-bold mb-1">Avg Bill Value</p>
                            <p class="mb-0 fw-bold text-teal">$<?php echo number_format($avg_bill_value, 2); ?></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start mb-3">
                        <i class="fas fa-chart-line text-teal mt-1 me-3"></i>
                        <div>
                            <p class="small text-uppercase fw-bold mb-1">7-Day Revenue</p>
                            <p class="mb-0 fw-bold text-teal">$<?php echo number_format($week_revenue, 2); ?></p>
                        </div>
                    </div>
                    <div class="d-flex align-items-start">
                        <i class="fas fa-tags text-teal mt-1 me-3"></i>
                        <div>
                            <p class="small text-uppercase fw-bold mb-1">Top Category</p>
                            <p class="mb-0 fw-bold text-teal"><?php echo htmlspecialchars($top_category['category']); ?></p>
                            <p class="small text-muted mb-0"><?php echo (int)$top_category['total']; ?> SKUs</p>
                        </div>
                    </div>
                </div>
                <div class="mt-auto pt-4 border-top border-teal border-opacity-10">
                    <p class="small text-muted italic mb-0">"Efficiency is the core of sustainable commerce."</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const inventoryChart = document.getElementById('inventoryChart');
    if (inventoryChart) {
        new Chart(inventoryChart, {
            type: 'doughnut',
            data: {
                labels: ['Healthy Stock', 'Low Stock'],
                datasets: [{
                    data: [<?php echo (int)$healthy_stock; ?>, <?php echo (int)$low_stock_count; ?>],
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
