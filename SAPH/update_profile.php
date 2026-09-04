<?php
// Start session if one has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
include 'DBConn.php';

// Ensure only logged in users can access this page
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}

// Get the currently logged in user's ID
$userID = $_SESSION['UserID'];

$success = "";
$error = "";

/*==================================================
    UPDATE PROFILE
==================================================*/
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Collect the updated information from the form
    $name = trim($_POST['name']);
    $lastName = trim($_POST['LastName']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['PhoneNumber']);
    $address = trim($_POST['Address']);
    $postalCode = trim($_POST['PostalCode']);

    // Update the user's information
    $sql = "UPDATE users
            SET
                name = ?,
                LastName = ?,
                email = ?,
                PhoneNumber = ?,
                Address = ?,
                PostalCode = ?
            WHERE UserID = ?";

    $stmt = $conn->prepare($sql);

    if(!$stmt){
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param(
        "ssssssi",
        $name,
        $lastName,
        $email,
        $phone,
        $address,
        $postalCode,
        $userID
    );

    if($stmt->execute()){

        // Update session name so navbar updates immediately
        $_SESSION['FullName'] = $name;
        $_SESSION['LastName'] = $lastName;

        $success = "Your profile has been updated successfully.";

    }else{

        $error = "Unable to update your profile.";

    }

    $stmt->close();
}


/*==================================================
    LOAD USER INFORMATION
==================================================*/

$sql = "SELECT
            name,
            LastName,
            email,
            PhoneNumber,
            Address,
            PostalCode
        FROM users
        WHERE UserID = ?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$userID);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$stmt->close();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Update Profile</title>

<link rel="stylesheet"
      href="includes/assets/css/style.css">

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main>
    <div class="form-box">
        <h2>Profile Details</h2>
        <p class="form-intro">Keep your personal information up to date. Your details help us contact you regarding adoptions, donations and home inspections.</p>

        <?php if($success){ ?>
            <p class="success"><?php echo $success; ?></p>
        <?php } ?>

        <?php if($error){ ?>
            <p class="error"><?php echo $error; ?></p>
        <?php } ?>

        <form method="POST">
            <label for="name">First Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>

            <label for="LastName">Last Name</label>
            <input type="text" id="LastName" name="LastName" value="<?php echo htmlspecialchars($user['LastName']); ?>" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

            <label for="PhoneNumber">Phone Number</label>
            <input type="text" id="PhoneNumber" name="PhoneNumber" value="<?php echo htmlspecialchars($user['PhoneNumber']); ?>" placeholder="082 123 4567">

            <label for="Address">Home Address</label>
            <textarea id="Address" name="Address" rows="4"><?php echo htmlspecialchars($user['Address']); ?></textarea>

            <label for="PostalCode">Postal Code</label>
            <input type="text" id="PostalCode" name="PostalCode" value="<?php echo htmlspecialchars($user['PostalCode']); ?>" placeholder="2193">

            <div class="form-actions">
                <button type="submit" class="submit-btn">Save Changes</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='profile.php'">Back to Profile</button>
            </div>
        </form>  
    </div>
    <?php include 'includes/footer.php'; ?>
</main>
