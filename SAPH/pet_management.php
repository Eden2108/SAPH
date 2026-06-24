<?php
session_start();
include 'DBConn.php';
include 'includes/navbar.php';

// Handle bulk status update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_all'])) {
    if (!empty($_POST['status'])) {
        foreach ($_POST['status'] as $petID => $newStatus) {
            if ($newStatus != "--Select Option--") {
                $stmt = $conn->prepare("UPDATE Pet SET AdoptionStatus=? WHERE PetID=?");
                $stmt->bind_param("si", $newStatus, $petID);
                $stmt->execute();
                $stmt->close();
            }
        }
    }

    // Refresh page after update
    header("Location: pet_management.php");
    exit();
}

// Fetch pets
$result = $conn->query("SELECT PetID, Name, Age, Colour, AdoptionStatus FROM Pet");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Management - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        .top-bar {
            display: flex;
            justify-content: center;   /* centers both buttons */
            gap: 25px;                 /* spacing between them */
            margin: 20px 0;
        }
        .btn {
            padding: 10px 18px;
            border-radius: 4px;
            font-weight: bold;
            border: none;
            cursor: pointer;
            text-decoration: none;
            color: white;
        }

    /* Add New Pet stays teal */
        .btn-add {
            background-color: teal;
        }
        .btn-add:hover {
            background-color: #008080; /* darker teal */
        }

        /* Update Status is orange */
        .btn-update {
            background-color: orange;
        }
        .btn-update:hover {
            background-color: darkorange;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: teal;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        select {
            padding: 5px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>
<main>
    <h2>Manage Pets</h2><br>

    <!-- Top bar -->
    <div class="top-bar">
    <a href="add_pet.php" class="btn btn-add"> Add New Pet</a>
    <a href="pet_listings.php?updated=true" class="btn btn-update">Update Status</a>
</div>

    
        
    <form method="POST" action="">
        <table>
            <tr>
                <th>ID</th><th>Name</th><th>Age</th><th>Colour</th><th>Status</th>
            </tr>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($pet = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $pet['PetID']; ?></td>
                        <td><?php echo $pet['Name']; ?></td>
                        <td><?php echo $pet['Age']; ?></td>
                        <td><?php echo $pet['Colour']; ?></td>
                        <td>
                            <select name="status[<?php echo $pet['PetID']; ?>]">
                                <option>--Select Option--</option>
                                <option value="Available" <?php if($pet['AdoptionStatus']=="Available") echo "selected"; ?>>Available</option>
                                <option value="Adopted" <?php if($pet['AdoptionStatus']=="Adopted") echo "selected"; ?>>Adopted</option>
                            </select>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5">No pets found.</td></tr>
            <?php endif; ?>
        </table>
    </form>
     <a href="admin_dashboard.php" class="add-item-btn">Back to Admin Dashboard</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
