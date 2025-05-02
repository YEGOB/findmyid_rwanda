<?php
require 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $phone_number = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);

    $insert = "INSERT INTO seeki (name, phone_number, description, date) 
               VALUES ('$name', '$phone_number', '$description', '$date')";

    if (mysqli_query($conn, $insert)) {
        $success = "Your report has been submitted successfully!";
    } else {
        $error = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Lost ID</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #e9ecef;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #007BFF;
        }

        label {
            font-weight: bold;
        }

        input[type="text"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }

        button:hover {
            background-color: #0056b3;
        }

        .message {
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        .success {
            background-color: #d4edda;
            color: #155724;
        }

        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Report Your Lost ID</h2>

        <?php if (isset($success)) echo "<div class='message success'>$success</div>"; ?>
        <?php if (isset($error)) echo "<div class='message error'>$error</div>"; ?>

        <form method="POST" action="">
            <label for="name">Your Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="phone_number">Phone Number:</label>
            <input type="text" id="phone_number" name="phone_number" required>

            <label for="description">Describe the Lost ID:</label>
            <textarea id="description" name="description" rows="4" required></textarea>

            <label for="date">Date of Loss:</label>
            <input type="date" id="date" name="date" required>

            <button type="submit">Submit Report</button>
        </form>
    </div>
</body>
</html>
