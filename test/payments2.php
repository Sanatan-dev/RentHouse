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
                            <h5 class="card-title">Current Monthly Payments</h5>
                            <p class="card-text"><span class="badge badge-custom bg-primary"><?php echo 70000; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="totalPaymentCard">
                        <div class="card-body">
                            <h5 class="card-title">Total Payments</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo 90000; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Payment Section -->
        <div class="table-section" id="monthlyPaymentSection" style="display: none;">
            <h4>Current Monthly Payments</h4>
            <div class="search s-container">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Owner ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Address</th>
                            <th>Type of ID</th>
                            <th>ID Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display monthly payment details here
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Payment Section -->
        <div class="table-section" id="totalPaymentSection" style="display: none;">
            <h4>Total Payments</h4>
            <div class="search s-container">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Owner ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Address</th>
                            <th>Type of ID</th>
                            <th>ID Photo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch and display total payment details here
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    // JavaScript to handle card clicks and display the respective section
    document.getElementById("monthlyPaymentCard").addEventListener("click", function () {
        document.getElementById("monthlyPaymentSection").style.display = "block";
        document.getElementById("totalPaymentSection").style.display = "none";
    });

    document.getElementById("totalPaymentCard").addEventListener("click", function () {
        document.getElementById("totalPaymentSection").style.display = "block";
        document.getElementById("monthlyPaymentSection").style.display = "none";
    });
</script>
