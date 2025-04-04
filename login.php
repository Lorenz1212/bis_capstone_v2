<?php
session_start();

if (isset($_POST['login'])) {
    require 'connection/connect.php'; // Assuming this file establishes your database connection

    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check against admin table
    $admin_query = "SELECT accountID,password FROM admin WHERE username=?";
    $stmt = $conn->prepare($admin_query);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($accountID,$cpassword);
        $stmt->fetch();
        if (password_verify($password, $cpassword)) {
            $_SESSION['accountID'] = $accountID;
            echo '<script>';
            echo 'window.alert("The account ID is: ' . $_SESSION['accountID'] . '");';
            echo '</script>';
            header('Location: admin/index.php'); // Redirect to admin dashboard
            exit();     
        }
    }

    // Check against resident list table
    $user_query = "SELECT accountID FROM resident_list WHERE username=? AND password=?";
    $stmt = $conn->prepare($user_query);
    $stmt->bind_param('ss', $username, $password);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($accountID);
        $stmt->fetch();
        $_SESSION['accountID'] = $accountID;
        echo '<script>';
        echo 'window.alert("The account ID is: ' . $_SESSION['accountID'] . '");';
        echo '</script>';
        header('Location: users/index.php');
        exit();
    }

    // If no matching user found in either table
    header('Location: index.php?error=1'); // Redirect back to login with error
    exit();
}
