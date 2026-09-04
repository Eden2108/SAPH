<?php
session_start();
include 'DBConn.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $LastName = trim($_POST["LastName"]);
    $email = trim($_POST["email"]);

    $plainPassword = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Check passwords match
    if ($plainPassword !== $confirmPassword) {

        $error = "Passwords do not match.";

    }

    // Continue only if no errors
    if (empty($error)) {

        // Check if email already exists
        $checkStmt = $conn->prepare(
            "SELECT UserID
             FROM users
             WHERE email = ?"
        );

        $checkStmt->bind_param("s", $email);
        $checkStmt->execute();

        $result = $checkStmt->get_result();

        if ($result->num_rows > 0) {

            $error = "An account with this email already exists.";

        } else {

            $hashedPassword = password_hash(
                $plainPassword,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, LastName, email, password, Role)
                VALUES (?, ?, ?, ?, 'User')"
            );

            $stmt->bind_param(
                "ssss",
                $name,
                $LastName,
                $email,
                $hashedPassword
            );

            if ($stmt->execute()) {

                $_SESSION["UserID"] = $stmt->insert_id;
                $_SESSION["FullName"] = $name;
                $_SESSION["LastName"] = $LastName;
                $_SESSION["Role"] = "User";

                header("Location: index.php");
                exit();

            } else {

                $error = "Registration failed. Please try again.";

            }

            $stmt->close();
        }

        $checkStmt->close();
    }
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

    <link rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
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

            <label>Last Name:</label>

            <input
                type="text"
                name="LastName"
                required
            >

            <label>Email:</label>

            <input
                type="email"
                name="email"
                required
            >

          <label>Password:</label>

<div class="password-wrapper">

    <input
        type="password"
        name="password"
        id="password"
        required
    >

    <span
        class="material-symbols-outlined toggle-password"
        id="togglePassword">
        visibility
    </span>

</div>

<p id="passwordStatus" class="password-status"></p>

<p class="password-hint">
Your password needs to be at least 8 characters and include numbers,
lowercase letters, uppercase letters and a special character.
</p>

 

<label>Confirm Password:</label>

<div class="password-wrapper">

    <input
        type="password"
        name="confirm_password"
        id="confirmPassword"
        required
    >

    <span
        class="material-symbols-outlined toggle-password"
        id="toggleConfirm">
        visibility
    </span>

</div>

<p id="matchStatus" class="password-status"></p>


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

<script>

const password = document.getElementById("password");
const confirmPassword = document.getElementById("confirmPassword");

const passwordStatus = document.getElementById("passwordStatus");
const matchStatus = document.getElementById("matchStatus");

const togglePassword = document.getElementById("togglePassword");
const toggleConfirm = document.getElementById("toggleConfirm");


togglePassword.addEventListener("click",function(){

    if(password.type==="password"){

        password.type="text";
        this.textContent="visibility_off";

    }else{

        password.type="password";
        this.textContent="visibility";

    }

});


toggleConfirm.addEventListener("click",function(){

    if(confirmPassword.type==="password"){

        confirmPassword.type="text";
        this.textContent="visibility_off";

    }else{

        confirmPassword.type="password";
        this.textContent="visibility";

    }

});


password.addEventListener("input",validatePassword);
confirmPassword.addEventListener("input",checkMatch);


function validatePassword(){

    let value=password.value;

    let strong=

        value.length>=8 &&
        /[a-z]/.test(value) &&
        /[A-Z]/.test(value) &&
        /\d/.test(value) &&
        /[^A-Za-z0-9]/.test(value);

    password.classList.remove("password-weak");
    password.classList.remove("password-strong");

    if(value.length===0){

        passwordStatus.innerHTML="";

        return;

    }

    if(value.length<8){

        password.classList.add("password-weak");

        passwordStatus.className="password-status status-error";

        passwordStatus.innerHTML="Too short minimum length 8";

    }

    else if(!strong){

        password.classList.add("password-weak");

        passwordStatus.className="password-status status-error";

        passwordStatus.innerHTML="Password weak";

    }

    else{

        password.classList.add("password-strong");

        passwordStatus.className="password-status status-success";

        passwordStatus.innerHTML="✓ Strong password";

    }

    checkMatch();

}


function checkMatch(){

    if(confirmPassword.value===""){

        matchStatus.innerHTML="";
        return;

    }

    if(password.value===confirmPassword.value){

        confirmPassword.classList.remove("password-weak");
        confirmPassword.classList.add("password-strong");

        matchStatus.className="password-status status-success";

        matchStatus.innerHTML="✓ Passwords match";

    }

    else{

        confirmPassword.classList.remove("password-strong");
        confirmPassword.classList.add("password-weak");

        matchStatus.className="password-status status-error";

        matchStatus.innerHTML="Passwords do not match";

    }

}

</script>

</body>

</html>