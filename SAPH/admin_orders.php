<?php
include 'DBConn.php';

$result = $conn->query("SELECT * FROM orders");
?>

<!DOCTYPE html>
<html>
<head>
<title>Orders</title>
<link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>

<h2>Orders</h2>

<table>

<tr>
<th>Order ID</th>
<th>Product ID</th>
<th>User ID</th>
<th>Quantity</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
<td><?php echo $row['order_id']; ?></td>
<td><?php echo $row['product_id']; ?></td>
<td><?php echo $row['user_id']; ?></td>
<td><?php echo $row['quantity']; ?></td>
</tr>

<?php endwhile; ?>

</table>

</main>

</body>
</html>