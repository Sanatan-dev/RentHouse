<?php
// Include the mail setup configuration (if needed) and any required libraries
// For example, include PHPMailer if you are using a library for better mail handling

// Database connection and other required configurations
include '../mail-engine.php'; // Include the mail engine

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_owners'])) {
    $selectedOwners = $_POST['selected_owners'];
    $ownerIds = implode(',', $selectedOwners); // Convert array to comma-separated list

    mysqli_begin_transaction($db);
    try {
        // Fetch the owner details for sending emails
        $ownerQuery = "SELECT owner_id, full_name, email FROM owner WHERE owner_id IN ($ownerIds)";
        $ownerResult = mysqli_query($db, $ownerQuery);
        if (!$ownerResult) {
            throw new Exception("Error fetching owner details: " . mysqli_error($db));
        }

        $owners = [];
        while ($ownerRow = mysqli_fetch_assoc($ownerResult)) {
            $owners[] = $ownerRow; // Store owner details for later use
        }

        // Perform the action based on the value of $_POST['action']
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
            // Prepare the email body and subject for deletion
            $subject = "Account Deleted: %s";
            $message = "Dear %s,\n\nYour account has been deleted along with all associated properties.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'block') {
            // Block selected owners
            $blockQuery = "UPDATE owner SET blocked=1 WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $blockQuery);
            // Prepare the email body and subject for blocking
            $subject = "Account Blocked: %s";
            $message = "Dear %s,\n\nYour account has been blocked. Please contact support for more information.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'unblock') {
            // Unblock selected owners
            $unblockQuery = "UPDATE owner SET blocked=0 WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $unblockQuery);
            // Prepare the email body and subject for unblocking
            $subject = "Account Unblocked: %s";
            $message = "Dear %s,\n\nYour account has been unblocked. You can now continue using the platform.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'reject') {
            // Reject (delete) selected owners
            $rejectQuery = "DELETE FROM owner WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $rejectQuery);
            // Prepare the email body and subject for rejection
            $subject = "Account Rejected: %s";
            $message = "Dear %s,\n\nYour account has been rejected and deleted from the platform.\n\nRegards,\nHouse Rental System";

        } elseif ($_POST['action'] == 'approved') {
            // Approve selected owners
            $approveQuery = "UPDATE owner SET approved = 1 WHERE owner_id IN ($ownerIds)";
            mysqli_query($db, $approveQuery);
            // Prepare the email body and subject for approval
            $subject = "Account Approved: %s";
            $message = "Dear %s,\n\nYour account has been approved. You can now access all features on the platform.\n\nRegards,\nHouse Rental System";
        }

        // Send the emails to each owner
        foreach ($owners as $owner) {
            $ownerName = $owner['full_name'];
            $ownerEmail = $owner['email'];
            $emailSubject = sprintf($subject, $ownerName); // Replace %s with owner's name
            $emailMessage = sprintf($message, $ownerName); // Replace %s with owner's name

            // Send the email (use PHP's mail function, or a more reliable email library like PHPMailer)
           sendMail($ownerEmail, $emailSubject, $emailMessage);
        }

        // Commit the transaction if all operations are successful
        mysqli_commit($db);

    } catch (Exception $e) {
        // Rollback the transaction in case of any errors
        mysqli_rollback($db);
        echo $e->getMessage();
    }
}
?>
