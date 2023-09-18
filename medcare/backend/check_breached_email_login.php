<?php


//error_reporting(0);
?>



<?php


ini_set('max_execution_time', 300); 
// temporarly extend time limit
set_time_limit(300);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$email = strip_tags($_POST['email']);
//$password = strip_tags($_POST['password']);



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




// Check if Email Exist and Get User Details


 $data_param= '{
  "email": "'.$email.'"
}';



$url ="https://authn.aws.us.pangea.cloud/v1/user/profile/get";

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

echo "<div class='dangerx'>
 Please Ensure there is Internet Connections and Try Again</div><br>";
exit();
}


$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];
$pangea_uid = $json['result']['id'];
$pangea_vault_id = $json['result']['profile']['pangea_vault_id'];
$secret_question = $json['result']['profile']['secret_question'];
$fname = $json['result']['profile']['fullname'];

if($request_id !='' && $status != 'Success'){
echo "<div class='dangerx'>
 Getting User Details Failed. Try Again.  Error: $summary  </div><br>";
exit();

}











// Checkif Email is Found in Breaches


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

                        <b>Email Not Found in Breaches:</b> <br>
                         <b>Breached Count:</b> $breach_count<br>
                         <b>Summary:</b> $summary. This Email is ok and Secured By Pangea<br>
                           <b>Status:</b> $status<br>
                            
        
</div><br>";



echo "


<script>
$(document).ready(function(){
$('#ve_btn').click(function(){
//$(document).on( 'click', '.ve_btn', function(){ 

var pangea_vault_idx  = '$pangea_vault_id';
var email = '$email';
var answerx = $('#answerx').val();
var fname   =  '$fname';


if(answerx==''){
alert('Security Answer Cannot be Empty');

}

else{

$('#loader_r7').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Please Wait! .Verifying Your Security Answer in Pangea Vaults...</div>');
var datasend = {pangea_vault_idx:pangea_vault_idx, email:email, answerx:answerx, fname:fname};


$.ajax({
			
			type:'POST',
			url:'./backend/check_security_answer_login.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_r7').hide();
				//$('#result_r7').fadeIn('slow').prepend(msg);
$('#result_r7').html(msg);
$('#alertdata_r7').delay(5000).fadeOut('slow');
$('.alertdata_r7').delay(5000).fadeOut('slow');


			
			}
			
		});
		
		}
		
	})
					
	});






$(document).ready(function(){
$('#emx_btn').click(function(){
//$(document).on( 'click', '.emx_btn', function(){ 

var pangea_vault_idx  = '$pangea_vault_id';
var email = '$email';
var fname   =  '$fname';
var secret_question = '$secret_question';

if(pangea_vault_idx==''){
alert('Pangea Vault Id Cannot be Empty');

}

else{

$('#loader_emx').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Please Wait! .Fetching Security Answer from Pangea Vaults and sending to your Email...</div>');
var datasend = {pangea_vault_idx:pangea_vault_idx, email:email,fname:fname, secret_question:secret_question};


$.ajax({
			
			type:'POST',
			url:'$site_urlx/backend/security_answer_forgot.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_emx').hide();
//$('#result_emx').fadeIn('slow').prepend(msg);
$('#result_emx').html(msg);
setTimeout(function(){ $('#result_emx').html(''); }, 8000);	



			
			}
			
		});
		
		}
		
	})
					
	});



</script>




<br>
<div class='well'>



 <div class='form-group'>
<span style='color:purple'>Your Security Question: <b>$secret_question</b></span><br>
              <label> Enter Security Answer </label>
              <input type='text' class='col-sm-12 form-control' id='answerx' name='answerx' placeholder='Enter Security Answer'>
            </div>
<br>




<div id='loader_r7'></div>

   <input type='button' id='ve_btn' class='pull-right btn btn-primary ve_btn' value='Verify Security Answer' /><br><br>

<div id='loader_emx'></div>
<div id='result_emx'></div>
<input type='button' id='emx_btn' class='pull-right btn btn-warning emx_btn' value='I Forgot My Security Answer' /><br><br>



<div id='result_r7'></div><br>


</div>


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