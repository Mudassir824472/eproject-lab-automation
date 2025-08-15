<?php
include('conn.php'); // DB connection

$title = "Add User";

if (isset($_POST['add_test'])) {
    $username = $_POST['username'];
    $role = $_POST['testing_type'];
    $password = md5($_POST['password']); // Hash the password before storing

    // Simple insert query
    $sql = "INSERT INTO users (username, role, password) 
            VALUES ('$username', '$role', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "<p style='color:green;'>User added successfully!</p>";
    } else {
        echo "<p style='color:red;'>Error: " . $conn->error . "</p>";
    }
}
?>
