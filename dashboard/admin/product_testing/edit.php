<?php
include('../../../db/conn.php');
$title = "Edit Testing";
ob_start();

if (!isset($_GET['id'])) {
    die("ID not provided");
}

$id = intval($_GET['id']);

// Fetch current data from both tables
$sql = "
    SELECT tng.*, pdt.status 
    FROM testing AS tng
    LEFT JOIN products AS pdt ON pdt.product_id = tng.product_id
    WHERE tng.testing_id = $id
";
$result = $conn->query($sql);

if ($result->num_rows === 0) {
    die("Record not found");
}

$testing = $result->fetch_assoc();

if (isset($_POST['update'])) {
    $testing_type = $_POST['testing_type'];
    $result_val   = $_POST['result'];
    $remarks      = $_POST['remarks'];
    $status       = $_POST['status'];

    // Update testing table
    $update_testing_sql = "
        UPDATE testing 
        SET testing_type='$testing_type', result='$result_val', remarks='$remarks'
        WHERE testing_id=$id
    ";

    // Update products table
    $update_product_sql = "
        UPDATE products 
        SET status='$status'
        WHERE product_id = (SELECT product_id FROM testing WHERE testing_id = $id)
    ";

    $ok1 = $conn->query($update_testing_sql);
    $ok2 = $conn->query($update_product_sql);

    if ($ok1 && $ok2) {
        header("Location: index.php?msg=updated");
        exit;
    } else {
        header("Location: index.php?msg=error");
        exit;
    }
}
?>

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Edit Testing</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Edit Testing</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Edit Testing Details</h3>
            </div>
            <form method="POST">
                <div class="card-body">
                    <div class="form-group">
                        <label for="testing_type">Testing Type</label>
                        <input type="text" name="testing_type" id="testing_type" class="form-control" value="<?php echo $testing['testing_type']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="result">Result</label>
                        <select name="result" id="result" class="form-control" required>
                            <option value="Pass" <?php if($testing['result'] == 'Pass') echo 'selected'; ?>>Pass</option>
                            <option value="Fail" <?php if($testing['result'] == 'Fail') echo 'selected'; ?>>Fail</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="remarks">Remarks</label>
                        <textarea name="remarks" id="remarks" class="form-control" rows="3" required><?php echo $testing['remarks']; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="Pending" <?php if($testing['status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            <option value="Completed" <?php if($testing['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                            <option value="Re-Making" <?php if($testing['status'] == 'Re-Making') echo 'selected'; ?>>Re-Making</option>
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" name="update" class="btn btn-primary">Update</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php
$content = ob_get_clean();
include '../layout/master.php';
?>
