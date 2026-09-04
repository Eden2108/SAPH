<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "save_a_pet_hub"; // <-- actual database name in phpMyAdmin

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
