<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database_name = 'ngo';

// Create connection using mysqli
$conn = mysqli_connect($server, $username, $password, $database_name);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($conn, "utf8");

// Also create $con for backward compatibility with existing code
$con = $conn;
?>