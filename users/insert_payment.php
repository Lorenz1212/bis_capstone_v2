<?php
include '../connection/connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $refNo = $_POST['refNo'];
    $type = $_POST['type'];
    $name = $_POST['name'];
    $amount = $_POST['amount'];
    $paymentDate = $_POST['paymentDate'];

    // Prepare SQL query
    $sql = "INSERT INTO request (RefNo, Type, Name, Amount, PaymentDate) VALUES (?, ?, ?, ?, ?)";

    // Prepare and bind
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "sssss", $refNo, $type, $name, $amount, $paymentDate);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            // Redirect to the page with the table after successful insertion
            header("Location: request.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }

    // Close the connection
    mysqli_close($conn);
} else {
    echo "Invalid request method.";
}
