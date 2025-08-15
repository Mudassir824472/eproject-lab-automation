<?php
include('../../../db/conn.php');
$title = "Home Page";
ob_start();
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Product Testing</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Simple Tables</li>
        </ol>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <!-- Alert Messages -->
    <?php if (isset($_GET['msg'])): ?>
      <?php if ($_GET['msg'] == 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <i class="icon fas fa-check"></i> Testing updated successfully!
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php elseif ($_GET['msg'] == 'error'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="icon fas fa-ban"></i> Error updating testing. Please try again.
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Product Testings</h3>

            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                <div class="input-group-append">
                  <button type="submit" class="btn btn-default">
                    <i class="fas fa-search"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
            <table class="table table-hover text-nowrap">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Code</th>
                  <th>Testing Type</th>
                  <th>Result</th>
                  <th>Remarks</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              <?php 
              $products = $conn->query("SELECT * FROM testing AS tng LEFT JOIN products AS pdt ON pdt.product_id = tng.product_id");
              while($product = $products->fetch_assoc()): ?>
                <tr>
                  <td><?php echo $product['product_id']; ?></td>
                  <td><?php echo $product['name']; ?></td>
                  <td><?php echo $product['product_code']; ?></td>
                  <td><?php echo $product['testing_type']; ?></td>
                  <td><?php echo $product['result']; ?></td>
                  <td><?php echo $product['remarks']; ?></td>
                  <td><?php echo $product['status']; ?></td>
                  <td>
                    <a href="edit.php?id=<?php echo $product['testing_id']; ?>" class="btn btn-sm btn-primary">
                      <i class="fas fa-edit"></i> Edit
                    </a>
                  </td>
                </tr>
              <?php endwhile; ?>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Auto-hide alerts -->
<script>
  setTimeout(function(){
    document.querySelectorAll('.alert').forEach(function(alert) {
      alert.classList.remove('show');
    });
  }, 3000);
</script>

<?php
$content = ob_get_clean();
include '../layout/master.php';
?>
