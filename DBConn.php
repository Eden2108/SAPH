<?php
$host = "sql104.infinityfree.com";   // InfinityFree host
$user = "if0_42835585";      // Our DB username
$password = "VGD8qrba9GAvG";  // Our DB password
$dbname = "if0_42835585_save_a_pet_hub";  // Our DB name

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
