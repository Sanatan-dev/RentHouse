function submitBulkAction(action) {
    document.getElementById('bulkAction').value = action;
    document.getElementById('bulkActionForm').submit();
}

function submitBulkAction2(action) {
    document.getElementById('bulkAction2').value = action;
    document.getElementById('bulkActionForm2').submit();
}

function submitBulkAction3(action) {
    document.getElementById('bulkAction3').value = action;
    document.getElementById('bulkActionForm3').submit();
}

var pageInput = document.getElementById('page');
var cardTypeInput = document.getElementById('card-type');


var tenantCard = document.getElementById('totalTenantCard');
var pendingTenantCard = document.getElementById('pendingTenantCard');
var blockedTenantCard = document.getElementById('blockedTenantCard');

// Event listeners for card clicks
tenantCard.addEventListener('click', function() {
    cardTypeInput.value = 'total';
    pageInput.value = 1; // Reset page
    document.getElementById('searchForm').submit();
});

pendingTenantCard.addEventListener('click', function() {
    cardTypeInput.value = 'pending';
    pageInput.value = 1; // Reset page
    document.getElementById('searchForm').submit();
});

blockedTenantCard.addEventListener('click', function() {
    cardTypeInput.value = 'blocked';
    pageInput.value = 1; // Reset page
    document.getElementById('searchForm').submit();
});

// Show the correct table based on card type (on page load or after form submit)
// Display the appropriate table after pagination
var tenantSection = document.getElementById('totalTenantTable');
var pendingTenantSection = document.getElementById('pendingTenant');
var blockedTenantSection = document.getElementById('blockedTenantSection');

if (cardTypeInput.value == 'total') {
    tenantSection.style.display = 'block';
    pendingTenantSection.style.display = 'none';
    blockedTenantSection.style.display = 'none';
} else if (cardTypeInput.value == 'pending') {
    pendingTenantSection.style.display = 'block';
    tenantSection.style.display = 'none';
    blockedTenantSection.style.display = 'none';
} else if (cardTypeInput.value == 'blocked') {
    blockedTenantSection.style.display = 'block';
    tenantSection.style.display = 'none';
    pendingTenantSection.style.display = 'none';
}