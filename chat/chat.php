<?php
session_start();
include("config/config.php");

if (isset($_POST['message']) && isset($_POST['owner_id']) && isset($_POST['tenant_id'])) {
  $message = $_POST['message'];
  $owner_id = $_POST['owner_id'];
  $tenant_id = $_POST['tenant_id'];

  // Insert message into the database
  $sql = "INSERT INTO chat(message, owner_id, tenant_id) VALUES ('$message', '$owner_id', '$tenant_id')";
  $query = mysqli_query($db, $sql);

  if ($query) {
    // Success response (optional if you want to send a response back)
    echo "Message sent!";
  } else {
    // Handle failure (optional)
    echo "Error sending message.";
  }
}
?>
