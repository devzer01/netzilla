<?php
//check permission type//
$permission_lv = array(1);	//define type permission can open this page.
funcs::checkPermission($smarty, $permission_lv);	//check permission

if($_POST['coinsms']!="" && $_POST['coinemail']!="" && $_POST['freecoins']!="" && $_POST['coinVerifyMobile']!="")
{
	funcs::updateCoinPoint($_POST['coinsms'], $_POST['coinemail'], $_POST['freecoins'], $_POST['coinVerifyMobile']);
	header("Location: ".$_SERVER['HTTP_REFERER']);
	exit;
}

//coin data
$data = funcs::getCoinData();

// print_r($data);

//send data to template//
$smarty->assign('type_box', array(1 => "Admin", 2 => "VIP", 3 => "Premium", 4 => "Standard", 9 => "StudiAdmin"));
$smarty->assign('managecoin',$data);
//select template file//
$smarty->display('admin.tpl');
?> 