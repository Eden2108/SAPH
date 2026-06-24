<?php
include 'includes/navbar.php';
include 'DBConn.php';

$result = $conn->query("SELECT VolunteerID, FullName, ContactInfo, Availability, AssignedRole FROM Volunteer");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Volunteer List - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>
<main class="profile-container">
    <h2>Registered Volunteers</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="10" style="margin:auto;">
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Contact</th>
                <th>Availability</th>
                <th>Role</th>
            </tr>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['VolunteerID'] ?></td>
                    <td><?= $row['FullName'] ?></td>
                    <td><?= $row['ContactInfo'] ?></td>
                    <td><?= $row['Availability'] ?></td>
                    <td><?= $row['AssignedRole'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No volunteers registered yet.</p>
    <?php endif; ?>
     <a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
