<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'DBConn.php';

if (!isset($_SESSION['UserID']) || ($_SESSION['Role'] ?? '') !== 'User') {
    header("Location: login.php");
    exit();
}

$userID = intval($_SESSION['UserID']);

$sql = "SELECT UserID,
               name,
               LastName,
               email,
                Role
        FROM users
        WHERE UserID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows != 1){
    header("Location: logout.php");
    exit();
}

$user = $result->fetch_assoc();

$stmt->close();

// Get first letter of first name
$firstInitial = strtoupper(substr($user['name'], 0, 1));

// Get first letter of last name (if it exists)
$lastInitial = "";

if (!empty($user['LastName'])) {
    $lastInitial = strtoupper(substr($user['LastName'], 0, 1));
}

// Combine them (e.g. RB)
$initial = $firstInitial . $lastInitial;
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1">

<title>My Profile | Save-A-Pet HUB</title>

<link rel="stylesheet" href="includes/assets/css/style.css">

<link rel="stylesheet"
href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="page-wrapper">
    <main class="profile-page">

        <div class="profile-card">

<!-- PROFILE HEADER -->
        <div class="profile-top">

    <div class="profile-avatar">
        <?php echo $initial; ?>
    </div>

    <div class="profile-user-info">

        <h2>
            <?php
            echo htmlspecialchars($user['name']) .
            " " .
            htmlspecialchars($user['LastName']);
            ?>
        </h2>

        <p>
            <?php echo htmlspecialchars($user['email']); ?>
        </p>

    </div>

</div>

        <hr>

        <!-- MANAGE ACCOUNT -->
    <div class="profile-links">

        <a href="update_profile.php" class="profile-link">

        <span class="icon">👤</span>

        <div>

        <strong>Profile Details</strong>

        <p>Manage your profile and address</p>

        </div>

        </a>


        <a href="change_password.php" class="profile-link">

        <span class="icon">🔒</span>

        <div>

        <strong>Change Password</strong>

        <p>Keep your account secure.</p>

        </div>

        </a>


        <a href="my_applications.php" class="profile-link">

        <span class="icon">🐾</span>

        <div>

        <strong>Adoption History</strong>

        <p>Track your adoption applications.</p>

        </div>

        </a>


        <a href="home_inspections.php" class="profile-link">

        <span class="icon">🏡</span>

        <div>

        <strong>Home Inspections</strong>

        <p>View upcoming inspections.</p>

        </div>

        </a>


        <a href="donation_history.php" class="profile-link">

        <span class="icon">💖</span>

        <div>

        <strong>Donation History</strong>

        <p>View your previous donations.</p>

        </div>

        </a>


        <a href="#" class="profile-link">

        <span class="icon">📄</span>

        <div>

        <strong>Adoption Certificate</strong>

        <p>Available after a successful adoption.</p>

        </div>

        </a>

        </div>

        <hr>

        <div class="profile-footer">

        <a href="logout.php" class="logout-btn">

        <span class="material-symbols-outlined">

        logout

        </span>Logout</a>

        </div>

    </div>
</div>
</main>

    <?php include 'includes/footer.php'; ?>

    </body>

</html>