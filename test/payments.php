<?php

session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}

include "navbar.php";
include "../config/config.php";
include "delete-details.php";

$cardType = isset($_GET['card-type']) ? $_GET['card-type'] : 'monthly'; // Default to 'total'
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;  // Default to page 1 if not set

// Fetch monthly and total payments from booking table
$monthlyQuery = "SELECT SUM(amount_paid) AS monthly_total FROM booking WHERE MONTH(payment_timestamp) = MONTH(CURRENT_DATE()) AND YEAR(payment_timestamp) = YEAR(CURRENT_DATE())";
$monthlyResult = mysqli_query($db, $monthlyQuery);
$monthlyPayment = mysqli_fetch_assoc($monthlyResult)['monthly_total'] ?? 0;

$totalQuery = "SELECT SUM(amount_paid) AS total_payment FROM booking";
$totalResult = mysqli_query($db, $totalQuery);
$totalPayment = mysqli_fetch_assoc($totalResult)['total_payment'] ?? 0;

if ($cardType == 'monthly') {
    $monthlyQuery = "SELECT * FROM booking WHERE MONTH(payment_timestamp) = MONTH(CURRENT_DATE()) AND YEAR(payment_timestamp) = YEAR(CURRENT_DATE())";
    $totalPaymentsQuery = "SELECT COUNT(*) as total FROM booking WHERE MONTH(payment_timestamp) = MONTH(CURRENT_DATE()) AND YEAR(payment_timestamp) = YEAR(CURRENT_DATE())";
} elseif ($cardType == 'total') {
    $totalQuery = "SELECT * FROM booking";
    $totalPaymentsQuery = "SELECT COUNT(*) as total FROM booking";
}

?>
<style>
    table {
        overflow-x: auto;
    }
</style>

