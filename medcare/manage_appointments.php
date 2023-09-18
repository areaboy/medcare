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



<div class="text-center">
<nav class="navbar navbar-fixed-top style='background:purple' ">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navgator">
        <span class="navbar-header-collapse-color icon-bar"></span>
        <span class="navbar-header-collapse-color icon-bar"></span>
        <span class="navbar-header-collapse-color icon-bar"></span> 
        <span class="navbar-header-collapse-color icon-bar"></span>                       
      </button>
     
<li class="navbar-brand home_click imagelogo_li_remove" ><img class="img-rounded imagelogo_data" src="logo.png"></li>
    </div>
    <div class="collapse navbar-collapse" id="navgator">

      <ul class="nav navbar-nav navbar-right">


<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='dashboard_staff.php' title='Dashboard'>Dashboard</a></button>

</li>


<li style='display:none;' class="navgate">

<button data-toggle="modal" data-target="#myModal_vault" class="invite_btnx btn btn-warning" title='Book Medical Appointments'>Book Medical Appointments </button>

</li>


<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='logout.php' title='Logout'>Logout</a></button>

</li>
</ul>





    </div>
  </div>


</nav>


    </div><br />
<br /><br />



<div style='width:100vw; height: 100vh;  min-height:600px;'>
 

<div class='row'>

<h4>Welcome: <?php echo $fullname_sess; ?> </h4>

<div class='col-sm-12 well'>
<h3>Patients Appointments Managements System</h3>




</div></div>





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


$result = $db->prepare("SELECT * FROM appointments");
$result->execute(array());
$rows = $result->fetch();
$counting_result = $result->rowCount();




$resultc = $db->prepare("SELECT * FROM appointments where status='Open' ");
$resultc->execute(array());
$rowsc = $resultc->fetch();
$counting_open = $resultc->rowCount();




$resultc = $db->prepare("SELECT * FROM appointments where status='Closed' ");
$resultc->execute(array());
$rowsc = $resultc->fetch();
$counting_closedx = $resultc->rowCount();
?>
<div class='row'>

<div class='col-sm-4 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_result; ?>) </b><br>
Total Medical Appointments Booked by all Patients.

</div>


<div class='col-sm-4 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_open; ?>)  </b><br>
Total Medical Appointments Awaiting Doctors Approval.



</div>

<div class='col-sm-4 report_cssx'>
<b style='font-size:20px'>
(<?php echo $counting_closedx; ?>) </b><br>
Total Medical Appointments Completed

</div>


</div><br>

<b>Search Medical Appointments by Fullname, Email, Status etc...</b><br><br>





<div class="container">
<div class="row">
<div class="col-sm-12 table-responsive">
<div class="alert_server_response"></div>
<div class="loader_x"></div>
<table id="bc" class="table table-bordered table-striped">
<thead><tr>
<th>Patients Fullname</th>

<th>Patients Medical Appointments Details<span style='color:red'> (Secured in Pangea Vaults)</span></th>
<th>Appointment Date</th>
<th>Appointment Time</th>
<th>Appointment Status</th>
<th>TimeAgo</th>
<th>Doctors Diagnosis & Drugs Prescriptions<span style='color:red'> (Secured in Pangea Vaults)</span></th>
<th>Actions</th>
</tr></thead>
</table>
</div>
</div>
</div>








<span class="alert_server_response"></span>
<span class="loader_x"></span>



<script>
$(document).ready(function(){
//$('.btn_call').click(function(){
$(document).on( 'click', '.btn_call', function(){ 



var id = $(this).data('id');
var pangea_userid = $(this).data('pangea_userid');
var services_title = $(this).data('services_title');
var email = $(this).data('email');
var fullname = $(this).data('fullname');
var pangea_vault_id = $(this).data('pangea_vault_id');
var status  = $(this).data('status');

var diagnosis  = $(this).data('diagnosis');
var medication= $(this).data('medication');

//alert(diagnosis);
//alert(medication);

$('.p_id').html(id);
$('.p_pangea_userid').html(pangea_userid);
$('.p_services_title').html(services_title);
$('.p_email').html(email);
$('.p_fullname').html(fullname);
$('.p_pangea_vault_id').html(pangea_vault_id);
$('.p_status').html(status);

$('.p_identity_value').val(id).value;
$('.p_email_value').val(email).value;
$('.p_fullname_value').val(fullname).value;
$('.p_services_title_value').val(services_title).value;
$('.p_pangea_vault_id_value').val(pangea_vault_id).value;
$('.p_pangea_userid_value').val(pangea_userid).value;

$('.p_diagnosis_value').val(diagnosis).value;
$('.p_medication_value').val(medication).value;
});

});





