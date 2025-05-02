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
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Found IDs - Admin Panel</title>
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
    .sidebar {
      width: 220px;
      background-color: #1e1e1e;
      height: 100vh;
      position: fixed;
      left: 0;
      top: 0;
      bottom: 0;
    }
    .main-content {
      margin-left: 220px;
      padding: 20px;
      width: 100%;
    }
    h1 {
      margin-top: 0;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      background-color: #1e1e1e;
      margin-top: 20px;
      border-radius: 12px;
      overflow: hidden;
    }
    th, td {
      padding: 12px 15px;
      text-align: center;
      border-bottom: 1px solid #333;
    }
    th {
      background-color: #00d4ff;
      color: #121212;
      font-weight: bold;
    }
    tr:hover {
      background-color: #292929;
    }
    .action-buttons i {
      margin: 0 8px;
      cursor: pointer;
      color: #00d4ff;
    }
    img {
      width: 70px;
      height: 70px;
      border-radius: 10px;
      object-fit: cover;
    }
    .add-new {
      background-color: #00d4ff;
      color: #121212;
      padding: 10px 20px;
      margin-bottom: 20px;
      display: inline-block;
      border-radius: 8px;
      font-weight: bold;
      text-decoration: none;
    }
    .add-new:hover {
      background-color: #00b0cc;
    }
  </style>
</head>
<body>

<?php include 'leftbar.php'; ?>

<div class="main-content">
  <h1>Found IDs Management</h1>

  <a href="add_found_id.php" class="add-new"><i class="fas fa-plus"></i> Add New Found ID</a>

  <table>
    <thead>
      <tr>
        <th>Image</th>
        <th>Full Name</th>
        <th>ID Number</th>
        <th>Phone Number</th>
        <th>Location</th>
        <th>Posted By</th>
        <th>Date Found</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $result = mysqli_query($conn, "SELECT * FROM found_ids ORDER BY created_at DESC");
      while($row = mysqli_fetch_assoc($result)):
      ?>
      <tr>
        <td>
          <a href="image.php?id=<?php echo $row['id']; ?>">
            <img src="uploads/<?php echo htmlspecialchars($row['image']); ?>" alt="ID Image">
            
          </a>
        </td>
        <td><?php echo htmlspecialchars($row['full_name']); ?></td>
        <td><?php echo htmlspecialchars($row['id_number']); ?></td>
        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
        <td><?php echo htmlspecialchars($row['location_found']); ?></td>
        <td><?php echo htmlspecialchars($row['posted_by']); ?></td>
        <td><?php echo htmlspecialchars($row['date_found']); ?></td>
        <td class="action-buttons">
          <a href="edit_found_id.php?id=<?php echo $row['id']; ?>"><i class="fas fa-edit"></i></a>
          <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this ID?');"><i class="fas fa-trash"></i></a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

</body>
</html>
