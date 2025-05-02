<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "find_id";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
