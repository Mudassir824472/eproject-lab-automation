<?php
session_start();
include '../../db/conn.php';

// redirect if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$username = $_SESSION['username'];

// stats queries
$total_products = $conn->query("SELECT COUNT(*) AS total FROM products")->fetch_assoc()['total'];
$total_completed = $conn->query("SELECT COUNT(*) AS total FROM products WHERE status='Completed'")->fetch_assoc()['total'];
$total_pending = $conn->query("SELECT COUNT(*) AS total FROM testing WHERE status='Pending'")->fetch_assoc()['total'];
$total_users = $conn->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];

// product status for chart
$product_status_data = $conn->query("
    SELECT status, COUNT(*) as total
    FROM products
    GROUP BY status
");
$product_status_labels = [];
$product_status_counts = [];
while ($row = $product_status_data->fetch_assoc()) {
    $product_status_labels[] = $row['status'];
    $product_status_counts[] = $row['total'];
}

// testing results for chart
$test_results_data = $conn->query("
    SELECT result, COUNT(*) as total
    FROM testing
    GROUP BY result
");
$test_results_labels = [];
$test_results_counts = [];
while ($row = $test_results_data->fetch_assoc()) {
    $test_results_labels[] = $row['result'];
    $test_results_counts[] = $row['total'];
}

// recent tests
$recent_tests = $conn->query("
    SELECT t.testing_id, p.name AS product_name, t.status, t.created_at
    FROM testing t
    LEFT JOIN products p ON t.product_id = p.product_id
    ORDER BY t.created_at DESC
    LIMIT 5
");

$title = "Dashboard";
ob_start();
?>

<!-- Content Header -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6"><h1>Dashboard</h1></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- Stats Boxes -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php
            $boxes = [
                ["color" => "info", "count" => $total_products, "label" => "Total Products", "icon" => "fas fa-boxes", "link" => "product/index.php"],
                ["color" => "success", "count" => $total_completed, "label" => "Tests Completed", "icon" => "fas fa-check-circle", "link" => "product/index.php"],
                ["color" => "warning", "count" => $total_pending, "label" => "Pending Tests", "icon" => "fas fa-hourglass-half", "link" => "product_testing/product_request.php"],
                ["color" => "danger", "count" => $total_users, "label" => "Users", "icon" => "fas fa-users", "link" => "user/index.php"]
            ];
            foreach ($boxes as $b) {
                echo "
                <div class='col-md-3'>
                    <div class='small-box bg-{$b['color']}'>
                        <div class='inner'>
                            <h3>{$b['count']}</h3>
                            <p>{$b['label']}</p>
                        </div>
                        <div class='icon'><i class='{$b['icon']}'></i></div>
                        <a href='{$b['link']}' class='small-box-footer'>More info <i class='fas fa-arrow-circle-right'></i></a>
                    </div>
                </div>";
            }
            ?>
        </div>

        <!-- Charts -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="productStatusChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="testResultsChart"></canvas>
            </div>
        </div>

        <!-- Recent Tests -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card card-primary">
                    <div class="card-header"><h3 class="card-title">Recent Tests</h3></div>
                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Test ID</th>
                                    <th>Product</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($test = $recent_tests->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $test['testing_id'] ?></td>
                                    <td><?= $test['product_name'] ?></td>
                                    <td><?= $test['status'] ?></td>
                                    <td><?= date("Y-m-d H:i", strtotime($test['created_at'])) ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const productStatusChart = new Chart(document.getElementById('productStatusChart'), {
        type: 'pie',
        data: {
            labels: <?= json_encode($product_status_labels) ?>,
            datasets: [{
                data: <?= json_encode($product_status_counts) ?>,
                backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545']
            }]
        }
    });

    const testResultsChart = new Chart(document.getElementById('testResultsChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($test_results_labels) ?>,
        datasets: [{
            label: 'Test Results',
            data: <?= json_encode($test_results_counts) ?>,
            backgroundColor: <?= json_encode(
                array_map(function($label) {
                    return strtolower($label) === 'fail' ? '#dc3545' : '#007bff';
                }, $test_results_labels)
            ) ?>
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } } }
});

</script>

<?php
$content = ob_get_clean();
include 'layout/dashboard.php';
?>
