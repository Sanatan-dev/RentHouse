<?php

session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}

include "navbar.php";
include "../config/config.php";
include "delete-details.php";
?>
<?php
// Assuming you have an active database connection
//total properties
$totalPQuery = "SELECT COUNT(*) AS total FROM add_property WHERE approved = 1 AND blocked = 0";
$resultP = mysqli_query($db, $totalPQuery);
$totalP = mysqli_fetch_assoc($resultP)['total'];

//total booked properties
$totalBooking = "SELECT COUNT(*) AS total from booking";
$resultBooking = mysqli_query($db, $totalBooking);
$totalBookedProperty = mysqli_fetch_assoc($resultBooking)['total'];

//total pending property
$totalPPQuery = "SELECT COUNT(*) AS total FROM add_property WHERE approved=0";
$resultPP = mysqli_query($db, $totalPPQuery);
$totalPP = mysqli_fetch_assoc($resultPP)['total'];

$totalBlockedQuery = "SELECT COUNT(*) AS total FROM add_property where blocked=1";
$resultBlocked = mysqli_query($db, $totalBlockedQuery);
$totalBlocked = mysqli_fetch_assoc($resultBlocked)['total'];
?>
<link rel="stylesheet" href="style.css">

<style>
    .table-section {
        display: none;
    }

    .s-container>form {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }

    .s-container>form>input {
        flex-grow: 3;
        width: 70%;
    }

    .s-container>form>button {
        flex-grow: 2;
    }

    .table-section {
        overflow-x: auto;
    }
</style>
<?php


$cardType = isset($_GET['card-type']) ? $_GET['card-type'] : 'total'; // Default to 'total'
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;  // Default to page 1 if not set

// Pagination settings
$itemsPerPage = 10;
$start = ($page - 1) * $itemsPerPage;

// // Handle search term, card type, and pagination
// Form parameters
$search_location = isset($_GET['search_location']) ? mysqli_real_escape_string($db, htmlspecialchars($_GET['search_location'])) : '';
$property_type = isset($_GET['property_type']) ? mysqli_real_escape_string($db, htmlspecialchars($_GET['property_type'])) : '';
$estimated_price = isset($_GET['estimated_price']) ? (int) $_GET['estimated_price'] : 0;  // Convert to integer
$min_rating = isset($_GET['min_rating']) ? (float) $_GET['min_rating'] : 0;  // Convert to float


$searchQuery = ""; // Default filters for approved and unblocked properties

if (!empty($search_location)) {
    $searchQuery .= " AND (country LIKE '%$search_location%' OR province LIKE '%$search_location%' OR city LIKE '%$search_location%')";
}

if (!empty($property_type)) {
    $searchQuery .= " AND property_type = '$property_type'";
}

if (!empty($estimated_price)) {
    $searchQuery .= " AND estimated_price <= $estimated_price";
}

// Include the minimum rating logic by joining the rating table
if (!empty($min_rating)) {
    $searchQuery .= " AND property_id IN (SELECT property_id FROM review GROUP BY property_id HAVING AVG(rating) >= $min_rating)";
}


// Construct the query based on card type (total, pending, or blocked)
if ($cardType == 'total') {
    $propertyQuery = "SELECT * FROM add_property WHERE 1=1 $searchQuery AND blocked = 0 AND approved = 1 LIMIT $start, $itemsPerPage";
    $totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property WHERE 1=1 $searchQuery AND approved = 1 AND blocked = 0";
    // $propertyQuery = "SELECT *
    //                   FROM add_property 
    //                   WHERE 1=1 $searchQuery AND apporoved = 1 AND blocked = 0
    //                   LIMIT $start, $itemsPerPage";
    // $totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property p WHERE 1=1 $searchQuery AND approved = 1 AND blocked = 0";
} elseif ($cardType == 'pending') {
    $propertyQuery = "SELECT * FROM add_property WHERE approved=0 $searchQuery LIMIT $start, $itemsPerPage";
    $totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property WHERE approved=0 $searchQuery";
} elseif ($cardType == 'blocked') {
    $propertyQuery = "SELECT * FROM add_property WHERE blocked=1 $searchQuery LIMIT $start, $itemsPerPage";
    $totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property WHERE blocked=1 $searchQuery";
} else if ($cardType == 'booked') {
    $propertyQuery = "SELECT * FROM add_property WHERE booked='Yes' $searchQuery LIMIT $start, $itemsPerPage";
    $totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property WHERE booked='Yes' $searchQuery";
}

