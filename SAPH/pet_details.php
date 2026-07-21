<?php
include 'DBConn.php';
include 'includes/navbar.php';

// Hardcoded pet list (fallback)
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

$pet = null;

// If petID is passed, load from DB
if (isset($_GET['petID'])) {
    $stmt = $conn->prepare("SELECT * FROM Pet WHERE PetID = ?");
    $stmt->bind_param("i", $_GET['petID']);
    $stmt->execute();
    $result = $stmt->get_result();
    $pet = $result->fetch_assoc();
}
// If petName is passed, look in hardcoded array
elseif (isset($_GET['petName'])) {
    foreach ($pets as $p) {
        if ($p['name'] === $_GET['petName']) {
            $pet = $p;
            break;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Details</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
    <style>
.pet-details {
    width: 100%;
    margin: 20px 0;
}

.pet-details dt {
    display: inline-block;   /* keep label inline */
    font-weight: bold;
    color: black;
    margin-right: 6px;       /* small gap between label and value */
}

.pet-details dd {
    display: inline-block;   /* keep value inline */
    margin: 0;               /* remove big default margin */
}
</style>

</head>
<body>
<div class="container">
<?php if($pet): ?>
    <h2>Meet Your Potential New Friend: <?php echo $pet['Name'] ?? $pet['name']; ?></h2>
    <img src="includes/assets/images/<?php echo $pet['Image']; ?>" alt="<?php echo $pet['Name']; ?>" width="300">
   <dl class="pet-details">
    <?php if(isset($pet['Species'])): ?>
        <dt>Species:</dt><dd><?php echo $pet['Species']; ?></dd><br>
        <dt>Breed:</dt><dd><?php echo $pet['Breed']; ?></dd><br>
        <dt>Age:</dt><dd><?php echo $pet['Age'] . ' ' . strtolower($pet['AgeUnit']); ?> old</dd><br>
        <dt>Colour:</dt><dd><?php echo $pet['Colour']; ?></dd><br>
        <dt>Health Status:</dt><dd><?php echo $pet['HealthStatus']; ?></dd><br>
        <dt>Adoption Status:</dt><dd><?php echo $pet['AdoptionStatus']; ?></dd><br>
    <?php else: ?>
        <dt>Description:</dt><dd><?php echo $pet['description']; ?></dd>
    <?php endif; ?>
</dl>

    <br>
    <a href="adoption_application.php">
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
