<?php
// Assuming database connection is active
include "navbar.php";
include "../config/config.php";

// Handle search term, card type, and pagination
$search = isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '';
$cardType = isset($_GET['card-type']) ? $_GET['card-type'] : 'total'; // Default to 'total'
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Default to page 1 if not set

// Pagination settings
$itemsPerPage = 10;
$start = ($page - 1) * $itemsPerPage;

// Search query
$searchQuery = "";
if (!empty($search)) {
    $searchQuery = "AND (full_name LIKE '%$search%' OR email LIKE '%$search%' OR phone_no LIKE '%$search%' OR address LIKE '%$search%')";
}

// Construct the query based on card type (total, pending, or blocked)
if ($cardType == 'total') {
    $tenantsQuery = "SELECT * FROM tenant WHERE 1=1 $searchQuery LIMIT $start, $itemsPerPage";
    $totalTenantsQuery = "SELECT COUNT(*) as total FROM tenant WHERE 1=1 $searchQuery";
} elseif ($cardType == 'pending') {
    $tenantsQuery = "SELECT * FROM tenant WHERE approved=0 $searchQuery LIMIT $start, $itemsPerPage";
    $totalTenantsQuery = "SELECT COUNT(*) as total FROM tenant WHERE approved=0 $searchQuery";
} elseif ($cardType == 'blocked') {
    $tenantsQuery = "SELECT * FROM tenant WHERE blocked=1 $searchQuery LIMIT $start, $itemsPerPage";
    $totalTenantsQuery = "SELECT COUNT(*) as total FROM tenant WHERE blocked=1 $searchQuery";
}

// Execute queries
$tenantsResult = mysqli_query($db, $tenantsQuery);
$totalResult = mysqli_query($db, $totalTenantsQuery);
$totalTenants = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalTenants / $itemsPerPage);
?>

