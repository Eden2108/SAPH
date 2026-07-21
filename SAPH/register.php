<?php
session_start();
include 'DBConn.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $plainPassword = $_POST["password"];

    // Check if email already exists
    $checkStmt = $conn->prepare(
        "SELECT UserID FROM users WHERE email = ?"
    );

    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();

    $result = $checkStmt->get_result();

    if ($result->num_rows > 0) {

        $error = "An account with this email address already exists.";

    } else {

        // Securely hash password
        $hashedPassword = password_hash(
            $plainPassword,
            PASSWORD_DEFAULT
        );

        $stmt = $conn->prepare(
            "INSERT INTO users (name, email, password)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $name,
            $email,
            $hashedPassword
        );

        if ($stmt->execute()) {

            // Store user session
            $_SESSION['UserID'] = $stmt->insert_id;
            $_SESSION['FullName'] = $name;
            $_SESSION['Role'] = "User";

            // Redirect directly to Home Page
            header("Location: index.php");
            exit();

        } else {

            $error = "Registration failed. Please try again.";

        }

        $stmt->close();
    }

    $checkStmt->close();
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

    <title>Register - Save-A-Pet HUB</title>

    <link
        rel="stylesheet"
        type="text/css"
        href="includes/assets/css/style.css"
    >
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main>

    <div class="form-box">

        <h2>Create Account</h2>

        <?php if (!empty($error)): ?>

            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST">

            <label>Name:</label>

            <input
                type="text"
                name="name"
                required
            >

            <label>Email:</label>

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
                Register
            </button>

        </form>

    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>

</html>