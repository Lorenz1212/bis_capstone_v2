<?php


// Include database connection file
include '../connection/connect.php';

// Check if form data is received via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $fileNo = $_POST['editFileNo'];
    $barangay = $_POST['editBarangay'];
    $purok = $_POST['editPurok'];
    $incident = $_POST['editIncident'];
    $placeOfIncident = $_POST['editPlaceOfIncident'];
    $date = $_POST['editDate'];
    $time = $_POST['editTime'];
    $complainant = $_POST['editComplainant'];
    $witness = $_POST['editWitness'];
    $narrative = $_POST['editNarrative'];
    $status = $_POST['editStatus'];

    // Prepare SQL statement to update the entry
    $sql = "UPDATE blotter SET
            Barangay = '$barangay',
            Purok = '$purok',
            Incident = '$incident',
            Place_of_Incident = '$placeOfIncident',
            Date = '$date',
            Time = '$time',
            Complainant = '$complainant',
            Witness = '$witness',
            Narrative = '$narrative',
            Status = '$status'
            WHERE File_No = '$fileNo'";

    // Execute the SQL statement
    if ($conn->query($sql) === TRUE) {
        // Return success response
        $response = array('success' => true);
        echo json_encode($response);
    } else {
        // Return error response
        $response = array('success' => false);
        echo json_encode($response);
    }

    // Close database connection
    $conn->close();
} else {
    // Invalid request method
    http_response_code(405); // Method Not Allowed
    echo "Invalid request method";
}
?>
