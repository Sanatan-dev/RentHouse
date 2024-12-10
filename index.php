<?php 
session_start();
include("navbar.php");
?>
<style>
body, html {
  height: 100%;
  margin: 0;
}
.bg {
  background-image: url("images/carousel.png");
  height: 60%; 
  background-position: bottom;
  background-repeat: no-repeat;
  background-size: cover;
}
.fa {
  padding: 20px;
  font-size: 30px;
  text-align: left;
  text-decoration: none;
  margin: 5px 2px;
}
.fa:hover {
    opacity: 0.7;
}
.fa-facebook {
  background: #3B5998;
  color: white;
}
.fa-linkedin {
  background: #007bb5;
  color: white;
}
.active-cyan-3 input[type=text] {
  border: 1px solid #4dd0e1;
  box-shadow: 0 0 0 1px #4dd0e1;
}
</style>

<div class="bg"></div><br>

<!-- Search and Filter Form -->
<div class="container active-cyan-4 mb-4">
  <form method="GET" action="search-property.php" onsubmit="return validateSearchForm()">
    <div class="row">
      <div class="col-md-4">
        <input class="form-control" type="text" placeholder="Enter location" name="search_location" aria-label="Search Location">
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
        <input class="form-control" type="number" placeholder="Max Price" name="estimated_price">
      </div>
      <div class="col-md-2">
        <input class="form-control" type="number" placeholder="Min Rating" name="min_rating" step="0.1" max="5">
      </div>
      <div class="col-md-1">
        <button class="btn btn-primary" type="submit">Search</button>
      </div>
    </div>
  </form>
</div>

<div>
<br><br><br>
<?php 
include "property-list4.php" ;
?>
<br><br><br>
</div>

<script>
// JavaScript function to validate form
function validateSearchForm() {
  // Get form fields
  var location = document.getElementsByName("search_location")[0].value;
  var propertyType = document.getElementsByName("property_type")[0].value;
  var maxPrice = document.getElementsByName("estimated_price")[0].value;
  var minRating = document.getElementsByName("min_rating")[0].value;

  // Check if all fields are empty
  if (location === "" && propertyType === "" && maxPrice === "" && minRating === "") {
    // Show error message if no input is provided
    // document.getElementById("error-message").style.display = "block";
    alert("You should enter something to search.");
    return false; // Prevent form submission
  }
  return true; // Allow form submission
}
</script>



