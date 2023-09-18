<?php
//error_reporting(0);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

session_start();
//include ('authenticate.php');

$userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$token_sess =   htmlentities(htmlentities($_SESSION['token'], ENT_QUOTES, "UTF-8"));
$email_sess =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['profession']);
$role = strip_tags($_SESSION['role']);




include('settings.php');
include('data6rst.php');



$email = strip_tags($_POST['email']);
$fullname = strip_tags($_POST['fullname']);
$title = strip_tags($_POST['med_services']);
$desc = strip_tags($_POST['diagnosis']);
$pangea_userid = strip_tags($_POST['userid']);


$diagnosis_count = strip_tags($_POST['diagnosis_count']);
$medication_count = strip_tags($_POST['medication_count']);
$appointment_id = strip_tags($_POST['appointment_id']);

$med_dm = strip_tags($_POST['med_dm']);



$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);
$tm = time();
$titlex ="Medical $med_dm for $fullname --$tm";




if ($email == ''){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Email Address is empty</font></div>";
exit();
}

$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>Email Address is Invalid</font></div>";
exit();
}

$ip= filter_var($ipaddress, FILTER_VALIDATE_IP);
if (!$ip){
echo "<div class='alert alert-danger' id='alerts_reg'><font color=red>IP Address is Invalid</font></div>";
exit();
}

		 

$timer1= time();



$data_param= '{
  "name": "'.$titlex.'",
"folder":"/medical_diagnosis",
"metadata":{"patient_name": "'.$fullname.'","patient_email":"'.$email.'", "doctor_name":"'.$fullname_sess.'","doctor_profession":"'.$profession.'","timing":"'.$timer1.'","appointment_id":"'.$appointment_id.'", "doctor_action":"'.$med_dm.'"},
  "secret": "'.$desc.'"
}';



$url ="https://vault.aws.us.pangea.cloud/v1/secret/store";

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

echo "<div style='background:red;color:white;padding:6px;border:none'>Please Ensure there is Internet Connections and Try Again.<br></div>";
exit();
}


$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];
$pid = $json['result']['id'];


if($request_id !='' && $status !='Success' ){


echo "<div style='background:red;color:white;padding:6px;border:none'>Medical Data Storage in Pangea Vaults Failed. Try Again.  Error: $summary <br></div>";

exit();

}




if($request_id !='' && $status =='Success'){

echo "<div style='background:green;color:white;padding:6px;border:none'>Medical Data Storage in Pangea Vaults Successful <br></div>";
echo "<script>alert('Medical Data Storage in Pangea Vaults Successful'); location.reload(); </script>";
}





// Auditing by Pangea starts

if($audit_log_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Audit Log Access Token at <b>settings.php</b> File</div><br>";
exit();

}


// one start

if($med_dm == 'Diagnosis'){

$msgv ="Dr. $fullname_sess Wrotes Diagnosis Reports for Patients $fullname";
$user_action="Wrotes Diagnosis Reports";
$actor=$fullname_sess;

$data_param= '{"event":{"message":"'.$msgv.'","action":"'.$user_action.'","actor":"'.$actor.'"}}';


$url ="https://audit.aws.us.pangea.cloud/v1/log";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $audit_log_accesstoken"));  
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

echo "<div class='dangerx'>
 Please Ensure there is Internet Connections and Try Again</div><br>";
exit();
}


$json = json_decode($output, true);
$user_id = $json['result']['id'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];


if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 Audit Log creation Failed. Try Again.  Error: $summary  </div><br>";
exit();

}



}


// one end



//two start



if($med_dm == 'Drug Prescription'){
$msgv ="Dr. $fullname_sess Prescribes Drugs for Patients $fullname";
$user_action="Prescribes Drugs";
$actor=$fullname_sess;

$data_param= '{"event":{"message":"'.$msgv.'","action":"'.$user_action.'","actor":"'.$actor.'"}}';


$url ="https://audit.aws.us.pangea.cloud/v1/log";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $audit_log_accesstoken"));  
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

echo "<div class='dangerx'>
 Please Ensure there is Internet Connections and Try Again</div><br>";
exit();
}


$json = json_decode($output, true);
$user_id = $json['result']['id'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];


if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 Audit Log creation Failed. Try Again.  Error: $summary  </div><br>";
exit();

}

}


//two end


// Audit log ends




}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}





?>



