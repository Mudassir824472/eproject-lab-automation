<?php
include('../../../db/conn.php');
$title = "Home Page";
// Start output buffering to capture content
ob_start();
?>
    
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User </h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Users</li>
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
                <h3 class="card-title">Users</h3>

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
                      <th>Username</th>
                      <th>Role</th> 
                      <th>Date</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php 
                  $products = $conn->query("SELECT * FROM users");
                  ?> 
                  <?php while($product = $products->fetch_assoc()): ?>


                    <tr>
                      <td><?php echo $product['id']; ?></td>
                      <td><?php echo $product['username']; ?></td>
                      <td><?php echo $product['role']; ?></td>
                      <td><?php echo $product['created_at']; ?></td>
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
  