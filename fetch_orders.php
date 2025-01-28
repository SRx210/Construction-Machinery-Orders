<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: sign_in.html");
    exit();
}

$user_id = $_SESSION['user_id'];

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ConstructionDB";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle delete request
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_sql = "DELETE FROM Machinery WHERE id = $delete_id";
    mysqli_query($conn, $delete_sql);
    header("Location: fetch_orders.php"); // Refresh the page
    exit();
}

// Handle update request
if (isset($_POST['save_changes'])) {
    $update_id = $_POST['update_id'];
    $machinery_name = $_POST['machinery_name'];
    $type = $_POST['type'];
    $model = $_POST['model'];
    $manufacturer = $_POST['manufacturer'];
    $quantity = $_POST['quantity'];
    $condition = $_POST['condition'];
    $description = $_POST['description'];
    $customer_name = $_POST['customer_name'];
    $customer_email = $_POST['customer_email'];

    $update_sql = "UPDATE Machinery SET 
                    machinery_name='$machinery_name',
                    type='$type',
                    model='$model',
                    manufacturer='$manufacturer',
                    quantity='$quantity',
                    `condition`='$condition',
                    description='$description',
                    customer_name='$customer_name',
                    customer_email='$customer_email'
                   WHERE id = $update_id";
    mysqli_query($conn, $update_sql);
    header("Location: fetch_orders.php"); // Refresh the page
    exit();
}

// Search functionality
$search_id = isset($_GET['search_id']) ? $_GET['search_id'] : '';
$sql = "SELECT id, machinery_name, type, model, manufacturer, quantity, `condition`, description, customer_name, customer_email 
        FROM Machinery 
        WHERE user_id = $user_id";

if ($search_id !== '') {
    $sql .= " AND id = $search_id";
}

$result = mysqli_query($conn, $sql);

// Check if update button was clicked to load data in form
if (isset($_GET['update_id'])) {
    $update_id = $_GET['update_id'];
    $update_sql = "SELECT * FROM Machinery WHERE id = $update_id";
    $update_result = mysqli_query($conn, $update_sql);
    $update_row = mysqli_fetch_assoc($update_result);
}

// Start outputting HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f3f4f7;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        margin: 0;
        padding: 20px;
    }
    .orders-container {
        width: 90%;
        max-width: 1200px;
        max-height: 90vh;
        overflow-y: auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px gray;
    }
    .orders-container h2 {
        text-align: center;
        color: #34495e;
        margin-bottom: 20px;
    }
    .search-box {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }
    .search-box input {
        padding: 6px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin-right: 10px;
    }
    .search-box button {
        padding: 6px 10px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    .search-box button:hover {
        background-color: #2980b9;
    }
    .order-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
        table-layout: fixed;
    }
    .order-table th, .order-table td {
        border: 1px solid #bdc3c7;
        padding: 8px;
        font-size: 0.9em;
        word-wrap: break-word;
    }
    .order-table th {
        background-color: #3498db;
        color: white;
    }
    .order-table td {
        background-color: #ecf0f1;
        color: #2c3e50;
        text-align: center;
    }
    .no-orders {
        text-align: center;
        color: gray;
        font-size: 16px;
        margin-top: 20px;
    }
    .button {
        background-color: #e67e22;
        color: white;
        padding: 5px 8px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.9em;
		display: block;
        margin-bottom: 10px;
    }
    .button:hover {
        background-color: #d35400;
    }
    .home-button {
        margin-top: 20px;
        display: block;
        text-align: center;
    }
    .form-container {
        margin-top: 20px;
        background-color: #f9f9f9;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0px 0px 10px gray;
    }
    .form-container input, .form-container textarea, .form-container select {
        width: 100%;
        padding: 8px;
        margin: 6px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
			
    }
	
	
</style>

</head>
<body>

    <div class="orders-container">
        <h2>My Orders</h2>

        <!-- Search box -->
        <div class="search-box">
            <form method="get">
                <input type="number" name="search_id" placeholder="Enter Order ID" value="<?php echo $search_id; ?>">
                <button type="submit">Search</button>
            </form>
        </div>

        <table class="order-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer Name</th>
                    <th>Customer Email</th>
                    <th>Machinery Name</th>
                    <th>Type</th>
                    <th>Model</th>
                    <th>Manufacturer</th>
                    <th>Quantity</th>
                    <th>Condition</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Output data for each row
                while ($row = mysqli_fetch_assoc($result)) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['customer_name']}</td>
                            <td>{$row['customer_email']}</td>
                            <td>{$row['machinery_name']}</td>
                            <td>{$row['type']}</td>
                            <td>{$row['model']}</td>
                            <td>{$row['manufacturer']}</td>
                            <td>{$row['quantity']}</td>
                            <td>{$row['condition']}</td>
                            <td>{$row['description']}</td>
                            <td>
                                <a href='fetch_orders.php?update_id={$row['id']}' class='button'>Update</a>
                                <a href='fetch_orders.php?delete_id={$row['id']}' class='button' onclick='return confirm(\"Are you sure you want to delete this record?\");'>Delete</a>
                            </td>
                          </tr>";
                }

                // If no results found, display a message
                if (mysqli_num_rows($result) === 0) {
                    echo "<tr><td colspan='11' class='no-orders'>No machinery added to your orders yet.</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>

        <!-- Update Form -->
        <?php if (isset($update_row)) : ?>
            <div class="form-container">
                <h3>Update Order</h3>
                <form method="post">
                    <input type="hidden" name="update_id" value="<?php echo $update_row['id']; ?>">
                    <label>Customer Name</label>
                    <input type="text" name="customer_name" value="<?php echo $update_row['customer_name']; ?>" required>
                    <label>Customer Email</label>
                    <input type="email" name="customer_email" value="<?php echo $update_row['customer_email']; ?>" required>
                    <label for="machinery-name">Machinery Name:</label>
                    <select id="machinery-name" name="machinery_name" required>
                        <option value="">Select Machinery</option>
                        <option value="excavator" <?php echo ($update_row['machinery_name'] == 'excavator') ? 'selected' : ''; ?>>Excavator</option>
                        <option value="bulldozer" <?php echo ($update_row['machinery_name'] == 'bulldozer') ? 'selected' : ''; ?>>Bulldozer</option>
                        <option value="crane" <?php echo ($update_row['machinery_name'] == 'crane') ? 'selected' : ''; ?>>Crane</option>
                        <option value="dump_truck" <?php echo ($update_row['machinery_name'] == 'dump_truck') ? 'selected' : ''; ?>>Dump Truck</option>
                    </select>
                    
                    <label>Type</label>
                    <input type="text" name="type" value="<?php echo $update_row['type']; ?>" required>

                    <label>Model</label>
                    <input type="text" name="model" value="<?php echo $update_row['model']; ?>" required>

                    <label>Manufacturer</label>
                    <input type="text" name="manufacturer" value="<?php echo $update_row['manufacturer']; ?>" required>

                    <label>Quantity</label>
                    <input type="number" name="quantity" value="<?php echo $update_row['quantity']; ?>" required>

                    <label>Condition</label>
                    <input type="text" name="condition" value="<?php echo $update_row['condition']; ?>" required>

                    <label>Description</label>
                    <textarea name="description" required><?php echo $update_row['description']; ?></textarea>

                    <button type="submit" name="save_changes" class="button">Save Changes</button>
                </form>
            </div>
        <?php endif; ?>

        <!-- Home Button -->
        <div class="home-button">
            <a href="home_page.html" class="button">Home</a>
        </div>
    </div>

</body>
</html>

