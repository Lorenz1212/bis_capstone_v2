<?php include 'header.php'; ?>
<?php include 'nav.php'; ?>
<?php
$accountID = $_SESSION['accountID'];

// Fetch user's name from the database based on their accountID
$userQuery = "SELECT firstName FROM resident_list WHERE accountID = ?";
if ($stmt = mysqli_prepare($conn, $userQuery)) {
    mysqli_stmt_bind_param($stmt, "s", $accountID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    $userName = $user['firstName'];
    mysqli_stmt_close($stmt);

    $updateSql = $conn->prepare("UPDATE request SET notification_flag = 0 WHERE resident_id = ? AND notification_flag in (1,2)");
    $updateSql->bind_param("s", $accountID);
    $updateSql->execute();
} else {
    echo "Error fetching user's name: " . mysqli_error($conn);
}



// Handle form submission for inserting a new request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['type'])) {
    // Retrieve form data
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $paymentDate = $_POST['paymentDate'];
    $purpose = $_POST['purpose'];
    $type_flag = $_POST['type_flag'];

    // Retrieve last request code from request table, not payment
    $code_query = "SELECT MAX(RefNo) AS last_code FROM request WHERE RefNo LIKE 'REQ-" . date('Ym') . "%'";
    $code_result = mysqli_query($conn, $code_query);
    $row = mysqli_fetch_assoc($code_result);

    // Check if a previous request exists
    if ($row && $row['last_code']) {
        // Extract the last 4 digits and increment
        $new_number = (int)substr($row['last_code'], -4) + 1;
    } else {
        // If no previous request, start from 0001
        $new_number = 1;
    }

    // Generate new RefNo
    $refNo = 'REQ-' . date('Ym') . str_pad($new_number, 4, "0", STR_PAD_LEFT);


    // Prepare SQL query
    $sql = "INSERT INTO request (RefNo, Type, resident_id, Amount, PaymentDate, Purpose, type_flag, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, now())";

    // Prepare and bind
    if ($stmt = mysqli_prepare($conn, $sql)) {
        mysqli_stmt_bind_param($stmt, "ssissss", $refNo, $type, $accountID, $amount, $paymentDate, $purpose, $type_flag);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Handle deletion of a request
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $refNoToDelete = $_GET['delete'];

    $deleteSql = "DELETE FROM request WHERE RefNo = ?";
    if ($stmt = mysqli_prepare($conn, $deleteSql)) {
        mysqli_stmt_bind_param($stmt, "s", $refNoToDelete);

        // Execute the statement
        if (mysqli_stmt_execute($stmt)) {
            header("Location: " . $_SERVER['PHP_SELF']);
            exit();
        } else {
            echo "Error deleting record: " . mysqli_error($conn);
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    } else {
        echo "Error preparing statement: " . mysqli_error($conn);
    }
}

// Fetch request data from the database
$sql = "SELECT A.*, 
    CONCAT(
        B.firstName, ' ', 
        IFNULL(CONCAT(LEFT(B.middleName, 1), '.'), ''), ' ', 
        B.lastName, 
        IF(B.Suffix IS NOT NULL AND B.Suffix != '', CONCAT(' ', B.Suffix), '')
    ) AS full_name 
FROM request A
LEFT JOIN resident_list B ON A.resident_id = B.accountID
WHERE A.resident_id = $accountID"; 

$result = mysqli_query($conn, $sql);


?>

<div class="content">
    <div class="table-container">
        <div class="filter-container">
            <button class="btn-primary" onclick="openPopupForm()">+Request</button>
        </div>
        <div class="payment_container">
            <table id="paymentTable">
                <thead>
                    <tr>
                        <th>Ref No.</th>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Payment Date</th>
                        <th>Purpose</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Display request data in the table
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $action = "-";
                            if($row['status'] === 'REQUEST'){
                                $action = "<button class='btn-danger' onclick='deleteRow(\"" . $row['RefNo'] . "\")'><i class='fas fa-trash'></i></button>";
                            }
                            echo "<tr>";
                            echo "<td>" . $row['RefNo'] . "</td>";
                            echo "<td>" . $row['Type'] . "</td>";
                            echo "<td>" . $row['full_name'] . "</td>";
                            echo "<td>₱" . $row['Amount'] . "</td>"; // Add peso sign to the amount
                            echo "<td>" . $row['PaymentDate'] . "</td>"; // Assuming the date is stored in 'YYYY-MM-DD' format
                            echo "<td>" . $row['Purpose'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td> $action </td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' style='text-align:center'>No requests found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Popup Form -->
<div id="popupForm" class="popup">
    <span class="close" onclick="closePopupForm()">&times;</span>
    <h3>Add New Request</h3>
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
        <label for="type">Type:</label>
        <select name="type" id="type" onchange="setAmount()" required>
            <option value="" selected disabled>Select Certificate</option>
            <?php
            $clearances = ["Brgy clearance", "Business Clearance", "Building Clearance", "Barangay Certificate", "Certificate of Indigency", "Cedula"];
            foreach ($clearances as $clearance) {
                echo "<option value='$clearance'>$clearance</option>";
            }
            ?>
        </select>
        <br><br>
        <select name="type_flag" id="type_flag" required>
            <option value="" disabled selected>---Select Type----</option>
            <option value="NEW">New</option>
            <option value="RENEW">Renew</option>
        </select><br><br>
        <label for="amount">Amount:</label>
        <input type="number" id="amount" name="amount" step="0.01" required readonly><br><br>
        <label for="purpose">Purpose:</label>
        <textarea name="purpose" rows="3" required></textarea><br><br>
        <input type="hidden" name="paymentDate" value="<?php echo date('Y-m-d'); ?>"> <!-- Set the current date automatically -->
        <input type="submit" value="Submit">
    </form>
</div>

<?php include 'footer.php'; ?>