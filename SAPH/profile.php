<?php
session_start();

// If user is not logged in, redirect to login page
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Include database connection
include 'DBConn.php';

// Get user info from database using session email or name
$email = $_SESSION['email'] ?? null; // store email in session during login
$userData = null;

if ($email) {
    $stmt = $conn->prepare("SELECT name, email FROM tblUser WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile - Save-A-Pet HUB</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?>

<main>
    <div class="profile-container">
        <h2>Successfully Registered!</h2>
        <?php if ($userData): ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($userData['name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($userData['email']); ?></p>
        <?php else: ?>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>!</p>
        <?php endif; ?>

        <a href="logout.php" class="btn logout">Back to Home</a>
    </div>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
