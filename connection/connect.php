<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// $host = "localhost";
// $username = "root";
// $password = "";
// $database = "brgy_info";


$host = "localhost";
$username = "u856582098_bis_v2"; 
$password = "2R=[mzmO8t&";
$database = "u856582098_bis_v2";



// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>