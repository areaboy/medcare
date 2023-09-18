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




<script>

function imagePreview(e) 
{
 var readImage = new FileReader();
 readImage.onload = function()
 {
  var displayImage = document.getElementById('imageupload_preview');
  displayImage.src = readImage.result;
 }
 readImage.readAsDataURL(e.target.files[0]);
}


            $(function () {
                $('#save_btn').click(function () {
					
                    var title = $('#title').val();
                    var file_fname = $('#file_content').val();
                  
// start if validate
if(file_fname==""){
alert('please Select File to Upload');
}


else if(title==""){
alert('please Enter File Title Name');
}

else{

var fname=  $('#file_content').val();
var ext = fname.split('.').pop();
//alert(ext);

// add double quotes around the variables
var fileExtention_quotes = ext;
fileExtention_quotes = "'"+fileExtention_quotes+"'";

 var allowedtypes = ["PNG", "png", "gif", "GIF", "jpeg", "JPEG", "BMP", "bmp","JPG","jpg"];
    if(allowedtypes.indexOf(ext) !== -1){
//alert('Good this is a valid Image');
}else{
alert("Please Upload a Valid image. Only Images Files are allowed");
return false;
    }


          var form_data = new FormData();
          form_data.append('file_content', $('#file_content')[0].files[0]);
          form_data.append('file_fname', file_fname);
          form_data.append('title', title);
        
                    $('.upload_progress').css('width', '0');

                    $('#loader').fadeIn(400).html('<br><div class="well" style="color:black"><img src="ajax-loader.gif">&nbsp;Please Wait, Medical File is being Uploaded and Scaned by Pangea....</div>');
                    $.ajax({
                        url: 'file_scan_upload.php',
                        data: form_data,
                        processData: false,
                        contentType: false,
                        ache: false,
                        type: 'POST',
                        xhr: function () {
                      //var xhr = new window.XMLHttpRequest();
                            var xhr = $.ajaxSettings.xhr();
                            xhr.upload.addEventListener("progress", function (event) {
                                var upload_percent = 0;
                                var upload_position = event.loaded;
                                var upload_total  = event.total;

                                if (event.lengthComputable) {
                                    var upload_percent = upload_position / upload_total;
                                    upload_percent = parseInt(upload_percent * 100);
                                  //upload_percent = Math.ceil(upload_position / upload_total * 100);
                                    $('.upload_progress').css('width', upload_percent + '%');
                                    $('.upload_progress').text(upload_percent + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function (msg) {
				$('#loader').hide();
				$('#result_data').html(msg);
				


//strip all html elemnts using jquery
var html_stripped = jQuery(msg).text();
//alert(html_stripped);

//check occurrence of word (Successful) from backend output already html stripped.
var Frombackend = html_stripped;
var bcount = (Frombackend.match(/Successful/g) || []).length;
//alert(bcount);

if(bcount > 0){
$('#file_fname').val('');
$('#title').val('');
}




                        }
                    });
} // end if validate




                });
            });



</script>
<style>
.upload_progress{
padding:10px;
background:green;
color:white;
cursor:pointer;
min-width:30px;
}

#imageupload_preview
{
max-height:200px;
max-width:200px;
}
</style>

<div class='well'>

<center><h3> Attach, Scan and Send Medical Files to Doctors/Medical Teams(Pangea File Intel)</h3></center><br>

Easily Upload and Scan your Medical Files against <b> malware, ransomware, trojan horses, spyware, adware</b> before sharing to your Doctors/Medical Teams.
File scanning is secured and protected by <b>Pangea File Intel Services</b>

<div class='row'>
<div class='col-sm-1'></div>


<div class='col-sm-10'>

<div class="form-group">
<label style="">Select Scanned Medical File: </label>
<input style="background:#c1c1c1;" class="col-sm-12 form-control" type="file" id="file_content" name="file_content" accept="image/*" onchange="imagePreview(event)" />
 <img id="imageupload_preview"/>
</div><br>


<br>


 <div class="form-group">
              <label> Enter File Title: </label>
              <input type="text" class="col-sm-12 form-control" id="title" name="title" placeholder="Enter File Title">
            </div>




 <div class="form-group">
                            <div class="upload_progress" style="width:0%">0%</div>

                        <div id="loaderx"></div>
						<div id="loader"></div>
                        <div id="result_data"></div>
                    </div>

                    <input type="button" id="save_btn" class="pull-right btn btn-primary" value="Upload & Scan File by Pangea File Intel" />


<br><br>




<script>

$(document).ready(function(){

var d = 'ok';

$('#loader_list_d').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Fetching Patients Scanned Medical Files.</div>');
var datasend = {d:d};
$.ajax({
			
			type:'POST',
			url:'./backend/list_scan_file.php',
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

