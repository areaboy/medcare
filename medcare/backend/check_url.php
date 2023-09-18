<?php

//error_reporting(0);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


$url_check = strip_tags($_POST['url']);

if($url_check ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>URL Link to Check Cannot be Empty</div><br>";
exit();
}


include('settings.php');

if($url_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea URL INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}


 $data_param= '{
  "url": "'.$url_check.'",
  "provider": "crowdstrike",
  "raw": true
}';



$url ="https://url-intel.aws.us.pangea.cloud/v1/reputation";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $url_intel_accesstoken"));  
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
$score = $json['result']['data']['score'];
$verdict = $json['result']['data']['verdict'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];
//$domain = $json['result']['parameters']['domain'];
//$provider = $json['result']['parameters']['provider'];


if($request_id !='' && $status != 'Success'){
echo "<div class='dangerx'>
 Verification/Checking Failed. Try Again.  Error: $summary  </div><br>";
exit();

}








if($request_id !='' && $status == 'Success' ){
echo "<div class='successx'>Verification/Checking Successful</div><br>";
echo "<div class='well'>

<b>Verified URL:</b> $url_check<br>
<b>Verification Score:</b> $score(%)<br>

                         <b>Verdict/Malicious Intent:</b> $verdict<br>
                         <b>Summary:</b> $summary<br>
                           <b>Status:</b> $status<br>
                            
        
</div><br>";

}





?>