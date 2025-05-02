<?php
$servername = "localhost";
$username = "root";  // Your DB username
$password = "";      // Your DB password
$dbname = "find_id"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Optionally, set the character set for better compatibility with special characters
$conn->set_charset("utf8mb4");
?>
