<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_products.php?msg=purged");
    } else {
        echo "Error: Could not purge item. It may be linked to active bills.";
    }
    $stmt->close();
} else {
    header("Location: view_products.php");
}
?>
