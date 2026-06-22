<?php
include 'DBConn.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<<main class="admin-dashboard-container">
    <h1 style="color: darkblue;">Admin Dashboard</h1>
    
    <div class="dashboard-grid">
        <a href="admin_products.php" class="dashboard-card">
            <h3>Pets</h3>
            <p>View, add, update, and manage all pet records on the hub.</p>
        </a>

        <a href="admin_users.php" class="dashboard-card">
            <h3>Users</h3>
            <p>View registered users, administrative accounts, and contact profiles.</p>
        </a>

        <a href="admin_donations.php" class="dashboard-card">
            <h3>Donations</h3>
            <p>Track financial contributions, donor history, and personal messages.</p>
        </a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>