<?php
include('../../../db/conn.php');
$title = "Product Request";
// Start output buffering to capture content
ob_start();
?>
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Product Request </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product Request</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid"> 
        <!-- /.row -->
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product Request</h3>

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
              <?php
            if(isset($_SESSION['message'])){
              echo $_SESSION['message'];
            }
            ?>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Code</th>
                      <th>Revision</th>
                      <th>Manufacturing Number</th>
                      <th>Date</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>

                  <?php 
                  $products = $conn->query("SELECT * FROM products where status='pending'");
                  ?> 
                  <?php while($product = $products->fetch_assoc()): ?>


                    <tr>
                      <td><?php echo $product['product_id']; ?></td>
                      <td><?php echo $product['name']; ?></td>
                      <td><?php echo $product['product_code']; ?></td>
                      <td><?php echo $product['revision']; ?></td>
                      <td><?php echo $product['manufacturing_number']; ?></td>
                      <td><span class="tag tag-danger"><?php echo $product['created_at']; ?></span></td>
                      <td><span class="tag tag-danger"><?php echo $product['status']; ?></span></td>
                      <td><span class="tag tag-danger"><a href="create.php?pid=<?= $product['product_id'] ?>">Test Product</a></span></td>
                    </tr>


                  <?php endwhile; ?> 
                    
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
         
      </div><!-- /.container-fluid -->
    </section>

<?php
$content = ob_get_clean(); // Save output to variable

include '../layout/master.php'; // Render layout with variables
?>
  