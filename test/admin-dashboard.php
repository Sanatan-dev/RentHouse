<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}
include "navbar.php";
include "../config/config.php";
include "delete-details.php"; // Ensure this is still included if needed.

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
            <h1 class="mt-4">Admin Dashboard</h1>
            <div id="dashboardContent">
                <?php include 'admin-overview.php'; ?>
            </div>
        </div>
    </div>
</div>
