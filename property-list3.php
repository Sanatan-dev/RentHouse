<?php
// Database connection
include("config/config.php");

// Pagination setup
$page = isset($_GET["page"]) ? $_GET["page"] : 1;
$itemsPerPage = 10;
$start = ($page - 1) * $itemsPerPage;

// Query for "All Properties" with pagination
$allPropertiesQuery = "
    SELECT * 
    FROM add_property 
    WHERE approved = 1 AND blocked = 0 
    LIMIT $start, $itemsPerPage
";
$allPropertiesResult = mysqli_query($db, $allPropertiesQuery);

// Count total properties for pagination
$totalPropertiesQuery = "
    SELECT COUNT(*) as total 
    FROM add_property 
    WHERE approved = 1 AND blocked = 0
";
$totalPropertiesResult = mysqli_query($db, $totalPropertiesQuery);
$totalProperties = mysqli_fetch_assoc($totalPropertiesResult)["total"];
$totalPages = ceil($totalProperties / $itemsPerPage);

// Query for 10 most recently added properties
$recentlyAddedQuery = "
    SELECT * 
    FROM add_property 
    WHERE approved = 1 AND blocked = 0 
    ORDER BY listing_date DESC 
    LIMIT 10
";
$recentlyAddedResult = mysqli_query($db, $recentlyAddedQuery);

// Query for 5 top-rated properties
$topRatedQuery = "
    SELECT p.*, AVG(r.rating) AS average_rating
    FROM add_property p
    LEFT JOIN review r ON p.property_id = r.property_id
    WHERE p.approved = 1 AND p.blocked = 0
    GROUP BY p.property_id
    ORDER BY average_rating DESC
    LIMIT 5
";
$topRatedResult = mysqli_query($db, $topRatedQuery);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        h2 {
            font-size: 24px;
            margin: 10px 0;
        }

        .property-section {
            display: flex;
            justify-content: space-between;
            margin: 20px;
        }

        .property-column {
            width: 48%;
            overflow-x: auto;
            white-space: nowrap;
            position: relative;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .property-column h2 {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
            margin: 0;
            padding: 10px 0;
            text-align: center;
        }

        .card {
            display: inline-block;
            width: 30%;
            margin-right: 10px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            text-align: center;
            border-radius: 8px;
        }

        .card img {
            width: 100%;
            height: 150px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .card h4 {
            margin: 10px 0;
        }

        .card p {
            margin: 5px 0;
        }

        .card .btn {
            margin: 10px 0;
            text-decoration: none;
            background-color: #007bff;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
        }

        .card .btn:hover {
            background-color: #0056b3;
        }

        .all-properties {
            margin: 20px;
            overflow-x: auto;
            white-space: nowrap;
            position: relative;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .all-properties h2 {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
            margin: 0;
            padding: 10px 0;
            text-align: center;
        }

        .all-properties .card {
            width: 18%;
            display: inline-block;
            margin-right: 10px;
        }

        .pagination-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination {
            list-style: none;
            display: flex;
            padding: 0;
        }

        .pagination li {
            margin: 0 5px;
        }

        .pagination a {
            text-decoration: none;
            padding: 10px;
            color: #007bff;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #007bff;
            color: white;
        }

        footer {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <!-- Most Recently Added Section -->
    <div class="property-section">
        <div class="property-column">
            <h2>Most Recently Added</h2>
            <?php
            if (mysqli_num_rows($recentlyAddedResult) > 0) {
                while ($recentProperty = mysqli_fetch_assoc($recentlyAddedResult)) {
                    $property_id = $recentProperty['property_id'];
                    ?>
                    <div class="card">
                        <?php
                        $photoQuery = "SELECT * FROM property_photo WHERE property_id='$property_id'";
                        $photoResult = mysqli_query($db, $photoQuery);
                        if (mysqli_num_rows($photoResult) > 0) {
                            $photoRow = mysqli_fetch_assoc($photoResult);
                            $photo = $photoRow['p_photo'];
                            echo '<img src="owner/' . $photo . '" alt="Property">';
                        }
                        ?>
                        <h4><?php echo $recentProperty['property_type']; ?></h4>
                        <p><?php echo $recentProperty['city'] . ', ' . $recentProperty['district']; ?></p>
                        <p><a href="view-property.php?property_id=<?php echo $property_id; ?>" class="btn">View Property</a></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No recently added properties found.</p>';
            }
            ?>
        </div>

        <!-- Top Rated Section -->
        <div class="property-column">
            <h2>Top Rated Properties</h2>
            <?php
            if (mysqli_num_rows($topRatedResult) > 0) {
                while ($topRatedProperty = mysqli_fetch_assoc($topRatedResult)) {
                    $property_id = $topRatedProperty['property_id'];
                    $average_rating = round($topRatedProperty['average_rating'], 1);
                    ?>
                    <div class="card">
                        <?php
                        $photoQuery = "SELECT * FROM property_photo WHERE property_id='$property_id'";
                        $photoResult = mysqli_query($db, $photoQuery);
                        if (mysqli_num_rows($photoResult) > 0) {
                            $photoRow = mysqli_fetch_assoc($photoResult);
                            $photo = $photoRow['p_photo'];
                            echo '<img src="owner/' . $photo . '" alt="Property">';
                        }
                        ?>
                        <h4><?php echo $topRatedProperty['property_type']; ?></h4>
                        <p><?php echo $topRatedProperty['city'] . ', ' . $topRatedProperty['district']; ?></p>
                        <p><b>Rating:</b> <?php echo $average_rating ? $average_rating . '/5' : 'No ratings yet'; ?></p>
                        <p><a href="view-property.php?property_id=<?php echo $property_id; ?>" class="btn">View Property</a></p>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No top-rated properties found.</p>';
            }
            ?>
        </div>
    </div>

    <!-- All Properties Section with Horizontal Scrolling -->
    <div class="all-properties">
        <h2>All Properties</h2>
        <?php
        if (mysqli_num_rows($allPropertiesResult) > 0) {
            while ($property = mysqli_fetch_assoc($allPropertiesResult)) {
                $property_id = $property['property_id'];
                ?>
                <div class="card">
                    <?php
                    $photoQuery = "SELECT * FROM property_photo WHERE property_id='$property_id'";
                    $photoResult = mysqli_query($db, $photoQuery);
                    if (mysqli_num_rows($photoResult) > 0) {
                        $photoRow = mysqli_fetch_assoc($photoResult);
                        $photo = $photoRow['p_photo'];
                        echo '<img src="owner/' . $photo . '" alt="Property">';
                    }
                    ?>
                    <h4><?php echo $property['property_type']; ?></h4>
                    <p><?php echo $property['city'] . ', ' . $property['district']; ?></p>
                    <p><a href="view-property.php?property_id=<?php echo $property_id; ?>" class="btn">View Property</a></p>
                </div>
                <?php
            }
        } else {
            echo '<p>No properties found.</p>';
        }
        ?>
    </div>

    <!-- Pagination -->
    <div class="pagination-container">
        <ul class="pagination">
            <li><a href="index.php?page=<?php echo max($page - 1, 1); ?>">&laquo; Prev</a></li>
            <?php
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<li><a href="index.php?page=' . $i . '">' . $i . '</a></li>';
            }
            ?>
            <li><a href="index.php?page=<?php echo min($page + 1, $totalPages); ?>">Next &raquo;</a></li>
        </ul>
    </div>

</body>

</html>
