<?php
// Include the database connection file
include '../connection/connect.php';

// Check if the national ID parameter is set in the URL
if (isset($_GET['id'])) {
    // Sanitize the input to prevent SQL injection
    $accountID = mysqli_real_escape_string($conn, $_GET['id']);

    // SQL query to delete the resident from the resident_list table
    $sql = "DELETE FROM resident_list WHERE accountID='$accountID'";

    // Execute the query
    if (mysqli_query($conn, $sql)) {
        // Redirect the user back to the resident list page after successful deletion
        header("Location: index.php");
        exit(); // Stop further execution
    } else {
        // Display an error message if the deletion fails
        echo "Error deleting record: " . mysqli_error($conn);
    }
} else {
    // If the national ID parameter is not set, display an error message
    echo "National ID not specified.";
}

// Close the database connection
mysqli_close($conn);
?>
