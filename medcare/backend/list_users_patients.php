<?php
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {


// Get User List for Patients


include('settings.php');

if($authn_intel_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea AuthN INTEL Access Token at <b>settings.php</b> File</div><br>";
exit();
}



$data_param='{"filter":{"profile_role":"Patients"}}';

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

echo "<div class='dangerx'>
 Please Ensure there is Internet Connections and Try Again</div><br>";
exit();
}



$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];

 $count = $json['result']['count'];




if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 Listing Users/Patients Count from Pangea Failed. Try Again.  Error: $summary  </div><br>";
exit();

}


if( $count ==0 ){
echo "<div class='dangerx'>&nbsp;&nbsp;&nbsp;&nbsp;No Patients Registered Yet on Pangea Users List </div><br>";
exit();

}




if($request_id !='' && $status =='Success'){







echo '<div class="row"><div class="col-sm-1"></div>
<div class="col-sm-10">
<table border="0" cellspacing="2" cellpadding="2" class="table table-striped_no table-bordered table-hover"> 
      <tr> 
<th> <font face="Arial">Pangea_Userid</font> </th> 
          <th> <font face="Arial">Fullname</font> </th> 
          <th> <font face="Arial">Profession</font> </th> 
          <th> <font face="Arial">Last Login At</font> </th> 
<th> <font face="Arial">Login Count</font> </th> 
<th> <font face="Arial">Action</font> </th> 


      </tr>';

foreach($json['result']['users'] as $row){

$email = $row['email'];


$microemail = substr($email, 0, 5)."xxxxxxxxxxxxx";

$uid = $row['id'];
$id = $uid;
$fullname = $row['profile']['fullname'];
$profession = $row['profile']['profession'];


$last_login_at= $row['last_login_at'];
$created_at = $row['created_at'];
$login_count = $row['login_count'];


// convert to reable time format
$timestamp = strtotime($last_login_at);
$date = new DateTime();
$date->setTimestamp($timestamp);
$date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
$last_login_atx =  $date->format('d/m/Y H:i:s') . "\n";





 echo "<tr class='rec_$id' > 
<td>$uid
<br><br>
<a style='background:purple;color:white;padding:4px;border:none;font-size:12px;' 
href='manage_patients_profile.php?pangea_userid=$uid' title='Manage Patients From Medical Profile'>Manage Patients From Medical Profile</a>



</td> 
<td>

<b>$fullname</b>
</td>


                
                  
                  <td>$profession</td>



                  <td>$last_login_atx</td> 

                  <td>$login_count</td>  
                 
 <td>
   <div class='loader-delete_$id'></div>
   <div class='result-delete_$id'></div>
<button class='btn btn-danger delete_btn' data-id='$id'  title='Delete User' disabled>Delete User</button>


</td>
              </tr>";





}

echo "</div><div class='col-sm-1'></div></div>";


}


}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


















?>