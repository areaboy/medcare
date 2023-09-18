<?php
error_reporting(0);

session_start();
//include ('authenticate.php');

$userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));



ini_set('max_execution_time', 300); //300 seconds = 5 minutes
// temporarly extend time limit
set_time_limit(300);

include('data6rst.php');
$obj = (object) array('role' => 'style', 'type' => 'string');
$data[] = array('Your Medical Appointments Data Over Time', '-', $obj);




$result = $db->prepare("SELECT * FROM appointments");
$result->execute(array());
$rows = $result->fetch();
$total_a = $result->rowCount();



$resultc = $db->prepare("SELECT * FROM appointments where status='Open'");
$resultc->execute(array());
$rowsc = $resultc->fetch();
$counting_open = $resultc->rowCount();




$resultc = $db->prepare("SELECT * FROM appointments where status='Closed'");
$resultc->execute(array());
$rowsc = $resultc->fetch();
$counting_closedx = $resultc->rowCount();


$data[] = array('Total Appointments Over Time',(int)$total_a, 'purple');
$data[] = array('Total Appointments Awaiting Approval',(int)$counting_open, 'gold');
$data[] = array('Total Appointments Approved',(int)$counting_closedx, 'navy');


echo json_encode($data);

?>