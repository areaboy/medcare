<?php
error_reporting(0);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

include('settings.php');

if($audit_log_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea Audit Log Access Token at <b>settings.php</b> File</div><br>";
exit();

}
//$data_param= '{"event":{"message":"'.$msgv.'","action":"'.$user_action.'","actor":"'.$actor.'"}}';


$data_param= '{}';
$url ="https://audit.aws.us.pangea.cloud/v1/search";

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
$countx = $json['result']['count'];




if($request_id !='' && $status !='Success' ){
echo "<div class='dangerx'>
 Audit Log Searching Failed. Try Again.  Error: $summary  </div><br>";
exit();

}




if( $countx ==0 ){
echo "<div class='dangerx'>&nbsp;&nbsp;&nbsp;&nbsp;No Auditing Logged Yet.. </div><br>";
exit();

}


if($request_id !='' && $status =='Success'){

echo "<div class='successx'>Pangea Audit System --- ($summary)</div><br>";






echo '<div class="row"><div class="col-sm-1"></div>
<div class="col-sm-10">
<table border="0" cellspacing="2" cellpadding="2" class="table table-striped_no table-bordered table-hover"> 
      <tr> 
<th> <font face="Arial">Logged Message</font> </th> 
          <th> <font face="Arial">Actor</font> </th> 
          <th> <font face="Arial">Action</font> </th> 
          <th> <font face="Arial">Time</font> </th>  


      </tr>';



foreach($json['result']['events'] as $row){

$received_at = $row['envelope']['received_at'];
$event = $row['envelope']['event']['message'];
$action = $row['envelope']['event']['action'];
$actor = $row['envelope']['event']['actor'];


// convert to reable time format
$timestamp = strtotime($received_at);
$date = new DateTime();
$date->setTimestamp($timestamp);
$date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
$received_atx =  $date->format('d/m/Y H:i:s') . "\n";





 echo "<tr class='rec_$received_at' > 

<td>

<span style='color:purple'>$event</span>
</td>

<td>$actor</td> 
                
                  
                  <td>$action</td>



                  <td>$received_atx</td> 


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