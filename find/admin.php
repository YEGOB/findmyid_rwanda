<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../home.php");
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
 
 <!-- admin_dashboard.php -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #121212;
      color: white;
      display: flex;
      min-height: 100vh;
    }
    .main-content {
      margin-left: 220px; /* Leave space for sidebar */
      flex-grow: 1;
      padding: 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    .header {
      width: 100%;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      padding: 10px 20px;
      background-color: #1e1e1e;
      position: sticky;
      top: 0;
      z-index: 100;
    }
    .notification {
      position: relative;
      font-size: 24px;
      cursor: pointer;
    }
    .notification::after {
      content: "3"; /* Example: 3 notifications */
      position: absolute;
      top: -8px;
      right: -10px;
      background: red;
      color: white;
      width: 18px;
      height: 18px;
      border-radius: 50%;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    h1 {
      margin-bottom: 30px;
    }
    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
      width: 100%;
      margin-top: 40px;
      padding: 0 20px;
    }
    .card {
      background-color: #1e1e1e;
      border-radius: 15px;
      height: 200px;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 22px;
      font-weight: bold;
      cursor: pointer;
      transition: 0.3s;
      box-shadow: 0 0 10px rgba(255, 255, 255, 0.1);
    }
    .card:hover {
      background-color: #333;
      transform: scale(1.05);
    }
    .card i {
      font-size: 50px;
      margin-bottom: 15px;
      color: #00d4ff;
    }
  </style>
</head>
<body>

<?php include 'leftbar.php'; ?>

<div class="main-content">
  <div class="header">
    <div class="notification">
      <i class="fas fa-bell"></i>
    </div>
  </div>

  <h1>Quick Access</h1>

  <div class="cards">
    <div class="card">
      <i class="fas fa-id-badge"></i>
      Lost IDs
    </div>
    <div class="card">
      <i class="fas fa-search"></i>
      Found IDs
    </div>
    <div class="card">
      <i class="fas fa-users"></i>
      Users
    </div>
    <div class="card">
      <i class="fas fa-bell"></i>
      Notifications
    </div>
    <div class="card">
      <i class="fas fa-user-shield"></i>
      Admins
    </div>
  </div>
</div>

</body>
</html>
