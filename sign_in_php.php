<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "ConstructionDB");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["uname"];
    $password = $_POST["pwd"];

    $query = "SELECT id, password FROM Users WHERE uname = '$username'";
    $result = mysqli_query($conn, $query);

    if ($result && $row = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $row["password"])) {
            $_SESSION['user_id'] = $row['id'];
            header("Location: home_page.html");
            exit();
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Invalid username or password.";
    }

    mysqli_close($conn);
}
?>
