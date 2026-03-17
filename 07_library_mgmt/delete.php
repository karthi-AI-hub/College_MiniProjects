<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin'])) { header("Location: login.php"); exit(); }

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    // Check if book is issued
    $check = $conn->prepare("SELECT status FROM books WHERE id = ?");
    $check->bind_param("i", $id);
    $check->execute();
    $status = $check->get_result()->fetch_assoc()['status'];
    
    if ($status == 'Available') {
        $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } else {
        die("Cannot delete a book that is Currently Issued on loan.");
    }
}
header("Location: view_books.php?msg=purged");
exit();
?>
