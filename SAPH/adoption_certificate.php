<?php

// Start the session if it has not already been started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
include 'DBConn.php';


// ==================================================
// CHECK USER ACCESS
// ==================================================

// Ensure that the user is logged in
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
// GET SUCCESSFUL ADOPTION INFORMATION
// ==================================================

// This query gets a pet that has been successfully adopted
// by the currently logged-in user

$sql = "SELECT

            a.ApplicationID,
            a.ApplicationDate,
            a.AdoptionStatus,

            p.Name,
            p.Species,
            p.Breed,
            p.Image

        FROM adoptionapplication a

        INNER JOIN pet p
            ON a.PetID = p.PetID

        WHERE a.UserID = ?

        AND a.AdoptionStatus = 'Approved'

        ORDER BY a.ApplicationDate DESC

        LIMIT 1";


$stmt = $conn->prepare($sql);


// Check if the query was prepared successfully
if (!$stmt) {

    die("Database error: " . $conn->error);

}


// Bind the logged-in user's ID
$stmt->bind_param("i", $userID);


// Execute the query
$stmt->execute();


// Get the result
$result = $stmt->get_result();


// Fetch the adoption information
$adoption = $result->fetch_assoc();


// Close the statement
$stmt->close();

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Adoption Certificate | Save-A-Pet HUB</title>


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


    <div class="certificate-container">


        <?php if ($adoption) { ?>


            <!-- ==========================================
                 ADOPTION CERTIFICATE
            =========================================== -->

            <div class="adoption-certificate">


                <div class="certificate-header">

                    <div class="certificate-paw">

                        🐾

                    </div>


                    <h1>

                        Certificate of Adoption

                    </h1>


                    <p>

                        Save-A-Pet HUB

                    </p>

                </div>


                <div class="certificate-content">


                    <p class="certificate-intro">

                        This certificate is proudly presented to

                    </p>


                    <!-- User's full name -->

                    <h2 class="certificate-user-name">

                        <?php

                        echo htmlspecialchars(
                            $_SESSION['FullName'] . " " .
                            $_SESSION['LastName']
                        );

                        ?>

                    </h2>


                    <p class="certificate-text">

                        In recognition of providing a loving
                        home to

                    </p>


                    <!-- Pet name -->

                    <h2 class="certificate-pet-name">

                        <?php

                        echo htmlspecialchars(
                            $adoption['Name']
                        );

                        ?>

                    </h2>


                    <!-- Pet information -->

                    <p class="certificate-pet-info">

                        <?php

                        echo htmlspecialchars(
                            $adoption['Breed']
                        );

                        ?>

                        <?php

                        echo htmlspecialchars(
                            $adoption['Species']
                        );

                        ?>

                    </p>


                    <?php

                    // Display the pet's image if available
                    if (!empty($adoption['Image'])) {

                    ?>

                        <img
                            src="<?php
                            echo htmlspecialchars(
                                $adoption['Image']
                            );
                            ?>"
                            alt="<?php
                            echo htmlspecialchars(
                                $adoption['Name']
                            );
                            ?>"
                            class="certificate-pet-image"
                        >

                    <?php

                    }

                    ?>


                    <p class="certificate-message">

                        Thank you for choosing to give an animal
                        a second chance and a forever home.
                        Your kindness has made a difference.

                    </p>


                    <!-- Adoption date -->

                    <p class="certificate-date">

                        <strong>

                            Adoption Application Approved:

                        </strong>

                        <?php

                        echo date(
                            "d F Y",
                            strtotime(
                                $adoption['ApplicationDate']
                            )
                        );

                        ?>

                    </p>


                </div>


                <div class="certificate-footer">

                    <p>

                        With love from Save-A-Pet HUB ❤️🐾

                    </p>

                </div>


            </div>


            <!-- Back to profile -->

            <div class="certificate-buttons">


                <a
                    href="profile.php"
                    class="cancel-btn certificate-back"
                >

                    Back to Profile

                </a>


            </div>


        <?php } else { ?>


            <!-- ==========================================
                 NO ADOPTION CERTIFICATE
            =========================================== -->

            <div class="empty-history">


                <div class="empty-icon">

                    📄🐾

                </div>


                <h3>

                    No Adoption Certificate Yet

                </h3>


                <p>

                    Your adoption certificate will become
                    available once your adoption application
                    has been successfully approved.

                </p>


                <a
                    href="my_applications.php"
                    class="action-btn"
                >

                    View Adoption Applications

                </a>


            </div>


            <div class="certificate-buttons">


                <a
                    href="profile.php"
                    class="cancel-btn certificate-back"
                >

                    Back to Profile

                </a>


            </div>


        <?php } ?>


    </div>


</main>


<!-- Footer -->

<?php include 'includes/footer.php'; ?>


</body>

</html>