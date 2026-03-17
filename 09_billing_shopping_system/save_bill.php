<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cust_name = trim($_POST['customer_name']);
    $prod_id = $_POST['product_id'];
    $qty = (int)$_POST['quantity'];
    $total = (float)$_POST['total_amount'];

    if (empty($cust_name) || empty($prod_id) || $qty <= 0) {
        die("Fatal Transaction Error: Missing payload data.");
    }

    // Start Transaction
    $conn->begin_transaction();

    try {
        // 1. Double check stock for safety (prevent race conditions)
        $stock_check = $conn->prepare("SELECT stock_quantity, product_name FROM products WHERE id = ? FOR UPDATE");
        $stock_check->bind_param("i", $prod_id);
        $stock_check->execute();
        $res = $stock_check->get_result()->fetch_assoc();

        if ($res['stock_quantity'] < $qty) {
            throw new Exception("Insufficient stock for [" . $res['product_name'] . "]. Current: " . $res['stock_quantity']);
        }

        /* 
           UPDATE Logic (Stock Subtraction):
           Decrement stock by the purchased quantity. 
           This is atomic because we used FOR UPDATE lock above.
        */
        $update_stock = $conn->prepare("UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?");
        $update_stock->bind_param("ii", $qty, $prod_id);
        $update_stock->execute();

        // 2. Save Bill Record
        // We store the item details as JSON for auditing purposes (Mandatory for High Value Logic)
        $bill_details = json_encode(['product_name' => $res['product_name'], 'qty' => $qty, 'unit_total' => $total]);
        
        $save_bill = $conn->prepare("INSERT INTO bills (customer_name, total_amount, bill_after_stock_update) VALUES (?, ?, ?)");
        $save_bill->bind_param("sds", $cust_name, $total, $bill_details);
        $save_bill->execute();

        $conn->commit();
        header("Location: view_bills.php?status=success&id=" . $conn->insert_id);
        exit();

    } catch (Exception $e) {
        $conn->rollback();
        die("Transaction Aborted: " . $e->getMessage());
    }
} else {
    header("Location: create_bill.php");
    exit();
}
?>
