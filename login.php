<?php
session_start();

if (isset($_SESSION['username'])) {
  header("Location: dashboard/admin/index.php");
  exit;
}

include 'db/conn.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $user['role'];
    header("Location: dashboard/admin/index.php");
  } else {
    $error = "Invalid Username or Password!";
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - Lab Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body, html {
      height: 100%;
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .split {
      display: flex;
      height: 100vh;
    }
    .left {
      flex: 1;
      background: url('img/lab-login-bg.jpg') no-repeat center center;
      background-size: cover;
    }
    .right {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #fff;
    }
    .login-box {
      width: 80%;
      max-width: 350px;
    }
    .login-box h2 {
      font-weight: bold;
      margin-bottom: 25px;
      text-align: center;
    }
    .form-control {
      border-radius: 20px;
    }
    .btn-primary {
      border-radius: 20px;
      background-color: #001a66;
      border: none;
    }
    .btn-primary:hover {
      background-color: #000d33;
    }
    .create-account {
      margin-top: 15px;
      text-align: center;
      font-size: 14px;
    }
    .create-account a {
      text-decoration: none;
      font-weight: bold;
    }
  </style>
</head>
<body>

<div class="split">
  <div class="left"></div>
  <div class="right">
    <div class="login-box">
      <div class="text-center mb-4">
        <img src="img/lab logo.png" alt="Logo" height="50">
        <h2>Welcome to lab test</h2>
      </div>

      <?php if(isset($error)): ?>
        <div class="alert alert-danger p-2 text-center">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <form method="POST">
        <div class="mb-3">
          <input type="text" name="username" class="form-control" placeholder="Enter your Username" required>
        </div>
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
        </div>
        <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
      </form>

      <div class="create-account">
        New user? <a href="register.php">create a account</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
