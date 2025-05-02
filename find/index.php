<?php
// Ensure no output is sent before session_start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../home.php");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "find_id");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// This SQL query is not used in this page, consider removing if unnecessary
// $sql = "SELECT * FROM found_ids ORDER BY id DESC";
// $result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>How It Works</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f9f9f9;
            padding: 40px;
            color: #333;
        }

        .container {
            max-width: 800px; /* Corrected from 8000px to 800px */
            margin: auto;
            background: white;
            padding: 40px; /* Reduced padding for better layout */
            border-radius: 10px; /* Reduced border radius for a cleaner look */
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        h1 {
            text-align: center;
            color: #007BFF;
        }

        p {
            line-height: 1.8;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 15px;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>How It Works</h1>
        <p>Welcome to our ID management platform. Here's how everything works:</p>

        <p><strong>1. Post Found ID:</strong> If you find someone’s ID card, you can post the details here so that the owner can see and reclaim it.</p>

        <p><strong>2. Search Lost ID:</strong> If you’ve lost your ID, you can search through posted IDs to see if someone has found and listed it.</p>

        <p><strong>3. Help Center:</strong> For any issues, questions, or support requests, reach out to our team for assistance.</p>

        <a href="dashboard.php" class="back-link">Back to Dashboard</a> <!-- Added a useful back link -->
    </div>
</body>
</html>
