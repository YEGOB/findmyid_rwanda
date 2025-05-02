<?php
include 'db_connect.php';

$message = '';

// Check if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $imageName = '';

    // Check if image uploaded
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/';
        $imageName = time() . '_' . basename($_FILES['image']['name']);
        $uploadPath = $uploadDir . $imageName;

        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
            // File uploaded successfully
        } else {
            $message = "Failed to upload image.";
        }
    }

    if (empty($message)) {
        // Check if admin already exists
        $checkAdmin = mysqli_query($conn, "SELECT * FROM admins ORDER BY id DESC LIMIT 1");
        if (mysqli_num_rows($checkAdmin) > 0) {
            // Update existing admin
            $admin = mysqli_fetch_assoc($checkAdmin);
            $updateQuery = "UPDATE admins SET username = '$username'";
            if (!empty($imageName)) {
                $updateQuery .= ", image = '$imageName'";
            }
            $updateQuery .= " WHERE id = " . $admin['id'];

            if (mysqli_query($conn, $updateQuery)) {
                $message = "Admin updated successfully!";
            } else {
                $message = "Failed to update admin.";
            }
        } else {
            // Insert new admin
            $insertQuery = "INSERT INTO admins (username, image) VALUES ('$username', '$imageName')";
            if (mysqli_query($conn, $insertQuery)) {
                $message = "Admin added successfully!";
            } else {
                $message = "Failed to add admin.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add or Update Admin</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #121212;
      color: white;
      font-family: Arial, sans-serif;
      padding: 40px;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .form-container {
      background-color: #1e1e1e;
      padding: 30px;
      border-radius: 12px;
      width: 400px;
      box-shadow: 0 0 10px #00d4ff;
    }
    input[type="text"], input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-top: 15px;
      margin-bottom: 20px;
      border: none;
      border-radius: 8px;
    }
    input[type="submit"] {
      background-color: #00d4ff;
      border: none;
      padding: 10px 20px;
      border-radius: 8px;
      color: #121212;
      font-weight: bold;
      cursor: pointer;
      width: 100%;
    }
    input[type="submit"]:hover {
      background-color: #00b0cc;
    }
    .message {
      margin-bottom: 20px;
      text-align: center;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Add or Update Admin</h2>

  <?php if (!empty($message)): ?>
    <div class="message"><?php echo htmlspecialchars($message); ?></div>
  <?php endif; ?>

  <form action="" method="POST" enctype="multipart/form-data">
    <label>Username:</label>
    <input type="text" name="username" required>

    <label>Profile Image:</label>
    <input type="file" name="image" accept="image/*">

    <input type="submit" value="Save Admin">
  </form>
</div>

</body>
</html>
