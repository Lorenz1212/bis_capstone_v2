<?php
// Include the database connection file
include '../connection/connect.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $accountID = $_POST['accountID'];
    $national_id = $_POST['national_id'];
    $last_name = $_POST['lastName'];
    $first_name = $_POST['firstName'];
    $middle_name = $_POST['middleName'];
    $Suffix = $_POST['Suffix'];
    $address = $_POST['address'];
    $house_no = $_POST['house_no'];
    $birthdate = $_POST['birthdate'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $civil_status = $_POST['civil_status'];
    $birthplace = $_POST['birthplace'];
    $religion = $_POST['religion'];
    $email = $_POST['email'];
    $contact_number = $_POST['contact_number'];
    $voter_status = $_POST['voter_status'];
    $education_attainment = $_POST['education_attainment'];
    $vaccination_status = $_POST['vaccination_status'];
    $occupation = $_POST['occupation'];
    $disability = $_POST['disability'];

    // Check for duplicate username, email, or national ID
    $query = "SELECT username FROM resident_list WHERE username = ? AND accountID != ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $username,$accountID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message .= "Username already exists!\n";
    }

    $query = "SELECT email FROM resident_list WHERE email = ? AND accountID != ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $email,$accountID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message .= "Email address already exists!\n";
    }

    $query = "SELECT national_id FROM resident_list WHERE national_id = ? AND accountID != ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $national_id,$accountID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message .= "National ID already exists!\n";
    }
   
   
    $targetDir = "./../uploads/";  
    $fileType = strtolower(pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION));
    $newFileName = "IMG_" . date("Ymd_His") . "." . $fileType;
    $targetFilePath = $targetDir . $newFileName;

    $allowedTypes = ["jpg", "jpeg", "png", "gif"];

    if (!in_array($fileType, $allowedTypes)) {
        $message ."Invalid file type. Allowed types: JPG, JPEG, PNG, GIF.\n";
    } 

    if (!empty($message)) {
        echo json_encode(["status" => "error", "message" => $message]);
        exit();
    }

    move_uploaded_file($_FILES["file"]["tmp_name"], $targetFilePath);

    // SQL query to update resident information using prepared statement
    $sql = "UPDATE resident_list 
            SET national_id=?, lastName=?, firstName=?, middleName=?, Suffix=?, address=?, house_no=?, birthdate=?, age=?, gender=?, civil_status=?, birthplace=?, religion=?, email=?, contact_number=?, voter_status=?, education_attainment=?, vaccination_status=?, occupation=?, disability=?
            WHERE accountID=?";

    // Prepare the SQL statement
    $stmt = $conn->prepare($sql);

    // Bind parameters to the prepared statement
    $stmt->bind_param("ssssssssssssssssssssi", $national_id, $last_name, $first_name, $middle_name, $Suffix, $address, $house_no, $birthdate, $age, $gender, $civil_status, $birthplace, $religion, $email, $contact_number, $voter_status, $education_attainment, $vaccination_status, $occupation, $disability, $accountID);

    // Execute the prepared statement
    if ($stmt->execute()) {
        // Redirect back to the resident list page with a success message
        header("Location: home.php?alert=Resident information updated successfully");
        exit();
    } else {
        // Redirect back to the resident list page with an error message
        header("Location: home.php?error=Error updating resident information: " . $stmt->error);
        exit();
    }

    // Close the prepared statement
    $stmt->close();
} else {
    // If the form is not submitted, redirect back to the resident list page
    header("Location: home.php");
    exit();
}

// Close the database connection
$conn->close();
?>
