<?php
error_reporting(0);

ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);

include ('./backend/settings.php');
include('./backend/data6rst.php');

session_start();
$userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$token_sess =   htmlentities(htmlentities($_SESSION['token'], ENT_QUOTES, "UTF-8"));
$email_sess =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['profession']);
$role = strip_tags($_SESSION['role']);


if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

$file_content = strip_tags($_POST['file_fname']);
$title = strip_tags($_POST['title']);


$mt_id=rand(0000,9999);
$dt2=date("Y-m-d H:i:s");
$ipaddress = strip_tags($_SERVER['REMOTE_ADDR']);
$timer = time();
$dt2=date("Y-m-d H:i:s");
$mt = microtime(true);
$mdx = md5($mt);
$uidx = uniqid();
$userid = $uidx.$timer.$mdx;
$username = $timer;





if ($file_content == ''){
echo "<div style='background:red;color:white;padding:8px;border:none;'>Files Upload is empty</div>";
exit();
}


if ($title == ''){
echo "<div style='background:red;color:white;padding:8px;border:none;'>File Title is empty</div>";
exit();
}


$upload_path = "uploads/";


$filename_string = strip_tags($_FILES['file_content']['name']);
// thus check files extension names before major validations

$allowed_formats = array("PNG", "png", "gif", "GIF", "jpeg", "JPEG", "BMP", "bmp","JPG","jpg");
$exts = explode(".",$filename_string);
$ext = end($exts);

if (!in_array($ext, $allowed_formats)) { 
echo "<div style='background:red;color:white;padding:8px;border:none;'>File Formats not allowed. Only Images are allowed.<br></div>";
exit();
}


 //validate file names, ensures directory tranversal attack is not possible.
//thus replace and allowe filenames with alphanumeric dash and hy

//allow alphanumeric,underscore and dash

$fname_1= preg_replace("/[^\w-]/", "", $filename_string);

// add a new extension name to the uploaded files after stripping out its dots extension name
$new_extension = ".png";
$fname = $fname_1.$new_extension;


$fsize = $_FILES['file_content']['size']; 
$ftmp = $_FILES['file_content']['tmp_name'];

//give file a random names
$filecontent_name = $username.time();
//$filecontent_name = 'fred1';


if ($fsize > 30 * 1024 * 1024) { // allow file of less than 30 mb
echo "<div id='alertdata' class='alerts alert-danger'>File greater than 30mb not allowed<br></div>";
exit();
}

// Check if file already exists
if (file_exists($upload_path . $filecontent_name.'.'.$ext)) {
echo "<div style='background:red;color:white;padding:8px;border:none;'>This uploaded File <b>$filecontent_name.$ext</b> already exist<br></div>";
exit(); 
}


$allowed_types=array(
'application/json',
'application/octet-stream',
'text/plain',
'image/gif',
    'image/jpeg',
    'image/png',
'image/jpg',

'image/GIF',
    'image/JPEG',
    'image/PNG',
'image/JPG'
);



if ( ! ( in_array($_FILES["file_content"]["type"], $allowed_types) ) ) {
  echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Only Images are allowed bro..<br><br></div>";
exit();
}



//validate image using file info  method
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $_FILES['file_content']['tmp_name']);

if ( ! ( in_array($mime, $allowed_types) ) ) {
  echo "<div id='alertdata_uploadfiles' class='alerts alert-danger'>Only Images are allowed...<br></div>";
exit();
}
finfo_close($finfo);


if (move_uploaded_file($ftmp, $upload_path . $filecontent_name.'.'.$ext)) {

$final_filename =  $filecontent_name.'.'.$ext;





// Hash File for scanning using SHA1
$sha1file = sha1_file("uploads/$final_filename");


$data_param= '{"hash_type": "sha1","hash": "'.$sha1file.'"}';

$url ="https://file-intel.aws.us.pangea.cloud/v1/reputation";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, TRUE);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', "Authorization: Bearer $file_intel_accesstoken"));  
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
echo "<div style='background:red;color:white;padding:8px;border:none;'>Please Ensure there is Internet Connections and Try Again</div>";
exit();
}



