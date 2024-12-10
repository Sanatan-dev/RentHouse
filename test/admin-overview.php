<?php

// session_start();
// if (!isset($_SESSION["email"])) {
//     header("location:../index.php");
// }

// include "navbar.php";
include "../config/config.php";
// include "delete-details.php";
?>
<?php
// Assuming you have an active database connection

// Query to get the total number of owners
$totalOwnersQuery = "SELECT COUNT(*) as total FROM owner";
$resultOwners = mysqli_query($db, $totalOwnersQuery);
$totalOwners = mysqli_fetch_assoc($resultOwners)['total'];

// Query to get the total number of tenants
$totalTenantsQuery = "SELECT COUNT(*) as total FROM tenant";
$resultTenants = mysqli_query($db, $totalTenantsQuery);
$totalTenants = mysqli_fetch_assoc($resultTenants)['total'];

// Calculate the total users as the sum of owners and tenants
$totalUsers = $totalOwners + $totalTenants;

$totalPropertyQuery = "SELECT COUNT(*) AS total FROM add_property WHERE approved = 1";
$resultProperty = mysqli_query($db, $totalPropertyQuery);
$totalProperty = mysqli_fetch_assoc($resultProperty)['total'];

$totalPayments = 0;
$totalBooking = "SELECT * from booking";
$resultBooking = mysqli_query($db, $totalBooking);
while ($rows = mysqli_fetch_assoc($resultBooking)) {
    $property_id = $rows['property_id'];

    $sql = "SELECT SUM(estimated_price) as total FROM add_property where approved = 1 AND blocked = 0 AND booked='Yes'";
    $result = mysqli_query($db, $sql);
    $payment = mysqli_fetch_assoc($result)['total'];
    $totalPayments = $payment;
}

$totalPOQuery = "SELECT COUNT(*) as total FROM owner WHERE approved=0";
$resultPO = mysqli_query($db, $totalPOQuery);
$totalPO = mysqli_fetch_assoc($resultPO)['total'];

// Query to get the total number of tenants
$totalPTQuery = "SELECT COUNT(*) as total FROM tenant WHERE approved=0";
$resultPT = mysqli_query($db, $totalPTQuery);
$totalPT = mysqli_fetch_assoc($resultPT)['total'];

$totalPPQuery = "SELECT COUNT(*) AS total FROM add_property WHERE approved=0";
$resultPP = mysqli_query($db, $totalPPQuery);
$totalPP = mysqli_fetch_assoc($resultPP)['total'];

// Calculate the total users as the sum of owners and tenants
$totalPQ = $totalPO + $totalPT + $totalPP;

?>
<style>
    .card{
        cursor:pointer;
    }
</style>
<div class="row">
    <div class="col-md-3 col-sm-6 mb-3" id="user-card">
        <div class="card card-gradient">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="card-text"><span class="badge bg-primary"><?php echo $totalUsers; ?></span></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3" id="property-card">
        <div class="card card-gradient">
            <div class="card-body">
                <h5 class="card-title">Total Properties</h5>
                <p class="card-text"><span class="badge bg-success"><?php echo $totalProperty; ?></span></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3" id="payment-card">
        <div class="card card-gradient">
            <div class="card-body">
                <h5 class="card-title">Total Payments</h5>
                <p class="card-text"><span class="badge bg-warning"><?php echo $totalPayments; ?></span></p>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card card-gradient">
            <div class="card-body">
                <h5 class="card-title">Pending Requests</h5>
                <p class="card-text"><span class="badge bg-danger"><?php echo $totalPQ; ?></span></p>
            </div>
        </div>
    </div>
</div>

<script>
    const usercard = document.getElementById('user-card');
    const propertycard = document.getElementById('property-card');
    const paymentcard = document.getElementById('payment-card');

    paymentcard.addEventListener('click',(event)=>{
        window.location.href = "payments.php";
    });

    usercard.addEventListener('click',(event)=>{
        window.location.href = "owners-section.php";
    });

    propertycard.addEventListener('click',(event)=>{
        window.location.href = "property-section.php";
    })

</script>
