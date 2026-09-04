<?php
include 'includes/navbar.php';
include 'DBConn.php';

$message = "";
$volunteers = [];

// Handle volunteer removal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove'])) {
    $volunteerId = intval($_POST['volunteer_id']);
    $conn->query("DELETE FROM volunteer_pet WHERE VolunteerID=$volunteerId");
    $conn->query("DELETE FROM volunteer WHERE VolunteerID=$volunteerId");
    $message = "❌ Volunteer removed successfully.";
}

// Fetch volunteers with grouped pets, shared note/date
$sql = "SELECT v.VolunteerID, v.FullName, v.Email, v.Availability, v.AssignedRole,
               GROUP_CONCAT(p.Name SEPARATOR ', ') AS PetNames,
               MIN(vp.StartDate) AS StartDate,
               MAX(vp.Notes) AS Notes
        FROM volunteer v
        LEFT JOIN volunteer_pet vp ON v.VolunteerID = vp.VolunteerID
        LEFT JOIN pet p ON vp.PetID = p.PetID
        GROUP BY v.VolunteerID
        ORDER BY v.VolunteerID ASC";

$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $volunteers[] = $row;
    }
} else {
    $message = "❌ No volunteers registered yet.";
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Management - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        .form-container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 25px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        table th, table td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: center;
        }
        table th { background-color: #006666; color: #fff; }

        /* Base style for all action buttons */
        .action-btn {
            display: inline-block;
            width: 100px;
            text-align: center;
            padding: 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            color: #fff;
        }
        .view-btn { background: #4a6fa5; }
        .view-btn:hover { background: #2f4a73; }
        .edit-btn { background: #008080; }
        .edit-btn:hover { background: #006666; }
        .remove-btn { background: #f44336; }
        .remove-btn:hover { background: #c62828; }

        .actions-cell {
            display: flex;
            justify-content: center;
            gap: 12px;
        }

        .add-item-btn {
            display: inline-block;
            margin-top: 20px;
            text-decoration: none;
            background: #008080;
            color: #fff;
            padding: 10px 15px;
            border-radius: 5px;
        }
        .add-item-btn:hover { background: #006666; }

        /* Modal styling */
        .modal {
            display: none;
            position: fixed;
            z-index: 999;
            left: 0; top: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
        }
        .modal-content {
            background: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 60%;
        }
        .close {
            float: right;
            font-size: 20px;
            cursor: pointer;
        }
    </style>
    <script>
        function confirmRemove() {
            return confirm("⚠️ Are you sure you want to remove this volunteer?");
        }
        function openModal(id) {
            document.getElementById("modal-" + id).style.display = "block";
        }
        function closeModal(id) {
            document.getElementById("modal-" + id).style.display = "none";
        }
    </script>
</head>
<body>
<main class="form-container">
    <h2>Current Volunteers</h2>
    <?php if($message) echo "<p class='form-intro'>$message</p>"; ?>

    <?php if(!empty($volunteers)): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Availability</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            <?php foreach($volunteers as $v): ?>
            <tr>
                <td><?php echo htmlspecialchars($v['VolunteerID']); ?></td>
                <td><?php echo htmlspecialchars($v['FullName']); ?></td>
                <td><?php echo htmlspecialchars($v['Email']); ?></td>
                <td><?php echo $v['Availability'] ? htmlspecialchars($v['Availability']) : "Not specified"; ?></td>
                <td><?php echo htmlspecialchars($v['AssignedRole']); ?></td>
                <td class="actions-cell">
                    <button class="action-btn view-btn" onclick="openModal(<?php echo $v['VolunteerID']; ?>)">View</button>
                    <a href="volunteer_edit.php?id=<?php echo $v['VolunteerID']; ?>" class="action-btn edit-btn">Edit</a>
                    <form method="POST" onsubmit="return confirmRemove();" style="display:inline;">
                        <input type="hidden" name="volunteer_id" value="<?php echo $v['VolunteerID']; ?>">
                        <button type="submit" name="remove" class="action-btn remove-btn">Remove</button>
                    </form>
                </td>
            </tr>

            <!-- Modal for details -->
            <div id="modal-<?php echo $v['VolunteerID']; ?>" class="modal">
                <div class="modal-content">
                    <span class="close" onclick="closeModal(<?php echo $v['VolunteerID']; ?>)">&times;</span>
                    <h3>Volunteer Details: <?php echo htmlspecialchars($v['FullName']); ?></h3>
                    <p><strong>Pets:</strong><br><?php echo $v['PetNames'] ? nl2br(htmlspecialchars($v['PetNames'])) : "No pets assigned"; ?></p>
                    <p><strong>Shared Note:</strong><br><?php echo $v['Notes'] ? htmlspecialchars($v['Notes']) : "-"; ?></p>
                    <p><strong>Shared Start Date:</strong><br><?php echo $v['StartDate'] ? htmlspecialchars($v['StartDate']) : "-"; ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
