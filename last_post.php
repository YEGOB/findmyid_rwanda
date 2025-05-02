<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "find_id");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle "take" action
if (isset($_POST['take_id'])) {
    $id_to_take = $_POST['take_id'];

    // Check if the ID is already taken
    $check_sql = "SELECT taken_by, taken_at FROM found_ids WHERE id = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $id_to_take);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($taken_by, $taken_at);
    $stmt->fetch();

    if ($taken_by) {
        // If the ID is already taken, display a message
        $message = "Sorry, this ID has already been taken by another user.";
    } else {
        // Mark the ID as taken by the current user and set the taken_at timestamp
        $update_sql = "UPDATE found_ids SET taken_by = ?, taken_at = NOW(), status = 'taken' WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ii", $_SESSION['user_id'], $id_to_take);
        $stmt->execute();

        // Confirmation message
        $message = "The ID has been successfully taken.";
    }
}

// Delete records older than 24 hours (cleanup task)
$current_time = date("Y-m-d H:i:s");
$delete_sql = "
    DELETE FROM found_ids
    WHERE taken_at IS NOT NULL
    AND TIMESTAMPDIFF(HOUR, taken_at, NOW()) >= 24
";
$conn->query($delete_sql);

// Fetch all the found IDs, ordered by created_at (latest first)
$sql_all_posts = "SELECT * FROM found_ids ORDER BY created_at DESC";
$result_all_posts = $conn->query($sql_all_posts);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Found IDs - Take and Cleanup</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f2f5;
            margin: 0;
            padding: 0;
        }
        nav {
            background-color: #333;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .logo {
            font-size: 24px;
            font-weight: bold;
            color: #00ffd5;
        }
        nav ul {
            list-style-type: none;
            display: flex;
            gap: 20px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }
        nav ul li a:hover {
            color: #00ffd5;
        }
        .container {
            width: 95%;
            margin: 30px auto;
            text-align: center;
        }
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background: #4CAF50;
            color: white;
        }
        tr:hover {
            background: #f1f1f1;
        }
        .back-btn {
            margin-top: 20px;
            background: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }
        .back-btn:hover {
            background: #0056b3;
        }
        img {
            width: 100px;
            height: auto;
        }
        .message {
            color: #d9534f;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .taken {
            background-color: green;
            color: white;
            cursor: not-allowed;
        }
        .take-btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .take-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">Find ID</div>
    <ul>
    <li><a href="dashboard.php">Home</a></li>
        <li><a href="post_found_id.php">Register Found ID</a></li>
        <li><a href="last_post.php">my last post Found ID</a></li>
        <li><a href="seeki_records.php">ID owners/Searchers</a></li>
        <li><a href="dash.php">Available IDs</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Help</a></li>
    </ul>
</nav>

<div class="container">
    <?php if (isset($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <h2>Last Found ID Posts</h2>
    <table>
        <thead>
            <tr>
                <th>ID Number</th>
                <th>ID Type</th>
                <th>Description</th>
                <th>Date Found</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_all_posts->num_rows > 0): ?>
                <?php while ($post = $result_all_posts->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($post['id_number']); ?></td>
                        <td><?php echo htmlspecialchars($post['category']); ?></td>
                        <td><?php echo htmlspecialchars($post['description']); ?></td>
                        <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                        <td><img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" alt="ID Image"></td>
                        <td>
                            <?php if (empty($post['taken_at'])): ?>
                                <form action="take_and_cleanup.php" method="post">
                                    <input type="hidden" name="take_id" value="<?php echo $post['id']; ?>">
                                    <button type="submit" class="take-btn">Take It</button>
                                </form>
                            <?php else: ?>
                                <h4>Reported</h4>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">No Found ID Posts Available</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="dashboard.php" class="back-btn">Back to Dashboard</a>
</div>

</body>
</html>

<?php
$conn->close();
?>
