<?php
$conn = mysqli_connect("localhost", "root", "");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "Connection to server successful.<br>";
}

$db_query = "CREATE DATABASE IF NOT EXISTS ConstructionDB";
if (mysqli_query($conn, $db_query)) {
    echo "Database 'ConstructionDB' created successfully.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn);
}

mysqli_select_db($conn, "ConstructionDB");

$table_query = "CREATE TABLE IF NOT EXISTS Users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    firstname VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    gender VARCHAR(10),
    interests VARCHAR(100),
    country VARCHAR(30),
    bio TEXT,
    favcolor VARCHAR(7),
    dob DATE,
    appointment DATETIME,
    email VARCHAR(50),
    resume VARCHAR(100),
    age INT,
    uname VARCHAR(30),
    password VARCHAR(255)
)";

if (mysqli_query($conn, $table_query)) {
    echo "Table 'Users' created successfully.<br>";
} else {
    die("Error creating table: " . mysqli_error($conn));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $gender = $_POST["gender"];
    $interests = isset($_POST["interests"]) ? (is_array($_POST["interests"]) ? implode(",", $_POST["interests"]) : $_POST["interests"]) : '';
    $country = $_POST["country"];
    $bio = $_POST["bio"];
    $favcolor = $_POST["favcolor"];
    $dob = $_POST["dob"];
    $appointment = $_POST["appointment"];
    $email = $_POST["email"];
    $resume = $_POST["resume"];
    $age = $_POST["age"];
    $uname = $_POST["uname"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $insert_query = "INSERT INTO Users (firstname, lastname, gender, interests, country, bio, favcolor, dob, appointment, email, resume, age, uname, password) 
                     VALUES ('$firstname', '$lastname', '$gender', '$interests', '$country', '$bio', '$favcolor', '$dob', '$appointment', '$email', '$resume', '$age', '$uname', '$password')";

    if (mysqli_query($conn, $insert_query)) {
        echo "New record created successfully.<br>";
    } else {
        echo "Error inserting data: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>
