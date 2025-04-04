<?php
include '../connection/connect.php';
// Check if the File_No parameter is set
if(isset($_GET['File_No'])) {
    // Get the File_No from the URL
    $fileNo = $_GET['File_No'];

    // Prepare and execute the DELETE SQL query
    $sql = "DELETE FROM blotter WHERE File_No = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $fileNo);
    if ($stmt->execute()) {
        // Redirect back to the page where the deletion was initiated
        header("Location:brgyissue.php");
        exit();
    } else {
        // Handle deletion failure
        echo "Error: Failed to delete the entry.";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // Handle case where File_No parameter is not set
    echo "Error: File_No parameter is missing.";
}
?>