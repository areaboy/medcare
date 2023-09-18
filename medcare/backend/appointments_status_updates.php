<?php
error_reporting(0);

session_start();
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));

include('settings.php');
include('data6rst.php');

if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$id = strip_tags($_POST['id']);


$res = $db->prepare('select * from appointments where id=:id');
$res->execute(array(':id' =>$id));
$rowb = $res->fetch();
$fullname_ok=htmlentities($rowb['fullname'], ENT_QUOTES, "UTF-8");




// Auditing by Pangea starts

$msgv ="Dr. $fullname_sess Approves $fullname_ok(Patients) Appointments";
$user_action="Approves Patients Appointments";
$actor=$fullname_sess;



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





if($id != ''){
// query table posts to get data
$result = $db->prepare('UPDATE appointments set status =:status WHERE id =:id');
$result->execute(array(':status' => 'Closed', ':id' => $id));

}
$return_arr = array("status"=>'Approved',"message"=>'1');
echo json_encode($return_arr);


}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}

?>


