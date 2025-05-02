<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "find_id");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'] ?? '';
    $id_number = $_POST['id_number'] ?? '';
    $category = $_POST['category'] ?? '';
    $location_found = $_POST['location_found'] ?? '';
    $date_found = $_POST['date_found'] ?? '';
    $posted_by = $_POST['posted_by'] ?? '';
    $phone_number = $_POST['phone_number'] ?? '';
    $description = $_POST['description'] ?? '';

    // Check if ID number already exists
    $check_query = "SELECT * FROM found_ids WHERE id_number = '$id_number' AND category = '$category'";
    $check_result = $conn->query($check_query);

    if ($check_result->num_rows > 0) {
        $message = "❌ ID Number already exists for this category.";
    } else {
        $image_name = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];
        $upload_folder = "uploads/";

        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0777, true);
        }

        $target_path = $upload_folder . basename($image_name);

        if (move_uploaded_file($tmp_name, $target_path)) {
            $sql = "INSERT INTO found_ids (full_name, id_number, category, image, location_found, date_found, posted_by, phone_number, description)
                    VALUES ('$full_name', '$id_number', '$category', '$image_name', '$location_found', '$date_found', '$posted_by', '$phone_number', '$description')";

            if ($conn->query($sql) === TRUE) {
                $query = "SELECT * FROM seeki WHERE id = '$id_number' AND category = '$category' AND notification_sent = FALSE";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $to = $row['email'];
                        $subject = "ID Found: {$category} - {$id_number}";
                        $message = "Hello {$row['full_name']},\n\nWe have found the ID you're searching for. Please check the system for more details.";
                        $headers = "From: no-reply@findid.com";

                        mail($to, $subject, $message, $headers);

                        $update_query = "UPDATE seekers SET notification_sent = TRUE WHERE id = {$row['id']}";
                        $conn->query($update_query);
                    }
                }

                $message = "✅ Kuragisha byakozwe!";
            } else {
                $message = "❌ Database Error: " . $conn->error;
            }
        } else {
            $message = "❌ Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Found ID</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: url('ca.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', sans-serif;
            color: black;
            min-height: 100vh;
        }
        html { scroll-behavior: smooth; }
        nav {
            background: rgba(0, 0, 0, 0.8);
            padding: 15px 20px;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        nav .logo { font-size: 28px; font-weight: bold; color: #00ffd5; letter-spacing: 2px; }
        nav ul { list-style: none; display: flex; }
        nav ul li { margin: 0 15px; }
        nav ul li a {
            text-decoration: none; color: white; font-weight: bold; transition: color 0.3s;
        }
        nav ul li a:hover { color: #00ffd5; }
        nav .logout-btn {
            background-color: #ff4444; color: white; padding: 8px 20px;
            border: none; border-radius: 7px; font-weight: bold; cursor: pointer;
            transition: background 0.3s;
        }
        nav .logout-btn:hover { background-color: #cc0000; }

        form {
            background: #fff;
            color: #000;
            max-width: 550px;
            margin: 120px auto 50px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #007BFF;
            font-size: 28px;
        }

        input, textarea, select {
            width: 100%;
            background-color: #f5f5f5;
            color: #000;
            margin: 10px 0;
            padding: 12px;
            border-radius: 5px;
            border: none;
            border-bottom: 2px solid #00ffd5;
            outline: none;
        }

        input::placeholder, textarea::placeholder {
            color: rgba(0, 0, 0, 0.5);
        }

        textarea { resize: none; }

        button {
            background: #007BFF;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            color: #fff;
            margin-top: 10px;
            cursor: pointer;
            transition: all 0.3s;
        }

        button:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }

        .msg {
            text-align: center;
            margin-top: 80px;
            padding: 10px;
            font-weight: bold;
            font-size: 18px;
            color: red;
        }
    </style>
</head>
<body>

<nav>
    <div class="logo">Find ID</div>
    <ul>
        <li><a href="dashboard.php">Home</a></li>
        <li><a href="post_found_id.php">Register Found ID</a></li>
        <li><a href="seeki_records.php">ID owners/Searchers</a></li>
        <li><a href="dash.php">Available IDs</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Help</a></li>
    </ul>
    <a href="logout.php" class="logout-btn">Logout</a>
</nav>

<?php if (isset($message)): ?>
    <div class="msg"><?= $message ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <h2>Post a Found ID</h2>
    <input type="text" name="full_name" placeholder="Full Name on ID" required>
    <input type="text" name="id_number" placeholder="ID Number" required>

    <!-- ✅ CATEGORY SELECT ADDED -->
    <select name="category" required>
        <option value="">-- Select ID Type --</option>
        <option value="National ID">National ID</option>
        <option value="Student ID">Student ID</option>
        <option value="Driver's License">Driver's License</option>
        <option value="Passport">Passport</option>
        <!-- add more as needed -->
    </select>

    <select name="location_found" required>
        <option value="">-- Select District Where ID Was Found --</option>
        <option value="Bugesera">Bugesera</option>
        <option value="Burera">Burera</option>
        <option value="Gakenke">Gakenke</option>
        <option value="Gasabo">Gasabo</option>
        <option value="Gatsibo">Gatsibo</option>
        <option value="Gicumbi">Gicumbi</option>
        <option value="Gisagara">Gisagara</option>
        <option value="Huye">Huye</option>
        <option value="Kamonyi">Kamonyi</option>
        <option value="Karongi">Karongi</option>
        <option value="Kayonza">Kayonza</option>
        <option value="Kicukiro">Kicukiro</option>
        <option value="Kirehe">Kirehe</option>
        <option value="Muhanga">Muhanga</option>
        <option value="Musanze">Musanze</option>
        <option value="Ngoma">Ngoma</option>
        <option value="Ngororero">Ngororero</option>
        <option value="Nyabihu">Nyabihu</option>
        <option value="Nyagatare">Nyagatare</option>
        <option value="Nyamagabe">Nyamagabe</option>
        <option value="Nyamasheke">Nyamasheke</option>
        <option value="Nyanza">Nyanza</option>
        <option value="Nyarugenge">Nyarugenge</option>
        <option value="Nyaruguru">Nyaruguru</option>
        <option value="Rubavu">Rubavu</option>
        <option value="Ruhango">Ruhango</option>
        <option value="Rulindo">Rulindo</option>
        <option value="Rusizi">Rusizi</option>
        <option value="Rutsiro">Rutsiro</option>
        <option value="Rwamagana">Rwamagana</option>
    </select>

    <input type="date" name="date_found" required>
    <input type="text" name="posted_by" placeholder="Your Name (who found it)" required>
    <input type="text" name="phone_number" placeholder="Your Phone Number" required>
    <textarea name="description" placeholder="Description of the ID" rows="4" required></textarea>
    <input type="file" name="image" accept="image/*" required>
    <button type="submit">📤 Post ID</button>
</form>

<?php include 'footer.php'; ?>

</body>
</html>
