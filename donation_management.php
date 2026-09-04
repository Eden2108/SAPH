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
    <style>
        table {
        width: 90%;
        margin: 0 auto; /* center the table */
        border-collapse: collapse;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    th, td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: lightseagreen;
        color: white;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #ffe5b4; /* light orange hover */
    }
    </style>
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
    <a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
