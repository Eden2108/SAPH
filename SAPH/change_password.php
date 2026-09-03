<?php
//============================================
// Start Session
//============================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include "DBConn.php";

// Only logged in users may access this page
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['UserID'];

$success = "";
$error = "";

//============================================
// Change Password
//============================================
if($_SERVER["REQUEST_METHOD"] == "POST"){

    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    // Retrieve current password
    $sql = "SELECT password
            FROM users
            WHERE UserID=?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("i",$userID);

    $stmt->execute();

    $user = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    // Verify current password
    if(!password_verify($currentPassword,$user['password'])){

        $error = "Current password is incorrect.";

    }

    // Password length
    elseif(strlen($newPassword) < 8){

        $error = "Password must be at least 8 characters.";

    }

    // Check confirmation
    elseif($newPassword != $confirmPassword){

        $error = "New passwords do not match.";

    }

    else{

        // Hash new password
        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE users
                SET password=?
                WHERE UserID=?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "si",
            $hashedPassword,
            $userID
        );

        if($stmt->execute()){

            $success = "Password updated successfully.";

        }else{

            $error = "Unable to update password.";

        }

        $stmt->close();

    }

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width,initial-scale=1.0">

<title>Change Password</title>

    <link rel="stylesheet"
    href="includes/assets/css/style.css">

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main class="profile-page">

    <div class="profile-card">

        <h2>Change Password</h2>

        <p class="form-intro">

        Keep your account secure by creating
        a strong password.

        </p>

        <?php if($success){ ?>

<div class="success-message">

        <?php echo $success; ?>

</div>

<?php } ?>

    <?php if($error){ ?>

    <p class="error">

        <?php echo $error; ?>

</p>

    <?php } ?>

<form method="POST">

    <label>Current Password</label>

        <input
        type="password"
        name="current_password"
        required>

    <label>New Password</label>

            <input
            type="password"
            name="new_password"
            required>

<label>Confirm New Password</label>

        <input
        type="password"
        name="confirm_password"
        required>

<div class="form-buttons">

        <button
        type="submit"
        class="submit-btn">

        Save Password

        </button>

        <button
        type="button"
        class="cancel-btn"
        onclick="window.location.href='profile.php'">

        Cancel

        </button>

</div>

</form>

</div>

</main>

<?php include 'includes/footer.php'; ?>

</body>

</html>

