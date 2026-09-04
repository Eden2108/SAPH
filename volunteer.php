<?php

include 'DBConn.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $fullName = $_POST['fullName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $interest = $_POST['interest'] ?? '';
    $availability = $_POST['availability'] ?? '';

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );


    /* Insert volunteer application */

    $sql = "INSERT INTO volunteer_application
            (
                FullName,
                Email,
                Password,
                Availability,
                AssignedRole
            )
            VALUES (?, ?, ?, ?, ?)";


    $stmt = $conn->prepare($sql);


    if (!$stmt) {

        die(
            "Prepare failed: " .
            $conn->error
        );

    }


    $stmt->bind_param(

        "sssss",

        $fullName,

        $email,

        $password,

        $availability,

        $interest

    );


    if ($stmt->execute()) {

        $message =
        "Thank you, " .
        htmlspecialchars($fullName) .
        "! Your application has been submitted and is pending admin approval.";

    } else {

        $message =
        "Error: " .
        $conn->error;

    }


    $stmt->close();

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Volunteer Registration | Save-A-Pet Hub
    </title>


    <link
        rel="stylesheet"
        href="includes/assets/css/style.css">

</head>


<body>


<?php include 'includes/navbar.php'; ?>


<main>
    <div class="form-box">
        <h2>Volunteer Registration</h2>

        <p class="form-intro">
            Join Save-A-Pet Hub and help us make a difference in the lives of animals.
        </p>

        <?php if ($message): ?>
            <p class="success"><?php echo $message; ?></p>
        <?php endif; ?>

        <form method="POST">
            <label for="fullName">Full Name</label>
            <input type="text" id="fullName" name="fullName" required>

            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required>

            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone">

            <label for="interest">Area of Interest</label>
            <select id="interest" name="interest" required>
                <option value="" disabled selected>Select an area</option>
                <option>Animal Care</option>
                <option>Dog Walking</option>
                <option>Fundraising</option>
                <option>Events</option>
                <option>Charity Shop</option>
                <option>Administration</option>
            </select>

            <label for="availability">Availability</label>
            <textarea id="availability" name="availability" rows="4"></textarea>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <!-- ✅ Side-by-side buttons -->
            <div class="form-actions">
                <button type="submit" class="submit-btn">Register</button>
                <button type="button" class="cancel-btn" onclick="window.location.href='volunteer_login.php'">
                    Login as Current Volunteer
                </button>
            </div>
        </form>

        <a href="index.php" class="add-item-btn">Back To Home</a>
    </div>
    <?php include 'includes/footer.php'; ?>
</main>
