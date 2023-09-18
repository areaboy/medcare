<?php
error_reporting(0);

session_start();
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
$id = strip_tags($_POST['id']);
$pang_id = strip_tags($_POST['pang_id']);

include('data6rst.php');
include('settings.php');




$res = $db->prepare('select * from appointments where id=:id');
$res->execute(array(':id' =>$id));
$rowb = $res->fetch();
$fullname_ok=htmlentities($rowb['fullname'], ENT_QUOTES, "UTF-8");




// Auditing by Pangea starts

if($fullname_sess != $fullname_ok){

$msgv ="Dr. $fullname_sess Deletes $fullname_ok(Patients) Appointments Data from Pangea Vault";
$user_action="Deletes Patients Appointments";
$actor=$fullname_sess;

}


if($fullname_sess == $fullname_ok){

$msgv ="$fullname_sess(Patients) Deleted Her Appointments Data from Pangea Vault";
$user_action="Patients Deletes Appointments";
$actor=$fullname_sess;

}




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
$return_arr = array("status"=>'Please Ensure there is Internet Connections and Try Again',"message"=>'1');
echo json_encode($return_arr);

exit();
}


$json = json_decode($output, true);
$user_id = $json['result']['id'];
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];


if($request_id !='' && $status !='Success' ){
$return_arr = array("status"=>" Audit Log creation Failed. Try Again.  Error: $summary  ","message"=>'1');
echo json_encode($return_arr);
exit();

}




// Audit log ends





if($vault_intel_accesstoken ==''){

$response = ['status' => 0, 'message' => "Please Ask Admin to Set Pangea VAULT INTEL Access Token at settings.php File"];
echo json_encode($response);

exit();
}



$data_param= '{
  "id": "'.$pang_id.'"
}';



$url ="https://vault.aws.us.pangea.cloud/v1/delete";

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
$response = ['status' => 0, 'message' => "Please Ensure there is Internet Connections and Try Again"];
echo json_encode($response);

exit();
}




$json = json_decode($output, true);
$request_id = $json['request_id'];
$summary = $json['summary'];
$status = $json['status'];


if($request_id !='' && $status !='Success' ){

$response = ['status' => 0, 'message' => " Pangea Vaults Records Deletion Failed. Try Again.  Error: $summary "];
echo json_encode($response);

exit();

}




if($request_id !='' && $status =='Success'){

$del = $db->prepare('DELETE FROM appointments where id = :id');

		$del->execute(array(
			':id' => $id
    ));



$response = ['status' => 1, 'message' => "Pangea Vaults Records Deletion SuccessFully "];
echo json_encode($response);
exit();

}









}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}



?>