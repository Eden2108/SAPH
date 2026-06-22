<?php
if(session_status() === PHP_SESSION_NONE){
    session_start();
}

$cartCount = 0;

if(isset($_SESSION['cart'])){
    foreach($_SESSION['cart'] as $item){
        $cartCount += $item['quantity'];
    }
}
?>

<!-- Header file for navigation -->
<header class="site-header">
    <div class="header-container">
        <!-- Logo Font -->
         <link href="https://fonts.googleapis.com/css2?family=Satisfy&display=swap" rel="stylesheet">
        <!-- Logo -->
        <h1 class="logo"> Save-a-Pet HUB <img src = "includes/assets/images/logo.jpeg"></h1>

        <!-- Navigation -->
        <nav class="nav-links">
            <a href="index.php">Home</a>
            <a href="adopt.php">Adopt</a>
            <a href="shop.php">Pets</a>
           <a href="cart.php">Donate</a>
            <a href="contact.php">Volunteer</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
             <a href="admin.php">Admin</a>
        </nav>
    </div>
</header>
