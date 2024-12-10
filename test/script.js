document.addEventListener('DOMContentLoaded', function() {
    const totalOwnersCard = document.getElementById("totalOwnersCard");
    const pendingRequestsCard = document.getElementById("pendingRequestsCard");
    const totalOwnersSection = document.getElementById("totalOwnersSection");
    const pendingRequestsSection = document.getElementById("pendingRequestsSection");

    // Show Total Owners Table on click
    totalOwnersCard.addEventListener('click', function() {
        totalOwnersSection.style.display = 'block';
        pendingRequestsSection.style.display = 'none';
    });

    // Show Pending Requests Table on click
    pendingRequestsCard.addEventListener('click', function() {
        totalOwnersSection.style.display = 'none';
        pendingRequestsSection.style.display = 'block';
    });

    const totalTenantCard = document.getElementById("totalTenantCard");
    const pendingTenantCard = document.getElementById("pendingTenantCard");
    const totalTenantSection = document.getElementById("totalTenantSection");
    const pendingTenantSection = document.getElementById("pendingTenantSection");

    // Show Total Tenants Table on click
    totalTenantCard.addEventListener('click', function() {
        totalTenantSection.style.display = 'block';
        pendingTenantSection.style.display = 'none';
    });

    // Show Pending Tenants Table on click
    pendingTenantCard.addEventListener('click', function() {
        totalTenantSection.style.display = 'none';
        pendingTenantSection.style.display = 'block';
    });

    const ownersButton = document.getElementById('ownersButton');
    const tenantButton = document.getElementById('tenantButton');
    const ownersSection = document.getElementById('ownersSection');
    const dashboardSection = document.getElementById('dashboardContent');
    const tenantsSection = document.getElementById('tenantSection');

    function hideAllSections() {
        ownersSection.style.display = 'none';
        tenantsSection.style.display = 'none';
        dashboardSection.style.display = 'none';
    }

    ownersButton.addEventListener('click', function() {
        hideAllSections();
        ownersSection.style.display = 'block';
    });

    tenantButton.addEventListener('click', function() {
        hideAllSections();
        tenantsSection.style.display = 'block';
    });
});
