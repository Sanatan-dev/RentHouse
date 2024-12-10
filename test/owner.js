function submitBulkAction(action) {
  document.getElementById("bulkAction").value = action;
  document.getElementById("bulkActionForm").submit();
}

function submitBulkAction2(action) {
  document.getElementById("bulkAction2").value = action;
  document.getElementById("bulkActionForm2").submit();
}

function submitBulkAction3(action) {
  document.getElementById("bulkAction3").value = action;
  document.getElementById("bulkActionForm3").submit();
}

var pageInput = document.getElementById("page");
var cardTypeInput = document.getElementById("card-type");

var ownersCard = document.getElementById("totalOwnersCard");
var pendingOwnersCard = document.getElementById("pendingRequestsCard");
var blockedOwnersCard = document.getElementById("blockedOwnersCard");
// Event listeners for card clicks
ownersCard.addEventListener("click", function () {
  cardTypeInput.value = "total";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});

pendingOwnersCard.addEventListener("click", function () {
  cardTypeInput.value = "pending";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});

blockedOwnersCard.addEventListener("click", function () {
  cardTypeInput.value = "blocked";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});

// Show the correct table based on card type (on page load or after form submit)
// Display the appropriate table after pagination
var ownersSection = document.getElementById("totalOwnersSection");
var pendingOwnersSection = document.getElementById("pendingRequestsSection");
var blockedOwnersSection = document.getElementById("blockedOwnersSection");

if (cardTypeInput.value == "total") {
  ownersSection.style.display = "block";
  pendingOwnersSection.style.display = "none";
  blockedOwnersSection.style.display = "none";
} else if (cardTypeInput.value == "pending") {
  pendingOwnersSection.style.display = "block";
  ownersSection.style.display = "none";
  blockedOwnersSection.style.display = "none";
} else if (cardTypeInput.value == "blocked") {
  blockedOwnersSection.style.display = "block";
  ownersSection.style.display = "none";
  pendingOwnersSection.style.display = "none";
}
