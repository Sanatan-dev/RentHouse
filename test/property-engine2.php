<?php
include '../mail-engine.php'; // Include the mail engine

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_property'])) {
    $selectedProperty = $_POST['selected_property'];
    $propertyIds = implode(',', $selectedProperty); // Convert array to comma-separated list

    try {
        foreach ($selectedProperty as $property_id) {
            // Fetch the owner details using property_id
            $ownerQuery = "SELECT owner_id FROM add_property WHERE property_id = '$property_id'";
            $ownerResult = mysqli_query($db, $ownerQuery);
            if (!$ownerResult || mysqli_num_rows($ownerResult) == 0) {
                throw new Exception("Owner not found for property_id: $property_id");
            }
            $ownerRow = mysqli_fetch_assoc($ownerResult);
            $owner_id = $ownerRow['owner_id'];

            // Fetch owner's full name and email using owner_id
            $ownerDetailsQuery = "SELECT full_name, email FROM owner WHERE owner_id = '$owner_id'";
            $ownerDetailsResult = mysqli_query($db, $ownerDetailsQuery);
            if (!$ownerDetailsResult || mysqli_num_rows($ownerDetailsResult) == 0) {
                throw new Exception("Owner details not found for owner_id: $owner_id");
            }
            $ownerDetailsRow = mysqli_fetch_assoc($ownerDetailsResult);
            $owner_name = $ownerDetailsRow['full_name'];
            $owner_email = $ownerDetailsRow['email'];

            // Perform action based on the selected action
            if ($_POST['action'] == 'delete') {
                // Delete selected properties
                $sql_booking = "DELETE FROM booking WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_booking)) {
                    throw new Exception("Error deleting record from booking: " . mysqli_error($db));
                }
                $sql_property_photo = "DELETE FROM property_photo WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_property_photo)) {
                    throw new Exception("Error deleting record from property_photo: " . mysqli_error($db));
                }
                $sql_review = "DELETE FROM review WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_review)) {
                    throw new Exception("Error deleting record from review: " . mysqli_error($db));
                }
                $sql_property = "DELETE FROM add_property WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_property)) {
                    throw new Exception("Error deleting record from add_property: " . mysqli_error($db));
                }

                // Prepare and send email for deletion
                $subject = "Property Deleted";
                $body = "
                    <h1>Property Deleted</h1>
                    <p>Dear $owner_name,</p>
                    <p>We regret to inform you that your property with Property ID $property_id has been deleted from our system.</p>
                    <p>If you have any questions, please feel free to contact us.</p>
                    <p>Regards,</p>
                    <p>Admin Team</p>
                    <p>Renthouse</p>
                ";
                sendmail($owner_email, $subject, $body);

            } elseif ($_POST['action'] == 'block') {
                // Block selected properties
                $blockQuery = "UPDATE add_property SET blocked=1 WHERE property_id = '$property_id' and booked='No'";
                if(mysqli_query($db, $blockQuery)) {
                    // Prepare and send email for blocking
                    $subject = "Property Blocked";
                    $body = "
                        <h1>Property Blocked</h1>
                        <p>Dear $owner_name,</p>
                        <p>Your property with Property ID $property_id has been blocked due to violations of our policies or other issues.</p>
                        <p>For further details, kindly contact us.</p>
                        <p>Regards,</p>
                        <p>Admin Team</p>
                        <p>Renthouse</p>
                    ";
                    sendmail($owner_email, $subject, $body);
                }

            } elseif ($_POST['action'] == 'unblock') {
                // Unblock selected properties
                $unblockQuery = "UPDATE add_property SET blocked=0 WHERE property_id = '$property_id'";
                mysqli_query($db, $unblockQuery);

                // Prepare and send email for unblocking
                $subject = "Property Unblocked";
                $body = "
                    <h1>Property Unblocked</h1>
                    <p>Dear $owner_name,</p>
                    <p>We are pleased to inform you that your property with Property ID $property_id has been unblocked and is now active again.</p>
                    <p>You may now manage your property as usual.</p>
                    <p>Regards,</p>
                    <p>Admin Team</p>
                    <p>Renthouse</p>
                ";
                sendmail($owner_email, $subject, $body);

            } elseif ($_POST['action'] == 'reject') {
                // Reject selected properties
                $sql_property_photo = "DELETE FROM property_photo WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_property_photo)) {
                    throw new Exception("Error deleting record from property_photo: " . mysqli_error($db));
                }
                $sql_review = "DELETE FROM review WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_review)) {
                    throw new Exception("Error deleting record from review: " . mysqli_error($db));
                }
                $sql_property = "DELETE FROM add_property WHERE property_id = '$property_id'";
                if (!mysqli_query($db, $sql_property)) {
                    throw new Exception("Error deleting record from add_property: " . mysqli_error($db));
                }

                // Prepare and send email for rejection
                $subject = "Property Rejected";
                $body = "
                    <h1>Property Rejected</h1>
                    <p>Dear $owner_name,</p>
                    <p>We regret to inform you that your property submission with Property ID $property_id has been rejected after review.</p>
                    <p>For more information or to appeal the decision, kindly contact us.</p>
                    <p>Regards,</p>
                    <p>Admin Team</p>
                    <p>Renthouse</p>
                ";
                sendmail($owner_email, $subject, $body);

            } elseif ($_POST['action'] == 'approved') {
                // Approve selected properties
                $approveQuery = "UPDATE add_property SET approved=1 WHERE property_id = '$property_id'";
                mysqli_query($db, $approveQuery);

                // Prepare and send email for approval
                $subject = "Property Approved";
                $body = "
                    <h1>Property Approved</h1>
                    <p>Dear $owner_name,</p>
                    <p>We are happy to inform you that your property with Property ID $property_id has been successfully approved and is now listed on our platform.</p>
                    <p>You can now manage and view your property online.</p>
                    <p>Regards,</p>
                    <p>Admin Team</p>
                    <p>Renthouse</p>
                ";
                sendmail($owner_email, $subject, $body);
            }
        }

        // Commit the transaction if all operations are successful
        mysqli_commit($db);

        // Redirect after successful operation
        // header("Location: admin-index.php"); // Uncomment and change to your actual page
        // exit();

    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
    }
}
?>
