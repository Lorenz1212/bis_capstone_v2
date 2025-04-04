<?php
include 'user_navbar.php';

$accountID = $_SESSION['accountID'];

// Handle accepting a request
if (isset($_GET['accept']) && !empty($_GET['accept'])) {
    $refNoToAccept = $_GET['accept'];

    // Fetch the request details
    $requestQuery = "SELECT * FROM request WHERE RefNo = ?";
    if ($stmt = mysqli_prepare($conn, $requestQuery)) {
        mysqli_stmt_bind_param($stmt, "s", $refNoToAccept);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            // Insert into payment table
            $insertQuery = "INSERT INTO payment (RefNo, Type, resident_id, Amount, PaymentDate, Purpose) VALUES (?, ?, ?, ?, ?, ?)";
            if ($insertStmt = mysqli_prepare($conn, $insertQuery)) {
                mysqli_stmt_bind_param($insertStmt, "ssssss", $row['RefNo'], $row['Type'], $row['resident_id'], $row['Amount'], $row['PaymentDate'], $row['Purpose']);

                if (mysqli_stmt_execute($insertStmt)) {
                    // Update request status
                    $updateQuery = "UPDATE request SET status ='APPROVED' , notification_flag=1, updated_at = now() WHERE RefNo = ?";
                    if ($updateStmt = mysqli_prepare($conn, $updateQuery)) {
                        mysqli_stmt_bind_param($updateStmt, "s", $refNoToAccept);
                        mysqli_stmt_execute($updateStmt);
                        mysqli_stmt_close($updateStmt);
                    }

                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "<div class='alert alert-danger'>Error inserting payment: " . mysqli_error($conn) . "</div>";
                }

                mysqli_stmt_close($insertStmt);
            }
        } else {
            echo "<div class='alert alert-warning'>Request not found.</div>";
        }

        mysqli_stmt_close($stmt);
    }
}

// Fetch requests
$sql = "SELECT *, 
    CONCAT(firstName, ' ', IFNULL(CONCAT(UPPER(LEFT(middleName, 1)), '. '), ''), lastName) AS Name 
    FROM request A
    LEFT JOIN resident_list B ON A.resident_id = B.accountID 
    ORDER BY A.created_at DESC";

$result = mysqli_query($conn, $sql);
?>

<div class="container mt-4">
    <h2 class="mb-3">Requests</h2>

    <!-- Scrollable table container -->
    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
        <table class="table table-striped table-hover border">
            <thead class="table-dark">
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
                <?php if (mysqli_num_rows($result) > 0) :
                    while ($row = mysqli_fetch_assoc($result)) :
                        $statusBadge = match ($row['status']) {
                            'REQUEST' => '<span class="badge bg-warning">Pending</span>',
                            'APPROVED' => '<span class="badge bg-success">Approved</span>',
                            default => '<span class="badge bg-secondary">' . $row['status'] . '</span>',
                        };

                        $action = ($row['status'] === 'REQUEST')
                            ? "<button class='btn btn-success btn-sm' onclick='confirmAccept(\"" . $row['RefNo'] . "\")'>Accept</button>"
                            : "<button class='btn btn-secondary btn-sm' disabled>-</button>";
                ?>
                        <tr>
                            <td><?= $row['RefNo']; ?></td>
                            <td><?= $row['Type'] . " - " . $row['type_flag']; ?></td>
                            <td><?= $row['Name']; ?></td>
                            <td>₱<?= number_format($row['Amount'], 2); ?></td>
                            <td><?= $row['PaymentDate']; ?></td>
                            <td><?= $row['Purpose']; ?></td>
                            <td><?= $statusBadge; ?></td>
                            <td><?= $action; ?></td>
                        </tr>
                <?php endwhile;
                else : ?>
                    <tr>
                        <td colspan="8" class="text-center">No requests found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function confirmAccept(refNo) {
    if (confirm("Are you sure you want to accept this payment?")) {
        window.location.href = "?accept=" + refNo;
    }
}
</script>