// clear Modal div content on modal closef closed
$(document).ready(function(){

$("#myModal_carto").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_empty').empty(); 
$('#q').val(''); 
});



$("#myModal_med").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_empty1').empty(); 
//$('#q').val(''); 
});





$("#myModal_diagnosis").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_empty2').empty(); 
//$('#q').val(''); 
});



$("#myModal_medication").on("hidden.bs.modal", function(){
    //$(".modal-body").html("");
 $('.mydata_empty3').empty(); 
//$('#q').val(''); 
});




});


</script>




<script>


   $(document).ready(function(){
//$(".reloadData").click(function(){
$(document).on( 'click', '.reloadData', function(){ 

location.reload();

});

});





$(document).ready(function(){

//$('.updates_btn').click(function(){
$(document).on( 'click', '.updates_btn', function(){ 

// confirm start
if(confirm("Are you sure you want to Mark this Appointments as Approved")){
var id = $(this).data('id');




$(".loader-updates_"+id).fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif"> &nbsp;Please Wait, Appointment Status is being Updated...</div>');
var datasend = {'id': id};
		$.ajax({
			
			type:'POST',
			url:'./backend/appointments_status_updates.php',
			data:datasend,
                         dataType: 'json',
                        crossDomain: true,
			cache:false,
			success:function(msg){

var status = msg['status'];
var message = msg['message'];
//alert(status);
//alert(message);



	if(message == 1){

//$(".loader-updates_"+id).hide();
//$(".result-updates_"+id).html("<div style='width: 90px;color:white;background:green;padding:10px;'>Updates  Successfully</div>");
//setTimeout(function(){ $(".result-updates_"+id).html(''); }, 5000);
//location.reload();

alert('Updates Successful');
$("#status_"+id).text(status);
$("#status1_"+id).text('Accepted');
$(".statuscolor_"+id).text('green_css');

$(".stx_"+id).html("<div style='width: 90px;font-size:12px;color:white;background:green;padding:6px;border:none;border-radius:15%;text-align:center;'>Accepted</div>");

$("#statushide_"+id).hide();
$("#statushide2_"+id).hide();

$(".loader-updates_"+id).hide();

}



}
			
});
}

// confirm ends

                });


            });





//Delete

$(document).ready(function(){

//$('.delete_btnx').click(function(){
$(document).on( 'click', '.delete_btnx', function(){ 

// confirm start
if(confirm("Are you sure you want to Delete this Medical Data From Pangea Vaults... ")){
var id = $(this).data('id');
var pang_id = $(this).data('pang_id');


$(".loader-delete_"+id).fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif"> &nbsp;Please Wait,Medical Data  is being Deleted from Pangea Vaults...</div>');
var datasend = {'id': id, pang_id:pang_id};
		$.ajax({
			
			type:'POST',
			url:'./backend/vault_data_delete.php',
			data:datasend,
                         dataType: 'json',
                        crossDomain: true,
			cache:false,
			success:function(msg){
//alert(msg.status );
if(msg.status == 0){
alert(msg.message);
$(".loader-delete_"+id).hide();
$(".result-delete_"+id).html("<div style='color:white;background:red;padding:10px;'>" +msg.message+ "</div>");
setTimeout(function(){ $(".result-delete_"+id).html(''); }, 5000);

}



	if(msg.status == 1){
alert(msg.message);
$(".loader-delete_"+id).hide();
$(".result-delete_"+id).html("<div style='color:white;background:green;padding:10px;'>" +msg.message+ "</div>");
setTimeout(function(){ $(".result-delete_"+id).html(''); }, 5000);
location.reload();

$(".rec_"+id).animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

}


}
			
});
}

// confirm ends

                });


            });

