<?php
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {


session_start();
$userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$token_sess =   htmlentities(htmlentities($_SESSION['token'], ENT_QUOTES, "UTF-8"));
$email_sess =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['profession']);
$role = strip_tags($_SESSION['role']);


include('settings.php');
include('data6rst.php');



$result_verified = $db->prepare('select * from file_scan where pangea_userid=:pangea_userid');
$result_verified->execute(array(':pangea_userid' =>$userid_sess));


$norows= $result_verified->rowCount();

if($norows ==0){
echo "<div style='background:red;padding:8px;color:white;border:none;'>You Have not Share any Medical Files toyour Medical Team/Doctors Yet....</div>";
exit();
}


if($norows >0){
echo "<div style='background:green;padding:8px;color:white;border:none;'>List of Your Shared Medical Files Scanned by Pangea File Intel.</div>";

}



echo '<div class="row"><div class="col-sm-1"></div>
<div class="col-sm-10">
<table border="0" cellspacing="2" cellpadding="2" class="table table-striped_no table-bordered table-hover"> 
      <tr> 

          <th> <font face="Arial">Fullname</font> </th> 
          <th> <font face="Arial">File Title</font> </th> 
          <th> <font face="Arial">Scan Verdict</font> </th> 
<th> <font face="Arial">Score</font> </th> 
<th> <font face="Arial">Summary</font> </th> 
<th> <font face="Arial">Timing</font> </th> 

      </tr>';

while($row = $result_verified->fetch()){


$id = $row['id'];
$pangea_userid = $row['pangea_userid'];
$fullname = $row['fullname'];
$title = $row['title'];
$file_name = $row['file_name'];
$verdict = $row['verdict'];
$score = $row['score'];
$timing = $row['timing'];
$summary = $row['summary'];



 echo "<tr class='rec_$id' > 
<td>

<b>$fullname</b><br>

<a style='display:none;background:purple;color:white;padding:4px;border:none;font-size:12px;' 
href='manage_patients_profile.php?pangea_userid=$pangea_userid' title='Manage Patients From Medical Profile'>Manage Patients Profile</a>

<a target ='_blank' style='background:fuchsia;color:white;padding:4px;border:none;font-size:12px;' 
href='uploads/$file_name' title='Download Files'>Download File</a>

</td>


                
                  
                  <td>$title</td>




                  <td>$verdict</td> 
<td>$score</td>  
<td>$summary</td>  
<td><span data-livestamp='$timing'></span></td>  
                 

              </tr>";





}

echo "</div><div class='col-sm-1'></div></div>";




}
else{
echo "<div id='' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}


















?>