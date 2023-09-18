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


<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='dashboard.php' title='Dashboard'>Dashboard</a></button>

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

<br>




<style>
.report_cssx{
background:#ddd;
padding:10px;
height:70px;
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



<?php
include('./backend/data6rst.php');


$result = $db->prepare("SELECT * FROM appointments where pangea_userid=:pangea_userid");
$result->execute(array(':pangea_userid'  =>$userid_sess));
$rows = $result->fetch();
$counting_result = $result->rowCount();




$resultc = $db->prepare("SELECT * FROM appointments where pangea_userid=:pangea_userid and status='Open' ");
$resultc->execute(array(':pangea_userid'  =>$userid_sess));
$rowsc = $resultc->fetch();
$counting_open = $resultc->rowCount();




$resultc = $db->prepare("SELECT * FROM appointments where pangea_userid=:pangea_userid and status='Closed' ");
$resultc->execute(array(':pangea_userid'  =>$userid_sess));
$rowsc = $resultc->fetch();
$counting_closedx = $resultc->rowCount();
?>
<div class='row'>

<div class='col-sm-4 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_result; ?>) </b><br>
Total Medical Appointments Booked So far.

</div>


<div class='col-sm-4 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_open; ?>)  </b><br>
Total Medical Appointments Awaiting Doctors Approval.



</div>

<div class='col-sm-4 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_closedx; ?>) </b><br>
Total Medical Appointments Completed in Life Time

</div>


</div><br>








<!--chart START-->



<style>
/*
body {
    width: 660px;
    margin: 0 auto;
}
*/
</style>


<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>


<script type="text/javascript">  

google.charts.load('current', {'packages':['corechart']});

google.charts.setOnLoadCallback(column_chart);
//google.charts.setOnLoadCallback(line_chart);
function column_chart() {

$('#loader1').fadeIn(400).html('<div style="background:#ddd;color:black;padding:10p"> <img src="loader.gif"> &nbsp; &nbsp; &nbsp;Please Wait, Statistics is being Loaded.</div>');

var res = $.ajax({
url: './backend/chartx.php',
dataType:"json",
async: false,
success: function(res)
{

  var options = {'title':'Appointments Data Over Time', 'width':800, 'height':400,
 series: {
            0: { color: 'purple' },
            1: { color: 'navy' },
          
          }
};


var data = new google.visualization.arrayToDataTable(res);
var chart = new google.visualization.ColumnChart(document.getElementById('columnchart_data'));
chart.draw(data, options);
$('#loader1').hide();

}
}).responseText;
}



google.charts.setOnLoadCallback(line_chart);
function line_chart() {


$('#loader2').fadeIn(400).html('<div style="background:#ddd;color:black;padding:10px;"><img src="loader.gif"> &nbsp; &nbsp; &nbsp;Please Wait, Statistics is being Loaded</div>');

var res1 = $.ajax({
url: './backend/chartx.php',
dataType:"json",
async: false,
success: function(res1)
{

  var options = {'title':'Appointments Data Over Time', 'width':800, 'height':400,
 series: {
            0: { color: '#800000' },
            1: { color: 'orange' },
          
          }
};


var data = new google.visualization.arrayToDataTable(res1);
var chart = new google.visualization.BarChart(document.getElementById('areachart_data'));
chart.draw(data, options);
$('#loader2').hide();

}
}).responseText;
}





google.charts.setOnLoadCallback(pie_chart);
function pie_chart() {


$('#loader3').fadeIn(400).html('<div style="background:#ddd;color:black;padding:10px;"><img src="loader.gif"> &nbsp; &nbsp; &nbsp;Please Wait,  Statistics is being Loaded</div>');

var res2 = $.ajax({
url: './backend/chartx.php',
dataType:"json",
async: false,
success: function(res2)
{

  var options = {'title':'Appointments Data Over Time', 'width':800, 'height':400,
 series: {
            0: { color: '#800000' },
            1: { color: 'orange' },
          
          }
};


var data = new google.visualization.arrayToDataTable(res2);
var chart = new google.visualization.PieChart(document.getElementById('piechart_data'));
chart.draw(data, options);
$('#loader3').hide();

}
}).responseText;
}






</script>


<br><br>

<div id="loader1"></div>
    <div id="areachart_data"></div>

<div id="loader2"></div>
    <div id="columnchart_data"></div>



<div id="loader3"></div>
    <div id="piechart_data"></div>



    </div>



<div id="loader" class='res_center_css'></div>



<!--chart ends-->















</body>
</html>

