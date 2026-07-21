<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'DBConn.php';

$message = "";
$assignments = [];
$volunteer = null;

// Make sure volunteer is logged in
if (!isset($_SESSION['VolunteerID'])) {
    header("Location: volunteer_login.php");
    exit();
}

$volunteer_id = $_SESSION['VolunteerID'];

// Get volunteer details
$sql = "SELECT FullName, Availability, AssignedRole
        FROM volunteer
        WHERE VolunteerID = ?";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("SQL Prepare Error: " . $conn->error);
}

$stmt->bind_param("i", $volunteer_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $volunteer = $result->fetch_assoc();
} else {
    $message = "Volunteer account could not be found.";
}

$stmt->close();

// Get volunteer pet assignments
$sql2 = "SELECT 
            p.Name AS PetName,
            vp.Notes,
            vp.StartDate
         FROM volunteer_pet vp
         LEFT JOIN pet p 
            ON vp.PetID = p.PetID
         WHERE vp.VolunteerID = ?";

$stmt2 = $conn->prepare($sql2);

if (!$stmt2) {
    die("Assignment SQL Error: " . $conn->error);
}

$stmt2->bind_param("i", $volunteer_id);
$stmt2->execute();

$result2 = $stmt2->get_result();

$assignments = $result2->fetch_all(MYSQLI_ASSOC);

$stmt2->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Volunteer Portal - Save-A-Pet HUB</title>

    <link
        rel="stylesheet"
        href="includes/assets/css/style.css"
    >

    <style>

        .volunteer-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 30px;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .volunteer-container h2,
        .volunteer-container h3 {
            text-align: center;
        }

        .volunteer-info {
            background: #f8f5ef;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background-color: #008c8c;
            color: white;
        }

        .no-assignments {
            text-align: center;
            margin: 25px 0;
        }

    </style>

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main class="volunteer-container">

    <h2>Volunteer Portal</h2>

    <?php if (!empty($message)): ?>

        <p class="error">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <?php if ($volunteer): ?>

        <h3>
            Welcome,
            <?php echo htmlspecialchars($volunteer['FullName']); ?>!
        </h3>


        <div class="volunteer-info">

            <p>
                <strong>Availability:</strong>

                <?php
                echo !empty($volunteer['Availability'])
                    ? htmlspecialchars($volunteer['Availability'])
                    : "Not specified";
                ?>
            </p>


            <p>
                <strong>Assigned Role:</strong>

                <?php
                echo !empty($volunteer['AssignedRole'])
                    ? htmlspecialchars($volunteer['AssignedRole'])
                    : "Not assigned";
                ?>
            </p>

        </div>


        <h3>Your Current Pet(s) Assignments:</h3>


        <?php if (!empty($assignments)): ?>

            <table>

                <thead>

                    <tr>
                        <th>Pet Name</th>
                        <th>Notes</th>
                        <th>Start Date</th>
                    </tr>

                </thead>

                <tbody>

                    <?php foreach ($assignments as $assignment): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $assignment['PetName'] ?? 'Unknown Pet'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $assignment['Notes'] ?? 'No notes'
                                );
                                ?>
                            </td>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $assignment['StartDate'] ?? 'Not specified'
                                );
                                ?>
                            </td>

                        </tr>

                    <?php endforeach; ?>

                </tbody>

            </table>

        <?php else: ?>

            <p class="no-assignments">
                You currently have no pets assigned.
            </p>

        <?php endif; ?>


    <?php endif; ?>

</main>
<a href="index.php" class="add-item-btn">Back To Home</a>
<?php include 'includes/footer.php'; ?>

</body>

</html>