// Execute queries
$propertyResult = mysqli_query($db, $propertyQuery);
$totalResult = mysqli_query($db, $totalPropertyQuery);
$totalProperty = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalProperty / $itemsPerPage);
?>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 col-sm-12">
            <?php include 'admin-sidebar.php'; ?>
        </div>
        <div class="col-md-10 col-sm-12">
            <h2>Property Section</h2>
            <div class="row">
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="totalPropertyCard">
                        <div class="card-body">
                            <h5 class="card-title">Total Property</h5>
                            <p class="card-text"><span
                                    class="badge badge-custom bg-primary"><?php echo $totalP; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="pendingPropertyCard">
                        <div class="card-body">
                            <h5 class="card-title">Pending Property</h5>
                            <p class="card-text"><span
                                    class="badge badge-custom bg-danger"><?php echo $totalPP; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="totalBookedPropertyCard">
                        <div class="card-body">
                            <h5 class="card-title">Booked Property</h5>
                            <p class="card-text"><span
                                    class="badge badge-custom bg-danger"><?php echo $totalBookedProperty; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="blockedPropertyCard">
                        <div class="card-body">
                            <h5 class="card-title">Blocked Property</h5>
                            <p class="card-text"><span
                                    class="badge badge-custom bg-danger"><?php echo $totalBlocked; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-section" id="totalPropertySection">
                <h4>Total Properties</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                                <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                                <input class="form-control" type="text" placeholder="Enter location"
                                    name="search_location" aria-label="Search Location">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="property_type">
                                    <option value="">Property Type</option>
                                    <option value="Full House Rent">Full House</option>
                                    <option value="Flat Rent">Flat</option>
                                    <option value="Room Rent">Room</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Max Price"
                                    name="estimated_price">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Min Rating" name="min_rating"
                                    step="0.1" max="5">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                        <!-- <input type="text" class="form-control" name="search" -->
                        <!-- placeholder="Enter Name/Email/Phone no/Address..." value="
                         <button type="submit" class="btn btn-primary">Search</button -->
                    </form>
                </div>
                <form method="POST" id="bulkActionForm">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Property ID</th>
                                <th>Owner ID</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Contact No.</th>
                                <th>Property Type</th>
                                <th>Estimated Price</th>
                                <th>Booked Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // $sql = "SELECT * FROM add_property WHERE approved = 1"; // Fetch pending property requests
                            $result = mysqli_query($db, $propertyQuery);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $property_id = $row['property_id'];
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_property[]' value='{$row['property_id']}'></td>";
                                    echo "<td>{$row['property_id']}</td>";
                                    echo "<td>{$row['owner_id']}</td>";
                                    echo "<td>{$row['province']}</td>";
                                    echo "<td>{$row['city']}</td>";
                                    echo "<td>{$row['contact_no']}</td>";
                                    echo "<td>{$row['property_type']}</td>";
                                    echo "<td>{$row['estimated_price']}</td>";
                                    echo "<td>{$row['booked']}</td>";
                                    echo "<td>
                                        <a href='view-property.php?property_id=$property_id' class='btn btn-info'>View</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="action" id="bulkAction">
                    <button type="button" class="btn btn-danger" onclick="submitBulkAction('delete')">Delete</button>
                    <button type="button" class="btn btn-warning" onclick="submitBulkAction('block')">Block</button>
                    <button type="button" class="btn btn-success" onclick="submitBulkAction('unblock')" disabled>Unblock</button>
                </form>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>

                        <!-- Current Page Number -->
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>

                        <!-- Next Page Link -->
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

            <!--- pending property section --->
            <div class="table-section" id="pendingPropertySection">
                <h4>Pending Properties</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                                <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                                <input class="form-control" type="text" placeholder="Enter location"
                                    name="search_location" aria-label="Search Location">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="property_type">
                                    <option value="">Property Type</option>
                                    <option value="Full House Rent">Full House</option>
                                    <option value="Flat Rent">Flat</option>
                                    <option value="Room Rent">Room</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Max Price"
                                    name="estimated_price">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Min Rating" name="min_rating"
                                    step="0.1" max="5">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm2">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Property ID</th>
                                <th>Owner ID</th>
                                <th>Country</th>
                                <th>City</th>
                                <th>Contact No.</th>
                                <th>Property Type</th>
                                <th>Estimated Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // $sql = "SELECT * FROM add_property WHERE approved = 0"; // Fetch pending property requests
                            $result = mysqli_query($db, $propertyQuery);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $property_id = $row['property_id'];
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_property[]' value='{$row['property_id']}'></td>";
                                    echo "<td>{$row['property_id']}</td>";
                                    echo "<td>{$row['owner_id']}</td>";
                                    echo "<td>{$row['country']}</td>";
                                    echo "<td>{$row['city']}</td>";
                                    echo "<td>{$row['contact_no']}</td>";
                                    echo "<td>{$row['property_type']}</td>";
                                    echo "<td>{$row['estimated_price']}</td>";
                                    echo "<td>
                                        <a href='view-property.php?property_id=$property_id' class='btn btn-info'>View</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="action" id="bulkAction2">
                    <button type="button" class="btn btn-primary"
                        onclick="submitBulkAction2('approved')">Approve</button>
                    <button type="button" class="btn btn-danger" onclick="submitBulkAction2('reject')" disabled>Reject</button>
                </form>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>

                        <!-- Current Page Number -->
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>

                        <!-- Next Page Link -->
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
            <div class="table-section" id="bookedPropertySection">
                <h4>Booked Properties</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                                <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                                <input class="form-control" type="text" placeholder="Enter location"
                                    name="search_location" aria-label="Search Location">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="property_type">
                                    <option value="">Property Type</option>
                                    <option value="Full House Rent">Full House</option>
                                    <option value="Flat Rent">Flat</option>
                                    <option value="Room Rent">Room</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Max Price"
                                    name="estimated_price">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Min Rating" name="min_rating"
                                    step="0.1" max="5">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <br>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Owner ID</th>
                            <th>Country</th>
                            <th>Province</th>
                            <th>City</th>
                            <th>Property Type</th>
                            <th>Estimated Price</th>
                            <th>Paid Ammount</th>
                            <th>Payment Date</th>
                            <th>Due Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // $sql = 'SELECT * FROM add_property WHERE booked="Yes"'; // Fetch pending property requests
                        $result = mysqli_query($db, $propertyQuery);
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                $property_id = $row['property_id'];
                                $payQuery = "SELECT amount_paid, DATE_FORMAT(payment_timestamp,'%d-%m-%Y') AS Payment_date,DATE_FORMAT(next_payment_date,'%d-%m-%Y') AS Due_date from booking where property_id ='$property_id'";
                                $payResult = mysqli_query($db, $payQuery);
                                $cols = mysqli_fetch_assoc($payResult);
                                echo "<td>{$row['property_id']}</td>";
                                echo "<td>{$row['owner_id']}</td>";
                                echo "<td>{$row['country']}</td>";
                                echo "<td>{$row['province']}</td>";
                                echo "<td>{$row['city']}</td>";
                                echo "<td>{$row['property_type']}</td>";
                                echo "<td>{$row['estimated_price']}</td>";
                                echo "<td>{$cols['amount_paid']}</td>";
                                echo "<td>{$cols['Payment_date']}</td>";
                                echo "<td>{$cols['Due_date']}</td>";
                                echo "<td>
                                        <a href='view-property.php?property_id=$property_id' class='btn btn-info'>View</a>
                                    </td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>

                        <!-- Current Page Number -->
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>

                        <!-- Next Page Link -->
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>

            <!--- Blocked property Section --->
            <div class="table-section" id="blockedPropertySection">
                <h4>Blocked Properties</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                                <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                                <input class="form-control" type="text" placeholder="Enter location"
                                    name="search_location" aria-label="Search Location">
                            </div>
                            <div class="col-md-3">
                                <select class="form-control" name="property_type">
                                    <option value="">Property Type</option>
                                    <option value="Full House Rent">Full House</option>
                                    <option value="Flat Rent">Flat</option>
                                    <option value="Room Rent">Room</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Max Price"
                                    name="estimated_price">
                            </div>
                            <div class="col-md-2">
                                <input class="form-control" type="number" placeholder="Min Rating" name="min_rating"
                                    step="0.1" max="5">
                            </div>
                            <div class="col-md-1">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm3">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Property ID</th>
                                <th>Owner ID</th>
                                <th>Country</th>
                                <th>City</th>
                                <th>Contact No.</th>
                                <th>Property Type</th>
                                <th>Estimated Price</th>
                                <!-- <th>rating</th> -->
                                <th>Booked Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = mysqli_query($db, $propertyQuery);
                            if ($result && mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $property_id = $row['property_id'];
                                    // $ratingquery = "SELECT rating FROM review WHERE property_id = $property_id";
                                    // $ratingResult = mysqli_query($db, $ratingquery);
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_property[]' value='{$row['property_id']}'></td>";
                                    echo "<td>{$row['property_id']}</td>";
                                    echo "<td>{$row['owner_id']}</td>";
                                    echo "<td>{$row['country']}</td>";
                                    echo "<td>{$row['city']}</td>";
                                    echo "<td>{$row['contact_no']}</td>";
                                    echo "<td>{$row['property_type']}</td>";
                                    echo "<td>{$row['estimated_price']}</td>";
                                    echo "<td>{$row['booked']}</td>";
                                    // if ($ratingResult) {
                                    //     echo "<td>$ratingResult</td>";
                                    // } else {
                                    //     echo "N/A";
                                    // }
                                    echo "<td>
                                        <a href='view-property.php?property_id=$property_id' class='btn btn-info'>View</a>
                                    </td>";
                                    echo "</tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                    <input type="hidden" name="action" id="bulkAction3">
                    <button type="button" class="btn btn-danger" onclick="submitBulkAction3('delete')" disabled>Delete</button>
                    <button type="button" class="btn btn-warning" onclick="submitBulkAction3('block')"
                        disabled>Block</button>
                    <button type="button" class="btn btn-success"
                        onclick="submitBulkAction3('unblock')">Unblock</button>
                </form>
                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <!-- Previous Page Link -->
                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>

                        <!-- Current Page Number -->
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>

                        <!-- Next Page Link -->
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="property-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>"
                                aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>

            </div>
        </div>
    </div>
</div>
<script src="property.js"></script>
<?php include 'property-engine2.php'; ?>