



<?php


ini_set('max_execution_time', 300); 
// temporarly extend time limit
set_time_limit(300);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

//error_reporting(0);
$email = strip_tags($_POST['email']);
$password = strip_tags($_POST['password']);
$first_name = strip_tags($_POST['first_name']);
$last_name = strip_tags($_POST['last_name']);
$phone_number = strip_tags($_POST['phone_number']);
$statusx = strip_tags($_POST['status']);
$profession = strip_tags($_POST['profession']);

$question = strip_tags($_POST['question']);
$answer = strip_tags($_POST['answer']);


if($email ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Email to Check Cannot be Empty</div><br>";
exit();
}


$em= filter_var($email, FILTER_VALIDATE_EMAIL);
if (!$em){
echo "<div class='alert alert-danger' id='err'>Email Address is Invalid</div>";
exit();
}


include('settings.php');

if($user_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea USER INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}




 $data_param= '{
  "email": "'.$email.'",
  "provider": "spycloud"
}';



$url ="https://user-intel.aws.us.pangea.cloud/v1/user/breached";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $user_intel_accesstoken"));  
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
$found_in_breach = $json['result']['data']['found_in_breach'];
$breach_count = $json['result']['data']['breach_count'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];


if($request_id !='' && $status != 'Success'){
echo "<div class='dangerx'>
 Verification/Checking Failed. Try Again.  Error: $summary  </div><br>";
exit();

}






if($request_id !='' && $status == 'Success' ){
echo "<div class='successx'>Step 1.) Pangea Email Breaches Verification/Checking Successful</div><br>";

if( $breach_count == 0){

echo "<div style='background:green;color:white;padding:10px;border:none;'> 
 <b>Verified Email:</b> $email<br>

                        <b>Found in Breached:</b> $found_in_breach <br>
                         <b>Breached Count:</b> $breach_count<br>
                         <b>Summary:</b> $summary. This Email is good to be used<br>
                           <b>Status:</b> $status<br>
                            
        
</div><br>";



echo "


<script>
$(document).ready(function(){


var password  =         '$password';
var email  =            '$email';
var first_name  =         '$first_name';
var last_name  =         '$last_name';
var phone_number  =         '$phone_number';
var status  =           '$statusx';
var profession  =       '$profession';
var question  =       '$question';
var answer  =       '$answer';


if(password==''){
alert('password Cannot be Empty');

}

else{

$('#loader_r').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Please Wait! .Checking If  Your Phone Number has been Breached using Pangea Security...</div>');
var datasend = {question:question, answer:answer, first_name:first_name, last_name:last_name,phone_number:phone_number,password:password, email:email, status:status, profession:profession};


$.ajax({
			
			type:'POST',
			url:'./backend/check_breached_phoneno_signup.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_r').hide();
				//$('#result_r').fadeIn('slow').prepend(msg);
$('#result_r').html(msg);
$('#alertdata_r').delay(5000).fadeOut('slow');
$('.alertdata_r').delay(5000).fadeOut('slow');


			
			}
			
		});
		
		}
		
	});
					





</script>

<div id='loader_r'></div>
<div id='result_r'></div><br>


";








exit();

}




if( $breach_count > 0){

echo "<div style='background:red;color:white;padding:10px;border:none;'> 
 <b>Verified Email:</b> $email<br>

                        <b>Found in Breached:</b> $found_in_breach<br>
                         <b>Breached Count:</b> $breach_count<br>
                         <b>Summary:</b> $summary.  Try New Email Address..<br>
                           <b>Status:</b> $status<br>
                            
        
</div><br>";
exit();

}





}






}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


?>