<?php
include("navbar.php");
$property_id = $_GET['property_id'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Failed</title>
    <!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f5f7fa;
            /* padding-top: 60px; Adjusts for navbar height */
        }

        .failure-container {
            max-width: 600px;
            margin: 3rem auto;
            background-color: #ffffff;
            padding: 3rem;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-align: center;
            animation: fadeIn 1s ease-in-out;
        }

        h1 {
            color: #dc3545;
            font-size: 2rem;
            font-weight: 700;
            margin-top: 1rem;
        }

        .crossmark {
            font-size: 4rem;
            color: #dc3545;
            animation: scaleIn 0.5s ease-in-out;
        }

        p {
            color: #333;
            font-size: 1.1rem;
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
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes scaleIn {
            from { transform: scale(0.5); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container failure-container">
        <div class="crossmark">❌</div>
        <h1>Booking Failed</h1>
        <p>Unfortunately, your booking could not be processed. Please try again or contact support.</p>
        <a href="view-property.php?property_id=<?php echo htmlspecialchars($property_id); ?>" class="btn btn-primary mt-3">Retry Payment</a>
    </div>
</body>
</html>
