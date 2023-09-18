
// User Login

$(document).ready(function(){
$('#login_btn').click(function(){
//$(document).on( 'click', '.login_btn', function(){ 
		
var password  = $('#password1').val();
var email  = $('#email1').val();



if(email==""){
alert('Please Enter Email Address');
//return false;   
}

else if(password==""){
alert('Please Enter Password');
//return false;   
}

else{
$('#loader_login').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif"> Please Wait! .Checking If  Your Email has been Breached using Pangea Security..</div>')

var datasend = {password:password, email:email};	
		$.ajax({
			
			type:'POST',
			url:'./backend/check_breached_email_login.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
 		
$('#loader_login').hide();
$('#result_login').html(msg);
//setTimeout(function(){ $('#result_login').html(''); }, 9000);


			}
			
		});
		
		}
	
	})
					
});





// Create User Medical Staff

$(document).ready(function(){
$('#signup_btnx').click(function(){
//$(document).on( 'click', '.signup_btnx', function(){ 
		
var password  =         $('#password').val();
var email  =            $('#email').val();
var password_confirm =  $('#password_confirm').val();
var first_name  =         $('#first_name').val();
var last_name  =         $('#last_name').val();
var phone_number  =         $('#phone_number').val();
var status  =         'Staff';
var profession  =  $('#profession').val();

var question  =         $('#question').val();
var answer  =         $('#answer').val();



if(/[a-z]+/.test(password) && /[A-Z]+/.test(password) && /\d+/.test(password) && password.length >= 8){
  //return true;
//alert('ok');
}
else{

alert("Your password needs Upper and lower case letters, numbers and a minimum 8 characters");
return false;
}


var special_character = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;

if(special_character.test(password)){
 // return true;
} else {


alert("Your password must contain atleast One Special Character Eg. !@#$%&* ");
  return false;
}


if(first_name==""){
alert('Please Enter First name');
//return false;
}


else if(email==""){
alert('Please Enter Email Address');
//return false;   
}

else if(password==""){
alert('Please Enter Password');
//return false;   
}

else if(password_confirm==""){
alert('Please Confirm Password');
//return false;
}


else if(password != password_confirm){
alert('Password Does not Match');
//return false;
}


else if(question ==''){
alert('Security Question Cannot be Empty');
//return false;
}

else if(answer ==''){
alert('Security Answer Cannot be Empty');
//return false;
}


else{
$('#loader_signup').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif"> Please Wait! .Checking If  Your Email has been Breached using Pangea Security...</div>')


var datasend = {question:question, answer:answer, first_name:first_name, last_name:last_name,phone_number:phone_number, password:password, email:email, password_confirm:password_confirm, status:status, profession:profession};	
		$.ajax({
			
			type:'POST',
			url:'./backend/check_breached_email_signup.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
 		
$('#loader_signup').hide();
$('#result_signup').html(msg);
//setTimeout(function(){ $('#result_signup').html(''); }, 9000);


//strip all html elemnts using jquery
var html_stripped = jQuery(msg).text();
//check occurrence of word (successful) from backend output already html stripped.
var Frombackend = html_stripped;
var bcount = (Frombackend.match(/successful/g) || []).length;
if(bcount > 0){
$('#password').val('');
$('#password_confirm').val('');
$('#email').val('');
$('#first_name').val('');
$('#answer').val('');

}
			}
			
		});
		
		}
	
	})
					
});








// Create User Patients

