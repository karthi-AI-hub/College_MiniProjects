<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    if (empty($name) || empty($price)) {
        $error = "Product name and price are non-negotiable fields.";
    } else {
        $stmt = $conn->prepare("INSERT INTO products (product_name, category, price, stock_quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $category, $price, $stock);
        
        if ($stmt->execute()) {
            $message = "[$name] added to SKU registry successfully.";
        } else {
            $error = "System Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Provision New SKU</h1>
    <a href="view_products.php" class="btn btn-outline-teal shadow-sm"><i class="fas fa-boxes me-2"></i>Full Inventory</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4"><i class="fas fa-check-circle me-2"></i> <?php echo $message; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4"><i class="fas fa-times-circle me-2"></i> <?php echo $error; ?></div>
<?php endif; ?>

<div class="card card-retail border-0 max-width-800 mx-auto">
    <div class="card-body p-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label small fw-bold text-muted">PRODUCT IDENTIFIER</label>
                    <input type="text" name="product_name" class="form-control form-control-lg" placeholder="Enter Product Name" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-muted">SEGMENT / CATEGORY</label>
                    <input type="text" name="category" class="form-control" placeholder="e.g. Dairy, Electronics" list="cat-suggestions">
                    <datalist id="cat-suggestions">
                        <option value="Dairy">
                        <option value="Bakery">
                        <option value="Beverages">
                        <option value="Snacks">
                        <option value="Grocery">
                    </datalist>
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-muted">UNIT PRICE ($)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">$</span>
                        <input type="number" name="price" step="0.01" class="form-control" placeholder="0.00" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-muted">INITIAL STOCK POOL</label>
                    <input type="number" name="stock_quantity" class="form-control" placeholder="Quantity" value="0" required>
                </div>
                <div class="col-md-6 mb-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-teal btn-lg w-100 fw-bold shadow-sm">COMMIT SKU TO DATABASE</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.max-width-800 { max-width: 800px; }
</style>

<?php
include 'includes/footer.php';
?>
