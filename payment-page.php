<?php
session_start();
include "booking-engine.php";
include("navbar.php");
include "config/config.php";
require_once 'razorpay-php-2.9.0/Razorpay.php';
use Razorpay\Api\Api;

$razorpay_key_id = '';
$razorpay_key_secret = '';
$api = new Api($razorpay_key_id, $razorpay_key_secret);

$property_id = $_POST['property_id'];
$estimated_price = $_POST['estimated_price'];
$monthly_price = $estimated_price / 12;

$payment_mode = $_POST['payment_mode'] ?? 'Y';
$months = $_POST['number_of_months'] ?? 1;

$payable_amount = ($payment_mode == "M") ? ($monthly_price * $months) : $estimated_price;
$_SESSION['payable_amount'] = $payable_amount;
$payable_amount = 1;
$order_amount = $payable_amount * 100;

$order = $api->order->create([
    'receipt' => 'order_rcptid_' . $property_id,
    'amount' => $order_amount,
    'currency' => 'INR'
]);

$_SESSION['razorpay_order_id'] = $order['id'];
$_SESSION['booking_id'] = $property_id;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent House Payment</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
        }
        
        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 3rem;
            animation: fadeIn 1s ease-in-out;
        }

        h2 {
            color: #333;
            font-weight: 700;
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .form-control:focus {
            border-color: #3399cc;
            box-shadow: none;
        }

        #payButton {
            width: 100%;
            background-color: #3399cc;
            border-color: #3399cc;
            font-size: 1.5rem;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        #payButton:hover {
            background-color: #2879a9;
            transform: translateY(-2px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        function updatePrice() {
            var paymentMode = document.getElementById("payment_mode").value;
            var monthlyPrice = <?php echo $monthly_price; ?>;
            var yearlyPrice = <?php echo $estimated_price; ?>;

            if (paymentMode === "M") {
                document.getElementById("months_container").style.display = "block";
                document.getElementById("payable_amount").value = (monthlyPrice * document.getElementById("number_of_months").value).toFixed(2);
            } else {
                document.getElementById("months_container").style.display = "none";
                document.getElementById("payable_amount").value = yearlyPrice.toFixed(2);
            }
        }

        function updateMonthlyAmount() {
            var months = document.getElementById("number_of_months").value;
            var monthlyPrice = <?php echo $monthly_price; ?>;
            document.getElementById("payable_amount").value = (months * monthlyPrice).toFixed(2);
        }
    </script>
</head>

<body>
<div class="container">
        <h2>Pay for Property ID: <?php echo $property_id; ?></h2>
        <p>Estimated Price: ₹<?php echo number_format($estimated_price, 2); ?></p>

        <form id="paymentForm" method="POST" action="verify_payment.php">
            <div class="form-group">
                <label for="payment_mode">Choose Payment Mode:</label>
                <select id="payment_mode" name="payment_mode" class="form-control" onchange="updatePrice()" required>
                    <option value="" disabled selected>Select payment mode</option>
                    <option value="M">Monthly Payment (₹<?php echo number_format($monthly_price, 2); ?> per month)</option>
                    <option value="Y">Yearly Payment (₹<?php echo number_format($estimated_price, 2); ?> per year)</option>
                </select>
            </div>

            <div id="months_container" class="form-group" style="display: none;">
                <label for="number_of_months">Select Number of Months:</label>
                <select id="number_of_months" name="number_of_months" class="form-control" onchange="updateMonthlyAmount()">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?> Month(s)</option>
                    <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="payable_amount">Amount to Pay:</label>
                <input type="text" id="payable_amount" name="payable_amount" class="form-control" value="<?php echo number_format($payable_amount, 2); ?>" readonly>
            </div>
            
            <input type="hidden" name="property_id" value="<?php echo $_POST['property_id']; ?>">
            <input type="hidden" name="estimated_price" value="<?php echo $_POST['estimated_price']; ?>">
            <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">

            <button id="payButton" type="button" class="btn btn-primary mt-3">Pay Now</button>
        </form>
    </div>

    <script>
        var options = {
            "key": "<?php echo $razorpay_key_id; ?>",
            "amount": "<?php echo $order_amount; ?>",
            "currency": "INR",
            "name": "House Rental System",
            "description": "Payment for Property Booking",
            "order_id": "<?php echo $order['id']; ?>",
            "handler": function (response) {
                // Populate hidden input with Razorpay payment ID
                document.getElementById("razorpay_payment_id").value = response.razorpay_payment_id;

                // Submit the form with all data to verify_payment.php
                document.getElementById("paymentForm").submit();
            },
            "prefill": {
                "email": "<?php echo $_SESSION['email']; ?>"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        var rzp1 = new Razorpay(options);
        document.getElementById('payButton').onclick = function (e) {
            rzp1.open();
            e.preventDefault();
        }
    </script>
</body>

</html>