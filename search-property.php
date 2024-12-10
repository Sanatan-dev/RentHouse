<?php
session_start();
isset($_SESSION["email"]);
include("navbar.php");
include("config/config.php");
?>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <style>
    .pagination-container {
      display: flex;
      justify-content: center;
      margin-top: 20px;
    }

    footer {
      width: 100%;
      padding: 10px;
      text-align: center;
    }

    .card {
      box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
      max-width: 100%;
      min-width: 100%;
      margin: auto;
      text-align: center;
      font-family: arial;
      display: inline;
    }

    .card:hover {
      box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
      opacity: 0.9;
      cursor: pointer;
    }

    .card>* {
      transform: scale(1);
      transition: transform 0.3s ease-in-out;
    }

    .card>* : hover {
      transform: scale(1.1);

    }

    .container {
      padding: 2px 16px;
    }

    .btn {
      width: 100%;
    }

    .image {
      min-width: 100%;
      min-height: 200px;
      max-width: 100%;
      max-height: 200px;
    }

    .home-btn {
      width: 90%;
      height: 90%;
      border: 1px solid black;
      border-radius: 10px;
    }
  </style>
</head>

<body>

  <?php
  $whereClauses = [];

  $page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
  $itemsPerPage = 12;
  $start = ($page - 1) * 12;

  // Handle location search
  if (!empty($_GET['search_location'])) {
    $search_location = mysqli_real_escape_string($db, $_GET['search_location']);
    $whereClauses[] = "(city LIKE '%$search_location%' OR district LIKE '%$search_location%')";
  }

  // Handle property type search    
  if (!empty($_GET['property_type'])) {
    $property_type = mysqli_real_escape_string($db, $_GET['property_type']);
    $whereClauses[] = "property_type = '$property_type'";
  }

  // Handle price filter
  if (!empty($_GET['estimated_price'])) {
    $max_price = mysqli_real_escape_string($db, $_GET['estimated_price']);
    $whereClauses[] = "estimated_price <= $max_price";
  }

  // Construct the base SQL query
  $sql = "SELECT ap.*, AVG(r.rating) AS avg_rating 
        FROM add_property ap 
        LEFT JOIN review r ON ap.property_id = r.property_id
        WHERE ap.approved = 1";

  // Add conditions from filters
  if (count($whereClauses) > 0) {
    $sql .= " AND " . implode(' AND ', $whereClauses);
  }

  // Group by property_id (ensuring all fields are grouped)
  $sql .= " GROUP BY ap.property_id";

  // Handle rating filter
  if (!empty($_GET['min_rating'])) {
    $min_rating = mysqli_real_escape_string($db, $_GET['min_rating']);
    $sql .= " HAVING avg_rating >= $min_rating";
  }

  $sql_copy = $sql;

  $sql .= " LIMIT $start,$itemsPerPage";

  $query = mysqli_query($db, $sql);
  $query_copy = mysqli_query($db, $sql_copy);
  $totalProperty = mysqli_num_rows($query_copy);
  $totalPages = ceil($totalProperty / $itemsPerPage);

  echo '<center><h1>Searched Properties</h1></center>';

  // Check if any properties are found based on the query
  if (mysqli_num_rows($query) > 0) {
    while ($rows = mysqli_fetch_assoc($query)) {
      $property_id = $rows['property_id'];
      ?>

      <div class="col-sm-2 card-container">
        <div class="card">
          <?php
          // Fetch property photo
          $sql2 = "SELECT * FROM property_photo WHERE property_id='$property_id'";
          $query2 = mysqli_query($db, $sql2);
          if (mysqli_num_rows($query2) > 0) {
            $row = mysqli_fetch_assoc($query2);
            $photo = $row['p_photo'];
            echo '<img class="image" src="owner/' . $photo . '">';
          }
          ?>
          <h4><b><?php echo $rows['property_type']; ?></b></h4>
          <p><?php echo $rows['city'] . ', ' . $rows['district']; ?></p>
          <p><b>Rating: </b><?php echo round($rows['avg_rating'], 1); ?>/5</p>
          <p>
            <?php echo '<a href="view-property.php?property_id=' . $rows['property_id'] . '" class="btn btn-lg btn-primary btn-block">View Property</a><br>'; ?>
          </p><br>
        </div>
      </div>

      <?php
    }
  } else {
    // If no results found, show a message
    echo "<center><h3>No properties found matching your search criteria...</h3></center>";
  }


  $paginationParams = "";
  if (!empty($_GET['search_location']))
    $paginationParams .= "&search_location=" . urlencode($_GET['search_location']);
  if (!empty($_GET['property_type']))
    $paginationParams .= "&property_type=" . urlencode($_GET['property_type']);
  if (!empty($_GET['estimated_price']))
    $paginationParams .= "&estimated_price=" . urlencode($_GET['estimated_price']);
  if (!empty($_GET['min_rating']))
    $paginationParams .= "&min_rating=" . urlencode($_GET['min_rating']);
  ?>

</body>
<!-- Pagination links -->
<footer class="pagination-container">
  <nav aria-label="Page navigation">
    <ul class="pagination" id="home-btn">
      <li class="page-item">
        <a href="index.php" aria-label="home-page" class="page-link">
          <span aria-hidden="true"><i class="bi bi-house-door"></i></span>
        </a>
      </li>
    </ul>
    <ul class="pagination">
      <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
        <a class="page-link" href="search-property.php?page=<?php echo max(1, $page - 1) . $paginationParams; ?>"
          aria-label="Previous">
          <span aria-hidden="true">&laquo; Previous</span>
        </a>
      </li>

      <!-- Current Page -->
      <li class="page-item active">
        <span class="page-link"><?php echo $page; ?></span>
      </li>

      <!-- Next Page -->
      <li class="page-item <?php echo ($page == $totalPages) || ($totalPages == 0) ? 'disabled' : ''; ?>">
        <a class="page-link"
          href="search-property.php?page=<?php echo (($totalPages == 0) ? 1 : min($totalPages, $page + 1)) . $paginationParams; ?>"
          aria-label="Next">
          <span aria-hidden="true">Next &raquo;</span>
        </a>
      </li>
    </ul>
  </nav>
</footer>