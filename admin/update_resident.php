<?php
include '../connection/connect.php'; // Include your database connection file

// Check if the form data is received via POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve the form data
    $accountID = $_POST['accountID'];
    $vaccinationStatus = $_POST['vaccinationStatus'];
    $vaccine = $_POST['vaccine'];
    $vaccinationType = $_POST['vaccinationType']; // Add vaccination type
    $vaccinationDate = $_POST['vaccinationDate']; // Add vaccination date

    // Prepare an SQL statement to update the vaccination status, vaccine, vaccination type, and vaccination date for the resident
    $updateQuery = "UPDATE resident_list SET vaccination_status = ?, vaccine = ?, vaccination_type = ?, vaccination_date = ? WHERE accountID = ?";
    
    // Prepare and bind parameters
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("sssss", $vaccinationStatus, $vaccine, $vaccinationType, $vaccinationDate, $accountID);
    
    // Execute the statement
    if ($stmt->execute()) {
        // If the update is successful, send a JSON response with success status
        $response = array(
            'status' => 'success',
            'message' => 'Vaccination details updated successfully'
            
        );
        echo json_encode($response);
    } else {
        // If the update fails, send a JSON response with error status and message
        $response = array(
            'status' => 'error',
            'message' => 'Failed to update vaccination details'
        );
        echo json_encode($response);
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If the request method is not POST, send an error response
    $response = array(
        'status' => 'error',
        'message' => 'Invalid request method'
    );
    echo json_encode($response);
}
?>
