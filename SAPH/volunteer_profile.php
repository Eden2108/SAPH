<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'DBConn.php';

if (!isset($_SESSION['VolunteerID'])) {
    header("Location: volunteer_login.php");
    exit();
}

$volunteerID = $_SESSION['VolunteerID'];
$message = "";
$messageClass = "";


// GET VOLUNTEER DETAILS

$sql = "SELECT VolunteerID, FullName, Email, Availability, AssignedRole
        FROM volunteer
        WHERE VolunteerID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteerID);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    header("Location: logout.php");
    exit();
}

$volunteer = $result->fetch_assoc();

$stmt->close();


// UPDATE VOLUNTEER PROFILE

if (isset($_POST['update_profile'])) {

    $fullName = trim($_POST['full_name']);
    $availability = trim($_POST['availability']);

    $sql = "UPDATE volunteer
            SET FullName = ?, Availability = ?
            WHERE VolunteerID = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssi",
        $fullName,
        $availability,
        $volunteerID
    );

    if ($stmt->execute()) {

        $_SESSION['FullName'] = $fullName;

        $message = "Profile updated successfully.";
        $messageClass = "success";

        $volunteer['FullName'] = $fullName;
        $volunteer['Availability'] = $availability;

    } else {

        $message = "Unable to update profile.";
        $messageClass = "error";
    }

    $stmt->close();
}


// CHANGE PASSWORD

if (isset($_POST['change_password'])) {

    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $sql = "SELECT Password
            FROM volunteer
            WHERE VolunteerID = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $volunteerID);
    $stmt->execute();

    $passwordResult = $stmt->get_result();
    $passwordData = $passwordResult->fetch_assoc();

    $stmt->close();


    if (!password_verify(
        $currentPassword,
        $passwordData['Password']
    )) {

        $message = "Current password is incorrect.";
        $messageClass = "error";

    } elseif ($newPassword !== $confirmPassword) {

        $message = "New passwords do not match.";
        $messageClass = "error";

    } elseif (strlen($newPassword) < 8) {

        $message = "Password must contain at least 8 characters.";
        $messageClass = "error";

    } else {

        $hashedPassword = password_hash(
            $newPassword,
            PASSWORD_DEFAULT
        );

        $sql = "UPDATE volunteer
                SET Password = ?
                WHERE VolunteerID = ?";

        $stmt = $conn->prepare($sql);

        $stmt->bind_param(
            "si",
            $hashedPassword,
            $volunteerID
        );

        if ($stmt->execute()) {

            $message = "Password changed successfully.";
            $messageClass = "success";

        } else {

            $message = "Unable to change password.";
            $messageClass = "error";
        }

        $stmt->close();
    }
}


// GET PET ASSIGNMENTS

$sql = "SELECT
            p.Name AS PetName,
            vp.Notes,
            vp.StartDate
        FROM volunteer_pet vp
        LEFT JOIN pet p
            ON vp.PetID = p.PetID
        WHERE vp.VolunteerID = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $volunteerID);
$stmt->execute();

$assignments = $stmt->get_result();

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

    <title>
        Volunteer Profile | Save-A-Pet Hub
    </title>

    <link
        rel="stylesheet"
        href="includes/assets/css/style.css"
    >

</head>


<body>


<?php include 'includes/navbar.php'; ?>


<main class="profile-container">


    <!-- PROFILE HEADER -->

    <div class="profile-header">

        <div class="profile-avatar">
            👤
        </div>

        <div>

            <h2>
                <?php
                echo htmlspecialchars(
                    $volunteer['FullName']
                );
                ?>
            </h2>

            <p>Save-A-Pet Hub Volunteer</p>

        </div>

    </div>



    <?php if (!empty($message)): ?>

        <div class="alert <?php echo $messageClass; ?>">

            <?php
            echo htmlspecialchars($message);
            ?>

        </div>

    <?php endif; ?>



    <div class="profile-grid">


        <!-- VOLUNTEER INFORMATION -->

        <section class="profile-card">

            <h3>Volunteer Information</h3>


            <form method="POST">


                <div class="input-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="full_name"
                        value="<?php
                        echo htmlspecialchars(
                            $volunteer['FullName']
                        );
                        ?>"
                        required
                    >

                </div>


                <div class="input-group">

                    <label>Email Address</label>

                    <input
                        type="email"
                        value="<?php
                        echo htmlspecialchars(
                            $volunteer['Email']
                        );
                        ?>"
                        readonly
                    >

                </div>


                <div class="input-group">

                    <label>Availability</label>

                    <textarea
                        name="availability"
                        rows="4"
                        placeholder="Example: Monday to Friday after 14:00"
                    ><?php
                    echo htmlspecialchars(
                        $volunteer['Availability'] ?? ''
                    );
                    ?></textarea>

                </div>


                <div class="input-group">

                    <label>Assigned Role</label>

                    <input
                        type="text"
                        value="<?php
                        echo htmlspecialchars(
                            $volunteer['AssignedRole']
                            ?? 'Awaiting Assignment'
                        );
                        ?>"
                        readonly
                    >

                </div>


                <button
                    type="submit"
                    name="update_profile"
                    class="submit-btn"
                >

                    Update Profile

                </button>

            </form>

        </section>



        <!-- CHANGE PASSWORD -->

        <section class="profile-card">

            <h3>Security</h3>

            <p class="section-description">
                Change your volunteer account password.
            </p>


            <form method="POST">


                <div class="input-group">

                    <label>Current Password</label>

                    <input
                        type="password"
                        name="current_password"
                        required
                    >

                </div>


                <div class="input-group">

                    <label>New Password</label>

                    <input
                        type="password"
                        name="new_password"
                        minlength="8"
                        required
                    >

                </div>


                <div class="input-group">

                    <label>Confirm New Password</label>

                    <input
                        type="password"
                        name="confirm_password"
                        minlength="8"
                        required
                    >

                </div>


                <button
                    type="submit"
                    name="change_password"
                    class="submit-btn"
                >

                    Change Password

                </button>

            </form>

        </section>


    </div>



    <!-- PET ASSIGNMENTS -->

    <section class="profile-card assignment-section">

        <h3>My Pet Assignments</h3>

        <p class="section-description">
            View animals currently assigned to you.
        </p>


        <?php if ($assignments->num_rows > 0): ?>


            <div class="table-responsive">

                <table>

                    <thead>

                        <tr>

                            <th>Pet Name</th>

                            <th>Assignment Notes</th>

                            <th>Start Date</th>

                        </tr>

                    </thead>


                    <tbody>


                    <?php while ($assignment = $assignments->fetch_assoc()): ?>


                        <tr>

                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $assignment['PetName']
                                    ?? 'Pet record unavailable'
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $assignment['Notes']
                                    ?? 'No notes'
                                );
                                ?>

                            </td>


                            <td>

                                <?php
                                echo htmlspecialchars(
                                    $assignment['StartDate']
                                );
                                ?>

                            </td>

                        </tr>


                    <?php endwhile; ?>


                    </tbody>

                </table>

            </div>


        <?php else: ?>


            <div class="empty-state">

                <h4>No Pet Assignments Yet</h4>

                <p>
                    You currently have no animals assigned to
                    your volunteer profile.
                </p>

            </div>


        <?php endif; ?>


    </section>



    <!-- LOGOUT -->

    <div class="profile-actions">

        <a
            href="index.php"
            class="add-item-btn"
        >
            Back to Home
        </a>


        <a
            href="logout.php"
            class="logout-btn"
        >
            Logout
        </a>

    </div>


</main>


<?php include 'includes/footer.php'; ?>


</body>

</html>