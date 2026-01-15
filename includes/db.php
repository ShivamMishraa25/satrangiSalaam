<?php
// $servername = "sdb-78.hosting.stackcp.net";  // Replace with your MySQL server host if needed
// $username = "satrangiMember";         // Replace with your MySQL username
// $password = "SSmode#22@Trust";             // Replace with your MySQL password
// $dbname = "satrangi_salaam-35303737e6fa"; // Database name

$servername = "localhost";  // Replace with your MySQL server host if needed sdb-78.hosting.stackcp.net
$username = "root";         // Replace with your MySQL username satrangi_salaam-35303737e6fa
$password = "";             // Replace with your MySQL password pi6b1z34ne
$dbname = "satrangi_salaam"; // Database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>