$json = json_decode($output, true);
$request_id = $json['request_id'];
echo $summary = $json['summary'];
$status = $json['status'];
$verdict = $json['result']['data']['verdict'];
$score = $json['result']['data']['score'];


if($request_id !='' && $status !='Success' ){
echo "<div style='background:red;color:white;padding:8px;border:none;'> Medical  File Scanning by Pangea File Intel  Failed. Try Again.  Error: $summary</div>";
exit();

}




if($request_id !='' && $status =='Success'){

echo "<div style='background:green;color:white;padding:8px;border:none;'> Medical  File Scanning by Pangea File Intel Done Successful</div>";


if($verdict =='benign'){

echo "<div class='well'>
<h3 style='color:green'>This Medical File is Good and not Malicious</h3>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>

<br><b>Medical File Upload Secured & Protected by Pangea Security</b>
</div><br>";



$statement = $db->prepare('INSERT INTO file_scan

(pangea_userid,fullname,title,file_name,verdict,score,timing,summary)
 
                          values
(:pangea_userid,:fullname,:title,:file_name,:verdict,:score,:timing,:summary)');

$statement->execute(array( 
':pangea_userid' => $userid_sess,
':fullname' => $fullname_sess,
':title' => $title,		
':file_name' =>$final_filename,
':verdict' =>$verdict,
':score' =>$score,
':timing' => $timer,
':summary' => $summary
));


                                   


echo  "<script>alert('File Uploads and Scanning by Pangea File Intel Successful...');</script>";
echo "<div style='background:green;padding:8px;color:white;border:none;'>File Uploads and Scanning by Pangea File Intel Successful...</div>";
echo "<script>location.reload();</script>";


}

if($verdict =='suspicious'){

echo "<div class='well'>
<h3  style='color:red'>This Medical File is Associated with actions that are malicious</h3>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>

<br><b>Medical File Upload Secured & Protected by Pangea Security</b>       
</div><br>";

  // remove the file from directory
unlink('uploads/$final_filename');
}


if($verdict =='malicious'){

echo "<div class='well'>
<h3  style='color:red'>This Medical File is Malicious</h3>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>

<br><b>Medical File Upload Secured & Protected by Pangea Security</b>
                     </div><br>";

  // remove the file from directory
unlink('uploads/$final_filename');

}



if($verdict =='unknown'){

echo "<div class='well'>
<h3  style='color:purple'>This Medical File Verdict is Unknown. However, You are Protected by Pangea Security ....</h3>
<b>Verification Score:</b> $score(%)<br>
<b>Verdict/Malicious Intent:</b> $verdict<br>
<b>Summary:</b> $summary<br>
<b>Status:</b> $status<br>


<br><b>Medical File Upload Secured & Protected by Pangea Security</b>
        
</div><br>";


$statement = $db->prepare('INSERT INTO file_scan

(pangea_userid,fullname,title,file_name,verdict,score,timing,summary)
 
                          values
(:pangea_userid,:fullname,:title,:file_name,:verdict,:score,:timing,:summary)');

$statement->execute(array( 
':pangea_userid' => $userid_sess,
':fullname' => $fullname_sess,
':title' => $title,		
':file_name' =>$final_filename,
':verdict' =>$verdict,
':score' =>$score,
':timing' => $timer,
':summary' => $summary
));



echo  "<script>alert('File Uploads and Scanning by Pangea File Intel Successful...');</script>";
echo "<div style='background:green;padding:8px;color:white;border:none;'>File Uploads and Scanning by Pangea File Intel Successful...</div>";
echo "<script>location.reload();</script>";


}


}









}else{
echo "<div style='background:red;padding:8px;color:white;border:none;'>File Uploads Failed...</div>";

}




}


?>



