<?php
include("navbar.php");
$property_id = $_GET['property_id'] ?? '';
$tenant_name = $_GET['tenant_name'] ?? '';
$owners_name = $_GET['owners_name'] ?? '';
$receipt_no = mt_rand(1, 100);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            /* Adjusts for navbar height */
        }

        .success-container {
            max-width: 600px;
            margin: 3rem auto;
            /* Centers the container on the page */
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            color: #28a745;
            font-size: 2rem;
            font-weight: 700;
            margin-top: 1rem;
        }

        .checkmark {
            font-size: 4rem;
            color: #28a745;
            animation: scaleIn 0.5s ease-in-out;
        }

        p {
            color: #333;
            font-size: 1.5rem;
            margin: 1rem 0;
        }

        .btn-primary {
            background-color: #3399cc;
            border-color: #3399cc;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #2879a9;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.5);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <div class="container success-container">
        <div class="checkmark">✅</div>
        <h1>Booking Successful</h1>
        <p>Your booking has been confirmed for Property ID: <?php echo htmlspecialchars($property_id); ?></p>
        <form method="POST" action="generate_receipt.php" target="_blank">
            <!-- Hidden Inputs to Post Data -->
            <input type="hidden" name="property_id" value="<?php echo $property_id; ?>">
            <input type="hidden" name="tenant_name" value="<?php echo $tenant_name; ?>">
            <input type="hidden" name="owners_name" value="<?php echo $owners_name; ?>">
            <input type="hidden" name="receipt_no" value="<?php echo $receipt_no; ?>">

            <!-- Submit Button -->
            <button type="submit" class="btn btn-success">Download Receipt</button>
        </form>
        <br>
        <a href="booked-property.php" class="btn btn-primary mt-3">Back to Property</a>
    </div>
</body>

</html>