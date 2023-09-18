<?php

include('settings.php');

if($ip_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea IP INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();

}

$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);

$data_param= '{"provider":"digitalelement","ip":"'.$ipaddress.'"}';
$url ="https://ip-intel.aws.us.pangea.cloud/v1/vpn";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $ip_intel_accesstoken"));  
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
$check = $json['result']['data']['is_vpn'];






if($request_id !='' && $status =='Success'){




//echo "<div class='successx'>User IP succesfully Checked For VPN</div><br>";


if($check ===true){
echo "<script>alert('Access Denied: Your IP Address Found in VPN'); 
 window.location='./backend/pangea_vpn_access_denied.php?id=Access Denied: Your IP Address Found in VPN';
</script>";
exit();
}
else{

$vpn_pass ="<div class='successx'>Your IP Not Found in VPN hence Passed (Ok.)</div><br>";

}


/*
if($check ===false){
echo "<script>alert('Access Denied: Your IP Address not Found in VPN'); 
 window.location='./backend/pangea_vpn_access_denied.php?id=Access Denied: Your IP Address not Found in VPN';
</script>";
exit();
}
*/




}




?>