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

$sql = "SELECT * FROM found_ids ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Find ID</title>
    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            overflow-x: hidden;
            background-color: #f0f2f5;
        }

        .background-slideshow {
            position: fixed;
            top: 0; left: 0;
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
            0%   { opacity: 0; }
            10%  { opacity: 1; }
            30%  { opacity: 1; }
            40%  { opacity: 0; }
            100% { opacity: 0; }
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

        .logout-btn {
            background-color: #ff4444;
            border: none;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }

        .card-button {
            margin-top: 15px;
            background-color: rgb(7, 109, 188);
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            display: inline-block;
            text-decoration: none;
        }

        .card-button:hover {
            background-color: rgb(0, 134, 179);
        }

        .welcome-section {
            text-align: center;
            margin-top: 100px;
            color: white;
        }

        .welcome-section h1 {
            font-size: 40px;
            text-shadow: 2px 2px 10px black;
        }

        .dashboard-icons {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            padding: 30px;
        }

        .dashboard-icons .card {
            background-color: rgba(0, 0, 0, 0.8);
            margin: 15px;
            padding: 20px;
            border-radius: 10px;
            width: 280px;
            text-align: center;
            color: white;
            transition: transform 0.3s;
            box-shadow: 0 4px 8px rgba(0,0,0,0.3);
            position: relative;
        }

        .dashboard-icons .card:hover {
            transform: scale(1.05);
        }

        .dashboard-icons .card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .dashboard-icons .card p {
            margin: 8px 0;
            font-size: 16px;
        }

        .taken-card {
            background-color:rgb(30, 179, 0) !important;
        }

        .card-button.disabled {
            background-color: grey;
            cursor: not-allowed;
            pointer-events: none;
        }

        #countdown {
            margin-top: 10px;
            font-weight: bold;
            color: yellow;
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                align-items: flex-start;
            }
            nav ul {
                flex-direction: column;
            }
            nav ul li {
                margin: 10px 0;
            }
            .dashboard-icons {
                padding: 10px;
            }
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
        < <li><a href="dashboard.php">Home</a></li>
        <li><a href="post_found_id.php">Register Found IDa</a></li>
        <li><a href="seeki_records.php">ID owners/Searchers</a></li>
        <li><a href="dash.php">Available IDs</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">help</a></li>
    </ul>
    <form action="logout.php" method="POST">
        <button class="logout-btn" type="submit">Logout</button>
        <a href="search_id.php" class="card-button">Search</a>
    </form>
</nav>

<div class="welcome-section">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</h1>
    <p>Track and find lost IDs easily.</p>
</div>

<div class="dashboard-icons">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <?php $is_taken = $row['is_taken']; ?>
            <div class="card <?= $is_taken ? 'taken-card' : '' ?>" id="card-<?= $row['id'] ?>">
                <p><strong>Name on ID:</strong><br><?= htmlspecialchars($row['full_name']) ?></p>
                <p><strong>Found By:</strong><br><?= htmlspecialchars($row['posted_by']) ?></p>
                <p><strong>Description:</strong><br><?= htmlspecialchars($row['description']) ?></p>

                <?php if (!empty($row['location_found'])): ?>
                    <p><strong>Location Found:</strong><br><?= htmlspecialchars($row['location_found']) ?></p>
                <?php endif; ?>

                <?php if (!empty($row['date_found'])): ?>
                    <p><strong>Date Found:</strong><br><?= htmlspecialchars($row['date_found']) ?></p>
                <?php endif; ?>

                <?php if ($is_taken): ?>
                    <button class="card-button disabled">Already Taken</button>
                    <div id="countdown-<?= $row['id'] ?>"></div>

                    <script>
                        var countDownDate<?= $row['id'] ?> = new Date("<?= date('Y-m-d H:i:s', strtotime($row['taken_at'].' +24 hours')) ?>").getTime();
                        var x<?= $row['id'] ?> = setInterval(function() {
                            var now = new Date().getTime();
                            var distance = countDownDate<?= $row['id'] ?> - now;

                            if (distance < 0) {
                                clearInterval(x<?= $row['id'] ?>);
                                document.getElementById("countdown-<?= $row['id'] ?>").innerHTML = "Expired ⌛";
                                setTimeout(function() {
                                    var card = document.getElementById("card-<?= $row['id'] ?>");
                                    if (card) {
                                        card.style.transition = "opacity 1s";
                                        card.style.opacity = 0;
                                        setTimeout(function(){ card.remove(); }, 1000);
                                    }
                                }, 2000);
                            } else {
                                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                document.getElementById("countdown-<?= $row['id'] ?>").innerHTML =
                                    "⏳ Expires in " + hours + "h " + minutes + "m " + seconds + "s "
                            }
                        }, 1000);
                    </script>
                <?php else: ?>
                    <a href="verfy.php?id=<?= htmlspecialchars($row['id']) ?>" class="card-button">🔍 View/Request</a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align:center; color:white;">❗ No ID posts yet.</p>
    <?php endif; ?>
</div>

<?php include 'find/index.php'; ?>
<?php include 'footer.php'; ?>

</body>
</html>
