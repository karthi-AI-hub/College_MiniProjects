<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM donors WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        header("Location: view_donors.php?msg=purged");
    } else {
        echo "Critial Operational Failure: " . $conn->error;
    }
    $stmt->close();
} else {
    header("Location: view_donors.php");
}
?>
