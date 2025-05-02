<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../home.php");
    exit;
}

$current_user_id = $_SESSION['user_id'];

// Fetch all users except the logged-in user
$sql = "SELECT id, username FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Users - Start Chat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            padding: 40px;
            margin: 0;
        }

        h1 {
            text-align: center;
            color: #007BFF;
            margin-bottom: 30px;
        }

        .user-list {
            max-width: 700px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .user-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #eee;
        }

        .user-item:last-child {
            border-bottom: none;
        }

        .user-name {
            font-size: 18px;
            font-weight: bold;
        }

        .chat-button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .chat-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

<h1>All People You Can Chat With</h1>

<div class="user-list">
    <?php if (count($users) > 0): ?>
        <?php foreach ($users as $user): ?>
            <div class="user-item">
                <div class="user-name"><?php echo htmlspecialchars($user['username']); ?></div>
                <a class="chat-button" href="chat.php?receiver_id=<?php echo $user['id']; ?>">Chat Now</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No users available yet.</p>
    <?php endif; ?>
</div>

</body>
</html>
