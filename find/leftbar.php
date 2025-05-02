<?php
// sidebar.php

include 'db_connect.php';

// Fetch the latest admin (or the one you want)
$adminResult = mysqli_query($conn, "SELECT * FROM admins ORDER BY id DESC LIMIT 1");
$admin = mysqli_fetch_assoc($adminResult);
?>

<div class="sidebar">
  <!-- Admin Profile Section -->
  <div class="profile">
    <a href="admin_add.php">
      <img src="uploads/<?php echo htmlspecialchars($admin['image']); ?>" alt="Admin Photo">
    </a>
    <h3><?php echo htmlspecialchars($admin['username']); ?></h3>
  </div>

  <!-- Sidebar Links -->
  <a href="admin.php" class="admin-link">
    <i class="fas fa-home"></i> Dashboard
  </a>
  <a href="seek.php">
    <i class="fas fa-id-badge"></i> Lost IDs
  </a>
  <a href="found_id.php">
    <i class="fas fa-search"></i> Found IDs
  </a>
  <a href="#">
    <i class="fas fa-users"></i> Users
  </a>
  <a href="#">
    <i class="fas fa-bell"></i> Notifications
  </a>
  <a href="#">
    <i class="fas fa-user-shield"></i> Agents
  </a>
  <a href="../home.php">
    <i class="fas fa-sign-out-alt"></i> Logout
  </a>
</div>

<!-- Sidebar Styles -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">

<style>
.sidebar {
  width: 220px;
  background-color: #1e1e1e;
  display: flex;
  flex-direction: column;
  padding-top: 20px;
  height: 100vh;
  position: fixed;
  align-items: center;
}
.profile {
  display: flex;
  flex-direction: column;
  align-items: center;
  margin-bottom: 20px;
}
.profile img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 50%;
  border: 2px solid white;
  cursor: pointer; /* Make it look clickable */
}
.profile h3 {
  margin-top: 10px;
  font-size: 18px;
}
.sidebar a {
  width: 100%;
  padding: 15px 20px;
  text-decoration: none;
  color: white;
  display: flex;
  align-items: center;
  gap: 10px;
  transition: background 0.3s;
}
.sidebar a:hover {
  background-color: #333;
}
.dashboard-link {
  background-color: #007bff;
}
.login-link {
  background-color: #28a745;
}
</style>
