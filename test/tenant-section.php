<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}

// Assuming database connection is active
include "navbar.php";
include "../config/config.php";


//Below section of code should not be included in pagination.
// Query and display total tenants and pending request
// Query to get the total number of tenants
$totalTQuery = "SELECT COUNT(*) as total FROM tenant WHERE approved=1 AND blocked = 0";
$resultT = mysqli_query($db, $totalTQuery);
$totalT = mysqli_fetch_assoc($resultT)['total'];

// Query to get the total number of tenants
$totalPTQuery = "SELECT COUNT(*) as total FROM tenant WHERE approved=0";
$resultPT = mysqli_query($db, $totalPTQuery);
$totalPT = mysqli_fetch_assoc($resultPT)['total'];

//Query to get the total number of blocked tenants
$totalBlockedQuery = "SELECT COUNT(*) AS total FROM tenant where blocked=1";
$resultBlocked = mysqli_query($db, $totalBlockedQuery);
$totalBlocked = mysqli_fetch_assoc($resultBlocked)['total'];
//Above section of code should not be included in pagination.
?>
<?php
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
    $tenantsQuery = "SELECT * FROM tenant WHERE 1=1 $searchQuery AND blocked=0 AND approved=1 LIMIT $start, $itemsPerPage";
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
    /* .table-section {
        display: none;
    } */

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
                            <p class="card-text"><span class="badge badge-custom bg-primary"><?php echo $totalT; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="pendingTenantCard">
                        <div class="card-body">
                            <h5 class="card-title">Pending Tenants</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo $totalPT; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="blockedTenantCard">
                        <div class="card-body">
                            <h5 class="card-title">Blocked Tenants</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo $totalBlocked; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-section" id="totalTenantTable">
                <h4>Total Tenants</h4>
                <!-- Below is the search box in which search term should be entered. -->
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm">
                    <!-- Table displaying tenants -->
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
                    <input type="hidden" name="action" id="bulkAction">
                    <button type="button" class="btn btn-danger" onclick="submitBulkAction('delete')">Delete</button>
                    <button type="button" class="btn btn-warning" onclick="submitBulkAction('block')">Block</button>
                    <button type="button" class="btn btn-success" onclick="submitBulkAction('unblock')" disabled>Unblock</button>
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
            <div class="table-section" id="pendingTenant">
                <h4>Pending Tenant Requests</h4>
                <!-- Below is the search box in which search term should be entered. -->
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm2">
                    <!-- Table displaying tenants -->
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
                    <input type="hidden" name="action" id="bulkAction2">
                    <button type="button" class="btn btn-primary" onclick="submitBulkAction2('approved')">Approve</button>
                    <button type="button" class="btn btn-danger" onclick="submitBulkAction2('reject')">Reject</button>
                </form>
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
            <div class="table-section" id="blockedTenantSection">
                <h4>Blocked Tenant Requests</h4>
                <!-- Below is the search box in which search term should be entered. -->
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm3">
                    <!-- Table displaying tenants -->
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
                    <input type="hidden" name="action" id="bulkAction3">
                    <button type="button" class="btn btn-danger" onclick="submitBulkAction3('delete')">Delete</button>
                    <button type="button" class="btn btn-warning" onclick="submitBulkAction3('block')" disabled>Block</button>
                    <button type="button" class="btn btn-success" onclick="submitBulkAction3('unblock')">Unblock</button>
                </form>
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
</div>

<!-- JavaScript to Handle Card Click and Table Display -->
<script src="tenant.js"></script>
<?php include 'tenant-engine2.php'; ?>

