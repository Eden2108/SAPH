<?php
session_start();
include 'DBConn.php';
include 'includes/navbar.php';

// Hardcoded pet list (fallback if DB is empty)
$pets = [
   ["name" => "Shelly", "description" => "Age: 4 years old | Colour: white/orange", "AdoptionStatus" => "Available", "image" => "includes/assets/images/crab_pet.jpg"],
   ["name" => "Bubbles", "description" => "Age: 5 years old | Colour: Gold", "AdoptionStatus" => "Available", "image" => "includes/assets/images/goldfish_pet.jpg"],
   ["name" => "Snowy", "description" => "Age: 9 years old | Colour: White", "AdoptionStatus" => "Available", "image" => "includes/assets/images/dove_pet.jpg"],
   ["name" => "Spike", "description" => "Age: 2 years old | Colour: Brown/White", "AdoptionStatus" => "Adopted", "image" => "includes/assets/images/hedgehog_pet.jpg"],
   ["name" => "Leo", "description" => "Age: 4 years old | Colour: Yellow", "AdoptionStatus" => "Available", "image" => "includes/assets/images/gecko_pet.jpg"],
   ["name" => "Hopper", "description" => "Age: 2 years old | Colour: Green", "AdoptionStatus" => "Available", "image" => "includes/assets/images/frog_pet.jpg"],
   ["name" => "Tiny Tim", "description" => "Age: 6 months old | Colour: Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/tortoise_pet.jpg"],
   ["name" => "Rocky", "description" => "Age: 2 years old | Colour: Black/Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/rotweiler_pet.jpg"],
   ["name" => "Max", "description" => "Age: 4 years old | Colour: Black/Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/germanshephard.jpg"],
   ["name" => "Clucky", "description" => "Age: 7 years old | Colour: Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/chicken.jpg"],
   ["name" => "Bella", "description" => "Age: 4 years old | Colour: White", "AdoptionStatus" => "Available", "image" => "includes/assets/images/chihuahua.jpg"],
   ["name" => "Sunny", "description" => "Age: 7 years old | Colour: Yellow", "AdoptionStatus" => "Available", "image" => "includes/assets/images/canary.jpg"],
   ["name" => "Kiwi", "description" => "Age: 8 years old | Colour: Green/Yellow", "AdoptionStatus" => "Available", "image" => "includes/assets/images/budgie.jpg"],
   ["name" => "Rio", "description" => "Age: 5 years old | Colour: Green", "AdoptionStatus" => "Available", "image" => "includes/assets/images/parrot.jpg"],
   ["name" => "Thumper", "description" => "Age: 3 years old | Colour: White/Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/rabbit.jpg"],
   ["name" => "Bandit", "description" => "Age: 2 years old | Colour: White/Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/ferret.jpg"],
   ["name" => "Glider", "description" => "Age: 2 years old | Colour: White/Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/sugargliders.jpg"],
   ["name" => "Axel", "description" => "Age: 6 years old | Colour: White/Pink", "AdoptionStatus" => "Available", "image" => "includes/assets/images/axolotl.jpg"],
   ["name" => "Nibbles", "description" => "Age: 4 years old | Colour: Brown", "AdoptionStatus" => "Available", "image" => "includes/assets/images/degus.jpg"],
   ["name" => "Ziggy", "description" => "Age: 4 years old | Colour: Green", "AdoptionStatus" => "Available", "image" => "includes/assets/images/greeniguana.jpg"],
   ["name" => "Ally", "description" => "Age: 10 years old | Colour: White", "AdoptionStatus" => "Available", "image" => "includes/assets/images/alpacas.jpg"],
   ["name" => "Rosie", "description" => "Age: 4 years old | Colour: Brown/Orange", "AdoptionStatus" => "Available", "image" => "includes/assets/images/tarantula.jpg"],   
   ["name" => "Slyther", "description" => "Age: 2 years old | Colour: Orange/Red", "AdoptionStatus" => "Available", "image" => "includes/assets/images/snake_pet.jpg"],
   ["name" => "Buddy", "description" => "Age: 3 years old | Colour: Golden", "AdoptionStatus" => "Available", "image" => "includes/assets/images/labrador.jpg"],
   ["name" => "Luna", "description" => "Age: 2 years old | Colour: Cream with Dark Points", "AdoptionStatus" => "Available", "image" => "includes/assets/images/siamese-cat.jpg"],
];

// Try to fetch pets from DB
$result = $conn->query("SELECT PetID, Name, Age, AgeUnit, Colour, Species, Breed, HealthStatus, AdoptionStatus, Image FROM Pet");
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
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while($pet = $result->fetch_assoc()): ?>
                <div class="product-card">
                    <!-- ✅ Use Image column from DB -->
                    <img src="includes/assets/images/<?php echo $pet['Image']; ?>" alt="<?php echo $pet['Name']; ?>" width="200">
                    <h3><?php echo $pet['Name']; ?></h3>
                    <p>Age: <?php echo $pet['Age'] . ' ' . strtolower($pet['AgeUnit']); ?> old | Colour: <?php echo $pet['Colour']; ?></p>
                    <p>Adoption Status: <?php echo $pet['AdoptionStatus']; ?></p>
                    <form method="get" action="pet_details.php">
                        <input type="hidden" name="petID" value="<?php echo $pet['PetID']; ?>">
                        <input type="submit" value="View Details">
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <?php foreach ($pets as $pet): ?>
                <div class="product-card">
                    <img src="<?php echo $pet['image']; ?>" alt="<?php echo $pet['name']; ?>" width="200">
                    <h3><?php echo $pet['name']; ?></h3>
                    <p><?php echo $pet['description']; ?></p>
                    <p>Adoption Status: <?php echo $pet['AdoptionStatus']; ?></p>
                    <form method="get" action="pet_details.php">
                        <input type="hidden" name="petName" value="<?php echo $pet['name']; ?>">
                        <input type="submit" value="View Details">
                    </form>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <a href="index.php" class="add-item-btn">Back To Home</a>
</main>
<?php include 'includes/footer.php'; ?>
</body>
</html>
