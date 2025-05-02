<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../home.php");
    exit;
}

include 'db_connect.php';

if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_query = "DELETE FROM seeki WHERE id = $delete_id";
    mysqli_query($conn, $delete_query);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$query = "SELECT id, name, phone_number, date, found_status, id_number, id_type FROM seeki ORDER BY date DESC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lost IDs - Admin Panel</title>
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
    .delete-btn {
      background-color: #ff4d4d;
      color: white;
      padding: 6px 12px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
    }
    .delete-btn:hover {
      background-color: #cc0000;
    }
  </style>
</head>
<body>

<?php include 'leftbar.php'; ?>

<div class="main-content">
  <h1>People Who Lost Their IDs</h1>

  <table>
    <thead>
      <tr>
        <th>Name</th>
        <th>Phone Number</th>
        <th>ID Number</th>
        <th>ID Type</th>
        <th>Date Lost</th>
        <th>Status</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = mysqli_fetch_assoc($result)): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['name']); ?></td>
        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
        <td><?php echo htmlspecialchars($row['id']); ?></td>
       
        <td><?php echo htmlspecialchars($row['id_type']); ?></td>
        <td><?php echo htmlspecialchars($row['date']); ?></td>
        <td>
          <?php echo $row['found_status'] == 1 ? '✅' : '❌'; ?>
        </td>
        <td>
          <a href="?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this entry?');">
            <button class="delete-btn"><i class="fas fa-trash"></i> Delete</button>
          </a>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

</div>

</body>
</html>
