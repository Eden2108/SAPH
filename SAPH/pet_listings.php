<?php
session_start();
include 'DBConn.php';
include 'includes/navbar.php';

// Hardcoded pet list (fallback if DB is empty)
$pets = [
   ["name" => "Hermet Crab", "description" => "Age: 4 years old | Colour: white/orange", "image" => "includes/assets/images/crab_pet.jpg"],
    ["name" => "Goldfish", "description" => "Age: 5 years old | Colour: Gold", "image" => "includes/assets/images/goldfish_pet.jpg"],
    ["name" => "White Dove", "description" => "Age: 9 years old | Colour: White",  "image" => "includes/assets/images/dove_pet.jpg"],
    ["name" => "Baby Hedgehog", "description" => "Age: 2 years old | Colour: Brown/White", "image" => "includes/assets/images/hedgehog_pet.jpg"],
    ["name" => "Crested Ghecko", "description" => "Age: 4 years old | Colour: Yellow", "image" => "includes/assets/images/ghecko_pet.jpg"],
    ["name" => "Pacific Frog", "description" => "Age: 2 years old | Colour: Green", "image" => "includes/assets/images/frog_pet.jpg"],
    ["name" => "Baby Tortoise", "description" => "Age: 6 months old | Colour: Brown", "image" => "includes/assets/images/tortoise_pet.jpg"],
    ["name" => "Puppy Rotweiler", "description" => "Age: 2 years old | Colour: Black/Brown", "image" => "includes/assets/images/rotweiler_pet.jpg"],
    ["name" => "Puppy German Shephard", "description" => "Age: 4 years old | Colour: Black/Brown", "image" => "includes/assets/images/germanshephard.jpg"],
    ["name" => "Red-Head Broiler Chicken", "description" => "Age: 7 years old | Colour: Brown", "image" => "includes/assets/images/chicken.jpg"],
    ["name" => "Chihuahua", "description" => "Age: 4 years old | Colour: White", "image" => "includes/assets/images/chihuahua.jpg"],
    ["name" => "Canary Bird", "description" => "Age: 7 years old | Colour: Yellow", "image" => "includes/assets/images/canary.jpg"],
    ["name" => "Australian Budgie", "description" => "Age: 8 years old | Colour: Green/Yellow", "image" => "includes/assets/images/budgie.jpg"],
    ["name" => "New Zealand Parrot", "description" => "Age: 9 years old | Colour: Green/Yellow/Blue", "image" => "includes/assets/images/parrot.jpg"],
    ["name" => "Rabbit", "description" => "Age: 4 years old | Colour: Brown", "image" => "includes/assets/images/rabbit.jpg"],
    ["name" => "Sable Ferret", "description" => "Age: 5 years old | Colour: White/Brown", "image" => "includes/assets/images/ferret.jpg"],
    ["name" => "Sugar Glider", "description" => "Age: 2 years old | Colour: White/Brown", "image" => "includes/assets/images/sugargliders.jpg"],
    ["name" => "Albino Axolotl", "description" => "Age: 6 years old | Colour: White/Pink", "image" => "includes/assets/images/axolotl.jpg"],
    ["name" => "Common Degu", "description" => "Age: 4 years old | Colour: Brown", "image" => "includes/assets/images/degus.jpg"],
    ["name" => "Green Ghecko", "description" => "Age: 4 years old | Colour: Green", "image" => "includes/assets/images/greeniguana.jpg"],
    ["name" => "Australian Alpacas", "description" => "Age: 10 years old | Colour: White", "image" => "includes/assets/images/alpacas.jpg"],
    ["name" => "Chilean Rose Tarantula", "description" => "Age: 4 years old | Colour: Brown/Orange", "image" => "includes/assets/images/tarantula.jpg"],   
    ["name" => "Corn Snake", "description" => "Age: 2 years old | Colour: Orange/Red", "image" => "includes/assets/images/snake_pet.jpg"],
    ["name" => "Labrador", "description" => "Age: 3 years old | Colour: Golden", "image" => "includes/assets/images/labrador.jpg"],
    ["name" => "Siamese Cat", "description" => "Age: 2 years old | Colour: Cream with Dark Points", "image" => "includes/assets/images/siamese-cat.jpg"],
];

// Try to fetch pets from DB
$result = $conn->query("SELECT PetID, Name, Age, Colour FROM Pet");
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
                    <img src="includes/assets/images/<?php echo strtolower(str_replace(' ', '-', $pet['Name'])); ?>.jpg" alt="<?php echo $pet['Name']; ?>">
                    <h3><?php echo $pet['Name']; ?></h3>
                    <p>Age: <?php echo $pet['Age']; ?> | Colour: <?php echo $pet['Colour']; ?></p>
                    <form method="get" action="pet_details.php">
                        <input type="hidden" name="petID" value="<?php echo $pet['PetID']; ?>">
                        <input type="submit" value="View Details">
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <?php foreach ($pets as $pet): ?>
                <div class="product-card">
                    <img src="<?php echo $pet['image']; ?>" alt="<?php echo $pet['name']; ?>">
                    <h3><?php echo $pet['name']; ?></h3>
                    <p><?php echo $pet['description']; ?></p>
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
