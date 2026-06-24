<?php
include 'DBConn.php'; // Include database connection file
session_start();      // Start session to store user data

$error = ""; // Variable to hold error messages

/* --- CHECK IF FORM IS SUBMITTED --- */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST["name"];
    $email    = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        $_SESSION['user']  = $name;
        $_SESSION['email'] = $email;
        header("Location: profile.php");
        exit();
    } else {
        $error = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - Save-A-Pet HUB</title>
    <!-- Link to site-wide CSS -->
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?> <!-- Consistent header -->

<main>
    <div class="form-box">
        <h2>Create Account</h2>
        <?php if ($error) echo "<p class='error'>$error</p>"; ?>
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Password:</label>
            <input type="password" name="password" required>

            <button type="submit" class="btn">Register</button>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?> <!-- Consistent footer -->

</body>
</html>

<?php
include 'DBConn.php';// Include database connection file

/* --- DROP TABLE IF EXISTS --- */
$drop = "DROP TABLE IF EXISTS tblUser";
$conn->query($drop);

/* --- CREATE TABLE --- */
$create = "CREATE TABLE tblUser (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
)";
$conn->query($create);

/* --- LOAD DATA FROM TEXT FILE --- */
/* The file userData.txt should be in the same folder as this script.
   Example contents:
   John Doe,jdoe@abc.co.za,29ef52e7563626a96cea74b4085c124c
   Jane Smith,jsmith@abc.co.za,29ef52e7563626a96cea74b4085c124c
*/


$conn->close();
?>
