<?php
include '../connection/connect.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);
$current_page = basename($_SERVER['PHP_SELF']);

if (!isset($_SESSION['accountID'])) {
    header("Location:../index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BIS Brgy Mamatid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="../css/document.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
</head>
<body>

<!-- Bootstrap Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center" href="#">
            <img src="../image/logo2.png" alt="Logo" width="40" height="40" class="d-inline-block align-text-top me-2">
            <div>
                <h6 class="mb-0 text-white">DIGITAL MANAGEMENT SYSTEM</h6>
                <p class="mb-0 text-white-50">Brgy. Mamatid, Cabuyao, Laguna</p>
            </div>
        </a>

        <!-- Toggle button for mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Items -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "index.php") ? 'active' : '' ?>" href="index.php">
                        <i class="fas fa-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "resident.php") ? 'active' : '' ?>" href="resident.php">
                        <i class="fas fa-users"></i> Residents
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "payment.php") ? 'active' : '' ?>" href="payment.php">
                        <i class="fas fa-money-bill-alt"></i> Payment
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "acc_request.php") ? 'active' : '' ?>" href="acc_request.php">
                        <i class="fa-solid fa-hand-point-up"></i> Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "document.php") ? 'active' : '' ?>" href="document.php">
                        <i class="fas fa-folder"></i> Document
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "brgyissue.php") ? 'active' : '' ?>" href="brgyissue.php">
                        <i class="fas fa-exclamation-triangle"></i> Blotter Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "announcement.php") ? 'active' : '' ?>" href="announcement.php">
                        <i class="fas fa-microphone"></i> Announcement
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "users.php") ? 'active' : '' ?>" href="users.php">
                        <i class="fas fa-user"></i> Accounts
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= ($current_page === "report.php") ? 'active' : '' ?>" href="report.php">
                        <i class="fas fa-folder"></i> Report
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger <?= ($current_page === "logout.php") ? 'active' : '' ?>" href="logout.php">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

