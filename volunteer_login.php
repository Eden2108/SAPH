<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'DBConn.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT VolunteerID, FullName, Email, Availability, 
                   AssignedRole, Password
            FROM volunteer
            WHERE Email = ?";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $volunteer = $result->fetch_assoc();

        // Supports existing plain text passwords
        // and new hashed passwords
        $passwordCorrect =
            password_verify($password, $volunteer['Password'])
            || $password === $volunteer['Password'];

        if ($passwordCorrect) {

            /*
             * If the password is still plain text,
             * automatically convert it to a secure hash.
             */
            if (password_get_info($volunteer['Password'])['algo'] === 0) {

                $hashedPassword = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $updateStmt = $conn->prepare(
                    "UPDATE volunteer 
                     SET Password = ?
                     WHERE VolunteerID = ?"
                );

                $updateStmt->bind_param(
                    "si",
                    $hashedPassword,
                    $volunteer['VolunteerID']
                );

                $updateStmt->execute();
                $updateStmt->close();
            }

            // Volunteer session
            $_SESSION['VolunteerID'] = $volunteer['VolunteerID'];
            $_SESSION['FullName'] = $volunteer['FullName'];
            $_SESSION['Role'] = "Volunteer";

            header("Location: volunteer_pet.php");
            exit();

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "No volunteer account was found with that email.";

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
        content="width=device-width, initial-scale=1.0"
    >

    <title>Volunteer Login - Save-A-Pet HUB</title>

    <link
        rel="stylesheet"
        href="includes/assets/css/style.css"
    >

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main>

    <div class="form-box">

        <h2>Volunteer Login</h2>

        <?php if (!empty($message)): ?>

            <p class="error">
                <?php echo htmlspecialchars($message); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Email Address:</label>

            <input
                type="email"
                name="email"
                required
            >

            <label>Password:</label>

            <input
                type="password"
                name="password"
                required
            >

            <button
                type="submit"
                class="submit-btn"
            >
                Login
            </button>

        </form>

        <a
            href="volunteer.php"
            class="add-item-btn"
        >
            Back to Volunteer Page
        </a>

    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>

</html>