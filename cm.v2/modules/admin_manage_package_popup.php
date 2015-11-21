<?php
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if($_GET['package_id'])
{
	$package_rate = funcs::getExchangeRate($_GET['package_id']);
	$smarty->assign('package_rate', $package_rate);
}

$smarty->display('admin_manage_package_popup.tpl');
?>