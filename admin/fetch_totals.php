<?php
include '../connection/connect.php';

$category = isset($_GET['category']) ? $_GET['category'] : '';
$month = isset($_GET['month']) ? $_GET['month'] : '';
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

$whereClause = "WHERE status in (0,2)";
$params = [];
$types = "";

if (!empty($category)) {
    if ($category == 1 ) {
        $whereClause .= " AND MONTH(created_at) = ? AND YEAR(created_at) = ?";
        $params = [$month, $year];
        $types = "ii";
    } elseif ($category == 2) {
        $whereClause .= " AND YEAR(created_at) = ?";
        $params = [$year];
        $types = "i";
    }
}

// Get total for "new"
$total_new_sql = "SELECT SUM(Amount) AS total_new FROM payment $whereClause AND type_flag = 'NEW'";
$stmt_new = $conn->prepare($total_new_sql);
if (!empty($params)) {
    $stmt_new->bind_param($types, ...$params);
}
$stmt_new->execute();
$result_new = $stmt_new->get_result();
$total_new = ($result_new->num_rows > 0) ? $result_new->fetch_assoc()['total_new'] : 0;

// Get total for "renew"
$total_renew_sql = "SELECT SUM(Amount) AS total_renew FROM payment $whereClause AND type_flag = 'RENEW'";
$stmt_renew = $conn->prepare($total_renew_sql);
if (!empty($params)) {
    $stmt_renew->bind_param($types, ...$params);
}
$stmt_renew->execute();
$result_renew = $stmt_renew->get_result();
$total_renew = ($result_renew->num_rows > 0) ? $result_renew->fetch_assoc()['total_renew'] : 0;

echo json_encode([
    "total_new" => number_format($total_new??0, 2),
    "total_renew" => number_format($total_renew??0, 2)
]);

?>
