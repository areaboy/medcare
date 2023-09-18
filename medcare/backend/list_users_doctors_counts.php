<?php
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {


// Get User List for Patients


include('settings.php');

if($authn_intel_accesstoken ==''){
echo "<span  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea AuthN INTEL Access Token at <b>settings.php</b> File</span>";
exit();
}



$data_param='{"filter":{"profile_role":"Staff"}}';

$url ="https://authn.aws.us.pangea.cloud/v1/user/list";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $authn_intel_accesstoken"));  
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

echo "<span class='dangerx'>
 Please Ensure there is Internet Connections and Try Again</span><br>";
exit();
}



$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];

 $count = $json['result']['count'];




if($request_id !='' && $status !='Success' ){
echo "<span class='dangerx'>
 Listing Doctors/Medical Staff Count from Pangea Failed. Try Again.  Error: $summary  </span><br>";
exit();

}

/*
if($res ==[] ){
echo "<span class='dangerx'>&nbsp;&nbsp;&nbsp;&nbsp;No Doctors/Medical Staff Registered Yet on Pangea Users List </span><br>";
exit();

}
*/



if($request_id !='' && $status =='Success'){

echo "$count";

}





}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}
