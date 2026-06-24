<?php
// application_form.php
session_start();

// Database connection (Adjust variables if using WAMP/MySQL defaults)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "save_a_pet_hub"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the pet ID from the URL if coming from the listing page
$pet_id = isset($_GET['pet_id']) ? intval($_GET['pet_id']) : 0;
$pet_name = "Selected Pet";

if ($pet_id > 0) {
    $stmt = $conn->prepare("SELECT name FROM pets WHERE id = ?");
    $stmt->bind_param("i", $pet_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $pet_name = $row['name'];
    }
    $stmt->close();
}

// Handle Form Submission
$message = "";
$message_class = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect user details and eligibility answers
    $user_name = mysqli_real_escape_string($conn, $_POST['user_name']);
    $user_email = mysqli_real_escape_string($conn, $_POST['user_email']);
    $user_phone = mysqli_real_escape_string($conn, $_POST['user_phone']);
    $pet_id_hidden = intval($_POST['pet_id']);
    
    $has_experience = mysqli_real_escape_string($conn, $_POST['has_experience']);
    $housing_type = mysqli_real_escape_string($conn, $_POST['housing_type']);
    $yard_fenced = mysqli_real_escape_string($conn, $_POST['yard_fenced']);
    $hours_alone = intval($_POST['hours_alone']);

    // Basic server-side eligibility check example
    if ($housing_type === 'Apartment' && $yard_fenced === 'No') {
        $status = "Pending Review (Requires Check)";
    } else {
        $status = "Submitted";
    }

    // Insert into applications table (Ensure this table exists in your DB)
    $sql = "INSERT INTO applications (pet_id, applicant_name, applicant_email, applicant_phone, housing_type, yard_fenced, hours_alone, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssssis", $pet_id_hidden, $user_name, $user_email, $user_phone, $housing_type, $yard_fenced, $hours_alone, $status);
    
    if ($stmt->execute()) {
        $message = "Application submitted successfully! We will review your eligibility.";
        $message_class = "success";
    } else {
        $message = "Error submitting application: " . $conn->error;
        $message_class = "error";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adoption Application | Save a Pet Hub</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?> <!-- Consistent header -->

<div class="form-container">
    <div class="form-card">
        <h2>Adoption Application Form</h2>
    

        <?php if (!empty($message)): ?>
            <div class="alert <?php echo $message_class; ?>">
                <?php echo $message; ?>
            </div>
        <?php endif; ?>

        <form action="application_form.php" method="POST">
            <input type="hidden" name="pet_id" value="<?php echo $pet_id; ?>">

            <div class="form-section">
                <h3>1. Personal Information</h3>
                <div class="input-group">
                    <label for="user_name">Full Name</label>
                    <input type="text" id="user_name" name="user_name" required placeholder="Harry Smith">
                </div>
                <div class="input-row">
                    <div class="input-group">
                        <label for="user_email">Email Address</label>
                        <input type="email" id="user_email" name="user_email" required placeholder="john@example.com">
                    </div>
                    <div class="input-group">
                        <label for="phone">Phone Number</label><br>
                        <input type="tel" id="user_phone" name="phone" required placeholder="012 345 6789"><br></br>
                    </div>
                </div>
            </div>

            
            <div class="form-section">
                <h3>2. Eligibility & Living Environment</h3>
                
                <div class="input-group">
                    <label for="housing_type">What type of housing do you live in?</label>
                    <select id="housing_type" name="housing_type" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="House">House</option>
                        <option value="Apartment">Apartment</option>
                        <option value="Complex/Townhouse">Complex / Townhouse</option>
                    </select>
                </div>

                <div class="input-group">
                    <label>Is your yard fully fenced/secured?</label><br></br>
                    <div class="radio-group">
                        <label><input type="radio" name="yard_fenced" value="Yes" required> Yes</label><br>
                        <label><input type="radio" name="yard_fenced" value="No"> No</label><br>
                        <label><input type="radio" name="yard_fenced" value="Partial"> Partially Fenced</label><br>
                        <label><input type="radio" name="yard_fenced" value="N/A"> No Yard / Apartment</label><br></br>
                    </div>
                </div>

                <div class="input-row">
                    <div class="input-group">
                        <label for="hours_alone">Average hours pet will be left alone daily?</label><br></br>
                        <input type="number" id="hours_alone" name="hours_alone" min="0" max="24" required placeholder="e.g. 4"><br></br>
                    </div>
                    <div class="input-group">
                        <label for="has_experience">Do you have previous pet experience?</label><br></br>
                        <select id="has_experience" name="has_experience" required>
                            <option value="" disabled selected>Select an option</option>
                            <option value="First-time owner">First-time owner</option>
                            <option value="Experienced">Experienced owner</option>
                        </select>
                    </div>
                </div>
            </div>

            <button type="submit" class="submit-btn" onclick="alert('Thank you so for applying! Please click OK to be redirected to the Homepage...'); window.location.href='index.php'; return false;">Submit Application</button>
        </form>
    </div>
</div>

</body>
</html>