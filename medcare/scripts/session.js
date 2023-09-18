


$(document).ready(function(){

 var userid =   sessionStorage.getItem("access_userid");
 var usertoken =   sessionStorage.getItem("access_usertoken");
 var fullname = sessionStorage.getItem("access_fullname");
 var email = sessionStorage.getItem("access_email");

/*
alert(userid);
alert(usertoken);
alert(fullname);
alert(email);
*/


if (usertoken== null || usertoken == undefined) {

  alert('You Must Login To Access this Page');
window.location='index.html';
//return false;
}

$('.fullname_sess').html(fullname);
$('.email_sess').html(email);

$('.userid_value').val(userid).value;
$('.fullname_value').val(fullname).value;

});

