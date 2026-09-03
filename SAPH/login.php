<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'DBConn.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'];

    if ($role === 'Admin') {

        $sql = "SELECT 
                    AdminID AS UserID,
                    Username AS name,
                    PermissionLevel AS Role,
                    Password AS PasswordHash
                FROM admin
                WHERE Email = ?";

    } elseif ($role === 'User') {

        $sql = "SELECT 
                    UserID,
                    name,
                    LastName,
                    Role,
                    password AS PasswordHash
                FROM users
                WHERE email = ?";

    } else {
        $error = "Please select a valid role.";
    }

    if (empty($error)) {

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user['PasswordHash'])) {

                $_SESSION['UserID'] = $user['UserID'];

                $_SESSION['FullName'] = isset($user['LastName'])
                    ? $user['name'] . " " . $user['LastName']
                    : $user['name'];

                $_SESSION['Role'] = $user['Role'];

                if ($role === 'Admin') {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: index.php");
                }

                exit();

            } else {
                $error = "Invalid email or password.";
            }

        } else {
            $error = "Invalid email or password.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login - Save-A-Pet HUB</title>

    <link rel="stylesheet" type="text/css"
          href="includes/assets/css/style.css">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main>

    <div class="form-box">

        <h2>Login</h2>

        <?php if (!empty($error)): ?>
            <p class="error">
                <?php echo htmlspecialchars($error); ?>
            </p>
        <?php endif; ?>

        <form method="POST">

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

            <label>Role:</label>

            <select name="role" required>

                <option value="">
                    --Select Role--
                </option>

                <option value="User">
                    User
                </option>

                <option value="Admin">
                    Admin
                </option>

            </select>

            <button
                type="submit"
                class="submit-btn"
            >
                Login
            </button>

        </form>

    </div>

</main>

<?php include 'includes/footer.php'; ?>

</body>

</html>