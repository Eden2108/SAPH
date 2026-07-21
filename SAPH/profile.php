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

$sql = "SELECT UserID, name, email, Role
        FROM users
        WHERE UserID = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("i", $userID);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: logout.php");
    exit();
}

$user = $result->fetch_assoc();

$stmt->close();

$initial = strtoupper(substr($user['name'], 0, 1));
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>My Profile | Save-A-Pet HUB</title>

    <link
        rel="stylesheet"
        href="includes/assets/css/style.css"
    >

</head>

<body>

<?php include 'includes/navbar.php'; ?>


<main class="profile-page">

    <div class="profile-container">


        <!-- PROFILE HEADER -->

        <div class="profile-header">

            <div class="profile-avatar-large">
                <?php echo htmlspecialchars($initial); ?>
            </div>


            <div class="profile-heading">

                <h2>
                    <?php echo htmlspecialchars($user['name']); ?>
                </h2>

                <p>
                    <?php echo htmlspecialchars($user['email']); ?>
                </p>

                <span class="profile-role">
                    Save-A-Pet HUB Member
                </span>

            </div>

        </div>



        <!-- ACCOUNT INFORMATION -->

        <div class="profile-section">

            <h3>Account Information</h3>

            <div class="profile-information-grid">


                <div class="profile-information-item">

                    <span>Full Name</span>

                    <strong>
                        <?php echo htmlspecialchars($user['name']); ?>
                    </strong>

                </div>


                <div class="profile-information-item">

                    <span>Email Address</span>

                    <strong>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </strong>

                </div>


                <div class="profile-information-item">

                    <span>Account Role</span>

                    <strong>
                        <?php echo htmlspecialchars($user['Role']); ?>
                    </strong>

                </div>


            </div>

        </div>



        <!-- ACCOUNT ACTIONS -->

        <div class="profile-section">

            <h3>Manage Account</h3>

            <div class="profile-action-grid">


                <a
                    href="update_profile.php"
                    class="profile-action-card"
                >

                    <span class="profile-action-icon">
                        👤
                    </span>

                    <div>

                        <strong>
                            Update Profile
                        </strong>

                        <p>
                            Change your personal account information.
                        </p>

                    </div>

                </a>



                <a
                    href="change_password.php"
                    class="profile-action-card"
                >

                    <span class="profile-action-icon">
                        🔒
                    </span>

                    <div>

                        <strong>
                            Change Password
                        </strong>

                        <p>
                            Update your account password securely.
                        </p>

                    </div>

                </a>


            </div>

        </div>



        <!-- PROFILE FOOTER ACTIONS -->

        <div class="profile-footer-actions">

            <a
                href="index.php"
                class="add-item-btn"
            >
                Back to Home
            </a>


            <a
                href="logout.php"
                class="logout-btn"
            >
                Logout
            </a>

        </div>


    </div>

</main>


<?php include 'includes/footer.php'; ?>

</body>

</html>