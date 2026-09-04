<?php

// Start the session if necessary
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
include 'DBConn.php';


// ==================================================
// CHECK USER ACCESS
// ==================================================

// Ensure the user is logged in
if (
    !isset($_SESSION['UserID']) ||
    ($_SESSION['Role'] ?? '') !== 'User'
) {

    header("Location: login.php");
    exit();

}


// Get the currently logged-in user's ID
$userID = intval($_SESSION['UserID']);


// ==================================================
// GET USER DONATION HISTORY
// ==================================================

$sql = "SELECT
            DonationID,
            Amount,
            DonationDate,
            PaymentMethod

        FROM donations

        WHERE UserID = ?

        ORDER BY DonationDate DESC";


$stmt = $conn->prepare($sql);


// This is to check if the SQL statement was prepared correctly
if (!$stmt) {

    die("Database error: " . $conn->error);

}


// Bind the currently logged-in user's ID
$stmt->bind_param("i", $userID);


// Execute the query
$stmt->execute();


// Get the donation records
$result = $stmt->get_result();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Donation History | Save-A-Pet HUB</title>


    <!-- Main stylesheet -->

    <link
        rel="stylesheet"
        href="includes/assets/css/style.css"
    >

</head>


<body>


<!-- Navigation bar -->

<?php include 'includes/navbar.php'; ?>


<main class="profile-page">

    <div class="history-container">


        <h2>My Donation History</h2>


        <p class="history-intro">

            Thank you for supporting the animals at
            Save-A-Pet HUB. View your previous donations below.

        </p>


        <?php

        // Check whether the user has made donations
        if ($result->num_rows > 0) {

            // Display every donation
            while ($donation = $result->fetch_assoc()) {

        ?>

                <!-- Donation card -->

                <div class="donation-history-card">


                    <!-- Donation icon -->

                    <div class="donation-icon">

                        💖

                    </div>


                    <!-- Donation information -->

                    <div class="donation-details">


                        <h3>

                            Donation

                        </h3>


                        <p>

                            <strong>Amount:</strong>

                            R<?php
                            echo number_format(
                                $donation['Amount'],
                                2
                            );
                            ?>

                        </p>


                        <p>

                            <strong>Donation Date:</strong>

                            <?php

                            echo date(
                                "d F Y",
                                strtotime(
                                    $donation['DonationDate']
                                )
                            );

                            ?>

                        </p>


                        <p>

                            <strong>Payment Method:</strong>

                            <?php
                            echo htmlspecialchars(
                                $donation['PaymentMethod']
                            );
                            ?>

                        </p>


                    </div>


                </div>


        <?php

            }

        } else {

        ?>

            <!-- Display this when the user has no donations -->

            <div class="empty-history">


                <div class="empty-icon">

                    💖

                </div>


                <h3>

                    No Donations Yet

                </h3>


                <p>

                    You have not made any donations yet.

                    Every contribution can help provide
                    food, shelter and care for animals.

                </p>


                <a
                    href="donate.php"
                    class="action-btn"
                >

                    Make a Donation

                </a>


            </div>


        <?php

        }

        ?>


        <!-- Back to profile -->

        <div class="history-back">

            <a
                href="profile.php"
                class="cancel-btn history-back-btn"
            >

                Back to Profile

            </a>

        </div>


    </div>

</main>


<!-- Footer -->

<?php include 'includes/footer.php'; ?>


</body>

</html>


<?php

// Close the database statement
$stmt->close();

?>