$(document).ready(function(){
$('#signup_btnxp').click(function(){
//$(document).on( 'click', '.signup_btnxp', function(){ 
		
var password  =         $('#passwordp').val();
var email  =            $('#emailp').val();
var password_confirm =  $('#password_confirmp').val();
var first_name  =         $('#first_namep').val();
var last_name  =         $('#last_namep').val();
var phone_number  =         $('#phone_numberp').val();
var status  =         'Patients';
var profession  =  'Patients';

var question  =         $('#questionp').val();
var answer  =         $('#answerp').val();




if(/[a-z]+/.test(password) && /[A-Z]+/.test(password) && /\d+/.test(password) && password.length >= 8){
  //return true;
//alert('ok');
}
else{

alert("Your password needs Upper and lower case letters, numbers and a minimum 8 characters");
return false;
}


var special_character = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;

if(special_character.test(password)){
 // return true;
} else {


alert("Your password must contain atleast One Special Character Eg. !@#$%&* ");
  return false;
}





if(first_name==""){
alert('Please Enter First name');
//return false;
}


else if(email==""){
alert('Please Enter Email Address');
//return false;   
}

else if(password==""){
alert('Please Enter Password');
//return false;   
}

else if(password_confirm==""){
alert('Please Confirm Password');
//return false;
}


else if(password != password_confirm){
alert('Password Does not Match');
//return false;
}


else if(question ==''){
alert('Security Question Cannot be Empty');
//return false;
}

else if(answer ==''){
alert('Security Answer Cannot be Empty');
//return false;
}




else{
$('#loader_signupp').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif">Please Wait! .Checking If  Your Email has been Breached using Pangea Security...</div>')



var datasend = {question:question, answer:answer, first_name:first_name, last_name:last_name,phone_number:phone_number,password:password, email:email, password_confirm:password_confirm, status:status, profession:profession};	
		$.ajax({
			
			type:'POST',
			url:'./backend/check_breached_email_signup.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
 		
$('#loader_signupp').hide();
$('#result_signupp').html(msg);
//setTimeout(function(){ $('#result_signup').html(''); }, 9000);


//strip all html elemnts using jquery
var html_stripped = jQuery(msg).text();
//check occurrence of word (successful) from backend output already html stripped.
var Frombackend = html_stripped;
var bcount = (Frombackend.match(/successful/g) || []).length;
if(bcount > 0){
$('#passwordp').val('');
$('#password_confirmp').val('');
$('#emailp').val('');
$('#first_namep').val('');
$('#answerp').val('');
}
			}
			
		});
		
		}
	
	})
					
});













// Reset Password


$(document).ready(function(){
$('#reset_password_btn').click(function(){
//$(document).on( 'click', '.reset_password_btn', function(){ 
		
var password  =         $('#passwordx').val();
var email  =            $('#emailx').val();
var password_confirm =  $('#password_confirmx').val();


if(/[a-z]+/.test(password) && /[A-Z]+/.test(password) && /\d+/.test(password) && password.length >= 8){
  //return true;
//alert('ok');
}
else{

alert("Your password needs Upper and lower case letters, numbers and a minimum 8 characters");
return false;
}


var special_character = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/;

if(special_character.test(password)){
 // return true;
} else {


alert("Your password must contain atleast One Special Character Eg. !@#$%&* ");
  return false;
}




if(email==""){
alert('Please Enter Email Address');
//return false;   
}

else if(password==""){
alert('Please Enter Password');
//return false;   
}

else if(password_confirm==""){
alert('Please Confirm Password');
//return false;
}


else if(password != password_confirm){
alert('Password Does not Match');
//return false;
}



else{
$('#loader_reset_password').fadeIn(400).html('<br><div style="color:black;background:#ddd;padding:10px;"><img src="ajax-loader.gif"> Please Wait! .Your Password is being Reset...</div>')



var datasend = {password:password, email:email};	
		$.ajax({
			
			type:'POST',
			url:'./backend/authn_password_reset.php',
			data:datasend,
                        crossDomain: true,
			cache:false,
			success:function(msg){
 		
$('#loader_reset_password').hide();
$('#result_reset_password').html(msg);
//setTimeout(function(){ $('#result_reset_password').html(''); }, 9000);


//strip all html elemnts using jquery
var html_stripped = jQuery(msg).text();
//check occurrence of word (successful) from backend output already html stripped.
var Frombackend = html_stripped;
var bcount = (Frombackend.match(/successful/g) || []).length;
if(bcount > 0){
$('#passwordx').val('');
$('#password_confirmx').val('');
$('#emailx').val('');

}
			}
			
		});
		
		}
	
	})
					
});





$(document).ready(function(){


$('#myModal_create_user').on('hidden.bs.modal', function() {
  $('.myform_clean_signup').empty();
 //alert("modal closed and content cleared");
});



$('#myModal_user_login').on('hidden.bs.modal', function() {
  $('.myform_clean_login').empty();
 //alert("modal closed and content cleared");
});

$('#myModal_reset_password').on('hidden.bs.modal', function() {
  $('.myform_clean_reset_password').empty();
 //alert("modal closed and content cleared");
});


$('#myModal_vault').on('hidden.bs.modal', function() {
  $('.myform_clean_v').empty();
 //alert("modal closed and content cleared");
});




});