<?php
if(isset($_SESSION['sess_id']))
	$gender = DBConnect::retrieve_value("SELECT gender FROM member WHERE id=".$_SESSION['sess_id']);
else
	$gender = 1;

$sql = "SELECT username, id, picturepath FROM member WHERE gender=".(($gender==2)?1:2)." AND isactive=1 AND fake=1 AND id>3 AND picturepath!='' ORDER BY RAND() LIMIT " . $_GET['total'];
$result = DBConnect::assoc_query_2D($sql);
$smarty->assign('result', $result);
$smarty->display('profile_list.tpl');
?>