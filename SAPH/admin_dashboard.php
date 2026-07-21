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

        <p>
            Add, edit and update pet records.
        </p>

        <a href="pet_management.php">
            <button>Manage Pets</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Adoption Applications</h3>

        <p>
            Review and manage adoption applications.
        </p>

        <a href="application_management.php">
            <button>View Applications</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Donations</h3>

        <p>
            View and monitor donation records.
        </p>

        <a href="donation_management.php">
            <button>View Donations</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Volunteer Management</h3>

        <p>
            Review volunteers and volunteer registrations.
        </p>

        <a href="volunteer_admin.php">
            <button>Manage Volunteers</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Animal Abuse Reports</h3>

        <p>
            Review reported animal welfare concerns.
        </p>

        <a href="abuse_report_management.php">
            <button>View Abuse Reports</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Pet Surrenders</h3>

        <p>
            Review pet surrender requests and records.
        </p>

        <a href="surrender_management.php">
            <button>View Surrenders</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Reports</h3>

        <p>
            View adoption, donation and intake reports.
        </p>

        <a href="reports.php">
            <button>View Reports</button>
        </a>
    </div>


    <div class="dashboard-card">
        <h3>Admin Profile</h3>

        <p>
            Manage administrator account information.
        </p>

        <a href="admin_profile.php">
            <button>View Profile</button>
        </a>
    </div>

</div>
<a href="index.php" class="add-item-btn">Back To Home</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>