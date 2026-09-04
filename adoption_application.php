<?php
session_start();

include 'DBConn.php';

$message = "";
$message_class = "";

$pet_id = isset($_GET['pet_id']) ? intval($_GET['pet_id']) : 0;
$pet_name = "Selected Pet";

$user_name = $_SESSION['user'] ?? "";
$user_email = $_SESSION['email'] ?? "";
$user_phone = "";
$user_address = "";


/* =========================================
   GET LOGGED-IN USER DETAILS
========================================= */

$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id > 0) {

    $user_stmt = $conn->prepare("
        SELECT name, LastName, email, PhoneNumber, Address
        FROM users
        WHERE UserID = ?
    ");

    if ($user_stmt) {

        $user_stmt->bind_param("i", $user_id);
        $user_stmt->execute();

        $user_result = $user_stmt->get_result();

        if ($user = $user_result->fetch_assoc()) {

            $user_name = trim(
                $user['name'] . " " . ($user['LastName'] ?? "")
            );

            $user_email = $user['email'];
            $user_phone = $user['PhoneNumber'] ?? "";
            $user_address = $user['Address'] ?? "";
        }

        $user_stmt->close();
    }
}


/* =========================================
   GET PET DETAILS
========================================= */

if ($pet_id > 0) {

    $stmt = $conn->prepare("
        SELECT Name
        FROM pet
        WHERE PetID = ?
    ");

    if ($stmt) {

        $stmt->bind_param("i", $pet_id);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            $pet_name = $row['Name'];
        }

        $stmt->close();
    }
}


/* =========================================
   HANDLE FORM SUBMISSION
========================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $user_name = trim($_POST['user_name']);
    $user_email = trim($_POST['user_email']);
    $user_phone = trim($_POST['user_phone']);
    $user_address = trim($_POST['user_address']);

    $pet_id_hidden = intval($_POST['pet_id']);

    $housing_type = $_POST['housing_type'];
    $yard_fenced = $_POST['yard_fenced'];
    $hours_alone = intval($_POST['hours_alone']);
    $has_experience = $_POST['has_experience'];

    $adoption_reason = trim($_POST['adoption_reason']);

    $inspection_date = $_POST['inspection_date'];
    $inspection_time = $_POST['inspection_time'];


    /* =====================================
       CHECK USER IS LOGGED IN
    ===================================== */

    if ($user_id == 0) {

        $message = "Please login before submitting an adoption application.";
        $message_class = "error";

    } elseif ($pet_id_hidden == 0) {

        $message = "Please select a pet before submitting an application.";
        $message_class = "error";

    } else {


        /* =====================================
           DETERMINE APPLICATION STATUS
        ===================================== */

        if (
            $housing_type === "Apartment" &&
            $yard_fenced === "No"
        ) {

            $status = "Pending Review";

        } else {

            $status = "Submitted";
        }


        /* =====================================
           UPDATE USER CONTACT DETAILS
        ===================================== */

        $update_user = $conn->prepare("
            UPDATE users
            SET PhoneNumber = ?,
                Address = ?
            WHERE UserID = ?
        ");

        if ($update_user) {

            $update_user->bind_param(
                "ssi",
                $user_phone,
                $user_address,
                $user_id
            );

            $update_user->execute();
            $update_user->close();
        }


        /* =====================================
           INSERT ADOPTION APPLICATION
        ===================================== */

        $sql = "
            INSERT INTO adoptionapplication
            (
                ApplicationDate,
                AdoptionStatus,
                AdoptionReason,
                UserID,
                PetID,
                InspectionDate,
                InspectionTime
            )
            VALUES
            (
                CURDATE(),
                ?,
                ?,
                ?,
                ?,
                ?,
                ?
            )
        ";


        $stmt = $conn->prepare($sql);


        if (!$stmt) {

            die("SQL Prepare Error: " . $conn->error);

        }


        $stmt->bind_param(
            "ssiiss",
            $status,
            $adoption_reason,
            $user_id,
            $pet_id_hidden,
            $inspection_date,
            $inspection_time
        );


        if ($stmt->execute()) {

            header("Location: index.php");
            exit();

        } else {

            $message = "Error submitting application: " . $stmt->error;
            $message_class = "error";
        }


        $stmt->close();
    }
}

$conn->close();
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
    Adoption Application | Save-A-Pet HUB
</title>

<link
    rel="stylesheet"
    type="text/css"
    href="includes/assets/css/style.css"
>


<style>

/* =========================================
   ADOPTION PAGE
========================================= */

.adoption-container {
    max-width: 950px;
    margin: 50px auto;
    padding: 0 25px;
}

.adoption-card {
    background: #ffffff;
    padding: 45px;
    border-radius: 18px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
}

.adoption-card h2 {
    text-align: center;
    color: #e39135;
    margin-bottom: 15px;
    font-size: 32px;
}

.pet-application-title {
    text-align: center;
    color: #008b8b;
    font-size: 18px;
    margin-bottom: 40px;
}


/* =========================================
   FORM SECTIONS
========================================= */

.form-section {
    margin-bottom: 45px;
}

.form-section h3 {
    color: #e39135;
    margin-bottom: 25px;
    font-size: 21px;
}

.form-description {
    color: #555;
    line-height: 1.6;
    margin-bottom: 25px;
}


/* =========================================
   INPUTS
========================================= */

.input-group {
    margin-bottom: 25px;
}

.input-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 9px;
    color: #333;
}

