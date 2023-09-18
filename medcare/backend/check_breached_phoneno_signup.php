<?php


//error_reporting(0);


ini_set('max_execution_time', 300); 
// temporarly extend time limit
set_time_limit(300);
?>

<script src="jquery.min.js"></script>


<?php



if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {


//$password = strip_tags($_POST['password']);
$hash_type = 'sha1';

$email = strip_tags($_POST['email']);
$password = strip_tags($_POST['password']);
$first_name = strip_tags($_POST['first_name']);
$last_name = strip_tags($_POST['last_name']);
$phone_number = strip_tags($_POST['phone_number']);
$statusx = strip_tags($_POST['status']);
$profession = strip_tags($_POST['profession']);
$question = strip_tags($_POST['question']);
$answer = strip_tags($_POST['answer']);


if($password ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Password to Check Cannot be Empty</div><br>";
exit();
}

if($hash_type ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>PasswordHash Type Cannot be Empty</div><br>";
exit();
}



include('settings.php');

if($user_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea USER INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}



 $data_param= '{
  "phone_number": "'.$phone_number.'",
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

/*


$json = json_decode($output, true);
$found_in_breach = $json['result']['data']['found_in_breach'];
$breach_count = $json['result']['data']['breach_count'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];


if($found_in_breach ===true){

$check = "True";
}else{

$check = "False";
}


*/

$json = json_decode($output, true);
$found_in_breach = $json['result']['data']['found_in_breach'];
$breach_count = $json['result']['data']['breach_count'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];



if($found_in_breach ===true){

$check = "True";
}else{

$check = "False";
}


if($request_id !='' && $status != 'Success'){
echo "<div class='dangerx'>
 Verification/Checking Failed. Try Again.  Error: $summary  </div><br>";
exit();

}





if($request_id !='' && $status == 'Success' ){
echo "<div class='successx'>Step 2.) Pangea Phone Number Breaches Verification/Checking Successful</div><br>";




if( $breach_count == 0){

echo "<div style='background:green;color:white;padding:10px;border:none;'> 


 <b>Verified Phone Number:</b> ($phone_number)  Not Found in Breaches<br>
                        <b>Found in Breached:</b> $check<br>
                         <b>Breached Count:</b> $breach_count<br>
                         <b>Summary:</b> $summary. This Phone Number is Good to be Used<br>
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

$('#loader_r2').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Step 3.) Please Wait! .Creating and Signup User via Pangea AuthN..</div>');
var datasend = {question:question, answer:answer, first_name:first_name, last_name:last_name,phone_number:phone_number,password:password, email:email, status:status, profession:profession};


$.ajax({
			
			type:'POST',
			url:'./backend/authn_create_user.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_r2').hide();
				//$('#result_r2').fadeIn('slow').prepend(msg);
$('#result_r2').html(msg);
$('#alertdata_r2').delay(5000).fadeOut('slow');
$('.alertdata_r2').delay(5000).fadeOut('slow');


			
			}
			
		});
		
		}
		
	});
					





</script>

<div id='loader_r2'></div>
<div id='result_r2'></div><br>


";










}






if( $breach_count > 0){

echo "<div style='background:red;color:white;padding:10px;border:none;'> 


 <b>Verified Phone Number:</b> ($phone_number)  Found in Breaches<br>
                        <b>Found in Breached:</b> $check<br>
                         <b>Breached Count:</b> $breach_count<br>
                         <b>Summary:</b> $summary. Try New Phone Number<br>
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