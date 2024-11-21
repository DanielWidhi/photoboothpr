<?php
// Database configuration
$servername = "localhost"; // Change to your server name
$username = "u140154479_photobooth";        // Change to your database username
$password = "Photobooth123@";            // Change to your database password
$database = "u140154479_photobooth";     // Change to your database name

// Create connection
$koneksi = new mysqli($servername, $username, $password, $database);

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}
?>