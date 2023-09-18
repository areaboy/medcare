<?php
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

//$url_check = 'http://113.235.101.11:54384';


$url_check = strip_tags($_POST['siteurl']);

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

if($verdict =='benign'){

echo "<div class='well'>
<h3 style='color:green'>This Site is Good and not Malicious</h3>
<b>Verified URL:</b> $url_check<br>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>
                            
        
</div><br>";
}

if($verdict =='suspicious'){

echo "<div class='well'>
<h3  style='color:red'>This Site is Associated with actions that are malicious</h3>
<b>Verified URL:</b> $url_check<br>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>
                            
        
</div><br>";
}


if($verdict =='malicious'){

echo "<div class='well'>
<h3  style='color:red'>This Site is Malicious</h3>
<b>Verified URL:</b> $url_check<br>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>
                            
        
</div><br>";
}



if($verdict =='unknown'){

echo "<div class='well'>
<h3  style='color:purple'>This Site Data is Unknown</h3>
<b>Verified URL:</b> $url_check<br>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>
                            
        
</div><br>";
}




}





}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}




?>