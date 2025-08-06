<?php
// Database configuration
$servername = "localhost"; // Change to your server name
// $username = "root"; // Change to your database username
$username = "gdpd2131_gdpbooth"; // Change to your database username
$password = "gdpbooth"; // Change to your database password
// $password = "gdpd2131_u140154479_photobooth"; // Change to your database password
$database = "gdpd2131_u140154479_photobooth"; // Change to your database name

// Create connection
$koneksi = new mysqli($servername, $username, $password, $database);

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

