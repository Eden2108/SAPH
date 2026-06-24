<?php
include 'DBConn.php';
session_start();
$error = "";
// PHP logic here...
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Save-A-Pet HUB</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?> <!-- Consistent header -->

<main>
    <div class="form-box">
        <h2>Login</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?> <!-- Consistent footer -->

</body>
</html>
