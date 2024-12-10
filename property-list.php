<?php
include("config/config.php");
// include ("navbar.php");
?>
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

  .card>*:hover {
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
    border-radius: 10px;
    border: 1px solid black;
  }
</style>

<body>
  <?php

  $page = isset($_GET["page"]) ? $_GET["page"] : 1;

  $itemsPerPage = 18;

  $start = ($page - 1) * $itemsPerPage;
  $sql = "SELECT * FROM add_property WHERE approved = 1 and blocked = 0 LIMIT $start,$itemsPerPage";
  $totalPropertyQuery = "SELECT COUNT(*) as total FROM add_property WHERE approved = 1 AND blocked = 0";
  $query = mysqli_query($db, $sql);
  $totalResult = mysqli_query($db, $totalPropertyQuery);
  $totalProperty = mysqli_fetch_assoc($totalResult)["total"];
  $totalPages = ceil($totalProperty / $itemsPerPage);

  if (mysqli_num_rows($query) > 0) {
    while ($rows = mysqli_fetch_assoc($query)) {
      $property_id = $rows['property_id'];
      ?>

      <div class="col-sm-2">
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
          <p>
            <?php echo '<a href="view-property.php?property_id=' . $rows['property_id'] . '" class="btn btn-lg btn-primary btn-block">View Property</a><br>'; ?>
          </p><br>
        </div>
      </div>
      <?php

    }
  } else {
    echo '<p>No properties found matching your criteria.</p>';
  }
  ?>
  <footer class="pagination-container">
    <nav aria-label="Page navigation">
      <ul class="pagination">
        <!-- Previous Page Link -->
        <li class="page-item <?php echo $page == 1 ? 'disabled' : ''; ?>">
          <a class="page-link" href="index.php?page=<?php echo max(1, $page - 1); ?>" aria-label="Previous">
            <span aria-hidden="true">&laquo; Previous</span>
          </a>
        </li>

        <!-- Current Page Number -->
        <li class="page-item active">
          <span class="page-link"><?php echo $page; ?></span>
        </li>

        <!-- Next Page Link -->
        <li class="page-item <?php echo $page == $totalPages ? 'disabled' : ''; ?>">
          <a class="page-link" href="index.php?page=<?php echo min($totalPages, $page + 1); ?>" aria-label="Next">
            <span aria-hidden="true">Next &raquo;</span>
          </a>
        </li>
      </ul>
    </nav>
  </footer>