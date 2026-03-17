<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id']) && isset($_GET['book_id'])) {
    $trans_id = $_GET['id'];
    $book_id = $_GET['book_id'];
    $return_date = date('Y-m-d');

    // Start Transaction
    $conn->begin_transaction();

    try {
        // 1. Update Transaction Record
        $stmt1 = $conn->prepare("UPDATE transactions SET return_date = ?, status = 'Returned' WHERE id = ?");
        $stmt1->bind_param("si", $return_date, $trans_id);
        $stmt1->execute();

        /* 
           SQL UPDATE Logic (Return):
           When a book is returned, we must update the book's status back to 'Available'
           so it appears in the catalog for other students to borrow.
        */
        $stmt2 = $conn->prepare("UPDATE books SET status = 'Available' WHERE id = ?");
        $stmt2->bind_param("i", $book_id);
        $stmt2->execute();

        $conn->commit();
        header("Location: transactions.php?msg=returned");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        echo "Critial Error during return processing: " . $e->getMessage();
    }
} else {
    header("Location: transactions.php");
    exit();
}
?>
