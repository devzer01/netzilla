<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission(&$smarty, $permission_lv);	//check permission

if(isset($_POST['send']) && ($_POST['send'] != ''))
{
	$save = $_POST;
	$colnames = array_flip(DBconnect::get_col_names(TABLE_PAY_LOG));
	$save = array_intersect_key($save, $colnames);

	if(DBConnect::retrieve_value("SELECT ID FROM ".TABLE_PAY_LOG." WHERE ID='".$_GET['id']."' AND copy_from <> '0'"))
	{
		DBconnect::update_1D_row_with_1D_array(TABLE_PAY_LOG, $save, "ID", $_GET['id']);
	}
	header("location: ".$_POST['return_url']);
}

$log = DBConnect::assoc_query_1D("SELECT * FROM ".TABLE_PAY_LOG." WHERE ID=".$_GET['id']);

$smarty->assign('save', $log);
$smarty->display('admin.tpl');
?>