<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

if(isset($_POST['copy']) && isset($_POST['copy']))
{
	$save = $_POST;
	//we get the column names
	$colnames = array_flip(DBconnect::get_col_names(TABLE_PAY_LOG));
	 //we delete everything that is not in the database
	$save = array_intersect_key($save, $colnames);
	 //we create the member and get the id from the creation
	DBconnect::assoc_insert_1D($save, TABLE_PAY_LOG);

	header("location: ".$_POST['return_url']);
}

$log = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_PAY_LOG." WHERE ID=".$_GET['id']);
/*
$log['recall'] = 0;
$log['payment_complete'] = 1;
$log['payday'] = date("Y-m-d H:i:s");
*/

$smarty->assign('save', $log);
$smarty->display('admin.tpl');
?>