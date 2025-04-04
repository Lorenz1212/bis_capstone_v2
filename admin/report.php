<?php
include 'user_navbar.php';

// Get filter parameters with null coalescing operator
$category = $_GET['category'] ?? '';
$month = $_GET['month'] ?? '';
$year = $_GET['year'] ?? date('Y');
$type = $_GET['type'] ?? '';

// Build WHERE clause for main query
$whereClause = "WHERE A.status IN (0,2)";
$params = [];
$types = "";

if (!empty($category)) {
    if ($category == 1 && !empty($month)) {
        $whereClause .= ($type) ? " AND A.Type = ?" : "";
        $whereClause .= " AND MONTH(A.created_at) = ? AND YEAR(A.created_at) = ?";
        $params = ($type) ? [$type, $month, $year] : [$month, $year];
        $types = ($type) ? "sii" : "ii";
    } elseif ($category == 2) {
        $whereClause .= ($type) ? " AND A.Type = ?" : "";
        $whereClause .= " AND YEAR(A.created_at) = ?";
        $params = ($type) ? [$type, $year] : [$year];
        $types = ($type) ? "si" : "i";
    }
}

// Build WHERE clause for totals (same filters as main query)
$totalsWhereClause = "WHERE status IN (0,2)";
$totalsParams = [];
$totalsTypes = "";

if (!empty($category)) {
    if ($category == 1 && !empty($month)) {
        $totalsWhereClause .= ($type) ? " AND Type = ?" : "";
        $totalsWhereClause .= " AND MONTH(created_at) = ? AND YEAR(created_at) = ?";
        $totalsParams = ($type) ? [$type, $month, $year] : [$month, $year];
        $totalsTypes = ($type) ? "sii" : "ii";
    } elseif ($category == 2) {
        $totalsWhereClause .= ($type) ? " AND Type = ?" : "";
        $totalsWhereClause .= " AND YEAR(created_at) = ?";
        $totalsParams = ($type) ? [$type, $year] : [$year];
        $totalsTypes = ($type) ? "si" : "i";
    }
}

// Get filtered totals
$total_new_sql = "SELECT COALESCE(SUM(Amount), 0) AS total FROM payment $totalsWhereClause AND type_flag = 'NEW'";
$total_renew_sql = "SELECT COALESCE(SUM(Amount), 0) AS total FROM payment $totalsWhereClause AND type_flag = 'RENEW'";

// Prepare and execute totals queries
$stmt_new = $conn->prepare($total_new_sql);
$stmt_renew = $conn->prepare($total_renew_sql);

if (!empty($totalsParams)) {
    $stmt_new->bind_param($totalsTypes, ...$totalsParams);
    $stmt_renew->bind_param($totalsTypes, ...$totalsParams);
}

$stmt_new->execute();
$total_new = $stmt_new->get_result()->fetch_assoc()['total'];

$stmt_renew->execute();
$total_renew = $stmt_renew->get_result()->fetch_assoc()['total'];

// Main query
$sql = "SELECT A.*, 
        CONCAT(
            firstName, ' ', 
            IFNULL(CONCAT(UPPER(LEFT(middleName, 1)), '. '), ''), 
            lastName
        ) AS Name 
        FROM payment A
        LEFT JOIN resident_list B ON A.resident_id = B.accountID 
        $whereClause
        AND B.registration_status = 'APPROVED'
        ORDER BY A.created_at DESC";

