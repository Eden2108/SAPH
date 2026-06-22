<?php
session_start();

// Hardcoded product list (later can be linked to DB)
//Loop ensures all products are included automatically
$products = [
    ["name" => "Hermet Crab", "description" => "Age: 4 years | Colour: white/orange", "price" => 459.99, "image" => "includes/assets/images/crab_pet.jpg"],
    ["name" => "Goldfish", "description" => "Age: 5 years | Colour: Gold", "price" => 549.99, "image" => "includes/assets/images/goldfish_pet.jpg"],
    ["name" => "White Dove", "description" => "Age: 9 years | Colour: White", "price" => 289.99, "image" => "includes/assets/images/dove_pet.jpg"],
    ["name" => "Baby Hedgehog", "description" => "Age: 2 years | Colour: Brown/White", "price" => 399.99, "image" => "includes/assets/images/hedgehog_pet.jpg"],
    ["name" => "Crested Ghecko", "description" => "Age: 4 years | Colour: Yellow", "price" => 350.99, "image" => "includes/assets/images/ghecko_pet.jpg"],
    ["name" => "Pacific Frog", "description" => "Age: 2 years | Colour: Green", "price" => 399.99, "image" => "includes/assets/images/frog_pet.jpg"],
    ["name" => "Baby Tortoise", "description" => "Age: 6 months | Colour: Brown", "price" => 99.99, "image" => "includes/assets/images/tortoise_pet.jpg"],
    ["name" => "Puppy Rotweiler", "description" => "Age: 2 years | Colour: Black/Brown", "price" => 899.99, "image" => "includes/assets/images/rotweiler_pet.jpg"],
    ["name" => "Puppy German Shephard", "description" => "Age: 4 years | Colour: Black/Brown", "price" => 749.99, "image" => "includes/assets/images/germanshephard.jpg"],
    ["name" => "Red-Head Broiler Chicken", "description" => "Age: 7 years | Colour: Brown", "price" => 599.99, "image" => "includes/assets/images/chicken.jpg"],
    ["name" => "Chihuahua", "description" => "Age: 4 years | Colour: White", "price" => 399.99, "image" => "includes/assets/images/chihuahua.jpg"],
    ["name" => "Canary Bird", "description" => "Age: 7 years | Colour: Yellow", "price" => 499.99, "image" => "includes/assets/images/canary.jpg"],
    ["name" => "Australian Budgie", "description" => "Age: 8 years | Colour: Green/Yellow", "price" => 349.99, "image" => "includes/assets/images/budgie.jpg"],
    ["name" => "New Zealand Parrot", "description" => "Age: 9 years | Colour: Green/Yellow/Blue", "price" => 59.99, "image" => "includes/assets/images/parrot.jpg"],
    ["name" => "Rabbit", "description" => "Age: 4years | Colour: Brown", "price" => 599.99, "image" => "includes/assets/images/rabbit.jpg"],
    ["name" => "Sable Ferret", "description" => "Age: 5 years | Colour: White/Brown", "price" => 299.99, "image" => "includes/assets/images/ferret.jpg"],
    ["name" => "Sugar Glider", "description" => "Age: 2 years | Colour: White/Brown", "price" => 249.99, "image" => "includes/assets/images/sugargliders.jpg"],
    ["name" => "Short-Tailed Chinchilla ", "description" => "Age: 4 years | Colour: Grey", "price" => 149.99, "image" => "includes/assets/images/chinchilla.jpg"],
    ["name" => "Albino Axolotl", "description" => "Age: 6 years | Colour: White/Pink", "price" => 199.99, "image" => "includes/assets/images/axolotl.jpg"],
    ["name" => "Common Degu", "description" => "Age: 4 years | Colour: Brown", "price" => 19.99, "image" => "includes/assets/images/degus.jpg"],
    ["name" => "Green Ghecko", "description" => "Age: 4 years | Colour: Green", "price" => 249.99, "image" => "includes/assets/images/greeniguana.jpg"],
    ["name" => "Australian Alpacas", "description" => "Age: 10 years | Colour: White", "price" => 15.99, "image" => "includes/assets/images/alpacas.jpg"],
    ["name" => "Chilean Rose Tarantula", "description" => "Age: 4 years | Colour: Brown/Orange", "price" => 299.99, "image" => "includes/assets/images/tarantula.jpg"],
    ["name" => "Australian Cockroach", "description" => "Age: 3 years | Colour: Black/Brown", "price" => 239.99, "image" => "includes/assets/images/cockroach.jpg"],
    
];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Pet Listing - Pastimes</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
    <h2>Adopt Available Pets</h2>

    <div class="product-grid">
        <?php foreach ($products as $item): ?>
            <div class="product-card">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                <h3><?php echo $item['name']; ?></h3>
                <p><?php echo $item['description']; ?></p>
              
                <form method="post" action="cart.php">
                    <input type="hidden" name="product" value="<?php echo $item['name']; ?>">
                    <input type="submit" value="Adopt Pet">
                </form>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Floating Add Item button -->
<a href="index.php" class="add-item-btn">Back To Home</a>
</main>

<?php include 'includes/footer.php'; ?>
</body>
</html>
