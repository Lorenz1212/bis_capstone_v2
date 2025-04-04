<?php
include './../connection/connect.php';
include 'send_email.php';

$message = "";
try {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve form data
        $accountID = $_POST['accountID'];
        $registration_status = $_POST['registration_status'];

        // Update registration status
        $sql = "UPDATE resident_list SET registration_status=? WHERE accountID=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $registration_status, $accountID);

        if ($stmt->execute()) {
            if ($registration_status == 'APPROVED') {
                // Count how many residents have already been registered
                $countQuery = "SELECT COUNT(*) AS total FROM resident_list WHERE registration_status='APPROVED'";
                $result = $conn->query($countQuery);
                $row = $result->fetch_assoc();
                $registrationNumber = str_pad($row['total'] + 1, 4, '0', STR_PAD_LEFT);

                // Generate username and password
                $username = "RSD" . $registrationNumber; // Example: RSD0001
                $password = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*"), 0, 7);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hash password for security

                // Update username and password in the database
                $sql = "UPDATE resident_list SET username=?, password=? WHERE accountID=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $username, $password, $accountID);

                if ($stmt->execute()) {
                    // Fetch user's name and email
                    $sql = "SELECT firstName, email FROM resident_list WHERE accountID=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $accountID);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    if ($row) {
                        $name = $row['firstName'];
                        $email = $row['email'];

                        // Send email
                        $content = [
                            'name' => $name,
                            'username' => $username,
                            'password' => $password,
                            'url' => 'https://barangay.stvincentdefense.tech/'
                        ];

                        send_mail('new_account', $email, $name, 'NEW ACCOUNT', $content);

                        echo json_encode([
                            "status" => "success",
                            "message" => "Resident Approved Successfully",
                            "username" => $username,
                            "password" => $password // Send plain password (optional)
                        ]);
                        exit;
                    } else {

                        echo json_encode(["status" => "error", "message" => "User details not found"]);
                        exit;
                    }
                } else {
                    echo json_encode(["status" => "error", "message" => "Error updating credentials: " . $conn->error]);
                    exit;
                }
            } else {
                $sql = "SELECT firstName, email FROM resident_list WHERE accountID=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $accountID);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                $name = $row['firstName'];
                $email = $row['email'];

                $content = [
                    'name' => $name,
                ];

                send_mail('account_rejected', $email, $name, 'ACCOUNT REJECTED', $content);
                
                echo json_encode(["status" => "success", "message" => "This resident is rejected"]);
                exit;
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Error: " . $conn->error]);
            exit;
        }
    }

    $conn->close();
} catch (Exception $e) {
    echo json_encode(["status" => "error", "message" => "Exception: " . $e->getMessage()]);
    exit;
}
?>
