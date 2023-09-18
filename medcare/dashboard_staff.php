<?php
error_reporting(0);
session_start();
include ('authenticate.php');
include ('./backend/settings.php');

$userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));
$fullname_sess =  htmlentities(htmlentities($_SESSION['fullname'], ENT_QUOTES, "UTF-8"));
$token_sess =   htmlentities(htmlentities($_SESSION['token'], ENT_QUOTES, "UTF-8"));
$email_sess =  htmlentities(htmlentities($_SESSION['email'], ENT_QUOTES, "UTF-8"));
$profession = strip_tags($_SESSION['profession']);
$role = strip_tags($_SESSION['role']);



?>

<!DOCTYPE html>
<html lang="en">

<head>
 
<title>Welcome <?php echo htmlentities(htmlentities($fullname, ENT_QUOTES, "UTF-8")); ?> to Medi-Care Appointment Booking System </title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="landing page, website design" />
  <script src="scripts/jquery.min.js"></script>
  <script src="scripts/bootstrap.min.js"></script>
<link type="text/css" rel="stylesheet" href="scripts/bootstrap.min.css">

<script src="scripts/app_script.js"></script>
<link type="text/css" rel="stylesheet" href="scripts/storex.css">

  <script src="scripts/jquery.dataTables.min.js"></script>
  <script src="scripts/dataTables.bootstrap.min.js"></script>  
  <link rel="stylesheet" href="scripts/dataTables.bootstrap.min.css" />
<script src="scripts/moment.js"></script>
	<script src="scripts/livestamp.js"></script>

</head>
<body>
    <div>
        



<!-- start column nav-->


<div class="text-center">
<nav class="navbar navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navgator">
        <span class="navbar-header-collapse-color icon-bar"></span>
        <span class="navbar-header-collapse-color icon-bar"></span>
        <span class="navbar-header-collapse-color icon-bar"></span> 
        <span class="navbar-header-collapse-color icon-bar"></span>                       
      </button>
     
<li class="navbar-brand home_click imagelogo_li_remove" ><img title='logo' alt='logo' class="img-rounded imagelogo_data" src="logo.png"></li>
    </div>
    <div class="collapse navbar-collapse" id="navgator">


      <ul class="nav navbar-nav navbar-right">


<li style='' class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='statistics_appointments.php' title='Appointments Statistics'>Appointments Statistics</a></button>

</li>



<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='logout.php' title='Logout'>Logout</a></button>

</li>

      



      </ul>




    </div>
  </div>



</nav>


    </div><br /><br />

<!-- end column nav-->




<style>
.report_cssx2{
background:#ddd;
padding:10px;
height:150px;
border:none;
color:black;
border-radius:20%;
font-size:16px;
text-align:center;


}


.report_cssx2:hover{
background:orange;
color:black;

}


</style>



<h3>Welcome  <b><?php echo $fullname_sess; ?></b></h3>

<br>


<?php
include('./backend/data6rst.php');

$resultc = $db->prepare("SELECT * FROM appointments");
$resultc->execute(array());
$rowsc = $resultc->fetch();
$counting_appointments = $resultc->rowCount();


$resultcf = $db->prepare("SELECT * FROM file_scan");
$resultcf->execute(array());
$rowscf = $resultcf->fetch();
$counting_file_scan = $resultcf->rowCount();


?>



<div style='width:100vw; height: 100vh;  min-height:600px;'>
 

<div class='row'>

<div class='col-sm-12 well'>

<div class='col-sm-6'>
<h3>Logged In Medical Staff Info</h3>
<b>Pangea UserId: </b><?php echo htmlentities(htmlentities($userid_sess, ENT_QUOTES, "UTF-8")); ?> <br>
<b>Name: </b><?php echo htmlentities(htmlentities($fullname_sess, ENT_QUOTES, "UTF-8")); ?> <br>
<b>Status: </b><?php echo htmlentities(htmlentities($role, ENT_QUOTES, "UTF-8")); ?> <br>
<b>Profession: </b><?php echo htmlentities(htmlentities($profession, ENT_QUOTES, "UTF-8")); ?> <br>
</div>



