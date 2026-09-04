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
// GET HOME INSPECTION INFORMATION
// ==================================================

$sql = "SELECT

            a.ApplicationID,
            a.ApplicationDate,
            a.AdoptionStatus,
            a.HomeInspectionDate,
            a.HomeInspectionTime,

            p.PetID,
            p.Name,
            p.Species,
            p.Breed,
            p.Image

        FROM adoption_applications a

        INNER JOIN pet p
            ON a.PetID = p.PetID

        WHERE a.UserID = ?

        AND a.HomeInspectionDate IS NOT NULL

        ORDER BY a.HomeInspectionDate ASC";


$stmt = $conn->prepare($sql);


// Check whether the query was prepared successfully
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

    <title>Home Inspections | Save-A-Pet HUB</title>


    <!-- Main CSS -->
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

        <h2>My Home Inspections</h2>

        <p class="history-intro">

            View your scheduled home inspections for
            your adoption applications.

        </p>


        <?php

        // Check whether home inspections exist
        if ($result->num_rows > 0) {

            // Display each inspection
            while ($inspection = $result->fetch_assoc()) {

        ?>

                <div class="inspection-card">


                    <!-- Pet image -->

                    <div class="inspection-pet-image">

                        <?php

                        // Display the image if it exists
                        if (!empty($inspection['Image'])) {

                        ?>

                            <img
                                src="<?php
                                echo htmlspecialchars(
                                    $inspection['Image']
                                );
                                ?>"
                                alt="<?php
                                echo htmlspecialchars(
                                    $inspection['Name']
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


                    <!-- Inspection details -->

                    <div class="inspection-details">


                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $inspection['Name']
                            );
                            ?>

                        </h3>


                        <p>

                            <strong>Pet:</strong>

                            <?php

                            echo htmlspecialchars(
                                $inspection['Species']
                            );

                            echo " • ";

                            echo htmlspecialchars(
                                $inspection['Breed']
                            );

                            ?>

                        </p>


                        <div class="inspection-date-box">

                            <strong>

                                🏡 Home Inspection

                            </strong>


                            <p>

                                <strong>Date:</strong>

                                <?php

                                echo date(
                                    "d F Y",
                                    strtotime(
                                        $inspection[
                                            'HomeInspectionDate'
                                        ]
                                    )
                                );

                                ?>

                            </p>


                            <p>

                                <strong>Time:</strong>

                                <?php

                                echo date(
                                    "H:i",
                                    strtotime(
                                        $inspection[
                                            'HomeInspectionTime'
                                        ]
                                    )
                                );

                                ?>

                            </p>

                        </div>


                        <!-- Adoption application status -->

                        <p>

                            <strong>Application Status:</strong>

                            <span
                                class="status-badge
                                <?php
                                echo strtolower(
                                    htmlspecialchars(
                                        $inspection[
                                            'AdoptionStatus'
                                        ]
                                    )
                                );
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $inspection[
                                        'AdoptionStatus'
                                    ]
                                );

                                ?>

                            </span>

                        </p>


                    </div>


                </div>


        <?php

            }

        } else {

        ?>

            <!-- No inspections message -->

            <div class="empty-history">

                <div class="empty-icon">

                    🏡

                </div>


                <h3>

                    No Home Inspections Scheduled

                </h3>


                <p>

                    You currently do not have any
                    scheduled home inspections.

                </p>


                <a
                    href="profile.php"
                    class="action-btn"
                >

                    Back to Profile

                </a>

            </div>


        <?php

        }

        ?>


        <!-- Back to profile button -->

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


<?php include 'includes/footer.php'; ?>


</body>

</html>


<?php

// Close the database statement
$stmt->close();

?>