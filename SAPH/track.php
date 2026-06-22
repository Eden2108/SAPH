<?php
session_start();

// Example order number (replace with DB/session data)
$orderNumber = isset($_SESSION['orderNumber']) ? $_SESSION['orderNumber'] : rand(100000000000, 999999999999);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Track Order - Pastimes</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
<h2>Track My Order</h2>
<p>Your order number is <strong>#<?php echo $orderNumber; ?></strong>.</p>
<p>Status: <span style="color:green;">Processing</span></p>
<p>Estimated delivery: <?php echo date('D d M', strtotime('+7 days')); ?></p>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
