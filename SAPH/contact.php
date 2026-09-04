<!DOCTYPE html>
<html>
<head>
    <title>Volunteer - Save-a-Pet HUB</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
<h2>Contact Us</h2>
<p style= "text-align: center;">If you have any questions or would like to get in touch, please reach out to us below:</p>

<form method="POST" action="index.php">
    <label for="name">Your Name:</label><br>
    <input type="text" id="name" name="name" required><br><br>

    <label for="email">Your Email:</label><br>
    <input type="email" id="email" name="email" required><br><br>

    <label for="message">Message:</label><br>
    <textarea id="message" name="message" rows="5" required></textarea><br><br>

    <input type="submit" value="Send Details">
</form>
</main>

<?php include 'includes/footer.php'; ?>

</body>
</html>
