<?php
error_reporting(0);


$filename = "test.png";
$sha1file = sha1_file($filename);
echo $sha1file;





exit();

include('settings.php');

if($file_scan_accesstoken ==''){
echo "<div  style='background:red;color:white;padding:10px;border:none;'>Please Ask Admin to Set Pangea File Scan Access Token at <b>settings.php</b> File</div><br>";
exit();

}



$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://file-scan.aws.us.pangea.cloud/v1/scan');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$post = array(
    'request' => '{\"provider\":\"crowdstrike\"};type=application/json',
    'upload' => '@' .realpath('http://localhost/medcare/backend/test.png;type=application/octet-stream')
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$headers = array();
$headers[] = "Authorization: Bearer $file_scan_accesstoken";
$headers[] = 'Content-Type: multipart/form-data';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

echo $result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);






exit();



// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, 'https://file-scan.aws.us.pangea.cloud/v1/scan');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
$post = array(
    'request' => '{"provider":"crowdstrike"};type=application/json',
    'upload' => 'test.png;type=application/octet-stream'
);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);

$headers = array();
$headers[] = "Authorization: Bearer $file_scan_accesstoken";
//$headers[] = 'Content-Type: multipart/form-data';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

echo $result = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
}
curl_close($ch);


echo "<img src='test.png'>";


exit();






//$data_param= '{"event":{"message":"'.$msgv.'","action":"'.$user_action.'","actor":"'.$actor.'"}}';

/*
curl -sSLX POST 'https://file-scan.aws.us.pangea.cloud/v1/scan' \
-H 'Authorization: Bearer <your_token>' \
-H 'Content-Type: multipart/form-data' \
-F 'request={"provider":"crowdstrike"};type=application/json' \
-F 'upload=@test.png;type=application/octet-stream'



*/

/*
if (function_exists('curl_file_create')) { // php 5.5+
  $cFile = curl_file_create($file_name_with_full_path);
} else { // 
  $cFile = '@' . realpath($file_name_with_full_path);
}

 $cfile = curl_file_create($_FILES['file']['tmp_name'],$_FILES['file']['type'],$_FILES['file']['name']);

*/


// Create a CURLFile object
           $cfile = curl_file_create('test.png','image/png','cattle-01.jpg');

           $post = array(
               'upload' => $cfile,
               'request' => '{"provider":"crowdstrike"}'
           );


$data_param= '{"provider": "crowdstrike", "upload": "test.png"}';
$url ='https://file-scan.aws.us.pangea.cloud/v1/scan';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array( 'Content-Type: multipart/form-data', "Authorization: Bearer $file_scan_accesstoken"));  
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_param);
//curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
 echo $output = curl_exec($ch); 


$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// catch error message before closing
if (curl_errno($ch)) {
    //echo $error_msg = curl_error($ch);
}

curl_close($ch); 




/*
$cf = new CURLFile("FILE-TO-UPLOAD.EXT");
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "HTTP://SITE.COM/");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, ["upload" => $cf]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$result = curl_exec($ch); 

*/













?>