<?php
$host   = "localhost";
$user   = "root";
$pass   = "";
$dbname = "ecommerce_marketplace"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("DB connection failed: " . $conn->connect_error);
}
?>