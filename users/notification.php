<?php
include '../connection/connect.php';

header('Content-Type: application/json');
session_start();

$accountID = $_SESSION['accountID'];

try {
    // Prepare main select query
    $sql = "SELECT * 
            FROM request 
            WHERE notification_flag = 1 
            AND status IN ('APPROVED', 'DISAPPROVED') 
            AND resident_id = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accountID);
    $stmt->execute();
    $result = $stmt->get_result();

    $res = array();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $res[] = $row;

            // Prepare update query
            $updateSql = $conn->prepare("UPDATE request SET notification_flag = 2 WHERE request_id = ?");
            $updateSql->bind_param("s", $row['request_id']);
            $updateSql->execute();
        }
    }

    // Get count of notifications
    $sqlcount = "SELECT COUNT(*) as count_request 
                 FROM request 
                 WHERE notification_flag IN (1,2) 
                 AND status IN ('APPROVED', 'DISAPPROVED') 
                 AND resident_id = ?";
    
    $countStmt = $conn->prepare($sqlcount);
    $countStmt->bind_param("i", $accountID);
    $countStmt->execute();
    $countResult = $countStmt->get_result();
    $countRow = $countResult->fetch_assoc();
    $count_request = $countRow['count_request'];

    // Send response as JSON
    echo json_encode(['data' => $res, 'count_request' => $count_request]);

} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
} finally {
    $conn->close();
}
