<?php
// Database connection credentials
$host = 'localhost';      // MySQL host
$dbname = 'task_management'; // Database name
$username = 'root';       // MySQL username (default is 'root' for local setups)
$password = '';           // MySQL password (leave empty for local setups)

// Establish the connection
$conn = mysqli_connect($host, $username, $password, $dbname);

// Check for errors
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