<div class='col-sm-6 report_cssx2'>
<b style='font-size:20px'>
(<?php echo $counting_file_scan; ?>) </b><br>
Total Patients Medical Files Scanned & Shared
<br>
<img style='height:40px;width:40px;max-height:40px;max-width:40px;' class='img-rounded' src='scan.png'>
<br>
<a style='background:purple;color:white;padding:8px;border:none' href="file_scan_all.php" title="Manage Patients Files Scanned by 
Pangea File Intel">Manage Patients Files Scanned by Pangea File Intel</a>

</div>



</div></div>



<style>
.report_cssx{
background:#ddd;
padding:10px;
height:250px;
border:none;
color:black;
border-radius:20%;
font-size:16px;
text-align:center;


}


.report_cssx:hover{
background:orange;
color:black;

}


</style>




<script>

$(document).ready(function(){

var patients_count = 'ok';

$('#loader_list_p').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Listing Patients Count from Pangea Users List API.</div>');
var datasend = {patients_count:patients_count};
$.ajax({
			
			type:'POST',
			url:'./backend/list_users_patients_counts.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_list_p').hide();
				//$('#result_list_p').fadeIn('slow').prepend(msg);
$('#result_list_p').html(msg);
$('#alertdata_list_p').delay(7000).fadeOut('slow');


			
			}
			
		});
});



$(document).ready(function(){

var doctors_count = 'ok';

$('#loader_list_d').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Listing Medical Staff Count from Pangea Users List API.</div>');
var datasend = {doctors_count:doctors_count};
$.ajax({
			
			type:'POST',
			url:'./backend/list_users_doctors_counts.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_list_d').hide();
				//$('#result_list_d').fadeIn('slow').prepend(msg);
$('#result_list_d').html(msg);
$('#alertdata_list_d').delay(7000).fadeOut('slow');


			
			}
			
		});
});







$(document).ready(function(){

var audit = 'ok';

$('#loader_list_ad').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Listing Audit Log Count.</div>');
var datasend = {audit:audit};
$.ajax({
			
			type:'POST',
			url:'./backend/list_audit_log_counts.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_list_ad').hide();
				//$('#result_list_ad').fadeIn('slow').prepend(msg);
$('#result_list_ad').html(msg);
$('#alertdata_list_ad').delay(7000).fadeOut('slow');


			
			}
			
		});
});

</script>




<div class='row'>

<div class='col-sm-3 report_cssx'>
<span id="loader_list_d"></span>
<b style='font-size:20px'>
(<span id="result_list_d"></span>) </b><br>
Total Registered Doctors/Medical Staff.
<br>
<img style='height:100px;width:100px;max-height:100px;max-width:100px;' class='img-rounded' src='home1.png'>
<br><br>
<a style='background:purple;color:white;padding:8px;border:none' href="manage_medical_staff.php" title="Manage Doctors/Medical Staff">Manage Doctors/Medical Staff</a>
</div>


<div class='col-sm-3 report_cssx'>
<span id="loader_list_p"></span>
<b style='font-size:20px'>
(<span id="result_list_p"></span>)  </b><br>
Total Registered Patients.
<br>
<img style='height:100px;width:100px;max-height:100px;max-width:100px;' class='img-rounded' src='patients.png'>
<br><br>
<a style='background:purple;color:white;padding:8px;border:none' href="manage_patients.php" title="Manage Patients">Manage Patients</a>




</div>

<div class='col-sm-3 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_appointments; ?>) </b><br>
Total Registered Medical Appointments
<br>
<img style='height:100px;width:100px;max-height:100px;max-width:100px;' class='img-rounded' src='calendar.png'>
<br><br>
<a style='background:purple;color:white;padding:8px;border:none' href="manage_appointments.php" title="Manage Appointments">Manage Appointments</a>

</div>




<div class='col-sm-3 report_cssx'>
<span id="loader_list_ad"></span>
<b style='font-size:20px'>
(<span id="result_list_ad"></span>)  </b><br>
Total Audit Logs.
<br>
<img style='height:100px;width:100px;max-height:100px;max-width:100px;' class='img-rounded' src='audit.png'>
<br><br>
<a style='background:purple;color:white;padding:8px;border:none' href="manage_audit_log.php" title="Manage Audit Log">Manage Pangea Audit Log</a>






</div><br>




















</body>
</html>

