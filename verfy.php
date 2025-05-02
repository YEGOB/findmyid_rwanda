<?php
// Connect to database
$conn = new mysqli('localhost', 'root', '', 'find_id');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submission
$message = '';
$verification_success = false; // Track if verification passed

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['full_name'], $_POST['id_number'], $_POST['category'])) {
        $full_name = trim($_POST['full_name']);
        $id_number = trim($_POST['id_number']);
        $category = trim($_POST['category']);

        $stmt = $conn->prepare("SELECT * FROM found_ids WHERE LOWER(full_name) = LOWER(?) AND id_number = ? AND LOWER(category) = LOWER(?)");
        $stmt->bind_param("sss", $full_name, $id_number, $category);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "<div class='success'>✅ Verification successful! Your ID was found!</div>";
            $verification_success = true;
        } else {
            $message = "<div class='error'>❌ Verification failed! No matching record found.</div>";
        }
    } else {
        $message = "<div class='error'>❌ Please fill in all fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify Your ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
        }
        .container {
            background: #ffffff;
            padding: 40px 30px;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 450px;
            text-align: center;
            position: relative;
            animation: fadeIn 1s ease forwards;
        }
        @keyframes fadeIn {
            from {opacity: 0; transform: translateY(30px);}
            to {opacity: 1; transform: translateY(0);}
        }
        .back-button {
            position: absolute;
            top: -50px;
            left: 0;
            background: #2575fc;
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s;
        }
        .back-button:hover {
            background: #6a11cb;
        }
        h2 {
            margin-bottom: 25px;
            font-size: 28px;
            color: #333;
            font-weight: 700;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-size: 14px;
            text-align: left;
            font-weight: 600;
            color: #666;
        }
        input {
            padding: 12px 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        input:focus {
            border-color: #2575fc;
            background-color: #f0f8ff;
            outline: none;
        }
        button, .take-it-button {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: #fff;
            padding: 14px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }
        button:hover, .take-it-button:hover {
            background: linear-gradient(to right, #2575fc, #6a11cb);
        }
        .success, .error {
            margin-bottom: 20px;
            padding: 12px 20px;
            border-radius: 10px;
            font-weight: bold;
            font-size: 15px;
            animation: pop 0.5s ease;
        }
        @keyframes pop {
            0% { transform: scale(0.8); opacity: 0;}
            100% { transform: scale(1); opacity: 1;}
        }
        .success {
            background: #e1f7e7;
            color: #2e7d32;
        }
        .error {
            background: #fdecea;
            color: #d32f2f;
        }
        .extra-buttons {
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="container">
   

    <h2>Verify Your ID</h2>

    <?php echo $message; ?>

    <form action="" method="POST">
        <label>Full Name</label>
        <input type="text" name="full_name" placeholder="Enter your full name" required>

        <label>ID Number</label>
        <input type="text" name="id_number" placeholder="Enter your ID number" required>

        <label>Type of ID (Category)</label>
        <input type="text" name="category" placeholder="Ex: National ID" required>

        <button type="submit">🔎 Verify Now</button>
    </form>

    <?php if ($verification_success): ?>
        <div class="extra-buttons">
            <form action="take_it.php" method="POST">
                <input type="hidden" name="full_name" value="<?php echo htmlspecialchars($full_name); ?>">
                <input type="hidden" name="id_number" value="<?php echo htmlspecialchars($id_number); ?>">
                <input type="hidden" name="category" value="<?php echo htmlspecialchars($category); ?>">
                <button type="submit" class="take-it-button">🎉 Take It</button>
                <a href="dashboard.php" class="take-it-button"> 🔙 Go Back</a>
            </form>
        </div>
    <?php endif; ?>
</div>

</body>
</html>
