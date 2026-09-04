<?php

// Start the session if necessary
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include the database connection
include 'DBConn.php';


// ==================================================
// CHECK IF THE USER IS LOGGED IN
// ==================================================

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
// GET THE USER'S ADOPTION APPLICATIONS
// ==================================================

$sql = "SELECT
            a.ApplicationID,
            a.ApplicationDate,
            a.AdoptionStatus,
            a.AdoptionReason,
            a.HomeInspectionDate,
            a.HomeInspectionTime,

            p.PetID,
            p.Name,
            p.Species,
            p.Breed,
            p.Age,
            p.AgeUnit,
            p.HealthStatus,
            p.Colour,
            p.Image

        FROM adoptionapplication a

        INNER JOIN pet p
            ON a.PetID = p.PetID

        WHERE a.UserID = ?

        ORDER BY a.ApplicationDate DESC";


$stmt = $conn->prepare($sql);


// Check whether the SQL statement was prepared successfully
if (!$stmt) {

    die("Database error: " . $conn->error);

}


// Bind the logged-in user's ID
$stmt->bind_param("i", $userID);


// Execute the query
$stmt->execute();


// Get the results
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

    <title>Adoption History | Save-A-Pet HUB</title>


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

        <h2>My Adoption History</h2>

        <p class="history-intro">

            View and track all of your adoption applications.

        </p>


        <?php

        // Check whether the user has submitted applications
        if ($result->num_rows > 0) {

            // Loop through each adoption application
            while ($application = $result->fetch_assoc()) {

        ?>

                <!-- Adoption application card -->

                <div class="adoption-history-card">


                    <!-- Pet image -->

                    <div class="history-pet-image">

                        <?php

                        // Display the pet image if one exists
                        if (!empty($application['Image'])) {

                        ?>

                            <img
                                src="includes/assets/images/<?php
                                echo htmlspecialchars(
                                    $application['Image']
                                );
                                ?>"
                                alt="<?php
                                echo htmlspecialchars(
                                    $application['Name']
                                );
                                ?>"
                            >

                        <?php

                        } else {

                        ?>

                            <div class="no-pet-image">

                                🐾

                            </div>

                        <?php

                        }

                        ?>

                    </div>


                    <!-- Application information -->

                    <div class="history-details">


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $application['Name']
                            );
                            ?>

                        </h3>


                        <p>

                            <strong>Species:</strong>

                            <?php
                            echo htmlspecialchars(
                                $application['Species']
                            );
                            ?>

                        </p>


                        <p>

                            <strong>Breed:</strong>

                            <?php
                            echo htmlspecialchars(
                                $application['Breed']
                            );
                            ?>

                        </p>


                        <p>

                            <strong>Age:</strong>

                            <?php
                            echo htmlspecialchars(
                                $application['Age']
                            );

                            echo " ";

                            echo htmlspecialchars(
                                $application['AgeUnit']
                            );
                            ?>

                        </p>


                        <p>

                            <strong>Application Date:</strong>

                            <?php
                            echo date(
                                "d F Y",
                                strtotime(
                                    $application['ApplicationDate']
                                )
                            );
                            ?>

                        </p>


                        <!-- Adoption status -->

                        <p>

                            <strong>Status:</strong>

                            <span
                                class="status-badge
                                <?php
                                echo strtolower(
                                    htmlspecialchars(
                                        $application['AdoptionStatus']
                                    )
                                );
                                ?>"
                            >

                                <?php
                                echo htmlspecialchars(
                                    $application['AdoptionStatus']
                                );
                                ?>

                            </span>

                        </p>


                        <!-- Adoption reason -->

                        <p class="adoption-reason">

                            <strong>Reason for Adoption:</strong>

                            <br>

                            <?php
                            echo htmlspecialchars(
                                $application['AdoptionReason']
                            );
                            ?>

                        </p>


                        <!-- Home inspection information -->

                        <?php

                        if (
                            !empty(
                                $application['HomeInspectionDate']
                            )
                        ) {

                        ?>

                            <div class="inspection-info">

                                <strong>
                                    🏡 Home Inspection
                                </strong>

                                <p>

                                    <?php

                                    echo date(
                                        "d F Y",
                                        strtotime(
                                            $application[
                                                'HomeInspectionDate'
                                            ]
                                        )
                                    );

                                    ?>

                                    at

                                    <?php

                                    echo htmlspecialchars(
                                        $application[
                                            'HomeInspectionTime'
                                        ]
                                    );

                                    ?>

                                </p>

                            </div>

                        <?php

                        }

                        ?>


                    </div>


                </div>


        <?php

            }

        } else {

        ?>

            <!-- Message shown when there are no applications -->

            <div class="empty-history">

                <div class="empty-icon">

                    🐾

                </div>

                <h3>

                    No Adoption Applications Yet

                </h3>

                <p>

                    You have not submitted any adoption
                    applications yet.

                </p>

                <a
                    href="pet_listings.php"
                    class="action-btn"
                >

                    View Available Pets

                </a>

            </div>


        <?php

        }

        ?>


        <!-- Back button -->

        <a href="profile.php" class="add-item-btn">Back To Profile</a>

        </div>


    </div>

</main>


<!-- Footer -->

<?php include 'includes/footer.php'; ?>


</body>

</html>


<?php

// Close the statement
$stmt->close();

?>