<link rel="stylesheet" href="style.css">
<style>
    .table-section {
        display: none;
    }
    .s-container {
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
    }
    .s-container > input {
        flex-grow: 3;
        width: 70%;
    }
    .s-container > button {
        flex-grow: 2;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 col-sm-12">
            <?php include 'admin-sidebar.php'; ?>
        </div>
        <div class="col-md-10 col-sm-12">
            <h2>Tenants Section</h2>
            <div class="row">
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="totalTenantCard">
                        <div class="card-body">
                            <h5 class="card-title">Total Tenants</h5>
                            <p class="card-text"><span class="badge badge-custom bg-primary"><?php echo $totalTenants; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="pendingTenantCard">
                        <div class="card-body">
                            <h5 class="card-title">Pending Tenants</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo $totalTenants; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="blockedTenantCard">
                        <div class="card-body">
                            <h5 class="card-title">Blocked Tenants</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo $totalTenants; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table Section - Search and Results -->
            <div class="table-section" id="tenantTable">
                <h4>Tenants Table</h4>

                <!-- Search bar -->
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>

                <!-- Table of Tenants -->
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Tenant ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Phone No.</th>
                            <th>Address</th>
                            <th>Type of ID</th>
                            <th>ID Photo</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($tenantsResult) > 0) {
                            while ($row = mysqli_fetch_assoc($tenantsResult)) {
                                echo "<tr>";
                                echo "<td>{$row['tenant_id']}</td>";
                                echo "<td>{$row['full_name']}</td>";
                                echo "<td>{$row['email']}</td>";
                                echo "<td>{$row['phone_no']}</td>";
                                echo "<td>{$row['address']}</td>";
                                echo "<td>{$row['id_type']}</td>";
                                echo "<td><img src='../{$row['id_photo']}' width='50'></td>";
                                echo "<td>
                                        <form method='POST' style='display: inline;'>
                                            <input type='hidden' name='property_id' value='{$row['tenant_id']}'>
                                            <button type='submit' class='btn btn-danger' name='block_tenant'>Block</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>No tenants found</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="tenants.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="tenants.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript to Handle Card Click and Table Display -->
<script>
    var tenantCard = document.getElementById('totalTenantCard');
    var pendingTenantCard = document.getElementById('pendingTenantCard');
    var blockedTenantCard = document.getElementById('blockedTenantCard');
    var cardTypeInput = document.getElementById('card-type');
    var pageInput = document.getElementById('page');
    var tenantTable = document.getElementById('tenantTable');

    // Hide table initially
    tenantTable.style.display = 'none';

    // Event listeners for card clicks
    tenantCard.addEventListener('click', function() {
        cardTypeInput.value = 'total';
        pageInput.value = 1;  // Reset page
        document.getElementById('searchForm').submit();
    });

    pendingTenantCard.addEventListener('click', function() {
        cardTypeInput.value = 'pending';
        pageInput.value = 1;  // Reset page
        document.getElementById('searchForm').submit();
    });

    blockedTenantCard.addEventListener('click', function() {
        cardTypeInput.value = 'blocked';
        pageInput.value = 1;  // Reset page
        document.getElementById('searchForm').submit();
    });

    // Show the correct table based on card type (on page load or after form submit)
    if (cardTypeInput.value) {
        tenantTable.style.display = 'block';
    }
</script>

<?php
// Assuming database connection is active
include "navbar.php";
include "../config/config.php";

// Handle bulk actions (delete, block, unblock)
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_tenants'])) {
    $selectedTenants = $_POST['selected_tenants'];
    $tenantIds = implode(',', $selectedTenants); // Convert array to comma-separated list

    if ($_POST['action'] == 'delete') {
        // Delete selected tenants
        $deleteQuery = "DELETE FROM tenant WHERE tenant_id IN ($tenantIds)";
        mysqli_query($db, $deleteQuery);
    } elseif ($_POST['action'] == 'block') {
        // Block selected tenants
        $blockQuery = "UPDATE tenant SET blocked=1 WHERE tenant_id IN ($tenantIds)";
        mysqli_query($db, $blockQuery);
    } elseif ($_POST['action'] == 'unblock') {
        // Unblock selected tenants
        $unblockQuery = "UPDATE tenant SET blocked=0 WHERE tenant_id IN ($tenantIds)";
        mysqli_query($db, $unblockQuery);
    }
}
?>

<!-- Add Bulk Actions and checkboxes in table -->
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-sm-12">
            <h2>Tenants Section</h2>

            <!-- Bulk Action Buttons -->
            <form method="POST" id="bulkActionForm">
                <input type="hidden" name="action" id="bulkAction">
                <button type="button" class="btn btn-danger" onclick="submitBulkAction('delete')">Delete</button>
                <button type="button" class="btn btn-warning" onclick="submitBulkAction('block')">Block</button>
                <button type="button" class="btn btn-success" onclick="submitBulkAction('unblock')">Unblock</button>

                <!-- Table displaying tenants -->
                <div class="table-section" id="totalTenantTable">
                    <h4>Total Tenants</h4>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Tenant ID</th>
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
                            $tenantsResult = mysqli_query($db, $tenantsQuery);
                            if (mysqli_num_rows($tenantsResult) > 0) {
                                while ($row = mysqli_fetch_assoc($tenantsResult)) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_tenants[]' value='{$row['tenant_id']}'></td>";
                                    echo "<td>{$row['tenant_id']}</td>";
                                    echo "<td>{$row['full_name']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['phone_no']}</td>";
                                    echo "<td>{$row['address']}</td>";
                                    echo "<td>{$row['id_type']}</td>";
                                    echo "<td><img src='../{$row['id_photo']}' width='50'></td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='8'>No tenants found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>

            <!-- Pagination -->
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                        <a class="page-link" href="tenant-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo; Previous</span>
                        </a>
                    </li>
                    <li class="page-item active">
                        <span class="page-link"><?php echo $page; ?></span>
                    </li>
                    <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                        <a class="page-link" href="tenant-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                            <span aria-hidden="true">Next &raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<script>
// JavaScript for bulk actions
function submitBulkAction(action) {
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkActionForm').submit();
}
</script>



<?php
// Assuming you have an active database connection

// Pagination settings
$itemsPerPage = 5;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;  // Default to page 1 if not set
$start = ($page - 1) * $itemsPerPage;

// Form parameters
$search_location = isset($_GET['search_location']) ? mysqli_real_escape_string($db, htmlspecialchars($_GET['search_location'])) : '';
$property_type = isset($_GET['property_type']) ? mysqli_real_escape_string($db, htmlspecialchars($_GET['property_type'])) : '';
$estimated_price = isset($_GET['estimated_price']) ? (int)$_GET['estimated_price'] : 0;  // Convert to integer
$min_rating = isset($_GET['min_rating']) ? (float)$_GET['min_rating'] : 0;  // Convert to float

// Build the search query dynamically
$searchQuery = "WHERE 1=1 AND blocked = 0 AND approved = 1"; // Default conditions (approved and not blocked)

// Filter by location (search_location)
if (!empty($search_location)) {
    $searchQuery .= " AND (country LIKE '%$search_location%' OR province LIKE '%$search_location%' OR city LIKE '%$search_location%')";
}

// Filter by property type
if (!empty($property_type)) {
    $searchQuery .= " AND property_type = '$property_type'";
}

// Filter by estimated price
if ($estimated_price > 0) {
    $searchQuery .= " AND estimated_price <= $estimated_price";
}

// Filter by min_rating (rating is in a different table)
if ($min_rating > 0) {
    $searchQuery .= " AND property_id IN (SELECT property_id FROM ratting WHERE avg(rating) >= $min_rating)";
}

// Query to fetch the filtered properties
$propertyQuery = "SELECT * FROM add_property $searchQuery LIMIT $start, $itemsPerPage";

// Query to count the total number of filtered properties
$totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property $searchQuery";

// Execute the queries
$propertyResult = mysqli_query($db, $propertyQuery);
$totalResult = mysqli_query($db, $totalPropertyQuery);
$totalProperty = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalProperty / $itemsPerPage);

// Display the properties in the table
?>
<div class="table-section" id="totalPropertySection">
    <h4>Total Properties</h4>
    <div class="search s-container">
        <form method="GET" id="searchForm">
            <input type="hidden" name="card-type" value="total">
            <input type="hidden" name="page" value="<?php echo $page; ?>">
            <div class="row">
                <div class="col-md-4">
                    <input class="form-control" type="text" placeholder="Enter location" name="search_location" value="<?php echo $search_location; ?>">
                </div>
                <div class="col-md-3">
                    <select class="form-control" name="property_type">
                        <option value="">Property Type</option>
                        <option value="Full House Rent" <?php echo $property_type == "Full House Rent" ? 'selected' : ''; ?>>Full House</option>
                        <option value="Flat Rent" <?php echo $property_type == "Flat Rent" ? 'selected' : ''; ?>>Flat</option>
                        <option value="Room Rent" <?php echo $property_type == "Room Rent" ? 'selected' : ''; ?>>Room</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input class="form-control" type="number" placeholder="Max Price" name="estimated_price" value="<?php echo $estimated_price > 0 ? $estimated_price : ''; ?>">
                </div>
                <div class="col-md-2">
                    <input class="form-control" type="number" placeholder="Min Rating" name="min_rating" step="0.1" max="5" value="<?php echo $min_rating > 0 ? $min_rating : ''; ?>">
                </div>
                <div class="col-md-1">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </div>
        </form>
    </div>

    <form method="POST" id="bulkActionForm">
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Select</th>
                    <th>Property ID</th>
                    <th>Owner ID</th>
                    <th>Country</th>
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
                if ($propertyResult && mysqli_num_rows($propertyResult) > 0) {
                    while ($row = mysqli_fetch_assoc($propertyResult)) {
                        $property_id = $row['property_id'];
                        echo "<tr>";
                        echo "<td><input type='checkbox' name='selected_property[]' value='{$row['property_id']}'></td>";
                        echo "<td>{$row['property_id']}</td>";
                        echo "<td>{$row['owner_id']}</td>";
                        echo "<td>{$row['country']}</td>";
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
                } else {
                    echo "<tr><td colspan='11'>No properties found.</td></tr>";
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
            <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="property-section.php?card-type=total&page=<?php echo max(1, $page - 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo; Previous</span>
                </a>
            </li>
            <li class="page-item active">
                <span class="page-link"><?php echo $page; ?></span>
            </li>
            <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="property-section.php?card-type=total&page=<?php echo min($totalPages, $page + 1); ?>&search_location=<?php echo $search_location; ?>&property_type=<?php echo $property_type; ?>&estimated_price=<?php echo $estimated_price; ?>&min_rating=<?php echo $min_rating; ?>" aria-label="Next">
                    <span aria-hidden="true">Next &raquo;</span>
                </a>
            </li>
        </ul>
    </nav>
</div>

