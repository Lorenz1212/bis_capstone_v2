<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include '../connection/connect.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $fileNo = $_POST['fileNo'];
    $barangay = $_POST['barangay'];
    $purok = $_POST['purok'];
    $incident = $_POST['incident'];
    $placeOfIncident = $_POST['placeOfIncident'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $complainant = $_POST['complainant'];
    $witness = $_POST['witness'];
    $narrative = $_POST['narrative'];

    // Update the database record
    $sql = "UPDATE blotter SET 
            Barangay = '$barangay', 
            Purok = '$purok', 
            Incident = '$incident', 
            Place_of_Incident = '$placeOfIncident', 
            Date = '$date', 
            Time = '$time', 
            Complainant = '$complainant', 
            Witness = '$witness', 
            Narrative = '$narrative' 
            WHERE File_No = '$fileNo'";

    if ($conn->query($sql) === TRUE) {
        // If update is successful, send a success response
        $response = array("success" => true);
        echo json_encode($response);
    } else {
        // If update fails, send an error response
        $response = array("success" => false);
        echo json_encode($response);
    }

    // Close the database connection
    $conn->close();
}
