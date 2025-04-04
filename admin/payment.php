<?php
// Include database connection
require '../connection/connect.php';

// Fetch payment data
$sql = "SELECT A.*, 
    CONCAT(firstName, ' ', IFNULL(CONCAT(UPPER(LEFT(middleName, 1)), '. '), ''), lastName) AS Name 
FROM payment A
LEFT JOIN resident_list B ON A.resident_id = B.accountID
ORDER BY A.created_at DESC";
$result = mysqli_query($conn, $sql);

// Get the last RefNo and generate a new one
$code_query = "SELECT MAX(RefNo) AS last_code FROM payment WHERE RefNo LIKE 'REQ-" . date('Ym') . "%'";
$code_result = mysqli_query($conn, $code_query);
$row = $code_result->fetch_assoc();
$last_code = $row['last_code'] ?? 'REQ-' . date('Ym') . '0001';

// Extract last 4 digits and increment
$new_number = (int)substr($last_code, -4) + 1;
$refNo = 'REQ-' . date('Ym') . str_pad($new_number, 4, "0", STR_PAD_LEFT);

// Fetch total payment amount for the current day
$sqlCurrentDay = "SELECT SUM(Amount) AS totalAmount FROM payment WHERE status in (0,2)";
$resultCurrentDay = mysqli_query($conn, $sqlCurrentDay);
$totalAmountCurrentDay = mysqli_fetch_assoc($resultCurrentDay)['totalAmount'] ?? 0;

mysqli_close($conn);
?>

<?php include 'user_navbar.php'; ?>
<div class="container">
    <div class="dashboard-container">
        <div class="date">
            <label for="dateFilter">Filter by Date:</label>
            <input type="date" id="dateFilter" onchange="filterByDate()">
        </div>
        <div class="dashboard-box">
            <h3>Total Amount Paid</h3>
            <p id="totalAmount">₱<?php echo number_format($totalAmountCurrentDay, 2); ?></p>
        </div>
    </div>

    <div class="table-container">
        <div class="filter-container">
            <button onclick="openPopupForm()">+ Add New</button>
        </div>
        <table id="paymentTable">
            <thead>
                <tr>
                    <th>Ref No.</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Purpose</th>
                    <th>Refund</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0) :
                    while ($row = mysqli_fetch_assoc($result)) :
                        $refund = ($row['status'] == 1) ? 'Yes' : 'No';
                        if($row['status'] == 0){
                            $action = "<div>
                            <button style='background-color: green; color: white; border: none; padding: 5px 10px; cursor: pointer;' onclick='approveRow(\"{$row['RefNo']}\")'>Complete</button>
                            <button style='background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer;' onclick='deleteRow(\"{$row['RefNo']}\")'>Refund</button>
                         </div>";
                        }else{
                            if($row['status'] == 2){
                                $action = 'Completed';
                            }else{
                                $action = 'Refunded';
                            }
                            
                        }
                    ?>
                        <tr>
                            <td><?= $row['RefNo']; ?></td>
                            <td><?= $row['Type'] . " - " . $row['type_flag']; ?></td>
                            <td><?= $row['Name']; ?></td>
                            <td>₱<?= number_format($row['Amount'], 2); ?></td>
                            <td><?= $row['PaymentDate']; ?></td>
                            <td><?= $row['Purpose']; ?></td>
                            <td><?= $refund; ?></td>
                            <td><?= $action; ?></td>
                        </tr>
                <?php endwhile;
                else : ?>
                    <tr><td colspan="8">No payments found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Popup Form -->
<div id="popupForm" class="popup">
    <div class="popupform-container">
        <div class="popup-content">
            <span class="close-button" onclick="closePopupForm()">&times;</span>
            <h3>Add New Payment</h3>
            <form action="insert_payment.php" method="POST">
                <p style="color: red;">Ref No.: <?php echo $refNo; ?></p>
                <input type="hidden" name="refNo" value="<?= $refNo; ?>">
                
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

                <label for="type_flag">Type Flag:</label>
                <select name="type_flag" id="type_flag" required>
                    <option value="" selected disabled>Select Type</option>
                    <option value="NEW">NEW</option>
                    <option value="RENEW">RENEW</option>
                </select>

                <label for="resident_id">Name:</label>
                <select name="resident_id" required>
                    <?php
                    require '../connection/connect.php';
                    $residents = mysqli_query($conn, "SELECT accountID, lastName, firstName FROM resident_list");
                    while ($row = mysqli_fetch_assoc($residents)) {
                        echo "<option value='{$row['accountID']}'>{$row['lastName']}, {$row['firstName']}</option>";
                    }
                    mysqli_close($conn);
                    ?>
                </select>

                <label for="amount">Amount:</label>
                <input type="number" id="amount" name="amount" required readonly>

                <label for="purpose">Purpose:</label>
                <textarea name="Purpose" rows="3" required></textarea>

                <input type="hidden" name="paymentDate" value="<?= date('Y-m-d'); ?>">
                <input type="submit" value="Submit">
            </form>
        </div>
    </div>
</div>

<script>
    // Open/close popup form
    function openPopupForm() {
        document.getElementById("popupForm").style.display = "block";
    }
    function closePopupForm() {
        document.getElementById("popupForm").style.display = "none";
    }

    // Filter payments by date
    function filterByDate() {
        let input = document.getElementById("dateFilter").value;
        let table = document.getElementById("paymentTable");
        let rows = table.getElementsByTagName("tr");
        let totalAmountFiltered = 0;

        for (let i = 1; i < rows.length; i++) {
            let dateCell = rows[i].getElementsByTagName("td")[4]; // Payment date column
            if (dateCell) {
                let paymentDate = dateCell.textContent.trim();
                rows[i].style.display = (paymentDate === input) ? "" : "none";
                if (paymentDate === input) {
                    totalAmountFiltered += parseFloat(rows[i].getElementsByTagName("td")[3].innerText.replace("₱", ""));
                }
            }
        }

        document.getElementById("totalAmount").innerText = "₱" + totalAmountFiltered.toFixed(2);
    }

    // Delete a payment
    function deleteRow(refNo) {
        if (confirm("Are you sure you want to refund this record?")) {
            fetch(`delete_payment.php?RefNo=${refNo}`)
                .then(() => location.reload());
        }
    }

    function approveRow(refNo) {
        if (confirm("Are you sure you want to complete this record?")) {
            fetch(`approve_payment.php?RefNo=${refNo}`)
                .then(() => location.reload());
        }
    }

    // Set payment amount based on type
    function setAmount() {
        const amounts = {
            "Brgy clearance": 60, "Business Clearance": 50, "Building Clearance": 40,
            "Barangay Certificate": 40, "Certificate of Indigency": 70, "Cedula": 60
        };
        document.getElementById("amount").value = amounts[document.getElementById("type").value] || "";
    }
</script>
