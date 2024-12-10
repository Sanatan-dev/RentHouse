<?php
include '../mail-engine.php'; // Include the mail engine

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_tenants'])) {
    $selectedTenants = $_POST['selected_tenants'];
    $tenantIds = implode(',', $selectedTenants); // Convert array to comma-separated list

    mysqli_begin_transaction($db);
    try {
        // Fetch tenant details for email purposes
        $tenantQuery = "SELECT tenant_id, full_name, email FROM tenant WHERE tenant_id IN ($tenantIds)";
        $tenantResult = mysqli_query($db, $tenantQuery);
        if (!$tenantResult) {
            throw new Exception("Error fetching tenant details: " . mysqli_error($db));
        }

        // Store tenant information for sending emails later
        $tenants = [];
        while ($tenantRow = mysqli_fetch_assoc($tenantResult)) {
            $tenants[] = $tenantRow;
        }

        // Perform action based on POST request
        if ($_POST['action'] == 'delete') {
            foreach ($selectedTenants as $tenant_id) {
                // Retrieve the property_id from the booking table using tenant_id
                $sql_get_property_id = "SELECT property_id FROM booking WHERE tenant_id = '$tenant_id'";
                $result = mysqli_query($db, $sql_get_property_id);

                if ($result && mysqli_num_rows($result) != 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $property_id = $row['property_id'];

                        // Update the booked column to 'No' in the add_property table using property_id
                        $sql_update_property = "UPDATE add_property SET booked = 'No' WHERE property_id = '$property_id'";
                        if (!mysqli_query($db, $sql_update_property)) {
                            throw new Exception("Error updating add_property table: " . mysqli_error($db));
                        }
                    }
                }

                // Delete records from the booking table using tenant_id
                $sql_delete_booking = "DELETE FROM booking WHERE tenant_id = '$tenant_id'";
                if (!mysqli_query($db, $sql_delete_booking)) {
                    throw new Exception("Error deleting record from booking: " . mysqli_error($db));
                }

                // Delete records from the chat table using tenant_id
                $sql_delete_chat = "DELETE FROM chat WHERE tenant_id = '$tenant_id'";
                if (!mysqli_query($db, $sql_delete_chat)) {
                    throw new Exception("Error deleting record from chat: " . mysqli_error($db));
                }

                // Finally, delete the tenant record from the tenant table
                $sql_delete_tenant = "DELETE FROM tenant WHERE tenant_id = '$tenant_id'";
                if (!mysqli_query($db, $sql_delete_tenant)) {
                    throw new Exception("Error deleting record from tenant: " . mysqli_error($db));
                }
            }

            // Prepare email subject and body for delete action
            $subject = "Account Deleted: %s";
            $message = "Dear %s,\n\nYour account has been deleted from the platform. If you have any questions, please contact support.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'block') {
            // Block selected tenants
            $blockQuery = "UPDATE tenant SET blocked=1 WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $blockQuery);

            // Prepare email subject and body for block action
            $subject = "Account Blocked: %s";
            $message = "Dear %s,\n\nYour account has been blocked. Please contact support for more information.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'unblock') {
            // Unblock selected tenants
            $unblockQuery = "UPDATE tenant SET blocked=0 WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $unblockQuery);

            // Prepare email subject and body for unblock action
            $subject = "Account Unblocked: %s";
            $message = "Dear %s,\n\nYour account has been unblocked. You can now use the platform as usual.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'reject') {
            // Reject and delete selected tenants
            $rejectQuery = "DELETE FROM tenant WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $rejectQuery);

            // Prepare email subject and body for reject action
            $subject = "Account Rejected: %s";
            $message = "Dear %s,\n\nYour account has been rejected and deleted from our platform.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'approved') {
            // Approve selected tenants
            $approveQuery = "UPDATE tenant SET approved = 1 WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $approveQuery);

            // Prepare email subject and body for approval action
            $subject = "Account Approved: %s";
            $message = "Dear %s,\n\nYour account has been approved. You now have full access to the platform.\n\nRegards,\nHouse Rental System";
        }

        // Send emails to all selected tenants
        foreach ($tenants as $tenant) {
            $tenantName = $tenant['full_name'];
            $tenantEmail = $tenant['email'];
            $emailSubject = sprintf($subject, $tenantName); // Replace %s with tenant's name
            $emailMessage = sprintf($message, $tenantName);  // Replace %s with tenant's name

            // Call the send_mail function to send the email
            sendmail($tenantEmail, $emailSubject, $emailMessage);
        }

        // Commit the transaction
        mysqli_commit($db);

    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
        header("Location: admin-index.php");
        exit();
    }

    // Redirect after the action is completed
   
}
?>
