

<!DOCTYPE html>
<html lang="en">

<head>
 
<title> </title>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="keywords" content="landing page, website design" />
  <script src="../scripts/jquery.min.js"></script>
  <script src="../scripts/bootstrap.min.js"></script>
<link type="text/css" rel="stylesheet" href="../scripts/bootstrap.min.css">

<link type="text/css" rel="stylesheet" href="../scripts/storex.css">

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
     
<li class="navbar-brand home_click imagelogo_li_remove" ><img title='logo' alt='logo' class="img-rounded imagelogo_data" src="../logo.png"></li>
    </div>
    <div class="collapse navbar-collapse" id="navgator">


      <ul class="nav navbar-nav navbar-right">


<li class="navgate">

<button class="invite_btnx btn btn-warning"><a style="color:white;" href='index.php' title='Dashboard'>Home</a></button>

</li>

 



      </ul>




    </div>
  </div>



</nav>


    </div><br /><br />

<!-- end column nav-->





<br><br><div class='well'>

<h3>Users IPs VPN Check</h3>

<?php


$id = $_GET['id'];

if($id ==''){

echo "<div  style='background:red;color:white;padding:10px;border:none;'>Direct Page Access Not Allowed...</div><br>";
exit();

}


echo "<div  style='background:red;color:white;padding:10px;border:none;'>$id</div><br>";

?>




<br>








</div>












</body>
</html>

