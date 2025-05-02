<?php
// connect to the database
include 'db_connect.php';

// Get ID from URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch ID data
$query = mysqli_query($conn, "SELECT * FROM found_ids WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "ID not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View ID Details</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      background-color: #121212;
      color: white;
      font-family: Arial, sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }
    .card {
      background-color: #1e1e1e;
      padding: 30px;
      border-radius: 12px;
      text-align: center;
      max-width: 400px;
      box-shadow: 0px 4px 15px rgba(0, 212, 255, 0.3);
    }
    img {
      width: 100%;
      height: auto;
      border-radius: 12px;
      margin-bottom: 20px;
      object-fit: cover;
    }
    h2, p {
      margin: 10px 0;
    }
    .back-btn {
      display: inline-block;
      margin-top: 20px;
      padding: 10px 20px;
      background-color: #00d4ff;
      color: #121212;
      text-decoration: none;
      border-radius: 8px;
      font-weight: bold;
    }
    .back-btn:hover {
      background-color: #00b0cc;
    }
  </style>
</head>
<body>

<div class="card">
<img src="uploads/<?php echo htmlspecialchars($data['image']); ?>" alt="ID Image">
  <h2><?php echo htmlspecialchars($data['full_name']); ?></h2>
  <p><strong>ID Number:</strong> <?php echo htmlspecialchars($data['id_number']); ?></p>

  <a href="admin_found_ids.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
</div>

</body>
</html>
