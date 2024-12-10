<?php 
session_start();
include("navbar.php");
include("config/config.php");

if (isset($_SESSION["email"])) {
    $u_email = $_SESSION["email"];
    
    // Fetch tenant_id based on email
    $sql = "SELECT * FROM tenant WHERE email='$u_email'";
    $query = mysqli_query($db, $sql);

    if ($query && mysqli_num_rows($query) > 0) {
        $row = mysqli_fetch_assoc($query); // fetch the tenant's data
        $tenant_id = $row['tenant_id'];

        if (isset($_POST['send_message'])) {
            $owner_id = $_POST['owner_id'];

            // Fetch previous chat between tenant and owner
            $sql1 = "SELECT * FROM chat WHERE owner_id='$owner_id' AND tenant_id='$tenant_id'";
            $query1 = mysqli_query($db, $sql1);

            // Display the chat interface
            ?>
           <div class="container">
  <center><h3>Send Messages</h3></center>
  <div class="display-chat" id="chatWindow">
  <?php
  if (mysqli_num_rows($query1) > 0) {
      while ($row1 = mysqli_fetch_assoc($query1)) {
          ?>
          <div class="message">
            <p>
              <span><?php echo $row1['message']; ?></span>
            </p>
          </div>
          <?php
      }
  } else {
      ?>
      <div class="message">
        <p>No previous chat available.</p>
      </div>
      <?php
  }
  ?>
  </div>
  
  <!-- Form to send new message -->
  <form id="chatForm" class="form-horizontal">
    <div class="form-group">
      <div class="col-sm-10">
        <input type="hidden" name="owner_id" value="<?php echo $owner_id; ?>">
        <input type="hidden" name="tenant_id" value="<?php echo $tenant_id; ?>">
        <textarea name="message" id="messageInput" class="form-control" placeholder="Type your message here..."></textarea>
      </div>
      <div class="col-sm-2">
        <button type="submit" class="btn btn-primary">Send</button>
      </div>
    </div>
  </form>
</div>

<center><button onclick="goBack()" class="btn btn-success">Go Back</button></center>
<script>
  function goBack() {
    window.history.back();
  }
</script>
<?php }}} ?>

<!-- AJAX to submit form without reloading page -->
<script>
  document.getElementById('chatForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent form from submitting the traditional way

    const formData = new FormData(this);
    const xhr = new XMLHttpRequest();

    xhr.open('POST', 'chat.php', true);
    xhr.onload = function () {
      if (xhr.status === 200) {
        // Add the new message to the chat window
        const chatWindow = document.getElementById('chatWindow');
        const newMessage = document.createElement('div');
        newMessage.classList.add('message');
        newMessage.innerHTML = `<p><span>${formData.get('message')}</span></p>`;
        chatWindow.appendChild(newMessage);

        // Clear the input field after sending
        document.getElementById('messageInput').value = '';

        // Scroll to the bottom of the chat window
        chatWindow.scrollTop = chatWindow.scrollHeight;
      }
    };

    xhr.send(formData);
  });
</script>
