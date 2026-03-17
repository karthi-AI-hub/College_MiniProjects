<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Using prepared statement for decommissioning
    $stmt = $conn->prepare("DELETE FROM vehicles WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_vehicles.php?msg=decommissioned");
    } else {
        echo "Critial Error during decommissioning: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: view_vehicles.php");
}
?>
