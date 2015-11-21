<?php
//check permission type//
$permission_lv = array(1, 8, 9);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission
//select template file//
$smarty->display('admin.tpl');
?>
