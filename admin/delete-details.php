<?php
// Include your database connection
include("../config/config.php");


if(isset($_POST['delete_property'])) {

    $db = new mysqli('localhost','root','','renthouse', 8080, null);

        if($db->connect_error){
            echo "Error connecting database";
            exit();
        }
    // Get the property ID from the form
    $property_id = $_POST['property_id'];
    
    // Start a transaction to ensure all operations succeed or fail together
    mysqli_begin_transaction($db);
    
    try {
        // Delete records from the booking table
        $sql_booking = "DELETE FROM booking WHERE property_id = '$property_id'";
        if (!mysqli_query($db, $sql_booking)) {
            throw new Exception("Error deleting record from booking: " . mysqli_error($db));
        }
        
        // Delete records from the property_photo table
        $sql_property_photo = "DELETE FROM property_photo WHERE property_id = '$property_id'";
        if (!mysqli_query($db, $sql_property_photo)) {
            throw new Exception("Error deleting record from property_photo: " . mysqli_error($db));
        }
        
        // Delete records from the review table
        $sql_review = "DELETE FROM review WHERE property_id = '$property_id'";
        if (!mysqli_query($db, $sql_review)) {
            throw new Exception("Error deleting record from review: " . mysqli_error($db));
        }
        
        // Delete the property record from add_property table
        $sql_property = "DELETE FROM add_property WHERE property_id = '$property_id'";
        if (!mysqli_query($db, $sql_property)) {
            throw new Exception("Error deleting record from add_property: " . mysqli_error($db));
        }
        
        // Commit the transaction if all operations are successful
        mysqli_commit($db);
        
        // Redirect after successful deletion
        header("Location: admin-index.php"); // Change this to your actual page
        exit();
        
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
    }
}



else if (isset($_POST['delete_owner'])) {

    $db = new mysqli('localhost','root','','renthouse', 8080, null);

        if($db->connect_error){
            echo "Error connecting database";
            exit();
        }

    // Get the owner ID from the form
    $owner_id = $_POST['owner_id'];
    
    // Start a transaction to ensure all operations succeed or fail together
    mysqli_begin_transaction($db);
    
    try {
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
        
        // Commit the transaction if all operations are successful
        mysqli_commit($db);
        
        // Redirect after successful deletion
        header("Location: admin-index.php"); // Change this to your actual page
        exit();
        
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
    }
}



else if (isset($_POST['delete_tenant'])) {
    
    $db = new mysqli('localhost','root','','renthouse', 8080, null);

        if($db->connect_error){
            echo "Error connecting database";
            exit();
        }

    // Get the tenant ID from the form
    $tenant_id = $_POST['tenant_id'];
    echo "I was here";
    echo $tenant_id;
    // Start a transaction to ensure all operations succeed or fail together
    mysqli_begin_transaction($db);
    
    try {
        // Retrieve the property_id from the booking table using tenant_id
        $sql_get_property_id = "SELECT property_id FROM booking WHERE tenant_id = '$tenant_id'";
        $result = mysqli_query($db, $sql_get_property_id);
        
        if ($result || mysqli_num_rows($result) != 0) {
            while($row = mysqli_fetch_assoc($result)){
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
        
        // Commit the transaction if all operations are successful
        mysqli_commit($db);
        
        // Redirect after successful deletion
        header("Location: admin-index.php"); // Change this to your actual page
        exit();
        
    } catch (Exception $e) {
        // Roll back the transaction in case of error
        mysqli_rollback($db);
        echo $e->getMessage();
        header("Location: admin-index.php"); // Change this to your actual page
        exit();
    }
}



?>
