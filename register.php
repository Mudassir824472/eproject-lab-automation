<?php
session_start();
include 'db/conn.php'; // expects $conn = new mysqli(...)

// Handle registration submit
if (isset($_POST['register'])) {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $confirm_password = $_POST['confirm_password'] ?? '';
  $role = $_POST['role'] ?? '';

  if ($username === '' || $password === '' || $confirm_password === '' || $role === '') {
    $error = "All fields are required.";
  } elseif ($password !== $confirm_password) {
    $error = "Passwords do not match.";
  } else {
    // Check if username already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? LIMIT 1");
    if ($stmt) {
      $stmt->bind_param("s", $username);
      $stmt->execute();
      $result = $stmt->get_result();
      
      if ($result && $result->num_rows > 0) {
        $error = "Username already exists.";
      } else {
        // Hash password
        $hashed_password = md5($password);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        if ($stmt) {
          $stmt->bind_param("sss", $username, $hashed_password, $role);
          if ($stmt->execute()) {
            // Registration successful → go to login.php
            header("Location: login.php?registered=1");
            exit;
          } else {
            $error = "Error occurred while saving user. Please try again.";
          }
        } else {
          $error = "Server error. Please try again later.";
        }
      }
      $stmt->close();
    } else {
      $error = "Server error. Please try again later.";
    }
  }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register - Lab Test</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
          <h4>Register for Labsky</h4>
        </div>
        <form method="POST" action="">
          <div class="card-body">

            <?php if(isset($error)): ?>
              <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Username -->
            <div class="form-group mb-3">
              <label for="username">Username</label>
              <input type="text" name="username" id="username" class="form-control" placeholder="Username" required>
            </div>

            <!-- Role Type -->
            <div class="form-group mb-3">
              <label for="role">Role Type</label>
              <select name="role" id="role" required class="form-control">
                <option value="">Select Role Type</option>
                <option value="manufacturer">Manufacturer</option>
                <option value="tester">Tester</option>
              </select>
            </div>

            <!-- Password -->
            <div class="form-group mb-3">
              <label for="password">Password</label>
              <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
            </div> 

            <!-- Confirm Password -->
            <div class="form-group mb-3">
              <label for="confirm_password">Confirm Password</label>
              <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm Password" required>
            </div> 

          </div>
          <div class="card-footer text-center">
            <button type="submit" name="register" class="btn btn-primary">Register</button>
          </div>
        </form>
      </div>
      <div class="text-center mt-3">
        Already have an account? <a href="login.php">Login here</a>
      </div>
    </div>
  </div>
</div>

</body>
</html>
