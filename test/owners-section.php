<?php
session_start();
if (!isset($_SESSION["email"])) {
    header("location:../index.php");
}

// Assuming database connection is active
include "navbar.php";
include "../config/config.php";
// Query and display total owners and pending requests
// Query to get the total number of owners
$totalOQuery = "SELECT COUNT(*) as total FROM owner WHERE approved = 1 AND blocked = 0";
$resultO = mysqli_query($db, $totalOQuery);
$totalO = mysqli_fetch_assoc($resultO)['total'];
//$totalPO = ...; // Fetch pending owners
$totalPOQuery = "SELECT COUNT(*) as total FROM owner WHERE approved=0";
$resultPO = mysqli_query($db, $totalPOQuery);
$totalPO = mysqli_fetch_assoc($resultPO)['total'];


$totalBlockedQuery = "SELECT COUNT(*) AS total FROM owner WHERE blocked=1";
$resultBlocked = mysqli_query($db, $totalBlockedQuery);
$totalBlocked = mysqli_fetch_assoc($resultBlocked)['total'];

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
    $ownersQuery = "SELECT * FROM owner WHERE 1=1 $searchQuery AND blocked = 0 AND approved = 1 LIMIT $start, $itemsPerPage";
    $totalOwnersQuery = "SELECT COUNT(*) as total FROM owner WHERE 1=1 $searchQuery";
} elseif ($cardType == 'pending') {
    $ownersQuery = "SELECT * FROM owner WHERE approved=0 $searchQuery LIMIT $start, $itemsPerPage";
    $totalOwnersQuery = "SELECT COUNT(*) as total FROM owner WHERE approved=0 $searchQuery";
} elseif ($cardType == 'blocked') {
    $ownersQuery = "SELECT * FROM owner WHERE blocked=1 $searchQuery LIMIT $start, $itemsPerPage";
    $totalOwnersQuery = "SELECT COUNT(*) as total FROM owner WHERE blocked=1 $searchQuery";
}

// Execute queries
$ownersResult = mysqli_query($db, $ownersQuery);
$totalResult = mysqli_query($db, $totalOwnersQuery);
$totalOwners = mysqli_fetch_assoc($totalResult)['total'];
$totalPages = ceil($totalOwners / $itemsPerPage);
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
</style>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 col-sm-12">
            <?php include 'admin-sidebar.php'; ?>
        </div>
        <div class="col-md-10 col-sm-12">
            <h2>Owners Section</h2>
            <div class="row">
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="totalOwnersCard">
                        <div class="card-body">
                            <h5 class="card-title">Total Owners</h5>
                            <p class="card-text"><span class="badge badge-custom bg-primary"><?php echo $totalO; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="pendingRequestsCard">
                        <div class="card-body">
                            <h5 class="card-title">Pending Owners</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo $totalPO; ?></span></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="card card-custom" id="blockedOwnersCard">
                        <div class="card-body">
                            <h5 class="card-title">Blocked Owners</h5>
                            <p class="card-text"><span class="badge badge-custom bg-danger"><?php echo $totalBlocked; ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-section" id="totalOwnersSection">
                <h4>Total Owners</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm">
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
                            // Fetch and display owners data
                            $ownersResult = mysqli_query($db, $ownersQuery);
                            if (mysqli_num_rows($ownersResult) > 0) {
                                while ($row = mysqli_fetch_assoc($ownersResult)) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_owners[]' value='{$row['owner_id']}'></td>";
                                    echo "<td>{$row['owner_id']}</td>";
                                    echo "<td>{$row['full_name']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['phone_no']}</td>";
                                    echo "<td>{$row['address']}</td>";
                                    echo "<td>{$row['id_type']}</td>";
                                    echo "<td><img src='../{$row['id_photo']}' width='50'></td>";
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

                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="owner-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="owner-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!--- Pending Requests of owners section --->
            <div class="table-section" id="pendingRequestsSection">
                <h4>Pending Owner Requests</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm2">
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
                            // Fetch and display pending owner requests
                            $ownersResult = mysqli_query($db, $ownersQuery);
                            if (mysqli_num_rows($ownersResult) > 0) {
                                while ($row = mysqli_fetch_assoc($ownersResult)) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_owners[]' value='{$row['owner_id']}'></td>";
                                    echo "<td>{$row['owner_id']}</td>";
                                    echo "<td>{$row['full_name']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['phone_no']}</td>";
                                    echo "<td>{$row['address']}</td>";
                                    echo "<td>{$row['id_type']}</td>";
                                    echo "<td><img src='../{$row['id_photo']}' width='50'></td>";
                                    echo "</tr>";
                                }
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
                            <a class="page-link" href="owner-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="owner-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!--- blocked owner section --->
            <div class="table-section" id="blockedOwnersSection">
                <h4>Blocked Owners</h4>
                <div class="search s-container">
                    <form method="GET" id="searchForm">
                        <input type="hidden" name="card-type" id="card-type" value="<?php echo $cardType; ?>">
                        <input type="hidden" name="page" id="page" value="<?php echo $page; ?>">
                        <input type="text" class="form-control" name="search" placeholder="Enter Name/Email/Phone no/Address..." value="<?php echo $search; ?>">
                        <button type="submit" class="btn btn-primary">Search</button>
                    </form>
                </div>
                <form method="POST" id="bulkActionForm3">

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
                            // Fetch and display pending owner requests
                            $ownersResult = mysqli_query($db, $ownersQuery);
                            if (mysqli_num_rows($ownersResult) > 0) {
                                while ($row = mysqli_fetch_assoc($ownersResult)) {
                                    echo "<tr>";
                                    echo "<td><input type='checkbox' name='selected_owners[]' value='{$row['owner_id']}'></td>";
                                    echo "<td>{$row['owner_id']}</td>";
                                    echo "<td>{$row['full_name']}</td>";
                                    echo "<td>{$row['email']}</td>";
                                    echo "<td>{$row['phone_no']}</td>";
                                    echo "<td>{$row['address']}</td>";
                                    echo "<td>{$row['id_type']}</td>";
                                    echo "<td><img src='../{$row['id_photo']}' width='50'></td>";
                                    echo "</tr>";
                                }
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
                            <a class="page-link" href="owner-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo max(1, $page - 1); ?>&search=<?php echo $search; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo; Previous</span>
                            </a>
                        </li>
                        <li class="page-item active">
                            <span class="page-link"><?php echo $page; ?></span>
                        </li>
                        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="owner-section.php?card-type=<?php echo $cardType; ?>&page=<?php echo min($totalPages, $page + 1); ?>&search=<?php echo $search; ?>" aria-label="Next">
                                <span aria-hidden="true">Next &raquo;</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script src="owner.js"></script>
<?php include 'owner-engine2.php' ?>

