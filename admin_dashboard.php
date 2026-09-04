<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';

// Only allow admins
if (!isset($_SESSION['UserID']) || ($_SESSION['Role'] ?? '') !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Fetch live counts
$petCount = $conn->query("SELECT COUNT(*) AS total FROM pet")->fetch_assoc()['total'];
$appCount = $conn->query("SELECT COUNT(*) AS total FROM adoptionapplication")->fetch_assoc()['total'];
$volCount = $conn->query("SELECT COUNT(*) AS total FROM volunteer")->fetch_assoc()['total'];
$donationSum = $conn->query("SELECT SUM(amount) AS total FROM donation")->fetch_assoc()['total'];
$abuseCount = $conn->query("SELECT COUNT(*) AS total FROM abuse_reports")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard | Save-A-Pet HUB</title>
    <link rel="stylesheet" href="includes/assets/css/admin.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="admin-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="pet_management.php"><i class="fas fa-dog"></i> Manage Pets</a></li>
            <li><a href="application_management.php"><i class="fas fa-file-alt"></i> Applications</a></li>
            <li><a href="donation_management.php"><i class="fas fa-hand-holding-heart"></i> Donations</a></li>
            <li><a href="volunteer_admin.php"><i class="fas fa-users"></i> Volunteers</a></li>
            <li><a href="abuse_report_management.php"><i class="fas fa-exclamation-triangle"></i> Welfare Reports</a></li>
            <li><a href="surrender_management.php"><i class="fas fa-paw"></i> Pet Surrenders</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
            <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <!-- Main Content -->
    <main class="dashboard">
        <h1>Administrator Dashboard</h1>
        <p class="welcome-text">Welcome, <?php echo htmlspecialchars($_SESSION['FullName']); ?> 👋</p>

        <div class="card-grid">
            <div class="card blue">
                <div class="card-icon"><i class="fas fa-dog"></i></div>
                <h3>Total Pets</h3>
                <p><?php echo $petCount; ?></p>
                <a href="pet_management.php">More info</a>
            </div>
            <div class="card teal">
                <div class="card-icon"><i class="fas fa-file-alt"></i></div>
                <h3>Total Applications</h3>
                <p><?php echo $appCount; ?></p>
                <a href="application_management.php">More info</a>
            </div>
            <div class="card green">
                <div class="card-icon"><i class="fas fa-users"></i></div>
                <h3>Total Volunteers</h3>
                <p><?php echo $volCount; ?></p>
                <a href="volunteer_admin.php">More info</a>
            </div>
            <div class="card yellow">
                <div class="card-icon"><i class="fas fa-hand-holding-heart"></i></div>
                <h3>Total Donations</h3>
                <p>R<?php echo number_format($donationSum, 2); ?></p>
                <a href="donation_management.php">More info</a>
            </div>
            <div class="card red">
                <div class="card-icon"><i class="fas fa-exclamation-triangle"></i></div>
                <h3>Welfare Reports</h3>
                <p><?php echo $abuseCount; ?></p>
                <a href="abuse_report_management.php">More info</a>
            </div>
            <div class="card purple">
            <div class="card-icon"><i class="fas fa-paw"></i></div>
            <h3>Pet Surrenders</h3>
            <p><?php 
                $surrenderCount = $conn->query("SELECT COUNT(*) AS total FROM pet_surrenders")->fetch_assoc()['total']; 
                echo $surrenderCount; 
            ?></p>
            <a href="surrender_management.php">More info</a>
        </div>
        </div>
    </main>
</div>
<?php include 'includes/footer.php'; ?>
</body>
</html>
