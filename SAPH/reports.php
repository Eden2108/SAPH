<?php
include 'DBConn.php';

$result = $conn->query("SELECT * FROM products");
?>

<?php include 'includes/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Reports</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<main>

    <h2>Reports Dashboard</h2>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <h3>Adoption Report</h3>
            <p>Total Adoptions: 45</p>
        </div>

        <div class="dashboard-card">
            <h3>Donation Report</h3>
            <p>Total Donations: R12 500</p>
        </div>

        <div class="dashboard-card">
            <h3>Monthly Intake Report</h3>
            <p>Animals Received: 30</p>
        </div>

    </div>
<a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>

</body>
</html>