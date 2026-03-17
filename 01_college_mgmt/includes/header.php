<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['admin']) && basename($_SERVER['PHP_SELF']) != 'login.php') {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>College Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        #wrapper {
            display: flex;
            min-height: 100vh;
        }
        #sidebar-wrapper {
            min-width: 250px;
            max-width: 250px;
            background-color: #343a40;
            color: #fff;
        }
        #page-content-wrapper {
            flex: 1;
            padding: 20px;
        }
        .sidebar-heading {
            padding: 20px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            border-bottom: 1px solid #4b545c;
        }
        .list-group-item {
            background-color: transparent;
            color: #ccc;
            border: none;
        }
        .list-group-item:hover, .list-group-item.active {
            background-color: #495057;
            color: #fff;
        }
        /* Print Styles */
        @media print {
            #sidebar-wrapper, .navbar, .btn-print-hide {
                display: none !important;
            }
            #page-content-wrapper {
                padding: 0;
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div id="wrapper">
