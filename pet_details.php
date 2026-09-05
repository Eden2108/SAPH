<?php
include 'DBConn.php';
include 'includes/navbar.php';

$pet = null;

// ✅ Use the same parameter name as pet_listings.php (pet_id)
if (isset($_GET['pet_id'])) {
    $stmt = $conn->prepare("SELECT * FROM pet WHERE PetID = ?");
    $stmt->bind_param("i", $_GET['pet_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
    $stmt->close();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Details</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
        .pet-details { margin: 20px 0; }
        .pet-details dt { font-weight: bold; margin-right: 6px; }
        .pet-details dd { margin: 0 0 10px 0; }
    </style>
</head>
<body>
<div class="container">
<?php if($pet): ?>
    <h2>Meet Your Potential New Friend: <?php echo htmlspecialchars($pet['Name']); ?></h2>
    <img src="includes/assets/images/<?php echo htmlspecialchars($pet['Image']); ?>" 
         alt="<?php echo htmlspecialchars($pet['Name']); ?>" width="300">
    <div class="pet-info">
    <p><strong>Species:</strong> <?php echo htmlspecialchars($pet['Species']); ?></p>
    <p><strong>Breed:</strong> <?php echo htmlspecialchars($pet['Breed']); ?></p>
    <p><strong>Age:</strong> <?php echo htmlspecialchars($pet['Age']) . ' ' . strtolower($pet['AgeUnit']); ?> old</p>
    <p><strong>Colour:</strong> <?php echo htmlspecialchars($pet['Colour']); ?></p>
    <p><strong>Health Status:</strong> <?php echo htmlspecialchars($pet['HealthStatus']); ?></p>
    <p><strong>Adoption Status:</strong> <?php echo htmlspecialchars($pet['AdoptionStatus']); ?></p>
</div>
<br>

    <!-- ✅ Pass pet_id to adoption_application.php -->
    <a href="adoption_application.php?pet_id=<?php echo $pet['PetID']; ?>">
        <button>Apply for Adoption</button>
    </a>
<?php else: ?>
    <h2>Pet Not Found</h2>
<?php endif; ?>
</div>
<a href="pet_listings.php" class="add-item-btn">Back To Available Pets</a>
<?php include 'includes/footer.php'; ?>
</body>
</html>
