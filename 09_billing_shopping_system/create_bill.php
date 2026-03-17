<?php
include 'includes/header.php';
include 'includes/sidebar.php';
require_once 'config.php';

// Fetch products for dropdown
$products = $conn->query("SELECT id, product_name, price, stock_quantity FROM products WHERE stock_quantity > 0 ORDER BY product_name ASC");

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark fw-bold">Point of Sale (POS)</h1>
    <a href="view_bills.php" class="btn btn-outline-dark shadow-sm"><i class="fas fa-history me-1"></i>Sale History</a>
</div>

<div class="row">
    <div class="col-lg-7 mx-auto">
        <div class="card card-retail border-0">
            <div class="card-header bg-teal text-white py-3">
                <h5 class="mb-0 fw-bold"><i class="fas fa-shopping-cart me-2"></i>Generate Checkout Invoice</h5>
            </div>
            <div class="card-body p-4">
                <form action="save_bill.php" method="post" id="billingForm">
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">CUSTOMER IDENTIFIER</label>
                        <input type="text" name="customer_name" class="form-control form-control-lg" placeholder="Name or Walk-in ID" required>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-8">
                            <label class="form-label small fw-bold text-muted">SELECT SKU</label>
                            <select name="product_id" id="product_select" class="form-select form-select-lg" required>
                                <option value="" data-price="0">Choose Product...</option>
                                <?php while($p = $products->fetch_assoc()): ?>
                                    <option value="<?php echo $p['id']; ?>" data-price="<?php echo $p['price']; ?>" data-stock="<?php echo $p['stock_quantity']; ?>">
                                        <?php echo htmlspecialchars($p['product_name']); ?> ($<?php echo $p['price']; ?>) | Stock: <?php echo $p['stock_quantity']; ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold text-muted">UNIT QUANTITY</label>
                            <input type="number" name="quantity" id="qty_input" class="form-control form-control-lg" min="1" value="1" required>
                        </div>
                    </div>

                    <div class="bg-light p-4 rounded-3 border mb-4">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Unit Price:</span>
                            <span class="fw-bold" id="unit_price_display">$0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                            <span class="text-muted">Selected Qty:</span>
                            <span class="fw-bold" id="qty_display">x 1</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-end">
                            <span class="h5 mb-0 fw-bold">TOTAL PAYABLE:</span>
                            <span class="h3 mb-0 fw-bold text-teal" id="total_display">$0.00</span>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-teal btn-lg w-100 fw-bold py-3 shadow">
                        <i class="fas fa-check-double me-2"></i>POST TRANSACTION & PRINT
                    </button>
                    <input type="hidden" name="total_amount" id="total_amount_hidden">
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const productSelect = document.getElementById('product_select');
    const qtyInput = document.getElementById('qty_input');
    const unitPriceDisplay = document.getElementById('unit_price_display');
    const qtyDisplay = document.getElementById('qty_display');
    const totalDisplay = document.getElementById('total_display');
    const totalAmountHidden = document.getElementById('total_amount_hidden');

    function calculateTotal() {
        const option = productSelect.options[productSelect.selectedIndex];
        const price = parseFloat(option.getAttribute('data-price')) || 0;
        const qty = parseInt(qtyInput.value) || 0;
        const maxStock = parseInt(option.getAttribute('data-stock')) || 0;

        if (qty > maxStock) {
            alert('Error: Quantity exceeds available stock (' + maxStock + ')');
            qtyInput.value = maxStock;
            return calculateTotal();
        }

        /* 
           PHP Math Logic Equivalent (JS for Front-end feedback):
           Total = Price * Quantity
        */
        const total = price * qty;
        
        unitPriceDisplay.textContent = '$' + price.toFixed(2);
        qtyDisplay.textContent = 'x ' + qty;
        totalDisplay.textContent = '$' + total.toFixed(2);
        totalAmountHidden.value = total.toFixed(2);
    }

    productSelect.addEventListener('change', calculateTotal);
    qtyInput.addEventListener('input', calculateTotal);
</script>

<?php
include 'includes/footer.php';
?>
