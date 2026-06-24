<?php
session_start();
include 'DBConn.php';
include 'includes/navbar.php';

// Fetch donations
$result = $conn->query("SELECT DonationID, DonorName, Amount, PaymentMethod, DonationDate, UserID FROM Donation");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Donation Management - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>
<main>
    <h2>Manage Donations</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Donor</th>
            <th>Amount (R)</th>
            <th>Method</th>
            <th>Date</th>
            <th>UserID</th>
        </tr>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($donation = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $donation['DonationID']; ?></td>
                    <td><?php echo $donation['DonorName']; ?></td>
                    <td><?php echo number_format($donation['Amount'], 2); ?></td>
                    <td><?php echo $donation['PaymentMethod']; ?></td>
                    <td><?php echo $donation['DonationDate']; ?></td>
                    <td><?php echo $donation['UserID']; ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No donations found.</td></tr>
        <?php endif; ?>
    </table>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
