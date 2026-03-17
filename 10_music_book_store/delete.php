<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM inventory WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_catalog.php?status=purged");
    } else {
        echo "Safety Halt: Could not decommissioning asset. Record might be linked to external logs.";
    }
    $stmt->close();
} else {
    header("Location: view_catalog.php");
}
?>
