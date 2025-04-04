<?php
// Include database connection
include '../connection/connect.php';

// Retrieve form data
$type = $_POST['type'];
$resident_id = $_POST['resident_id'];
$amount = $_POST['amount'];
$paymentDate = $_POST['paymentDate'];
$Purpose = $_POST['Purpose'];
$type_flag = $_POST['type_flag'];

// Get the last employee_no and increment it properly
$code_query = "SELECT MAX(RefNo) AS last_code FROM payment WHERE RefNo LIKE 'REQ-" . date('Ym') . "%'";
$code_result = mysqli_query($conn, $code_query);
$row = $code_result->fetch_assoc();
$last_code = $row['last_code'] ?? 'REQ-' . date('Ym') . '0001'; // Default if no records found

// Extract the last 4 digits and increment
$new_number = (int)substr($last_code, -4) + 1;
$new_code = 'REQ-' . date('Ym') . str_pad($new_number, 4, "0", STR_PAD_LEFT);

// SQL query to insert data into the Payment table
$sql = "INSERT INTO Payment (RefNo, Type, resident_id, Amount, PaymentDate, Purpose, type_flag, created_at) 
        VALUES ('$new_code', '$type', '$resident_id', '$amount', '$paymentDate', '$Purpose', '$type_flag',now())";
   
// Execute the SQL query
if (mysqli_query($conn, $sql)) {
    $sql1 = "INSERT INTO request (RefNo, Type, resident_id, Amount, PaymentDate, Purpose, type_flag, status,created_at) 
        VALUES ('$new_code', '$type', '$resident_id', '$amount', '$paymentDate', '$Purpose', '$type_flag', 'APPROVED',now())";    
    mysqli_query($conn, $sql1);
    // Redirect back to the payment page
    header("Location: payment.php");
    exit();
} else {
    // Display error message
    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
}

// Close database connection
mysqli_close($conn);
