<?php

include("config/config.php");
include("mail-engine.php");

if (isset($_POST['book_property'])) {


  if (isset($_SESSION["email"])) {
    global $db, $property_id;
    $u_email = $_SESSION["email"];

    $property_id = $_POST['property_id'];
    $payment_due = $_POST['estimated_price'] - $_POST['payable_amount'];
    $payment_mode = $_POST['payment_mode'];
    $amount_paid = $_POST['payable_amount'];

    $number_of_months = isset($_POST['number_of_months']) ? $_POST['number_of_months'] : 0; // Default to 0 if not set

        // Get the current date
        $current_date = new DateTime();

        // Calculate next payment date based on payment mode
        if ($payment_mode == "M" && $number_of_months > 0) {
            $next_payment_date = $current_date->modify("+{$number_of_months} month")->format("Y-m-d H:i:s");
        } elseif ($payment_mode == "Y") {
            $next_payment_date = $current_date->modify("+1 year")->format("Y-m-d H:i:s");
        } else {
            $next_payment_date = $current_date; // If no payment mode or invalid, set to null
        }

    $sql = "SELECT * FROM tenant where email='$u_email'";
    $query = mysqli_query($db, $sql);

    if (mysqli_num_rows($query) > 0) {
      while ($rows = mysqli_fetch_assoc($query)) {
        $tenant_id = $rows['tenant_id'];
        $tenant_name = $rows['full_name'];


        $sql1 = "UPDATE add_property SET booked='Yes' WHERE property_id='$property_id'";
        $query1 = mysqli_query($db, $sql1);

        $sql2 = "INSERT INTO booking(property_id,tenant_id,payment_mode,amount_paid,payment_due,next_payment_date) VALUES ('$property_id','$tenant_id','$payment_mode','$amount_paid','$payment_due','$next_payment_date')";
        $query2 = mysqli_query($db, $sql2);

        $sql3 = "SELECT owner_id from add_property WHERE property_id='$property_id'";
        $query3 = mysqli_query($db, $sql3);
        $owner_id = mysqli_fetch_assoc($query3)["owner_id"];

        $sql4 = "SELECT * from owner WHERE owner_id='$owner_id'";
        $query4 = mysqli_query($db, $sql4);
        $owners= mysqli_fetch_assoc($query4);
        $owners_name = $owners["full_name"];
        $owner_email = $owners["email"] ;



        if ($query2) {
          // After successful booking
          $subject = "Booking Successful";
          $body = "
                <h1>Booking Successfull</h1>
                 <p>Dear $tenant_name,</p>
                <p>Your booking for the property with property Id $property_id has been booked.</p>
                <p>You can contact owner of the property $owners_name.</p>
                <p> on gmail : $owner_email</p>
                <p>Regards,</p>
                <p>Admin Team</p>
                <p>Renthouse</p>
                ";

          $body1 = "
          <h1>Booking Successfull</h1>
           <p>Dear $owners_name,</p>
          <p>Your property with property Id $property_id has been booked by $tenant_name.</p>
          <p>You can contact your tenant of your property $tenant_name.</p>
          <p> on gmail : $u_email</p>
          <p>Regards,</p>
          <p>Admin Team</p>
          <p>Renthouse</p>
          ";

          // Send email to the tenant
          sendMail($u_email, $subject, $body);

          // Optionally send an email to the owner as well
          sendMail($owner_email, "New Booking for Your Property", $body1);
          header("Location: view-property.php?property_id=$property_id");
          ?>


          <style>
            .alert {
              padding: 20px;
              background-color: #DC143C;
              color: white;
            }

            .closebtn {
              margin-left: 15px;
              color: white;
              font-weight: bold;
              float: right;
              font-size: 22px;
              line-height: 20px;
              cursor: pointer;
              transition: 0.3s;
            }

            .closebtn:hover {
              color: black;
            }
          </style>
          <script>
            window.setTimeout(function () {
              $(".alert").fadeTo(1000, 0).slideUp(500, function () {
                $(this).remove();
              });
            }, 2000);
          </script>
          <div class="container">
            <div class="alert" role='alert'>
              <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
              <center><strong>Thankyou for booking this property.</strong></center>
            </div>
          </div>



          <?php





        }

      }




    }
  }
  // header("locaion:view-property.php?property_id=$property_id");
}
?>