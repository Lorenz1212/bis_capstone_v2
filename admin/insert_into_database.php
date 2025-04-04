<?php
// Include database connection
include '../connection/connect.php';

// Check if the form data has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
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
    $status = $_POST['status'];

    // Perform basic validation
    if (empty($fileNo) || empty($barangay) || empty($purok) || empty($incident) || empty($placeOfIncident) || empty($date) || empty($time) || empty($complainant) || empty($witness) || empty($narrative) || empty($status)) {
        // Error if any fields are empty
        $response = array("success" => false, "message" => "All fields are required");
        echo json_encode($response);
        exit();
    }

    // Prepare SQL statement to insert data into the database
    $sql = "INSERT INTO blotter (File_No, Barangay, Purok, Incident, Place_of_Incident, Date, Time, Complainant, Witness, Narrative, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssssss", $fileNo, $barangay, $purok, $incident, $placeOfIncident, $date, $time, $complainant, $witness, $narrative, $status);

    // Execute the statement
    if ($stmt->execute()) {
        // If insertion is successful
        $response = array("success" => true, "message" => "Data inserted successfully");
        echo json_encode($response);
    } else {
        // If insertion fails
        $response = array("success" => false, "message" => "Failed to insert data into the database");
        echo json_encode($response);
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // If the request method is not POST
    $response = array("success" => false, "message" => "Invalid request method");
    echo json_encode($response);
}
?>
