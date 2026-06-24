<!DOCTYPE html>
<html>
<head>
    <title>Save-a-pet HUB | Home</title>

   <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>
<?php include 'includes/navbar.php'; ?> <!-- Include navigation bar -->

<!-- Banner section -->
 <main>
<section class="banner">
    <img src="includes/assets/images/banner_pet.jpg" alt="Banner Image" class="banner-img">
    <div class="banner-text">
        <h1>ADOPT  PAWS & CLAWS</h1>
        <p>Discover abondoned pets and give them a home</p>
    </div>
</section>


<!-- Featured pets -->
<h2>Available Pets</h2>
<div class="product-grid">
    <?php
    // Array of featured products
    $featured = [
        ["name" => "Orange Corn Snake (8 months old) ",  "image" => "includes/assets/images/snake_pet.jpg"],
        ["name" => "Golden retriever dog (6 years old) ", "price" => 549.99, "image" => "includes/assets/images/dog_pet.jpg"],
        ["name" => "Hamster (2 years old) ", "price" => 399.99, "image" => "includes/assets/images/hamster_pet.jpg"],
        ["name" => "Kitten (7 months old) ", "price" => 289.99, "image" => "includes/assets/images/cat_pet.jpg"]
    ];
    // Loop through and display each product
    foreach ($featured as $item) {
        echo "<div class='product-card'>
            <img src='{$item['image']}' alt='{$item['name']}'>
            <h4>{$item['name']}</h4>
            
            <a href='pet_listings.php'><input type='submit' value='View Available Pets'></a>
          </div>";
}
    ?>
</div>

<section class="news-section">
    <h2 class="section-title">Recent SAPH News</h2>
    
    <div class="news-grid">
        <!-- Card 1 -->
        <article class="news-card">
            <div class="card-image-container">
                <img src="includes/assets/images/neglected_pet1.jpg" alt="Child with dog">
                <div class="date-badge red-badge">15 June 2026</div>
            </div>
            <div class="card-content">
                <h3>Growing Up Together: Animals, Youth and a Lifetime of Lessons</h3>
                <span class="sub-date">June 15, 2026</span>
                <p>Discover how growing up with animals shapes empathy, responsibility, and provides a lifetime of valuable lessons...</p>
            Read More...
            </div>
        </article>

        <!-- Card 2 -->
        <article class="news-card">
            <div class="card-image-container">
                <img src="includes/assets/images/neglected_pet2.jpg" alt="Dog resting">
                <div class="date-badge red-badge">29 May 2026</div>
            </div>
            <div class="card-content">
                <h3>Your support means the world to an animal in need</h3>
                <span class="sub-date">June 12, 2026</span>
                <p>Every small donation and act of kindness helps us provide medical care, warm shelter, and food to vulnerable animals...</p>
            Read More...
            </div>
        </article>

        <!-- Card 3 -->
        <article class="news-card">
            <div class="card-image-container">
                <img src="includes/assets/images/team.jpeg" alt="SPCA Team">
                <div class="date-badge blue-badge">14 April 2026</div>
            </div>
            <div class="card-content">
                <h3>April is Prevention of Animal Cruelty Month – How You Can Make a Difference</h3>
                <span class="sub-date">April 14, 2026</span>
                <p>Join our mission this month to speak up for those who cannot speak for themselves. Learn how you can raise awareness...</p>
            Read More...
            </div>
        </article>
    </div>
</section>

<!-- Floating browse available pets button -->
<a href="pet_listings.php" class="add-item-btn">Browse Available Pets</a>
</main>

<?php include 'includes/footer.php'; ?> <!-- Include footer -->
</body>
</html>
