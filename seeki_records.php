<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require 'db_connect.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_number = mysqli_real_escape_string($conn, $_POST['id_number']);
    $id_type = mysqli_real_escape_string($conn, $_POST['id_type']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $sql_insert = "INSERT INTO seeki (id_number, id_type, name, phone_number, description, date)
                   VALUES ('$id_number', '$id_type', '$name', '$phone', '$description', '$date')";

    if (mysqli_query($conn, $sql_insert)) {
        $message = "✅ Your lost ID report has been submitted successfully!";
        $message_class = "success";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
        $message_class = "error";
    }
}

// Fetch all posted found IDs
$found_query = "SELECT id_number, category FROM found_ids";
$found_result = mysqli_query($conn, $found_query);
$found_ids = [];
while ($row = mysqli_fetch_assoc($found_result)) {
    $found_ids[] = $row['id_number'] . '|' . $row['category'];
}

// Detect if there's at least one match
$match_found = false;
$seek_check_query = "SELECT id_number, id_type FROM seeki";
$seek_check_result = mysqli_query($conn, $seek_check_query);
while ($seek_row = mysqli_fetch_assoc($seek_check_result)) {
    $key = $seek_row['id_number'] . '|' . $seek_row['id_type'];
    if (in_array($key, $found_ids)) {
        $match_found = true;
        break;
    }
}

// Display Seekers Messages
$query = "SELECT * FROM seeki ORDER BY date DESC";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seekers Styled Messages</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body {
            background-color: #f4f4f4;
            color: #333;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .form-header {
            background-color: #007BFF;
            color: white;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
            margin-bottom: 30px;
        }

        .message-card {
            background-color: #fff;
            border-left: 5px solid #00aaff;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .message-card h3 {
            color: #007BFF;
            font-size: 20px;
            margin: 0;
        }

        .message-card p {
            line-height: 1.6;
            font-size: 16px;
            margin-right: 20px;
            flex-grow: 1;
        }

        .message-card .date {
            font-size: 12px;
            color: #aaa;
            margin-top: 10px;
        }

        .notification-icon {
            font-size: 24px;
            margin-left: 10px;
        }

        .notification-icon.new {
            color: red;
        }

        .notification-icon.old {
            color: #007BFF;
        }

        .success {
            color: green;
            font-weight: bold;
        }

        .error {
            color: red;
            font-weight: bold;
        }

        .back-btn {
            background-color: #007BFF;
            color: white;
            padding: 12px 18px;
            border-radius: 6px;
            text-decoration: none;
            display: inline-block;
            font-weight: bold;
        }

        .back-btn:hover {
            background-color: #0056b3;
        }

        .id-match {
            color: red;
            font-weight: bold;
        }

        .id-normal {
            color: black;
        }

        .alert-box {
            background-color: #ffdddd;
            color: #a94442;
            border: 1px solid #f5c6cb;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="container">

    <?php if (isset($message)): ?>
        <p class="<?= $message_class ?>"><?= $message ?></p>
    <?php endif; ?>

    <?php if ($match_found): ?>
        <div class="alert-box">
            <i class="fa fa-bell"></i> 🔔 A found ID matches a reported lost ID. Please check below!
        </div>
    <?php endif; ?>

    <div class="form-header">
        <h2>Messages from Seekers</h2>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <?php
                $match_key = $row['id_number'] . '|' . $row['id_type'];
                $is_match = in_array($match_key, $found_ids);
            ?>
            <div class="message-card">
                <div>
                    <h3><?= htmlspecialchars($row['name']) ?> (<?= htmlspecialchars($row['phone_number']) ?>)</h3>
                    <p>
                        <strong>ID Number:</strong> <span class="<?= $is_match ? 'id-match' : 'id-normal' ?>"><?= htmlspecialchars($row['id']) ?></span><br>
                        <strong>ID Type:</strong> <span class="<?= $is_match ? 'id-match' : 'id-normal' ?>"><?= htmlspecialchars($row['id_type']) ?></span><br>
                        <?= nl2br(htmlspecialchars($row['description'])) ?>
                    </p>
                    <div class="date">Date: <?= htmlspecialchars($row['date']) ?></div>
                </div>
                <div class="notification-icon <?= $is_match ? 'new' : 'old' ?>">
                    <i class="fa fa-bell"></i>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No messages found.</p>
    <?php endif; ?>

    <a href="dashboard.php" class="back-btn">← Back to Dashboard</a>
</div>

</body>
</html>
