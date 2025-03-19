<?php
// Database credentials
$host = 'localhost';  // Your database host, usually 'localhost'
$username = 'root';   // Your database username
$password = '';       // Your database password (if any)
$dbname = 'listing-platform'; // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// echo "Connected successfully";

// Close connection
//$conn->close();
?>
