<?php
//error_reporting(0); 
$servername = "localhost";
$username = "root";
$password = "";
$port = 3306;
$db_name ="medcare_db";

try {
    $db = new PDO("mysql:host=$servername;dbname=$db_name;charset=utf8", $username);
//$db = new PDO("mysql:host=$servername;port=$port;dbname=$db_name;charset=utf8", $username, $password, $options);
    // set the PDO error mode to exception
    //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 //echo "Mysql Database Connected successfully."; 
}
catch(PDOException $e)
    {
   // echo "Connection failed: " . $e->getMessage();
echo "<div style='color:white;background:red;padding:10px;border:none;'>Mysql Database Connection Failed...Check Mysql Database Credentials and Internet as well</div>";
}






