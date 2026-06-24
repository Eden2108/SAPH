<?php
include 'DBConn.php';
include 'includes/navbar.php';

// Hardcoded pet list (fallback)
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
</head>
<body>
<div class="container">
<?php if($pet): ?>
    <h2><?php echo $pet['Name'] ?? $pet['name']; ?></h2>
    <img src="<?php echo $pet['image'] ?? "includes/assets/images/".strtolower(str_replace(' ', '-', $pet['Name'])).".jpg"; ?>" width="300">
    <table>
        <?php if(isset($pet['Species'])): ?>
            <tr><th>Species</th><td><?php echo $pet['Species']; ?></td></tr>
            <tr><th>Breed</th><td><?php echo $pet['Breed']; ?></td></tr>
            <tr><th>Age</th><td><?php echo $pet['Age']; ?></td></tr>
            <tr><th>Colour</th><td><?php echo $pet['Colour']; ?></td></tr>
            <tr><th>Health Status</th><td><?php echo $pet['HealthStatus']; ?></td></tr>
            <tr><th>Adoption Status</th><td><?php echo $pet['AdoptionStatus']; ?></td></tr>
        <?php else: ?>
            <tr><td colspan="2"><?php echo $pet['description']; ?></td></tr>
        <?php endif; ?>
    </table>
    <br>
    <a href="adoption_application.php">
        <button>Apply for Adoption</button>
    </a>
<?php else: ?>
    <h2>Pet Not Found</h2>
<?php endif; ?>
</div>
 <a href="index.php" class="add-item-btn">Back To Home</a>
<?php include 'includes/footer.php'; ?>
</body>
</html>
