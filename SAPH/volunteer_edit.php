<?php
include 'includes/navbar.php';
include 'DBConn.php';

$volunteerId = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch volunteer details
$sql = "SELECT * FROM volunteer WHERE VolunteerID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteerId);
$stmt->execute();
$volunteer = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST['AssignedRole'];
    $availability = $_POST['Availability'];
    $startDate = $_POST['StartDate'];
    $note = $_POST['Notes'];

    // Update volunteer role/availability
    $sql = "UPDATE volunteer SET AssignedRole=?, Availability=? WHERE VolunteerID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $role, $availability, $volunteerId);
    $stmt->execute();
    $stmt->close();

    // Remove old pet assignments
    $conn->query("DELETE FROM volunteer_pet WHERE VolunteerID=$volunteerId");

    // Insert new pet assignments with shared note/date
    if (!empty($_POST['pets'])) {
        foreach ($_POST['pets'] as $petId) {
            $sql = "INSERT INTO volunteer_pet (VolunteerID, PetID, StartDate, Notes) VALUES (?,?,?,?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiss", $volunteerId, $petId, $startDate, $note);
            $stmt->execute();
            $stmt->close();
        }
    }

    // Redirect back to admin list so form clears
    header("Location: volunteer_admin.php?updated=1");
    exit();
}

// Fetch all pets
$pets = $conn->query("SELECT PetID, Name FROM pet");

// Fetch currently assigned pets
$assigned = $conn->query("SELECT PetID, StartDate, Notes FROM volunteer_pet WHERE VolunteerID=$volunteerId");
$assignedPets = [];
$commonDate = "";
$commonNote = "";
while($row = $assigned->fetch_assoc()) {
    $assignedPets[] = $row['PetID'];
    if ($commonDate == "") $commonDate = $row['StartDate'];
    if ($commonNote == "") $commonNote = $row['Notes'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Volunteer - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        .form-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 25px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 2px 6px rgba(0,0,0,0.1);
        }
        h2 { text-align: center; margin-bottom: 20px; color: #333; }
        label { display: block; margin-top: 10px; color: #000; }
        input, select { width: 100%; padding: 8px; margin-top: 5px; }
        .submit-btn {
            margin-top: 20px;
            background: #008080;
            color: #fff;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }
        .submit-btn:hover { background: #006666; }

        /* Grid layout for pets */
        .assign-pets {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 8px;
            margin-top: 10px;
        }
        .assign-pets label {
            border: 1px solid #ccc;
            padding: 6px;
            border-radius: 5px;
            background: #f9f9f9;
        }
    </style>
</head>
<body>
<main class="form-container">
    <h2>Edit Volunteer</h2>

    <?php if($volunteer): ?>
        <form method="POST">
            <label>Full Name</label>
            <input type="text" value="<?php echo htmlspecialchars($volunteer['FullName']); ?>" disabled>

            <label>Email</label>
            <input type="text" value="<?php echo htmlspecialchars($volunteer['Email']); ?>" disabled>

            <label>Availability</label>
            <select name="Availability">
                <option value="Weekdays" <?php if($volunteer['Availability']=="Weekdays") echo "selected"; ?>>Weekdays</option>
                <option value="Weekends" <?php if($volunteer['Availability']=="Weekends") echo "selected"; ?>>Weekends</option>
                <option value="Evenings" <?php if($volunteer['Availability']=="Evenings") echo "selected"; ?>>Evenings</option>
            </select>

            <label>Assigned Role</label>
            <select name="AssignedRole">
                <option value="Animal Care" <?php if($volunteer['AssignedRole']=="Animal Care") echo "selected"; ?>>Animal Care</option>
                <option value="Dog Walking" <?php if($volunteer['AssignedRole']=="Dog Walking") echo "selected"; ?>>Dog Walking</option>
                <option value="Fundraising" <?php if($volunteer['AssignedRole']=="Fundraising") echo "selected"; ?>>Fundraising</option>
                <option value="Kennel Cleaning" <?php if($volunteer['AssignedRole']=="Kennel Cleaning") echo "selected"; ?>>Kennel Cleaning</option>
            </select>

    
            <label>Assign Pets</label>
            <div class="assign-pets">
                <?php while($p = $pets->fetch_assoc()): ?>
                    <label>
                        <input type="checkbox" name="pets[]" value="<?php echo $p['PetID']; ?>"
                               <?php if(in_array($p['PetID'], $assignedPets)) echo "checked"; ?>>
                        <?php echo htmlspecialchars($p['Name']); ?>
                    </label>
                <?php endwhile; ?>
            </div>

            <label>Start Date (applies to all pets)</label>
            <input type="date" name="StartDate" value="<?php echo htmlspecialchars($commonDate); ?>">

            <label>Notes (applies to all pets)</label>
            <input type="text" name="Notes" value="<?php echo htmlspecialchars($commonNote); ?>">

            <button type="submit" class="submit-btn">Save Changes</button>
        </form>
    <?php else: ?>
        <p>Volunteer not found.</p>
    <?php endif; ?>

    <a href="volunteer_admin.php" class="submit-btn">Back to Current Volunteers</a>
</main>

<script>
document.getElementById('selectAllPets').addEventListener('change', function() {
    document.querySelectorAll('input[name="pets[]"]').forEach(cb => cb.checked = this.checked);
});
</script>

<?php include 'includes/footer.php'; ?>
</body>
</html>
