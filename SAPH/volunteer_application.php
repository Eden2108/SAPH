<?php
include 'includes/navbar.php';
include 'DBConn.php';

$message = "";

// Handle Accept / Decline actions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appId = intval($_POST['application_id']);

    // Accept action
    if (isset($_POST['accept'])) {
        $sql = "SELECT FullName, Email, Password, Availability, AssignedRole 
                FROM volunteer_application WHERE ApplicationID = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $appId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // ✅ Insert into volunteer table
            $sql2 = "INSERT INTO volunteer (FullName, Email, Password, Availability, AssignedRole)
                     VALUES (?, ?, ?, ?, ?)";
            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("sssss", $row['FullName'], $row['Email'], $row['Password'], $row['Availability'], $row['AssignedRole']);
            $stmt2->execute();
            $stmt2->close();
        }
        $stmt->close();

        $conn->query("UPDATE volunteer_application SET Status='Accepted' WHERE ApplicationID=$appId");
        $message = "✅ Volunteer accepted and added.";
    }

    // Decline action
    if (isset($_POST['decline'])) {
        $conn->query("UPDATE volunteer_application SET Status='Declined' WHERE ApplicationID=$appId");
        $message = "❌ Volunteer application declined.";
    }
}

// Get all applications
$sql = "SELECT ApplicationID, FullName, Email, Availability, AssignedRole, Status 
        FROM volunteer_application ORDER BY ApplicationID DESC";
$result = $conn->query($sql);
$applications = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Applications - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        .form-container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 25px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: center;
        }
        table th {
            background-color: #006666; /* teal header */
            color: #fff;
        }
        .action-btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .accept-btn {
            background-color: #4CAF50;
            color: white;
        }
        .decline-btn {
            background-color: #f44336;
            color: white;
        }
        /* Clean layout for actions column */
        .actions-cell {
            display: flex;
            justify-content: center;
            gap: 12px;
        }
        .actions-cell form {
            margin: 0;
        }
        /* Teal Back button */
        .add-item-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #008080; /* teal */
            color: #fff;
            padding: 8px 12px;
            border-radius: 5px;
        }
        .add-item-btn:hover {
            background: #006666; /* darker teal */
        }
    </style>
</head>
<body>
<main class="form-container">
    <h2>Volunteer Applications</h2>
    <?php if($message) echo "<p class='form-intro'>$message</p>"; ?>

    <?php if(!empty($applications)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Availability</th>
                <th>Assigned Role</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach($applications as $app): ?>
            <tr>
                <td><?php echo htmlspecialchars($app['ApplicationID']); ?></td>
                <td><?php echo htmlspecialchars($app['FullName']); ?></td>
                <td><?php echo htmlspecialchars($app['Email']); ?></td>
                <td><?php echo htmlspecialchars($app['Availability']); ?></td>
                <td><?php echo htmlspecialchars($app['AssignedRole']); ?></td>
                <td><?php echo htmlspecialchars($app['Status']); ?></td>
                <td class="actions-cell">
                    <?php if($app['Status'] === 'Pending'): ?>
                        <form method="POST">
                            <input type="hidden" name="application_id" value="<?php echo $app['ApplicationID']; ?>">
                            <button type="submit" name="accept" class="action-btn accept-btn">Accept</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="application_id" value="<?php echo $app['ApplicationID']; ?>">
                            <button type="submit" name="decline" class="action-btn decline-btn">Decline</button>
                        </form>
                    <?php else: ?>
                        <em>No actions</em>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p>No volunteer applications yet.</p>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
