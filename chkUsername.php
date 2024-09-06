<?php
include_once("conn.php");
$name = $_GET['id'];

if(isset($_GET['staff_id']))
{
	$countChk = mysqli_num_rows(mysqli_query($conn,"select * from users where user_name = '$name' and staff_ref_id != ".$_GET['staff_id'])); 
}
else
{
	$countChk = mysqli_num_rows(mysqli_query($conn,"select * from users where user_name = '$name'")); 
}


if($countChk > 0)
{
	echo "<span style='color:red'>This username is already exists.</span>";
}

?>