</script>




<style>
.full-screen-modal {
    width: 80%;
    height: 80%;
    margin: 0;
    top: 0;
    left: 0;
}



.red_css {
    background:red;
    color: white;
    padding: 6px;
border:none;
border-radius:15%;
text-align:center;
font-size:12px;
}

.green_css {
    background:green;
    color: white;
    padding: 6px;
border:none;
border-radius:15%;
text-align:center;
font-size:12px;
width: 90px;
}

.email_css{
background: navy;
color:white;
padding:6px;
cursor:pointer;
border:none;
font-size:12px;
//border-radius:25%;
//font-size:16px;
}

.email_css:hover{
background: black;
color:white;

}



.email_users_css{
background: fuchsia;
color:white;
padding:6px;
cursor:pointer;
border:none;
font-size:12px;

}

.email_users_css:hover{
background: black;
color:white;

}





.report_css{
//background: purple;
color:purple;
padding:4px;
cursor:pointer;
border:none;
font-size:12px;
//border-radius:25%;
//font-size:16px;
}

.report_css:hover{
background: black;
color:white;

}

</style>






<script>
$(document).ready(function(){

var get_content = 'get_data';
var backup_type = 'g';
if(get_content=="" && backup_type==""){
alert('There is an Issue with Cotent Database Retrieval');
}
else{
$('.loader_x').fadeIn(400).html('<br><div style="background:#ccc;color:black; width:100%;height:30%;text-align:center"><img src="ajax-loader.gif">&nbsp;Please Wait, Your Data is being Loaded</div>');
		
 var bck = $('#bc').DataTable({
  "processing":true,
  "serverSide":true,
  "order":[],
  "ajax":{
   url:"./backend/appointments_patients_all.php",
   type:"POST",
   data:{get_content:get_content, backup_type:backup_type}
  },
  "columnDefs":[
   {
    "orderable":false,
   },
  ],
  "pageLength": 10
 });

if(bck !=''){
$('.loader_x').hide();
}

}

 
});
</script>












<hr style="margin-top:1.5em">
<div style="text-align:center"><a href="#">.</a></div>


<div class='row'>


<div id="loader_vault"></div>
<div id="result_vault"></div>


</div>




</div>








<input type="hidden" class="p_identity_value pidx"  value="">

<input type="hidden" class="p_pangea_vault_id_value"  value="">
<input type="hidden" class="p_pangea_userid_value"  value="">

<input type="hidden" class="p_diagnosis_value"  value="">
<input type="hidden" class="p_medication_value"  value="">





<script>



$(document).ready(function(){

//$('.diagnosis_load_btn').click(function(){

$(document).on( 'click', '.diagnosis_load_btn', function(){ 


var appointment_id = $('.p_identity_value').val();
//alert(appointment_id);

if(appointment_id==""){
alert('Appointment ID cannot be Empty.');


}


else{


$('#loader_dia').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Loading Patients Medical Diagnosis From Pangea Vaults.</div>');
var datasend = {appointment_id:appointment_id};


$.ajax({
			
			type:'POST',
			url:'./backend/diagnosis_vault_load.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_dia').hide();
				//$('#result_dia').fadeIn('slow').prepend(msg);
$('#result_dia').html(msg);
$('#alertdata_dia').delay(7000).fadeOut('slow');


			
			}
			
		});
		
		}
					
})
});









// Retrieve Vaults Secret Medical Data

