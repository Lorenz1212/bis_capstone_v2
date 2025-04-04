<?php include 'user_navbar.php'; ?>
<?php
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

<div class="container mt-4">
    <!-- Dashboard -->
    <div class="row mb-3">
        <div class="col-md-6">
            <label for="dateFilter">Filter by Date:</label>
            <input type="date" id="dateFilter" class="form-control" onchange="filterByDate()">
        </div>
        <div class="col-md-6 text-end">
            <h4 class="fw-bold">Total Amount Paid: <span class="text-success">₱<?php echo number_format($totalAmountCurrentDay, 2); ?></span></h4>
        </div>
    </div>

    <!-- Add Payment Button -->
    <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#paymentModal">+ Add New</button>
    </div>

    <!-- Payment Table -->
    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
        <table class="table table-bordered table-hover" id="paymentTable">
            <thead class="table-dark">
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
                        if ($row['status'] == 0) {
                            $action = "
                                <button class='btn btn-success btn-sm' onclick='approveRow(\"{$row['RefNo']}\")'>Complete</button>
                                <button class='btn btn-danger btn-sm' onclick='deleteRow(\"{$row['RefNo']}\")'>Refund</button>";
                        } else {
                            $action = ($row['status'] == 2) ? '<span class="badge bg-success">Completed</span>' : '<span class="badge bg-danger">Refunded</span>';
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
                    <tr><td colspan="8" class="text-center">No payments found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Bootstrap Modal for Add Payment -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentModalLabel">Add New Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="insert_payment.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Ref No.: <span class="text-danger"><?php echo $refNo; ?></span></label>
                        <input type="hidden" name="refNo" value="<?= $refNo; ?>">
                    </div>
                    
                    <div class="mb-3">
                        <label for="type" class="form-label">Type:</label>
                        <select name="type" id="type" class="form-control" onchange="setAmount()" required>
                            <option value="" selected disabled>Select Certificate</option>
                            <?php
                            $clearances = ["Brgy clearance", "Business Clearance", "Building Clearance", "Barangay Certificate", "Certificate of Indigency", "Cedula"];
                            foreach ($clearances as $clearance) {
                                echo "<option value='$clearance'>$clearance</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="type_flag" class="form-label">Type Flag:</label>
                        <select name="type_flag" id="type_flag" class="form-control" required>
                            <option value="" selected disabled>Select Type</option>
                            <option value="NEW">NEW</option>
                            <option value="RENEW">RENEW</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="resident_id" class="form-label">Name:</label>
                        <select name="resident_id" class="form-control" required>
                            <?php
                            require '../connection/connect.php';
                            $residents = mysqli_query($conn, "SELECT accountID, lastName, firstName FROM resident_list");
                            while ($row = mysqli_fetch_assoc($residents)) {
                                echo "<option value='{$row['accountID']}'>{$row['lastName']}, {$row['firstName']}</option>";
                            }
                            mysqli_close($conn);
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount:</label>
                        <input type="number" id="amount" name="amount" class="form-control" required readonly>
                    </div>

                    <div class="mb-3">
                        <label for="purpose" class="form-label">Purpose:</label>
                        <textarea name="Purpose" rows="3" class="form-control" required></textarea>
                    </div>

                    <input type="hidden" name="paymentDate" value="<?= date('Y-m-d'); ?>">
                    <button type="submit" class="btn btn-primary w-100">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script>
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
