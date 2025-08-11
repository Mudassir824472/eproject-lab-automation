<?php
include('../../../db/conn.php');

$title = "Edit - Product";
ob_start(); 
// Initialize variables
$id = '';
$name = '';
$code = '';
$revision = '';
$manufacturing_number = '';

// Validate `pid` in URL
if (isset($_GET['pid']) && !empty($_GET['pid'])) {
    $pid = $_GET['pid'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->bind_param("s", $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $id = $row['product_id'];
        $name = $row['name'];
        $code = $row['product_code'];
        $revision = $row['revision'];
        $manufacturing_number = $row['manufacturing_number'];
    } else {
        $_SESSION['message'] = "<div class='alert alert-danger'>Product not found!</div>";
        header("Location: product_list.php");
        exit();
    }
    $stmt->close();
} else {
    $_SESSION['message'] = "<div class='alert alert-danger'>Invalid Product ID!</div>";
    header("Location: product_list.php");
    exit();
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Edit Product</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item">Product</li>
          <li class="breadcrumb-item active">Edit</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <!-- left column -->
      <div class="col-md-12">
        <!-- general form elements -->
        <div class="card card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Product</h3>
          </div>

          <?php
          if (isset($_SESSION['message'])) {
              echo $_SESSION['message'];
              unset($_SESSION['message']);
          }
          ?>
          <!-- form start -->
          <form method="POST" action="../../../db/product.php">
            <input type="hidden" value="<?= htmlspecialchars($id) ?>" name="pid"/>
            <div class="card-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="product_id">Product ID (10-digit)</label>
                    <input type="text" class="form-control" id="product_id" name="product_id" 
                           value="<?= htmlspecialchars($id) ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="product_name">Product Name</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" 
                           value="<?= htmlspecialchars($name) ?>" required>
                  </div>

                  <div class="form-group">
                    <label for="product_code">Product Code</label>
                    <input type="text" class="form-control" id="product_code" name="product_code" 
                           value="<?= htmlspecialchars($code) ?>" required>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="form-group">
                    <label for="revision">Revision</label>
                    <input type="text" class="form-control" id="revision" name="revision" 
                           value="<?= htmlspecialchars($revision) ?>">
                  </div>

                  <div class="form-group">
                    <label for="manufacturing_number">Manufacturing Number</label>
                    <input type="text" class="form-control" id="manufacturing_number" name="manufacturing_number" 
                           value="<?= htmlspecialchars($manufacturing_number) ?>" required>
                  </div>
                </div>
              </div>
            </div>

            <div class="card-footer">
              <button type="submit" name="edit_product" class="btn btn-primary">Update Product</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<?php
$content = ob_get_clean();
include '../layout/master.php';
?>