$(document).ready(function(){
//$('.get_btn').click(function(){
$(document).on( 'click', '.get_btn', function(){ 

var id = $(this).data('id');
//alert(id);


$(".loader-get_"+id).fadeIn(400).html('<br><div style="color:black;background:white;padding:10px;"><img src="ajax-loader.gif"> &nbsp;Please Wait, Retrieving Medical Data from Pangea Vault...</div>');
var datasend = {'id': id};
		$.ajax({
			
			type:'POST',
			url:'./backend/diagnosis_vault_retrieve.php',
			data:datasend,
dataType: 'json',
                        crossDomain: true,
			cache:false,
			success:function(msg){

if(msg.status == 1){
alert(msg.message);
$(".loader-get_"+id).hide();
$(".result-get_"+id).html("<div class='well alerts alert-info'><span> " +msg.secret+ "</span></div>");
//setTimeout(function(){ $(".result-get_"+id).html(''); }, 5000);

//$(".rec_"+id).animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

}


	if(msg.status == 0){

alert(msg.message);

$(".loader-get_"+id).hide();

$(".result-get_"+id).html("<div style='color:white;background:red;padding:8px;border:none;'>" +msg.message+ "</div>");
setTimeout(function(){ $(".result-get_"+id).html(''); }, 5000);

}

}
			
});





});
});






// Delete Vaults Medical Data

$(document).ready(function(){
//$('.deletez_btn').click(function(){
$(document).on( 'click', '.deletez_btn', function(){ 

var id = $(this).data('id');
//alert(id);

// confirm start
 if(confirm("Are you sure you want to Delete this Medical Data: ")){

$(".loader-deletez_"+id).fadeIn(400).html('<br><div style="color:black;background:white;padding:10px;"><img src="ajax-loader.gif"> &nbsp;Please Wait, Vault Data is being deleted...</div>');
var datasend = {'id': id};
		$.ajax({
			
			type:'POST',
			url:'./backend/diagnosis_vault_delete.php',
			data:datasend,
dataType: 'json',
                        crossDomain: true,
			cache:false,
			success:function(msg){


if(msg.status == 1){

//alert(msg.message);
$(".loader-deletez_"+id).hide();
$(".result-deletez_"+id).html("<div style='color:white;background:green;padding:8px;border:none;'>" +msg.message+ "</div>");
setTimeout(function(){ $(".result-deletez_"+id).html(''); }, 5000);
//location.reload();

$(".recz_"+id).animate({ backgroundColor: "#fbc7c7" }, "fast").animate({ opacity: "hide" }, "slow");

}


	if(msg.status == 0){

//alert(msg.message);

$(".loader-deletez_"+id).hide();
$(".result-deletez_"+id).html("<div style='color:white;background:red;padding:8px;border:none;'>" +msg.message+ "</div>");
setTimeout(function(){ $(".result-deletez_"+id).html(''); }, 5000);

}

}
			
});
}

// confirm ends




});
});

</script>












 <!-- Diagnosis Modal -->
  <div class="modal fade" id="myModal_diagnosis" role="dialog">
    <div class="modal-dialog  modal-appear-center1">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='background:purple;color:white;padding:6px;border:none;'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Patients Medical Conditions, Diagnosis & Drug Prescription System powered by Pangea Vaults</h4>
        </div>
        <div class="modal-body">


<script>



$(document).ready(function(){
$('#diagnosis_btn').click(function(){

var email = $('.p_email_valuex').val();
var fullname = $('.p_fullname_valuex').val();
var userid = $('.p_pangea_userid_value').val();
var med_services = $('.p_services_title_valuex').val();

var diagnosis= $('#diagnosis').val();

var diagnosis_count= $('.p_diagnosis_value').val();
var medication_count= $('.p_medication_value').val();
var appointment_id= $('.p_identity_value').val();

var med_dm = $(".med_dm:checked").val();




//alert(userid);
if(diagnosis==""){
alert('Diagnosis details cannot be Empty.');
return false;
}

 if(med_dm==undefined){
alert('please Select Either Diagnosis or Drug Prescription.');
return false;
}



/*
if(isNaN(discount)){
return false;
}
*/
if(diagnosis==""){
alert('Diagnosis details cannot be Empty.');
}


else{


$('#loader_diagnosis').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait, Patients Diagnosis Details is being Saved in Pangea Vaults.</div>');
var datasend = {med_dm:med_dm,diagnosis_count:diagnosis_count,medication_count:medication_count, appointment_id:appointment_id, email:email,fullname:fullname,userid:userid, med_services:med_services,diagnosis:diagnosis};


$.ajax({
			
			type:'POST',
			url:'./backend/patients_diagnosis_pangea_Vault.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_diagnosis').hide();
				//$('#result_diagnosis').fadeIn('slow').prepend(msg);
$('#result_diagnosis').html(msg);
$('#alertdata_diagnosis').delay(7000).fadeOut('slow');


$('#diagnosis').val('');

			
			}
			
		});
		
		}
		
	})
					
});




