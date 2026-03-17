<?php
session_start();
require_once 'config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: view_departments.php?msg=auth");
    exit();
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Using prepared statement to prevent SQL injection
    $stmt = $conn->prepare("DELETE FROM departments WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_departments.php?msg=deleted");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: view_departments.php");
}
?>
