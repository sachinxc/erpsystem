<?php
$host = "localhost";
$username = "root";
$password = "";
$dbname = "erp_db"; // name of the imported db

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>
