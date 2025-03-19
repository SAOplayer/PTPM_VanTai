<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$database = "Test1"; 

// Kết nối đến MySQL
$conn = new mysqli($servername, $username, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>