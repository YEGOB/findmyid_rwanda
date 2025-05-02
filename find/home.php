<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to Find ID</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background: url('cre.jpg') no-repeat center center fixed;
      background-size: cover;
      margin: 0;
      height: 100vh;
      position: relative;
    }

    /* Overlay */
    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Adjust this value to control darkness */
      z-index: 0;
    }

    .container {
      position: relative;
      z-index: 1;
      background: rgba(255, 255, 255, 0.9);
      padding: 40px;
      border-radius: 15px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
      text-align: center;
      max-width: 450px;
      width: 90%;
      margin: auto;
      top: 50%;
      transform: translateY(-50%);
    }

    .container h2 {
      font-size: 2.5rem;
      color: #333;
      margin-bottom: 30px;
    }

    .button-container a {
      display: inline-block;
      background-color: #ff7b7b;
      color: white;
      padding: 12px 24px;
      font-size: 1.2rem;
      margin: 10px;
      border-radius: 8px;
      text-decoration: none;
      transition: 0.3s ease;
    }

    .button-container a:hover {
      background-color: #ff5c5c;
    }
  </style>
</head>
<body>

<div class="container">
  <h2>Welcome to Find ID</h2>
  <div class="button-container">
    <a href="../login.php">Login</a>
    <a href="../register.php">Register</a>
  </div>
</div>

</body>
</html>
