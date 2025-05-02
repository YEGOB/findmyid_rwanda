<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "find_id");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = null;
$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $search_term = $conn->real_escape_string($_POST['search_term']);
    $sql = "SELECT * FROM found_ids WHERE full_name LIKE '%$search_term%' OR id_number LIKE '%$search_term%'";
    $result = $conn->query($sql);

    if ($result->num_rows == 0) {
        $errorMessage = "❌ No matching ID found. Please try again!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search ID - Find ID</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f0f4f8;
            color: #333;
        }

        .background-slideshow img {
            display: none;
        }

        nav {
            background-color: #111;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: white;
        }

        nav .logo {
            font-size: 26px;
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
            font-weight: 500;
        }

        nav ul li a:hover {
            color: #00ffd5;
        }

        .logout-btn {
            background-color: #f44336;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
        }

        .container {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .welcome-section h1 {
            text-align: center;
            font-size: 32px;
        }

        .search-form {
            text-align: center;
            margin: 30px 0;
        }

        .search-form input {
            padding: 10px;
            width: 280px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        .search-form button {
            padding: 10px 20px;
            background-color: #007BFF;
            border: none;
            color: white;
            font-weight: bold;
            border-radius: 6px;
            margin-left: 10px;
            cursor: pointer;
        }

        .search-form button:hover {
            background-color: #0056b3;
        }

        .error-message {
            text-align: center;
            color: #e74c3c;
            font-weight: bold;
            margin-top: 15px;
        }

        .dashboard-icons {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            width: 300px;
            padding: 15px;
            text-align: center;
        }

        .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .card p {
            margin: 6px 0;
        }

        .card-button {
            margin-top: 10px;
            background-color: #0968c0;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }

        .card-button:hover {
            background-color: #054c8a;
        }

        @media (max-width: 600px) {
            .dashboard-icons {
                flex-direction: column;
                align-items: center;
            }
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">Find ID</div>
    <ul>
    <li><a href="dashboard.php">Home</a></li>
        <li><a href="post_found_id.php">Register Found IDa</a></li>
        <li><a href="seeki_records.php">ID owners/Searchers</a></li>
        <li><a href="dash.php">Available IDs</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">help</a></li>
    </ul>
    <div>  <a href="logout.php" class="logout-btn">Logout</a>
  
      
       
    </div>
</nav>

<div class="container">
    <div class="welcome-section">
        <h1>Murakaza neza, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    </div>

    <div class="search-form">
        <form method="POST" action="search_id.php">
            <input type="text" name="search_term" placeholder="Enter ID number or name" required>
            <button type="submit">Search</button>
        </form>
        <?php if (!empty($errorMessage)): ?>
            <div class="error-message"><?php echo $errorMessage; ?></div>
        <?php endif; ?>
    </div>

    <div class="dashboard-icons">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="card">
                  
                    <p><strong>Name:</strong> <?= htmlspecialchars($row['full_name']) ?></p>
                    <p><strong>Found by:</strong> <?= htmlspecialchars($row['posted_by']) ?></p>
                    <p><strong>Phone:</strong> <?= htmlspecialchars($row['phone_number']) ?></p>
                    <p><strong>ibisobanuro:</strong><br><?= htmlspecialchars($row['description']) ?></p>
                    <a href="verfy.php?id=<?= htmlspecialchars($row['id']) ?>" class="card-button">🔍 View/Request</a>
                </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

</body>
</html>
