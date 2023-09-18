<?php


//error_reporting(0);
?>

<script src="jquery.min.js"></script>


<?php


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {


$email = strip_tags($_POST['email']);
$pangea_vault_id = strip_tags($_POST['pangea_vault_idx']);
$answer = strip_tags($_POST['answerx']);
$fname = strip_tags($_POST['fname']);


if($pangea_vault_id ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Pangea Vault ID to Check Cannot be Empty</div><br>";
exit();
}



if($answer ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Security Answer Cannot be Empty</div><br>";
exit();
}


include('settings.php');

if($user_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea USER INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}




// Fetching Security Question and answer from Pangea Vaults


$data_param= '{
  "id": "'.$pangea_vault_id.'"
}';



$url ="https://vault.aws.us.pangea.cloud/v1/get";

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

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ensure there is Internet Connections and Try Again</div><br>";


exit();
}



$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];
$secret = $json['result']['current_version']['secret'];


if($request_id !='' && $status !='Success' ){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Security Answer in Pangea Vaults Retrieval  Failed. Try Again.  Error: $summary</div><br>";

exit();

}





if($request_id !='' && $status =='Success'){

if($secret == $answer){

echo "<br><div class='successx'>Step 2.) Pangea Security Question Verification/Checking Successful</div><br>";

echo "<div style='background:green;color:white;padding:10px;border:none;'> 
 Hi! <b>$fname</b>, Security Answer Matched:</div><br>";




echo "
<script>
$(document).ready(function(){

$('#pa_btn').click(function(){
//$(document).on( 'click', '.pa_btn', function(){ 

var password  =         $('#passxx').val();
var email  =            '$email';



if(password==''){
alert('password Cannot be Empty');

}

else{

$('#loader_r28').fadeIn(400).html('<br><div style=color:black;background:#ddd;padding:10px;><img src=loader.gif style=font-size:20px> &nbsp;Step 3.) Please Wait! . User is being Loggin via Pangea AuthN..</div>');
var datasend = {password:password, email:email};


$.ajax({
			
			type:'POST',
			url:'./backend/authn_login_user.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){

                        $('#loader_r28').hide();
				//$('#result_r28').fadeIn('slow').prepend(msg);
$('#result_r28').html(msg);
$('#alertdata_r28').delay(5000).fadeOut('slow');
$('.alertdata_r28').delay(5000).fadeOut('slow');


			
			}
			
		});
		
		}
		
	})
					
});




</script>



 <div class='form-group'>
              <label> Enter Login Password</label>
              <input type='text' class='col-sm-12 form-control' id='passxx' name='passxx' placeholder='Enter Login Password' value=''>
            </div>
<br>


<div id='loader_r28'></div>
<div id='result_r28'></div><br>


   <input type='button' id='pa_btn' class='pull-right btn btn-primary pa_btn' value='Login Now' />


";







}else{

echo "<br><div style='background:red;color:white;padding:10px;border:none;'> 
 Security Answer Does Not Matched with Answer Saved in Pangea Vault:</div><br>";

exit();
}




}









}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}





















?>