<?php
session_start();
include 'db.php';

if (isset($_POST['login'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']);

  $sql = "SELECT * FROM users WHERE username='$username' AND password='$password'";
  $result = $conn->query($sql);

  if ($result->num_rows == 1) {
    $_SESSION['username'] = $username;
    header("Location: index.php");
  } else {
    $error = "Invalid Username or Password!";
  }
}
?>