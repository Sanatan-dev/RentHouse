<?php
session_start();
include("config/config.php");
include("mail-engine.php");
require_once 'razorpay-php-2.9.0/Razorpay.php'; // Include Razorpay SDK

use Razorpay\Api\Api;

$razorpay_key_id = 'rzp_test_2xuCr5Gu8ys5Pr'; // Replace with your key ID
$razorpay_key_secret = 'aapkSTPDvH8y6ncT2GxseAs5'; // Replace with your secret key

$api = new Api($razorpay_key_id, $razorpay_key_secret);

// Verify payment
if (isset($_POST['razorpay_payment_id'], $_SESSION['email'])) {
    $paymentId = $_POST['razorpay_payment_id'];
    $u_email = $_SESSION['email'];

    try {
        // Fetch payment details
        $payment = $api->payment->fetch($paymentId);
        // Check if payment was successful
        if ($payment->status == 'captured') {
            // Retrieve booking details from session or POST
            $property_id = $_POST['property_id'];
            $estimated_price = $_POST['estimated_price'];
            $payment_mode = $_POST['payment_mode'];
            $amount_paid = $_POST['payable_amount'];
            $number_of_months = isset($_POST['number_of_months']) ? $_POST['number_of_months'] : 0;

            // Calculate payment_due and next payment date
            $payment_due = $estimated_price - $amount_paid;
            $current_date = new DateTime();
            if ($payment_mode == "M" && $number_of_months > 0) {
                $next_payment_date = $current_date->modify("+{$number_of_months} month")->format("Y-m-d H:i:s");
            } elseif ($payment_mode == "Y") {
                $next_payment_date = $current_date->modify("+1 year")->format("Y-m-d H:i:s");
            } else {
                $next_payment_date = $current_date->format("Y-m-d H:i:s");
            }

            // Get tenant details
            $sql = "SELECT * FROM tenant WHERE email='$u_email'";
            $query = mysqli_query($db, $sql);

            if (mysqli_num_rows($query) > 0) {
                $tenant = mysqli_fetch_assoc($query);
                $tenant_id = $tenant['tenant_id'];
                $tenant_name = $tenant['full_name'];

                // Mark property as booked
                $sql1 = "UPDATE add_property SET booked='Yes' WHERE property_id='$property_id'";
                mysqli_query($db, $sql1);

                // Insert booking details into database
                $sql2 = "INSERT INTO booking (property_id, tenant_id, payment_mode, amount_paid, payment_due, next_payment_date)
                         VALUES ('$property_id', '$tenant_id', '$payment_mode', '$amount_paid', '$payment_due', '$next_payment_date')";
                $query2 = mysqli_query($db, $sql2);

                // Fetch owner details
                $sql3 = "SELECT owner_id FROM add_property WHERE property_id='$property_id'";
                $query3 = mysqli_query($db, $sql3);
                $owner_id = mysqli_fetch_assoc($query3)['owner_id'];

                $sql4 = "SELECT * FROM owner WHERE owner_id='$owner_id'";
                $query4 = mysqli_query($db, $sql4);
                $owner = mysqli_fetch_assoc($query4);
                $owners_name = $owner["full_name"];
                $owner_email = $owner["email"];

                // Send booking confirmation emails
                if ($query2) {
                    $tenant_email_body = "
                        <h1>Booking Successful</h1>
                        <p>Dear $tenant_name,</p>
                        <p>Your booking for the property with ID $property_id has been confirmed.</p>
                        <p>Property owner: $owners_name, Email: $owner_email</p>
                        <p>Regards,<br>Renthouse Team</p>";
                    sendMail($u_email, "Booking Confirmation", $tenant_email_body);

                    $owner_email_body = "
                        <h1>New Booking</h1>
                        <p>Dear $owners_name,</p>
                        <p>Your property with ID $property_id has been booked by $tenant_name.</p>
                        <p>Tenant's email: $u_email</p>
                        <p>Regards,<br>Renthouse Team</p>";
                    sendMail($owner_email, "New Booking for Your Property", $owner_email_body);

                    header("Location: payment-success.php?property_id=$property_id?owners_name=$owners_name?tenant_name=$tenant_name");
                    exit;
                }
            }
        } else {
            // echo "if failur";
            // Payment not successful
            header("Location: payment-failure.php?property_id=$property_id");
            exit;
        }
    } catch (Exception $e) {
        // echo "try failure";
        // Handle exceptions
        error_log($e->getMessage());
        header("Location: payment-failure.php?property_id=$property_id");
        exit;
    }
} else {
    // echo "if failur 2";
    header("Location: payment-failure.php?property_id=$property_id");
    exit;
}
?>
