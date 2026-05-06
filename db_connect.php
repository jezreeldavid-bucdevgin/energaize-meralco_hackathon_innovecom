<?php
$servername = "localhost";
$username = "root";  // Usually "root" for XAMPP
$password = "";  // Usually "" (empty) for XAMPP
$dbname = "test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8mb4
$conn->set_charset("utf8mb4");
?>