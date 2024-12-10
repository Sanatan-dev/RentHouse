function submitBulkAction(action) {
  document.getElementById("bulkAction").value = action;
  document.getElementById("bulkActionForm").submit();
}

function submitBulkAction2(action) {
  document.getElementById("bulkAction2").value = action;
  console.log(document.getElementById("bulkAction").value);
  document.getElementById("bulkActionForm2").submit();
}

function submitBulkAction3(action) {
  document.getElementById("bulkAction3").value = action;
  document.getElementById("bulkActionForm3").submit();
}

var pageInput = document.getElementById("page");
var cardTypeInput = document.getElementById("card-type");

var totalPropertyCard = document.getElementById("totalPropertyCard");
var pendingPropertyCard = document.getElementById("pendingPropertyCard");
var totalBookedPropertyCard = document.getElementById(
  "totalBookedPropertyCard"
);
var blockedPropertyCard = document.getElementById("blockedPropertyCard");

var totalPropertySection = document.getElementById("totalPropertySection");
var pendingPropertySection = document.getElementById("pendingPropertySection");
var bookedPropertySection = document.getElementById("bookedPropertySection");
var blockedPropertySection = document.getElementById("blockedPropertySection");

totalPropertyCard.addEventListener("click", (event) => {
  cardTypeInput.value = "total";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});
pendingPropertyCard.addEventListener("click", (event) => {
  cardTypeInput.value = "pending";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});
totalBookedPropertyCard.addEventListener("click", (event) => {
  cardTypeInput.value = "booked";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});
blockedPropertyCard.addEventListener("click", (event) => {
  cardTypeInput.value = "blocked";
  pageInput.value = 1; // Reset page
  document.getElementById("searchForm").submit();
});

if (cardTypeInput.value == "total") {
  totalPropertySection.style.display = "block";
  pendingPropertySection.style.display = "none";
  blockedPropertySection.style.display = "none";
  bookedPropertySection.style.display = "none";
} else if (cardTypeInput.value == "pending") {
  pendingPropertySection.style.display = "block";
  totalPropertySection.style.display = "none";
  blockedPropertySection.style.display = "none";
  bookedPropertySection.style.display = "none";
} else if (cardTypeInput.value == "blocked") {
  blockedPropertySection.style.display = "block";
  totalPropertySection.style.display = "none";
  pendingPropertySection.style.display = "none";
  bookedPropertySection.style.display = "none";
} else if (cardTypeInput.value == "booked") {
  bookedPropertySection.style.display = "block";
  totalPropertySection.style.display = "none";
  blockedPropertySection.style.display = "none";
  pendingPropertySection.style.display = "none";
}


document.getElementById('bulkActionForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Create a new FormData object to send the form data via AJAX
    var formData = new FormData(this);

    // Send the form data via an XMLHttpRequest (AJAX)
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'property-engine2.php', true); // Replace 'property-engine2.php' with the path to your backend processing file
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Success: Refresh the page after successful processing
            window.location.href = window.location.href; // This refreshes the page after the form is processed
        }
    };
    xhr.send(formData);
});
document.getElementById('bulkActionForm2').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Create a new FormData object to send the form data via AJAX
    var formData = new FormData(this);

    // Send the form data via an XMLHttpRequest (AJAX)
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'property-engine2.php', true); // Replace 'property-engine2.php' with the path to your backend processing file
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Success: Refresh the page after successful processing
            window.location.href = window.location.href; // This refreshes the page after the form is processed
        }
    };
    xhr.send(formData);
});
document.getElementById('bulkActionForm3').addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Create a new FormData object to send the form data via AJAX
    var formData = new FormData(this);

    // Send the form data via an XMLHttpRequest (AJAX)
    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'property-engine2.php', true); // Replace 'property-engine2.php' with the path to your backend processing file
    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            // Success: Refresh the page after successful processing
            window.location.href = window.location.href; // This refreshes the page after the form is processed
        }
    };
    xhr.send(formData);
});

// Capture the form element
// const form = document.getElementById("bulkActionForm");
// const form2 = document.getElementById("bulkActionForm2");
// const form3 = document.getElementById("bulkActionForm3");

// Listen for form submission
// form2.addEventListener("submit", function (event) {
//   event.preventDefault(); // Prevent default form submission behavior

//   // Capture the form data
//   const formData = new FormData(form2);

//   // Extract the current URL to stay on the same page
//   const currentUrl = window.location.href;

//   // Send form data to property-engine2.php using fetch API
//   fetch("property-engine2.php", {
//     method: "POST",
//     body: formData,
//   })
//     .then((response) => response.text()) // Assuming the response is plain text
//     .then((data) => {
//       // Update UI or display a success message
//       document.getElementById("message").innerHTML =
//         "Action performed successfully!";

//       // Optionally, you can reload the table data or other UI elements here

//       // Ensure the user stays on the same page (keeping the URL intact)
//       history.pushState(null, null, currentUrl);
//     })
//     .catch((error) => {
//       // Handle errors
//       document.getElementById("message").innerHTML =
//         "Error occurred: " + error.message;
//     });
// });
// form.addEventListener("submit", function (event) {
//   event.preventDefault(); // Prevent default form submission behavior

//   // Capture the form data
//   const formData = new FormData(form);

//   // Extract the current URL to stay on the same page
//   const currentUrl = window.location.href;

//   // Send form data to property-engine2.php using fetch API
//   fetch("property-engine2.php", {
//     method: "POST",
//     body: formData,
//   })
//     .then((response) => response.text()) // Assuming the response is plain text
//     .then((data) => {
//       // Update UI or display a success message
//       document.getElementById("message").innerHTML =
//         "Action performed successfully!";

//       // Optionally, you can reload the table data or other UI elements here

//       // Ensure the user stays on the same page (keeping the URL intact)
//       history.pushState(null, null, currentUrl);
//     })
//     .catch((error) => {
//       // Handle errors
//       document.getElementById("message").innerHTML =
//         "Error occurred: " + error.message;
//     });
// });
// form3.addEventListener("submit", function (event) {
//   event.preventDefault(); // Prevent default form submission behavior

//   // Capture the form data
//   const formData = new FormData(form3);

//   // Extract the current URL to stay on the same page
//   const currentUrl = window.location.href;

//   // Send form data to property-engine2.php using fetch API
//   fetch("property-engine2.php", {
//     method: "POST",
//     body: formData,
//   })
//     .then((response) => response.text()) // Assuming the response is plain text
//     .then((data) => {
//       // Update UI or display a success message
//       document.getElementById("message").innerHTML =
//         "Action performed successfully!";

//       // Optionally, you can reload the table data or other UI elements here

//       // Ensure the user stays on the same page (keeping the URL intact)
//       history.pushState(null, null, currentUrl);
//     })
//     .catch((error) => {
//       // Handle errors
//       document.getElementById("message").innerHTML =
//         "Error occurred: " + error.message;
//     });
// });
