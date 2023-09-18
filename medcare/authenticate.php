

<?php
	
//set session
if(!isset($_SESSION['token']) || (trim($_SESSION['token']) == '')) {
//$token=strip_tags($_GET['token']);
		header("location: index.php");
		exit();
	}


?>