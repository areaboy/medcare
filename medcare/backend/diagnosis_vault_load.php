<?php
error_reporting(0);

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);



session_start();
$fullname_sessx =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
$appointment_id = strip_tags($_POST['appointment_id']);

if($appointment_id ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>There is an Issue with Patients Appointment ID</div><br>";
exit();

}


include('settings.php');

if($vault_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Vault INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}


// Get Vault List


$data_param='{"filter":{"folder":"/medical_diagnosis","metadata_appointment_id": "'.$appointment_id.'"},"size":2000,"order":"desc"}';



$url ="https://vault.aws.us.pangea.cloud/v1/list";
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

echo "<div class='dangerx'>
 Please Ensure there is Internet Connections and Try Again</div><br>";
exit();
}


$json = json_decode($output, true);
$user_id = $json['result']['id'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];

$res = $json['result']['items'];


if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 Retrieving Password Failed. Try Again.  Error: $summary  </div><br>";
exit();

}



if($res ==[] ){
echo "<div class='dangerx'>&nbsp;&nbsp;&nbsp;&nbsp;No Diagnosis OR Medications has been created on Pangea Vault for This Patients Medical Conditions </div><br>";
exit();

}




if($request_id !='' && $status =='Success'){

//echo "<div class='successx'>Password Vaults Info SuccessFully Retrieved </div><br>";

}




echo '<div class="row"><div class="col-sm-1"></div>';


$count == $jsonx['result']['count'];

foreach($json['result']['items'] as $row){

$diagnosis_title = $row['name'];
$type = $row['type'];
$diagnosis = $row['secret'];
$pangea_vault_id = $row['id'];
$created_at = $row['created_at'];
$appointment_idx = $row['metadata']['appointment_id'];
$doctor_name = $row['metadata']['doctor_name'];
$doctor_profession = $row['metadata']['doctor_profession'];
$patient_email = $row['metadata']['patient_email'];
$patient_name = $row['metadata']['patient_name'];
$timing = $row['metadata']['timing'];
$doctor_action = $row['metadata']['doctor_action'];


if($appointment_id == $appointment_id){



echo "<div class='well recz_$pangea_vault_id'>
<b style='font-size:18px;color:purple'> Medical Action: $doctor_action </b><br>

<b> Patients Name:</b>   $patient_name<br>
<b> Medical Title:</b> $diagnosis_title  <br>
<b> Type:</b>   $type<br>
<b> Patients Medical Conditions/Drug Prescription:</b> <br>
<div class='loader-get_$pangea_vault_id'></div>
   <div class='result-get_$pangea_vault_id'></div>
<button class='btn btn-success get_btn' data-id='$pangea_vault_id'  title='Retrieve Medical Data from Pangea Vaults'>Retrieve Medical Data from Pangea Vaults</button>
<br>

<b> $doctor_action by Dr.:</b>  $doctor_name <br>
<b> Dr. Profession:</b>   $doctor_profession<br>
<b> Created_At:</b>   $created_at<br>
<b> Timing:</b>  <span data-livestamp=$timing></span><br>

  <div class='loader-deletez_$pangea_vault_id'></div>
   <div class='result-deletez_$pangea_vault_id'></div>
<button class='btn btn-danger deletez_btn' data-id='$pangea_vault_id'  title='Delete Data'>Delete Data</button>


</div><br>";


}

}









// Auditing by Pangea starts



if($audit_log_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Audit Log Access Token at <b>settings.php</b> File</div><br>";
exit();

}


// one start

if($patient_name !=  $fullname_sessx){

$msgv ="Medical Staff/Dr. $fullname_sessx View $patient_name Diagnosis Result/Drug Prescription";
$user_action="View Patients data";
$actor=$fullname_sessx;




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



if($patient_name ==  $fullname_sessx){



$msgv ="$patient_name View her Diagnosis/Drug Prescription Data";
$user_action="View Her Diagnosis Data";
$actor=$fullname_sessx;



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