<?php
include './../connection/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = $_POST['firstName'];
    $middleName = $_POST['middleName'];
    $lastName = $_POST['lastName'];
    $suffix = (isset($_POST['Suffix']))?$_POST['Suffix']:'';
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
    $occupation = $_POST['occupation'];
    $disability = $_POST['disability'];
    $vaccination_status = $_POST['vaccination_status'];
    $vaccine = $_POST['vaccine'];
    $vaccination_type = $_POST['vaccination_type'];
    $vaccination_date = $_POST['vaccination_date'];
    $national_id = $_POST['national_id'];

    $message = "";

    $query = "SELECT email FROM resident_list WHERE email = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message .= "Email address already exists!\n";
    }

    $query = "SELECT national_id FROM resident_list WHERE national_id = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $national_id);
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
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    $status = 'APPROVED';  // Store the 'APPROVED' string in a variable

    $sql = "INSERT INTO resident_list (
        firstName, middleName, lastName,
        suffix, address, house_no, birthdate, age, gender, 
        civil_status, birthplace, religion, email, contact_number, voter_status, 
        education_attainment, occupation, disability, 
        vaccination_status, vaccine, vaccination_type, vaccination_date, national_id, registration_status, file
    ) VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    // Bind parameters, including the status variable
    $stmt->bind_param("sssssssssssssssssssssssss", $firstName, $middleName, $lastName, $suffix, $address, $house_no, $birthdate, $age, $gender, $civil_status, $birthplace, $religion, $email, $contact_number, $voter_status, $education_attainment, $occupation, $disability, $vaccination_status, $vaccine, $vaccination_type, $vaccination_date, $national_id, $status, $newFileName);


    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Add new resident successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
    }
}
?>
