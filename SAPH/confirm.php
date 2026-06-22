<?php
session_start();

// Retrieve checkout selections
$checkout = isset($_SESSION['checkout']) ? $_SESSION['checkout'] : [];

// Generate a random order number for demo purposes
$orderNumber = rand(100000000000, 999999999999);

// Example estimated delivery date (7 days from now)
$deliveryDate = date('D d M', strtotime('+7 days'));

// Clear cart after successful order
unset($_SESSION['cart']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order Confirmation - Pastimes</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<!-- SUCCESS BOX-->
<main>
<div class="confirmation-box">
    <!-- Icon + Heading -->
    <div class="confirmation-header">
        <div class="icon-wrapper">
            <i class="fa-solid fa-box box-icon"></i>
            <i class="fa-solid fa-circle-check check-icon"></i>
        </div>
        <h2>Thanks, we’ve received your order!</h2>
    </div>

    <!-- Order details -->
    <p class="order-number">Order Number: <strong>#<?php echo $orderNumber; ?></strong></p>
    <p class="delivery-date">Estimated Delivery: <strong><?php echo $deliveryDate; ?></strong></p>

    <!-- Info text -->
    <p class="info-text">
        We will send you an SMS or email as soon as your order is on it's way.
    </p>

    <!-- Action buttons -->
    <div class="confirmation-actions">
        <a href="track.php" class="btn"><i class="fa-solid fa-truck"></i> Track Order</a>
        <a href="shop.php" class="btn s"><i class="fa-solid fa-bag-shopping"></i> Continue Shopping</a>
    </div>
</div>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>