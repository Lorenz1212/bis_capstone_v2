<?php
include '../connection/connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
$current_page = basename($_SERVER['PHP_SELF']);
session_start();
if(!$_SESSION['accountID']){
    header("Location:../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"> <!-- Include Font Awesome CSS -->
    <link rel="stylesheet" type="text/css" href="../css/navbar.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> <!-- Include jQuery -->
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">
            <div class="logo">
                <img src="../image/logo2.png" alt="">
            </div>

            <div class="title">
                <h1>DIGITAL MANAGEMENT INFORMATION SYSTEM</h1>
                <h2>BARANGAY MARINIG CABUYAO, LAGUNA</h2>
            </div>
        </div>
        
        <div class="navbar-menu">
            <ul>
                <li <?php if ($current_page === "index.php") echo 'class="active"'; ?>><i class="fas fa-user"></i> <a href="index.php">Resident</a></li>
                <li <?php if ($current_page === "payment.php") echo 'class="active"'; ?>><i class="fas fa-money-bill-alt"></i> <a href="payment.php">Payment</a></li>
                <li <?php if ($current_page === "acc_request.php") echo 'class="active"'; ?>><i class="fa-solid fa-hand-point-up"></i></i><a href="acc_request.php">Requests</a></li>

                <li <?php if ($current_page === "document.php") echo 'class="active"'; ?>><i class="fas fa-solid fa-folder"></i> <a href="document.php">Document</a></li>
                <li <?php if ($current_page === "brgyissue.php") echo 'class="active"'; ?>><i class="fas fa-exclamation-triangle"></i> <a href="brgyissue.php">Blotter Report</a></li>

                <li <?php if ($current_page === "report.php") echo 'class="active"'; ?>><i class="fas fa-solid fa-folder"></i> <a href="report.php">Report</a></li>
                <li <?php if ($current_page === "logout.php") echo 'class="active"'; ?>><i class="fas fa-sign-out-alt"></i> <a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <script src="navbar.js"></script>