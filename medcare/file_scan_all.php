﻿<?php
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


<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='dashboard_staff.php' title='Dashboard'>Dashboard</a></button>

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





<br><br><br>


<h3>Welcome  <b><?php echo $fullname_sess; ?></b></h3>






<div class='well'>

<center><h3> Manage Patients Medical Files Shared and Scanned by (Pangea File Intel)</h3></center><br>

The Below listed Medical Files  are scanned by <b>Pangea File Intel Services</b> against <b> malware, ransomware, trojan horses, spyware, adware</b> before 
being Shared....<br><br>


<div class='row'>
<div class='col-sm-1'></div>


<div class='col-sm-10'>





<script>

$(document).ready(function(){

var d = 'ok';

$('#loader_list_d').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Fetching Patients Scanned Medical Files.</div>');
var datasend = {d:d};
$.ajax({
			
			type:'POST',
			url:'./backend/list_scan_file_all.php',
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


</script>

<span id="result_list_d"></span>
<span id="loader_list_d"></span>





</div>

<div class='col-sm-1'></div>
</div>








</div>


















</body>
</html>

