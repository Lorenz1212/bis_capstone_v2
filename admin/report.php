<?php
include 'user_navbar.php';

$accountID = $_SESSION['accountID'];

$category = isset($_GET['category']) ? $_GET['category'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Query to get total amount for "new" and "renew"
$whereClauseTotal="";

// Prepare SQL query based on category filter
$whereClause = "WHERE status in (0,2)";
$params = [];
$types = "";
if (!empty($category)) {
    if ($category == 1 && !empty($month)) {
        $whereClause .= " AND MONTH(A.created_at) = ? AND YEAR(A.created_at) = ?";
        $params = [$month, $year];
        $types = "ii";
        $whereClauseTotal .= " AND MONTH(created_at) = $month AND YEAR(created_at) = $year";
    } elseif ($category == 2) {
        $whereClause .= " AND YEAR(A.created_at) = ?";
        $params = [$year];
        $types = "i";
        $whereClauseTotal .= " AND YEAR(created_at) = $year";
    }
}

$total_new_sql = "SELECT SUM(Amount) AS total_new FROM payment WHERE type_flag = 'NEW' AND status in (0,2)  $whereClauseTotal";
$total_renew_sql = "SELECT SUM(Amount) AS total_renew FROM payment WHERE type_flag = 'RENEW' AND status in (0,2) $whereClauseTotal";

$total_new_result = $conn->query($total_new_sql);
$total_renew_result = $conn->query($total_renew_sql);

$total_new = ($total_new_result->num_rows > 0) ? $total_new_result->fetch_assoc()['total_new'] : 0;
$total_renew = ($total_renew_result->num_rows > 0) ? $total_renew_result->fetch_assoc()['total_renew'] : 0;

//comment

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

// Bind parameters dynamically
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Summary</title>
    <link rel="stylesheet" href="styles.css"> <!-- Ensure you have this stylesheet -->
</head>
<body>
    <div class="container">
        <h2>Payment Summary</h2>
        <div class="dashboard-container">
            <div class="dashboard-box">
                <h3>Total New</h3>
                <p id="totalAmountNew">₱<?php echo number_format($total_new??0, 2); ?></p>
            </div>
            <div class="dashboard-box">
                <h3>Total Renew</h3>
                <p id="totalAmountRenew">₱<?php echo number_format($total_renew??0, 2); ?></p>
            </div>
        </div>
        <div class="dashboard-container-form ">
            <form class="button-group"> 
                <div>
                    <select class="form-control" id="category">
                        <option value="1" <?php echo ($category == "1") ? "selected" : ""; ?>>Daily</option>
                        <option value="2" <?php echo ($category == "2") ? "selected" : ""; ?>>Monthly</option>
                        <option value="3" <?php echo ($category == "3") ? "selected" : ""; ?>>Yearly</option>
                    </select>
                </div>
                <div id="month_hide">
                    <select class="form-control" id="month">
                        <?php
                        $months = [
                            "01" => "January", "02" => "February", "03" => "March",
                            "04" => "April", "05" => "May", "06" => "June",
                            "07" => "July", "08" => "August", "09" => "September",
                            "10" => "October", "11" => "November", "12" => "December"
                        ];
                        foreach ($months as $key => $value) {
                            $selected = ($month == $key) ? "selected" : "";
                            echo "<option value='$key' $selected>$value</option>";
                        }
                        ?>
                    </select>
                </div>
                <div id="year_hide">
                    <select class="form-control" id="year">
                        <?php
                        $currentYear = date("Y");
                        for ($y = $currentYear; $y >= 2022; $y--) {
                            $selected = ($year == $y) ? "selected" : "";
                            echo "<option value='$y' $selected>$y</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="button" class="btn-success" id="search">Search</button>
                <button type="button" class="btn-primary" onclick="printData()" >Print</button>
            </form>
        </div>

        <table class="request_table" id="request_table">
            <thead>
                <tr>
                    <th>Ref No.</th>
                    <th>Type</th>
                    <th>Name</th>
                    <th>Amount</th>
                    <th>Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['RefNo'] . "</td>";
                        echo "<td>" . $row['Type'] . " - " . $row['type_flag'] . "</td>";
                        echo "<td>" . $row['Name'] . "</td>";
                        echo "<td>₱" . number_format($row['Amount'], 2) . "</td>";
                        echo "<td>" . date('F d, Y', strtotime($row['created_at'])) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No requests found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>

  document.addEventListener("DOMContentLoaded", function () {
    function printData() {
        var divToPrint = document.getElementById("request_table");

        if (!divToPrint) {
            console.error("The table with ID 'request_table' could not be found.");
            return;
        }

        var newWin = window.open("", "", "width=800,height=600");

        if (!newWin) {
            alert("Popup was blocked. Please allow popups for this site.");
            return;
        }

        // Absolute paths for images (Modify these paths accordingly)
        var logo1 = "../image/logo.png"; // Left logo (Barangay Logo)
        var logo2 = "../image/cablogo.png"; // Right logo (City/Municipality Logo)

        newWin.document.write(`
            <html>
            <head>
                <title>Print</title>
                <style>
                    body { font-family: Arial, sans-serif; text-align: center; margin: 20px; }
                    .header-container {
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                        width: 100%;
                        margin-bottom: 20px;
                        border-bottom: 2px solid black;
                        padding-bottom: 10px;
                    }
                    .header-container img {
                        width: 100px;
                        height: auto;
                    }
                    .header-container .head {
                        flex-grow: 1;
                        text-align: center;
                    }
                    .head h3 {
                        font-size: 22px;
                        font-weight: bold;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-top: 20px;
                    }
                    th, td {
                        padding: 8px;
                        text-align: left;
                        border: 1px solid #ddd;
                    }
                </style>
            </head>
            <body>
                <div class="header-container">
                    <img src="${logo1}" id="print-logo1" alt="Barangay Logo">
                    <div class="head">
                        <p>Republic of the Philippines</p>
                        <p>Province of Laguna</p>
                        <p>Municipality of Cabuyao</p>
                        <h3>Barangay Marinig</h3>
                    </div>
                    <img src="${logo2}" id="print-logo2" alt="Municipality Logo">
                </div>
                ${divToPrint.outerHTML}
            </body>
            </html>
        `);

        newWin.document.close();

        // Ensure images are loaded before printing
        var img1 = newWin.document.getElementById("print-logo1");
        var img2 = newWin.document.getElementById("print-logo2");

        function checkImagesLoaded() {
            if (img1.complete && img2.complete) {
                newWin.print();
                newWin.close();
            } else {
                setTimeout(checkImagesLoaded, 500);
            }
        }
        checkImagesLoaded();
    }

    document.querySelector(".btn-primary").onclick = printData;
});


        document.getElementById('category').addEventListener('change', function () {
        let val = this.value;
        
        const monthElement = document.getElementById('month_hide');
        const yearElement = document.getElementById('year_hide');
        
        if (val == 1) {
            monthElement.classList.remove('d-none');
            yearElement.classList.remove('d-none');
        } else if (val == 2){
            monthElement.classList.add('d-none');
            yearElement.classList.remove('d-none');
        }else{
            monthElement.classList.add('d-none');
            yearElement.classList.add('d-none');
        }
    });

    document.getElementById('search').addEventListener('click', function () {
        let category = document.getElementById('category').value;
        let month = document.getElementById('month').value;
        let year = document.getElementById('year').value;
        let baseUrl = window.location.origin + window.location.pathname;
        let query = `?category=${category}&month=${month}&year=${year}`;
        window.location.href = baseUrl+query;
       
    });

    // document.getElementById('category').addEventListener('change', updateTotals);
    // document.getElementById('month').addEventListener('change', updateTotals);
    // document.getElementById('year').addEventListener('change', updateTotals);
</script>

</body>
</html>
