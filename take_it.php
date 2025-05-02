<?php
// Database connection
$conn = new mysqli('localhost', 'root', '', 'find_id');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$full_name = $_POST['full_name'] ?? '';
$id_number = $_POST['id_number'] ?? '';
$category = $_POST['category'] ?? '';

$info = null;
if ($full_name && $id_number && $category) {
    $stmt = $conn->prepare("SELECT * FROM found_ids WHERE LOWER(full_name) = LOWER(?) AND id_number = ? AND LOWER(category) = LOWER(?)");
    $stmt->bind_param("sss", $full_name, $id_number, $category);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $info = $result->fetch_assoc();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your ID Details</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #89f7fe, #66a6ff);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            flex-direction: column;
        }
        .card {
            background: #ffffff;
            padding: 30px;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
            display: none;
            animation: fadeIn 0.7s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9);}
            to { opacity: 1; transform: scale(1);}
        }
        img {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 20px;
        }
        h2 {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }
        p {
            font-size: 16px;
            color: #555;
            margin: 5px 0;
        }
        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
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
        .agree-box {
            background: #fff;
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 400px;
            width: 100%;
            margin-bottom: 20px;
        }
        .agree-box input {
            margin-right: 10px;
        }
        .ok-btn, .take-btn {
            background: #6a11cb;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 16px;
            margin-top: 10px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .ok-btn:hover, .take-btn:hover {
            background: #2575fc;
        }
        .take-btn {
            margin-top: 20px;
            background: #28a745;
        }
        .take-btn:hover {
            background: #218838;
        }
    </style>
</head>
<body>

<a href="index.php" class="back-button">🔙 Home</a>

<!-- Agreement First -->
<div class="agree-box" id="agreeBox">
    <h2>Before Viewing the ID</h2>
    <label>
        <input type="checkbox" id="agreeCheck">
        I agree that I am the owner of this ID or authorized to retrieve it.
    </label>
    <br>
    <button class="ok-btn" onclick="showCard()">OK</button>
</div>

<!-- ID Information Card -->
<div class="card" id="infoCard">
    <?php if ($info): ?>
        <img src="uploads/<?php echo htmlspecialchars($info['image']); ?>" alt="ID Image">
        <h2><?php echo htmlspecialchars($info['full_name']); ?></h2>
        <p><strong>ID Number:</strong> <?php echo htmlspecialchars($info['id_number']); ?></p>
        <p><strong>Category:</strong> <?php echo htmlspecialchars($info['category']); ?></p>
        <p><strong>Found At:</strong> <?php echo htmlspecialchars($info['location_found']); ?></p>
        <p><strong>Date Found:</strong> <?php echo htmlspecialchars($info['date_found']); ?></p>
        <p><strong>Posted By:</strong> <?php echo htmlspecialchars($info['posted_by']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($info['phone_number']); ?></p>

        <!-- "I Take" Button -->
        <form action="confirm_take.php" method="post">
            <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
            <button class="take-btn" type="submit">✅ I Take</button>
        </form>

    <?php else: ?>
        <p>❌ ID Information not found!</p>
    <?php endif; ?>
</div>

<script>
function showCard() {
    const check = document.getElementById('agreeCheck');
    if (check.checked) {
        document.getElementById('agreeBox').style.display = 'none';
        document.getElementById('infoCard').style.display = 'block';
    } else {
        alert('Please agree to the terms before proceeding.');
    }
}
</script>

</body>
</html>
