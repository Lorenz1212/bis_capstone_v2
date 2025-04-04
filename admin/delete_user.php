<?php
include '../connection/connect.php';

$data = json_decode(file_get_contents('php://input'), true);
$accountID = $data['accountID'];

$stmt = $conn->prepare("DELETE FROM admin WHERE accountID = ?");
$stmt->bind_param("i", $accountID);

if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => $stmt->error]);
}
?>