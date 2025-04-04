<?php
// Include your database connection file
include '../connection/connect.php';

// Check if RefNo is provided in the URL
if(isset($_GET['RefNo'])) {
    // Sanitize the input to prevent SQL injection
    $refNo = mysqli_real_escape_string($conn, $_GET['RefNo']);

    // Query to delete the payment entry from the database
    $deleteQuery = "UPDATE payment SET status = 2 WHERE RefNo = '$refNo'";

    // Perform the deletion query
    if(mysqli_query($conn, $deleteQuery)) {
        // If deletion is successful, redirect back to the table view page
        header("Location: document.php");
        exit();
    } else {
        // If an error occurs, display an error message
        echo "Error deleting payment: " . mysqli_error($conn);
    }
} else {
    // If RefNo is not provided in the URL, display an error message
    echo "RefNo not provided.";
}
?>
