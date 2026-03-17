<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

$message = "";
$error = "";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $p = $stmt->get_result()->fetch_assoc();
    if (!$p) die("SKU not found.");
} else {
    header("Location: view_products.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['product_name']);
    $category = trim($_POST['category']);
    $price = $_POST['price'];
    $stock = $_POST['stock_quantity'];

    if (empty($name) || empty($price)) {
        $error = "Name and Price are mandatory SKU metrics.";
    } else {
        $stmt = $conn->prepare("UPDATE products SET product_name=?, category=?, price=?, stock_quantity=? WHERE id=?");
        $stmt->bind_param("ssdii", $name, $category, $price, $stock, $id);
        
        if ($stmt->execute()) {
            $message = "SKU for [$name] updated successfully.";
            // Refresh local data
            $p = $_POST;
            $p['id'] = $id;
        } else {
            $error = "Update Error: " . $conn->error;
        }
        $stmt->close();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Maintain SKU: #<?php echo $id; ?></h1>
    <a href="view_products.php" class="btn btn-outline-teal shadow-sm"><i class="fas fa-arrow-left me-2"></i>Inventory</a>
</div>

<?php if($message): ?>
    <div class="alert alert-success border-0 shadow-sm mb-4"><?php echo $message; ?></div>
<?php endif; ?>

<?php if($error): ?>
    <div class="alert alert-danger border-0 shadow-sm mb-4"><?php echo $error; ?></div>
<?php endif; ?>

<div class="card card-retail border-0 max-width-800 mx-auto">
    <div class="card-body p-4">
        <form action="" method="post">
            <div class="row">
                <div class="col-md-12 mb-4">
                    <label class="form-label small fw-bold text-muted">PRODUCT IDENTIFIER</label>
                    <input type="text" name="product_name" class="form-control form-control-lg" value="<?php echo htmlspecialchars($p['product_name']); ?>" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-muted">SEGMENT / CATEGORY</label>
                    <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($p['category']); ?>" list="cat-suggestions">
                </div>
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-muted">UNIT PRICE ($)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">$</span>
                        <input type="number" name="price" step="0.01" class="form-control border-start-0" value="<?php echo $p['price']; ?>" required>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-4">
                    <label class="form-label small fw-bold text-muted">CURRENT STOCK POOL</label>
                    <input type="number" name="stock_quantity" class="form-control" value="<?php echo $p['stock_quantity']; ?>" required>
                </div>
                <div class="col-md-6 mb-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-teal btn-lg w-100 fw-bold shadow-sm">SAVE CHANGES & REFRESH</button>
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
