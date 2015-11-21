<?php
//smarty paging//
$permission_lv = array(1, 4, 8, 9);
funcs::checkPermission($smarty, $permission_lv);
SmartyPaginate::connect(); //smarty paging connect
SmartyPaginate::setUrl("?action=".$_GET['action']."&type=".$_GET['type']); //smarty paging set URL

if(!isset($_GET['next']))
	SmartyPaginate::setCurrentItem(1); //go to first record

if(isset($_GET['do'])){
	$permission_lv = array(1, 8, 9);
	funcs::checkPermission($smarty, $permission_lv);
	$base_url = "http://stats.content.tv/content.php?contract=";
	$redir_url = $base_url.$_GET['do'];

	$sql = "SELECT COUNT(*) FROM webcam_log WHERE userid = ".$_SESSION['sess_id']." AND use_date = '".date("Y.m.d",time())."'";
	$row = DBconnect::get_nbr($sql);
	if ($row < 1){
		$sql = "INSERT INTO webcam_log
			SET use_date = NOW(),
			userid = ".$_SESSION['sess_id'];		
	}
	
	DBconnect::execute($sql);	
	
	header("location: $redir_url");
	exit;
}
//select template file//
$smarty->display('index.tpl');
?>