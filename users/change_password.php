<?php
session_start();
require_once '../connection/connect.php';

// Check if user is logged in
if (!isset($_SESSION['accountID'])) {
    header("Location: ../index.php");
    exit();
}

$accountID = $_SESSION['accountID'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Check if new password matches confirmation
    if ($newPassword !== $confirmNewPassword) {
        $error = "New passwords do not match!";
        header("Location: index.php");
        exit();
    } else {
        // Fetch the current password from the database
        $sql = "SELECT password FROM resident_list WHERE accountID = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            $error = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
            header("Location: index.php");
            exit();
        }
        $stmt->bind_param("s", $accountID);
        if (!$stmt->execute()) {
            $error = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            header("Location: index.php");
            exit();
        }
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if (!$user || $currentPassword !== $user['password']) {
            $error = "Current password is incorrect!";
            header("Location: index.php");
            exit();
        } else {
            // Update the password in the database without hashing
            $updateSql = "UPDATE resident_list SET password = ? WHERE accountID = ?";
            $updateStmt = $conn->prepare($updateSql);
            if (!$updateStmt) {
                $error = "Prepare failed: (" . $conn->errno . ") " . $conn->error;
                header("Location: index.php");
                exit();
            }
            $updateStmt->bind_param("ss", $newPassword, $accountID);
            if ($updateStmt->execute()) {
                $success = "Password changed successfully!";
                header("Location: index.php");
                exit();
            } else {
                $error = "Error changing password: " . $conn->error;
                header("Location: index.php");
                exit();
            }
        }
    }
}
