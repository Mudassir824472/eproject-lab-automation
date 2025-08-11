<?php
session_start();
include '../../db/conn.php';

// redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'];

// stats queries
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_completed = $conn->query("SELECT COUNT(*) AS total FROM testing WHERE status='Completed'")->fetch_assoc()['total'];
$total_pending = $conn->query("SELECT COUNT(*) AS total FROM testing WHERE status='Pending'")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// recent tests
$recent_tests = $conn->query("
    SELECT t.testing_id, p.name AS product_name, t.status, t.created_at
    FROM testing t
    LEFT JOIN products p ON t.product_id = p.product_id
    ORDER BY t.created_at DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f6f9;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            width: calc(100% - 250px);
        }
       
        .info-box {
            display: flex;
            background: #fff;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
        }
        .info-box-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 70px;
            height: 70px;
            font-size: 30px;
            color: white;
            border-radius: 5px;
            margin-right: 15px;
        }
        .info-box-content {
            flex: 1;
        }
        .info-box-text {
            font-size: 14px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .info-box-number {
            font-size: 22px;
            font-weight: bold;
        }
        .progress {
            height: 7px;
            margin-top: 5px;
        }
        .small-box {
            border-radius: 5px;
            position: relative;
            display: block;
            margin-bottom: 20px;
            box-shadow: 0 1px 1px rgba(0,0,0,0.1);
            color: white;
        }
        .small-box > .inner {
            padding: 15px;
        }
        .small-box h3 {
            font-size: 38px;
            font-weight: bold;
            margin: 0 0 10px 0;
            white-space: nowrap;
            padding: 0;
        }
        .small-box p {
            font-size: 15px;
        }
        .small-box .icon {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 70px;
            color: rgba(0,0,0,0.15);
            transition: all .3s linear;
        }
        .small-box:hover .icon {
            font-size: 75px;
        }
        .small-box-footer {
            display: block;
            padding: 8px 15px;
            background: rgba(0,0,0,0.1);
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 0 0 5px 5px;
        }
        .small-box-footer:hover {
            background: rgba(0,0,0,0.15);
        }
        .bg-primary { background-color: #007bff !important; }
        .bg-success { background-color: #28a745 !important; }
        .bg-info { background-color: #17a2b8 !important; }
        .bg-warning { background-color: #ffc107 !important; }
        .bg-danger { background-color: #dc3545 !important; }
        .bg-secondary { background-color: #6c757d !important; }
        .bg-purple { background-color: #6f42c1 !important; }
        .bg-pink { background-color: #e83e8c !important; }
        .bg-teal { background-color: #20c997 !important; }
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 20px;
        }
        
     
       
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Main Content -->
    <div class="main-content">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i> Home</a></li>
            </ol>
        </nav>

        <!-- Info Boxes -->
        <div class="row">
            <div class="col-md-3">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3><?php echo $total_products; ?></h3>
                        <p>Total Products</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <a href="product/view.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3><?php echo $total_completed; ?></h3>
                        <p>Tests Completed</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="testing/view.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3><?php echo $total_pending; ?></h3>
                        <p>Pending Tests</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <a href="testing/view.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-md-3">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3><?php echo $total_users; ?></h3>
                        <p>Users</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="users/view.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </div>

        

       
    </div>
</div>

<script src="assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>