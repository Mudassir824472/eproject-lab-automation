<?php
session_start();

if(isset($_SESSION['username'])) {
  header("Location: dashboard/admin/product/index.php");
  exit;
}

include 'db/conn.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $user = $result->fetch_assoc();  // fetch full row
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $user['role'];
    header("Location: dashboard/admin/product/index.php");
  } else {
    $error = "Invalid Username or Password!";
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
</head>
<body>
  <h2>Login Form</h2>
  <?php if(isset($error)){ echo "<p style='color:red;'>$error</p>"; } ?>
  <form method="POST" action="">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit" name="login">Login</button>
  </form>
</body>
</html>
