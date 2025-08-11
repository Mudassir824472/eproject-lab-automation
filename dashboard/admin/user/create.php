<?php
include('../../../db/conn.php');
$title = "Add User";
// Start output buffering to capture content
ob_start();
?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>User</h1>
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
    <h3 class="card-title">Add User</h3>
  </div>
  <!-- /.card-header -->

  <!-- form start -->
  <form method="POST" action="../../../db/adduser.php">
    <div class="card-body">
      <div class="form-group">
        <label for="product_id">Username</label>
        <input type="text" name="username" class="form-control" placeholder="Username"/>
      </div>

      <div class="form-group">
        <label for="testing_type">Role Type</label>
        <select name="testing_type" id="testing_type" required class="form-control">
          <option value="">Select Role Type</option>
          <option value="Manufacturer">Manufacturer</option>
          <option value="Tester">Tester</option>
        </select>
      </div>

      <div class="form-group">
        <label for="product_id">Password</label>
        <input type="text" name="password" class="form-control" placeholder="Password"/>
      </div> 

    <!-- /.card-body -->
    <div class="card-footer">
      <button type="submit" name="add_test" class="btn btn-primary">Submit</button>
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
  