</script>





<input type="hidden" class="p_services_title_value p_services_title_valuex"  value="">
<input type="hidden" class="p_email_value p_email_valuex"  value="">
<input type="hidden" class="p_fullname_value p_fullname_valuex"  value="">


<div class='row'>
<div class='col-sm-12' style='background:#ddd;'>

<h4>Patients Medical Info</h4>


<b>Name: </b><span class='p_fullname'></span><br>
<b>Email: </b><span class='p_email'></span><br>
<b>Medical Appointment Services: </b><span class='p_services_title'></span><br>



               </div>


</div>


<br>


<div id="loader_dia"></div>
<div id="result_dia" class='mydata_empty2'></div>



<br>

<h5> Write Patients Medical Conditions OR  Prescribe Drugs and Store it in Pangea Vaults</h5>


 <div class="form-group">
<p><label>Medical Action</label><br>


<div class='col-sm-6 time_css'>
<input type="radio" id="med_dm" name="med_dm" value="Diagnosis" class="med_dm"/>Diagnosis(patients Medical Condtions)<br>
</div>
<div class='col-sm-6 time_css'>
<input type="radio" id="med_dm" name="med_dm" value="Drug Prescription" class="med_dm"/>Drug Prescription<br>
</div>

</div>


 <div class="form-group">
              <textarea class="col-sm-12 form-control diagnosis" id="diagnosis" cols="3" rows="3" placeholder="Write Patients Medical Conditions/Results"> </textarea>

            </div><br>





<div class="form-group">
<div id="loader_diagnosis" ></div>

<div id="result_diagnosis" class='mydata_empty2'></div>
<br />

<button type="button" id="diagnosis_btn" class="btn btn-primary" title='Submit'>Save to Pangea Vault</button>
</div>



     </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



<!-- The Modal diagnosis Ends -->










 <!-- email Modal -->
  <div class="modal fade" id="myModal_email" role="dialog">
    <div class="modal-dialog  modal-appear-center1">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='background:purple;color:white;padding:6px;border:none;'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Contact Patients Via Email</h4>
        </div>
        <div class="modal-body">


<script>



$(document).ready(function(){
$('#email_users_btn').click(function(){

var email_title = $('#email_title').val();		
var email_message = $('#email_message').val();
var email = $('.p_email_valuex').val();
var fullname = $('.p_fullname_valuex').val();
var userid = $('.p_identity_value').val();
var med_services = $('.p_services_title_valuex').val();
//alert(med_services);
/*
if(isNaN(discount)){
return false;
}
*/
if(email_message==""){
alert('Email Message cannot be Empty.');
$('.email_message_alert').html("<div class='alert alert-warning' style='color:red;'>Email Message Cannot be Empty.</div>");


}


else{


$('#loader_recxx').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait, Email is being sent in Progress.</div>');
var datasend = {email_title:email_title, email_message:email_message,email:email,fullname:fullname,userid:userid, med_services:med_services};


$.ajax({
			
			type:'POST',
			url:'<?php echo $site_urlx; ?>/backend/email_patients.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_recxx').hide();
				//$('#result_recxx').fadeIn('slow').prepend(msg);
$('#result_recxx').html(msg);
$('#alertdata_recxx').delay(7000).fadeOut('slow');
$('#alertdata_recxx').delay(7000).fadeOut('slow');

$('#email_title').val('');
$('#email_message').val('');
			
			}
			
		});
		
		}
		
	})
					
});




</script>





<input type="hidden" class="p_services_title_value p_services_title_valuex"  value="">
<input type="hidden" class="p_email_value p_email_valuex"  value="">
<input type="hidden" class="p_fullname_value p_fullname_valuex"  value="">


