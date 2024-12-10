<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// require 'vendor/autoload.php'; // Include PHPMailer files if using Composer

function sendMail($toEmail, $subject, $body) {
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->isSMTP(); 
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = ''; // Your email login
        $mail->Password = ''; // Your email password
        $mail->SMTPSecure = 'tls'; 
        // $mail->SetFrom("email");
        $mail->CharSet = "UTF-8";
        $mail->Port = 587; 
        $mail->SMTPOptions = array('ssl'=>array(
            'verify_peer'=> 'false',
            'verify_peer_name'=> 'false',
            'allow_self_signed'=> 'false'
        ));
        
        // Recipients
        $mail->setFrom('', 'Renthouse'); 
        $mail->addAddress($toEmail); 

        // Content
        $mail->isHTML(true); 
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        // showAlert("Email has been sent.");
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
