<?php
include 'user_navbar.php';

$accountID = $_SESSION['accountID'];

// Handle accepting a request
if (isset($_GET['accept']) && !empty($_GET['accept'])) {
    $refNoToAccept = $_GET['accept'];

    // Fetch the request details from the request table
    
    $requestQuery = "SELECT * FROM request WHERE RefNo = ?";
    if ($stmt = mysqli_prepare($conn, $requestQuery)) {
        mysqli_stmt_bind_param($stmt, "s", $refNoToAccept);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Insert the request details into the payment table
            $insertQuery = "INSERT INTO payment (RefNo, Type, resident_id, Amount, PaymentDate, Purpose) VALUES (?, ?, ?, ?, ?, ?)";
            if ($insertStmt = mysqli_prepare($conn, $insertQuery)) {
                mysqli_stmt_bind_param($insertStmt, "ssssss", $row['RefNo'], $row['Type'], $row['resident_id'], $row['Amount'], $row['PaymentDate'], $row['Purpose']);

                if (mysqli_stmt_execute($insertStmt)) {
                    // Delete the request from the request table
                    $deleteQuery = "UPDATE request SET status ='APPROVED' , notification_flag=1, updated_at = now() WHERE RefNo = ?";
                    if ($deleteStmt = mysqli_prepare($conn, $deleteQuery)) {
                        mysqli_stmt_bind_param($deleteStmt, "s", $refNoToAccept);
                        mysqli_stmt_execute($deleteStmt);
                        mysqli_stmt_close($deleteStmt);
                    }

                    // Redirect to the same page to refresh the list of requests
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Error inserting payment: " . mysqli_error($conn);
                }

                mysqli_stmt_close($insertStmt);
            } else {
                echo "Error preparing insert statement: " . mysqli_error($conn);
            }
        } else {
            echo "Request not found.";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing request statement: " . mysqli_error($conn);
    }
}

// Fetch requests from the database
    $sql = "SELECT *, 
        CONCAT(
            firstName, ' ', 
            IFNULL(CONCAT(UPPER(LEFT(middleName, 1)), '. '), ''), 
            lastName
        ) AS Name 
        FROM request A
        LEFT JOIN resident_list B ON A.resident_id = B.accountID ORDER BY A.created_at DESC";
        $result = mysqli_query($conn, $sql);
    ?>
    <div class="container">
        <h2>Requests</h2>
        <table class="request_table">
            <thead>
                <tr>
                    <th>Ref No.</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Purpose</th>
                    <th>STATUS</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display request data in the table
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $action = "";
                        if($row['status'] == 'REQUEST'){
                            $action =" <a class='accept-btn' href='#' onclick='confirmAccept(\"" . $row['RefNo'] . "\")'>Accept</a>";
                        }else{
                            $action ="-";
                        }
                        echo "<tr>";
                        echo "<td>" . $row['RefNo'] . "</td>";
                        echo "<td>" . $row['Type'] . " - ".$row['type_flag']."</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>₱" . $row['Amount'] . "</td>";
                        echo "<td>" . $row['PaymentDate'] . "</td>";
                        echo "<td>" . $row['Purpose'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td>". $action."</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No requests found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
function confirmAccept(refNo) {
    if (confirm("Are you sure you want to accept this payment?")) {
        window.location.href = "?accept=" + refNo;
    }
}
</script>
</body>

</html>