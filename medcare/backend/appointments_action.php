
<?php 

if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

session_start();
$pangea_userid_sess =  htmlentities(htmlentities($_SESSION['uid'], ENT_QUOTES, "UTF-8"));



include('data6rst.php');

// get total count
$pstmt = $db->prepare('SELECT * FROM appointments where pangea_userid=:pangea_userid');
$pstmt->execute(array(':pangea_userid' =>$pangea_userid_sess));
$total_count = $pstmt->rowCount();

// ensure that they cotain only alpha numericals
if(strip_tags(isset($_POST["get_content"]))){
$get_content = strip_tags($_POST["get_content"]);
if($get_content == 'get_data'){

$sql_query = '';
$error = '';
$message='';
$response_bl = array();

$sql_query .= "SELECT * FROM appointments ";
if(strip_tags(isset($_POST["search"]["value"]))){

//$search_value= strip_tags($_POST["search"]["value"]);
$search_value1= strip_tags($_POST["search"]["value"]);
$search_value=  htmlentities(htmlentities($search_value1, ENT_QUOTES, "UTF-8"));
$sql_query .= 'WHERE (pangea_userid =:pangea_userid) AND  (fullname LIKE "%'.$search_value.'%"  OR  email LIKE "%'.$search_value.'%" OR status LIKE "%'.$search_value.'%")';


//$sql_query .= 'WHERE fullname LIKE "%'.$search_value.'%" ';
//$sql_query .= 'OR email LIKE "%'. $search_value.'%" ';
//$sql_query .= 'OR status LIKE "%'. $search_value.'%" ';
//$sql_query .= 'OR pangea_userid LIKE "%'. $search_value.'%" ';
  }

//ensure that order post is set
$start = $_POST['start'];
$length = $_POST['length'];
$draw= $_POST["draw"];
if(strip_tags(isset($_POST["order"]))){
$order_column = strip_tags($_POST['order']['0']['column']);
$order_dir = strip_tags($_POST['order']['0']['dir']);

$sql_query .= 'ORDER BY '.$order_column.' '.$order_dir.' ';
}
else{
$sql_query .= 'ORDER BY id DESC ';
}
if($length != -1){
$sql_query .= 'LIMIT ' . $start . ', ' . $length;
}

$pstmt = $db->prepare($sql_query);
$pstmt->execute(array(':pangea_userid' =>$pangea_userid_sess));
$rows_count = $pstmt->rowCount();

//$result = $pstmt->fetchAll();
//foreach($result as $row){
while($row = $pstmt->fetch()){
$rows1 = array();
$id = $row['id'];
$email = $row['email'];
$fullname = $row['fullname'];
$pangea_userid = $row['pangea_userid'];
$timing = $row["timing"];
$services_title = $row["services_title"];
$pangea_vault_id = $row["pangea_vault_id"];
$a_date = $row['a_date'];
$a_time = $row['a_time'];
$status = $row['status'];
$diagnosis = $row['diagnosis'];
$medication = $row['medication'];


$s_t= "<span style='color:purple;font-size:16px;'><b>$services_title</b></span>";
$com= "<span style='color:green;font-size:14px;'>$status</span>";

if($status == 'Open'){

$st = "Awaiting Doctors Approval";
$colorx ="red_css";
}else{

$st = "Approved";
$colorx ="green_css";

}



if($status == 'Open'){


                  
$rxc= "<div style='color:green;background:;padding:4px;font-size:12px;' id='status1_$id'></div>
<button style='display:none;' id='statushide_$id' title='Click to Approve Appointments' class='report_css updates_btn'
 data-id='$id'
 data-userid='$id'
 >
 Click to Approve Appointments</button>
 <div class='loader-updates_$id'></div>
   <div class='result-updates_$id'></div>";

}else{
$rxc="<div style='color:green;background:;padding:4px;font-size:12px;'>Approved";

}


//<span class="badge bg-success email_count">Email</span>


        

//$rows1[] = $fullname. "<br>($email)";

$rows1[] = $fullname;
$rows1[] = $s_t. '<br><br>
<button type="button"  class="btn btn-primary btn-xs btn_call med_btnx" data-toggle="modal" data-target="#myModal_med"
data-id="'. intval($row["id"]).'"
data-pangea_userid="'. strip_tags($row["pangea_userid"]).'"
data-email="'. strip_tags($row["email"]).'"
data-fullname="'. strip_tags($row["fullname"]).'"
data-services_title="'. strip_tags($row["services_title"]).'"
data-pangea_vault_id="'. strip_tags($row["pangea_vault_id"]).'"
data-status="'. strip_tags($row["status"]).'"
data-timing="'. strip_tags($row["timing"]).'"
data-diagnosis="'. strip_tags($row["diagnosis"]).'"
data-medication="'. strip_tags($row["medication"]).'"
>View Medical<br> Appointments Details<br> from Pangea Vaults </button>';

$rows1[] = $a_date;
$rows1[] = $a_time;
$rows1[] = '<span id="statushide2_'.$id.'" class="'.$colorx.'" > '.$st.' </span><span id="status_'.$id.'" class="stx_'.$id.'" > </span> <span>'.$rxc.'</span>';

$rows1[] = '<span data-livestamp="'.$timing.'"></span>';


 
$rows1[] = '

<button type="button"  class="btn btn-warning btn-xs btn_call diagnosis_load_btn"  data-toggle="modal" data-target="#myModal_diagnosis"
data-id="'. intval($row["id"]).'"
data-pangea_userid="'. strip_tags($row["pangea_userid"]).'"
data-email="'. strip_tags($row["email"]).'"
data-fullname="'. strip_tags($row["fullname"]).'"
data-services_title="'. strip_tags($row["services_title"]).'"
data-pangea_vault_id="'. strip_tags($row["pangea_vault_id"]).'"
data-status="'. strip_tags($row["status"]).'"
data-timing="'. strip_tags($row["timing"]).'"
data-diagnosis="'. strip_tags($row["diagnosis"]).'"
data-medication="'. strip_tags($row["medication"]).'"
>View Doctors  Diagnosis <br>OR Drug Prescription <br>From Pangea Vault </button>

';




$rows1[] = '
<div class="loader-delete_'. intval($row["id"]).'"></div>
   <div class="result-delete_'. intval($row["id"]).'"></div>
<button type="button"  data-id="'. intval($row["id"]).'"  data-pang_id="'. $row["pangea_vault_id"].'" class="btn btn-danger btn-xs delete_btnx">Delete Data from<br> Pangea Vaults</button>';


$response_bl[] = $rows1;
}

$data = array(
"draw"    => $draw,
"recordsTotal"  => $rows_count,
"recordsFiltered" => $total_count,
"data"    => $response_bl);
}// you can close this



 echo json_encode($data);
}



}
else{
echo "<div id='alertdata_uploadfiles' style='background:red;color:white;padding:10px;border:none;'>
Direct Page Access not Allowed<br></div>";
}




?>