.input-group input,
.input-group select,
.input-group textarea {
    width: 100%;
    padding: 14px 16px;
    border: 1px solid #d8ccc0;
    border-radius: 10px;
    font-size: 16px;
    box-sizing: border-box;
    background: #ffffff;
    font-family: inherit;
}

.input-group textarea {
    resize: vertical;
    min-height: 110px;
}

.input-group input:focus,
.input-group select:focus,
.input-group textarea:focus {
    outline: none;
    border-color: #008b8b;
    box-shadow: 0 0 0 3px rgba(0, 139, 139, 0.10);
}

.input-row {
    display: flex;
    gap: 25px;
}

.input-row .input-group {
    flex: 1;
}


/* =========================================
   RADIO BUTTONS
========================================= */

.radio-group {
    display: flex;
    flex-direction: column;
    gap: 14px;
    margin-top: 12px;
}

.radio-group label {
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: normal;
    margin: 0;
    cursor: pointer;
}

.radio-group input[type="radio"] {
    width: auto;
    margin: 0;
}


/* =========================================
   ADOPTION FEE INFORMATION
========================================= */

.adoption-fee-card {
    background: #fff8ee;
    border-left: 5px solid #e39135;
    padding: 28px;
    border-radius: 12px;
    margin-bottom: 40px;
}

.adoption-fee-card h3 {
    color: #e39135;
    margin-top: 0;
    margin-bottom: 18px;
}

.adoption-fee-card p {
    color: #555;
    line-height: 1.7;
}

.fee-list {
    display: flex;
    gap: 15px;
    margin: 25px 0;
}

.fee-item {
    flex: 1;
    background: #ffffff;
    padding: 20px;
    border-radius: 10px;
    text-align: center;
    border: 1px solid #ead8c4;
}

.fee-icon {
    display: block;
    font-size: 30px;
    margin-bottom: 8px;
}

.fee-item span {
    display: block;
}

.fee-item strong {
    display: block;
    color: #008b8b;
    margin-top: 8px;
    font-size: 20px;
}

.fee-note {
    font-size: 14px;
    font-style: italic;
}


/* =========================================
   INSPECTION
========================================= */

.inspection-note {
    display: block;
    margin-top: 8px;
    color: #777;
    font-size: 14px;
}


/* =========================================
   ALERTS
========================================= */

.alert {
    padding: 15px;
    margin-bottom: 25px;
    border-radius: 8px;
    text-align: center;
}

.alert.error {
    background: #ffe4e4;
    color: #a00000;
}

.alert.success {
    background: #e1f5e8;
    color: #176b37;
}


/* =========================================
   BUTTON
========================================= */

.submit-btn {
    width: 100%;
    padding: 15px;
    border: none;
    border-radius: 10px;
    background: #008b8b;
    color: white;
    font-size: 17px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s;
}

.submit-btn:hover {
    background: #006f6f;
    transform: translateY(-2px);
}


/* =========================================
   MOBILE
========================================= */

@media (max-width: 700px) {

    .adoption-card {
        padding: 30px 22px;
    }

    .input-row {
        flex-direction: column;
        gap: 0;
    }

    .fee-list {
        flex-direction: column;
    }
}

</style>

</head>


<body>


<?php include 'includes/navbar.php'; ?>


<main class="adoption-container">

<div class="adoption-card">


<h2>Adoption Application Form</h2>

<p class="pet-application-title">
    Applying to adopt:
    <strong>
        <?php echo htmlspecialchars($pet_name); ?>
    </strong>
</p>


<?php if (!empty($message)): ?>

<div class="alert <?php echo $message_class; ?>">

    <?php echo htmlspecialchars($message); ?>

</div>

<?php endif; ?>


<form method="POST">


<input
    type="hidden"
    name="pet_id"
    value="<?php echo $pet_id; ?>"
>


<!-- =====================================
     1. PERSONAL INFORMATION
===================================== -->

<div class="form-section">

<h3>1. Personal Information</h3>


<div class="input-group">

<label for="user_name">
    Full Name
</label>

<input
    type="text"
    id="user_name"
    name="user_name"
    value="<?php echo htmlspecialchars($user_name); ?>"
    placeholder="Harry Smith"
    required
>

</div>


<div class="input-row">


<div class="input-group">

<label for="user_email">
    Email Address
</label>

<input
    type="email"
    id="user_email"
    name="user_email"
    value="<?php echo htmlspecialchars($user_email); ?>"
    placeholder="john@example.com"
    required
>

</div>


<div class="input-group">

<label for="user_phone">
    Phone Number
</label>

<input
    type="tel"
    id="user_phone"
    name="user_phone"
    value="<?php echo htmlspecialchars($user_phone); ?>"
    placeholder="012 345 6789"
    required
