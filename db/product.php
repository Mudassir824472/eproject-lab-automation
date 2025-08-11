<?php
include('conn.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {

    // Validate and assign variables safely
    $product_id = $_POST['product_id'] ?? null;
    $product_name = $_POST['product_name'] ?? '';
    $product_code = $_POST['product_code'] ?? '';
    $revision = $_POST['revision'] ?? '';
    $manufacturing_number = $_POST['manufacturing_number'] ?? '';

    // Insert query
    $sql = "INSERT INTO products (product_id, name, product_code, revision, manufacturing_number) 
            VALUES ('$product_id', '$product_name', '$product_code', '$revision', '$manufacturing_number')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['message'] = "Product added successfully!";
        header("Location: ../dashboard/admin/product/create.php");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// edit product


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {

    // Validate and assign variables safely
    $product_id = $_POST['pid'] ?? null;
    $product_name = $_POST['product_name'] ?? '';
    $product_code = $_POST['product_code'] ?? '';
    $revision = $_POST['revision'] ?? '';
    $manufacturing_number = $_POST['manufacturing_number'] ?? '';

    // Insert query
    $sql = "UPDATE products SET 
    `name` = '$product_name', 
    `product_code` = '$product_code', 
    `revision` = '$revision', 
    `manufacturing_number` = '$manufacturing_number'
    WHERE   
    `product_id` = ".$product_id; 

    if ($conn->query($sql) === TRUE) {  
        $_SESSION['message'] = "Product updated successfully!";
        header("Location: ../dashboard/admin/product/edit.php?pid=".$product_id);
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}




?>
