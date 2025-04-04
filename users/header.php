<?php  
require_once '../connection/connect.php';

session_start(); 
// Check if user is logged in
if (!isset($_SESSION['accountID'])) {
    header("Location: ../index.php");
    exit();
}
$accountID = $_SESSION['accountID'];

$sqlcount = "SELECT COUNT(*) as count_request 
FROM request 
WHERE notification_flag IN (1,2) 
AND status IN ('APPROVED', 'DISAPPROVED') 
AND resident_id = ?";

$countStmt = $conn->prepare($sqlcount);
$countStmt->bind_param("i", $accountID);
$countStmt->execute();
$countResult = $countStmt->get_result();
$countRow = $countResult->fetch_assoc();
$count_request = $countRow['count_request'];

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Resident Information</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
        <link rel="stylesheet" href="css/nav.css">
        <link rel="stylesheet" href="../css/toastify.min.css">
    </head>
<body>