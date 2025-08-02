<?php
include('../../../db/conn.php');
$title = "User Detail";
// Start output buffering to capture content
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
    <h3 class="card-title">Add Testing</h3>
  </div>
  <!-- /.card-header -->

  <!-- form start -->
  <form method="POST">
    <div class="card-body">
      <div class="form-group">
        <label for="product_id">Product ID</label>
        <?php 
        $products = $conn->query("SELECT product_id FROM products");
        ?>
        <select name="product_id" id="product_id" required class="form-control">
          <option value="">Select Product</option>
          <?php while($product = $products->fetch_assoc()): ?>
            <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_id']; ?></option>
          <?php endwhile; ?>
        </select>
      </div>

      <div class="form-group">
        <label for="testing_type">Testing Type</label>
        <select name="testing_type" id="testing_type" required class="form-control">
          <option value="">Select Testing Type</option>
          <option value="Insulation Resistance">Insulation Resistance</option>
          <option value="Dielectric Strength">Dielectric Strength</option>
          <option value="Temperature Rise">Temperature Rise</option>
          <option value="Short Circuit">Short Circuit</option>
          <option value="Mechanical Endurance">Mechanical Endurance</option>
        </select>
      </div>

      <div class="form-group">
        <label for="result">Result</label>
        <select name="result" id="result" required class="form-control">
          <option value="Pass">Pass</option>
          <option value="Fail">Fail</option>
        </select>
      </div>

      <div class="form-group">
        <label for="status">Status</label>
        <select name="status" id="status" required class="form-control">
          <option value="Pending">Pending</option>
          <option value="Completed">Completed</option>
          <option value="Re-Making">Re-Making</option>
        </select>
      </div>

      <div class="form-group">
        <label for="tester_name">Tester Name</label>
        <input type="text" name="tester_name" id="tester_name" placeholder="Enter tester name" required class="form-control">
      </div>

      <div class="form-group">
        <label for="remarks">Remarks</label>
        <textarea name="remarks" id="remarks" rows="3" placeholder="Enter detailed remarks about the testing..." class="form-control"></textarea>
      </div>
    </div>

    <!-- /.card-body -->
    <div class="card-footer">
      <button type="submit" name="add_test" class="btn btn-primary">Add Test</button>
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

<?php
$content = ob_get_clean(); // Save output to variable

include '../layout/master.php'; // Render layout with variables
?>
  