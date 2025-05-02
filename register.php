<?php
include('db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $rule = $_POST['rule'];

    $sql = "INSERT INTO users (name, email, password, rule) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $email, $password, $rule);
    $stmt->execute();
    $stmt->close();

    echo "<div class='success-message'>Registration successful!</div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Register</title>
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
      background-color: rgba(0, 0, 0, 0.5); /* dark overlay */
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

    .success-message {
      position: absolute;
      top: 20px;
      left: 50%;
      transform: translateX(-50%);
      color: green;
      font-size: 1rem;
      background: #e6ffe6;
      padding: 10px 20px;
      border: 1px solid #b2ffb2;
      border-radius: 5px;
      z-index: 1000;
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

<div class="register-container">
  <h2>Register New User</h2>
  <form action="" method="POST">
    <input type="text" name="name" placeholder="Full Name" required><br>
    <input type="email" name="email" placeholder="Email Address" required><br>

    <input type="password" id="password" name="password" placeholder="Password" required><br>

    <div class="toggle-password" onclick="togglePassword()">👁️ Show/Hide Password</div><br>

    <label for="rule">Select Role:</label>
    <select name="rule" id="rule" required>
      <option value="">--Select Role--</option>
      <option value="Admin">Admin</option>
      <option value="User">User</option>
      <option value="Manager">Manager</option>
      <option value="Guest">Guest</option>
    </select><br>

    <input type="submit" value="Register">
  </form>

  <div class="footer">
    <p>Already have an account? <a href="login.php">Login here</a></p>
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
