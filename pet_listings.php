<?php
session_start();
include 'DBConn.php';
include 'includes/navbar.php';

// ✅ Query pets directly from InfinityFree DB
$sql = "SELECT PetID, Name, Age, AgeUnit, Colour, Species, Breed, HealthStatus, AdoptionStatus, Image 
        FROM pet";   

$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Listings - Save-A-Pet Hub</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>
<main>
    <h2>Adopt Available Pets</h2>
    <div class="product-grid">

        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <!-- ✅ Loop through DB rows -->
            <?php while($pet = mysqli_fetch_assoc($result)): ?>
                <div class="product-card">
                    <!-- ✅ Use Image column from DB -->
                    <img src="includes/assets/images/<?php echo htmlspecialchars($pet['Image']); ?>" 
                         alt="<?php echo htmlspecialchars($pet['Name']); ?>" width="200">

                    <h3><?php echo htmlspecialchars($pet['Name']); ?></h3>
                    <p>
                        Age: <?php echo htmlspecialchars($pet['Age']) . ' ' . strtolower($pet['AgeUnit']); ?> old |
                        Colour: <?php echo htmlspecialchars($pet['Colour']); ?>
                    </p>
                    <p>Species: <?php echo htmlspecialchars($pet['Species']); ?> | Breed: <?php echo htmlspecialchars($pet['Breed']); ?></p>
                    <p>Health Status: <?php echo htmlspecialchars($pet['HealthStatus']); ?></p>
                    <p>Adoption Status: <?php echo htmlspecialchars($pet['AdoptionStatus']); ?></p>

                    <!-- ✅ Pass PetID to details page -->
                    <form method="get" action="pet_details.php">
                        <input type="hidden" name="petID" value="<?php echo $pet['PetID']; ?>">
                        <input type="submit" value="View Details">
                    </form>
                </div>
            <?php endwhile; ?>

        <?php else: ?>
            <!-- ✅ Fallback if DB query fails -->
            <p>No pets available right now. Please check back later.</p>
        <?php endif; ?>

    </div>
    <a href="index.php" class="add-item-btn">Back To Home</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
