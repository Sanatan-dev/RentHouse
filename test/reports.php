<?php

session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}

include "navbar.php";
include "../config/config.php";
include "delete-details.php";
?>
<link rel="stylesheet" href="style.css"> <!-- Link to the CSS file -->
<script src="scripts.js"></script> <!-- Link to the JS file -->

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 col-sm-12">
            <?php include 'admin-sidebar.php'; ?>
        </div>
        <!-- Main Content -->
        <div class="col-md-10 col-sm-12">
            <?php
            // Assuming $db is your database connection
            $currentYear = date("Y");

            // Query to fetch bookings count per month for the current year
            $query = " SELECT MONTH(payment_timestamp) as month, COUNT(*) as count
                    FROM booking
                    WHERE YEAR(payment_timestamp) = $currentYear
                    GROUP BY MONTH(payment_timestamp)
                    ORDER BY month ASC";

            $result = mysqli_query($db, $query);

            // Initialize an array to store the monthly data
            $bookingsPerMonth = array_fill(1, 12, 0); // Fill array with 12 months, default value 0
            
            while ($row = mysqli_fetch_assoc($result)) {
                $month = $row['month'];
                $bookingsPerMonth[$month] = $row['count'];
            }

            $paymentsQuery = "SELECT MONTH(payment_timestamp) as month, SUM(amount_paid) as total_payments
                                FROM booking
                                WHERE YEAR(payment_timestamp) = $currentYear
                                GROUP BY MONTH(payment_timestamp)
                                ORDER BY month ASC";

            $paymentsResult = mysqli_query($db, $paymentsQuery);

            // Initialize an array to store monthly payment data
            $paymentsPerMonth = array_fill(1, 12, 0); // Fill array with 12 months, default value 0
            
            while ($row = mysqli_fetch_assoc($paymentsResult)) {
                $month = $row['month'];
                $paymentsPerMonth[$month] = $row['total_payments'];
            }
            ?>
            <div class="col-md-5 col-sm-12">
                <canvas id="monthlyBookingsChart" width="800" height="600"></canvas>
            </div>
            <div class="col-md-5 col-sm-12">
                <canvas id="monthlyPaymentsChart" width="800" height="600"></canvas>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get data from PHP
    const bookingsPerMonth = <?php echo json_encode(array_values($bookingsPerMonth)); ?>;
    const paymentsPerMonth = <?php echo json_encode(array_values($paymentsPerMonth)); ?>;

    const ctx = document.getElementById('monthlyBookingsChart').getContext('2d');
    const monthlyBookingsChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ],
            datasets: [{
                label: 'Bookings per Month (<?php echo $currentYear; ?>)',
                data: bookingsPerMonth,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Number of Bookings'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });


    // Render Monthly Payments Chart
    const ctx2 = document.getElementById('monthlyPaymentsChart').getContext('2d');
    const monthlyPaymentsChart = new Chart(ctx2, {
        type: 'line',
        data: {
            labels: [
                'January', 'February', 'March', 'April', 'May', 'June',
                'July', 'August', 'September', 'October', 'November', 'December'
            ],
            datasets: [{
                label: 'Payments per Month (<?php echo $currentYear; ?>)',
                data: paymentsPerMonth,
                backgroundColor: 'rgba(153, 102, 255, 0.2)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Payments'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Month'
                    }
                }
            }
        }
    });
</script>