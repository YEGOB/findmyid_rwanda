<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Connect to database
$conn = new mysqli("localhost", "root", "", "find_id");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch the last post
$sql_last_post = "SELECT * FROM found_ids ORDER BY id DESC LIMIT 1";
$result_last_post = $conn->query($sql_last_post);
$last_post = $result_last_post->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Find ID</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        /* Your existing styles */
        html {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f4f4f4;
            color: #333;
        }
        .background-slideshow {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }
        .background-slideshow img {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            animation: fade 15s infinite;
        }
        .background-slideshow img:nth-child(1) { animation-delay: 0s; }
        .background-slideshow img:nth-child(2) { animation-delay: 5s; }
        .background-slideshow img:nth-child(3) { animation-delay: 10s; }
        @keyframes fade {
            0%, 40%, 100% { opacity: 0; }
            10%, 30% { opacity: 1; }
        }
        nav {
            background-color: rgba(0, 0, 0, 0.7);
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
            margin: 0;
            padding: 0;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            text-decoration: none;
            color: white;
            font-weight: 500;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #00ffd5;
        }
        .logout-btn, .card-button {
            background-color: #007BFF;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            margin-left: 10px;
        }
        .dashboard-icons {
            display: flex;
            justify-content: center;
            gap: 30px;
            margin-top: 100px;
            animation: slideInLoop 0.8s ease-out 0s infinite alternate;
            flex-wrap: wrap;
        }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
            width: 250px;
        }
        .card img {
            width: 100%;
            border-radius: 8px;
        }
        .card-button:hover {
            background-color: #0056b3;
        }
        @keyframes slideInLoop {
            0% { transform: translateX(2%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        .lost-id-form {
            max-width: 600px;
            margin: 40px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .lost-id-form h2 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }
        .lost-id-form label {
            font-size: 16px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .lost-id-form input,
        .lost-id-form textarea,
        .lost-id-form select {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .lost-id-form button {
            background-color: #007BFF;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }
        .lost-id-form button:hover {
            background-color: #0056b3;
        }

        /* Last Post Button Styling */
        .last-post-section {
            margin-top: 30px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .last-post-button {
            background-color: #28a745;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            display: inline-block;
            margin-top: 10px;
        }
        .last-post-button:hover {
            background-color: #218838;
        }

        /* Button for Last Post at the top of the image */
        .top-button-container {
            position: absolute;
            top: 120px; /* Adjust as needed to position it below the navbar */
            left: 50%;
            transform: translateX(-50%);
            z-index: 1;
        }
        .top-button {
            background-color: #ff4500;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            font-weight: bold;
        }
        .top-button:hover {
            background-color: #e43f00;
        }
    </style>
</head>
<body>
<div class="background-slideshow">
    <img src="ca.jpg" alt="Background 1">
    <img src="card.jpg" alt="Background 2">
    <img src="cre.jpg" alt="Background 3">
</div>



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
    <form action="logout.php" method="POST" style="display: flex; align-items: center;">
        <button class="logout-btn" type="submit">Logout</button>
        <a href="search_id.php" class="card-button">Search</a>
    </form>
</nav>

<section class="dashboard-icons">
    <div class="card">
        <img src="c.jpg" alt="Card 1">
        <p>Option 1</p>
        <a href="#" class="card-button">Explore</a>
    </div>
    <div class="card">
        <img src="card.jpg" alt="Card 2">
        <p>Option 2</p>
        <a href="#" class="card-button">Explore</a>
    </div>
    <div class="card">
        <img src="ca.jpg" alt="Card 3">
        <p>Option 3</p>
        <a href="#" class="card-button">Explore</a>
    </div>
    <div class="card">
        <img src="cre.jpg" alt="Card 4">
        <p>Option 4</p>
        <a href="#" class="card-button">Explore</a>
    </div>
</section>

<div class="lost-id-form">
    <h2>Report Your Lost ID</h2>
    <form method="POST" action="">
        <label for="id">ID Number:</label>
        <input type="text" id="id" name="id" required> <!-- ID Number -->
        
        <label for="id_type">Type of ID:</label>
        <select id="id_type" name="id_type" required>
            <option value="">Select ID Type</option>
            <option value="Passport">Passport</option>
            <option value="National ID">National ID</option>
            <option value="Driver's License">Driver's License</option>
            <option value="Voter's Card">Voter's Card</option>
            <option value="Other">Other</option>
        </select>
        
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


    


<?php include 'chat.php';?>
<?php include 'find/index.php'; ?>

<?php include 'footer.php'; ?>

</body>
</html>

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $conn->real_escape_string($_POST['id']); // ID Number
    $id_type = $conn->real_escape_string($_POST['id_type']); // ID Type
    $name = $conn->real_escape_string($_POST['name']);
    $phone = $conn->real_escape_string($_POST['phone_number']);
    $description = $conn->real_escape_string($_POST['description']);
    $date = $conn->real_escape_string($_POST['date']);

    $sql_insert = "INSERT INTO seeki (id, id_type, name, phone_number, description, date)
                   VALUES ('$id', '$id_type', '$name', '$phone', '$description', '$date')"; // Insert ID and ID Type

    if ($conn->query($sql_insert) === TRUE) {
        echo "<script>alert('✅ Your lost ID report has been submitted successfully.');</script>";
    } else {
        echo "<script>alert('❌ Error submitting your report: " . $conn->error . "');</script>";
    }
}
?>
