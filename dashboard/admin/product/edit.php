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
            <h1>Product</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">General Form</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
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
              <h3 class="card-title">Add Product</h3>
            </div>
            <!-- /.card-header -->

            <!-- form start -->
            <form method="POST">
              <div class="card-body">
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                  <label for="product_id">Product ID (10-digit)</label>
                  <input type="text" class="form-control" id="product_id" name="product_id" placeholder="e.g. PRD0012023" required>
                </div>

                <div class="form-group">
                  <label for="product_code">Product Code</label>
                  <input type="text" class="form-control" id="product_code" name="product_code" placeholder="e.g. SWITCH001" required>
                </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                  <label for="revision">Revision</label>
                  <input type="text" class="form-control" id="revision" name="revision" placeholder="e.g. REV02">
                </div>

                <div class="form-group">
                  <label for="manufacturing_number">Manufacturing Number</label>
                  <input type="text" class="form-control" id="manufacturing_number" name="manufacturing_number" placeholder="e.g. MFG2023001" required>
                </div>
                      </div>
                  </div>
                

                
              </div>
              <!-- /.card-body -->

              <div class="card-footer">
                <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
              </div>
            </form>
          </div>  
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

<?php
$content = ob_get_clean(); // Save output to variable

include '../layout/master.php'; // Render layout with variables
?>
  