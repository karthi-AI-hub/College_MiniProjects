<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM events WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_events.php?msg=cancelled");
    } else {
        echo "Error: Unable to cancel event.";
    }
    $stmt->close();
} else {
    header("Location: view_events.php");
}
?>
