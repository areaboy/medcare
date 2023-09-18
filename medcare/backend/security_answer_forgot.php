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

$timec = time();
$email_title = "Your Login Security Answer -- $timec";
$email = strip_tags($_POST['email']);
$fullname = strip_tags($_POST['fname']);
$pangea_vault_id = strip_tags($_POST['pangea_vault_idx']);
$secret_question = strip_tags($_POST['secret_question']);

/*
var pangea_vault_idx  = '$pangea_vault_id';
var email = '$email';
var fname   =  '$fname';
var secret_question = '$secret_question';
*/

$timer1= time();
$email_subject = $email_title;




// Load Composer's autoloader
require 'phpemailer/vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);








// Fetching Security Question and answer from Pangea Vaults


$data_param= '{
  "id": "'.$pangea_vault_id.'"
}';



$url ="https://vault.aws.us.pangea.cloud/v1/get";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $vault_intel_accesstoken"));  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
$output = curl_exec($ch); 


$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// catch error message before closing
if (curl_errno($ch)) {
    //echo $error_msg = curl_error($ch);
}

curl_close($ch); 





if($output ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ensure there is Internet Connections and Try Again</div><br>";


exit();
}



$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];
$secret = $json['result']['current_version']['secret'];


if($request_id !='' && $status !='Success' ){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Security Answer in Pangea Vaults Retrieval  Failed. Try Again.  Error: $summary</div><br>";

exit();

}






$recipient_name = $fullname;
$recipient_email = $email;		    

$messaging = "Hi $recipient_name, Here is Your Login Security Question and Answer:<br><br>


<b>Question: </b> $secret_question<br>
<b>Answer:</b> $secret<br>

";



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

    echo "
<script>alert('Security Answer successfully sent to your Email');</script>
<div style='background:green;color:white;padding:10px;border:none;'> Security Answer successfully sent to your Email</div>";



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