$stmt = $conn->prepare($sql);
if (!empty($params)) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>
    <div class="container py-4">
        <h2 class="mb-4">Payment Summary</h2>
        
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-6 mb-3">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-file-alt me-2"></i>Total New</h5>
                        <p class="card-text display-6">₱<?= number_format($total_new, 2) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card summary-card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-sync-alt me-2"></i>Total Renew</h5>
                        <p class="card-text display-6">₱<?= number_format($total_renew, 2) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <label for="category" class="form-label">Filter By:</label>
                        <select class="form-select" id="category">
                            <option value="1" <?= ($category == "1") ? 'selected' : '' ?>>Daily</option>
                            <option value="2" <?= ($category == "2") ? 'selected' : '' ?>>Monthly</option>
                            <option value="3" <?= ($category == "3") ? 'selected' : '' ?>>Yearly</option>
                        </select>
                    </div>
                    <div class="col-md-3" id="monthContainer">
                        <label for="month" class="form-label">Month:</label>
                        <select class="form-select" id="month">
                            <?php foreach ([
                                "01" => "January", "02" => "February", "03" => "March",
                                "04" => "April", "05" => "May", "06" => "June",
                                "07" => "July", "08" => "August", "09" => "September",
                                "10" => "October", "11" => "November", "12" => "December"
                            ] as $key => $value): ?>
                                <option value="<?= $key ?>" <?= ($month == $key) ? 'selected' : '' ?>><?= $value ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="yearContainer">
                        <label for="year" class="form-label">Year:</label>
                        <select class="form-select" id="year">
                            <?php for ($y = date("Y"); $y >= 2022; $y--): ?>
                                <option value="<?= $y ?>" <?= ($year == $y) ? 'selected' : '' ?>><?= $y ?></option>
                            <?php endfor ?>
                        </select>
                    </div>
                    <div class="col-md-3" id="yearContainer">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type">
                        <option value="" selected>ALL</option>
                            <?php $clearances = ["Brgy clearance", "Business Clearance", "Building Clearance", "Barangay Certificate", "Certificate of Indigency", "Cedula"];
                            foreach ($clearances as $clearance): ?>
                                <option value="<?= htmlspecialchars($clearance) ?>" <?= ($type == $clearance) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($clearance) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end gap-2">
                        <button type="button" class="btn btn-primary" id="searchBtn">
                            <i class="fas fa-search me-1"></i> Search
                        </button>
                        <button type="button" class="btn btn-success" onclick="printReport()">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Payment Table -->
        <div class="card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0" id="request_table">
                        <thead class="table-dark">
                            <tr>
                                <th>Ref No.</th>
                                <th>Type</th>
                                <th>Name</th>
                                <th>Amount</th>
                                <th>Payment Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['RefNo']) ?></td>
                                        <td><?= htmlspecialchars($row['Type']) ?> - <?= htmlspecialchars($row['type_flag']) ?></td>
                                        <td><?= htmlspecialchars($row['Name']) ?></td>
                                        <td>₱<?= number_format($row['Amount'], 2) ?></td>
                                        <td><?= date('F d, Y', strtotime($row['created_at'])) ?></td>
                                    </tr>
                                <?php endwhile ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">No payment records found</td>
                                </tr>
                            <?php endif ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Toggle month/year visibility based on filter
        function updateFilterVisibility() {
            const val = document.getElementById('category').value;
            document.getElementById('monthContainer').classList.toggle('d-none', val != 1);
            document.getElementById('yearContainer').classList.toggle('d-none', val == 3);
        }
        
        // Initial visibility setup
        updateFilterVisibility();
        
        // Category change event
        document.getElementById('category').addEventListener('change', updateFilterVisibility);
        
        // Search button click event
        document.getElementById('searchBtn').addEventListener('click', function() {
            const category = document.getElementById('category').value;
            const month = document.getElementById('month').value;
            const year = document.getElementById('year').value;
            const type = document.getElementById('type').value;
            window.location.href = `?category=${category}&month=${month}&year=${year}&type=${type}`;
        });
    });

    function printReport() {
        const content = `
            <html>
            <head>
                <title>Payment Report</title>
                <style>
                    @page { size: A4; margin: 15mm; }
                    body { font-family: Arial; font-size: 12pt; }
                    .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
                    .header img { height: 80px; }
                    .header-text { text-align: center; }
                    .summary { display: flex; justify-content: space-around; margin: 20px 0; }
                    .summary-box { border: 1px solid #ddd; padding: 15px; text-align: center; width: 45%; }
                    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .footer { margin-top: 20px; text-align: right; font-size: 10pt; }
                </style>
            </head>
            <body>
                <div class="header">
                    <img src="../image/logo.png" alt="Barangay Logo">
                    <div class="header-text">
                        <h3>BARANGAY MARINIG</h3>
                        <p>Payment Summary Report</p>
                    </div>
                    <img src="../image/cablogo.png" alt="City Logo">
                </div>
                
                <div class="summary">
                    <div class="summary-box">
                        <h4>Total New Payments</h4>
                        <p>₱<?= number_format($total_new, 2) ?></p>
                    </div>
                    <div class="summary-box">
                        <h4>Total Renewal Payments</h4>
                        <p>₱<?= number_format($total_renew, 2) ?></p>
                    </div>
                </div>
                
                <table>
                    <thead>
                        <tr>
                            <th>Ref No.</th>
                            <th>Type</th>
                            <th>Name</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $result->data_seek(0); // Reset result pointer
                        while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['RefNo']) ?></td>
                                <td><?= htmlspecialchars($row['Type']) ?> - <?= htmlspecialchars($row['type_flag']) ?></td>
                                <td><?= htmlspecialchars($row['Name']) ?></td>
                                <td>₱<?= number_format($row['Amount'], 2) ?></td>
                                <td><?= date('M d, Y', strtotime($row['created_at'])) ?></td>
                            </tr>
                        <?php endwhile ?>
                    </tbody>
                </table>
                
                <div class="footer">
                    <p>Generated on: <?= date('F j, Y h:i A') ?></p>
                </div>
            </body>
            </html>
        `;

        const printWindow = window.open('', '_blank');
        printWindow.document.write(content);
        printWindow.document.close();
        printWindow.onload = function() {
            printWindow.print();
            printWindow.close();
        };
    }
    </script>
</body>
</html>