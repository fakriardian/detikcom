<?php
include "./src/utils/autoload.php";

$servername = env('DB_HOST');
$username = env('DB_USERNAME');
$password = env('DB_PASSWORD');
$dbName = env('DB_NAME');

// Create connection
try {
    $conn = new PDO("mysql:host=$servername", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "CREATE DATABASE $dbName";
    // use exec() because no results are returned
    $conn->exec($sql);
    echo "Database created successfully!";
} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
