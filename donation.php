<?php 
// Include your navbar at the top
include 'includes/navbar.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make a Donation - Save-a-Pet HUB</title>
    <link rel="stylesheet" href="includes/assets/css/style.css">
</head>
<body>

<main>
    <div class="form-box">
        <h2>Make a Donation</h2>
        <p class="form-intro">Your support helps us provide second chances for every animal in need.</p>

        <form action="donate.php" method="POST">
            <label for="donor_name">Your Name:</label>
            <input type="text" id="donor_name" name="donor_name" required placeholder="Enter your full name">

            <label for="donation_amount">Donation Amount (R):</label>
            <input type="number" id="donation_amount" name="donation_amount" min="1" step="0.01" required placeholder="Enter amount">

            <label for="payment_method">Payment Method:</label>
            <select id="payment_method" name="payment_method" required>
                <option value="" disabled selected>Select payment method</option>
                <option value="Credit Card">Credit Card</option>
                <option value="Debit Card">Debit Card</option>
                <option value="EFT">EFT / Bank Transfer</option>
                <option value="PayPal">PayPal</option>
            </select>

            <div class="form-actions">
                <button type="submit" class="cancel-btn">Submit Donation</button>
            </div>
        </form>
    </div>
</main>

        
    <div class="form-actions">
    <!-- Back to the full listings -->
    <a href="index.php" class="add-item-btn">
        Back To Home
    </a>
</div>

    <?php include 'includes/footer.php'; ?>
</main>
</body>
</html>