<?php
include 'DBConn.php';
?>
<?php include 'includes/navbar.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<main class="dashboard">

    <h2>Administrator Dashboard</h2>

    <div class="dashboard-grid">

        <div class="dashboard-card">
            <h3>Manage Pets</h3>
            <p>Add, edit and update pet records.</p>
            <a href="pet_management.php">
                <button>View Pet Listing</button>
            </a>
        </div>

        <div class="dashboard-card">
            <h3>Manage Applications</h3>
            <a href="application_management.php">
                <button>View Applications</button>
            </a>
        </div>

        <div class="dashboard-card">
            <h3>Manage Donations</h3>
            <p>View donation records.</p>
            <a href="donation_management.php">
                <button>View Donations</button>
            </a>
        </div>

        <div class="dashboard-card">
            <h3>Volunteer Management</h3>
            <p>Review volunteer current voluntuneers and registrations.</p>
            <a href="volunteer_list.php">
                <button>View Volunteers</button>
            </a><br>
            <a href="volunteer.php">
                <button>Register Volunteer</button>
            </a>
        </div>

        <div class="dashboard-card">
            <h3>Reports</h3>
            <a href="reports.php">
                <button>View Reports</button>
            </a>
        </div>

    </div>
<a href="index.php" class="add-item-btn">Back To Home</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
