<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include 'DBConn.php';

// Restrict access to admins
if (!isset($_SESSION['UserID']) || ($_SESSION['Role'] ?? '') !== 'Admin') {
    header("Location: login.php");
    exit();
}

// Handle status update
if (isset($_POST['update_status'])) {
    $reportID = intval($_POST['ReportID']);
    $newStatus = $_POST['Status'];

    $stmt = $conn->prepare("UPDATE abuse_reports SET Status = ? WHERE ReportID = ?");
    $stmt->bind_param("si", $newStatus, $reportID);
    $stmt->execute();
    $stmt->close();
}

// Fetch reports
$sql = "SELECT ReportID, ReporterName, ReporterEmail, ReporterPhone, IncidentAddress, 
               AnimalType, AbuseType, IncidentDescription, IncidentDate, UrgencyLevel, Status, DateReported 
        FROM abuse_reports 
        ORDER BY DateReported DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Animal Welfare Reports | Save-A-Pet HUB</title>
    <link rel="stylesheet" href="includes/assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <h2>Admin Panel</h2>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="pet_management.php"><i class="fas fa-dog"></i> Manage Pets</a></li>
            <li><a href="application_management.php"><i class="fas fa-file-alt"></i> Applications</a></li>
            <li><a href="donation_management.php"><i class="fas fa-hand-holding-heart"></i> Donations</a></li>
            <li><a href="volunteer_admin.php"><i class="fas fa-users"></i> Volunteers</a></li>
            <li><a href="abuse_report_management.php" class="active"><i class="fas fa-exclamation-triangle"></i> Welfare Reports</a></li>
            <li><a href="pet_surrender_management.php"><i class="fas fa-paw"></i> Pet Surrenders</a></li>
            <li><a href="reports.php"><i class="fas fa-chart-line"></i> Reports</a></li>
            <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="dashboard">
        <h1>Animal Welfare Reports</h1>
        <p class="welcome-text">Review and update reported animal abuse cases.</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Reporter</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Animal</th>
                    <th>Abuse Type</th>
                    <th>Description</th>
                    <th>Incident Date</th>
                    <th>Urgency</th>
                    <th>Status</th>
                    <th>Date Reported</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['ReportID']; ?></td>
                            <td><?php echo htmlspecialchars($row['ReporterName']); ?></td>
                            <td><?php echo htmlspecialchars($row['ReporterEmail']); ?></td>
                            <td><?php echo htmlspecialchars($row['ReporterPhone']); ?></td>
                            <td><?php echo htmlspecialchars($row['IncidentAddress']); ?></td>
                            <td><?php echo htmlspecialchars($row['AnimalType']); ?></td>
                            <td><?php echo htmlspecialchars($row['AbuseType']); ?></td>
                            <td><?php echo htmlspecialchars($row['IncidentDescription']); ?></td>
                            <td><?php echo htmlspecialchars($row['IncidentDate']); ?></td>
                            <td><?php echo htmlspecialchars($row['UrgencyLevel']); ?></td>
                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                            <td><?php echo htmlspecialchars($row['DateReported']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="ReportID" value="<?php echo $row['ReportID']; ?>">
                                    <select name="Status">
                                        <option value="Open" <?php if($row['Status']=="Open") echo "selected"; ?>>Open</option>
                                        <option value="Investigating" <?php if($row['Status']=="Investigating") echo "selected"; ?>>Investigating</option>
                                        <option value="Resolved" <?php if($row['Status']=="Resolved") echo "selected"; ?>>Resolved</option>
                                        <option value="Escalated" <?php if($row['Status']=="Escalated") echo "selected"; ?>>Escalated</option>
                                    </select>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="13">No reports found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
