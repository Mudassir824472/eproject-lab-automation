<?php
include('conn.php');
session_start();

/**
 * Generate a unique Testing ID, e.g. T0001, T0002, ...
 */
function generateTestingID($conn) {
    $sql = "SELECT testing_id FROM testing ORDER BY testing_id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $lastID = intval(substr($row['testing_id'], 1)); // remove first letter
        $newID = $lastID + 1;
    } else {
        $newID = 1;
    }

    return 'T' . str_pad($newID, 4, '0', STR_PAD_LEFT);  // Example -> T0001
}

if (isset($_POST['add_test'])) {

    // Add new test
    $testing_id   = generateTestingID($conn);
    $product_id   = $_POST['product_id'];
    $testing_type = $_POST['testing_type'];
    $result       = $_POST['result'];
    $remarks      = $_POST['remarks'];
    $status       = $_POST['status'];
    $tester_name  = $_POST['tester_name'];

    $sql = "INSERT INTO testing (testing_id, product_id, testing_type, result, remarks, status, tester_name) 
            VALUES ('$testing_id', '$product_id', '$testing_type', '$result', '$remarks', '$status', '$tester_name')
            ON DUPLICATE KEY UPDATE testing_id = VALUES(testing_id)";

    if ($conn->query($sql) === TRUE) {
        header("Location: ../dashboard/admin/product_testing/create.php");
        // Update product status if test failed and status is Re-Making
        if ($result == 'Fail' && $status == 'Re-Making') {
            $update_sql = "UPDATE products SET status = 'Re-Making' WHERE product_id = '$product_id'";
            $conn->query($update_sql);
        }
        $message = "Test added successfully! Testing ID: $testing_id";

    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }
}

?>
