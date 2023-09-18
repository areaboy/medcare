<?php

include('settings.php');

if($embargo_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Embargo INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();

}

$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);

$data_param= '{"ip":"'.$ipaddress.'"}';
$url ="https://embargo.aws.us.pangea.cloud/v1/ip/check";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $embargo_intel_accesstoken"));  
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

$timer1 = time();


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
$count = $json['result']['count'];
 $embargoed_country_name = $json['result']['sanctions'][0]['embargoed_country_name'];



if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 User IP Embargo Checking Failed. Try Again.  Error: $summary  </div><br>";
exit();

}




if($request_id !='' && $status =='Success'){




//echo "<div class='successx'>User IP succesfully Checked against known sanction and trade embargo lists</div><br>";


if($count > 0){
echo "<script>alert('Access Denied: Your IP Address and country ($embargoed_country_name) found in 1 embargo list(s)'); 
 window.location='./backend/pangea_embargo_access_denied.php?id=Access Denied: Your IP Address and country <b>($embargoed_country_name)</b> found in 1 embargo list(s)';
</script>";
exit();
}
else{

$embargo_pass ="<div class='successx'>Your IP Country Embargo Checking Passed (ok.)</div><br>";

}



}




?>