<?php
error_reporting(0);


$id = strip_tags($_POST['p_pangea_vault_id_value']);


if($id ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Pangea Vault ID Not Found</div><br>";
exit();
}

include('settings.php');

if($vault_intel_accesstoken ==''){


echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea VAULT INTEL Access Token at settings.php File</div><br>";

exit();
}



$data_param= '{
  "id": "'.$id.'"
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


echo "<div  style='background:red;color:white;padding:10px;border:none;'> Password Secret Vaults Retrieval  Failed. Try Again.  Error: $summary </div><br>";


exit();

}




if($request_id !='' && $status =='Success'){


echo "<div  style='background:green;color:white;padding:10px;border:none;'> Patients Medical Appointments Retrival from Pangea Vaults Successful </div><br><br>";

echo "<div  class='Well'> <b>Patients Medical Details: </b> $secret</div><br>";


}





?>