<?php
$host = "localhost";
$user = "root"; // Your DB username
$pass = "";     // Your DB password
$dbname = "aces_db"; // Create this DB in phpMyAdmin

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
