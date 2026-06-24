<?php
include 'includes/navbar.php';
include 'DBConn.php'; // connect to database

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $interest = $_POST['interest'];
    $availability = $_POST['availability'];

    // Combine email + phone into ContactInfo (since table has only one column)
    $contactInfo = $email . " | " . $phone;

    $sql = "INSERT INTO Volunteer (AdminID, FullName, ContactInfo, Availability, AssignedRole)
            VALUES (1, ?, ?, ?, ?)"; // AdminID = 1 for now

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $fullName, $contactInfo, $availability, $interest);
    
    if ($stmt->execute()) {
        $message = "✅ Thank you, $fullName! You are now registered as a volunteer.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
    $stmt->close();
}

// Fetch all volunteers to display
$volunteers = $conn->query("SELECT FullName, ContactInfo, Availability, AssignedRole FROM Volunteer");
?>
<!DOCTYPE html>
<html>
<head>
    <title>Volunteer Registration - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<main class="form-container">
    <h2>Volunteer Registration</h2>
    <?php if($message) echo "<p class='form-intro'>$message</p>"; ?>

    <form method="POST" action="">
        <label>Full Name</label><br>
        <input type="text" name="fullName" required><br><br>

        <label>Email Address</label><br>
        <input type="email" name="email" required><br><br>

        <label>Phone Number</label><br>
        <input type="tel" name="phone" required><br><br>

        <label>Area of Interest</label><br>
        <select name="interest" required>
            <option>Animal Care</option>
            <option>Dog Walking</option>
            <option>Fundraising</option>
            <option>Events</option>
            <option>Charity Shop</option>
            <option>Administration</option>
        </select><br><br>

        <label>Availability</label><br>
        <textarea name="availability" rows="4"></textarea><br><br>

        <button type="submit">Register as Volunteer</button>
    </form>

    <h3>Current Volunteers</h3>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>Full Name</th>
            <th>Contact Info</th>
            <th>Availability</th>
            <th>Assigned Role</th>
        </tr>
        <?php if ($volunteers && $volunteers->num_rows > 0): ?>
            <?php while($row = $volunteers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['FullName']); ?></td>
                    <td><?php echo htmlspecialchars($row['ContactInfo']); ?></td>
                    <td><?php echo htmlspecialchars($row['Availability']); ?></td>
                    <td><?php echo htmlspecialchars($row['AssignedRole']); ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">No volunteers registered yet.</td></tr>
        <?php endif; ?>
    </table>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
