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
            <h3>Filter Reports</h3>
            <form method="POST" action="reports2.php">
                <div class="form-group">
                    <label for="city">City:</label>
                    <select name="city" id="city" class="form-control">
                        <option value="">Select City</option>
                        <?php
                        // Query to fetch distinct cities from the add_property table
                        $cityQuery = "SELECT DISTINCT city FROM add_property";
                        $cityResult = mysqli_query($db, $cityQuery);
                        while ($row = mysqli_fetch_assoc($cityResult)) {
                            echo "<option value='" . $row['city'] . "'>" . $row['city'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="province">Province:</label>
                    <select name="province" id="province" class="form-control">
                        <option value="">Select Province</option>
                        <?php
                        // Query to fetch distinct provinces
                        $provinceQuery = "SELECT DISTINCT province FROM add_property";
                        $provinceResult = mysqli_query($db, $provinceQuery);
                        while ($row = mysqli_fetch_assoc($provinceResult)) {
                            echo "<option value='" . $row['province'] . "'>" . $row['province'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="property_type">Property Type:</label>
                    <select name="property_type" id="property_type" class="form-control">
                        <option value="">Select Property Type</option>
                        <?php
                        // Query to fetch distinct property types
                        $propertyTypeQuery = "SELECT DISTINCT property_type FROM add_property";
                        $propertyTypeResult = mysqli_query($db, $propertyTypeQuery);
                        while ($row = mysqli_fetch_assoc($propertyTypeResult)) {
                            echo "<option value='" . $row['property_type'] . "'>" . $row['property_type'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filter</button>
            </form>

            <?php
            // Get filter values from the form
            $city = $_POST['city'] ?? '';
            $province = $_POST['province'] ?? '';
            $property_type = $_POST['property_type'] ?? '';

            // Get current year
            $currentYear = date("Y");

            // Query to fetch bookings count per month based on selected filters
            $bookingQuery = "SELECT MONTH(payment_timestamp) as month, COUNT(*) as count
                             FROM booking b
                             JOIN add_property p ON b.property_id = p.property_id
                             WHERE YEAR(payment_timestamp) = $currentYear";

            if ($city) {
                $bookingQuery .= " AND p.city = '$city'";
            }
            if ($province) {
                $bookingQuery .= " AND p.province = '$province'";
            }
            if ($property_type) {
                $bookingQuery .= " AND p.property_type = '$property_type'";
            }

            $bookingQuery .= " GROUP BY MONTH(payment_timestamp) ORDER BY month ASC";

            $result = mysqli_query($db, $bookingQuery);

            // Initialize an array to store the monthly data
            $bookingsPerMonth = array_fill(1, 12, 0); // Fill array with 12 months, default value 0
            
            while ($row = mysqli_fetch_assoc($result)) {
                $month = $row['month'];
                $bookingsPerMonth[$month] = $row['count'];
            }

            // Query to fetch total payments per month based on selected filters
            $paymentQuery = "SELECT MONTH(payment_timestamp) as month, SUM(amount_paid) as total_payments
                             FROM booking b
                             JOIN add_property p ON b.property_id = p.property_id
                             WHERE YEAR(payment_timestamp) = $currentYear";

            if ($city) {
                $paymentQuery .= " AND p.city = '$city'";
            }
            if ($province) {
                $paymentQuery .= " AND p.province = '$province'";
            }
            if ($property_type) {
                $paymentQuery .= " AND p.property_type = '$property_type'";
            }

            $paymentQuery .= " GROUP BY MONTH(payment_timestamp) ORDER BY month ASC";

            $paymentsResult = mysqli_query($db, $paymentQuery);

            // Initialize an array to store monthly payment data
            $paymentsPerMonth = array_fill(1, 12, 0); // Fill array with 12 months, default value 0
            
            while ($row = mysqli_fetch_assoc($paymentsResult)) {
                $month = $row['month'];
                $paymentsPerMonth[$month] = $row['total_payments'];
            }

            // Query to fetch total properties booked based on selected filters
            $propertiesBookedQuery = "SELECT COUNT(*) as total_booked
                                      FROM booking b
                                      JOIN add_property p ON b.property_id = p.property_id
                                      WHERE YEAR(payment_timestamp) = $currentYear";

            if ($city) {
                $propertiesBookedQuery .= " AND p.city = '$city'";
            }
            if ($province) {
                $propertiesBookedQuery .= " AND p.province = '$province'";
            }
            if ($property_type) {
                $propertiesBookedQuery .= " AND p.property_type = '$property_type'";
            }

            $propertiesBookedResult = mysqli_query($db, $propertiesBookedQuery);
            $propertiesBookedRow = mysqli_fetch_assoc($propertiesBookedResult);
            $totalBookedProperties = $propertiesBookedRow['total_booked'];
            ?>

            <div class="col-md-5 col-sm-12">
                <h4>Monthly Bookings (<?php echo $currentYear; ?>)</h4>
                <canvas id="monthlyBookingsChart" width="800" height="600"></canvas>
            </div>
            <div class="col-md-5 col-sm-12">
                <h4>Monthly Payments (<?php echo $currentYear; ?>)</h4>
                <canvas id="monthlyPaymentsChart" width="800" height="600"></canvas>
            </div>

            <div class="col-md-12">
                <h4>Total Properties Booked in <?php echo $currentYear; ?> (Filtered by City/Province/Type):</h4>
                <p>Total Properties Booked: <?php echo $totalBookedProperties; ?></p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Get data from PHP
    const bookingsPerMonth = <?php echo json_encode(array_values($bookingsPerMonth)); ?>;
    const paymentsPerMonth = <?php echo json_encode(array_values($paymentsPerMonth)); ?>;

    // Render Monthly Bookings Line Chart
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
                fill: false,
                borderColor: 'rgba(75, 192, 192, 1)',
                tension: 0.1
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

    // Render Monthly Payments Line Chart
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
                fill: false,
                borderColor: 'rgba(153, 102, 255, 1)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Total Payments (₹)'
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
</body>
</html>
