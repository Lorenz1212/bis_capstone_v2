<?php
// Include database connection
include '../connection/connect.php';

// Check if delete request is sent
if(isset($_POST['delete_refNo'])) {
    $refNo = $_POST['delete_refNo'];
    
    // Delete the record from the database
    $sqlDelete = "DELETE FROM Payment WHERE RefNo = '$refNo'";
    if(mysqli_query($conn, $sqlDelete)) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Close database connection
mysqli_close($conn);
?>
