<?php
include 'includes/navbar.php';
include 'DBConn.php'; // connect to database

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? ''; // optional, not stored in application table
    $interest = $_POST['interest'] ?? '';
    $availability = $_POST['availability'] ?? '';
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // hash password

    // Insert into volunteer_application table
    $sql = "INSERT INTO volunteer_application (FullName, Email, Password, Availability, AssignedRole)
            VALUES (?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("sssss", $fullName, $email, $password, $availability, $interest);

    if ($stmt->execute()) {
        $message = "✅ Thank you, $fullName! Your application has been submitted and is pending admin approval.";
    } else {
        $message = "❌ Error: " . $conn->error;
    }
    $stmt->close();
}
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

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <label>Phone Number (optional)</label><br>
        <input type="tel" name="phone"><br><br>

        <label>Area of Interest</label><br>
        <select name="interest" required>
            <option value="">--Select an area--</option>
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
        <button type="button" onclick="window.location.href='volunteer_login.php'">
    Login as Current Volunteer
</button>

    </form>

    <a href="index.php" class="add-item-btn">Back To Home</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>