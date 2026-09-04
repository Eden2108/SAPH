<?php
session_start();
include 'DBConn.php';

$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $reporter_name = trim($_POST['reporter_name']);
    $reporter_email = trim($_POST['reporter_email']);
    $reporter_phone = trim($_POST['reporter_phone']);
    $incident_address = trim($_POST['incident_address']);
    $animal_type = $_POST['animal_type'];
    $abuse_type = $_POST['abuse_type'];
    $incident_description = trim($_POST['incident_description']);
    $incident_date = !empty($_POST['incident_date'])
        ? $_POST['incident_date']
        : NULL;
    $urgency_level = $_POST['urgency_level'];

    $sql = "INSERT INTO abuse_reports
            (
                ReporterName,
                ReporterEmail,
                ReporterPhone,
                IncidentAddress,
                AnimalType,
                AbuseType,
                IncidentDescription,
                IncidentDate,
                UrgencyLevel
            )
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if (!$stmt) {
        die("SQL Prepare Error: " . $conn->error);
    }

    $stmt->bind_param(
        "sssssssss",
        $reporter_name,
        $reporter_email,
        $reporter_phone,
        $incident_address,
        $animal_type,
        $abuse_type,
        $incident_description,
        $incident_date,
        $urgency_level
    );

    if ($stmt->execute()) {
        $message = "Your report has been submitted successfully. Thank you for helping protect animals.";
        $message_class = "success";
    } else {
        $message = "Unable to submit report: " . $stmt->error;
        $message_class = "error";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Report Suspected Animal Abuse | Save-A-Pet Hub
    </title>

    <link rel="stylesheet"
          href="includes/assets/css/style.css">
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<main class="form-container">

    <div class="form-card">

        <h2>Report Suspected Animal Abuse</h2>

        <p class="form-intro">
            If you suspect that an animal is being neglected,
            mistreated, or placed in danger, please provide as
            much information as possible. 
        </p>
<br>
        <?php if (!empty($message)): ?>

            <div class="alert <?php echo $message_class; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php endif; ?>


        <form method="POST"
              action="report_abuse.php">


            <!-- REPORTER INFORMATION -->
<div class="information-box">

                <h3>Important Information:</h3>

                <p>
                    Please contact us if you know of any animal that is being neglected or treated badly. By reporting cruelty, you help to free them from suffering.
                </p>
            <br>
                <p>
                  We will act – but we can only do so if we know about it. We depend on people like you to be our ‘eyes and ears’ in your community.
                </p>
            <br>
                <p>
                    Your personal information will remain confidential.
                    Providing us with contact details allows us to provide you with feedback and also to contact you should we require further information. 
                    <br></br>
                    Please also include what animals your complaint is relating to, how many and if possible a description of the animal(s) to allow us to easily identify them.
            <br></br>
                    SAPHs are obligated by law to admit any animal brought in. No animal may be turned away.
                </p>

            </div>
<br><br>
            <div class="form-section">

                <h3>1. Your Information:</h3>

                <p class="section-description">
                    Your contact information may be used if
                    additional information is required.
                </p>


                <div class="input-group">

                    <label for="reporter_name">
                        Full Name
                    </label>

                    <input
                        type="text"
                        id="reporter_name"
                        name="reporter_name"
                        required
                        placeholder="Enter your full name">

                </div>


                <div class="input-row">

                    <div class="input-group">

                        <label for="reporter_email">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="reporter_email"
                            name="reporter_email"
                            required
                            placeholder="example@email.com">

                    </div>


                    <div class="input-group">

                        <label for="reporter_phone">
                            Phone Number
                        </label>
                <br></br>
                        <input
                            type="tel"
                            id="reporter_phone"
                            name="reporter_phone"
                            placeholder="012 345 6789">

                    </div>

                </div>

            </div>

<br></br>

            <!-- INCIDENT INFORMATION -->

            <div class="form-section">

                <h3>2. Incident Information:</h3>


                <div class="input-group">

                    <label for="incident_address">
                        Location or Address of Incident
                    </label>

                    <input
                        type="text"
                        id="incident_address"
                        name="incident_address"
                        required
                        placeholder="Enter the address or location">

                </div>


                <div class="input-row">

                    <div class="input-group">

                        <label for="animal_type">
                            Type of Animal
                        </label>

                        <select
                            id="animal_type"
                            name="animal_type"
                            required>

                            <option value="" disabled selected>
                                Select animal type
                            </option>

                            <option value="Dog">Dog</option>

                            <option value="Cat">Cat</option>

                            <option value="Bird">Bird</option>

                            <option value="Farm Animal">
                                Farm Animal
                            </option>

                            <option value="Reptile">
                                Reptile
                            </option>

                            <option value="Other">
                                Other
                            </option>

                        </select>

                    </div>


                    <div class="input-group">

                        <label for="incident_date">
                            Date of Incident
                        </label>
                <br></br>
                        <input
                            type="date"
                            id="incident_date"
                            name="incident_date">

                    </div>

                </div>

<br></br>
                <div class="input-group">

                    <label for="abuse_type">
                        Type of Suspected Abuse
                    </label>

                    <select
                        id="abuse_type"
                        name="abuse_type"
                        required>

                        <option value="" disabled selected>
                            Select suspected abuse
                        </option>

                        <option value="Neglect">
                            Neglect
                        </option>

                        <option value="Physical Abuse">
                            Physical Abuse
                        </option>

                        <option value="Lack of Food or Water">
                            Lack of Food or Water
                        </option>

                        <option value="Unsafe Living Conditions">
                            Unsafe Living Conditions
                        </option>

                        <option value="Animal Abandonment">
                            Animal Abandonment
                        </option>

                        <option value="Animal Fighting">
                            Suspected Animal Fighting
                        </option>

                        <option value="Other">
                            Other Concern
                        </option>

                    </select>

                </div>


                <div class="input-group">

                    <label for="incident_description">
                        Describe What You Observed
                    </label>

                    <textarea
                        id="incident_description"
                        name="incident_description"
                        rows="6"
                        required
                        placeholder="Please describe the situation and provide as much detail as possible..."></textarea>

                </div>

            </div>


           <!-- URGENCY -->

<div class="form-section">

    <h3>3. Urgency Assessment:</h3>

    <p class="section-description">
        Please select the option that best describes the current situation of the animal.
    </p>
<br>
    <div class="abuse-urgency-options">

        <div class="abuse-urgency-option">
            <input
                type="radio"
                id="urgency_low"
                name="urgency_level"
                value="Low"
                required>

            <label for="urgency_low">Low - Concern about the animal's welfare, but there is no immediate danger.</label>
        </div>
<br>
        <div class="abuse-urgency-option">
            <input
                type="radio"
                id="urgency_moderate"
                name="urgency_level"
                value="Moderate">

            <label for="urgency_moderate">Moderate - Ongoing neglect, lack of care, or unsafe living conditions.</label>
        </div>
<br>
        <div class="abuse-urgency-option">
            <input
                type="radio"
                id="urgency_high"
                name="urgency_level"
                value="High">

            <label for="urgency_high">High - Ongoing neglect, lack of care, or unsafe living conditions.</label>
        </div>

    </div>

</div>
<br>
            <button
                type="submit"
                class="submit-btn">

                Submit Abuse Report

            </button>

        </form>

    </div>

</main>

 <a href="index.php" class="add-item-btn">Back To Home</a>

<?php include 'includes/footer.php'; ?>

</body>

</html>