<?php
error_reporting(0);



$id = strip_tags($_POST['id']);


if($id ==''){

//echo "<div  style='background:red;color:white;padding:10px;border:none;'>Pangea Vault ID Cannot be Empty</div><br>";
exit();
}

include('settings.php');

if($vault_intel_accesstoken ==''){

$response = ['status' => 0, 'message' => "Please Ask Admin to Set Pangea VAULT INTEL Access Token at settings.php File"];
echo json_encode($response);

exit();
}



$data_param= '{
  "id": "'.$id.'"
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

$response = ['status' => 0, 'message' => "Vaults Data Deletion Failed. Try Again.  Error: $summary "];
echo json_encode($response);

exit();

}




if($request_id !='' && $status =='Success'){

$response = ['status' => 1, 'message' => "Pangea Vaults Data Deletion SuccessFully "];
echo json_encode($response);

}





?>