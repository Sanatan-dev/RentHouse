<?php
include '../mail-engine.php'; // Include the mail engine

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_tenants'])) {
    $selectedTenants = $_POST['selected_tenants'];
    $tenantIds = implode(',', $selectedTenants); // Convert array to comma-separated list

    mysqli_begin_transaction($db);
    try {
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
        } elseif ($_POST['action'] == 'block') {
            // Block selected tenants
            $blockQuery = "UPDATE tenant SET blocked=1 WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $blockQuery);
        } elseif ($_POST['action'] == 'unblock') {
            // Unblock selected tenants
            $unblockQuery = "UPDATE tenant SET blocked=0 WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $unblockQuery);
        } elseif ($_POST['action'] == 'reject') {
            // Unblock selected tenants
            $unblockQuery = "DELETE FROM tenant WHERE tenant_id in ($tenantIds)";
            mysqli_query($db, $unblockQuery);
        } elseif ($_POST['action'] == 'approved') {
            // Unblock selected tenants
            $unblockQuery = "UPDATE tenant SET approved = 1 WHERE tenant_id IN ($tenantIds)";
            mysqli_query($db, $unblockQuery);
        }
        mysqli_commit($db);
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
        header("Location: admin-index.php");
        exit();
    }
}
?>