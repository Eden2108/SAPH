<!DOCTYPE html>
<html>
<head>
    <title>Volunteer - Save-a-Pet HUB</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>

    <div class="form-box">

        <h2>Contact Us</h2>

        <p class="form-intro">
            If you have any questions or would like to get in touch,
            please reach out to us below.
        </p>

        <form>

            <label>Your Name:</label>

            <input
                type="text"
                name="name"
                required>


            <label>Your Email:</label>

            <input
                type="email"
                name="email"
                required>


            <label>Message:</label>

            <textarea
                name="message"
                rows="6"
                required></textarea>


            <button type="submit">
                Send Details
            </button>

        </form>
<a href="index.php" class="add-item-btn">Back To Home</a>
    </div>
<?php include 'includes/footer.php'; ?>
</main>
