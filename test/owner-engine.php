<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_owners'])) {
    $selectedOwners = $_POST['selected_owners'];
    $ownerIds = implode(',', $selectedOwners); // Convert array to comma-separated list

    mysqli_begin_transaction($db);
    try {
        if ($_POST['action'] == 'delete') {
            foreach ($selectedOwners as $owner_id) {

                // Find all property IDs for the given owner
                $sql_get_properties = "SELECT property_id FROM add_property WHERE owner_id = '$owner_id'";
                $result = mysqli_query($db, $sql_get_properties);

                if (!$result) {
                    throw new Exception("Error fetching property IDs: " . mysqli_error($db));
                }

                // Loop through all property IDs and delete related records
                while ($row = mysqli_fetch_assoc($result)) {
                    $property_id = $row['property_id'];

                    // Delete records from the property_photo table
                    $sql_property_photo = "DELETE FROM property_photo WHERE property_id = '$property_id'";
                    if (!mysqli_query($db, $sql_property_photo)) {
                        throw new Exception("Error deleting record from property_photo: " . mysqli_error($db));
                    }

                    // Delete records from the booking table
                    $sql_booking = "DELETE FROM booking WHERE property_id = '$property_id'";
                    if (!mysqli_query($db, $sql_booking)) {
                        throw new Exception("Error deleting record from booking: " . mysqli_error($db));
                    }

                    // Delete records from the review table
                    $sql_review = "DELETE FROM review WHERE property_id = '$property_id'";
                    if (!mysqli_query($db, $sql_review)) {
                        throw new Exception("Error deleting record from review: " . mysqli_error($db));
                    }
                }

                // Delete records from the chat table using the owner_id
                $sql_chat = "DELETE FROM chat WHERE owner_id = '$owner_id'";
                if (!mysqli_query($db, $sql_chat)) {
                    throw new Exception("Error deleting record from chat: " . mysqli_error($db));
                }

                // Delete records from the add_property table using the owner_id
                $sql_add_property = "DELETE FROM add_property WHERE owner_id = '$owner_id'";
                if (!mysqli_query($db, $sql_add_property)) {
                    throw new Exception("Error deleting record from add_property: " . mysqli_error($db));
                }

                // Finally, delete the owner record from the owner table
                $sql_owner = "DELETE FROM owner WHERE owner_id = '$owner_id'";
                if (!mysqli_query($db, $sql_owner)) {
                    throw new Exception("Error deleting record from owner: " . mysqli_error($db));
                }
            }

            // Commit the transaction if all operations are successful

            // Redirect after successful deletion
            // header("Location: admin-index.php"); // Change this to your actual page
            // exit();
        } elseif ($_POST['action'] == 'block') {
            // Block selected owners
            $blockQuery = "UPDATE owner SET blocked=1 WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $blockQuery);
        } elseif ($_POST['action'] == 'unblock') {
            // Unblock selected owners
            $unblockQuery = "UPDATE owner SET blocked=0 WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $unblockQuery);
        } elseif ($_POST['action'] == 'reject') {
            // Unblock selected owners
            $unblockQuery = "DELETE FROM owner WHERE owner_id in ($ownerIds)";
            mysqli_query($db, $unblockQuery);
        } elseif ($_POST['action'] == 'approved') {
            // Unblock selected owners
            $unblockQuery = "UPDATE owner SET approved = 1 WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $unblockQuery);
        }
        mysqli_commit($db);
    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        mysqli_rollback($db);
        echo $e->getMessage();
    }
}
?>