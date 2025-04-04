<?php
include '../connection/connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accountID = $_POST['accountID'];
    $username = $_POST['username'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'] ?? '';
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Check if username or email already exists (excluding current user)
    $check = $conn->prepare("SELECT * FROM admin WHERE (username = ? OR email = ?) AND accountID != ?");
    $check->bind_param("ssi", $username, $email, $accountID);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Username or email already exists"]);
        exit();
    }

    if ($password) {
        $stmt = $conn->prepare("UPDATE admin SET username=?, password=?, fname=?, lname=?, mname=?, email=? WHERE accountID=?");
        $stmt->bind_param("ssssssi", $username, $password, $fname, $lname, $mname, $email, $accountID);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET username=?, fname=?, lname=?, mname=?, email=? WHERE accountID=?");
        $stmt->bind_param("sssssi", $username, $fname, $lname, $mname, $email, $accountID);
    }

    if ($stmt->execute()) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "message" => $stmt->error]);
    }
}
?>