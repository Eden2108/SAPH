<?php
include 'DBConn.php';

$result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html>
<head>
<title>Users</title>
<link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>

<h2>Registered Users</h2>

<table>

<tr>
<th>User_ID:  </th>
<th>Adopter Name: </th>
<th>Adopter Email: </th>
</tr>

<?php while($row = $result->fetch_assoc()): ?>

<tr>
<td><?php echo $row['user_id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
</tr>

<?php endwhile; ?>

</table>

</main>

</body>
</html>