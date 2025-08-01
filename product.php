<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SRS Electrical Appliances Testing System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .status-pending { background-color: #FEF9C3; color: #B45309; }
        .status-completed { background-color: #DCFCE7; color: #15803D; }
        .status-remaking { background-color: #FEE2E2; color: #B91C1C; }
        .result-pass { background-color: #BBF7D0; color: #15803D; }
        .result-fail { background-color: #FECACA; color: #B91C1C; }
    </style>
</head>
<body class="bg-gray-100">
    <?php
    // Database configuration
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "eproject";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Generate testing ID (12 digit)
    function generateTestingID($conn) {
        $prefix = "TST";
        $year = date("y");
        $month = date("m");
        $day = date("d");
        
        // Get highest existing ID
        $sql = "SELECT MAX(testing_id) as max_id FROM testing";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        
        if ($row['max_id']) {
            $last_id = $row['max_id'];
            $last_seq = intval(substr($last_id, -4));
            $seq = $last_seq + 1;
        } else {
            $seq = 1;
        }
        
        $number = str_pad($seq, 4, "0", STR_PAD_LEFT);
        return $prefix . $year . $month . $day . $number;
    }

    // Handle form submissions
    $message = "";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['add_product'])) {
            // Add new product
            $product_id = $_POST['product_id'];
            $product_code = $_POST['product_code'];
            $revision = $_POST['revision'];
            $manufacturing_number = $_POST['manufacturing_number'];

            $sql = "INSERT INTO products (product_id, product_code, revision, manufacturing_number) 
                    VALUES ('$product_id', '$product_code', '$revision', '$manufacturing_number')";

            if ($conn->query($sql) === TRUE) {
                $message = "Product added successfully!";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        elseif (isset($_POST['add_test'])) {
            // Add new test
            $testing_id = generateTestingID($conn);
            $product_id = $_POST['product_id'];
            $testing_type = $_POST['testing_type'];
            $result = $_POST['result'];
            $remarks = $_POST['remarks'];
            $status = $_POST['status'];
            $tester_name = $_POST['tester_name'];

            $sql = "INSERT INTO testing (testing_id, product_id, testing_type, result, remarks, status, tester_name) 
                    VALUES ('$testing_id', '$product_id', '$testing_type', '$result', '$remarks', '$status', '$tester_name')
                    ON DUPLICATE KEY UPDATE testing_id = VALUES(testing_id)";

            if ($conn->query($sql) === TRUE) {
                // Update product status if test failed and status is Re-Making
                if ($result == 'Fail' && $status == 'Re-Making') {
                    $update_sql = "UPDATE products SET status = 'Re-Making' WHERE product_id = '$product_id'";
                    $conn->query($update_sql);
                }
                $message = "Test added successfully! Testing ID: $testing_id";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
        elseif (isset($_POST['update_status'])) {
            // Update test status
            $testing_id = $_POST['testing_id'];
            $status = $_POST['status'];

            $sql = "UPDATE testing SET status='$status' WHERE testing_id='$testing_id'";

            if ($conn->query($sql) === TRUE) {
                $message = "Status updated successfully!";
            } else {
                $message = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Search functionality
    $search_results = [];
    if (isset($_GET['search'])) {
        $search_term = $_GET['search_term'];
        $search_type = $_GET['search_type'];
        
        $sql = "";
        if ($search_type == "product_id") {
            $sql = "SELECT p.product_id, p.product_code, p.revision, p.manufacturing_number, 
                   t.testing_id, t.testing_type, t.result, t.remarks, t.status, t.tester_name, t.created_at
                   FROM products p 
                   LEFT JOIN testing t ON p.product_id = t.product_id
                   WHERE p.product_id LIKE '%$search_term%'";
        } elseif ($search_type == "testing_id") {
            $sql = "SELECT p.product_id, p.product_code, p.revision, p.manufacturing_number, 
                   t.testing_id, t.testing_type, t.result, t.remarks, t.status, t.tester_name, t.created_at
                   FROM products p 
                   JOIN testing t ON p.product_id = t.product_id
                   WHERE t.testing_id LIKE '%$search_term%'";
        } elseif ($search_type == "status") {
            $sql = "SELECT p.product_id, p.product_code, p.revision, p.manufacturing_number, 
                   t.testing_id, t.testing_type, t.result, t.remarks, t.status, t.tester_name, t.created_at
                   FROM products p 
                   JOIN testing t ON p.product_id = t.product_id
                   WHERE t.status = '$search_term'";
        }

        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $search_results[] = $row;
            }
        }
    }
    ?>
    
    <div class="container mx-auto p-6">
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-4 text-center">SRS Electrical Appliances Testing System</h1>
            
            <!-- Display message if exists -->
            <?php if ($message): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-6">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>
            
            <!-- Search Form -->
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">Search Tests</h2>
                <form method="GET" class="flex gap-4">
                    <select name="search_type" class="border border-gray-300 p-2 rounded">
                        <option value="product_id">Product ID</option>
                        <option value="testing_id">Testing ID</option>
                        <option value="status">Status</option>
                    </select>
                    <input type="text" name="search_term" placeholder="Enter search term" class="border border-gray-300 p-2 rounded flex-grow">
                    <button type="submit" name="search" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Search</button>
                </form>
            </div>

            <!-- Search Results -->
            <?php if (!empty($search_results)): ?>
                <div class="overflow-x-auto mb-8">
                    <h2 class="text-xl font-semibold mb-4">Search Results</h2>
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="py-2 px-4 border">Product ID</th>
                                <th class="py-2 px-4 border">Product Code</th>
                                <th class="py-2 px-4 border">Testing ID</th>
                                <th class="py-2 px-4 border">Testing Type</th>
                                <th class="py-2 px-4 border">Result</th>
                                <th class="py-2 px-4 border">Status</th>
                                <th class="py-2 px-4 border">Tester</th>
                                <th class="py-2 px-4 border">Date</th>
                                <th class="py-2 px-4 border">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($search_results as $row): ?>
                                <tr>
                                    <td class="py-2 px-4 border"><?php echo $row['product_id']; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $row['product_code']; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $row['testing_id']; ?></td>
                                    <td class="py-2 px-4 border"><?php echo $row['testing_type']; ?></td>
                                    <td class="py-2 px-4 border"><span class="px-2 py-1 rounded-full <?php echo 'result-' . strtolower($row['result']); ?>"><?php echo $row['result']; ?></span></td>
                                    <td class="py-2 px-4 border"><span class="px-2 py-1 rounded-full <?php echo 'status-' . strtolower(str_replace(' ', '-', $row['status'])); ?>"><?php echo $row['status']; ?></span></td>
                                    <td class="py-2 px-4 border"><?php echo $row['tester_name']; ?></td>
                                    <td class="py-2 px-4 border"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                    <td class="py-2 px-4 border">
                                        <form method="POST">
                                            <input type="hidden" name="testing_id" value="<?php echo $row['testing_id']; ?>">
                                            <select name="status" class="border border-gray-300 p-1 rounded text-sm">
                                                <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="Re-Making" <?php echo $row['status'] == 'Re-Making' ? 'selected' : ''; ?>>Re-Making</option>
                                            </select>
                                            <button type="submit" name="update_status" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">Update</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php if (!empty($row['remarks'])): ?>
                                    <tr>
                                        <td colspan="7" class="py-2 px-4 border bg-gray-50">
                                            <strong>Remarks:</strong> <?php echo $row['remarks']; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php elseif (isset($_GET['search'])): ?>
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-6">
                    No results found for your search criteria.
                </div>
            <?php endif; ?>

            <!-- Tabs -->
            <div class="flex border-b mb-6">
                <button class="tab-button py-2 px-4 font-medium border-b-2 border-blue-500 text-blue-600" data-tab="add-product">Add Product</button>
                <button class="tab-button py-2 px-4 font-medium text-gray-500 hover:text-gray-700" data-tab="add-test">Add Test</button>
                <button class="tab-button py-2 px-4 font-medium text-gray-500 hover:text-gray-700" data-tab="view-tests">View All Tests</button>
            </div>

            <!-- Add Product Form -->
            <div id="add-product" class="tab-content mb-8">
                <h2 class="text-xl font-semibold mb-4">Add New Product</h2>
                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Product ID (10-digit)</label>
                            <input type="text" name="product_id" placeholder="e.g. PRD0012023" required class="border border-gray-300 p-2 rounded w-full">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Product Code</label>
                            <input type="text" name="product_code" placeholder="e.g. SWITCH001" required class="border border-gray-300 p-2 rounded w-full">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Revision</label>
                            <input type="text" name="revision" placeholder="e.g. REV02" class="border border-gray-300 p-2 rounded w-full">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Manufacturing Number</label>
                            <input type="text" name="manufacturing_number" placeholder="e.g. MFG2023001" required class="border border-gray-300 p-2 rounded w-full">
                        </div>
                    </div>
                    <button type="submit" name="add_product" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Add Product</button>
                </form>
            </div>

            <!-- Add Test Form -->
            <div id="add-test" class="tab-content hidden mb-8">
                <h2 class="text-xl font-semibold mb-4">Add New Test</h2>
                <form method="POST">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Product ID</label>
                            <?php 
                            $products = $conn->query("SELECT product_id FROM products");
                            ?>
                            <select name="product_id" required class="border border-gray-300 p-2 rounded w-full">
                                <option value="">Select Product</option>
                                <?php while($product = $products->fetch_assoc()): ?>
                                    <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_id']; ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Testing Type</label>
                            <select name="testing_type" required class="border border-gray-300 p-2 rounded w-full">
                                <option value="">Select Testing Type</option>
                                <option value="Insulation Resistance">Insulation Resistance</option>
                                <option value="Dielectric Strength">Dielectric Strength</option>
                                <option value="Temperature Rise">Temperature Rise</option>
                                <option value="Short Circuit">Short Circuit</option>
                                <option value="Mechanical Endurance">Mechanical Endurance</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Result</label>
                            <select name="result" required class="border border-gray-300 p-2 rounded w-full">
                                <option value="Pass">Pass</option>
                                <option value="Fail">Fail</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Status</label>
                            <select name="status" required class="border border-gray-300 p-2 rounded w-full">
                                <option value="Pending">Pending</option>
                                <option value="Completed">Completed</option>
                                <option value="Re-Making">Re-Making</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Tester Name</label>
                            <input type="text" name="tester_name" placeholder="Enter tester name" required class="border border-gray-300 p-2 rounded w-full">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-gray-700 mb-2">Remarks</label>
                            <textarea name="remarks" rows="3" placeholder="Enter detailed remarks about the testing..." class="border border-gray-300 p-2 rounded w-full"></textarea>
                        </div>
                    </div>
                    <button type="submit" name="add_test" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">Add Test</button>
                </form>
            </div>

            <!-- View All Tests -->
            <div id="view-tests" class="tab-content hidden mb-8">
                <h2 class="text-xl font-semibold mb-4">All Tests</h2>
                <?php
                $sql = "SELECT p.product_id, p.product_code, p.revision, p.manufacturing_number, 
                       t.testing_id, t.testing_type, t.result, t.remarks, t.status, t.tester_name, t.created_at
                       FROM products p 
                       JOIN testing t ON p.product_id = t.product_id
                       ORDER BY t.created_at DESC LIMIT 50";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-200">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="py-2 px-4 border">Product ID</th>
                                    <th class="py-2 px-4 border">Testing ID</th>
                                    <th class="py-2 px-4 border">Testing Type</th>
                                    <th class="py-2 px-4 border">Result</th>
                                    <th class="py-2 px-4 border">Status</th>
                                    <th class="py-2 px-4 border">Tester</th>
                                    <th class="py-2 px-4 border">Date</th>
                                    <th class="py-2 px-4 border">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td class="py-2 px-4 border"><?php echo $row['product_id']; ?></td>
                                        <td class="py-2 px-4 border"><?php echo $row['testing_id']; ?></td>
                                        <td class="py-2 px-4 border"><?php echo $row['testing_type']; ?></td>
                                        <td class="py-2 px-4 border"><span class="px-2 py-1 rounded-full <?php echo 'result-' . strtolower($row['result']); ?>"><?php echo $row['result']; ?></span></td>
                                        <td class="py-2 px-4 border"><span class="px-2 py-1 rounded-full <?php echo 'status-' . strtolower(str_replace(' ', '-', $row['status'])); ?>"><?php echo $row['status']; ?></span></td>
                                        <td class="py-2 px-4 border"><?php echo $row['tester_name']; ?></td>
                                        <td class="py-2 px-4 border"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
                                        <td class="py-2 px-4 border">
                                            <form method="POST">
                                                <input type="hidden" name="testing_id" value="<?php echo $row['testing_id']; ?>">
                                                <select name="status" class="border border-gray-300 p-1 rounded text-sm">
                                                    <option value="Pending" <?php echo $row['status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                    <option value="Completed" <?php echo $row['status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                    <option value="Re-Making" <?php echo $row['status'] == 'Re-Making' ? 'selected' : ''; ?>>Re-Making</option>
                                                </select>
                                                <button type="submit" name="update_status" class="bg-yellow-500 hover:bg-yellow-600 text-white px-2 py-1 rounded text-sm">Update</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php if (!empty($row['remarks'])): ?>
                                        <tr>
                                            <td colspan="8" class="py-2 px-4 border bg-gray-50">
                                                <strong>Remarks:</strong> <?php echo $row['remarks']; ?>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative">
                        No tests found in the database.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(content => {
                    content.classList.add('hidden');
                });
                
                // Remove active state from all buttons
                document.querySelectorAll('.tab-button').forEach(btn => {
                    btn.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600');
                    btn.classList.add('text-gray-500', 'hover:text-gray-700');
                });
                
                // Show selected tab content
                const tabId = button.getAttribute('data-tab');
                document.getElementById(tabId).classList.remove('hidden');
                
                // Set active state on clicked button
                button.classList.remove('text-gray-500', 'hover:text-gray-700');
                button.classList.add('border-b-2', 'border-blue-500', 'text-blue-600');
            });
        });
    </script>
</body>
</html>