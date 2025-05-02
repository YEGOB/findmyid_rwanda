<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'find_id');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is posted
if (!isset($_POST['id'])) {
    echo "❌ No ID selected.";
    exit;
}

$id = intval($_POST['id']);

// Check if the ID exists
$stmt = $conn->prepare("SELECT * FROM found_ids WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$idData = $result->fetch_assoc();

if (!$idData) {
    echo "❌ ID not found.";
    exit;
}

// Now update to mark it as taken
$update = $conn->prepare("UPDATE found_ids SET is_taken = 1 WHERE id = ?");
$update->bind_param("i", $id);
if ($update->execute()) {
    // Success
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>ID Taken</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                background: linear-gradient(135deg, #89f7fe, #66a6ff);
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                flex-direction: column;
            }
            .message {
                background: white;
                padding: 30px;
                border-radius: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.2);
                text-align: center;
            }
            .message h2 {
                color: #28a745;
                margin-bottom: 20px;
            }
            .back-btn {
                margin-top: 20px;
                background: #2575fc;
                color: white;
                padding: 10px 20px;
                border-radius: 30px;
                text-decoration: none;
                font-weight: bold;
                transition: background 0.3s;
            }
            .back-btn:hover {
                background: #6a11cb;
            }
        </style>
    </head>
    <body>
        <div class='message'>
            <h2>✅ You have successfully taken your ID!</h2>
            <a class='back-btn' href='dashboard.php'>🔙 Back to Home</a>
        </div>
    </body>
    </html>";
} else {
    echo "❌ Failed to mark ID as taken. Try again.";
}
?>
