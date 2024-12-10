<?php
include '../mail-engine.php';

$subject1 = "Property Deleted";
$body1 = "
                            <h1>Property Deleted</h1>
                            <p>Dear $owner_name,</p>
                            <p>We regret to inform you that your property with Property ID $property_id has been deleted from our system.</p>
                            <p>If you have any questions, please feel free to contact us.</p>
                            <p>Regards,</p>
                            <p>Admin Team</p>
                            <p>Renthouse</p>
                            ";


$subject2 = "Property Blocked";
$body2 = "
        <h1>Property Blocked</h1>
        <p>Dear $owner_name,</p>
        <p>Your property with Property ID $property_id has been blocked due to violations of our policies or other issues.</p>
        <p>For further details, kindly contact us.</p>
        <p>Regards,</p>
        <p>Admin Team</p>
        <p>Renthouse</p>
        ";


$subject3 = "Property Unblocked";
$body3 = "
        <h1>Property Unblocked</h1>
        <p>Dear $owner_name,</p>
        <p>We are pleased to inform you that your property with Property ID $property_id has been unblocked and is now active again.</p>
        <p>You may now manage your property as usual.</p>
        <p>Regards,</p>
        <p>Admin Team</p>
        <p>Renthouse</p>
        ";



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


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && isset($_POST['selected_property'])) {
    $selectedProperty = $_POST['selected_property'];
    $propertyIds = implode(',', $selectedProperty); // Convert array to comma-separated list

    try {
        if ($_POST['action'] == 'delete') {
            // Delete selected properties
            // $deleteQuery = "DELETE FROM add_property WHERE property_id IN ($propertyIds)";
            // mysqli_query($db, $deleteQuery);
            // Delete records from the booking table
            $sql_booking = "DELETE FROM booking WHERE property_id in ($propertyIds)";
            if (!mysqli_query($db, $sql_booking)) {
                throw new Exception("Error deleting record from booking: " . mysqli_error($db));
            }

            // Delete records from the property_photo table
            $sql_property_photo = "DELETE FROM property_photo WHERE property_id IN ($propertyIds)";
            if (!mysqli_query($db, $sql_property_photo)) {
                throw new Exception("Error deleting record from property_photo: " . mysqli_error($db));
            }

            // Delete records from the review table
            $sql_review = "DELETE FROM review WHERE property_id in ($propertyIds)";
            if (!mysqli_query($db, $sql_review)) {
                throw new Exception("Error deleting record from review: " . mysqli_error($db));
            }

            // Delete the property record from add_property table
            $sql_property = "DELETE FROM add_property WHERE property_id in ($propertyIds)";
            if (!mysqli_query($db, $sql_property)) {
                throw new Exception("Error deleting record from add_property: " . mysqli_error($db));
            }

        } elseif ($_POST['action'] == 'block') {
            // Block selected properties
            $blockQuery = "UPDATE add_property SET blocked=1 WHERE property_id IN ($propertyIds)";
            mysqli_query($db, $blockQuery);
        } elseif ($_POST['action'] == 'unblock') {
            // Unblock selected properties
            $unblockQuery = "UPDATE add_property SET blocked=0 WHERE property_id IN ($propertyIds)";
            mysqli_query($db, $unblockQuery);
        } elseif ($_POST['action'] == 'reject') {
            // Delete records from the property_photo table
            $sql_property_photo = "DELETE FROM property_photo WHERE property_id IN ($propertyIds)";
            if (!mysqli_query($db, $sql_property_photo)) {
                throw new Exception("Error deleting record from property_photo: " . mysqli_error($db));
            }

            // Delete records from the review table
            $sql_review = "DELETE FROM review WHERE property_id in ($propertyIds)";
            if (!mysqli_query($db, $sql_review)) {
                throw new Exception("Error deleting record from review: " . mysqli_error($db));
            }

            // Delete the property record from add_property table
            $sql_property = "DELETE FROM add_property WHERE property_id in ($propertyIds)";
            if (!mysqli_query($db, $sql_property)) {
                throw new Exception("Error deleting record from add_property: " . mysqli_error($db));
            }
            // Unblock selected properties
        } elseif ($_POST['action'] == 'approved') {
            // Unblock selected properties
            $approveQuery = "UPDATE add_property SET approved=1 WHERE property_id IN ($propertyIds)";
            mysqli_query($db, $approveQuery);
        }
        // Commit the transaction if all operations are successful
        mysqli_commit($db);

        // Redirect after successful deletion
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit;
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
    }
}
?>