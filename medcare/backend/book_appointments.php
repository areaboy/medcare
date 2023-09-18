<?php
//error_reporting(0);
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

session_start();
//include ('authenticate.php');

$userid =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$token =   htmlentities(htmlentities($_SESSION['token'], ENT_QUOTES, "UTF-8"));
$email =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['profession']);
$role = strip_tags($_SESSION['role']);




include('settings.php');
include('data6rst.php');



$p_date = strip_tags($_POST['p_date']);
$p_time = strip_tags($_POST['p_time']);

$title = strip_tags($_POST['title']);
$desc = strip_tags($_POST['desc']);

$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);
$tm = time();
$titlex ="Medical Appointments on $title --$tm";




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


//personal



$data_param= '{
  "name": "'.$titlex.'",
"folder":"/medical_appointments",
"metadata":{"created_by": "'.$fullname.'","email":"'.$email.'"},
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
echo $pid = $json['result']['id'];


if($request_id !='' && $status !='Success' ){


echo "<div style='background:red;color:white;padding:6px;border:none'>Medical Data Storage in Pangea Vaults Failed. Try Again.  Error: $summary <br></div>";

exit();

}





if($request_id !='' && $status =='Success'){





// Auditing by Pangea starts



if($audit_log_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Audit Log Access Token at <b>settings.php</b> File</div><br>";
exit();

}



$msgv ="Patients $fullname Booked Appointment";
$user_action="Booked Appointments";
$actor=$fullname;

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




if($request_id !='' && $status =='Success'){

echo "<div class='successx'>Audit Login Successful --- ($summary)</div><br>";


}


// Audit log ends









echo "<div style='background:green;color:white;padding:6px;border:none'>Medical Data Storage in Pangea Vaults Successful <br></div>";


$statement = $db->prepare('INSERT INTO appointments
(pangea_userid,email,fullname,services_title,pangea_vault_id,status,a_date,a_time,timing,diagnosis,medication)
                          values
(:pangea_userid,:email,:fullname,:services_title,:pangea_vault_id,:status,:a_date,:a_time,:timing,:diagnosis,:medication)');

$statement->execute(array( 
':pangea_userid' => $userid,
':email' => $email,
':fullname' => $fullname,
':services_title' => $title,
':pangea_vault_id' => $pid,
':status' => 'Open',
':a_date' => $p_date,
':a_time' => $p_time,
':timing' => $timer1,
':diagnosis' => '0',
':medication' => '0'

));


$res = $db->query("SELECT LAST_INSERT_ID()");
$lastId_post = $res->fetchColumn();


if($statement){


echo "<div id='alertdata' style='background:green;color:white;padding:10px;border:none;'><br>Appointment Booked Successfully..</div>";

echo "<script>alert('Appointment Submitted Successfully'); location.reload();</script>";




}
else {
echo "<div id='alertdata' class='alerts alert-danger'>Appointment Boooking Failed. Please Try Again...<br></div>";
}


}






}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}





?>