>

</div>


</div>

</div>


<!-- =====================================
     2. ELIGIBILITY & LIVING ENVIRONMENT
===================================== -->

<div class="form-section">

<h3>2. Eligibility & Living Environment</h3>


<div class="input-group">

<label for="user_address">
    Home Address
</label>

<textarea
    id="user_address"
    name="user_address"
    placeholder="e.g. 123 Main Street, Johannesburg"
    required
><?php echo htmlspecialchars($user_address); ?></textarea>

</div>


<div class="input-group">

<label for="housing_type">
    What type of housing do you live in?
</label>

<select
    id="housing_type"
    name="housing_type"
    required
>

<option value="" disabled selected>
    --Select an option--
</option>

<option value="House">
    House
</option>

<option value="Apartment">
    Apartment
</option>

<option value="Complex/Townhouse">
    Complex / Townhouse
</option>

</select>

</div>


<div class="input-group">

<label>
    Is your yard fully fenced or secured?
</label>


<div class="radio-group">

<label>
<input
    type="radio"
    name="yard_fenced"
    value="Yes"
    required
>
Yes
</label>


<label>
<input
    type="radio"
    name="yard_fenced"
    value="No"
>
No
</label>


<label>
<input
    type="radio"
    name="yard_fenced"
    value="Partial"
>
Partially Fenced
</label>


<label>
<input
    type="radio"
    name="yard_fenced"
    value="N/A"
>
No Yard / Apartment
</label>

</div>

</div>


<div class="input-row">


<div class="input-group">

<label for="hours_alone">
    Average hours the pet will be left alone daily
</label>

<input
    type="number"
    id="hours_alone"
    name="hours_alone"
    min="0"
    max="24"
    placeholder="e.g. 4"
    required
>

</div>


<div class="input-group">

<label for="has_experience">
    Do you have previous pet experience?
</label>

<select
    id="has_experience"
    name="has_experience"
    required
>

<option value="" disabled selected>
    --Select an option--
</option>

<option value="First-time owner">
    First-time owner
</option>

<option value="Experienced">
    Experienced owner
</option>

</select>

</div>


</div>

</div>


<!-- =====================================
     3. ADOPTION MOTIVATION
===================================== -->

<div class="form-section">

<h3>3. Adoption Motivation</h3>

<p class="form-description">
    Every adoption journey is different. Please tell us why you
    would like to provide this pet with a forever home.
</p>


<div class="input-group">

<label for="adoption_reason">
    Why would you like to adopt a pet?
</label>

<textarea
    id="adoption_reason"
    name="adoption_reason"
    rows="5"
    placeholder="Tell us why you would like to adopt this pet and how you plan to provide a loving and suitable home..."
    required
></textarea>

</div>

</div>


<!-- =====================================
   4. HOME INSPECTION APPOINTMENT
===================================== -->

<div class="form-section">

<h3>4. Home Inspection Appointment</h3>

<p class="form-description">
    Please select your preferred date and time for an SAPH inspector to conduct a
    home inspection at the address provided above.
</p>


<div class="input-row">


<div class="input-group">

<label for="inspection_date">
    Preferred Inspection Date
</label>

<input
    type="date"
    id="inspection_date"
    name="inspection_date"
    min="<?php echo date('d-m-Y'); ?>"
    required
>

</div>


<div class="input-group">

<label for="inspection_time">
    Preferred Inspection Time
</label>

<input
    type="time"
    id="inspection_time"
    name="inspection_time"
    min="08:00"
    max="16:30"
    required
>

<span class="inspection-note">
    Home inspections are available between 08:00 and 16:30.
</span>

</div>


</div>

</div>


<!-- =====================================
     ADOPTION FEE INFORMATION
===================================== -->

<div class="adoption-fee-card">

<h3>🐾 DID YOU KNOW?</h3>

<p>
    During their stay in our care, animals are given exercise, playtime and enrichment activities to help keep them happy, active and stimulated.
</p>
<br>
<p>
    No SAPH "sells" pets. The adoption fee simply contributes towards part of the veterinary and care expenses involved in getting your furry friend adoption ready.
</p>


<div class="fee-list">


<div class="fee-item">

<span class="fee-icon">🐶</span>

<span>Dogs</span>

<strong>R1 000</strong>

</div>


<div class="fee-item">

<span class="fee-icon">🐱</span>

<span>Cats</span>

<strong>R750</strong>

</div>


<div class="fee-item">

<span class="fee-icon">🐰</span>

<span>Other Pets</span>

<strong>Fee May Vary</strong>

</div>


</div>


<p class="fee-note">
    Adoption fees contribute towards only a fraction of the
    veterinary care and procedures provided before an animal
    is ready for adoption.
</p>

</div>


<button
    type="submit"
    class="submit-btn"
>
    Submit Adoption Application
</button>


</form>

</div>

<a href="pet_listings.php" class="add-item-btn">Back to Available Pets</a>


<?php include 'includes/footer.php'; ?>


</body>

</html>