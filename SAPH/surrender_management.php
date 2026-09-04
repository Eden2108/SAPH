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
    $surrenderID = intval($_POST['SurrenderID']);
    $newStatus = $_POST['Status'];

    $stmt = $conn->prepare("UPDATE pet_surrenders SET Status = ? WHERE SurrenderID = ?");
    $stmt->bind_param("si", $newStatus, $surrenderID);
    $stmt->execute();
    $stmt->close();
}

// Fetch surrender requests
$sql = "SELECT SurrenderID, UserID, PetName, AnimalType, Breed, Age, Gender, 
               SurrenderReason, Temperament, MedicalInformation, SurrenderFee, Status, DateSubmitted 
        FROM pet_surrenders ORDER BY DateSubmitted DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pet Surrender Management | Save-A-Pet HUB</title>
    <link rel="stylesheet" href="includes/assets/css/admin.css">
</head>
<body>
<div class="admin-container">
    <aside class="sidebar">
        <h2>Admin Panel - <?php echo htmlspecialchars($_SESSION['FullName']); ?></h2>
        <ul>
            <li><a href="admin_dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="pet_management.php"><i class="fas fa-dog"></i> Manage Pets</a></li>
            <li><a href="application_management.php"><i class="fas fa-file-alt"></i> Applications</a></li>
            <li><a href="donation_management.php"><i class="fas fa-hand-holding-heart"></i> Donations</a></li>
            <li><a href="volunteer_admin.php"><i class="fas fa-users"></i> Volunteers</a></li>
            <li><a href="surrender_management.php" class="active"><i class="fas fa-paw"></i> Pet Surrenders</a></li>
            <li><a href="abuse_report_management.php"><i class="fas fa-exclamation-triangle"></i> Welfare Reports</a></li>
            <li><a href="logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </aside>

    <main class="dashboard">
        <h1>Pet Surrender Requests</h1>
        <p class="welcome-text">Review and update surrendered pets.</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User ID</th>
                    <th>Pet Name</th>
                    <th>Animal Type</th>
                    <th>Breed</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Reason</th>
                    <th>Temperament</th>
                    <th>Medical Info</th>
                    <th>Fee</th>
                    <th>Status</th>
                    <th>Date Submitted</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['SurrenderID']; ?></td>
                            <td><?php echo $row['UserID']; ?></td>
                            <td><?php echo htmlspecialchars($row['PetName']); ?></td>
                            <td><?php echo htmlspecialchars($row['AnimalType']); ?></td>
                            <td><?php echo htmlspecialchars($row['Breed']); ?></td>
                            <td><?php echo htmlspecialchars($row['Age']); ?></td>
                            <td><?php echo htmlspecialchars($row['Gender']); ?></td>
                            <td><?php echo htmlspecialchars($row['SurrenderReason']); ?></td>
                            <td><?php echo htmlspecialchars($row['Temperament']); ?></td>
                            <td><?php echo htmlspecialchars($row['MedicalInformation']); ?></td>
                            <td><?php echo htmlspecialchars($row['SurrenderFee']); ?></td>
                            <td><?php echo htmlspecialchars($row['Status']); ?></td>
                            <td><?php echo htmlspecialchars($row['DateSubmitted']); ?></td>
                            <td>
                                <form method="post" style="display:inline;">
                                    <input type="hidden" name="SurrenderID" value="<?php echo $row['SurrenderID']; ?>">
                                    <select name="Status">
                                        <option value="Pending" <?php if($row['Status']=="Pending") echo "selected"; ?>>Pending</option>
                                        <option value="Approved" <?php if($row['Status']=="Approved") echo "selected"; ?>>Approved</option>
                                        <option value="Rejected" <?php if($row['Status']=="Rejected") echo "selected"; ?>>Rejected</option>
                                    </select>
                                    <button type="submit" name="update_status">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="14">No surrender requests found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
