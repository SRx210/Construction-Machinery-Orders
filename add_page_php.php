<?php
session_start();

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ConstructionDB";

// Create connection
$conn = mysqli_connect($servername, $username, $password);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if it doesn't exist
$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select the database
mysqli_select_db($conn, $dbname);

// Create Machinery table with user_id column if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS Machinery (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL, /* Foreign key to Users table */
    machinery_name VARCHAR(50) NOT NULL,
    type VARCHAR(20) NOT NULL,
    model VARCHAR(20) NOT NULL,
    manufacturer VARCHAR(50) NOT NULL,
    quantity INT(11) NOT NULL,
    `condition` VARCHAR(20) NOT NULL,
    description TEXT NOT NULL,
    customer_name VARCHAR(50) NOT NULL,
    customer_email VARCHAR(100) NOT NULL
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'Machinery' created successfully<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("You must be logged in to add machinery.");
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $update_id = $_POST['update_id'] ?? null;
    $user_id = $_SESSION['user_id'];
    $machinery_name = $_POST["machinery-name"];
    $type = $_POST["type"];
    $model = $_POST["model"];
    $manufacturer = $_POST["manufacturer"];
    $quantity = $_POST["quantity"];
    $condition = $_POST["condition"];
    $description = $_POST["description"];
    $customer_name = $_POST["customer_name"];
    $customer_email = $_POST["customer_email"];

    if ($update_id) {
        // Update existing entry with the associated user_id
        $sql = "UPDATE Machinery SET machinery_name='$machinery_name', type='$type', model='$model', 
                manufacturer='$manufacturer', quantity=$quantity, `condition`='$condition', 
                description='$description', customer_name='$customer_name', customer_email='$customer_email' 
                WHERE id=$update_id AND user_id=$user_id";
    } else {
        // Insert new entry with user_id
        $sql = "INSERT INTO Machinery (user_id, machinery_name, type, model, manufacturer, quantity, `condition`, 
                description, customer_name, customer_email) 
                VALUES ('$user_id', '$machinery_name', '$type', '$model', '$manufacturer', $quantity, '$condition', 
                '$description', '$customer_name', '$customer_email')";
    }

    if (mysqli_query($conn, $sql)) {
        echo "Operation completed successfully!";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

// Close connection
mysqli_close($conn);
?>
