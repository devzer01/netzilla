<?php
	//$permission_lv = array(1, 4);	//define type permission can open this page.
	//funcs::checkPermission(&$smarty, $permission_lv);	//check permission
	$permission_lv = array(1, 4, 8, 9); //jeab edited
	funcs::checkPermission($smarty, $permission_lv);	//check permission

	/*$_SESSION['sess_mobile_ver'] = true;
	if(!(isset($_SESSION['sess_mobile_ver'])))
	{
		header('Location: .');
		exit;
	}
	else
	{
		unset($_SESSION['sess_mobile_ver']);
	}*/

	$smarty->assign('text', funcs::getText($_SESSION['lang'], '$mobile_valid_success'));
	//select template file//
	$smarty->display('mobileverify_successful.tpl');
	//$smarty->display('index.tpl');
?>