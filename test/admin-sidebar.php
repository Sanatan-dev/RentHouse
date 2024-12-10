
<style>
    #sidenav> a{
        background-color: black;
        color: white;
        
    }
</style>
<div class="d-flex flex-column p-3 bg-light" id="side-nav" style="width: 150px; height: 100vh;">
    <a href="#" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
        <span class="fs-4">Admin Panel</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <!-- Dashboard Button -->
        <li class="nav-item mb-2">
            <a href="admin-dashboard.php" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;">
                Dashboard
            </a>
        </li>

        <!-- Users Dropdown Button -->
        <li class="nav-item mb-2">
            <a href="#usersSubMenu" data-bs-toggle="collapse" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;" aria-expanded="false" aria-controls="usersSubMenu">
                Users
            </a>
            <div class="collapse" id="usersSubMenu">
                <ul class="list-unstyled ps-3">
                    <!-- Owners Button -->
                    <li class="nav-item mb-2">
                        <a href="owners-section.php" id="ownersButton" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;" role="button">
                            Owners
                        </a>
                    </li>
                    <!-- Tenants Button -->
                    <li class="nav-item mb-2">
                        <a href="tenant-section.php" id="tenantButton" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;" role="button">
                            Tenants
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <!-- Properties Button -->
        <li class="nav-item mb-2">
            <a href="property-section.php" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;">
                Properties
            </a>
        </li>

        <!-- Payments Button -->
        <li class="nav-item mb-2">
            <a href="payments.php" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;">
                Payments
            </a>
        </li>

        <!-- Reports Button -->
        <li class="nav-item mb-2">
            <a href="reports2.php" class="nav-link btn btn-outline-dark text-start w-100" style="border-radius: 0;">
                Reports
            </a>
        </li>
    </ul>
</div>