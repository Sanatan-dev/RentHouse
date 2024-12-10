<?php
session_start();
isset($_SESSION["email"]);

include "booking-engine.php";
include "config/config.php";
 // Same as estimated price for yearly payment

 $property_id = $_POST['property_id'];
    $estimated_price = $_POST['estimated_price']; // Total price
    
    // Calculate monthly and yearly prices
    $monthly_price = $estimated_price / 12; // Dividing estimated price by 12 months
    $yearly_price = $estimated_price;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>payment-page</title>
    <script>
        function updatePrice() {
            // Get selected payment mode from dropdown
            var paymentMode = document.getElementById("payment_mode").value;
            var monthlyPrice = <?php echo $monthly_price; ?>;
            var yearlyPrice = <?php echo $yearly_price; ?>;
            
            // Update the price input field based on selected option
            if (paymentMode === "monthly") {
                document.getElementById("payable_amount").value = monthlyPrice.toFixed(2);
            } else if (paymentMode === "yearly") {
                document.getElementById("payable_amount").value = yearlyPrice.toFixed(2);
            }
        }
    </script>
</head>
<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-5">
    <h2>Payment Options for Property ID: <?php echo $property_id; ?></h2>
    <p>Estimated Price: ₹<?php echo number_format($estimated_price, 2); ?></p>

    <!-- Payment form -->
    <form method="POST">
        <!-- Payment Mode Dropdown -->
        <div class="form-group">
            <label for="payment_mode">Choose Payment Mode:</label>
            <select class="form-control" id="payment_mode" name="payment_mode" onchange="updatePrice()" required>
                <option value="" disabled selected>Select payment mode</option>
                <option value="M">Monthly Payment (₹<?php echo number_format($monthly_price, 2); ?> per month)</option>
                <option value="Y">Yearly Payment (₹<?php echo number_format($yearly_price, 2); ?> per year)</option>
            </select>
        </div>

        <!-- Input field to display the payable amount -->
        <div class="form-group">
            <label for="payable_amount">Amount to Pay:</label>
            <input type="text" class="form-control" id="payable_amount" name="payable_amount" readonly placeholder="0.00">
        </div>

        <!-- Book Property Button -->
        <input type="hidden" name="property_id" value="<?php echo $_POST['property_id']; ?>">
        <input type="hidden" name="estimated_price" value="<?php echo $_POST['estimated_price']; ?>">
        <input type="submit" class="btn btn-lg btn-primary" name="book_property" style="width: 100%" value="Proceed to Pay">
    </form>
</div>
</body>
</html>