<div class='row'>
<div class='col-sm-12' style='background:#ddd;'>

<h4>Patients Medical Info</h4>


<b>Name: </b><span class='p_fullname'></span><br>
<b>Email: </b><span class='p_email'></span><br>
<b>Medical Appointment Services: </b><span class='p_services_title'></span><br>



               </div>


</div>


<br>

<h5> Send Email to User</h5><br>



 <div class="form-group">
           <b>Email Title</b>
              <input type='text' class="col-sm-12 form-control email_title" id="email_title" name="email_title" value="">

            </div>



 <div class="form-group">
           <b>Message</b>
              <textarea class="col-sm-12 form-control" id="email_message" name="email_message" ></textarea>

            </div>

<div class='email_message_alert mydata_empty'></div>





<div class="form-group">
<div id="loader_recxx" ></div>

<div id="result_recxx" class='mydata_empty'></div>
<br />

<button type="button" id="email_users_btn" class="btn btn-primary" title='Email User'>Email User</button>
</div>




<div id="loader_msg"></div>
<div id="result_msg"></div>




     </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



<!-- The Modal contact/email users Ends -->











 <!-- Med Pangea Vaults Modal  starts-->
  <div class="modal fade" id="myModal_med" role="dialog">
    <div class="modal-dialog  modal-appear-center1">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header" style='background:purple;color:white;padding:6px;border:none;'>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">View Patients Medical Appointments Details From Pangea Vaults</h4>
        </div>
        <div class="modal-body">


<script>



$(document).ready(function(){

//$('.med_btnx').click(function(){

$(document).on( 'click', '.med_btnx', function(){ 


var p_pangea_vault_id_value = $('.p_pangea_vault_id_value').val();

if(p_pangea_vault_id_value==""){
alert('Pangea Vault ID cannot be Empty.');


}


else{


$('#loader_med').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="loader.gif" style="font-size:20px"> &nbsp;Please Wait,  Loading Patients Medical Appointments Details From Pangea Vaults.</div>');
var datasend = {p_pangea_vault_id_value:p_pangea_vault_id_value};


$.ajax({
			
			type:'POST',
			url:'./backend/patients_info_vaults.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){


                        $('#loader_med').hide();
				//$('#result_med').fadeIn('slow').prepend(msg);
$('#result_med').html(msg);
$('#alertdata_med').delay(7000).fadeOut('slow');


			
			}
			
		});
		
		}
					
})
});



</script>




<div class='row'>
<div class='col-sm-12' style='background:#ddd;'>

<h4>Patients Medical Info</h4>


<b>Name: </b><span class='p_fullname'></span><br>
<b>Email: </b><span class='p_email'></span><br>
<b>Medical Appointment Services: </b><span class='p_services_title'></span><br>



               </div>


</div>


<br>

<h5> View Patients Medical Appointments Details From Pangea Vaults</h5><br>





<div id="loader_med"></div>
<div id="result_med" class='mydata_empty1'></div>




     </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>



<!-- The Modal Med Pangea Vaults Ends -->








<script>


// Books Appointments Secret Vaults


$(document).ready(function(){
$('#p_btn').click(function(){
//$(document).on( 'click', '.p_btn', function(){ 
		
var desc  =         $('#desc').val();
var title =  $(".title:checked").val();
var p_date  =  $('#p_date').val();
var p_time = $(".p_time:checked").val();


if(title==undefined){
alert('Please Select Medical Appointment Services');
//return false;
}


else if(desc==''){
alert('Please Enter Appointment Details');
//return false;
}




 else if(p_date==''){
alert('please Select Appointment date.');
}



 else if(p_time==undefined){
alert('please Select Appointment Time.');
}


else{
$('#loader_v').fadeIn(400).html('<br><br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif"> Please Wait! .Bookings is being created. MedicalInfo being Store in Pangea Vaults...</div>')



var datasend = {p_date:p_date, p_time:p_time, title:title, desc:desc};	
		$.ajax({
			
			type:'POST',
			url:'./backend/book_appointments.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
 		
$('#loader_v').hide();
$('#result_v').html(msg);
//setTimeout(function(){ $('#result_v').html(''); }, 9000);

$('#desc').val('');
$(".title:checked").val('');
$('#p_date').val('');
$(".p_time:checked").val();
//location.reload();
		

	}
			
		});
		
		}
	
	})
					
});



