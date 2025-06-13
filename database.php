<?php
$servername = "localhost";
$username = "root"; // default username for XAMPP
$password = "";     // default password for XAMPP (leave empty)
$dbname = "caretrack"; // make sure you have already created this database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // Redirect to error page and stop script
    header("Location: database_error.php");
    exit();
}
?>
