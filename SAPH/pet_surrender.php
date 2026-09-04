<?php

// Start the session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include database connection
include 'DBConn.php';

// Ensure the user is logged in
if (
    !isset($_SESSION['UserID']) ||
    ($_SESSION['Role'] ?? '') !== 'User'
) {
    header("Location: login.php");
    exit();
}

// Get the logged-in user's ID
$userID = intval($_SESSION['UserID']);

$success = "";
$error = "";


// ================================================
// PROCESS PET SURRENDER FORM
// ================================================

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get form information
    $petName = trim($_POST['PetName']);
    $animalType = trim($_POST['AnimalType']);
    $breed = trim($_POST['Breed']);
    $age = trim($_POST['Age']);
    $gender = trim($_POST['Gender']);

    $reason = trim($_POST['SurrenderReason']);
    $temperament = trim($_POST['Temperament']);
    $medicalInformation = trim($_POST['MedicalInformation']);


    // Calculate surrender fee
    $surrenderFee = 150;

    if ($animalType === "Dog") {

        $surrenderFee = 250;

    } elseif ($animalType === "Cat") {

        $surrenderFee = 200;

    }


    // Check required information
    if (
        empty($petName) ||
        empty($animalType) ||
        empty($reason)
    ) {

        $error = "Please complete all required fields.";

    } else {

        // Insert surrender request into database
        $sql = "INSERT INTO pet_surrenders
                (
                    UserID,
                    PetName,
                    AnimalType,
                    Breed,
                    Age,
                    Gender,
                    SurrenderReason,
                    Temperament,
                    MedicalInformation,
                    SurrenderFee
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);


        if (!$stmt) {

            $error = "Database error: " . $conn->error;

        } else {

            // Bind information to the SQL statement
            $stmt->bind_param(
                "issssssssd",
                $userID,
                $petName,
                $animalType,
                $breed,
                $age,
                $gender,
                $reason,
                $temperament,
                $medicalInformation,
                $surrenderFee
            );


            // Submit the request
            if ($stmt->execute()) {

                $success =
                    "Your pet surrender request has been submitted successfully. " .
                    "The surrender fee is R" .
                    number_format($surrenderFee, 2) . ".";

            } else {

                $error =
                    "Unable to submit your surrender request. Please try again.";

            }


            $stmt->close();
        }
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

    <title>Pet Surrender | Save-A-Pet HUB</title>

    <link
        rel="stylesheet"
        href="includes/assets/css/style.css"
    >

</head>


<body>

<?php include 'includes/navbar.php'; ?>


<main class="profile-page">

    <div class="profile-card">

        <h2>Pet Surrender Request</h2>

        <p class="form-intro">

            Please provide information about the pet you wish to surrender.
            This information will help us review your request.

        </p>


        <!-- Success message -->
        <?php if (!empty($success)) { ?>

            <div class="success-message">

                <?php echo htmlspecialchars($success); ?>

            </div>

        <?php } ?>


        <!-- Error message -->
        <?php if (!empty($error)) { ?>

            <div class="error">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php } ?>


        <form method="POST">


            <!-- Pet name -->
            <label>Pet Name *</label>

            <input
                type="text"
                name="PetName"
                required
            >


            <!-- Animal type -->
            <label>Animal Type *</label>

            <select
                name="AnimalType"
                id="animalType"
                required
            >

                <option value="">
                    Select animal type
                </option>

                <option value="Dog">
                    Dog
                </option>

                <option value="Cat">
                    Cat
                </option>

                <option value="Other">
                    Other
                </option>

            </select>


            <!-- Breed -->
            <label>Breed</label>

            <input
                type="text"
                name="Breed"
            >


            <!-- Age -->
            <label>Age</label>

            <input
                type="text"
                name="Age"
                placeholder="For example: 3 years"
            >


            <!-- Gender -->
            <label>Gender</label>

            <select name="Gender">

                <option value="">
                    Select gender
                </option>

                <option value="Male">
                    Male
                </option>

                <option value="Female">
                    Female
                </option>

            </select>


            <!-- Reason for surrender -->
            <label>Reason for Surrender *</label>

            <textarea
                name="SurrenderReason"
                rows="4"
                required
            ></textarea>


            <!-- Temperament -->
            <label>Pet Temperament</label>

            <textarea
                name="Temperament"
                rows="3"
                placeholder="For example: Friendly, nervous or energetic"
            ></textarea>


            <!-- Medical information -->
            <label>Medical Information</label>

            <textarea
                name="MedicalInformation"
                rows="4"
                placeholder="Please include any known medical conditions"
            ></textarea>


            <!-- Dynamic surrender fee -->
            <div class="surrender-fee">

                <strong>
                    Estimated Surrender Fee:
                </strong>

                <span id="feeAmount">

                    R0.00

                </span>

            </div>


            <div class="form-buttons">

                <button
                    type="submit"
                    class="submit-btn"
                >

                    Submit Request

                </button>


                <button
                    type="button"
                    class="cancel-btn"
                    onclick="window.location.href='profile.php';"
                >

                    Cancel

                </button>

            </div>

        </form>

    </div>

</main>


<?php include 'includes/footer.php'; ?>


<script>

// Get the animal type dropdown
const animalType =
    document.getElementById("animalType");

// Get the fee display area
const feeAmount =
    document.getElementById("feeAmount");


// Update the displayed fee
animalType.addEventListener(
    "change",
    function () {

        let fee = 150;


        // Change the fee depending on the animal type
        if (this.value === "Dog") {

            fee = 250;

        } else if (this.value === "Cat") {

            fee = 200;

        }


        // Display the fee
        feeAmount.textContent =
            "R" + fee.toFixed(2);

    }
);

</script>


</body>

</html>