<link rel="stylesheet" href="style.css"> <!-- Link to the CSS file -->
<!-- <script src="scripts.js"></script> Link to the JS file -->

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 col-sm-12">
            <?php include 'admin-sidebar.php'; ?>
        </div>
        <!-- Main Content -->
        <div class="col-md-10 col-sm-12">
            <h2 class="mt-4">Payments section</h2>
            <div class="row">
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="monthlyPaymentCard">
                        <div class="card-body">
                            <h5 class="card-title">Current Months Payments</h5>
                            <p class="card-text"><span
                                    class="badge badge-custom bg-primary"><?php echo $monthlyPayment; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="totalPaymentCard">
                        <div class="card-body">
                            <h5 class="card-title">Total Payments</h5>
                            <p class="card-text"><span
                                    class="badge badge-custom bg-danger"><?php echo $totalPayment; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-section" id="monthlyPaymentSection">
                <h4>Current Monthly Payments</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <!-- <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php //echo $search; ?>"> -->
                        <!-- <button type="submit" class="btn btn-primary">Search</button> -->
                    </form>
                    <br>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>booking_id</th>
                                <th>property_id</th>
                                <th>tenant_id</th>
                                <th>payment_mode</th>
                                <th>payment_date</th>
                                <th>due_payment_date</th>
                                <th>amount_paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Fetch and display pending owner requests
                            $monthlyResult = mysqli_query($db, $monthlyQuery);
                            $totalAmount = 0;
                            if (mysqli_num_rows($monthlyResult) > 0) {
                                while ($row = mysqli_fetch_assoc($monthlyResult)) {
                                    echo "<tr>";
                                    echo "<td>{$row['booking_id']}</td>";
                                    echo "<td>{$row['property_id']}</td>";
                                    echo "<td>{$row['tenant_id']}</td>";
                                    echo "<td>{$row['payment_mode']}</td>";
                                    echo "<td>{$row['payment_timestamp']}</td>";
                                    echo "<td>{$row['next_payment_date']}</td>";
                                    echo "<td>{$row['amount_paid']}</td>";
                                    echo "</tr>";
                                    $totalAmount += $row['amount_paid'];
                                }
                                echo "<tr><td colspan='6'><b>Total Amount:</b></td><td><b>$totalAmount</b></td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- PDF Download Button -->
                    <form method="POST" action="generate-pdf.php" target="_blank">
                        <input type="hidden" name="month" value="<?php echo $selectedMonth; ?>">
                        <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
                        <button type="submit" class="btn btn-success">Download PDF</button>
                    </form>
                </div>
            </div>
            <div class="table-section" id="totalPaymentSection">
                <h4>Total Payments</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <div class="row">
                            <div class="col-md-2">
                                <select class="form-control" name="month">
                                    <option value="">Select Month</option>
                                    <option value="1">January</option>
                                    <option value="2">February</option>
                                    <option value="3">March</option>
                                    <option value="4">April</option>
                                    <option value="5">May</option>
                                    <option value="6">June</option>
                                    <option value="7">July</option>
                                    <option value="8">August</option>
                                    <option value="9">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" name="year" placeholder="Select Year"
                                    min="2000" max="<?php echo date("Y"); ?>" required>
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                    <br>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Property ID</th>
                                <th>Tenant ID</th>
                                <th>Payment Mode</th>
                                <th>Payment Date</th>
                                <th>Due Payment Date</th>
                                <th>Amount Paid</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Get selected month and year from form submission
                            $selectedMonth = isset($_GET['month']) ? $_GET['month'] : '';
                            $selectedYear = isset($_GET['year']) ? $_GET['year'] : '';

                            // Check for invalid selection
                            if ($selectedMonth && !$selectedYear) {
                                echo "<tr><td colspan='7'>Please select a year when selecting a month.</td></tr>";
                            } else {
                                // Base query
                                $totalQuery = "SELECT booking_id, property_id, tenant_id, payment_mode, payment_timestamp, next_payment_date, amount_paid FROM booking";

                                // Add conditions based on selected month and year
                                $conditions = [];
                                if ($selectedMonth) {
                                    $conditions[] = "MONTH(payment_timestamp) = '$selectedMonth'";
                                }
                                if ($selectedYear) {
                                    $conditions[] = "YEAR(payment_timestamp) = '$selectedYear'";
                                }

                                // Append conditions to the query
                                if (!empty($conditions)) {
                                    $totalQuery .= " WHERE " . implode(" AND ", $conditions);
                                }

                                // Execute the query
                                $totalResult = mysqli_query($db, $totalQuery);
                                $totalAmount = 0;

                                // Display filtered results
                                if (mysqli_num_rows($totalResult) > 0) {
                                    while ($row = mysqli_fetch_assoc($totalResult)) {
                                        echo "<tr>";
                                        echo "<td>{$row['booking_id']}</td>";
                                        echo "<td>{$row['property_id']}</td>";
                                        echo "<td>{$row['tenant_id']}</td>";
                                        echo "<td>{$row['payment_mode']}</td>";
                                        echo "<td>{$row['payment_timestamp']}</td>";
                                        echo "<td>{$row['next_payment_date']}</td>";
                                        echo "<td>{$row['amount_paid']}</td>";
                                        echo "</tr>";
                                        $totalAmount += $row['amount_paid'];
                                    }
                                    echo "<tr><td colspan='6'><b>Total Amount:</b></td><td><b>$totalAmount</b></td></tr>";
                                } else {
                                    echo "<tr><td colspan='7'>No transactions found for the selected month and year.</td></tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <!-- PDF Download Button -->
                    <form method="POST" action="generate-pdf.php" target="_blank">
                        <input type="hidden" name="month" value="<?php echo $selectedMonth; ?>">
                        <input type="hidden" name="year" value="<?php echo $selectedYear; ?>">
                        <button type="submit" class="btn btn-success">Download PDF</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var pageInput = document.getElementById("page");
    var cardTypeInput = document.getElementById("card-type");

    var monthlyPaymentCard = document.getElementById("monthlyPaymentCard");
    var totalPaymentCard = document.getElementById("totalPaymentCard");


    monthlyPaymentCard.addEventListener("click", function () {
        cardTypeInput.value = "monthly";
        pageInput.value = 1; // Reset page
        document.getElementById("searchForm").submit();
    });

    totalPaymentCard.addEventListener("click", function () {
        cardTypeInput.value = "total";
        pageInput.value = 1; // Reset page
        document.getElementById("searchForm").submit();
    });

    var monthlyPaymentSection = document.getElementById("monthlyPaymentSection");
    var totalPaymentSection = document.getElementById("totalPaymentSection");

    if (cardTypeInput.value == "monthly") {
        monthlyPaymentSection.style.display = "block";
        totalPaymentSection.style.display = "none";
    } else if (cardTypeInput.value == "total") {
        totalPaymentSection.style.display = "block";
        monthlyPaymentSection.style.display = "none";
    }
</script>