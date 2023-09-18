<?php

/*
The phpmailer keyword must be declared in the outermost or topmost scope of a file (the global scope) 
or inside namespace declarations. This is because the importing is done at compile time and not runtime.
 otherwise it will throw error syntax error, unexpected 'use' (T_USE)
*/

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(0);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);

if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

include('settings.php');
//include('data6rst.php');
$sender_name = $hospital_name;
$sender_email = $hospital_email;

$email_title = strip_tags($_POST['email_title']);
$email_message = strip_tags($_POST['email_message']);
$email = strip_tags($_POST['email']);
$fullname = strip_tags($_POST['fullname']);
$userid = strip_tags($_POST['userid']);
$med_services= strip_tags($_POST['med_services']);


$timer1= time();



$email_subject = $email_title;
$email_message="$email_message";



// Load Composer's autoloader
require 'phpemailer/vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);


$recipient_name = $fullname;
$recipient_email = $email;		    

$messaging = "Hi $recipient_name, You have Message from $sender_name  based on Medical Appointment  Services you booked. <b>$med_services</b> <br><br>

<b>Sender Name: </b> $sender_name<br>
<b>Sender Email: </b> $sender_email<br>
<b>Message: </b> $email_message";


// php mailer try starts here

try {
    
    
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);

    //Server settings
    $mail->SMTPDebug = 0;                                       // 0 - Disable Debugging, 2 - Responses received from the server
    $mail->isSMTP();                                            // Set mailer to use SMTP
    $mail->Host       = $smtp_email_host;  // Specify main and backup SMTP servers
    $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    $mail->Username   = $smtp_email_username;                     // SMTP username
    $mail->Password   = $smtp_email_password;                               // SMTP password
    $mail->SMTPSecure = 'tls';//PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
    $mail->Port       = $smtp_email_port;                                    // TCP port to connect to

    //Sender and sender name
    //$mail->setFrom('support@fredjarsoft.com', 'f');

    $mail->setFrom('support@fredjarsoft.com', "$sender_name");

//recipient email address and name
    //$mail->addAddress('ese@gmail.com', 'fred recipients');     // Add a recipient
      $mail->addAddress($recipient_email, $recipient_name);     // Add a recipient
  
    // Content
    $mail->isHTML(true); // Set email format to HTML
    $mail->Subject = $email_subject;
    $mail->Body = $messaging;
    $mail->AltBody = $messaging; // for Plain text for non-HTML mails

   $sent =  $mail->send();

    echo "<div style='background:green;color:white;padding:10px;border:none;'> Email Message successfully sent</div>";



} catch (Exception $e) {
 echo "<div style='background:red;color:white;padding:10px;border:none;'>Email Message not sent. Error: {$mail->ErrorInfo}</div>";

//echo 0;

}


//php mailer try email ends here




   
}else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


?>



