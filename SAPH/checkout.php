<?php
// Start session to store checkout selections
session_start();

// Handle remove item action
if (isset($_GET['remove'])) {
    $removeIndex = $_GET['remove'];
    unset($_SESSION['cart'][$removeIndex]);
    $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex array
}

// If form is submitted, save selections and redirect to confirmation page
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['checkout'] = $_POST;
    header("Location: confirm.php");
    exit();
}

// Example cart items (replace with DB or session data)
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [
        ["name" => "Denim Jacket", "price" => 459.99, "image" => "includes/assets/images/jacket.jpg"],
        ["name" => "White Sneakers", "price" => 549.99, "image" => "includes/assets/images/sneakers.jpg"]
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout - Pastimes</title>
    <link rel="stylesheet" type="text/css" href="includes/assets/css/style.css">
        <!-- Added Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<main>
<h2>Checkout</h2>
<p>Please review your order and select delivery and payment options:</p>

<form method="POST" action="">
    <!-- ORDER SUMMARY SECTION-->
    <h3><i class="fa-solid fa-bag-shopping"></i> Order Summary</h3>
    <?php 
   $total = 0;
        foreach ($_SESSION['cart'] as $index => $item):
        $itemTotal = $item['price'] * $item['quantity'];
        $total += $itemTotal;
    ?>
        <div class="order-item">
            <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="order-img">
            <div class="order-details">
                <p><strong><?php echo $item['name']; ?></strong></p>
                <p> Pastimes</p>
               <p>Quantity: <?php echo $item['quantity']; ?></p>

                <p>
                Subtotal:
                R<?php echo number_format($itemTotal, 2); ?>
                </p>
            </div>
            <div class="order-remove">
                <a href="checkout.php?remove=<?php echo $index; ?>"><i class="fa-solid fa-trash"></i> Remove</a>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="order-total">
    <p><strong>Items Total: R<?php echo number_format($total, 2); ?></strong></p>

    <p id="deliveryCost">
        Delivery: R0.00
    </p>

    <p id="grandTotal">
        <strong>Grand Total: R<?php echo number_format($total, 2); ?></strong>
    </p>
</div>

<input type="hidden" id="baseTotal" value="<?php echo $total; ?>">

    <br></br>
    <!--DELIVERY OPTIONS SECTION -->
    <h3><i class="fa-solid fa-truck"></i> Delivery Method</h3>
    <label>
    <input type="radio" name="delivery" value="postnet" data-cost="109" required>
        PostNet-to-PostNet (+R109)
    </label><br>

    <label>
    <input type="radio" name="delivery" value="aramex" data-cost="99.99">
        Aramex Store-to-Door (+R99.99)
    </label><br>
    <br></br>

    <!-- PAYMENT OPTIONS SECTION -->
<!-- PAYMENT OPTIONS SECTION -->
<h3><i class="fa-solid fa-credit-card"></i> Payment Method</h3>

<!-- Credit/Debit Card option -->
<div class="payment-option">
    <label>
        <input type="radio" name="payment" value="card" required>
        Credit / Debit Card
    </label>
    <div id="card-box" class="payment-box hidden">
        <h4>Enter Card Details</h4>

        <!-- Card Number -->
        <label>Card Number</label>
        <input type="text" placeholder="1234 5678 9012 3456" maxlength="16">

        <!-- Name on Card -->
        <label>Name on Card</label>
        <input type="text" placeholder="John Doe">

        <!-- CVV + Expiry side by side -->
        <div class="card-row">
            <div class="card-field">
                <label>CVV</label>
                <input type="text" placeholder="123" maxlength="3">
            </div>
            <div class="card-field">
                <label>Expiry Date (MM/YY)</label>
                <input type="text" placeholder="MM/YY" maxlength="5">
            </div>
        </div>

        <!-- Store Card checkbox -->
        <div class="store-card">
            <label>
                <input type="checkbox" name="store_card" >
                Store Card for future use
            </label>
        </div>

        <!-- Pay Now button -->
       <button type="button" class="btn pay" onclick="fakePayment()">Pay Now</button>

        <!-- Footer line -->
        <p class="secure-note">Secured by PayU</p>
    </div>
</div>

<!-- EFT option -->
<div class="payment-option">
    <label>
        <input type="radio" name="payment" value="eft">
        EFT / Bank Transfer
    </label>
    <div id="eft-box" class="payment-box hidden">
        <h4>Select Your Bank</h4>
        <select>
             <option>--Select--</option>
            <option>Absa</option>
            <option>Capitec</option>
            <option>FNB</option>
            <option>Investec</option>
            <option>Nedbank</option>
            <option>Standard Bank</option>
            <option>TymeBank</option>
        </select>
    </div>
</div>

<!-- Wallet option -->
<div class="payment-option">
    <label>
        <input type="radio" name="payment" value="wallet">
        Pastimes wallet
    </label>
    <div id="wallet-box" class="payment-box hidden">
        <h4>Pastimes wallet</h4>
        <p>You can pay for the purchase with the money in your Pastimes wallet. To use this payment option, wallet balance available for shopping must be greater than the purchase amount, including transport. Partial payment with Pastimes wallet is not possible.</p>
    </div>
</div>

    <br></br>
    <!--DISCOUNT CODE SECTION-->
    <h3><i class="fa-solid fa-tag"></i> Do you have a discount code?</h3>
    <input type="text" class="discount-input" name="discount" placeholder="Enter code">

    <!--SUBMIT BUTTON-->
    <br><br>
    <input type="submit" value="Confirm Order">
</form>
</main>

<?php include 'includes/footer.php'; ?>

<!-- JavaScript toggle for payment dropdown -->
<script>
let activePayment = null;

document.querySelectorAll('input[name="payment"]').forEach(function(radio) {
  radio.addEventListener('click', function() {
    const value = this.value;
    const box = document.getElementById(value + '-box');

    // Collapse if clicking the same option again
    if (activePayment === value) {
      box.classList.remove('show');
      this.checked = false;
      activePayment = null;
      return;
    }

    // Hide all
    document.getElementById('card-box').classList.remove('show');
    document.getElementById('eft-box').classList.remove('show');
    document.getElementById('wallet-box').classList.remove('show');

    // Show selected
    box.classList.add('show');
    activePayment = value;
  });
});
</script>
<script>
const baseTotal =
    parseFloat(document.getElementById('baseTotal').value);

document.querySelectorAll('input[name="delivery"]').forEach(function(radio){

    radio.addEventListener('change', function(){

        const delivery =
            parseFloat(this.dataset.cost);

        const grandTotal =
            baseTotal + delivery;

        document.getElementById('deliveryCost').innerHTML =
            "Delivery: R" + delivery.toFixed(2);

        document.getElementById('grandTotal').innerHTML =
            "<strong>Grand Total: R" +
            grandTotal.toFixed(2) +
            "</strong>";
    });

});
</script>

<script>
function fakePayment(){
    alert("Payment Successful! Thank you for shopping with Pastimes.");
}
</script>

</body>
</html>
