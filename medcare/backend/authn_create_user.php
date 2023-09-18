<?php
error_reporting(0);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$email = strip_tags($_POST['email']);
$password = trim($_POST['password']);
$first_name = strip_tags($_POST['first_name']);
$last_name = strip_tags($_POST['last_name']);
$phone_number = strip_tags($_POST['phone_number']);
$stx= strip_tags($_POST['status']);
$profession = strip_tags($_POST['profession']);
$question = strip_tags($_POST['question']);
$answer = strip_tags($_POST['answer']);

 $pass = $password;



include('data6rst.php');
include('settings.php');

/*
if($password ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Password Cannot be Empty nnnn</div><br>";
exit();
}

if($email ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Email Address Cannot be Empty</div><br>";
exit();
}


$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div  style='background:red;color:white;padding:10px;border:none;' id='err'>Email Address is Invalid</div>";
exit();
}
*/


if($stx ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Status Cannot be Empty</div><br>";
exit();
}


if($profession ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Profession Cannot be Empty</div><br>";
exit();
}





if($first_name ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>First_name Cannot be Empty</div><br>";
exit();
}

include('settings.php');

if($authn_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea AuthN INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}



$fullname ="$last_name $first_name";



// Store Secret Questions and answer in Pangea Vault

$tmx = time();
$titlex = "Login Secret by $fullname --$tmx";
$data_param= '{
  "name": "'.$titlex.'",
"folder":"/secret_question",
"metadata":{"created_by": "'.$fullname.'","email":"'.$email.'","question":"'.$question.'"},
  "secret": "'.$answer.'"
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


echo "<div style='background:red;color:white;padding:6px;border:none'>Security Question Storage in Pangea Vaults Failed. Try Again.  Error: $summary <br></div>";
exit();

}



if($request_id !='' && $status =='Success'){


echo "<div style='background:green;color:white;padding:6px;border:none'>Security Question Storage in Pangea Vaults Successful <br></div>";

}







$data_param= '{
  "email": "'.$email.'",
  "authenticator": "'.$pass.'",
  "id_provider": "password",
"require_mfa":true,
"profile":{
"first_name": "'.$first_name.'",
"last_name": "'.$last_name.'",
"phone_number": "'.$phone_number.'",
"fullname": "'.$fullname.'",
"role": "'.$stx.'",
"profession": "'.$profession.'",
"pangea_vault_id": "'.$pid.'",
"secret_question": "'.$question.'"
}
}';





$url ="https://authn.aws.us.pangea.cloud/v1/user/create";

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


if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 User creation Failed. Try Again.  Error: $summary  </div><br>";
exit();

}





if($request_id !='' && $status =='Success'){




echo "<div class='successx'>User Created SuccessFully. You can Now Login</div><br>";
echo "<div class='well'>

                         <b>UserId :</b> $user_id<br>
                         <b>Summary:</b> $summary<br>
                           <b>Status:</b> $status<br>
                            
        
</div><br>";

}




// Auditing by Pangea



if($audit_log_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Audit Log Access Token at <b>settings.php</b> File</div><br>";
exit();

}

if($stx =='Staff'){

$stc_msg = "Doctor/Medical Staff $fullname successfully Registered";

}else{

$stc_msg = "Patients $fullname successfully Registered";


}



$msgv =$stc_msg;
$user_action="Signup with the Site";
$actor=$fullname;

//$data_param= '{"config_id":"pci_m2miypebe6ilja5ymts77jhllqrenmey","event":{"message":"'.$msg.'","action":"'.$user_action.'","actor":"'.$actor.'"}}';

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


echo "Reloading Page in 6 Sec....<img src='loader.gif'><br></div>";


echo "<script>
window.setTimeout(function() {
    location.reload();
}, 6000);
</script><br><br>";




}








}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}



?>