<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $sql = "SELECT * FROM users WHERE email = ? AND rule = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_rule'] = $user['rule'];

        if ($role == 'Admin') {
            header('Location: find/admin.php');
        } elseif ($role == 'User') {
            header('Location: dashboard.php');
        } else {
            header('Location: guest_dashboard.php');
        }
        exit();
    } else {
        $errorMessage = "Invalid email, password, or role.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
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
      background-color: rgba(0, 0, 0, 0.5); /* dark overlay */
      z-index: 0;
    }

    .login-container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.95);
      padding: 40px;
      border-radius: 12px;
      max-width: 400px;
      margin: 100px auto;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
      text-align: center;
    }

    .login-container h2 {
      margin-bottom: 20px;
      color: #333;
      font-size: 2rem;
    }

    .login-container input,
    .login-container select {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      font-size: 1rem;
      border: 2px solid #ddd;
      border-radius: 8px;
      transition: 0.3s;
    }

    .login-container input:focus,
    .login-container select:focus {
      border-color: #ff7b7b;
      outline: none;
    }

    .login-container input[type="submit"] {
      background-color: #ff7b7b;
      color: white;
      font-weight: bold;
      border: none;
      cursor: pointer;
    }

    .login-container input[type="submit"]:hover {
      background-color: #ff5c5c;
    }

    .error-message {
      color: red;
      background: #fddede;
      padding: 10px;
      border: 1px solid #fbb1b1;
      border-radius: 5px;
      margin-top: 10px;
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

    .toggle-password {
      margin-top: 10px;
      cursor: pointer;
      color: #333;
      font-size: 0.9rem;
      user-select: none;
    }
  </style>
</head>
<body>

<div class="login-container">
  <h2>Login</h2>
  <form action="" method="POST">
    <input type="email" name="email" placeholder="Email Address" required>
    <input type="password" id="password" name="password" placeholder="Password" required>
    
    <div class="toggle-password" onclick="togglePassword()">👁️ Show/Hide Password</div><br>

    <label for="role">Select Role</label>
    <select name="role" id="role" required>
      <option value="">--Select Role--</option>
      <option value="Admin">Admin</option>
      <option value="User">User</option>
      <option value="Guest">Guest</option>
    </select>

    <input type="submit" value="Login">
  </form>

  <?php if (isset($errorMessage)) echo "<div class='error-message'>$errorMessage</div>"; ?>

  <div class="footer">
    <p>Don't have an account? <a href="register.php">Register here</a></p>
  </div>
  <div class="footer">
    <p>Reset my password? <a href="forget.php">Reset here</a></p>
  </div>
</div>

<script>
function togglePassword() {
    var passwordField = document.getElementById("password");
    if (passwordField.type === "password") {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>

</body>
</html>
