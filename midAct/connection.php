<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "infomanagementact";

$conn = new mysqli($host, $user, $pass);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($sql) !== TRUE) {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

$sqlUserTable = "CREATE TABLE IF NOT EXISTS user (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(30) NOT NULL,
    lastName VARCHAR(30) NOT NULL,
    contactNumber INT(11) NOT NULL,
    username VARCHAR(15) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
if ($conn->query($sqlUserTable) !== TRUE) {
    die("Error creating user table: " . $conn->error);
}
?>