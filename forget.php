<?php
include('db_connect.php');

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $rule = $_POST['rule'] ?? '';
    $old_password = $_POST['old_password'] ?? '';
    $new_password = isset($_POST['new_password']) ? password_hash($_POST['new_password'], PASSWORD_DEFAULT) : '';

    if (!empty($email) && !empty($rule) && !empty($old_password) && !empty($new_password)) {

        // Check if user exists
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ? AND rule = ?");
        $stmt->bind_param("ss", $email, $rule);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($db_password);
            $stmt->fetch();

            if (password_verify($old_password, $db_password)) {
                $stmt->close();

                // Update password
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ? AND rule = ?");
                $update_stmt->bind_param("sss", $new_password, $email, $rule);
                $update_stmt->execute();
                $update_stmt->close();

                echo "<div class='success-message'>Password reset successfully!</div>";
            } else {
                echo "<div class='error-message'>Old password is incorrect!</div>";
            }
        } else {
            echo "<div class='error-message'>Email or role not found!</div>";
        }
    } else {
        echo "<div class='error-message'>Please fill in all fields!</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Reset Password</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background: url('cre.jpg') no-repeat center center fixed;
      background-size: cover;
      height: 100vh;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0; left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      z-index: 0;
    }

    .register-container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 12px;
      max-width: 450px;
      margin: 80px auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    .register-container h2 {
      margin-bottom: 20px;
      color: #333;
      font-size: 2rem;
    }

    .register-container input,
    .register-container select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      font-size: 1rem;
      border: 2px solid #ddd;
      border-radius: 8px;
      transition: 0.3s;
    }

    .register-container input:focus,
    .register-container select:focus {
      border-color: #ff7b7b;
      outline: none;
    }

    .register-container input[type="submit"] {
      background-color: #ff7b7b;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }

    .register-container input[type="submit"]:hover {
      background-color: #ff5c5c;
    }

    .footer {
      margin-top: 20px;
      font-size: 0.9rem;
    }

    .footer a {
      color: #ff7b7b;
      text-decoration: none;
    }

    .footer a:hover {
      text-decoration: underline;
    }

    .success-message, .error-message {
      position: absolute;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      font-size: 1rem;
      padding: 10px 20px;
      border-radius: 5px;
      z-index: 1000;
    }

    .success-message {
      color: green;
      background: #e6ffe6;
      border: 1px solid #b2ffb2;
    }

    .error-message {
      color: red;
      background: #ffe6e6;
      border: 1px solid #ffb2b2;
    }

    .toggle-password {
      margin-bottom: 10px;
      cursor: pointer;
      color: #333;
      font-size: 0.9rem;
      user-select: none;
    }
  </style>
</head>
<body>

<div class="register-container">
  <h2>Reset Your Password</h2>
  <form action="" method="POST">
    <input type="email" name="email" placeholder="Email Address" required><br>

    <label for="rule">Select Role:</label>
    <select name="rule" id="rule" required>
      <option value="">--Select Role--</option>
      <option value="Admin">Admin</option>
      <option value="User">User</option>
      <option value="Manager">Manager</option>
      <option value="Guest">Guest</option>
    </select><br>

    <input type="password" id="old_password" name="old_password" placeholder="Enter Old Password" required><br>
    <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required><br>

    <div class="toggle-password" onclick="togglePassword()">👁️ Show/Hide Password</div>

    <input type="submit" value="Reset Password">
  </form>

  <div class="footer">
    <p>Back to <a href="login.php">Login</a></p>
  </div>
</div>

<script>
function togglePassword() {
    var old_pass = document.getElementById("old_password");
    var new_pass = document.getElementById("new_password");

    if (old_pass.type === "password") {
        old_pass.type = "text";
        new_pass.type = "text";
    } else {
        old_pass.type = "password";
        new_pass.type = "password";
    }
}
</script>

</body>
</html>
