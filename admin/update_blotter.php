<?php
// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include database connection file
include '../connection/connect.php';

// Check if the request is a POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the POST request
    $fileNo = $_POST['fileno'];
    $barangay = $_POST['barangay'];
    $purok = $_POST['purok'];
    $incident = $_POST['incident'];
    $placeOfIncident = $_POST['placeofincident'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $complainant = $_POST['complainant'];
    $witness = $_POST['witness'];
    $narrative = $_POST['narrative'];

    // Get current file path from database
    $currentFileQuery = "SELECT file FROM blotter WHERE File_No = ?";
    $stmt = $conn->prepare($currentFileQuery);
    $stmt->bind_param("s", $fileNo);
    $stmt->execute();
    $fileResult = $stmt->get_result();
    $currentFile = $fileResult->fetch_assoc()['file'];

    $newFileName = $currentFile; // Default to current file path

    // Only process file upload if a new file was provided
    $targetDir = "./../uploads/evidences/";  
    if (!empty($_FILES["file"]["name"])) {
        $fileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
        $newFileName = "EVD_" . date("Ymd_His") . "." . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        $allowedTypes = ["jpg", "jpeg", "png", "gif"];

        if (!in_array($fileType, $allowedTypes)) {
            $message .= "Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.\n";
        } else {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath)) {
                // Delete old file if it exists and is different from new one
                 $oldTargetFile = $targetDir . $currentFile; 
                if (file_exists($oldTargetFile)) {
                    unlink($oldTargetFile);
                }
            } else {
                $message .= "Sorry, there was an error uploading your file.\n";
                $newFileName = $currentFile; // Revert to old file if upload fails
            }
        }
    }

    if (!empty($message)) {
        echo json_encode(["status" => "error", "message" => $message]);
        exit();
    }


    $sql = "UPDATE blotter SET 
    Barangay = ?, 
    Purok = ?, 
    Incident = ?, 
    Place_of_Incident = ?, 
    Date = ?, 
    Time = ?, 
    Complainant = ?, 
    Witness = ?, 
    Narrative = ?,
    file = ?
    WHERE File_No = ?";

    $stmt = $conn->prepare($sql);

    // Check if prepare succeeded
    if ($stmt === false) {
    $response = array("success" => false, "message" => "Prepare failed: " . $conn->error);
    echo json_encode($response);
    exit();
    }

    // Bind parameters
    $stmt->bind_param("sssssssssss", 
    $barangay, 
    $purok, 
    $incident, 
    $placeOfIncident, 
    $date, 
    $time, 
    $complainant, 
    $witness, 
    $narrative,
    $newFileName,
    $fileNo
    );

    // Execute and check result
    if ($stmt->execute()) {
    $response = array("success" => true, "message" => "Saved Changes");
    } else {
    $response = array("success" => false, "message" => "Error: " . $stmt->error);
    }

    echo json_encode($response);
    // Close the database connection
    $conn->close();
}
