<?php 
session_start();
isset($_SESSION["email"]);
include("navbar.php");
include("config/config.php");

// Fetch tenant_id from session email
$u_email = $_SESSION["email"];
$sql = "SELECT tenant_id FROM tenant WHERE email='$u_email'";
$query = mysqli_query($db, $sql);
$tenant_row = mysqli_fetch_assoc($query);
$tenant_id = $tenant_row['tenant_id'];

// Check if message is sent
if (isset($_POST['send_message'])) {
    $owner_id = $_POST['owner_id'];
    $message = $_POST['message'];

    $insert_query = "INSERT INTO chat (message, owner_id, tenant_id) VALUES ('$message', '$owner_id', '$tenant_id')";
    mysqli_query($db, $insert_query);

    // Return response for AJAX to handle (No full page refresh)
    echo json_encode(["status" => "success"]);
    exit();
}

if (isset($_POST['owner_id'])) {
    $owner_id = $_POST['owner_id'];

    // Fetch chat history between tenant and owner
    $sql1 = "SELECT * FROM chat WHERE owner_id='$owner_id' AND tenant_id='$tenant_id'";
    $query1 = mysqli_query($db, $sql1);
}
?>

<!-- Styles for modern chat UI -->
<style>
  h3 {
    color: white;
  }

  .container {
    margin-top: 3%;
    width: 60%;
    padding-right: 10%;
    padding-left: 10%;
  }

  .display-chat {
    height: 300px;
    background-color: lightgrey;
    margin-bottom: 4%;
    overflow-y: auto;
    padding: 15px;
  }

  .message {
    max-width: 60%;
    margin-bottom: 3%;
    padding: 10px;
    border-radius: 10px;
    clear: both;
  }

  .sent-message {
    background-color: #007bff;
    color: white;
    margin-left: auto;
    text-align: right;
  }

  .received-message {
    background-color: #e5e5ea;
    margin-right: auto;
    text-align: left;
    color: black;
  }
</style>

<!-- Chat Window -->
<div class="container">
  <center><h3>Send Messages</h3></center>
  
  <div class="display-chat" id="chatWindow">
    <?php if (mysqli_num_rows($query1) > 0): ?>
        <?php while ($row1 = mysqli_fetch_assoc($query1)): ?>
            <?php if ($row1['tenant_id'] == $tenant_id): ?>
                <!-- Message sent by logged-in tenant -->
                <div class="message sent-message">
                    <p><span><?php echo $row1['message']; ?></span></p>
                </div>
            <?php else: ?>
                <!-- Message sent by owner -->
                <div class="message received-message">
                    <p><span><?php echo $row1['message']; ?></span></p>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="message">
            <p>No previous chat available.</p>
        </div>
    <?php endif; ?>
  </div>

  <!-- Message Input Form -->
  <form id="chatForm" class="form-horizontal" method="post">
    <div class="form-group">
      <div class="col-sm-10"> 
        <input type="hidden" name="owner_id" value="<?php echo $owner_id; ?>">    
        <textarea id="messageInput" name="message" class="form-control" placeholder="Type your message here..."></textarea>
      </div>
      <div class="col-sm-2">
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </div>
  </form>

  <center><button onclick="goBack()" class="btn btn-success">Go Back</button></center>
</div>

<!-- JavaScript for AJAX handling -->
<script>
  document.getElementById('chatForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent traditional form submission

    const formData = new FormData(this); // Get form data
    const xhr = new XMLHttpRequest(); // Create AJAX request

    xhr.open('POST', 'chat.php', true); // Send data to the same page
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Parse the response
        const response = JSON.parse(xhr.responseText);
        
        if (response.status === 'success') {
          // Append new message to chat window
          const chatWindow = document.getElementById('chatWindow');
          const newMessage = document.createElement('div');
          newMessage.classList.add('message', 'sent-message');
          newMessage.innerHTML = `<p><span>${formData.get('message')}</span></p>`;
          chatWindow.appendChild(newMessage);

          // Clear the input field
          document.getElementById('messageInput').value = '';

          // Scroll to the bottom of the chat
          chatWindow.scrollTop = chatWindow.scrollHeight;
        }
      }
    };

    xhr.send(formData); // Send form data via AJAX
  });

  function goBack() {
    window.history.back();
  }
</script>

<?php
// Footer, closing tags, and other parts of your page can go here.
