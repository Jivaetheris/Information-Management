<?php
$host = "localhost"; 
$username = "root"; 
$password = "";
$dbname = "company"; 

try {
    $conn = new mysqli($host, $username, $password, $dbname);

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    die($e->getMessage()); 
}
?>
