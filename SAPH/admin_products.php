<?php
include 'DBConn.php';

$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html>
<head>
<title>Pets</title>
<link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>

<h2>Pets</h2>

<a href="add_product.php" class="action-btn">
Go back to Pets Dashboard to view Pets
</a>

<table>

<tr>
<th>ID</th>
<th>Description</th>
<th>Price</th>
<th>Quantity</th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
<td><?php echo $row['product_id']; ?></td>
<td><?php echo $row['description']; ?></td>
<td>R<?php echo $row['price']; ?></td>
<td><?php echo $row['quantity']; ?></td>
</tr>

<?php endwhile; ?>

</table>

</main>

</body>
</html>