</script>

<!-- Vault  Modal start -->



<div id="myModal_vault" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header"  style='background: #008080;color:white;padding:10px;'>
        <h4 class="modal-title">Medical Appointment Booking System</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body">


Easily Book Medical Appointments and have Your Data Secures and Protected in <b>Pangea</b> highly secured Vaults System.<br><br>





        <div class="well">
    		<label>Medical Services</label><br>

<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Dental Care" class="title"/>Dental Care <br>
</div>


<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Eye Care" class="title"/>Eye Care <br>
</div>




<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Gynaecological Care" class="title"/>Gynaecological Care <br>
</div>


<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Paediatric Care" class="title"/>Paediatric Care <br>
</div>



<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Occulist Care" class="title"/>Occulist Care <br>
</div>


<div class='col-sm-4 time_css'>
<input type="radio" id="title" name="title" value="Lab Services" class="title"/>Lab Services<br>
</div>


</div>

<br>



 <div class="form-group">
              <label>Reasons for Appointments(Medical Details) </label>
              <textarea class="col-sm-12 form-control" cols="3" rows="3" id="desc" name="desc" placeholder="Reasons for Appointments(Medical Details)"></textarea>
            </div>
<br>




 <div class="form-group">
              <label>Appointment Date</label>
              <input type="date" class="col-sm-12 form-control" id="p_date" name="p_date" placeholder="Select Date">
            </div>
<br>





<style>
.time_css{
background:#ccc;padding:6px;border-radius:20%;
}

.time_css:hover{
background:orange;color:black;
}



</style>



    	


        <div role="" class="well">
    		<p><label>Appointment Time</label><br>


<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="10:00:00_10:00 AM" class="p_time"/>10:00 AM <br>
</div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="10:30:00_10:30 AM" class="p_time"/>10:30 AM <br>
</div>
<div class='col-sm-3 time_css'>
 <input type="radio" id="p_time" name="p_time" value="11:00:00_11:00 AM" class="p_time"/>11:00 AM <br>   
</div>

<div class='col-sm-3 time_css'>
 <input type="radio" id="p_time" name="p_time" value="11:30:00_11:30 AM" class="p_time"/>11:30 AM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="12:00:00_12:00 PM" class="p_time"/>12:00 PM <br> </div>
<div class='col-sm-3 time_css'> 
<input type="radio" id="p_time" name="p_time" value="12:30:00_12:30 PM" class="p_time"/>12:30 PM <br></div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="13:00:00_1:00 PM" class="p_time"/>1:00 PM <br>  </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="13:30:00_1:30 PM" class="p_time"/>1:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="14:00:00_2:00 PM" class="p_time"/>2:00 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="14:30:00_2:30 PM" class="p_time"/>2:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="15:00:00_3:00 PM" class="p_time"/>3:00 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="15:30:00_3:30 PM" class="p_time"/>3:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="16:00:00_4:00 PM" class="p_time"/>4:00 PM <br></div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="16:30:00_4:30 PM" class="p_time"/>4:30 PM <br> </div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="17:00:00_5:00 PM" class="p_time"/>5:00 PM <br>
</div>
<div class='col-sm-3 time_css'>
<input type="radio" id="p_time" name="p_time" value="17:30:00_5:30 PM" class="p_time"/>5:30 PM <br>
</div>



</p>



 <div class="form-group">
						<div id="loader_v"></div>
                        <div id="result_v" class="myform_clean_v"></div>
                    </div>

                    <input type="button" id="p_btn" class="pull-right btn btn-primary p_btn" value="Submit Appointments" />

<br>
      </div>
<br><br>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>

<!-- Vaults Modal ends -->











</div>

</body>
</html>
