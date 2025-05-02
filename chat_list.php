<?php
session_start();
require 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "You must be logged in to view this page.";
    exit;
}

// Get current user ID
$current_user_id = $_SESSION['user_id'];

// Get current user's details for profile
$sql_user = "SELECT id, name FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $current_user_id);
$stmt_user->execute();
$user_details = $stmt_user->get_result()->fetch_assoc();

// Prepare query to fetch other users (except current user)
$sql = "SELECT id, name FROM users WHERE id != ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Users</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            background-color: #f4f4f9;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* Container for the top section (profile + notifications) */
        .top-section {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
            margin-bottom: 20px;
        }

        /* Notification Icon */
        .notification-icon {
            font-size: 30px;
            cursor: pointer;
        }

        /* Search bar */
        .search-bar {
            padding: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 16px;
            margin: 0 20px;
        }

        /* Profile Section (Right Side, Top) */
        .profile {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 250px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile h3 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .profile .user-name {
            font-size: 16px;
            font-weight: bold;
        }

        .profile img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        /* Container for chat list */
        .chat-list-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            width: 100%;
        }

        /* User container */
        .user {
            position: relative;
            display: flex;
            align-items: center;
            padding: 15px;
            margin-bottom: 8px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: white;
            cursor: pointer;
            width: 250px;
            justify-content: space-between;
        }

        .user:hover {
            background-color: #e0e0e0;
        }

        /* Human icon */
        .user::before {
            content: "\1F464"; /* Unicode for human icon */
            font-size: 30px;
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #7a7a7a;
        }

        /* Username style */
        .user-name {
            margin-left: 35px;
            font-size: 16px;
            font-weight: bold;
        }

        /* Chat button */
        .chat-button {
            background-color: #4CAF50; /* Green button */
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .chat-button:hover {
            background-color: #45a049;
        }

        /* Delete button */
        .delete-button {
            background-color: #f44336; /* Red button */
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .delete-button:hover {
            background-color: #e53935;
        }

        /* Active chat user style (Top-right corner) */
        .active-chat {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 14px;
            font-weight: bold;
            color: #4CAF50; /* Green color */
        }
    </style>
</head>
<body>

    <!-- Top Section (Notification Icon + Search Bar + Profile) -->
    <div class="top-section">
        <!-- Notification Icon -->
        <div class="notification-icon">&#128276;</div> <!-- Bell Icon for notifications -->

        <!-- Search Bar -->
        <input type="text" id="searchInput" class="search-bar" placeholder="Search for a user..." onkeyup="filterUsers()">

        <!-- Profile Section -->
        <div class="profile">
            <h3>Your Profile</h3>
            <div class="profile-info">
                <!-- Display the user's name and a placeholder image (can be updated later) -->
                <div class="user-name"><?php echo htmlspecialchars($user_details['name']); ?></div>
                <img src="https://via.placeholder.com/50" alt="User Icon">
            </div>
        </div>
    </div>

    <!-- Chat List Section -->
    <div class="chat-list-container">
        <!-- List of available users -->
        <?php while ($user = $result->fetch_assoc()): ?>
            <div class="user">
                <!-- If this user is the active chat, display their status -->
                <?php if ($user['id'] == $current_user_id): ?>
                    <div class="active-chat">In Chat</div>
                <?php endif; ?>

                <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
                
                <!-- Chat button -->
                <a href="send.php?user_id=<?php echo $user['id']; ?>" class="chat-button">Chat</a>
                
                
                <!-- Delete button (you can add functionality to delete here) -->
                <button class="delete-button" onclick="deleteUser(<?php echo $user['id']; ?>)">Delete</button>
            </div>
        <?php endwhile; ?>
    </div>

    <script>
        function startChat(userId) {
            alert('Starting chat with user ID: ' + userId);
            // You can redirect to the chat page like this:
            // window.location.href = 'chat.php?user_id=' + userId;
        }

        function deleteUser(userId) {
            alert('Deleting user ID: ' + userId);
            // You can add the functionality to delete a user here, like an AJAX request to the server
        }

        // Filter users by search input
        function filterUsers() {
            const searchInput = document.getElementById("searchInput").value.toLowerCase();
            const users = document.querySelectorAll(".user");

            users.forEach(function(user) {
                const userName = user.querySelector(".user-name").textContent.toLowerCase();
                if (userName.includes(searchInput)) {
                    user.style.display = "";
                } else {
                    user.style.display = "none";
                }
            });
        }
    </script>

</body>
</html>
